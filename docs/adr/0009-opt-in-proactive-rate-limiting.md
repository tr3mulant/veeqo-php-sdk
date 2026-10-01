# Proactive rate limiting is opt-in, per credential, and never handles 429s itself

Veeqo's leaky bucket (5 req/s, documented burst 100, observed ~56) belongs to one API key or access token. In a single process, the reactive retries (ADR 0001) absorb it. An Appstore app running many workers on one access token exhausts them and gets `RateLimited`, and only shared state across those workers prevents that. The SDK can't choose that store for the caller, so throttling is opt-in: the SDK ships the wiring and Veeqo's limits, and the caller supplies the store.

## Shape

- **Saloon's `rate-limit-plugin` (^2.5) is a hard dependency** on the `Veeqo` connector. When no store is passed, it stays disabled.
- **Turned on at construction:** `Veeqo::withAccessToken($token, rateLimitStore: $store)` and the same on `withApiKey()`. Any plugin `RateLimitStore` works (memory, file, PSR-16, Laravel cache, Redis). The connector stays immutable (ADR 0005). In Laravel, `veeqo.rate_limit_store` names a cache store for the `Veeqo` singleton.
- **Limits: `allow(25)->everySeconds(5)->sleep()`.** A fixed window can pass 2N requests across a boundary, so N = 25 caps the burst at 50, under the observed ~56, and holds 5/s sustained. Callers change it by subclassing and overriding `resolveLimits()`, with no SDK knob.
- **A full window sleeps** (up to ~5s). It never throws.
- **Keyed by `veeqo:` plus the SHA-256 of the credential**, computed internally, so the raw secret never reaches the store. Separate API keys on one Veeqo account get separate limits, matching Veeqo.
- **The plugin's 429 detection is off** (`$detectTooManyAttempts = false`). By default it locks the credential for 60s on any 429 without `Retry-After` (every Veeqo 429) and throws, which would block ADR 0001's retries. 429s stay with the reactive retries alone.
- **No jitter.** The shared window already stops woken workers from reaching Veeqo together. Revisit only if real workloads show retry storms.
- **`VeeqoOAuth` has no limiter.** It makes one exchange per Veeqo account and has no access token yet.

## Considered options

- Reactive only, with README guidance: every Appstore app re-derives the per-credential key, the 56-vs-100 burst and the 429-detection trap.
- On by default with a memory store: it adds overhead to every private integration and implies cross-process protection a memory store can't give.
- `allow(5)->everySeconds(1)`: safe, but it forfeits the burst. `allow(50)->everySeconds(10)`: a worst-case burst of 100, above what was observed.
- Throwing when the window is full: callers opt in for smooth throughput, not a new failure mode.
- `suggest` plus a `class_exists` check: a runtime failure path, and awkward under the PHPStan gate (ADR 0002).
- Keying by Veeqo account id: needs a `current_company` call, and is wrong when one account has several API keys.
- Keeping 429 detection with a 1s sleeping release: coordination the shared window already provides.

# Errors are SDK exceptions over Saloon's; only GET retries on connection failures and 5xx

Callers catch SDK-owned exceptions, not Saloon's. Every HTTP error is a `VeeqoRequestException`, which extends Saloon's `RequestException` (returned from the connector's `getRequestException()`), so Saloon's own handling, which only checks `RequestException`, keeps working. Retries are reactive and method-aware: a write is never resent after a failure that might have reached Veeqo, because a resent `POST` can create a duplicate order.

## Exceptions

- `VeeqoException`: marker interface on every SDK exception.
- `VeeqoRequestException extends RequestException`: concrete base for every HTTP error response; any status without a subclass below is thrown as this class.
  - Subclasses extend `VeeqoRequestException`, not Saloon's status classes, so `catch (Saloon\…\NotFoundException)` does not catch the SDK's 404:
  - `ValidationFailed` (400, 422), `Unauthorized` (401), `Forbidden` (403: plan or permission gate), `NotFound` (404), `RateLimited` (429 after retries run out), `ServerError` (5xx).
- Connection failures stay Saloon's `FatalRequestException`: there is no response to wrap.
- Every HTTP exception exposes `errorMessages(): list<string>`, flattening Veeqo's three body shapes: `{"error_messages": [..]}` as-is, `{"error_messages": "..."}` as one item, `{"status", "error"}` as `[error]`. JSON-encoded upstream errors inside a message stay strings.

## Retries

- **429: retried for every method.** Veeqo's leaky bucket refuses the request outright when full, so it was never processed and a resend can't duplicate.
- **Connection failures and 5xx: retried for `GET` only.** A timeout or 500 on a write may already have taken effect; the caller decides. Widen to `PUT`/`DELETE` only if fixtures prove Veeqo treats them as idempotent.
- **Budget: 1 request + 3 retries**, exponential backoff 1s, 2s, 4s (about 7s worst case). This is Saloon's `$tries = 4`, which counts the first request. `Retry-After` is not honoured: Saloon v4 ignores it and Veeqo's 429 doesn't send one (an empty gateway response with no rate-limit headers).
- No jitter: the opt-in proactive limiter (ADR 0009) is what keeps workers from piling onto Veeqo, and it adds no jitter either.
- Callers tune the policy through Saloon's own `$tries`, `$retryInterval` and `$useExponentialBackoff` on the connector or per request (e.g. a queue job setting `$tries = 1` to defer to the queue's retries). The SDK adds no retry API of its own.

## Considered options

- Exposing Saloon's exceptions directly: callers would depend on Saloon and get no typed error body.
- A standalone SDK hierarchy wrapping Saloon's: duplicates Saloon's hierarchy for no gain.
- Each subclass extending its Saloon status counterpart (`NotFound extends NotFoundException`) under a `VeeqoRequestException` interface: PHP's single inheritance rules out a shared base class, and the match is only partial (`ValidationFailed` spans 400 and 422, `ServerError` every 5xx), so `catch (BadRequestException)` would silently miss SDK 400s. One rule, "catch ours", beats a catch that works for some statuses only.
- Honouring `Retry-After` in our own `handleRetry()`: speculative (Veeqo never sends it) and stacks on Saloon's backoff sleep.
- An SDK `$retries` property (clearer name than `$tries`): a second knob for the same setting, ambiguous when both are set.

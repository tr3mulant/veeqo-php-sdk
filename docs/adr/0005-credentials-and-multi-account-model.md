# One connector per Veeqo account, built by credential kind; OAuth stops at the code exchange

Appstore apps act for many Veeqo accounts, one access token each; private integrations use one API key. Both are plain strings, so a constructor taking `string $apiKey` lets an access token through as an API key and fails only at Veeqo with a 401. The credential kind is therefore named at the call site, and the only thing it changes is the authenticator, so private integrations fall out of the Appstore design for free.

## Shape

- **Named constructors on the single `Veeqo` connector**: `Veeqo::withAccessToken($token)` (Saloon `TokenAuthenticator`, `Authorization: Bearer`) and `Veeqo::withApiKey($key)` (Saloon `HeaderAuthenticator`, `x-api-key`). The constructor is private; the secret parameter is `#[\SensitiveParameter]`. No credential value classes.
- **A connector is bound to one Veeqo account for its life.** No setter or `as($token)`: an Appstore app builds one connector per Veeqo account, so per-token state (rate limiting, middleware) can't leak between accounts in a shared worker. Saloon's per-request authentication remains for anyone who needs it.
- **No `base_url` setting.** Veeqo has no sandbox and tests use `MockClient`; a proxy subclasses and overrides `resolveBaseUrl()`.

## OAuth

- A separate `VeeqoOAuth` connector uses Saloon's `AuthorizationCodeGrant`: `new VeeqoOAuth($clientId, $clientSecret, $redirectUri)`. It builds the authorize URL (`app.veeqo.com/oauth/authorize`), generates and verifies `state`, and exchanges the code (`api.veeqo.com/oauth/token`, valid 10 minutes).
- The exchange returns Saloon's `OAuthAuthenticator` unchanged. Veeqo issues no refresh token and no expiry, so its `getAccessToken()` string is the permanent access token.
- The token response doesn't name the Veeqo account; the app calls `current_company` with the new token to learn which account to store it against. The SDK doesn't do this for it.
- Storing access tokens, routes and controllers are the app's. The README's "Appstore apps" section suggests a redirect route and a callback route (verify `state`, handle a declined grant, exchange, look up the account, store the token keyed by it, encrypted). An `Unauthorized` (401) on a stored token means the account revoked the app.

## Laravel

Bindings exist only when configured, never resolving to a connector with an empty credential:

- `Veeqo` singleton when `veeqo.api_key` is set (private integrations).
- `VeeqoOAuth` singleton when `veeqo.client_id` is set, with `veeqo.client_secret` and `veeqo.redirect_uri`. A singleton fits because client credentials identify the app, not a Veeqo account.
- Appstore apps call `Veeqo::withAccessToken()` themselves; there is no factory binding.

## Considered options

- One constructor taking an `AccessToken`/`ApiKey` object: same safety, two extra classes for what a constructor name already says.
- A connector per credential kind: duplicates the connector for a one-line authenticator difference.
- A shared connector switching accounts per call: cheaper to construct, but lets per-token state cross accounts.
- A `VeeqoFactory` binding: only wraps the named constructors; swapping in tests is `MockClient`'s job.
- Exchange also fetching `current_company`: a second request and a result type for one line of app code.
- Shipping Laravel routes for the OAuth callback: imposes our routes and middleware on the app.

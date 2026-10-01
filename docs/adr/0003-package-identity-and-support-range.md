# Package identity, support range and semver policy

The package is `irongateenterprises/veeqo-php-sdk` under the `IronGate\Veeqo` namespace. It is a company product, so it carries the company's name, not a personal account's; the GitHub repo moves from `tr3mulant` to an `irongateenterprises` org before the first Packagist release, so the vendor and repo match. A Packagist name can't be changed without abandoning the package, which is why this is settled first.

## Policy

- **PHP `^8.4`.** We never claim a PHP version CI can't test, and the dev tooling (Pest 5, PHPUnit 13) needs 8.4. It also brings property hooks and asymmetric visibility to typed responses.
- **Laravel: every version still in security support**, today `^12.0 || ^13.0`. Each is dropped once its security support ends.
- **Laravel is optional.** `illuminate/support` goes in `require-dev` and `suggest`, never `require`. A `conflict` entry blocks unsupported versions (`illuminate/support: <12.0`). There's no separate Laravel package while the layer is one service provider; revisit when it grows facades or fakes (the same trigger as Larastan in ADR 0002).
- **0.x until the core API is covered and verified against the live API**, then 1.0.0. In 0.x, minor releases may break, as Composer's `^0.x` allows.
- **Public API** is everything in `src/` not marked `@internal`.
- **Veeqo-driven changes follow semver from the user's side.** Adding a response field is a minor release. Removing or retyping one is a major release, even though Veeqo caused it, because it breaks users' PHPStan level 10 builds.
- **Dropping an end-of-life PHP or Laravel version is a minor release**, as Saloon and the Laravel ecosystem do.

## Considered options

- `tr3mulant/…` vendor: matches where the repo lives today, but ties a company product to a personal account forever.
- PHP `^8.2` or `^8.3`: reaches more users, but either the declared floor goes untested or the tooling has to drop back to Pest 4. PHP 8.2 stops getting security fixes in December 2026.
- A separate `veeqo-php-sdk-laravel` package: twice the release work for one class.
- 1.0 straight after the Orders slice: it would commit to a stable API before any of it had been checked against live Veeqo responses.

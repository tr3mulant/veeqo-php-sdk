<?php

namespace TremulantTech\Veeqo;

use Saloon\Contracts\OAuthAuthenticator;
use Saloon\Exceptions\InvalidStateException;
use Saloon\Helpers\OAuth2\OAuthConfig;
use Saloon\Http\Connector;
use Saloon\Http\Response;
use Saloon\Traits\OAuth2\AuthorizationCodeGrant;
use Saloon\Traits\Plugins\AcceptsJson;
use Throwable;
use TremulantTech\Veeqo\Exceptions\VeeqoRequestException;

class VeeqoOAuth extends Connector
{
    use AcceptsJson;
    use AuthorizationCodeGrant {
        getAccessToken as private exchangeCode;
        // Veeqo issues no refresh token and has no user endpoint; keep these off the public API.
        refreshAccessToken as private;
        getUser as private;
    }

    public function __construct(
        private readonly string $clientId,
        #[\SensitiveParameter] private readonly string $clientSecret,
        private readonly string $redirectUri,
    ) {}

    public function resolveBaseUrl(): string
    {
        return 'https://api.veeqo.com';
    }

    /**
     * Saloon skips the state check when either side is empty, so a state lost from the session
     * would let a forged callback through; here both must be present and equal.
     */
    public function getAccessToken(string $code, ?string $state, ?string $expectedState): OAuthAuthenticator
    {
        if ($state === null || $state === '' || $expectedState === null || ! hash_equals($expectedState, $state)) {
            throw new InvalidStateException;
        }

        return $this->exchangeCode($code);
    }

    public function getRequestException(Response $response, ?Throwable $senderException): VeeqoRequestException
    {
        return VeeqoRequestException::fromResponse($response, $senderException);
    }

    protected function defaultOauthConfig(): OAuthConfig
    {
        return OAuthConfig::make()
            ->setClientId($this->clientId)
            ->setClientSecret($this->clientSecret)
            ->setRedirectUri($this->redirectUri)
            ->setAuthorizeEndpoint('https://app.veeqo.com/oauth/authorize')
            ->setAllowBaseUrlOverride()
            ->setTokenEndpoint('oauth/token');
    }
}

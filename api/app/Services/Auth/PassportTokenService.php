<?php

namespace App\Services\Auth;

use App\Data\Auth\AuthTokenData;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

final readonly class PassportTokenService
{
    public function issuePasswordToken(string $email, string $password): AuthTokenData
    {
        return $this->requestToken(
            payload: $this->withClientCredentials([
                'grant_type' => 'password',
                'username' => $email,
                'password' => $password,
                'scope' => '',
            ]),
            errorField: 'email',
            errorMessage: __('auth.failed'),
        );
    }

    public function refresh(string $refreshToken): AuthTokenData
    {
        return $this->requestToken(
            payload: $this->withClientCredentials([
                'grant_type' => 'refresh_token',
                'refresh_token' => $refreshToken,
                'scope' => '',
            ]),
            errorField: 'refresh_token',
            errorMessage: 'Invalid refresh token.',
        );
    }

    /**
     * @param  array<string, string>  $payload
     * @return array<string, string>
     */
    private function withClientCredentials(array $payload): array
    {
        $payload['client_id'] = $this->clientId();

        $clientSecret = $this->clientSecret();

        if ($clientSecret !== null) {
            $payload['client_secret'] = $clientSecret;
        }

        return $payload;
    }

    /**
     * @param  array<string, string>  $payload
     *
     * @throws Exception
     */
    private function requestToken(array $payload, string $errorField, string $errorMessage): AuthTokenData
    {
        $response = app()->handle(Request::create(
            uri: '/oauth/token',
            method: 'POST',
            parameters: $payload,
            server: ['HTTP_ACCEPT' => 'application/json'],
        ));

        if (! $response->isSuccessful()) {
            $this->throwTokenException($response, $errorField, $errorMessage);
        }

        $data = json_decode($response->getContent(), true);

        if (
            ! is_array($data) ||
            ! isset(
                $data['access_token'],
                $data['refresh_token'],
                $data['token_type'],
                $data['expires_in'],
            )
        ) {
            throw ValidationException::withMessages([
                $errorField => ['Invalid OAuth token response.'],
            ]);
        }

        return new AuthTokenData(
            accessToken: (string) $data['access_token'],
            refreshToken: (string) $data['refresh_token'],
            tokenType: (string) $data['token_type'],
            expiresIn: (int) $data['expires_in'],
        );
    }

    private function throwTokenException(Response $response, string $field, string $fallbackMessage): never
    {
        $data = json_decode($response->getContent(), true);
        $error = is_array($data) ? $data['error'] ?? null : null;

        $message = match ($error) {
            'invalid_client' => 'Invalid OAuth client credentials.',
            'unsupported_grant_type' => 'Unsupported OAuth grant type.',
            default => $fallbackMessage,
        };

        throw ValidationException::withMessages([
            $field => [$message],
        ]);
    }

    private function clientId(): string
    {
        $clientId = config('passport.password_id');

        if (! is_string($clientId) || $clientId === '') {
            throw ValidationException::withMessages([
                'client' => ['Passport password client id is not configured.'],
            ]);
        }

        return $clientId;
    }

    private function clientSecret(): ?string
    {
        $clientSecret = config('passport.password_secret');

        if ($clientSecret === null || $clientSecret === '') {
            return null;
        }

        return (string) $clientSecret;
    }
}

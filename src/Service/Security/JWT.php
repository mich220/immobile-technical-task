<?php

declare(strict_types=1);

namespace App\Service\Security;

use App\Exception\Security\InvalidJWTSignatureException;
use App\Exception\Security\InvalidJWTTokenException;
use App\Exception\Security\JWTExpiredException;

class JWT
{
    public const JWT_TTL = 2592000; // 30 * 86400

    public function __construct(private readonly string $secret)
    {
    }

    public function encode(array $payload = []): string
    {
        $header = [
            'typ' => 'JWT',
            'alg' => 'HS256',
        ];

        $payload += [
            'exp' => \time() + self::JWT_TTL,
        ];

        $base64UrlHeader = $this->base64UrlEncode(json_encode($header));
        $base64UrlPayload = $this->base64UrlEncode(json_encode($payload));

        $signature = $this->generateSignature($base64UrlHeader, $base64UrlPayload);
        $base64UrlSignature = $this->base64UrlEncode($signature);

        return $base64UrlHeader.'.'.$base64UrlPayload.'.'.$base64UrlSignature;
    }

    public function decode(string $token): array
    {
        $tokenParts = \explode('.', $token);
        if (3 !== count($tokenParts)) {
            throw new InvalidJWTTokenException();
        }

        $header = \base64_decode($tokenParts[0]);
        $payload = \base64_decode($tokenParts[1]);
        $signatureProvided = $tokenParts[2];

        $base64UrlHeader = $this->base64UrlEncode($header);
        $base64UrlPayload = $this->base64UrlEncode($payload);
        $signature = $this->generateSignature($base64UrlHeader, $base64UrlPayload);
        $base64UrlSignature = $this->base64UrlEncode($signature);

        if ($base64UrlSignature !== $signatureProvided) {
            throw new InvalidJWTSignatureException();
        }

        $rawPayload = \json_decode($payload, true);

        if (\time() > $rawPayload['exp']) {
            throw new JWTExpiredException();
        }

        return $rawPayload;
    }

    private function generateSignature(string $base64UrlHeader, string $base64UrlPayload): string
    {
        return \hash_hmac('sha256', $base64UrlHeader.'.'.$base64UrlPayload, $this->secret, true);
    }

    private function base64UrlEncode(string $text): string
    {
        return \str_replace(
            ['+', '/', '='],
            ['-', '_', ''],
            \base64_encode($text)
        );
    }
}

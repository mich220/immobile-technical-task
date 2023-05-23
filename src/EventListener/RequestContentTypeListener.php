<?php

declare(strict_types=1);

namespace App\EventListener;

use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class RequestContentTypeListener
{
    public function __invoke(RequestEvent $request, $next): void
    {
        $request = $request->getRequest();
        $path = $request->getPathInfo();

        if (!str_starts_with($path, '/api')) {
            return;
        }

        $contentType = $request->headers->get('Content-Type');
        $acceptHeader = $request->headers->get('Accept');

        if (!$this->isAllowedContentType($contentType) || !$this->isAllowedAcceptHeader($acceptHeader)) {
            throw new BadRequestException(\sprintf('Only JSON and XML content types are allowed. Current: %s, %s', $contentType, $acceptHeader));
        }
    }

    private function isAllowedContentType(?string $contentType): bool
    {
        $allowedContentTypes = ['application/json', 'application/xml'];

        return in_array($contentType, $allowedContentTypes, true);
    }

    private function isAllowedAcceptHeader(?string $acceptHeader): bool
    {
        $allowedAcceptHeaders = ['application/json', 'application/xml', '*/*'];

        return in_array($acceptHeader, $allowedAcceptHeaders, true);
    }
}

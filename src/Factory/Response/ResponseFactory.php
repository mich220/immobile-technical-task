<?php

declare(strict_types=1);

namespace App\Factory\Response;

use App\Service\Response\ImmobileJsonResponse;
use App\Service\Response\ImmobileXmlResponse;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\SerializerInterface;

readonly class ResponseFactory
{
    public function __construct(private SerializerInterface $serializer)
    {
    }

    public function createResponse(
        string $acceptHeader,
        array $data = [],
        int $status = Response::HTTP_NO_CONTENT,
        array $headers = []
    ): ImmobileJsonResponse|ImmobileXmlResponse {
        return match ($acceptHeader) {
            'application/json', '*/*' => $this->createJsonResponse($data, $status, $headers),
            'application/xml' => $this->createXmlResponse($data, $status, $headers),
            default => throw new \InvalidArgumentException(\sprintf('Invalid content type header: %s', $acceptHeader)),
        };
    }

    public function createJsonResponse(
        array $data = [],
        int $status = Response::HTTP_NO_CONTENT,
        array $headers = []
    ): ImmobileJsonResponse {
        $headers = array_merge($headers, [
            'Content-Type' => 'application/json',
        ]);

        return new ImmobileJsonResponse(
            $data,
            $status,
            $headers
        );
    }

    public function createXmlResponse(
        array $data = [],
        int $status = Response::HTTP_NO_CONTENT,
        array $headers = []
    ): ImmobileXmlResponse {
        $headers = array_merge($headers, [
            'Content-Type' => 'application/xml',
        ]);
        $responseData = $this->serializer->serialize(
            $data,
            XmlEncoder::FORMAT,
            [
                XmlEncoder::ROOT_NODE_NAME => 'root',
                XmlEncoder::ENCODING => 'UTF-8',
            ]
        );

        return new ImmobileXmlResponse(
            $responseData,
            $status,
            $headers
        );
    }

    public function createSuccessMessage(
        string $acceptHeader,
        array $message,
        int $status = Response::HTTP_OK
    ): ImmobileJsonResponse|ImmobileXmlResponse {
        return $this->createResponse($acceptHeader, ['data' => $message], $status);
    }

    public function createFailureFormItemsMessage(string $acceptHeader, FormInterface $form): ImmobileJsonResponse|ImmobileXmlResponse
    {
        $errors = [];
        foreach ($form->getErrors(true) as $error) {
            $errors[$error->getOrigin()->getErrors()->getForm()->getName()][] = $error->getMessage();
        }

        return $this->createResponse($acceptHeader, ['error' => ['items' => $errors]], Response::HTTP_CONFLICT);
    }

    public function createFailureMessage(
        string $acceptHeader,
        array|string $message,
        int $status = Response::HTTP_CONFLICT
    ): ImmobileJsonResponse|ImmobileXmlResponse {
        $errorData = [
            'error' => [
                'message' => $message,
            ],
        ];

        return $this->createResponse($acceptHeader, $errorData, $status);
    }
}

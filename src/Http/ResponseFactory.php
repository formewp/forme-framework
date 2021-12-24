<?php
declare(strict_types=1);

namespace Forme\Framework\Http;

use Laminas\Diactoros\Response\EmptyResponse;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;

class ResponseFactory
{
    /** @param mixed $response */
    public static function create($response = ''): ResponseInterface
    {
        if (is_iterable($response)) {
            return new JsonResponse($response);
        }

        if (empty($response)) {
            return new EmptyResponse();
        }

        if ($response instanceof ResponseInterface) {
            return $response;
        }

        return new HtmlResponse($response);
    }
}

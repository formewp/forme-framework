<?php
declare(strict_types=1);

namespace Forme\Framework\Http;

use function Http\Response\send;
use Psr\Http\Message\ResponseInterface;

/**
 * This functionality has been borrowed from Lumberjack's application class.
 */
class Shutdown
{
    public function shutdown(ResponseInterface $response = null): void
    {
        if ($response) {
            global $wp;
            $wp->send_headers();

            // If we're handling a WordPressController response at this point then WordPress will already have
            // sent headers as it happens earlier in the lifecycle. For this scenario we need to do a bit more
            // work to make sure that duplicate headers are not sent back.
            send($this->removeSentHeadersAndMoveIntoResponse($response));
        }

        exit();
    }

    private function removeSentHeadersAndMoveIntoResponse(ResponseInterface $response): ResponseInterface
    {
        // 1. Format the previously sent headers into an array of [key, value]
        // 2. Remove all headers from the output that we find
        // 3. Filter out any headers that would clash with those already in the response
        $headersToAdd = collect(headers_list())->map(function ($header) {
            $parts = explode(':', $header, 2);
            header_remove($parts[0]);

            return $parts;
        })->filter(function ($header) {
            return !in_array(strtolower($header[0]), ['content-type']);
        });

        // Add the previously sent headers into the response
        // Note: You can't mutate a response so we need to use the reduce to end up with a response
        // object with all the correct headers
        /** @var ResponseInterface */
        $responseToSend = collect($headersToAdd)->reduce(function (ResponseInterface $newResponse, array $header) {
            return $newResponse->withAddedHeader($header[0], $header[1]);
        }, $response);

        return $responseToSend;
    }
}

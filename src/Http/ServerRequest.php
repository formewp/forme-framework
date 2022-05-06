<?php
declare(strict_types=1);

namespace Forme\Framework\Http;

use ArrayAccess;
use Laminas\Diactoros\ServerRequest as DiactorosServerRequest;
use Psr\Http\Message\ServerRequestInterface;
use Rareloop\Psr7ServerRequestExtension\InteractsWithInput;
use Rareloop\Psr7ServerRequestExtension\InteractsWithUri;

/**
 * @implements ArrayAccess<string, mixed>
 */
class ServerRequest extends DiactorosServerRequest implements ArrayAccess
{
    use InteractsWithInput;
    use InteractsWithUri;
    use ArraysRequestInput;

    public static function fromRequest(ServerRequestInterface $request): ServerRequestInterface
    {
        $parsedBody = $request->getParsedBody();

        // Is this a JSON request?
        if (stripos($request->getHeaderLine('Content-Type'), 'application/json') !== false) {
            $parsedBody = @json_decode($request->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);
        }

        return new static(
            $request->getServerParams(),
            $request->getUploadedFiles(),
            $request->getUri(),
            $request->getMethod(),
            $request->getBody(),
            $request->getHeaders(),
            $request->getCookieParams(),
            $request->getQueryParams(),
            $parsedBody,
            $request->getProtocolVersion()
        );
    }

    public function ajax(): bool
    {
        if (!$this->hasHeader('X-Requested-With')) {
            return false;
        }

        return $this->getHeader('X-Requested-With')[0] === 'XMLHttpRequest';
    }

    public function getMethod(): string
    {
        return strtoupper(parent::getMethod());
    }
}

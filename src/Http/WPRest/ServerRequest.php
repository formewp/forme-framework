<?php

namespace Forme\Framework\Http\WPRest;

use ArrayAccess;
use Forme\Framework\Http\ArraysRequestInput;
use Psr\Http\Message\ServerRequestInterface;
use Rareloop\Psr7ServerRequestExtension\InteractsWithInput;
use Rareloop\Psr7ServerRequestExtension\InteractsWithUri;
use WPRestApi\PSR7\WP_REST_PSR7_ServerRequest as WPRestServerRequest;

class ServerRequest extends WPRestServerRequest implements ServerRequestInterface, ArrayAccess
{
    use InteractsWithInput;
    use InteractsWithUri;
    use ArraysRequestInput;
}

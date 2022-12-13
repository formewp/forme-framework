<?php
declare(strict_types=1);

namespace Forme\Framework\Controllers\Cypress;

use Forme\Framework\Controllers\AbstractController;
use Laminas\Diactoros\Response\JsonResponse;

final class RunPhp extends AbstractController
{
    public function __invoke($request)
    {
        $code       = $request->input('command');

        if ($code[-1] !== ';') {
            $code .= ';';
        }

        if (!str_contains($code, 'return')) {
            $code = 'return ' . $code;
        }

        return new JsonResponse([
            'result' => eval($code),
        ]);
    }
}

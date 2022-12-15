<?php
declare(strict_types=1);

namespace Forme\Framework\Controllers\Cypress;

use Forme\Framework\Controllers\AbstractController;
use Laminas\Diactoros\Response\JsonResponse;
use Symfony\Component\String\UnicodeString;

final class Create extends AbstractController
{
    public function __invoke($request)
    {
        $model               = $request->input('model');
        $attributes          = $request->input('attributes') ?? [];

        $attributesConverted = [];

        // convert the keys to snake_case
        foreach ($attributes as $key => $value) {
            $attributesConverted[(new UnicodeString($key))->snake()->toString()] = $value;
        }

        if (method_exists($model, 'factory')) {
            $instance = $model::factory()->create($attributesConverted);
        } else {
            $instance = $model::create($attributesConverted);
        }

        return new JsonResponse($instance);
    }
}

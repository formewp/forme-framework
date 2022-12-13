<?php
declare(strict_types=1);

namespace Forme\Framework\Controllers\Cypress;

use Forme\Framework\Controllers\AbstractController;
use Jawira\CaseConverter\CaseConverter;
use Laminas\Diactoros\Response\JsonResponse;

final class Create extends AbstractController
{
    public function __construct(private CaseConverter $caseConverter)
    {
    }

    public function __invoke($request)
    {
        $model               = $request->input('model');
        $attributes          = $request->input('attributes') ?? [];

        $attributesConverted = [];

        // convert the keys to snake_case
        foreach ($attributes as $key => $value) {
            $attributesConverted[$this->caseConverter->convert($key)->toSnake()] = $value;
        }

        if (method_exists($model, 'factory')) {
            $instance = $model::factory()->create($attributesConverted);
        } else {
            $instance = $model::create($attributesConverted);
        }

        return new JsonResponse($instance);
    }
}

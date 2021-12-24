<?php
declare(strict_types=1);

namespace Forme\Framework\View;

use function Forme\getInstance;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;
use Twig\TwigFunction;

final class TwigExtension extends AbstractExtension implements GlobalsInterface
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('fn', [getInstance(TwigAdhocFunction::class), 'exec']),
            new TwigFunction('function', [getInstance(TwigAdhocFunction::class), 'exec']),
        ];
    }

    public function getGlobals(): array
    {
        return [
            'user' => wp_get_current_user(),
        ];
    }
}

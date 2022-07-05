<?php
declare(strict_types=1);

namespace Forme\Framework\Enums;

use Spatie\Enum\Enum;
use Spatie\Enum\Exceptions\UnknownEnumProperty;

/**
 * @property string $name
 * @property string $key
 */
abstract class AcfEnum extends Enum
{
    /** @var array */
    protected $fieldNames = [];

    public function acfName(): string
    {
        return $this->fieldNames[$this->value];
    }

    /**
     * @return int|string
     *
     * @throws UnknownEnumProperty
     */
    public function __get(string $name)
    {
        return match ($name) {
            'name'   => $this->acfName(),
            'key'    => $this->value,
            'value'  => $this->value,
            'label'  => $this->label,
            default  => throw new UnknownEnumProperty($name),
        };
    }
}

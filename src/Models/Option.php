<?php
declare(strict_types=1);

namespace Forme\Framework\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $option_name
 * @property string $name
 * @property string $value
 * @property string $option_value
 * @property int    $option_id
 * @property string $autoload
 */
class Option extends Model
{
    protected $table = 'options';

    protected $primaryKey = 'option_id';

    public function getIdAttribute(): int
    {
        return $this->option_id;
    }

    public function getNameAttribute(): string
    {
        return $this->option_name;
    }

    public function setNameAttribute(string $value): void
    {
        $this->option_name = $value;
    }

    public function setValueAttribute(string $value): void
    {
        $this->option_value = $value;
    }

    public function getValueAttribute(): string
    {
        return $this->option_value;
    }
}

<?php

declare(strict_types=1);

namespace Forme\Framework\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int    $id
 * @property array  $payload
 * @property string $type
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
final class Event extends Model
{
    /**
     * @var string
     */
    protected $table = 'forme_events';

    /** @phpstan-ignore-next-line */
    public $timestamps = ['created_at'];

    public const UPDATED_AT = null;

    /**
     * @var string[]
     */
    protected $fillable = ['type', 'payload', 'created_at'];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'payload' => 'array',
     ];
}

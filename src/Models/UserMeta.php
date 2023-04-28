<?php
declare(strict_types=1);

namespace Forme\Framework\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int    $umeta_id
 * @property int    $user_id
 * @property User   $user
 * @property string $meta_key
 * @property string $meta_value
 */
class UserMeta extends Model
{
    protected $primaryKey = 'umeta_id';

    public $timestamps = false;

    protected $table = 'usermeta';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'ID');
    }
}

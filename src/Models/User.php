<?php
declare(strict_types=1);

namespace Forme\Framework\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $primaryKey = 'ID';

    public $timestamps = false;

    protected $table = 'users';

    /**
     * Relation for user meta.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function meta()
    {
        return $this->hasMany('Forme\Framework\Models\UserMeta', 'user_id');
    }
}

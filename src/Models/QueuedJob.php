<?php
declare(strict_types=1);

namespace Forme\Framework\Models;

use Illuminate\Database\Eloquent\Model;

class QueuedJob extends Model
{
    protected $table = 'forme_queued_jobs';
}

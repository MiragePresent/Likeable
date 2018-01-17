<?php

namespace MiragePresent\Likeable;

use Illuminate\Database\Eloquent\Model;

/**
 * Like model
 *
 * @property int $id
 * @property int $user_id
 * @property int $likable_id
 * @property string $likable_type
 *
 * @mixin \Eloquent
 */

class Like extends Model
{

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'likable_id',
        'likable_type'
    ];

}

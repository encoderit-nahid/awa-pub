<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JuryCategoryPermission extends Model
{
    protected $table = 'jury_category_permissions';
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id',
        'cat_id',
    ];

    public function user()
    {
        return $this->belongsTo(\App\User::class, 'user_id');
    }

    public function category()
    {
        return $this->belongsTo(\App\Cat::class, 'cat_id');
    }
}

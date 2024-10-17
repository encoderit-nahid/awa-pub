<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Cat extends Model
{

    use LogsActivity, SoftDeletes;

    protected $table = 'cats';
    protected $primaryKey = 'id';

    protected $fillable = ['name', 'code', 'count', 'text', 'ort', 'hints', 'beschreibung', 'fulldescription', 'words', 'extra', 'referenz', 'group', 'stat', 'deleted_at'];

    protected static $logAttributes = ['name', 'code', 'count', 'text', 'ort', 'hints', 'beschreibung', 'words', 'extra', 'referenz', 'group', 'stat', 'deleted_at'];

    public function project(): \Illuminate\Database\Eloquent\Relations\HasMany
    {

        return $this->hasMany('App\Project');
    }

    public function juryCategoryPermission()
    {
        return $this->hasMany(\App\JuryCategoryPermission::class, 'cat_id');
    }

    public function user()
    {
        return $this->belongsTo(\App\User::class, 'user_id');
    }

}

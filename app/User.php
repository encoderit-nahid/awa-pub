<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Activitylog\Traits\LogsActivity;

class User extends Authenticatable
{
    use LogsActivity, Notifiable, SoftDeletes;

    protected $table = 'users';
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'vorname', 'firma', 'anr', 'agb', 'newsletter', 'datenschutz', 'voucher', 'instagram', 'teilnahmebedingung',
    ];
    protected static $logAttributes = [
        'name', 'email'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    public function projects()
    {
        return $this->hasMany(\App\Project::class, 'user_id');
    }

    public function firstRoundEvaluation()
    {
        return $this->hasMany(\App\FirstRoundEvaluation::class, 'jury_id');
    }
}

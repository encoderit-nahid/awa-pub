<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Count extends Model
{
  use LogsActivity;

  protected $table = 'counts';
  protected $primaryKey = 'id';

  protected $fillable = ['project_id', 'user_id', 'counts', 'deleted_at'];

  protected static $logAttributes = ['project_id', 'user_id', 'counts', 'deleted_at'];

  public function project()
    {
        return $this->belongsTo(\App\Project::class, 'project_id');
    }

    public function user()
      {
          return $this->belongsTo(\App\User::class, 'user_id');
      }
}
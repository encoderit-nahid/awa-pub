<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Project extends Model
{
  use LogsActivity;

  protected $table = 'projects';
  protected $primaryKey = 'id';

  protected $fillable = ['user_id', 'name', 'projektname', 'cat_id', 'cat_name', 'group', 'beschreibung', 'youtube', 'copyright', 'testimonial', 'check', 'ort', 'datum', 'stat', 'deleted_at', 'extra', 'jury', 'is_paid'];

  protected static $logAttributes = ['user_id', 'name', 'projektname', 'cat_id', 'cat_name', 'group', 'beschreibung', 'youtube', 'copyright', 'testimonial', 'check', 'ort', 'datum', 'stat', 'deleted_at', 'extra', 'jury', 'is_paid'];

  /*
  public function user() {
        return $this->belongsTo('App\User');
        return $this->hasOne('App\User');
  }
  */
  /*public function cat() {
        return $this->belongsTo('App\Cat');
        return $this->hasOne('App\Cat');
  }
*/
  public function image()
  {
    // return $this->belongsTo('App\Image');
    return $this->hasMany('App\Image');
  }

  public function count()
  {

    return $this->hasMany('App\Count', 'project_id');
  }

  public function publicVoteCount()
  {

    return $this->hasMany('App\PublicVoteCount', 'project_id');
  }

  public function rolleFiveCount()
  {

    return $this->hasOne('App\RolleFiveCount', 'project_id');
  }

  public function images()
  {
    return $this->hasMany(\App\Image::class, 'project_id');
  }

  public function user()
  {
    return $this->belongsTo(\App\User::class, 'user_id');
  }

  public function cat()
  {
    return $this->belongsTo(\App\Cat::class, 'cat_id');
  }

  public function scopeWithImages($queries) {
    return $queries->with(['images' => function ($imageQ) {
      return $imageQ->select(['id', 'project_id', 'url', 'thumb_url'])->inRandomOrder();
    }]);
  }
}
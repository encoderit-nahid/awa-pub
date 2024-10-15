<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Image extends Model
{
	use LogsActivity;
	protected $table = 'images';

	protected $fillable = ['project_id', 'filename', 'url', 'thumb_url', 'filesize', 'state', 'deleted_at'];

	protected static $logAttributes = ['project_id', 'filename', 'url', 'thumb_url', 'filesize', 'state', 'deleted_at'];

  public function project()
    {
        return $this->belongsTo(\App\Project::class, 'project_id');
    }
}

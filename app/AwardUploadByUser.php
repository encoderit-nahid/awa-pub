<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class AwardUploadByUser extends Model
{

  use LogsActivity;

  protected $table = 'award_upload_by_user';
  protected $primaryKey = 'id';

  protected $fillable = ['projektname', 'title', 'description'];

  protected static $logAttributes = ['projektname', 'title', 'description'];

}

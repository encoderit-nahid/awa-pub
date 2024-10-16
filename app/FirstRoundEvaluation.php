<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FirstRoundEvaluation extends Model
{
//    use SoftDeletes;

    protected $table = 'first_round_evaluation';

    protected $primaryKey = 'id';

    protected $fillable = ['jury_id', 'project_id', 'status'];

}

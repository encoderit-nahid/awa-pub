<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FirstRoundEvaluation extends Model
{
    protected $table = 'first_round_evaluation';

    protected $primaryKey = 'id';

    protected $fillable = ['jury_id', 'project_id', 'status'];

}

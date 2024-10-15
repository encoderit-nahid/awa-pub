<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaypalPayment extends Model
{
    protected $fillable = ['project_id', 'tnx_id', 'payer_id', 'payer_name', 'payer_email', 'amount', 'currency', 'status',];
}

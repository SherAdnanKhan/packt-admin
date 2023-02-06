<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Tax extends Model
{
    protected $table = 'taxes';
    protected $fillable = ['country_id', 'state_id', 'city_id', 'cat_type', 'type', 'amount'];
}

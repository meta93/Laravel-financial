<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CountryModel extends Model
{
    protected $table='apps_countries';
    protected $guarded = array('id');
}

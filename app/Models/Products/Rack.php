<?php

namespace App\Models\Products;

use Illuminate\Database\Eloquent\Model;

class Rack extends Model
{
    protected $table= 'racks';
    protected $guarded = array('id');

    public function godown()
    {
        return $this->belongsTo(Godown::class);
    }
}

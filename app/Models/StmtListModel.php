<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StmtListModel extends Model
{
    protected $table='stmt_lists';
    protected $guarded = array('id');

//    protected $fillable = ['compCode', 'fileNo', 'fileDesc','importFile','importLine','intoLine','user_id'];
}

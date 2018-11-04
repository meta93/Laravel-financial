<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class UserActionModel extends Model
{
    protected $table= 'user_actions';
    protected $fillable = ['user_id','compCode', 'action', 'action_model', 'action_id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

<?php
/**
 * Created by PhpStorm.
 * User: ubuntu
 * Date: 8/14/17
 * Time: 2:04 AM
 */

namespace App\Observers;

use App\Models\UserActionModel;
use App\User;
use Illuminate\Support\Facades\Auth;

class UserActionsObserver
{
    public function saved($model)
    {
        if ($model->wasRecentlyCreated == true) {
            // Data was just created
            $action = 'created';
        } else {
            // Data was updated
            $action = 'updated';
        }
        if (Auth::check()) {
            UserActionModel::create([
                'user_id'      => Auth::user()->id,
                'compCode'     => Auth::user()->compCode,
                'action'       => $action,
                'action_model' => $model->getTable(),
                'action_id'    => $model->id
            ]);
        }
    }

    public function deleting($model)
    {
        if (Auth::check()) {
            UserActionModel::create([
                'user_id'      => Auth::user()->id,
                'compCode'     => Auth::user()->compCode,
                'action'       => 'deleted',
                'action_model' => $model->getTable(),
                'action_id'    => $model->id
            ]);
        }
    }

}
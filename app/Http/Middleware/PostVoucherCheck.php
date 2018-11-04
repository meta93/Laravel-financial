<?php

namespace App\Http\Middleware;

use App\Models\TransactionModel;
use Closure;
use Illuminate\Support\Facades\Auth;

class PostVoucherCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {


        if(!is_null($request->voucherNo))
        {
            $data = TransactionModel::where('compCode',Auth::user()->compCode)->where('voucherNo',$request->voucherNo)->get();


            if(count($data) != 0)
            {
//                dd($data);
                if($data[0]->postFlag == true)
                {
                    return redirect()->back()->with($request->session()->flash('alert-danger', 'Voucher Already Posted'));
                }else
                {
                    $request->route()->setParameter('data', $data);
                    return $next($request);
                }
            }

            return redirect()->back()->with($request->session()->flash('alert-danger', 'Voucher Not Found'));
        }

        return $next($request);

    }
}

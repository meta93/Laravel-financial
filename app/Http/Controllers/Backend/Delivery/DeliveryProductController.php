<?php

namespace App\Http\Controllers\Backend\Delivery;

use App\Models\Common\TransProduct;
use App\Models\Inventory\Delivery;
use App\Models\Inventory\ItemMovement;
use App\Models\Inventory\Sales;
use App\Models\Products\Product;
use App\Models\UserPrivilegesModel;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;
use Response;
class DeliveryProductController extends Controller
{

    public $comp_code;
    public $user_id;

    /**
     * DeliveryProductController constructor.
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->comp_code = Auth::user()->compCode;
            $this->user_id = Auth::user()->id;

            return $next($request);
        });
    }

    public function index()
    {
        $userPr = UserPrivilegesModel::where('email',Auth::user()->email)
            ->where('compCode',Auth::user()->compCode)
            ->where('useCaseId','P02')->first();

        if($userPr->view == 0)
        {
            session()->flash('alert-danger', 'You do not have permission. Please Contact Administrator');
            return redirect()->back();
            die();
        }

        return view('backend.delivery.deliveryproductindex')->with('userPr',$userPr);

    }

    public function getinvoicedata()
    {
        $invoice = Sales::query()->where('compCode',Auth::user()->compCode)->where('status',2)->with('relationship');



        return Datatables::of($invoice)

            ->editColumn('type',function ($invoice) { return $invoice->type == 'L' ? 'Local Sales' : 'Export';})

            ->addColumn('action', function ($invoice) {

                return '<button id="approveinvoice" type="button" class="btn btn-xs btn-detail btn-primary"><i class="glyphicon glyphicon-eye-open"></i> Details</button>
                    
                    ';
            })
            ->make(true);
    }

    public function invoicedetails($invoiceno)
    {
        $invoicedata = TransProduct::query()->where('compCode',Auth::user()->compCode)
            ->where('refno',$invoiceno)
            ->with('item')->get();

        return Response::json($invoicedata);
    }

    /**
     * @return string
     */

    public function submit(Request $request)
    {
        DB::beginTransaction();

        try {

            $request['compCode'] = $this->comp_code;
            $request['challanno'] = get_trans_numbers('DC');
            $request['challandate'] = Carbon::now();
            $request['user_id'] = $this->user_id;

            Delivery::create($request->all());

            $delivery_item = array();

            if(!empty($request['quantity']))
            {

                for($i=0; $i< count($request['quantity']); $i++)
                {

                    $delivery_item['compCode'] = $this->comp_code;
                    $delivery_item['refno'] = $request['challanno'];
                    $delivery_item['contra'] = $request['invoiceno'];
                    $delivery_item['reftype'] = 'D';
                    $delivery_item['product_id'] = $request['product_id'][$i];
                    $delivery_item['quantity'] = $request['quantity'][$i];
                    $delivery_item['delevered'] = $request['quantity'][$i];

                    ItemMovement::create($delivery_item);  // Insert Delivery items

                    Product::where('id',$request['product_id'])
                        ->where('compCode',$this->comp_code)
                        ->increment('sellQty',$request['quantity'][$i]);  //Update sellQty

                    Product::where('id',$request['product_id'])
                        ->where('compCode',$this->comp_code)
                        ->increment('onhand',$request['quantity'][$i]);  //Update current balance


                    TransProduct::where('id',$request['id'][$i])->increment('delevered', $request['quantity'][$i]);
                }

            }

            Sales::where('invoiceno',$request['invoiceno'])
                ->where('compCode',$this->comp_code)
                ->update(['status'=>5]); // 5 = Delivered

            $request->session()->flash('alert-success', 'Delivery Items Successfully Created for Approving');

        } catch (\HttpException $e) {
            DB::rollBack();
            $request->session()->flash('alert-danger', $e->getMessage());
            return redirect()->back();
        } catch (QueryException $e) {
            DB::rollBack();
            $request->session()->flash('alert-danger', $e->getMessage());
            return redirect()->back();
        }

        DB::commit();

        return redirect()->action('Backend\Delivery\DeliveryProductController@index');
    }
}

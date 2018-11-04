<?php

namespace App\Http\Controllers\Backend\Sales;

use App\Models\Common\TransProduct;
use App\Models\Inventory\Sales;
use App\Models\Products\Product;
use App\Models\Products\Relationship;
use App\Models\Products\Tax;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class SalesController extends Controller
{

    public $comp_code;
    public $user_id;
    /**
     * SalesController constructor.
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
        $customers = Relationship::where('compCode',$this->comp_code)
            ->where('type','C')
            ->orderBy('name')->pluck('name','id');

        $invoicenumber = get_trans_numbers('SI');

        $taxes = Tax::where('compCode',Auth::user()->compCode)
            ->pluck('taxName','id');

        $taxes = $taxes->toArray();

        return view('backend.sales.salesInvoice')
            ->with('customers',$customers)
            ->with('invoicenumber',$invoicenumber)->with('taxes',$taxes);

    }

    public function create(Request $request)
    {

        $request['compCode'] = $this->comp_code;
        $request['type'] = 'L'; //Local Sales
        $request['invoiceno'] = ltrim($request->input('invoiceno'),'SI');
        $request['invoicedate'] = Carbon::createFromFormat('d/m/Y',$request->input('invoicedate'));
        $request['user_id'] = $this->user_id;
        $request['status'] = true;
        $request['deleted'] = false;



        DB::beginTransaction();

        try {


            $ids = Sales::create($request->all());

            $purchase_item = array();

            if ($request['item']) {
                foreach ($request['item'] as $item) {
                    $item_sku = '';
                    $item_name = '';
                    $item_tax_total= 0;

                    if (!empty($item['item_id'])) {
                        $data = Product::where('id', $item['item_id'])->first();

                        $item_sku = $data['sku'];
                        $item_name = $data['name'];
                    }

                    $tax_id = 0;

                    if (!empty($item['tax'])) {
                        $tax = Tax::where('id', $item['tax'])->first();


                        if($tax->calculatingMode == 'P')
                        {
                            $item_tax_total = (($item['price'] * $item['quantity']) / 100) * $tax->rate;
                            $tax_amt = ($item['price'] / 100) * $tax->rate;
                        }

                        if($tax->calculatingMode == 'F')
                        {
                            $item_tax_total = $item['quantity']*$tax->rate;
                            $tax_amt = $tax->rate;
                        }
                        $tax_id = $item['tax'];

                    }

                    $purchase_item['compCode'] = $this->comp_code;
                    $purchase_item['refno'] = ltrim($request->input('invoiceno'),'SI');
                    $purchase_item['contra'] = ltrim($request->input('invoiceno'),'SI');
                    $purchase_item['reftype'] = 'S';
                    $purchase_item['product_id'] = $item['item_id'];
                    $purchase_item['name'] = $item_name;
                    $purchase_item['sku'] = $item_sku;
                    $purchase_item['quantity'] = $item['quantity'];
                    $purchase_item['sold'] = $item['quantity'];
                    $purchase_item['unit_price'] = $item['price'];
//                        $purchase_item['tax'] = (($item['price'] * $item['quantity']) / 100) * $tax_rate;
                    $purchase_item['tax_id'] = $tax_id;
                    $purchase_item['tax_total'] = $item_tax_total;
                    $purchase_item['total_price'] = ($item['price'] + $tax_amt)* $item['quantity'];

                    $request['amount'] += $purchase_item['total_price'];

                    TransProduct::create($purchase_item);

                    $request->session()->flash('alert-success', 'Salse Invoice Created Successfully For Approving');
                }

                $due_amt = $request['amount'] - $request['paid_amt'];

                Sales::where('id',$ids->id)
                ->update(['invoice_amt'=>$request['amount'],'due_amt'=>$due_amt]);
            }

        }catch (\Exception $e)
        {
            DB::rollBack();

            $request->session()->flash('alert-danger', $e->getMessage());
            return redirect()->back();

        }catch (QueryException $e)
        {
            DB::rollBack();
            $request->session()->flash('alert-danger', $e->getMessage());
            return redirect()->back();
        }

        DB::commit();

        return redirect()->action('Backend\Sales\SalesController@index');
    }
}

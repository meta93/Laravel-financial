<?php

namespace App\Http\Controllers\Backend\Purchase;

use App\Models\Common\TransProduct;
use App\Models\Inventory\Purchase;
use App\Models\Products\Product;
use App\Models\Products\Relationship;
use App\Models\Products\Tax;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;

class PurchaseController extends Controller
{

    public $comp_code;
    public $user_id;

    /**
     * PurchaseController constructor.
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

        $products = Product::where('compCode',$this->comp_code)
            ->orderBy('name')
            ->pluck('name','productCode');

        $suppliers = Relationship::where('compCode',$this->comp_code)
            ->where('type','S')
            ->orderBy('name')->pluck('name','id');

        $ponumber = get_trans_numbers('PR');

        $taxes = Tax::where('compCode',Auth::user()->compCode)
            ->pluck('taxName','id');

        $taxes = $taxes->toArray();

//        return view('backup.purchaseIndex')
//            ->with('products',$products)->with('suppliers',$suppliers)
//            ->with('ponumber',$ponumber);

        return view('backend.purchase.purchaseIndex')
            ->with('products',$products)->with('suppliers',$suppliers)
            ->with('ponumber',$ponumber)->with('taxes',$taxes);
    }

    public function itemdatadetails()
    {
        $term = Input::get('term');

//        $results = array();

        $items = Product::select('id as item_id', 'name', 'unitPrice', 'initialPrice', 'tax_id')
            ->where('compCode',$this->comp_code)
            ->where('name', 'LIKE', '%'.$term.'%')->get();

//        $items = Product::select('id as item_id', 'name', 'unitPrice', 'initialPrice', 'tax_id')->get();

//        dd($items);

//        if(count($queries))
//        {
//            foreach ($queries as $query)
//            {
//                $results[] = [ 'item_id' => $items->id, 'value' => $items->name.' Avail '.$items->openingQty ];
//            }
//        }else
//        {
//            $results[] = ['value'=>'No Result Found','id'=>''];
//        }

//        if ($items) {
//            foreach ($items as $item) {
//                $tax = Tax::where('id', $item->tax_id)->first();
//
//                $item_tax_price = 0;
//
//                if (!empty($tax)) {
//
//                    if($tax->calculatingMode == 'P')
//                    {
//                        $item_tax_price = ($item->unitPrice / 100) * $tax->rate;
//                    }
//
//                    if($tax->calculatingMode == 'F')
//                    {
//                        $item_tax_price = ($item->unitPrice + $tax->rate);
//                    }
//
//
//
////                    $item_tax_price = ($item->sale_price / 100) * $tax->rate;
//                }
//
//                $item->purchase_price = $item->unitPrice;
//                $total = $item->purchase_price + $item_tax_price;
//
//                $item->total = $total;
//
//            }
//        }

        return response()->json($items);
    }




    public function create(Request $request)
    {

        $request['compCode'] = $this->comp_code;
        $request['type'] = 'L'; //Local Purchase
        $request['refno'] = ltrim($request->input('orderNo'),'PO');
        $request['pdate'] = Carbon::createFromFormat('d/m/Y',$request->input('pdate'));
        $request['user_id'] = $this->user_id;
        $request['status'] = true;
        $request['deleted'] = false;



        DB::beginTransaction();

        try {


            $ids = Purchase::create($request->all());

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
                        $tax_rate = 0;
                        $tax_amt = 0;


//                        if (!empty($item['tax'])) {
//                            $tax = Tax::where('id', $item['tax'])->first();
//
//                            $tax_rate = $tax->rate;
//                            $tax_id = $item['tax'];
//                        }

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

//                        dd($tax_id);


                        $purchase_item['compCode'] = $this->comp_code;
                        $purchase_item['refno'] = ltrim($request->input('orderNo'),'PO');
                        $purchase_item['contra'] = ltrim($request->input('orderNo'),'PO');
                        $purchase_item['reftype'] = 'P';
                        $purchase_item['product_id'] = $item['item_id'];
                        $purchase_item['name'] = $item_name;
                        $purchase_item['sku'] = $item_sku;
                        $purchase_item['quantity'] = $item['quantity'];
                        $purchase_item['purchased'] = $item['quantity'];
                        $purchase_item['unit_price'] = $item['price'];
//                        $purchase_item['tax'] = (($item['price'] * $item['quantity']) / 100) * $tax_rate;
                        $purchase_item['tax_id'] = $tax_id;
                        $purchase_item['tax_total'] = $item_tax_total;
                        $purchase_item['total_price'] = ($item['price'] + $tax_amt)* $item['quantity'];

                        $request['amount'] += $purchase_item['total_price'];

                        TransProduct::create($purchase_item);

                        $request->session()->flash('alert-success', 'Purchase Data Successfully Completed For Approval');
                    }

                    Purchase::where('id',$ids->id)
                        ->update(['invoice_amt'=>$request['amount'],'due_amt'=>$request['amount']]);
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

        return redirect()->action('Backend\Purchase\PurchaseController@index');
    }


    public function invoicePreview()
    {
        return view('backend.sales.invoicePreview');
    }
}

<?php

namespace App\Http\Controllers\Backend\Requisition;

use App\Models\Products\Product;
use App\Models\Requisition\Requisition;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;

class RequisitionController extends Controller
{
    public function index()
    {
        $products = Product::where('compCode',Auth::user()->compCode)
            ->orderBy('name')
            ->pluck('name','productCode');

        $reqnumber = get_trans_numbers('RQ');

        return view('backend.requisition.requisitionIndex')
            ->with('products',$products)->with('reqnumber',$reqnumber);

    }

    public function getproducts()
    {
        $term = Input::get('term');

        $results = array();

        $queries = Product::where('compCode',Auth::user()->compCode)
            ->where('name', 'LIKE', '%'.$term.'%')->get();

        if(count($queries))
        {
            foreach ($queries as $query)
            {
                $results[] = [ 'item_id' => $query->id, 'value' => $query->name.' Avail '.$query->openingQty ];
            }
        }else
        {
            $results[] = ['value'=>'No Result Found','id'=>''];
        }

        return response()->json($results);
    }



    public function postdata(Request $request)
    {

        DB::beginTransaction();

            try{

                if ($request['item']) {
                    foreach ($request['item'] as $item) {

                        $requisition_item = array();
                        $requisition_item['compCode'] = Auth::user()->compCode;
                        $requisition_item['refNo'] = ltrim($request->input('refNo'),'RQ');
                        $requisition_item['reqType'] = $request->input('reqType');
                        $requisition_item['reqDate'] = Carbon::now();
                        $requisition_item['product_id'] = $item['item_id'];
                        $requisition_item['quantity'] = $item['quantity'];
                        $requisition_item['user_id'] = $request->user()->id;

                        Requisition::create($requisition_item);
                    }
                }

            }catch (\Exception $e)
            {
                DB::rollBack();

                $request->session()->flash('alert-danger', $e->getMessage());
                return Redirect::back()->withInput();

            }catch (QueryException $e)
            {
                DB::rollBack();
                $request->session()->flash('alert-danger', $e);
                return redirect()->back();
            }

        DB::commit();

        return redirect()->action('Backend\Requisition\RequisitionController@index');
    }
}

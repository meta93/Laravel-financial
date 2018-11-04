<?php

namespace App\Http\Controllers\Backend\Sales;

use App\Models\Common\TransProduct;
use App\Models\Inventory\Sales;
use App\Models\Products\Relationship;
use Elibyy\TCPDF\Facades\TCPDF;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class RptInvoiceController extends Controller
{
    public $comp_code;
    public $user_id;

    /**
     * RptInvoiceController constructor.
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->comp_code = Auth::user()->compCode;
            $this->user_id = Auth::user()->id;

            return $next($request);
        });
    }


    /**
     * @return string
     */

    public function index()
    {

        $customers = Relationship::where('compCode',$this->comp_code)
            ->where('type','C')
            ->orderBy('name')->pluck('name','id');

        $customers = $customers->toArray();

//        return view('backup.requisitionIndex');
//
        return view('backend.sales.rptinvoiceindex')->with('customers',$customers);
    }

    /**
     * @return string
     */

    public function invoiceautocomplete()
    {

        $term = Input::get('term');

        $results = array();

        $queries = Sales::select('id', 'invoiceno')
            ->where('compCode',$this->comp_code)
            ->where('invoiceno', 'LIKE', '%'.$term.'%')->get();

        if(count($queries))
        {
            foreach ($queries as $query)
            {
                $results[] = [ 'id' => $query->id, 'value' => $query->invoiceno ];
            }
        }else
        {
            $results[] = ['value'=>'No Result Found','id'=>''];
        }

        return response()->json($results);

    }

    public function invoiceprintorpreview(Request $request)
    {

        $items = TransProduct::query()->where('refno',$request['invoiceno'])
            ->where('compCode',$this->comp_code)
            ->where('reftype','S')->with('item')->get();

        $invoice = Sales::query()->where('compCode',$this->comp_code)
            ->where('invoiceno',$request['invoiceno'])->with('relationship')->first();

        switch($request['submittype']) {

            case 'preview': //action for html here
                return view('backend.sales.invoicepreview')->with('items',$items);
                break;

            case 'print': //action for css here

                $view = \View::make('backend.sales.invoiceprint',compact('items','invoice'));
                $html = $view->render();

                $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, 'A4', true, 'UTF-8', false);

                $pdf::AddPage();

                $pdf::writeHTML($html, true, false, true, false, '');
                $pdf::Output('voucher.pdf');

                break;

        }

    }
}

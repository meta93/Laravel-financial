<?php

namespace App\Http\Controllers\Backend\Delivery;

use App\Models\Inventory\Delivery;
use App\Models\Inventory\ItemMovement;
use App\Models\Products\Relationship;
use Elibyy\TCPDF\Facades\TCPDF;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;

class RptDeliveryController extends Controller
{
    public $comp_code;
    public $user_id;
    /**
     * RptDeliveryController constructor.
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
        $customers = Relationship::query()->where('compCode',$this->comp_code)
            ->where('type','C')
            ->orderBy('name')->pluck('name','id');

        $customers = $customers->toArray();

        return view('backend.delivery.rptdeliverychallanindex')->with('customers',$customers);
    }

    public function report(Request $request)
    {
        dd($request);
    }

    public function challanautocomplete()
    {
        $term = Input::get('term');

        $results = array();

        $queries = Delivery::select('id', 'challanno')
            ->where('compCode',$this->comp_code)
            ->where('challanno', 'LIKE', '%'.$term.'%')->get();

        if(count($queries))
        {
            foreach ($queries as $query)
            {
                $results[] = [ 'id' => $query->id, 'value' => $query->challanno ];
            }
        }else
        {
            $results[] = ['value'=>'No Result Found','id'=>''];
        }

        return response()->json($results);
    }

    public function dcreport(Request $request)
    {
        $items = ItemMovement::query()->where('refno',$request['challanno'])
            ->where('compCode',$this->comp_code)
            ->where('reftype','D')->with('item')->get();

        $dchallan = Delivery::query()->where('compCode',$this->comp_code)
            ->where('challanno',$request['challanno'])->with('relationship')->first();

        switch($request['submittype']) {

            case 'preview': //action for html here
                return view('backend.delivery.rptdeliverychallanpreview')->with('items',$items)->with('dchallan',$dchallan);
                break;

            case 'print': //action for css here

//                dd($dchallan);

                $view = \View::make('backend.delivery.rptdeliverychallanprint',compact('items','dchallan'));
                $html = $view->render();

                $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, 'A4', true, 'UTF-8', false);

                $pdf::AddPage();

                $pdf::writeHTML($html, true, false, true, false, '');
                $pdf::Output('voucher.pdf');

                break;

        }

    }
}

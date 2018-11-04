<?php

namespace App\Http\Controllers\Product;

use App\Models\BasicProperty;
use App\Models\Products\Brand;
use App\Models\Products\Category;
use App\Models\Products\Godown;
use App\Models\Products\Models;
use App\Models\Products\Product;
use App\Models\Products\Rack;
use App\Models\Products\Relationship;
use App\Models\Products\Size;
use App\Models\Products\SubCategory;
use App\Models\Products\Tax;
use App\Models\Products\TaxGroup;
use App\Models\Products\Unit;
use App\Models\UserPrivilegesModel;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Money\Currencies\ISOCurrencies;
use Money\Currency;
use Money\Formatter\IntlMoneyFormatter;
use Money\Money;
use Yajra\Datatables\Datatables;
use Form;
use NumberFormatter;

class ProductController extends Controller
{

    public $comp_code;
    public $user_id;

    /**
     * ProductController constructor.
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

        $categories = Category::where('compCode',Auth::user()->compCode)
            ->pluck('name','id');
//        $categories = $categories->toArray();

        $brands = Brand::where('compCode',Auth::user()->compCode)
            ->pluck('name','id');
//        $brands = $brands->toArray();


        $units = Unit::pluck('formalName','name');
//        $units = $units->toArray();


        $sizes = Size::where('compCode',Auth::user()->compCode)
            ->pluck('size','id');
//        $sizes = $sizes->toArray();

        $models = Models::where('compCode',Auth::user()->compCode)
            ->pluck('modelNo','id');
//        $models = $models->toArray();

//Take Taxes table is only one tax is applicable

        $taxes = Tax::where('compCode',Auth::user()->compCode)
            ->pluck('taxName','id');
//        $taxes = $taxes->toArray();

//        $taxes = TaxGroup::where('compCode',Auth::user()->compCode)
//            ->pluck('taxGroupName','taxgrp_code');


        $godowns = Godown::where('compCode',Auth::user()->compCode)
            ->pluck('godownName','id');
//        $godowns = $godowns->toArray();


        $racks = Rack::where('compCode',Auth::user()->compCode)
            ->pluck('name','id');
//        $racks = $racks->toArray();

        $suppliers = Relationship::where('compCode',Auth::user()->compCode)
            ->where('type','S')->pluck('name','id');

        return view('product.productIndex')->with('userPr',$userPr)
            ->with('categories',$categories)->with('brands',$brands)
            ->with('units',$units)->with('sizes',$sizes)
            ->with('models',$models)->with('taxes',$taxes)
            ->with('godowns',$godowns)->with('racks',$racks)->with('suppliers',$suppliers);
    }

    public function getData()
    {
        $products = Product::query()->where('compCode',Auth::user()->compCode)
            ->with('category')->with('brand');


        return Datatables::of($products)
            ->addColumn('status', function ($categories) {

                return Form::checkbox('status',$categories->id,$categories->status, array('id'=>'status','disabled'));
            })

//            ->addColumn('action', function ($products) {
//
//                return '<button id="editgodown" data-toggle="modal" data-target="#editUnitModal'.$products->id.'"  type="button" class="btn btn-xs btn-primary"><i class="fa fa-edit"></i> Edit</button>
//                    <button data-remote="unit.delete/' . $products->id . '" type="button" class="btn btn-xs btn-delete btn-danger pull-right" ><i class="fa fa-remove"></i>Del</button>
//
//                    <!-- editGodownModal -->
//
//                    <div class="modal fade" id="editUnitModal'.$products->id.'" role="dialog" data-backdrop="false" style="background-color: rgba(0, 0, 0, 0.5);">
//                        <div class="modal-dialog">
//                            <div class="modal-content">
//                                <div class="modal-header">
//                                    <button type="button" class="close" data-dismiss="modal">x</button>
//                                    <h4 class="modal-title">Edit Unit Data</h4>
//                                </div>
//                                <div class="modal-body">
//                                    <form class="form-horizontal" role="form" action="unit.edit/'.$products->id.'" method="POST" >
//
//                                    <input type="hidden" name="_token" value="'. csrf_token().'">
//                                    <input type="hidden" name="id" value="'.$products->id.'">
//                                        <div class="form-group">
//                                            <label for="name" class="col-md-3 control-label">Unit Name</label>
//                                            <div class="col-md-9">
//                                                <input id="name" type="text" class="form-control" name="name" value="'.$products->name.'" required autofocus>
//                                            </div>
//                                        </div>
//
//                                        <div class="form-group">
//                                            <label for="name" class="col-md-3 control-label">Formal Name</label>
//                                            <div class="col-md-9">
//                                                <input id="formalName" type="text" class="form-control" name="formalName" value="'.$products->formalName.'" required>
//                                            </div>
//                                        </div>
//
//                                        <div class="form-group">
//                                            <label for="name" class="col-md-3 control-label">No of Decimal Places</label>
//                                            <div class="col-md-9">
//                                                <input id="noOfDecimalplaces" type="text" class="form-control" name="noOfDecimalplaces" value="'.$products->noOfDecimalplaces.'" required>
//                                            </div>
//                                        </div>
//
//
//
//                                        <button type="submit" class="btn btn-primary" id="update-data">Save Changes</button>
//                                    </form>
//                                </div>
//                            </div>
//                        </div>
//                    </div>';
//            })
            ->make(true);
    }

    public function create()
    {
        $categories = Category::where('compCode',Auth::user()->compCode)
            ->pluck('name','id');

        $subcategories = SubCategory::where('compCode',Auth::user()->compCode)
            ->pluck('name','id');
//        $categories = $categories->toArray();

        $brands = Brand::where('compCode',Auth::user()->compCode)

            ->pluck('name','id');
//        $brands = $brands->toArray();


        $units = Unit::pluck('formalName','name');
//        $units = $units->toArray();


        $sizes = Size::where('compCode',Auth::user()->compCode)
            ->pluck('size','id');
//        $sizes = $sizes->toArray();

        $models = Models::where('compCode',Auth::user()->compCode)
            ->pluck('modelNo','id');
//        $models = $models->toArray();

        $taxes = Tax::where('compCode',Auth::user()->compCode)
            ->pluck('taxName','id');



//        $taxes = TaxGroup::where('compCode',Auth::user()->compCode)
//            ->pluck('taxGroupName','taxgrp_code');
//        $taxes = $taxes->toArray();


        $godowns = Godown::where('compCode',Auth::user()->compCode)
            ->pluck('godownName','id');
//        $godowns = $godowns->toArray();


        $racks = Rack::where('compCode',Auth::user()->compCode)
            ->pluck('name','id');
//        $racks = $racks->toArray();

        $suppliers = Relationship::where('compCode',Auth::user()->compCode)
            ->where('type','S')->pluck('name','id');

        return view('product.addProductForm')
            ->with('categories',$categories)->with('brands',$brands)
            ->with('units',$units)->with('sizes',$sizes)
            ->with('models',$models)->with('taxes',$taxes)
            ->with('godowns',$godowns)->with('racks',$racks)
            ->with('suppliers',$suppliers)->with('subcategories',$subcategories);
    }

    public function addProduct(Request $request)
    {



        $file = Input::file('imagePath');

        if(!empty($file)) {

            $filename = Input::file('imagePath')->getClientOriginalName();
            Input::file('imagePath')->move('images/products/', $filename);
            $request['image'] =  '/images/products/'.$filename;
        }


        DB::beginTransaction();

            $maxCode = Product::where('compCode',$request->user()->compCode)
                ->max('productCode');

            if(!empty($maxCode))
            {
                $prdCode = $maxCode + 1;
            }
            else
            {
                $prdCode = '200001';
            }



            $request->request->add(['productCode'=>$prdCode,'user_id' => Auth::user()->id,'compCode'=>Auth::user()->compCode]);

            try {



                Product::create($request->except('imagePath'));

                $request->session()->flash('alert-success', $request->input('name').' Added');

            }catch (\Exception $e)
            {
                DB::rollBack();
                $error = $e->getMessage();
                $request->session()->flash('alert-danger', $error.' '.$request->input('name').' Not Saved');
                return redirect()->back()->withInput();
            }catch (QueryException $e)
            {
                DB::rollBack();
                $error = $e->getMessage();
                $request->session()->flash('alert-danger', $error.' '.$request->input('name').' Not Saved');
                return redirect()->back()->withInput();
            }

        DB::commit();


        return redirect()->action('Product\ProductController@index');
    }

    public function edit(Request $request, $id)
    {

        DB::beginTransaction();

        try{

            Unit::where('id',$id)->update(['name'=>Str::upper($request->input('name')),'formalName'=>$request->formalName,'noOfDecimalplaces'=>$request->noOfDecimalplaces]);
            $request->session()->flash('alert-success', 'Updated');

        }catch (HttpException $e)
        {
            DB::rollBack();
            $request->session()->flash('alert-success', $request->name.' Not updated');
            return redirect()->back();
        }

        DB::commit();

        return redirect()->action('Product\UnitController@index');
    }


    public function delete($id)
    {

    }

    public function details($id)
    {
        $data = Product::query()->where('id',$id)->get();

       return json_encode($data);
    }


    public function totalproduct()
    {
        $input_items = request('item');
        $currency_code = get_currency();

        $currencies = new ISOCurrencies();
        $numberFormatter = new NumberFormatter('en_US', NumberFormatter::CURRENCY);
//        new NumberFormatter('nl_NL', NumberFormatter::CURRENCY);
        $moneyFormatter = new IntlMoneyFormatter($numberFormatter, $currencies);

//        $zero = $numberFormatter->getSymbol(\NumberFormatter::ZERO_DIGIT_SYMBOL);


        $json = new \stdClass;

        $sub_total = 0;
        $tax_total = 0;

        $items = array();

        if ($input_items) {
            foreach ($input_items as $key => $item) {
                $item_tax_total= 0;
                $item_sub_total = ($item['price'] * $item['quantity']*100); //Money function gets last two digit as decimal

                if (!empty($item['tax'])) {
                    $tax = Tax::where('id', $item['tax'])->first();


                    if($tax->calculatingMode == 'P')
                    {
                        $item_tax_total = (($item['price'] * $item['quantity']) / 100)*100 * $tax->rate;
                    }

                    if($tax->calculatingMode == 'F')
                    {
                        $item_tax_total = $item['quantity']*$tax->rate*100;
                    }

                }
//
                $sub_total += $item_sub_total;
                $tax_total += $item_tax_total;

                $total = $item_sub_total + $item_tax_total;


                $money = new Money($total, new Currency($currency_code));

                $moneyFormatter->format($money);

                $items[$key] = $moneyFormatter->format($money);
            }
        }

        $json->items = $items;

        $sub_total = new Money($sub_total, new Currency($currency_code));

        $json->sub_total = $moneyFormatter->format($sub_total);

        $tax_total = new Money($tax_total, new Currency($currency_code));

        $json->tax_total = $moneyFormatter->format($tax_total);

        $grand_total = $sub_total->add($tax_total);

        $json->grand_total = $moneyFormatter->format($grand_total);


        return response()->json($json);
    }

    public function autocomplete()
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
}

<?php

namespace App\Http\Controllers\Product;

use App\Models\Products\Brand;
use App\Models\UserPrivilegesModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Yajra\Datatables\Datatables;

class BrandController extends Controller
{
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

        return view('product.brandsIndex')->with('userPr',$userPr);
    }

    public function getData()
    {
        $brands = Brand::query();


        return Datatables::of($brands)
            ->addColumn('showimage',function ($brands) {
                return '<img src=" '.$brands->imagePath.' " height='. 25 .' width= ' . 75 .'  />';
            })
            ->addColumn('action', function ($brands) {

                return '<button id="editproject" data-toggle="modal" data-target="#editCategoryModal'.$brands->id.'"  type="button" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i> Edit</button>
                    <button data-remote="product.brand.delete/' . $brands->id . '" type="button" class="btn btn-xs btn-delete btn-danger pull-right" ><i class="glyphicon glyphicon-remove"></i>Delete</button>
                    
                    <!-- editCategoryModal -->
                    
                    <div class="modal fade" id="editCategoryModal'.$brands->id.'" role="dialog" data-backdrop="false" style="background-color: rgba(0, 0, 0, 0.5);">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">x</button>
                                    <h4 class="modal-title">Edit Brands</h4>
                                </div>
                            <form class="form-horizontal" role="form" action="product.brand.edit/'.$brands->id.'" method="POST" >
                                <div class="modal-body">
                                   
                                    
                                    <input type="hidden" name="_token" value="'. csrf_token().'">
                                    <input type="hidden" name="id" value="'.$brands->id.'">
                                        <div class="form-group">
                                            <label for="name" class="col-md-3 control-label">Name</label>
                                            <div class="col-md-8">
                                                <input id="name" type="text" class="form-control" name="name" value="'.$brands->name.'" required autofocus>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label class="control-label col-md-3">Manufacturer</label>
                                            <div class="col-md-8">
                                                <input name="manufacturer" class="form-control" type="text" id="manufacturer" value="'.$brands->manufacturer.'">
                                                
                                            </div>
                                        </div>
                                        
                                
                                    <div class="form-group">
                                        <div class="col-md-10 col-md-offset-1">
                                            <button type="submit" class="btn btn-primary  pull-right">Submit</button>
                                            <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">Cancel</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>';
            })
            ->make(true);
    }

    public function add(Request $request)
    {
        $file = Input::file('logo');

        if(!empty($file)) {

            $filename = Input::file('logo')->getClientOriginalName();
            Input::file('logo')->move('images/brands/', $filename);
            $request->request->add(['imagePath' => 'images/brands/'.$filename,'compCode'=>Auth::user()->compCode]);
        }

        $request->request->add(['compCode'=>Auth::user()->compCode]);

        DB::beginTransaction();

            try {

                Brand::create($request->except(['logo']));

                $request->session()->flash('alert-success', $request->input('name').' Added');
                $request->flash();

            }catch (\Exception $e)
            {
                DB::rollBack();
                $error = $e->getMessage();
                $request->session()->flash('alert-danger', $error.' '.$request->input('name').' Not Saved');
                return redirect()->back();
            }

        DB::commit();

        return redirect()->action('Product\BrandController@index');
    }

    public function edit(Request $request, $id)
    {
        DB::beginTransaction();

        try{

            Brand::where('id',$id)->update(['name'=>$request->input('name'),'manufacturer' => $request->input('manufacturer')]);
            $request->session()->flash('alert-success', 'Successfully Updated');

        }catch (HttpException $e)
        {
            DB::rollBack();
            $request->session()->flash('alert-danger', $request->name.' Not updated');
            return redirect()->back();
        }catch (\Illuminate\Database\QueryException $e)
        {
            DB::rollBack();
            $request->session()->flash('alert-danger', $e.' '.$request->name.' Not updated');
            return redirect()->back();
        }

        DB::commit();

        return redirect()->action('Product\BrandController@index');
    }


    public function destroy(Request $request, $id)
    {
        DB::beginTransaction();

        try{

            Brand::find($id)->delete();
            echo json_encode(array("status" => TRUE));

        }catch (HttpException $e)
        {
            DB::rollBack();
            return $e;
        }catch (\Illuminate\Database\QueryException $e)
        {
            DB::rollBack();
            return $e;
        }

        DB::commit();

    }
}

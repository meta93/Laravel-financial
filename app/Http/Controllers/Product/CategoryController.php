<?php

namespace App\Http\Controllers\Product;

use App\Models\Products\Category;
use App\Models\Products\SubCategory;
use App\Models\UserPrivilegesModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Yajra\Datatables\Datatables;
use Form;

class CategoryController extends Controller
{
    public function index()
    {
        $userPr = UserPrivilegesModel::where('email',Auth::user()->email)
            ->where('compCode',Auth::user()->compCode)
            ->where('useCaseId','P01')->first();

        if($userPr->view == 0)
        {
            session()->flash('alert-danger', 'You do not have permission. Please Contact Administrator');
            return redirect()->back();
            die();
        }

        return view('product.categoriesIndex')->with('userPr',$userPr);
    }

    public function getData()
    {
        $categories = Category::query()->where('compCode',Auth::user()->compCode);


        return Datatables::of($categories)

            ->addColumn('status', function ($categories) {

                return Form::checkbox('status',$categories->id,$categories->status, array('id'=>'status','disabled'));
            })

            ->addColumn('action', function ($categories) {

                return '<button id="editproject" data-toggle="modal" data-target="#editCategoryModal'.$categories->id.'"  type="button" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i> Edit</button>
                    <button data-remote="categories.data.delete/' . $categories->id . '" type="button" class="btn btn-xs btn-delete btn-danger pull-right" ><i class="glyphicon glyphicon-remove"></i>Delete</button>
                    
                    <!-- editCategoryModal -->
                    
                    <div class="modal fade" id="editCategoryModal'.$categories->id.'" role="dialog" data-backdrop="false" style="background-color: rgba(0, 0, 0, 0.5);">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">x</button>
                                    <h4 class="modal-title">Edit Category Data</h4>
                                </div>
                                <form class="form-horizontal" role="form" action="categories.data.edit/'.$categories->id.'" method="POST" >
                                
                                <div class="modal-body">
                                    
                                    
                                    <input type="hidden" name="_token" value="'. csrf_token().'">
                                    <input type="hidden" name="id" value="'.$categories->id.'">
                                        <div class="form-group">
                                            <label for="name" class="col-md-3 control-label">Category Name</label>
                                            <div class="col-md-9">
                                                <input id="name" type="text" class="form-control" name="name" value="'.$categories->name.'" required autofocus>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label class="control-label col-md-3">GL Code</label>
                                            <div class="col-md-9">
                                                <input name="accNo" placeholder="Alias" class="form-control" type="text" id="accNo" value="'.$categories->accNo.'" required>
                                                
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                        <label for="status" class="col-md-3 control-label">Active ?</label>
                                        <div class="col-md-6"> '.
                                            Form::checkbox('status',$categories->id,$categories->status, array('id'=>'status'))
                                    .'
                                        </div>
                                        </div>
                                        
                                </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary">Save changes</button>
                                        <button type="submit" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
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
        DB::beginTransaction();

        try {

            Category::create([
                'compCode' => Auth::user()->compCode,
                'name' => Str::upper($request->input('name')),
                'status' => true,
                'accNo' =>$request->input('accNo')
            ]);

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

        return redirect()->action('Product\CategoryController@index');

    }

    public function edit(Request $request, $id)
    {
        DB::beginTransaction();

        try{

            if(empty($request->status))
            {
                $request->request->add(['active'=>false]);

            }else{
                $request->request->add(['active'=>true]);
            }

            Category::where('id',$id)->update(['name'=>$request->input('name'),'accNo' => $request->input('accNo'),
            'status' => $request->active]);
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

        return redirect()->action('Product\CategoryController@index');
    }

    public function destroy(Request $request, $id)
    {
        DB::beginTransaction();

        try{

            Category::find($id)->delete();
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

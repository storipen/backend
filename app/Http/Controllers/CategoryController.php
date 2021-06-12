<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $category=Category::orderBy('created_at', 'desc')->paginate(10);
        return view('category/list',compact('category'));
    }
    public function add(){
        return view('category/add');
    }

    public function save(Request $request){
        try{

            $find=Category::where('code', $request["code"])->first();
            if($find!=null) {
                echo "masuk";
                return redirect()->back()->with('alert', 'Category Code duplicate!');
            }


            $category =new Category();
            $category->title=$request["title"];
            $category->code=$request["code"];
            $category->save();
            
            return redirect('category');
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function edit($id){
        $category=Category::where('id', $id)->first();

        return view('category/edit',compact('category'));
    }

    public function update(Request $request){
        try{
            $affected = DB::table('categories')
            ->where('id', $request['id'])
            ->update([
                'title' => $request['title']]);

            return redirect('category');
        }catch (Exception $e){
            echo  $e->getMessage();
        }
    }

}

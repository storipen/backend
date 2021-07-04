<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Story;
use Exception;
use Illuminate\Support\Facades\Storage;
use App\StoryCategory;
use App\Category;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use KyslikColumnSortableSortable;
use App\Banner;

class StoryController extends Controller
{
   
    public function __construct()
    {
        $this->middleware('auth');
    }
    //
    public function index()
    {
        Log::info('OK');
        $uploadimage = env('APP_DIRECTORYIMAGE', 'default_value');
        $value = env('APP_LOADIMAGE', 'default_value');
        Log::info("PUBLIC index===" . $uploadimage);
        Log::info("PUBLIC index===" . $value);
        $story = Story::orderBy('created_at', 'desc')->paginate(10);
        return view('story', compact('story'));
    }

    public function addstory()
    {
        $category = Category::all();
        return view('addstory', compact('category'));
    }

    public function saveStory(Request $request)
    {
        try {
            $story = new Story();
            $value = env('APP_LOADIMAGE', 'default_value');
            $uploadimage = env('APP_DIRECTORYIMAGE', 'default_value');
            $destinationPath = public_path('/thumbnail');
            $story->title = $request['title'];
            $story->sinopsis = $request['sinopsis'];

            $imageName = time() . '.' . $request->image->extension();
            
            $image = $request->file('image');
            $img = Image::make($image->path());
            // $img->resize(300, 500, function ($constraint) {
            //     $constraint->aspectRatio();
            // })->save($imageName);
            $filePath =  $imageName;
            $disk = Storage::disk('gcs')->put($filePath, $img);
           
            $story->thumbnail =  $value . $imageName;
            $story->imageHeader =  $imageName;
            $story->author = $request['author'];
            $story->tagline = $request['tagline'];
            $story->countView = 0;
            $story->save();
            $category = $request['category'];
            foreach ($category as $ctg) {
                $categorydb = new StoryCategory();
                $resultcategories = Category::find($ctg);
                $categorydb->story_id = $story->id;
                $categorydb->idcategory = $ctg;
                $categorydb->category_name = $resultcategories->title;
                $categorydb->save();
            }
            return redirect('/story');
            } catch (Exception $e) {
                echo $e->getMessage();
            }
    }
    public function getEditStory($id)
    {
        $story = Story::with('StoryCategory')->where('id', $id)->first();

        $category = Category::all();
        $list;
        foreach ($story->StoryCategory as $sku){ 
            // Code Here
            if(!empty($list)){
                $list=$list.",".$sku->idcategory;
            }else{
                $list=$sku->idcategory;
            }  
        }
      
    
        return view('editstory', compact('story', 'category', 'list'));
    }
    public function updateStory(Request $request)
    {
        try {
            $story = Story::with('StoryCategory')->where('id', $request['id'])->first();
            $value = env('APP_LOADIMAGE', 'default_value');
            $uploadimage = env('APP_DIRECTORYIMAGE', 'default_value');
            if ($request->image != null) {
                $imageName = time() . '.' . $request->image->extension();
                // $request->image->move(public_path('images'), $imageName);
                // $request->image->move($uploadimage, $imageName);
                $image = $request->file('image');
                $img = Image::make($image->path());
                // $img->resize(300, 500, function ($constraint) {
                //     $constraint->aspectRatio();
                // })->save($uploadimage.'/'.$imageName);
                
                $imageNameUpdate= $value . $imageName ;
                $disk = Storage::disk('gcs')->put($imageNameUpdate, $img);
                Log::info("PUBLIC PATH===" . $uploadimage);
            } else {
                $imageNameUpdate =  $story->thumbnail;
                $imageName = $story->imageHeader;
            }
            
            $affected = DB::table('stories')
                ->where('id', $request['id'])
                ->update([
                    'title' => $request['title'],
                    'sinopsis' => $request['sinopsis'],
                    'author' => $request['author'],
                    'tagline' => $request['tagline'],
                    'thumbnail'=> $imageNameUpdate,
                    'imageHeader'=>$imageName]);
           
        
           $t= storyCategory::where('story_id', $request['id'])->delete(); 
           $category = $request['category'];
           foreach ($category as $ctg) {
               $categorydb = new storyCategory();
               $resultcategories = Category::find($ctg);
               $categorydb->story_id =$request['id'];
               $categorydb->idcategory = $ctg;
               $categorydb->category_name = $resultcategories->title;
               $categorydb->save();
           }
            return redirect('/story');
        } catch (Exception $e) {
            echo  $e->getMessage();
        }
    }
    public function recomended()
    {
        $detail = DB::table('recommended')->first();
        $data = json_encode($detail->recommended);

        $query = Story::with('StoryCategory');
        foreach (explode(',', $detail->recommended) as $split) {
            $query->orWhere('id', $split);
        }
        $recomended = $query->paginate(10);
        $idRecomended=$detail->id;
        // $story = Story::paginate(10);
        return view('recomended/recomended', compact('recomended','idRecomended'));
    }
    public function deleterecomended($storyID,$id)
    {   
        try{
            $detail = DB::table('recommended')->first();
            $data = json_encode($detail->recommended);
            $arr="";
            foreach (explode(',', $detail->recommended) as $split) {
               if($split!=$storyID){
                $arr=$arr.",".$split;
               }
            }
         
            $affected = DB::table('recommended')
                    ->where('id',$id)
                    ->update([
                        'recommended' => $arr,
                    ]);
            return redirect('recommended');
        }catch(Exception $e){
            error_log("ppp");
            error_log($e->getMessage());
        }
    
    }
    public function addRecomended(Request $request)
    {
        $filter = $request->query('filter');
        $detail = DB::table('recommended')->first();
        $data = json_encode($detail->recommended);
        $alldata;
        if (!empty($filter)) {
            $story = Story::sortable();
                foreach (explode(',', $detail->recommended) as $split) {
                    $story->where('id','!=' ,$split);
                }
                $story->where('stories.title', 'like', '%'.$filter.'%');
                $alldata=$story->paginate(5);
        } else {
            $story = Story::sortable();
            foreach (explode(',', $detail->recommended) as $split) {
                $story->where('id','!=' ,$split);
            }
            // $story->paginate(5);
            $alldata=$story->paginate(5);
        }
       
        
        
        $idRecomended=$detail->id;
        // $story = Story::paginate(10);
        // return view('recomended/addRecomended', compact('recomended','idRecomended'));
        return view('recomended/addRecomended')->with('story', $alldata)->with('filter', $filter)->with('idRecomended',$idRecomended);
    }
    public function SaveRecomended($id,$storyID)
    {
        try{
            error_log("masuk");
            $detail = DB::table('recommended')->first();
            
            $arr=$detail->recommended.','.$storyID;
            $affected = DB::table('recommended')
                    ->where('id',$id)
                    ->update([
                        'recommended' => $arr,
                    ]);
            return redirect('recommended');
        }catch(Exception $e){
            error_log("ppp");
            error_log($e->getMessage());
        }
      
    }

    public function ViewBanner(){
        try {
            error_log("test");
            // $databanner = DB::table('banners')->orderBy('created_at', 'asc')->paginate(10);
            // error_log($banner);
            $databanner= Banner::paginate(10);
            // dd($databanner);
        
            return view('banner/list',compact('databanner'));
        } catch (Exception $e) {
            error_log("ppp");
            error_log($e->getMessage());
        }
    }
    public function SaveBanner(Request $request){
        try {
            $banner = new Banner();
            $value = env('APP_LOADIMAGE', 'default_value');
            $uploadimage = env('APP_DIRECTORYIMAGE', 'default_value');
            $destinationPath = public_path('/thumbnail');
            
            $imageName = time() . '.' . $request->image->extension();
            Log::info("PUBLIC PATH===" . $uploadimage);
            $request->image->move($uploadimage, $imageName);

            $banner->title = $request['title'];
            $banner->url = $value . $imageName;
            $banner->description = $request['description'];
            $banner->save();
            return redirect('banner');
        } catch (Exception $e) {
            error_log("ppp");
            error_log($e->getMessage());
        }
    }
    public function addBanner()
    {
        
        return view('banner/addbanner');
    }

    public function deleteBanner($id){
        try{
            error_log("__");
            $delete= Banner::find($id);
            $delete->delete();
            return redirect('banner');
        }catch(Exception $e){
            error_log("ppp");
            error_log($e->getMessage());
        }
    }


    public function bestStories(){
        $best = DB::table('story_bests')->first();
        $data = json_encode($best->story_id);

        $query = Story::with('StoryCategory');
        foreach (explode(',', $best->story_id) as $split) {
            $query->orWhere('id', $split);
        }
        $idbest=$best->id;
        $best = $query->paginate(10);
        
        // $story = Story::paginate(10);
        return view('best/best', compact('best','idbest'));

    }
    public function addBest(Request $request){
      
        $filter = $request->query('filter');
        $detail = DB::table('story_bests')->first();
        $data = json_encode($detail->story_id);
        $alldata;
        if (!empty($filter)) {
            $story = Story::sortable();
                foreach (explode(',', $detail->story_id) as $split) {
                    $story->where('id','!=' ,$split);
                }
                $story->where('stories.title', 'like', '%'.$filter.'%');
                $alldata=$story->paginate(5);
        } else {
            $story = Story::sortable();
            foreach (explode(',', $detail->story_id) as $split) {
                $story->where('id','!=' ,$split);
            }
    
            $alldata=$story->paginate(5);
        }
        
        $idbest=$detail->id;

        return view('best/addBest')->with('story', $alldata)->with('filter', $filter)->with('idbest',$idbest);

    }
    public function SaveBest($id,$storyID)
    {
        try{
            error_log("masuk");
            $detail = DB::table('story_bests')->first();
            
            $arr=$detail->story_id.','.$storyID;
            $affected = DB::table('story_bests')
                    ->where('id',$id)
                    ->update([
                        'story_id' => $arr,
                    ]);
            return redirect('best');
        }catch(Exception $e){
            error_log("ppp");
            error_log($e->getMessage());
        }
      
    }
    public function deletebest($storyID,$id)
    {   
        try{
            $detail = DB::table('story_bests')->first();
            $data = json_encode($detail->story_id);
            $arr="";
            foreach (explode(',', $detail->story_id) as $split) {
               if($split!=$storyID){
                $arr=$arr.",".$split;
               }
            }
         
            $affected = DB::table('story_bests')
                    ->where('id',$id)
                    ->update([
                        'story_id' => $arr,
                    ]);
            return redirect('best');
        }catch(Exception $e){
            error_log("ppp");
            error_log($e->getMessage());
        }
    
    }
    public function deleteStory($id){
        try{
            error_log("__");
            $delete= Story::find($id);
            $delete->delete();
            return redirect('/story');
        }catch(Exception $e){
            error_log("ppp");
            error_log($e->getMessage());
        }
    }
 

}

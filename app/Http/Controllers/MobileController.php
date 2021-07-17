<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Story;
use App\Comment;
use App\Like;
use App\StoryCategory;
use Exception;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\stdClass;
use App\Http\Controllers\Config;
use Illuminate\Database\Eloquent\Collection;
use App\Response;
use App\Favorite;
use Illuminate\Support\Facades\Input;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Auth;
use AuthApi;


// use Illuminate\Support\Collection;

use function PHPSTORM_META\type;


class MobileController extends Controller
{
    //
    protected $erro_500 = "Internal Server Error";

    public function GetAllDataStory(Request $request)
    {

        try {
            error_log("get di siin");
            $response = new Response;
            $data = new Collection;
            $value = env('APP_LOADIMAGE', 'default_value');


            // $collection = new Collection();
            $result = collect();
            $story = Story::with('StoryCategory')->orderBy('created_at', 'asc')->paginate(10);
            $response->data = $story;

            $response->message = "get all data stories";

            return response()->json($response, 200);
        } catch (Exception $e) {
            error_log($e->getMessage());
            $response->message = "error load data";
            $response->error = $this->erro_500;
            return response()->json($response, 500);
        }
    }
    public function GetDataStoryPart($id)
    {
        $response = new Response;
        error_log("di sini ya" ,$id);
        try {
             // $details = DB::table('part_stories')->select('id', 'idstory', 'titlePart', 'thumbnail', 'countView', 'created_at', 'updated_at', 'imageHeader', 'sub_title')->where('idstory', $id)->get();
            $user=$this->GetMe();
            $storydata = Story::with('StoryCategory')->where('id',  $id)->first();
            $details = DB::table('part_stories')->select('id', 'idstory', 'titlePart', 'thumbnail', 'countView', 'created_at', 'updated_at', 'imageHeader', 'sub_title')
            ->rightjoin("likes",'part_stories.id','=','likes.partId')
            ->select('part_stories.id', 'part_stories.idstory', 'part_stories.titlePart', 'part_stories.thumbnail', 'part_stories.countView', 'part_stories.created_at', 'part_stories.updated_at', 'part_stories.imageHeader', 'part_stories.sub_title',DB::raw("count(likes.id) as total_likes"))
            ->groupBy('part_stories.id','likes.partId')
            ->where('idstory', $id)->get();
          
           $cekfavorites = false;
            $ceklikes=false;
            if($user!=null){
                error_log("pale user");
                $likes = DB::table('likes')->where('userId', $user->id)->where('storyId', $storydata->id)->first();
                $favorites = DB::table('favorites')->where('userId', $user->id)->where('storyId', $storydata->id)->where('partId',"00")->first();
                // dd($favorites);
                if ($favorites) {
                    $cekfavorites = true;
                } else {
                    $cekfavorites = false;
                }
                if ($likes != null) {
                    if ($likes->like) {
                        $ceklikes = true;
                    } else {
                        $ceklikes = false;
                    }
                }
            }

            $countComment=DB::table("comments")->where("storyId", $id)->count();
            $countLike=DB::table("likes")->where("storyId", $id)->where("like",1)->count();

           

            $o = new \stdClass();
            $o->story = $storydata;
            $o->like = $ceklikes;
            $o->favorites = $cekfavorites;
            $o->part = $details;
            $o->TotalComment=$countComment;
            $o->totalLike=$countLike;
            $response->data = $o;
            $response->message = "get all data part stories";
            $affected = DB::table('stories')
            ->where('id', $id)
            ->update([
                'countView' => $storydata->countView+1,
            ]);
            return response()->json($response, 200);

            //update
           
        } catch (Exception $e) {
            error_log($e->getMessage());
            $response->message = $e->getMessage();
            $response->error = $this->erro_500;
            return response()->json($response, 500);
        }
    }
    public function GetDataStoryDetail(Request $request)
    {
        try {
            $user=$this->GetMeSpesial();
            
            $response = new Response;
            $o = new \stdClass();
            $detail = DB::table('part_stories')->where('id', $request["id"])->first();
            // dd($detail);
            $parent =  DB::table('stories')->where('id', $detail->idstory)->first();
            $cekfavorites = false;
            $ceklikes=false;
            $type=gettype($user);
            error_log($type);
            if($type=="integer"){
                error_log("masuk integer");
                if($user===1){
                    return response()->json(['status' => 'Token is Invalid'],401);
                }
                if($user===2){
                    return response()->json(['status' => 'Token is Expired'],401);
                }
            }
        
            
            if($user!=null){
                error_log("tidak sama dengan nul");
                $likes = DB::table('likes')->where('userId', $user->id)->where('storyId', $detail->idstory)->where('partId', $request["id"])->first();
                $favorites = DB::table('favorites')->where('userId', $user->id)->where('storyId', $detail->idstory)->where('partId', $request["id"])->first();
                if ($favorites) {
                    $cekfavorites = true;
                } else {
                    $cekfavorites = false;
                }
                if ($likes != null) {
                    if ($likes->like) {
                        $ceklikes = true;
                    } else {
                        $ceklikes = false;
                    }
                }
            }
            $countComment=DB::table("comments")->where("storyId", $detail->idstory)->where('partId', $request["id"])->count();
            $countLike=DB::table("likes")->where("storyId", $detail->idstory)->where('partId', $request["id"])->where("like",1)->count();



           
            // error_log($favorites->id);
            $o->titleParent=$parent->title;
            $o->author = $parent->author;
            $o->like = $ceklikes;
            $o->favorites = $cekfavorites;
            $o->detail = $detail;
            $o->TotalComment=$countComment;
            $o->totalLike=$countLike;
            $response->data = $o;
            $response->message = "get data stories detail";
            $affected = DB::table('part_stories')
            ->where('id', $request["id"])
            ->update([
                'countView' => $detail->countView+1,
            ]);
            return response()->json($response, 200);
        } catch (Exception $e) {
            error_log($e->getMessage());
            $response->message = "error load data";
            $response->error = $this->erro_500;
            return response()->json($response, 500);
        }
    }

    public function Recommended()
    {
        try {
            $response = new Response;
            $collection = new Collection();
            $detail = DB::table('recommended')->first();
            $data = json_encode($detail->recommended);
            foreach (explode(',', $detail->recommended) as $split) {
                error_log($split);
            }
            $query = Story::with('StoryCategory');
            foreach (explode(',', $detail->recommended) as $split) {
                $query->orWhere('id', $split);
            }
            $result = $query->paginate(10);

            $response->data = $result;
            $response->message = "get all data stories Recommended";

            return response()->json($response, 200);
        } catch (Exception $e) {
            $response->message = "error load data";
            $response->error = $this->erro_500;
            return response()->json("Error load data", 500);
        }
    }

    public function Popular()
    {
        try {

            $collection = new Collection();
            $value = env('APP_LOADIMAGE', 'default_value');

            $story = Story::with('StoryCategory')->orderBy('countView', 'desc')->paginate(5);
            $response = new Response;
            $response->data = $story;
            $response->message = "get all data stories popular";

            return response()->json($response, 200);
        } catch (Exception $e) {
            $response->message = "error load data";
            $response->error = $this->erro_500;
            return response()->json("Error load data", 500);
        }
    }
    public function Banners()
    {
        try {
            $story = DB::table('banners')->orderBy('created_at', 'asc')->paginate(10);
            $response = new Response;
            $response->data = $story;
            $response->message = "get all data Banner";
            return response()->json($story, 200);
        } catch (Exception $e) {
            $response->message = "error load data";
            $response->error = $this->erro_500;
            return response()->json("Error load data", 500);
        }
    }

    public function PostComment(Request $request)
    {
        try {
            $user=$this->GetMe();
            $comment = new Comment();
            $comment->userId = $user->id;
            $comment->storyId = $request["storyId"];
            $comment->partId = $request["partId"];
            $comment->comment = $request["comment"];
            $comment->Save();


            $response = new Response;
            // $response->data=$story;
            // $response->message = "Sukses insert data";
            $comments = DB::table('comments')->where('storyId', $request["storyId"])->where('partId', $request["partId"])->where('userId', $user->id)->orderBy('created_at', 'desc')->paginate(10);
            $response->data = $comments;
            $response->message = "Insert Success";
            return response()->json($response, 200);
        } catch (Exception $e) {
            error_log($e->getMessage());
            $response->message = "error Insert data";
            $response->error = $this->erro_500;
            return response()->json($response, 500);
        }
    }

    public function GetComment($id)
    {
        try {
            // $comments = DB::table('comments')->where('storyId', $id)->orderBy('created_at', 'desc')->paginate(10);
            $comments = DB::table('comments')
            ->join('users', 'comments.userId', '=', 'users.id')
            ->select('comments.*','users.name','users.picture')
            ->where('storyId', $id)->orderBy('created_at', 'desc')->paginate(10);
            $response = new Response;
            $response->data = $comments;
            $response->message = "get commnet";
            return response()->json($response, 200);
        } catch (Exception $e) {
            error_log($e->getMessage());
            $response->message = "error load data";
            $response->error = $this->erro_500;
            return response()->json($response, 500);
        }
    }

    public function PostLike(Request $request)
    {
        try {
            $user=$this->GetMe();
            error_log("------");
            error_log($request["userId"]);
            $response = new Response;
            $likes = DB::table('likes')->where('userId', $user->id)->where('storyId', $request["storyId"])->where('partId', $request["partId"])->first();

            if ($likes != null) {
                DB::table('likes')
                    ->where('id', $likes->id)
                    ->update(['like' => $request["like"]]);
                $response->message = "sukses Update data";
            } else {
                $like = new Like();
                $like->userId = $user->id;
                $like->storyId = $request["storyId"];
                $like->partId = $request["partId"];
                $like->like = $request["like"];
                $like->Save();

                // $response->data=$comments;
                $response->message = "sukses Like data";
            }


            return response()->json($response, 200);
        } catch (Exception $e) {
            error_log($e->getMessage());
            $response->message = $e->getMessage();
            $response->error = $this->erro_500;
            return response()->json($response, 500);
        }
    }

    public function CountLike($id)
    {
        try {
            $likes = DB::table('likes')->where('storyId', $id)->count();
            $o = new \stdClass();
            $o->count = $likes;
            $response = new Response;
            $response->data = $o;
            $response->message = "get Count data";
            return response()->json($response, 200);
        } catch (Exception $e) {
            error_log($e->getMessage());
            $response->message = "erro get Count data";
            $response->error = $this->erro_500;
            return response()->json($response, 500);
        }
    }

    public function GetdataKategory($id)
    {
        try {
            $response = new Response;
            $detail = DB::table('story_categories')->where('idcategory', $id)->get();
            $data = json_encode($detail);
            error_log($data);
            $query = Story::with('StoryCategory');
            if (count($detail) > 0) {
                foreach ($detail as $split) {
                    error_log($split->story_id);
                    $query->orWhere('id', $split->story_id);
                }
                $result = $query->get();
                $response->data = $result;
                $response->message = "get data category";
            } else {
                $response->data = "Category is not found";
                $response->message = "get data category";
            }



            return response()->json($response, 200);
        } catch (Exception $e) {
            $response->message = $e->getMessage();
            $response->error = $this->erro_500;
            return response()->json($response, 500);
        }
    }

    public function GetDataStoryBest()
    {
        try {

            $response = new Response;
            $collection = new Collection();
            $detail = DB::table('story_bests')->first();

            $query = Story::with('StoryCategory');
            foreach (explode(',', $detail->story_id) as $split) {
                $query->orWhere('id', $split);
            }
            $result = $query->get();

            $response->data = $result;
            $response->message = "get all data stories Best";

            return response()->json($response, 200);
        } catch (Exception $e) {
            $response->message = "error load data";
            $response->error = $this->erro_500;
            return response()->json($response, 500);
        }
    }
    public function GetDataStoryforyou()
    {
        try {
            $response = new Response;
            $data = new Collection;
            $value = env('APP_LOADIMAGE', 'default_value');


            // $collection = new Collection();
            $result = collect();
            $story = Story::with('StoryCategory')->orderBy('created_at', 'asc')->paginate(10);
            $response->data = $story;

            $response->message = "get all data stories for you";

            return response()->json($response, 200);
        } catch (Exception $e) {
            error_log($e->getMessage());
            $response->message = "error load data";
            $response->error = $this->erro_500;
            return response()->json($response, 500);
        }
    }
    public function GetDataSlideLeft($id)
    {
        try {
            $response = new Response;
            //  $data = new Collection;
            $value = env('APP_LOADIMAGE', 'default_value');
            $test = array();
            error_log($id);
            $param = explode(',', $id);
            $data = count($param);
            //  $te="1;
            error_log($param[0]);
            //  for(int n=0;n<$data;n++){

            //  }

            // $collection = new Collection();
            $result = collect();
            //  $story = Story::with('StoryCategory')->orderBy('created_at', 'asc')->paginate(10);
            //  $response->data=$story;

            $response->message = "get all data stories for you";

            return response()->json($response, 200);
        } catch (Exception $e) {
            error_log($e->getMessage());
            $response->message = "error load data";
            $response->error = $this->erro_500;
            return response()->json($response, 500);
        }
    }
    public function GetFavoriteByUserId(Request $request)
    {
        try {
            $user=$this->GetMe();
            
            $response = new Response;
            $collection = new Collection();
            $detail = DB::table('favorites')->where('userId', $user->id)->paginate(10);
            if(count($detail)>0){
                $data = json_encode($detail);
                foreach ($detail as $split) {
                    error_log($split);
                }
                $query = Story::with('StoryCategory');
                foreach ($detail as $split) {
                    $query->orWhere('id', $split->storyId);
                }
                $result = $query->get();
    
                $response->data = $result;
                $response->message = "get All data favoirete user";
    
                return response()->json($response, 200);
            }else{
              
                $response->message = "Not Found";
                $response->error="Not Found";
                return response()->json($response, 404);
            }
           
        } catch (Exception $e) {
            $response->message = "error load data";
            $response->error = $e->getMessage();
            return response()->json($response, 500);
        }
    }
    public function GetCommentPart($story, $id)
    {
        try {
            // $comments = DB::table('comments')->where('storyId', $story)->where('partId', $id)->orderBy('created_at', 'desc')->paginate(10);
            $comments = DB::table('comments')
            ->join('users', 'comments.userId', '=', 'users.id')
            ->select('comments.*','users.name','users.picture')
            ->where('storyId', $storyId)->where('storyId', $storyId)->where('partId', $id)->orderBy('created_at', 'asc')->paginate(10);
            $response = new Response;
            $response->data = $comments;
            $response->message = "get Data Comment";
            return response()->json($response, 200);
        } catch (Exception $e) {
            error_log($e->getMessage());
            $response->message = "error load data";
            $response->error = $this->erro_500;
            return response()->json($response, 500);
        }
    }
    public function SaveFavorite(Request $request)
    {
        try {
            $user=$this->GetMe();
            $fav = new Favorite();

            $fav->userId = $user->id;
            $fav->storyId = $request["storyId"];
            $fav->partId = $request["partId"];
            $fav->Save();

            $parent = new Favorite();
            $parent->userId = $user->id;
            $parent->storyId = $request["storyId"];
            $parent->partId = "00";
            $parent->Save();
    
            
            $response = new Response;
         
            $response->message = "Sukses insert data";

            return response()->json($response, 200);
        } catch (Exception $e) {
            error_log($e->getMessage());
            $response->message = "error Insert data";
            $response->error = $this->erro_500;
            return response()->json($response, 500);
        }
    }

    public function OnePopular()
    {
        try {
            $response = new Response;
            $config = DB::table('config_counts')->first();
            if ($config == null) {
                $story = Story::with('StoryCategory')->orderBy('countView', 'asc')->limit(1)->paginate(1);
            } else {
                $story = Story::with('StoryCategory')->where('id', $config->storyId)->paginate(1);
            }
            $response->data = $story;
            $response->message = "get One popular";
            return response()->json($response, 200);
        } catch (Exception $e) {
            error_log($e->getMessage());
            $response->message = "error Insert data";
            $response->error = $this->erro_500;
            return response()->json($response, 500);
        }
    }
    public function deleteFavorites(Request $request)
    {
        try {
            $user=$this->GetMe();
            $response = new Response;
            $userId = $request->input("userId");
            $storyId = $request->input("storyId");
            $partId = $request->input("partId");

            error_log($storyId);
            error_log($userId);
            $favorites = DB::table('favorites')->where('userId', $user->id)->where('storyId', $storyId)->where('partId',$partId)->delete();
            $favoritesparent = DB::table('favorites')->where('userId', $user->id)->where('storyId', $storyId)->where('partId',"00")->delete();
            if ($favorites != null) {
                $response->message = "Deleted Data favorites";
            } else {
                $response->message = "Data not found";
            }

            return response()->json($response, 200);
        } catch (Exception $e) {
            error_log($e->getMessage());
            $response->message = "error load data";
            $response->error = $this->erro_500;
            return response()->json($response, 500);
        }
    }
    public function GetTopCommentByDate(Request $request)
    {
        try {
            $response = new Response;
            $storyId = $request->input("storyId");
            $partId = $request->input("partId");

          
            // $favorites = DB::table('favorites')->where('userId', $userId)->where('storyId', $storyId)->delete();
            if($storyId != null ){
                $getComment = DB::table('comments')
                ->join('users', 'comments.userId', '=', 'users.id')
                ->select('comments.*','users.name','users.picture')
                ->where('storyId', $storyId)->orderBy('created_at', 'asc')->paginate(3);
            }else if ($storyId != null & $partId != null) {
                $getComment = DB::table('comments')
                ->join('users', 'comments.userId', '=', 'users.id')
                ->select('comments.*','users.name','users.picture')
                ->where('storyId', $storyId)->where('partId', $partId)->orderBy('created_at', 'asc')->paginate(3);
            } else  {
                $getComment = DB::table('comments')
                ->join('users', 'comments.userId', '=', 'users.id')
                ->select('comments.*','users.name','users.picture')
                ->where('storyId', $storyId)->orderBy('created_at', 'asc')->get();
            }
            $response->data = $getComment;
            $response->message = "Data Comment";

            return response()->json($response, 200);
        } catch (Exception $e) {
            error_log($e->getMessage());
            $response->message = $e->getMessage();
            $response->error = $this->erro_500;
            return response()->json($response, 500);
        }
    }
    public function GetMe(){
        
        try{
            $payload = JWTAuth::parseToken()->getPayload();
            $t=$payload->get('user');
            return $t;
        }catch(Exception $e){
            return null;
        }
        
    }
    public function SaveFavoriteParrent(Request $request)
    {
        try {
            $user=$this->GetMe();
            $fav = new Favorite();
            $fav->userId = $user->id;
            $fav->storyId = $request["storyId"];
            $fav->partId = "00";
            $fav->Save();
            
            $details = DB::table('part_stories')->select('id', 'idstory', 'titlePart', 'thumbnail', 'countView', 'created_at', 'updated_at', 'imageHeader', 'sub_title')->where('idstory', $request["storyId"])->get();
            foreach($details as $value){
                error_log($value->id);
                $fav2 = new Favorite();
                $fav2->userId = $user->id;
                $fav2->storyId = $request["storyId"];
                $fav2->partId = $value->id;
                $fav2->Save();
            }
            $response = new Response;
         
            $response->message = "Sukses insert data";

            return response()->json($response, 200);
        } catch (Exception $e) {
            error_log($e->getMessage());
            $response->message = "error Insert data";
            $response->error = $this->erro_500;
            return response()->json($response, 500);
        }
    }
    public function deleteFavoritesParent(Request $request)
    {
        try {
            $user=$this->GetMe();
            $response = new Response;
            $userId = $request->input("userId");
            $storyId = $request->input("storyId");

            error_log($storyId);
            error_log($userId);
            $favorites = DB::table('favorites')->where('userId', $user->id)->where('storyId', $storyId)->delete();
            if ($favorites != null) {
                $response->message = "Deleted Data favorites";
            } else {
                $response->message = "Data not found";
            }
            // $details = DB::table('favorites')->select('userId','storyId','partId')->where('storyId', $storyId)->get();
            // foreach($details as $value){
            //     $favorites = DB::table('favorites')->where('userId', $user->id)->where('storyId', $storyId)->delete();
            // }
            


            return response()->json($response, 200);
        } catch (Exception $e) {
            error_log($e->getMessage());
            $response->message = "error load data";
            $response->error = $this->erro_500;
            return response()->json($response, 500);
        }
    }
    public function searchByTitle(Request $request)
    {

        try {
            $response = new Response;
            $data = new Collection;
            $title=$request->input("title");
            $value = env('APP_LOADIMAGE', 'default_value');


            // $collection = new Collection();
            $result = collect();
            $story = Story::with('StoryCategory')->orWhere('title', 'like', '%' . $title . '%')->orderBy('created_at', 'asc')->paginate(10);
            $response->data = $story;

            $response->message = "get all data stories";

            return response()->json($response, 200);
        } catch (Exception $e) {
            error_log($e->getMessage());
            $response->message = "error load data";
            $response->error = $this->erro_500;
            return response()->json($response, 500);
        }
    }
    public function GetMeSpesial(){
        
        try{
            $user = JWTAuth::parseToken()->authenticate();
            $payload = JWTAuth::parseToken()->getPayload();
            $t=$payload->get('user');
            error_log("token valid");
            return $t;
        }catch(Exception $e){
            error_log($e->getMessage());
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){
                error_log("sini invalid");
               return 1;
            }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){
                return 2;
            }
            // else{
            //     return 401;
                // return response()->json(['status' => 'Authorization Token not found'],401);
            // }
            return null;
        }
      
        
    }

  
}

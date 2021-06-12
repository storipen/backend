<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\partStory;
use Illuminate\Contracts\Pagination\Paginator;
use App\pratStory;
use Illuminate\Support\Facades\DB;
use Exception;
use Intervention\Image\Facades\Image;

class partStoryController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index($id)
    {
        error_log($id);
        $partStory = partStory::where('idstory', $id)->paginate(5);
        return view('partstory', compact('partStory', 'id'));
    }

    public function addpartStory($id)
    {
        return view('addpartstory', compact('id'));
    }
    public function savepartstory(Request $request)
    {
        error_log("MASUK SAVE");
        try {
            $value = env('APP_LOADIMAGE', 'default_value');
            $uploaddirectory = env('APP_DIRECTORYIMAGE', 'default_value');
            $partStory = new partStory();
            $partStory->idstory = $request['idstory'];
            $partStory->titlePart = $request['title'];
            $partStory->content = $request['content'];
            $partStory->sub_title = $request['subtitle'];
            $imageName = time() . '.' . $request->image->extension();
            // $request->image->move($uploaddirectory, $imageName);
            $image = $request->file('image');
            $img = Image::make($image->path());
            $img->resize(300, 500, function ($constraint) {
                $constraint->aspectRatio();
            })->save($uploaddirectory.'/'.$imageName);
            $partStory->thumbnail = $value . $imageName;
            $partStory->imageHeader = $imageName;
            $partStory->countView = 0;
            $partStory->save();

            return redirect()->route('listpartStory', $request['idstory']);
        } catch (Exception $e) {
            echo  $e->getMessage();
        }
    }
    public function getEditPartStory($id)
    {
        error_log($id);
        $partStory = partStory::where('id', $id)->first();
        //  error_log($partStory['titlePart']);
        return view('editpartstory', compact('partStory'));
    }

    public function updatepartStory(Request $request)
    {

        try {
            $partStory = partStory::where('id', $request['id'])->first();
            $value = env('APP_LOADIMAGE', 'default_value');
            $uploaddirectory = env('APP_DIRECTORYIMAGE', 'default_value');
            if ($request->image != null) {
                $imageName = time() . '.' . $request->image->extension();
                // $request->image->move($uploaddirectory, $imageName);
                $image = $request->file('image');
                $img = Image::make($image->path());
                $img->resize(300, 500, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($uploaddirectory.'/'.$imageName);
                $imageNameUpdate= $value . $imageName ;
            } else {

                $imageNameUpdate=$partStory->thumbnail;
                $imageName = $partStory->imageHeader;
            }

            $affected = DB::table('part_stories')
                ->where('id', $request['id'])
                ->update([
                    'titlePart' => $request['title'],
                    'content' => $request['content'],
                    'thumbnail' => $imageNameUpdate,
                    'imageHeader'=> $imageName,
                ]);

            return redirect()->route('listpartStory', $request['idstory']);
        } catch (Exception $e) {
            echo  $e->getMessage();
        }
    }
    public function deletePartStory($storyID,$id){
        try{
            error_log("__");
            $delete= partStory::find($id);
            $delete->delete();
            return redirect()->route('listpartStory', $storyID);
        }catch(Exception $e){
            error_log("ppp");
            error_log($e->getMessage());
        }
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Story;

class RecommendedController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }
    //
    public function index()
    {
        $story = Story::paginate(10);
        return view('story', compact('story'));
    }
}

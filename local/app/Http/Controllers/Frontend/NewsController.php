<?php

namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;

class NewsController extends Controller
{
	public function index(){

		return view('frontend/news');
	}


}
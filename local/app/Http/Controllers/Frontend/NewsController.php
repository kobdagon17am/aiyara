<?php

namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;

class NewsController extends Controller
{
	public function index(){

		$business_location_id_fk = Auth::guard('c_user')->user()->business_location_id;

		$data = DB::table('db_news')
		->where('business_location_id_fk','=',$business_location_id_fk)
		->whereDate('start_date', '<=',date('Y-m-d')) 
		->whereDate('end_date', '>=',date('Y-m-d')) 
		->orderby('id','DESC')
		->get();

		return view('frontend/news',compact('data'));
	}

	public function news_detail($news_id){

		$data = DB::table('db_news')
		->where('id','=',$news_id)
		->first();

		return view('frontend/news_detail',compact('data'));
	}


}
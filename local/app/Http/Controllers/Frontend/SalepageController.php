<?php

namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;

class SalepageController extends Controller
{
	public function salepage_1(){
		return view('frontend/salepage/salepage_1');
	} 


}
<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Cart;

class CartController extends Controller
{
	public function index(){
		$cartCollection = Cart::getContent();
		$data=$cartCollection->toArray();
		return view('frontend/product/cart',compact('data'));

	}
}
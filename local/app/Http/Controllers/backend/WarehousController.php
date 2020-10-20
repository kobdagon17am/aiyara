<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class WarehousController extends Controller
{

    public function index(Request $request)
    {

      $dsWarehouse  = \App\Models\Dataset\Warehouse::get();
      return view('backend.manage_warehouse')->with(['dsWarehouse'=>$dsWarehouse]);

      // return view('backend.manage_warehouse');

    }
}

<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;

class PromotionCodeController extends Controller
{

    public function index(Request $request)
    {
        return view("backend.promotion_cus.index");
    }

    public function create(Request $request)
    {

    }
    
    public function store(Request $request)
    {

    }

    public function edit($id)
    {

    }

    public function update(Request $request, $id)
    {
    }

   public function form($id=NULL)
    {

    }

    public function destroy($id)
    {
    }

    public function Datatable(Request $req){

      $sTable = \App\Models\Backend\PromotionCode::search()->orderBy('id', 'asc');
      $sQuery = \DataTables::of($sTable);
      return $sQuery
      ->make(true);
    }



}

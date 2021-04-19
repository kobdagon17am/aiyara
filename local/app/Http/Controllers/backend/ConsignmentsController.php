<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;

class ConsignmentsController extends Controller
{

    public function index(Request $request)
    {
    }

    public function create()
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

    public function Datatable(){
      $sTable = \App\Models\Backend\Consignments::search();
      $sQuery = \DataTables::of($sTable);
      return $sQuery
      ->make(true);
    }

    public function DatatableMap(){
      $sTable = \App\Models\Backend\Consignments::search();
      $sQuery = \DataTables::of($sTable);
      return $sQuery
      ->make(true);
    }


    public function DatatableSent(){
      $sTable = \App\Models\Backend\Consignments::search();
      $sQuery = \DataTables::of($sTable);
      return $sQuery
      ->make(true);
    }

}

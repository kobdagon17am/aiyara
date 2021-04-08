<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;
use PDF;

class Stock_cardController extends Controller
{

    public function index(Request $request)
    {
       // dd($request);
    }

    public function create()
    {
    }

    public function store(Request $request)
    {
      // dd($request->all());
    }

    public function edit($id)
    {
       // dd($id);
    }

    public function update(Request $request, $id)
    {
      // dd($request->all());
    }

   public function form($id=NULL)
    {
  
    }

    public function destroy($id)
    {
      
    }

    
    public function Datatable(){
      $sTable = \App\Models\Backend\Stock_card::search()->orderBy('action_date', 'asc');
      $sQuery = \DataTables::of($sTable);
      return $sQuery
      ->addColumn('action_user', function($row) {
        if(@$row->action_user!=''){
          $sD = DB::select(" select * from ck_users_admin where id=".$row->action_user." ");
           return @$sD[0]->name;
        }else{
          return '';
        }
      })      
      ->addColumn('approver', function($row) {
        if(@$row->approver!=''){
          $sD = DB::select(" select * from ck_users_admin where id=".$row->approver." ");
           return @$sD[0]->name;
        }else{
          return '';
        }
      })        
      ->make(true);
    }




}

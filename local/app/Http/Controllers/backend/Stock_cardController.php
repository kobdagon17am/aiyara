<?php

namespace App\Http\Controllers\backend;

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
      // $sTable = \App\Models\Backend\Stock_card::search()->orderBy('action_date', 'asc');
      DB::select(" SET @csum := 0; ");
      // $sTable = DB::select(" 
      //     SELECT db_stock_card.*,
      //     CASE WHEN @csum + ( CASE WHEN amt_out>0 THEN -(amt_out) ELSE amt_in END ) > 0 THEN 
      //     (@csum := @csum + ( CASE WHEN amt_out>0 THEN -(amt_out) ELSE amt_in END )) ELSE 0 END
      //      as remain FROM db_stock_card ORDER BY action_date;
      //     ");
      $sTable = DB::select(" 
          SELECT db_stock_card.*,(@csum := @csum + ( CASE WHEN amt_out>0 THEN -(amt_out) ELSE amt_in END )) as remain FROM db_stock_card ORDER BY action_date;
          ");     
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


 
    public function Datatable01(){

      DB::select(" SET @csum := 0; ");

      $sTable = DB::select(" 
          SELECT db_stock_card.*,(@csum := @csum + ( CASE WHEN amt_out>0 THEN -(amt_out) ELSE amt_in END )) as remain FROM db_stock_card ORDER BY action_date;
          ");     
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
      // ->addColumn('remain', function($row) {
      //   if(!empty(@$row->remain) && @$row->remain>0 ){
      //      return @$row->remain;
      //   }else{
      //      return  0 ;
      //   }
      // })   
      ->make(true);
    }




}

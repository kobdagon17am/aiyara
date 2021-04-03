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
     
      //  ->addColumn('business_location', function($row) {
      //   if(@$row->business_location_id_fk!=''){
      //        $P = DB::select(" select * from dataset_business_location where id=".@$row->business_location_id_fk." ");
      //        return @$P[0]->txt_desc;
      //   }else{
      //        return '-';
      //   }
      // })       
      // ->addColumn('customer_name', function($row) {
      // 	if(@$row->customer_id_fk!=''){
      //    	$Customer = DB::select(" select * from customers where id=".@$row->customer_id_fk." ");
      //   	return @$Customer[0]->prefix_name.@$Customer[0]->first_name." ".@$Customer[0]->last_name;
      // 	}else{
      // 		return '';
      // 	}
      // })
      // ->addColumn('commission_cost', function($row) {
      //   return number_format($row->commission_cost,2);
      // })     
      // ->addColumn('tax_amount', function($row) {
      //   return number_format($row->tax_amount,2);
      // })        
      ->make(true);
    }




}

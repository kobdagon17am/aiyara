<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;
use PDF;

class TaxdataController extends Controller
{

    public function index(Request $request)
    {
        $sBusiness_location = \App\Models\Backend\Business_location::when(auth()->user()->permission !== 1, function ($query) {
            return $query->where('id', auth()->user()->business_location_id_fk);
        })->get();
        $sBranchs = \App\Models\Backend\Branchs::when(auth()->user()->permission !== 1, function ($query) {
            return $query->where('id', auth()->user()->branch_id);
        })->get();

      return View('backend.taxdata.index')->with(
        array(
           'sBusiness_location'=>$sBusiness_location,'sBranchs'=>$sBranchs
        ) );

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

    public function createPDFTaxtvi($id)
     {
        // dd($id);
        $data = [$id];
        $pdf = PDF::loadView('backend.taxdata.taxtvi',compact('data'));
        // return $pdf->download('cover_sheet.pdf'); // โหลดทันที
        return $pdf->stream('receipt_sheet.pdf'); // เปิดไฟลฺ์

    }
    
    public function Datatable(){
      $sTable = \App\Models\Backend\Taxdata::search()->orderBy('id', 'asc');

    //   if(isset($request->start_date)){
    //     if($request->start_date!=''){
    //         $taxs = DB::table('db_taxdata')
    //         ->where('action_date','>=',$request->start_date)
    //         ->where('action_date','<=',$request->end_date)
    //         ->orderBy('action_date','desc');

    //         if(isset($request->business_location_id_fk)){
    //             if($request->business_location_id_fk!=''){
    //                 $taxs = $taxs->where('business_location_id_fk',$request->business_location_id_fk);
    //             }
    //         }
          
    //         if(isset($request->customer_id)){
    //             if($request->customer_id!=''){
    //                 $taxs = $taxs->where('customer_id_fk',$request->customer_id);
    //             }
    //         }
    //         // $taxs = $taxs->get();
    //     }
    // }

      $sQuery = \DataTables::of($sTable);
      return $sQuery
       ->addColumn('business_location', function($row) {
        if(@$row->business_location_id_fk!=''){
             $P = DB::select(" select * from dataset_business_location where id=".@$row->business_location_id_fk." ");
             return @$P[0]->txt_desc;
        }else{
             return '-';
        }
      })       
      ->addColumn('customer_name', function($row) {
      	if(@$row->customer_id_fk!=''){
         	$Customer = DB::select(" select * from customers where id=".@$row->customer_id_fk." ");
        	return @$Customer[0]->user_name ." : ". @$Customer[0]->prefix_name.@$Customer[0]->first_name." ".@$Customer[0]->last_name;
      	}else{
      		return '';
      	}
      })
      ->addColumn('commission_cost', function($row) {
        return number_format($row->commission_cost,2);
      })     
      ->addColumn('tax_amount', function($row) {
        return number_format($row->tax_amount,2);
      })        
      ->make(true);
    }

    public function Datatable2(Request $request){
      // $sTable = \App\Models\Backend\Taxdata::search()->orderBy('id', 'asc');

      if(isset($request->start_date)){
        if($request->start_date!=''){
            $taxs = DB::table('db_taxdata')
            ->where('action_date','>=',$request->start_date)
            ->where('action_date','<=',$request->end_date)
            ->orderBy('action_date','desc');

            if(isset($request->business_location_id_fk)){
                if($request->business_location_id_fk!=''){
                    $taxs = $taxs->where('business_location_id_fk',$request->business_location_id_fk);
                }
            }
          
            if(isset($request->customer_id)){
                if($request->customer_id!=''){
                    $taxs = $taxs->where('customer_id_fk',$request->customer_id);
                }
            }
            // $taxs = $taxs->get();
        }
    }

      $sQuery = \DataTables::of($taxs);
      return $sQuery
       ->addColumn('business_location', function($row) {
        if(@$row->business_location_id_fk!=''){
             $P = DB::select(" select * from dataset_business_location where id=".@$row->business_location_id_fk." ");
             return @$P[0]->txt_desc;
        }else{
             return '-';
        }
      })       
      ->addColumn('customer_name', function($row) {
      	if(@$row->customer_id_fk!=''){
         	$Customer = DB::select(" select * from customers where id=".@$row->customer_id_fk." ");
        	return @$Customer[0]->prefix_name.@$Customer[0]->first_name." ".@$Customer[0]->last_name;
      	}else{
      		return '';
      	}
      })
      ->addColumn('commission_cost', function($row) {
        return number_format($row->commission_cost,2);
      })     
      ->addColumn('tax_amount', function($row) {
        return number_format($row->tax_amount,2);
      })        
      ->make(true);
    }



}

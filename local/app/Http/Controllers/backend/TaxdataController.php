<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;
use PDF;

class TaxdataController extends Controller
{

    public function index(Request $request)
    {
      return view('backend.taxdata.index');
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
      $sQuery = \DataTables::of($sTable);
      return $sQuery
      ->addColumn('customer_name', function($row) {
      	if(@$row->customer_id_fk!=''){
         	$Customer = DB::select(" select * from customers where id=".@$row->customer_id_fk." ");
        	return @$Customer[0]->prefix_name.@$Customer[0]->first_name." ".@$Customer[0]->last_name;
      	}else{
      		return '';
      	}
      })
      ->addColumn('order_amount', function($row) {
        return number_format($row->order_amount,2);
      })     
      ->addColumn('tax_amount', function($row) {
        return number_format($row->tax_amount,2);
      })        
      ->make(true);
    }




}

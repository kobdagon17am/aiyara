<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;
use Redirect;

class Po_supplier_products_getController extends Controller
{

    public function index(Request $request)
    {

      return view('backend.po_supplier_products_get.index');

    }

  public function create($id)
    {
       // dd($id);
       $Po_supplier_products = \App\Models\Backend\Po_supplier_products::find($id);
       // dd($Po_supplier_products);

       $Po_supplier = \App\Models\Backend\Po_supplier::find($Po_supplier_products->po_supplier_id_fk);
       // dd($Po_supplier);
       $sProduct = \App\Models\Backend\Products_details::where('lang_id', 1)->get();
        $sGetStatus = DB::select(" select * from dataset_get_product_status  ");
       return View('backend.po_supplier_products_get.form')->with(array(
        'Po_supplier_products'=>$Po_supplier_products,
        'Po_supplier'=>$Po_supplier,
        'sProduct'=>$sProduct,
         'sGetStatus'=>$sGetStatus,
         ) );
       // return View('backend.po_supplier_products_get.form');

    }

    public function store(Request $request)
    {
      // dd($request->all());
      return $this->form();
    }

    public function edit($id)
    {
        $sRow = \App\Models\Backend\Po_supplier_products_get::find($id);
        // dd($sRow);
        $Po_supplier_products = \App\Models\Backend\Po_supplier_products::find($sRow->po_supplier_products_id_fk);
        // dd($Po_supplier_products);
        $Po_supplier = \App\Models\Backend\Po_supplier::find($Po_supplier_products->po_supplier_id_fk);
        // dd($Po_supplier);
        $sProduct = \App\Models\Backend\Products_details::where('lang_id', 1)->get();
        $sGetStatus = DB::select(" select * from dataset_get_product_status  ");
       return View('backend.po_supplier_products_get.form')->with(array('sRow'=>$sRow ,
        'sRow'=>$sRow,
        'Po_supplier_products'=>$Po_supplier_products,
        'Po_supplier'=>$Po_supplier,
        'sProduct'=>$sProduct,
        'sGetStatus'=>$sGetStatus,
         ) );
    }

    public function update(Request $request, $id)
    {
      // dd($request->all());
      return $this->form($id);
    }


    public function form($id=NULL)
    {
      \DB::beginTransaction();
      try {
        // dd($id);
          if( $id ){
            $sRow = \App\Models\Backend\Po_supplier_products_get::find($id);
          }else{
            $sRow = new \App\Models\Backend\Po_supplier_products_get;

            $db_po_supplier_products_get = DB::select(" SELECT time_get FROM `db_po_supplier_products_get` where po_supplier_products_id_fk=".request('po_supplier_products_id_fk')." order by time_get desc ");
            if($db_po_supplier_products_get){
                $sRow->time_get = $db_po_supplier_products_get[0]->time_get + 1 ;

            }else{
                $sRow->time_get = 1 ;
            }
            
          }

          $sRow->po_supplier_products_id_fk    = request('po_supplier_products_id_fk');
          $sRow->amt    = request('amt');
          $sRow->get_status    = request('get_status');
          $sRow->created_at = date('Y-m-d H:i:s');
          $sRow->save();

          if(request('get_status')){

              DB::select(" UPDATE `db_po_supplier_products` SET get_status=".request('get_status')." where id=".request('po_supplier_products_id_fk')." ");

              @$r1 = DB::select(" SELECT * FROM `db_po_supplier_products` where id=".request('po_supplier_products_id_fk')." ");
              @$r2 = DB::select(" SELECT * FROM `db_po_supplier_products` where po_supplier_id_fk in(".$r1[0]->po_supplier_id_fk.") AND get_status=2 ");
              if(@$r2){
                DB::select(" UPDATE `db_po_supplier` SET po_status=2 where id=".@$r2[0]->po_supplier_id_fk." ");
              }else{
                @$r3 = DB::select(" SELECT * FROM `db_po_supplier_products` where po_supplier_id_fk in(".@$r1[0]->po_supplier_id_fk.") AND get_status not in (2) ");
                if(@$r3){
                  DB::select(" UPDATE `db_po_supplier` SET po_status=1 where id=".@$r3[0]->po_supplier_id_fk." ");
                }
              }

          }

          \DB::commit();

          return redirect()->to(url("backend/po_supplier_products/".request('po_supplier_products_id_fk')."/edit"));

      } catch (\Exception $e) {
        echo $e->getMessage();
        \DB::rollback();
        // return redirect()->action('backend\Po_supplier_products_getController@index')->with(['alert'=>\App\Models\Alert::e($e)]);
      }
    }

    public function destroy($id)
    {
      $sRow = \App\Models\Backend\Po_supplier_products::find($id);
      if( $sRow ){
        $sRow->forceDelete();
      }
      return response()->json(\App\Models\Alert::Msg('success'));
    }

  
    public function Datatable(Request $req){
      // $sTable = DB::select("select * from db_po_supplier_products_get where id=$req->po_supplier_products_id_fk  ");
      $sTable = \App\Models\Backend\Po_supplier_products_get::search()->orderBy('id', 'asc');
      $sQuery = \DataTables::of($sTable);
      return $sQuery
      ->addColumn('get_status', function($row) {
        if($row->get_status==1){
          return 'ได้รับสินค้าครบแล้ว';
        }else if($row->get_status==2){
          return 'ยังค้างรับสินค้าจาก Supplier';
        }else if($row->get_status==3){
          return 'ยกเลิกรายการสินค้านี้';
        }else{
          return 'อยู่ระหว่างการดำเนินการ';
        }
      })
      ->make(true);
    }


}

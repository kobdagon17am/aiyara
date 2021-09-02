<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;
use Redirect;

class Po_supplier_productsController extends Controller
{

    public function index(Request $request)
    {

      return view('backend.po_supplier_products.index');

    }

  public function create($id)
    {
       // dd($id);

       $sRowNew = \App\Models\Backend\Po_supplier::find($id);
       // dd($sRowNew);
       // dd($sRowNew->po_number);
       // $sProduct = \App\Models\Backend\Products::get();
       $sProduct = \App\Models\Backend\Products_details::where('lang_id', 1)->get();
       $sProductUnit = \App\Models\Backend\Product_unit::where('id', 4)->where('lang_id', 1)->get();
       return View('backend.po_supplier_products.form')->with(array(
        'sRowNew'=>$sRowNew,
        'sProduct'=>$sProduct,
        'sProductUnit'=>$sProductUnit,
      ));

    }

    public function store(Request $request)
    {
      return $this->form();
    }

    public function edit($id)
    {
       $sRow = \App\Models\Backend\Po_supplier_products::find($id);
       // dd($sRow);
       $sRowNew = \App\Models\Backend\Po_supplier::find($sRow->po_supplier_id_fk);
       $sProduct = \App\Models\Backend\Products_details::where('lang_id', 1)->get();
       // dd($sProduct);
       $sProductUnit = \App\Models\Backend\Product_unit::get();
       $sGetStatus = DB::select(" select * from dataset_get_product_status  ");
       return View('backend.po_supplier_products.form')->with(array('sRow'=>$sRow , 'id'=>$id, 'sRowNew'=>$sRowNew ,
        'sProduct'=>$sProduct,
        'sProductUnit'=>$sProductUnit,
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
          if( $id ){
            $sRow = \App\Models\Backend\Po_supplier_products::find($id);
          }else{
            $sRow = new \App\Models\Backend\Po_supplier_products;
          }

          $sRow->po_supplier_id_fk    = request('po_supplier_id_fk');
          $sRow->product_id_fk    = request('product_id_fk');
          $sRow->product_amt    = request('product_amt');
          $sRow->product_unit    = request('product_unit');
          // $sRow->product_unit    = 4 ;
          $sRow->get_status    = request('get_status');
          
          $sRow->created_at = date('Y-m-d H:i:s');
          $sRow->save();

          \DB::commit();

          return redirect()->to(url("backend/po_supplier/".request('po_supplier_id_fk')."/edit"));

      } catch (\Exception $e) {
        echo $e->getMessage();
        \DB::rollback();
        return redirect()->action('backend\Po_supplier_productsController@index')->with(['alert'=>\App\Models\Alert::e($e)]);
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

    public function Datatable(){
      $sTable = \App\Models\Backend\Po_supplier_products::search()->orderBy('id', 'asc');
      $sQuery = \DataTables::of($sTable);
      return $sQuery
      ->addColumn('product_name', function($row) {
                  // return $row->product_unit_id_fk;
        if(!empty($row->product_id_fk)){

          $Products = DB::select(" 
                SELECT products.id as product_id,
                  products.product_code,
                  (CASE WHEN products_details.product_name is null THEN '* ไม่ได้กรอกชื่อสินค้า' ELSE products_details.product_name END) as product_name ,
                  products_cost.member_price,
                  products_cost.pv
                  FROM
                  products_details
                  Left Join products ON products_details.product_id_fk = products.id
                  LEFT JOIN products_cost on products.id = products_cost.product_id_fk
                  WHERE lang_id=1 AND products.id= ".$row->product_id_fk."

           ");

           return  @$Products[0]->product_code." : ".@$Products[0]->product_name;

         }
      })
      ->addColumn('product_unit_desc', function($row) {
          $sP = \App\Models\Backend\Product_unit::find($row->product_unit);
          return $sP->product_unit;
      })
      ->addColumn('get_status', function($row) {
        if($row->product_amt==$row->product_amt_receive) @$get_status=1;
        if($row->product_amt>$row->product_amt_receive) @$get_status=2;
        if(@$get_status==1){
          return '<font color=green>ได้รับสินค้าครบแล้ว</font>';
        }else if(@$get_status==2){
          return '<font color=red>ยังค้างรับสินค้าจาก Supplier</font>';
        }else if(@$row->get_status==3){
          return 'ยกเลิกรายการสินค้านี้';
        }else{
          return 'อยู่ระหว่างการดำเนินการ';
        }
      })
      ->escapeColumns('get_status')
      ->addColumn('get_status_2', function($row) {
          return @$row->get_status;
      })
      ->make(true);
    }





}

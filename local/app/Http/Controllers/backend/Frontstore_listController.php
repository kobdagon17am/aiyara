<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;

class Frontstore_listController extends Controller
{

    public function index(Request $request)
    {

      $Frontstore_list = \App\Models\Backend\Frontstore_list::get();
      // dd($Frontstore_list);
      $sUser = DB::select(" select * from ck_users_admin ");
      $sApproveStatus = DB::select(" select * from dataset_approve_status where status=1 ");


      $Customer = DB::select(" select * from customers ");
      return View('backend.frontstore_list.index')->with(
        array(
           'Customer'=>$Customer,
           'sUser'=>$sUser,
           'sApproveStatus'=>$sApproveStatus,
        ) );
      
    }

 public function create()
    {
      $sUser = \App\Models\Backend\Permission\Admin::get();

      $Products = DB::select("SELECT products.id as product_id,
      products.product_code,
      (CASE WHEN products_details.product_name is null THEN '* ไม่ได้กรอกชื่อสินค้า' ELSE products_details.product_name END) as product_name 
      FROM
      products_details
      Left Join products ON products_details.product_id_fk = products.id
      WHERE lang_id=1");

      $Customer = DB::select(" select * from customers ");
      $sPurchase_type = DB::select(" select * from dataset_purchase_type where status=1 ");
      $sPay_type = DB::select(" select * from dataset_pay_type where status=1 ");
      $sDistribution_channel = DB::select(" select * from dataset_distribution_channel where status=1  ");
      $sProductUnit = \App\Models\Backend\Product_unit::where('lang_id', 1)->get();
      return View('backend.frontstore_list.form')->with(
        array(
           'Customer'=>$Customer,
           'sPurchase_type'=>$sPurchase_type,
           'sPay_type'=>$sPay_type,
           'sProductUnit'=>$sProductUnit,
           'sDistribution_channel'=>$sDistribution_channel,
           'Products'=>$Products,
        ) );
    }
    public function store(Request $request)
    {
      // dd($request->all());
      return $this->form();
    }

    public function edit($id)
    {
      // dd($id);
      $sRow = \App\Models\Backend\Frontstore_list::find($id);
      $CusAddrFrontstore_list = \App\Models\Backend\CusAddrFrontstore_list::where('frontstore_list_id_fk',$id)->get();
      $sUser = \App\Models\Backend\Permission\Admin::get();

      $Delivery_location = DB::select(" select id,txt_desc from dataset_delivery_location  ");

      $Products = DB::select("SELECT products.id as product_id,
      products.product_code,
      (CASE WHEN products_details.product_name is null THEN '* ไม่ได้กรอกชื่อสินค้า' ELSE products_details.product_name END) as product_name 
      FROM
      products_details
      Left Join products ON products_details.product_id_fk = products.id
      WHERE lang_id=1");

      $Customer = DB::select(" select * from customers ");
      $sPurchase_type = DB::select(" select * from dataset_purchase_type where status=1 ");
      $sPay_type = DB::select(" select * from dataset_pay_type where status=1 ");
      $sDistribution_channel = DB::select(" select * from dataset_distribution_channel where status=1  ");
      $sProductUnit = \App\Models\Backend\Product_unit::where('lang_id', 1)->get();

      $sProvince = DB::select(" select *,name_th as province_name from dataset_provinces order by name_th ");

      $sAmphures = DB::select(" select *,name_th as amphur_name from dataset_amphures order by name_th ");

      $sTambons = DB::select(" select *,name_th as tambon_name from dataset_districts order by name_th ");


      return View('backend.frontstore_list.form')->with(
        array(
           'sRow'=>$sRow,
           'Customer'=>$Customer,
           'sPurchase_type'=>$sPurchase_type,
           'sPay_type'=>$sPay_type,
           'sProductUnit'=>$sProductUnit,
           'sDistribution_channel'=>$sDistribution_channel,
           'Products'=>$Products,
           'sProvince'=>$sProvince,
           'sAmphures'=>$sAmphures,
           'sTambons'=>$sTambons,
           'Delivery_location'=>$Delivery_location,
           'CusAddrFrontstore_list'=>$CusAddrFrontstore_list,
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
            $sRow = \App\Models\Backend\Frontstore_list::find($id);
          }else{
            $sRow = new \App\Models\Backend\Frontstore_list;
          }

  // `customers_id_fk` int(11) DEFAULT '0' COMMENT 'Ref>customers>id',
  // `distribution_channel_id_fk` int(11) DEFAULT '0' COMMENT 'Ref>dataset_distribution_channel>id',
  // `purchase_type_id_fk` int(11) DEFAULT '0' COMMENT 'Ref>dataset_purchase_type>id',
  // `pay_type_id_fk` int(11) DEFAULT '0' COMMENT 'Ref>dataset_pay_type>id',
  // `note` text COMMENT 'หมายเหตุ',
  
          $sRow->customers_id_fk    = request('customers_id_fk');
          $sRow->distribution_channel_id_fk    = request('distribution_channel_id_fk');
          $sRow->purchase_type_id_fk    = request('purchase_type_id_fk');
          $sRow->pay_type_id_fk    = request('pay_type_id_fk');
          $sRow->note    = request('note');

          $sRow->action_user = \Auth::user()->id;
          $sRow->action_date = date('Y-m-d H:i:s');
                    
          $sRow->created_at = date('Y-m-d H:i:s');
          $sRow->save();

          \DB::commit();

           return redirect()->to(url("backend/frontstore_list/".$sRow->id."/edit"));
           

      } catch (\Exception $e) {
        echo $e->getMessage();
        \DB::rollback();
        return redirect()->action('backend\Frontstore_listController@index')->with(['alert'=>\App\Models\Alert::e($e)]);
      }
    }

    public function destroy($id)
    {
      // dd($id);
      $sRow = \App\Models\Backend\Frontstore_list::find($id);
      if( $sRow ){
        $sRow->forceDelete();
      }
      return response()->json(\App\Models\Alert::Msg('success'));
    }

    public function Datatable(){
      $sTable = \App\Models\Backend\Frontstore_list::search()->orderBy('id', 'asc');
      $sQuery = \DataTables::of($sTable);
      return $sQuery
      ->addColumn('customer_name', function($row) {
        $Customer = DB::select(" select * from customers where id=".$row->customers_id_fk." ");
        return $Customer[0]->prefix_name.$Customer[0]->first_name." ".$Customer[0]->last_name;
      })
      ->addColumn('purchase_type', function($row) {
          $purchase_type = DB::select(" select * from dataset_purchase_type where id=".$row->purchase_type_id_fk." ");
          return $purchase_type[0]->txt_desc;
      }) 
      ->addColumn('total_price', function($row) {
          $total_price = DB::select(" select sum(total_price) as tt from db_frontstore_list_products_list where frontstore_list_id_fk=".$row->id." ");
          return $total_price[0]->tt;
      })       
      ->addColumn('updated_at', function($row) {
        return is_null($row->updated_at) ? '-' : $row->updated_at;
      })
      ->make(true);
    }



}

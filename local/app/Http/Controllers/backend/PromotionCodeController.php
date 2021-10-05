<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use PDO;
use File;
use Session;

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

      $coupon_name = $req->couponName;

      $startDate = $req->startDate;
      $endDate = $req->endDate;
      $status = $req->pstatus;

      $sTable = \App\Models\Backend\PromotionCode::search()
        ->leftJoin('promotions', 'promotions.id', 'db_promotion_code.promotion_id_fk')
        ->when($coupon_name, function ($query, $coupon_name) {
          return $query->where('promotions.name_thai', 'LIKE', "%{$coupon_name}%");
        })
        ->when($startDate, function ($query, $startDate) {
          return $query->where('db_promotion_code.pro_sdate', '>=', $startDate);
        })
        ->when($endDate, function ($query, $endDate) {
          return $query->where('db_promotion_code.pro_edate', '<=', $endDate);
        })
        ->when($status, function ($query, $status) {
            if ($status == -1) {
              return $query->where('db_promotion_code.pro_edate', '<', date('Y-m-d'));
            } else if ($status == 2) {
              return $query->where('db_promotion_code.pro_edate', '>', date('Y-m-d'))
                ->where('db_promotion_code.status', 0);
            } else {
              return $query->where('db_promotion_code.status', $status);
            }
        });
      $sQuery = \DataTables::of($sTable);
      return $sQuery
      ->addColumn('promotion_name', function($row) {
        if(@$row->promotion_id_fk){
          // $d = \App\Models\Backend\Promotions::find($row->promotion_id_fk)->get();
          $d = DB::select(" select * from promotions where id=".$row->promotion_id_fk." ");
          return @$d[0]->name_thai;
        }
      })
      ->addColumn('pro_sdate', function($row) {
        $d = strtotime($row->pro_sdate); return date("d/m/", $d).(date("Y", $d)+543);
      })
      ->addColumn('pro_edate', function($row) {
        $d = strtotime($row->pro_edate); return date("d/m/", $d).(date("Y", $d)+543);
      })
      ->addColumn('status', function($row) {
          //  `pro_status` int(1) DEFAULT '1' COMMENT '1=ใช้งานได้,2=ถูกใช้แล้ว,3=หมดอายุแล้ว,4=import excel,5=Gen code',
            // $sRowProCus = \App\Models\Backend\PromotionCus::where('promotion_code_id_fk', $row->id)->get();
            // if(@$sRowProCus[0]->pro_status==3){
            //    return "<span style='color:red;'>Expired/หมดอายุ</span>";
            // }else{
              // `status` int(1) DEFAULT '1' COMMENT 'การใช้งาน/การแสดงผล 1=แสดงผล,0=ปิดการแสดง',
               if( @$row->pro_edate < date("Y-m-d") ){
                  return "<span style='color:red;'>Expired/หมดอายุ</span>";
               }elseif(@$row->status==1){
                  return "ใช้งานได้";
               }else{
                  return "ปิดการใช้งาน";
               }
            // }
            // return $row->status;
      })
      ->escapeColumns('status')
      ->make(true);
    }



}

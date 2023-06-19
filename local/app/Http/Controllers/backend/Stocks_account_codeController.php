<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;

class Stocks_account_codeController extends Controller
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

    public function Datatable(Request $req){

      // if(\Auth::user()->permission==1){
      //         if(isset($req->id)){
      //           $sTable = \App\Models\Backend\Stocks_account_code::where('id',$req->id);
      //         }else{
      //           $sTable = \App\Models\Backend\Stocks_account_code::where('status_confirm_to_approve','1')->search()->orderBy('id', 'asc');
      //         }
      // }else{
      //         if(isset($req->id)){
      //           $sTable = \App\Models\Backend\Stocks_account_code::where('id',$req->id)->where('action_user',\Auth::user()->id);
      //         }else{
      //           if($req->sA==''){
      //               $sTable = \App\Models\Backend\Stocks_account_code::search()->orderBy('id', 'asc');
      //           }else{
      //               $sTable = \App\Models\Backend\Stocks_account_code::where('action_user',\Auth::user()->id)->orderBy('id', 'asc');
      //           }

      //         }
      // }

      if(\Auth::user()->permission==1){
            $sTable = \App\Models\Backend\Stocks_account_code::search()->orderBy('id', 'desc');
      }else{
           $sTable = \App\Models\Backend\Stocks_account_code::where('branch_id_fk',\Auth::user()->branch_id_fk)->search()->orderBy('id', 'desc');
      }

      // $sTable = \App\Models\Backend\Stocks_account_code::search()->orderBy('id', 'asc');
      $sQuery = \DataTables::of($sTable);
      return $sQuery
      ->addColumn('action_user_id', function($row) {
        if(@$row->action_user!=''){
           return @$row->action_user;
        }else{
          return '0';
        }
      })
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
       ->addColumn('business_location', function($row) {
        if(@$row->business_location_id_fk!=''){
             $P = DB::select(" select * from dataset_business_location where id=".@$row->business_location_id_fk." ");
             return @$P[0]->txt_desc;
        }else{
             return '-';
        }
      })
      ->addColumn('branch', function($row) {
          $sBranchs = DB::select(" select * from branchs where id=".$row->branch_id_fk." ");
          return @$sBranchs[0]->b_name;
      })
      ->addColumn('amt_diff', function($row) {
          $amt_diff = DB::select(" SELECT db_stocks_account.amt_diff as amt_diff FROM db_stocks_account where stocks_account_code_id_fk =".$row->id." AND amt_diff<>0 ");
          return @$amt_diff[0]->amt_diff;
      })
      ->make(true);
    }


}

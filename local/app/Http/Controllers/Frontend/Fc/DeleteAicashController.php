<?php

namespace App\Http\Controllers\Frontend\Fc;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Backend\Giveaway;


class DeleteAicashController extends Controller
{

  public static function delete_aicash($delete_aicash_id,$customer_or_admin,$type_user_cancel){
    if($delete_aicash_id){
      DB::BeginTransaction();
      try{

        // $delete = DB::table('db_add_ai_cash') //update บิล
        // ->where('id', $delete_aicash_id)
        // ->update([
        //     'delete_by_user_id_fk' => $customer_or_admin,
        //     'deleted_status' => 1,
        //     'type_user' => $type_user_cancel, //customer || Admin
        //     'deleted_at' => date('Y-m-d H:i:s'),
        // ]);
        DB::table('db_add_ai_cash')->where('id', '=', $delete_aicash_id)->delete();
        DB::commit();
        $resule = ['status' => 'success', 'message' => 'Delete Ai-Cash Success'];
        return  $resule;
      }catch(Exception $e){
        DB::rollback();
        $resule = ['status' => 'fail', 'message' => $e];
        return  $resule;
      }

    }else{
      DB::rollback();
        $resule = ['status' => 'fail', 'message' => 'Delete Ai-Cash Fail : Data is null'];
        return  $resule;
    }

  }


}

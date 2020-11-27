<?php
namespace App\Models\Frontend;

use Illuminate\Database\Eloquent\Model;
use Laraveldaily\Quickadmin\Observers\UserActionsObserver;
use DB;
class Runpv extends Model
{
	 public static function run_pv($type,$pv,$username){
        if(!empty($type) || !empty($pv) || !empty($username) ){
            $resule = ['status'=>'success','message'=>'Yess']; 

        }else{
            $resule = ['status'=>'fail','message'=>'Data in Null']; 
        }

        return $resule;

     }

}

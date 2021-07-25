<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alert extends Model
{
  //protected $table = 'ck_alert';
  public static function Msg($sMode = 'success', $sCode = NULL )
	{
    if($sMode =='success' && $sCode==NULL){
      $method = \Request::method();
      if($method=='PUT'){
        return array('status'=>'success', 'msg'=>'บันทึกข้อมูลเรียบร้อย');
      }
      if($method=='POST'){
        return array('status'=>'success', 'msg'=>'เพิ่มข้อมูลเรียบร้อย');
      }
      if($method=='DELETE'){
        return array('status'=>'success', 'msg'=>'ลบข้อมูลเรียบร้อย');
      }
    }

		$sAction	= ctype_alnum($sCode)?$sCode:NULL;
		$sMode 		= ucfirst($sMode);
		if( !empty($sCode) && !ctype_alnum($sCode) ) return array('status'=>$sMode, 'msg'=>$sCode);
		list($sController, $sMethod) = explode('@', str_replace('App\Http\Controllers\\', '', \Route::getCurrentRoute()->getActionName()));
    return array('status'=>$sMode, 'msg'=>$sCode);
	}

  public static function e($e)
	{
		return array(
			'status'=>'error',
			'msg' =>'
			<b>Message : </b> '.$e->getMessage().'<br/>
			<b>File : </b> '.$e->getFile().'<br/>
			<b>Line : </b> '.$e->getLine().'<br/>'
		);
	}
}

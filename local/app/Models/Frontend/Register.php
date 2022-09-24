<?php
namespace App\Models\Frontend;

use Illuminate\Database\Eloquent\Model;
use Laraveldaily\Quickadmin\Observers\UserActionsObserver;
use DB;
use App\Models\Frontend\LineModel;
class Register extends Model
{
	public static function register($req,$introduce_id=''){
    //dd($req->all());
        $type_introduce = LineModel::check_type_introduce($introduce_id,$req->upline_id);

        if( $type_introduce['status'] == 'success'){

            $introduce_type = $type_introduce['data']->line_type;
        }else{
            $introduce_type = '';
        }

        $customer_code_id =DB::table('customer_code')
        ->select('id')
        ->orderby('id','DESC')
        //->limit('1')
        ->first();

        if($customer_code_id){
          $customer_code_id =  $customer_code_id->id+1;
          $c_code = 'A'.$customer_code_id;
          $username = $c_code;
          $AUTO_INCREMENT ='';
        }else{
          $customer_code_id =DB::table('customers')
          ->select('id')
          ->orderby('id','DESC')
          //->limit('1')
          ->first();


          $customer_code_id =  $customer_code_id->id+1;
          $c_code = 'A'.$customer_code_id;
          $username = $c_code;

          $AUTO_INCREMENT = "ALTER TABLE customer_code AUTO_INCREMENT = $customer_code_id;";
          DB::unprepared($AUTO_INCREMENT);

        }

        $check_user_name = DB::table('customers')
        ->where('user_name','=',$username)
        ->count();

        if($check_user_name>0){
          $data = ['status'=>'fail','massage'=>'มีรหัสนี้ในระบบแล้วกรุณาเช็คข้อมูลก่อนการสมัครอีกครั้ง '];
          return $data;
          // $customer_code_id =DB::table('customers')
          // ->select('id')
          // ->orderby('id','DESC')
          // //->limit('1')
          // ->first();
          // $AUTO_INCREMENT = "ALTER TABLE customer_code AUTO_INCREMENT = $customer_code_id->id;";
          // $customer_code_id =  $customer_code_id->id+1;
          // $c_code = 'A'.$customer_code_id;
          // $username = $c_code;
        }


        $alphabet = '123456789';
        $pass = array(); //remember to declare $pass as an array

    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i < 8; $i++) {
    	$n = rand(0, $alphaLength);
    	$pass[] = $alphabet[$n];
    }

    $pass = implode($pass); //turn the array into a string
    $pass_db =md5($pass);


    $prefix_name= (trim($req->input('name_prefix')) == '') ? null : $req->input('name_prefix');
    $first_name= (trim($req->input('name_first')) == '') ? null : $req->input('name_first');
    $last_name= (trim($req->input('name_last')) == '') ? null : $req->input('name_last');
    $business_name= (trim($req->input('name_business')) == '') ? null : $req->input('name_business');
    $family_status= (trim($req->input('family_status')) == '') ? null : $req->input('family_status');

    $business_location = (trim($req->input('business_location')) == '') ? null : $req->input('business_location');

        //birth_day
    $birth_day= (trim($req->input('birth_day')) == '') ? null : $req->input('birth_day');

    $id_card= (trim($req->input('id_card')) == '') ? null : $req->input('id_card');
    $nation_id= (trim($req->input('nation_id')) == '') ? null : $req->input('nation_id');

    $email= (trim($req->input('email')) == '') ? null : $req->input('email');
    $line_type = (trim($req->input('line_type_back')) == '') ? null : $req->input('line_type_back');
    $upline_id = (trim($req->input('upline_id')) == '') ? null : $req->input('upline_id');


    $house_no= (trim($req->input('house_no')) == '') ? null : $req->input('house_no');
    $house_name= (trim($req->input('house_name')) == '') ? null : $req->input('house_name');
    $moo= (trim($req->input('moo')) == '') ? null : $req->input('moo');
    $soi= (trim($req->input('soi')) == '') ? null : $req->input('soi');
    $road= (trim($req->input('road')) == '') ? null : $req->input('road');

    $province_id= ($req->province == '') ? null : $req->province;
    $amphures_id= ( $req->amphures == '') ? null : $req->amphures;
    $district_id= ($req->district == '') ? null : $req->district;
    $zipcode= (trim($req->input('zipcode')) == '') ? null : $req->input('zipcode');

    $card_house_no= (trim($req->input('card_house_no')) == '') ? null : $req->input('card_house_no');
    $card_house_name= (trim($req->input('card_house_name')) == '') ? null : $req->input('card_house_name');
    $card_moo= (trim($req->input('card_moo')) == '') ? null : $req->input('card_moo');
    $card_soi= (trim($req->input('card_soi')) == '') ? null : $req->input('card_soi');


    $card_province_id= ($req->card_province == '') ? null : $req->card_province;
    $card_amphures_id= ( $req->card_amphures == '') ? null : $req->card_amphures;
    $card_district_id= ($req->card_district == '') ? null : $req->card_district;

    $card_road= (trim($req->input('card_road')) == '') ? null : $req->input('card_road');
    $card_zipcode= (trim($req->input('card_zipcode')) == '') ? null : $req->input('card_zipcode');
        //$country= "Thailand";

    $benefit_name= (trim($req->input('benefit_name')) == '') ? null : $req->input('benefit_name');
    $benefit_relation= (trim($req->input('benefit_relation')) == '') ? null : $req->input('benefit_relation');
    $benefit_id= (trim($req->input('benefit_id')) == '') ? null : $req->input('benefit_id');
    $bank_no= (trim($req->input('bank_no')) == '') ? null : $req->input('bank_no');
    $bank_account= (trim($req->input('bank_account')) == '') ? null : $req->input('bank_account');
    $bank_type= (trim($req->input('bank_type')) == '') ? null : $req->input('bank_type');
    $bank_branch= (trim($req->input('bank_branch')) == '') ? null : $req->input('bank_branch');

    if($business_location == 1){
      $bank_name= (trim($req->input('bank_name')) == '') ? null : $req->input('bank_name');
    }else{
      $bank_name= (trim($req->input('bank_name_cam')) == '') ? null : $req->input('bank_name_cam');

    }

    $tel_home= (trim($req->input('tel_home')) == '') ? null : $req->input('tel_home');
    $tel_mobile= (trim($req->input('tel_mobile')) == '') ? null : $req->input('tel_mobile');


    $count_user = DB::table('customers')
    ->select('*')
    ->where('user_name','=',$username)
    ->count();

    $count_tel = DB::table('customers_detail')
    ->select('*')
    ->where('tel_mobile','=',$tel_mobile)
    ->count();

    $check_id_card = DB::table('customers')
    ->select('*')
    ->where('id_card','=',$id_card)
    ->where('nation_id','=',$nation_id)
    ->count();

    $check_email = DB::table('customers')
    ->select('*')
    ->where('email','=',$email)
    ->count();


  //   if($check_email > 0 ){
  //      $data = ['status'=>'fail','massage'=>'Email already exists in the system.'];
  //      DB::rollback();
  //      return $data;
  //  }

  //  if($count_tel > 0 ){
  //      $data = ['status'=>'fail','massage'=>'MobileNumber already exists in the system.'];
  //      DB::rollback();
  //      return $data;
  //  }

   if($check_id_card > 0 ){
       $data = ['status'=>'fail','massage'=>'ID Card already exists in the system.'];
       DB::rollback();
       return $data;
   }

   if($count_user > 0 ){
       $data = ['status'=>'fail','massage'=>'Username or MobileNumber already exists in the system.'];
   }else{
       try {
          DB::BeginTransaction();
  //ฟรีแอคทีม 2 เดือนปฏิทินหลังจากกการสมัคร
          $strtime = strtotime(date('Y-m'));
          $caltime=strtotime("+2 Month",$strtime);
          $two_month = date("Y-m-1", $caltime);

          $data_customer = [
             'business_location_id'=>$business_location,
             'user_name'=>$username,
             'password'=>$pass_db,
             'prefix_name'=>$prefix_name,
             'first_name'=>$first_name,
             'last_name'=>$last_name,
             'business_name'=>$business_name,
             'family_status'=>$family_status,
             'id_card'=>$id_card,
             'nation_id'=>$nation_id,
             'email'=>$email,
             'line_type'=>$line_type,
             'introduce_type'=>$introduce_type,
             'upline_id'=>$upline_id,
             'birth_day'=>$birth_day,
             'introduce_id'=>$introduce_id,
             'pv_mt_active'=>$two_month];
             $id = DB::table('customers')->insertGetId($data_customer);

             $data_customer_detail = ['house_no'=>$house_no,
             'house_name'=>$house_name,
             'user_name'=>$username,
             'moo'=>$moo,
             'soi'=>$soi,
             'road'=>$road,
             'province_id_fk'=>$province_id,
             'amphures_id_fk'=>$amphures_id,
             'district_id_fk'=>$district_id,
             'zipcode'=>$zipcode,
             'benefit_name'=>$benefit_name,
             'benefit_relation'=>$benefit_relation,
             'benefit_id_card'=>$benefit_id,
             'bank_no'=>$bank_no,
             'bank_account'=>$bank_account,
             'bank_type'=>$bank_type,
             'bank_branch'=>$bank_branch,
             'bank_name'=>$bank_name,
             'tel_home'=>$tel_home,
             'tel_mobile'=>$tel_mobile,
             'customer_id'=>$id];
      //  dd($data_customer_detail);
             DB::table('customers_detail')->insert($data_customer_detail);
             $customer_address_card = [
              'user_name'=>$username,
                'card_house_no'=>$card_house_no,
                'card_house_name'=>$card_house_name,
                'card_moo'=>$card_moo,
                'card_soi'=>$card_soi,
                'card_province_id_fk'=>$card_province_id,
                'card_amphures_id_fk'=>$card_amphures_id,
                'card_district_id_fk'=>$card_district_id,
                'card_road'=>$card_road,
                'card_zipcode'=>$card_zipcode,
                'customer_id'=>$id];
                DB::table('customers_address_card')->insert($customer_address_card);




                $customer_code = [
                   'customers_id_fk'=>$id,
                   'c_code'=>$c_code];
                   DB::table('customer_code')->insert($customer_code);

                   $file_1 = $req->file_1;
                   if(isset($file_1)){
                      $url='local/public/files_register/1/'.date('Ym');
                      $f_name =  date('YmdHis').'_'.$id.'_1'.'.'.$file_1->getClientOriginalExtension();
                      $f_name =strtolower($f_name);
                      if($file_1->move($url,$f_name)){
                         DB::table('register_files')
                         ->insert(['customer_id'=>$id,'type'=>'1','url'=>$url,'file'=>$f_name,'regis_doc_status'=>'0','business_location_id_fk'=>$business_location]);

                     }


                 }
                 $file_2 = $req->file_2;
                 if(isset($file_2)){

                  $url='local/public/files_register/2/'.date('Ym');
                  $f_name =  date('YmdHis').'_'.$id.'_2'.'.'.$file_2->getClientOriginalExtension();
                  $f_name =strtolower($f_name);
                  if($file_2->move($url,$f_name)){
                     DB::table('register_files')
                     ->insert(['customer_id'=>$id,'type'=>'2','url'=>$url,'file'=>$f_name,'regis_doc_status'=>'0','business_location_id_fk'=>$business_location]);

                 }
             }

             $file_3 = $req->file_3;
             if(isset($file_3)){

              $url='local/public/files_register/3/'.date('Ym');
              $f_name =  date('YmdHis').'_'.$id.'_3'.'.'.$file_3->getClientOriginalExtension();
              $f_name =strtolower($f_name);
              if($file_3->move($url,$f_name)){
                 DB::table('register_files')
                 ->insert(['customer_id'=>$id,'type'=>'3','url'=>$url,'file'=>$f_name,'regis_doc_status'=>'0','business_location_id_fk'=>$business_location]);

             }
         }

         $file_4 = $req->file_4;
             if(isset($file_4)){
              $url='local/public/files_register/4/'.date('Ym');
              $f_name =  date('YmdHis').'_'.$id.'_4'.'.'.$file_4->getClientOriginalExtension();
              $f_name =strtolower($f_name);
              if($file_4->move($url,$f_name)){
                 DB::table('register_files')
                 ->insert(['customer_id'=>$id,'type'=>'4','url'=>$url,'file'=>$f_name,'regis_doc_status'=>'0','business_location_id_fk'=>$business_location]);
             }
         }

         $data = ['status'=>'success','massage'=>'Add User Success','data'=>$data_customer,'pass'=>$pass,'search_id'=>$id];

     } catch (Exception $e) {
       $data = ['status'=>'fail','massage'=>'Add User Error'];

   }

}

if($data['status'] == 'success'){

    DB::commit();
    //DB::rollback();
    return $data;

}else{
    DB::rollback();
    return $data;

}
}

}

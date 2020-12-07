<?php
namespace App\Models\Frontend;

use Illuminate\Database\Eloquent\Model;
use Laraveldaily\Quickadmin\Observers\UserActionsObserver;
use DB;
class Register extends Model
{
	public static function register($req,$introduce_id=''){
		$id =DB::table('Customers')
		->select('id')
		->orderby('id','DESC')
		->limit('1')
		->get();
		$new_id = $id[0]->id+1;
		$maxId = substr("0000000".$new_id, -7);
		$username ="A".$maxId;
		$pass=md5('142536');

		$prefix_name= (trim($req->input('name_prefix')) == '') ? null : $req->input('name_prefix');
		$first_name= (trim($req->input('name_first')) == '') ? null : $req->input('name_first');
		$last_name= (trim($req->input('name_last')) == '') ? null : $req->input('name_last');
		$business_name= (trim($req->input('name_business')) == '') ? null : $req->input('name_business');
		$family_status= (trim($req->input('family_status')) == '') ? null : $req->input('family_status');
        //birth_day
		$birth_day= (trim($req->input('birth_day')) == '') ? null : $req->input('birth_day');

		$id_card= (trim($req->input('id_card')) == '') ? null : $req->input('id_card');
		$email= (trim($req->input('email')) == '') ? null : $req->input('email');
		$line_type = (trim($req->input('line_type_back')) == '') ? null : $req->input('line_type_back');
		$upline_id = (trim($req->input('upline_id')) == '') ? null : $req->input('upline_id');


		$house_no= (trim($req->input('house_no')) == '') ? null : $req->input('house_no');
		$house_name= (trim($req->input('house_name')) == '') ? null : $req->input('house_name');
		$moo= (trim($req->input('moo')) == '') ? null : $req->input('moo');
		$soi= (trim($req->input('soi')) == '') ? null : $req->input('soi');
		$road= (trim($req->input('road')) == '') ? null : $req->input('road');
		$district_sub= (trim($req->input('district_sub')) == '') ? null : $req->input('district_sub');
		$district= (trim($req->input('district')) == '') ? null : $req->input('district');
		$province= (trim($req->input('province')) == '') ? null : $req->input('province');
		$zipcode= (trim($req->input('zipcode')) == '') ? null : $req->input('zipcode');

		$card_house_no= (trim($req->input('card_house_no')) == '') ? null : $req->input('card_house_no');
		$card_house_name= (trim($req->input('card_house_name')) == '') ? null : $req->input('card_house_name');
		$card_moo= (trim($req->input('card_moo')) == '') ? null : $req->input('card_moo');
		$card_soi= (trim($req->input('card_soi')) == '') ? null : $req->input('card_soi');
		$card_district= (trim($req->input('card_district')) == '') ? null : $req->input('card_district');
		$card_district_sub= (trim($req->input('card_district_sub')) == '') ? null : $req->input('card_district_sub');
		$card_province= (trim($req->input('card_province')) == '') ? null : $req->input('card_province');
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
		$bank_name= (trim($req->input('bank_name')) == '') ? null : $req->input('bank_name');
		$tel_home= (trim($req->input('tel_home')) == '') ? null : $req->input('tel_home');
		$tel_mobile= (trim($req->input('tel_mobile')) == '') ? null : $req->input('tel_mobile');
		$head_line_id= (trim($req->input('head_line_id')) == '') ? null : $req->input('head_line_id');

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
		->count();

		$check_email = DB::table('customers')
		->select('*')
		->where('email','=',$email)
		->count();

 
		if($check_email > 0 ){
			$data = ['status'=>'fail','massage'=>'Email already exists in the system.'];
			DB::rollback();
			return $data;
		}

		if($count_tel > 0 ){
			$data = ['status'=>'fail','massage'=>'MobileNumber already exists in the system.'];
			DB::rollback();
			return $data;
		}

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

				$data_customer = [
					'user_name'=>$username,
					'password'=>$pass,
					'prefix_name'=>$prefix_name,
					'first_name'=>$first_name,
					'last_name'=>$last_name,
					'business_name'=>$business_name,
					'family_status'=>$family_status,
					'id_card'=>$id_card,
					'email'=>$email,
					'line_type'=>$line_type, 
					'upline_id'=>$upline_id,
					'birth_day'=>$birth_day,
					'introduce_id'=>$introduce_id,
					'head_line_id'=>$head_line_id];
					$id = DB::table('customers')->insertGetId($data_customer);

					$data_customer_detail = ['house_no'=>$house_no,
					'house_name'=>$house_name,
					'moo'=>$moo,
					'soi'=>$soi,
					'road'=>$road,
					'district_sub'=>$district_sub,
					'district'=>$district,
					'province'=>$province,
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
					DB::table('customers_detail')->insert($data_customer_detail);


					$customer_address_card = [
						'card_house_no'=>$card_house_no,
						'card_house_name'=>$card_house_name,
						'card_moo'=>$card_moo,
						'card_soi'=>$card_soi,
						'card_district'=>$card_district,
						'card_district_sub'=>$card_district_sub,
						'card_province'=>$card_province,
						'card_road'=>$card_road,
						'card_zipcode'=>$card_zipcode,
						'customer_id'=>$id];
						DB::table('customers_address_card')->insert($customer_address_card);

						$customer_code_id =DB::table('customer_code')
						->select('id')
						->orderby('id','DESC')
						//->limit('1')
						->first();
					 
						$customer_code_id =  $customer_code_id->id+1;
						$c_code = 'A'.$customer_code_id;

						$customer_code = [
							'customers_id_fk'=>$id,
							'c_code'=>$c_code];
							DB::table('customer_code')->insert($customer_code);

							$file_1 = $req->file_1;
							if(isset($file_1)){
								$url='local/public/files_register/1/'.date('Ym');
								$f_name =  date('YmdHis').'_'.$id.'_1'.'.'.$file_1->getClientOriginalExtension();
								if($file_1->move($url,$f_name)){
									DB::table('register_files')
									->insert(['customer_id'=>$id,'type'=>'1','url'=>$url,'file'=>$f_name,'status'=>'W']);

								}


							}
							$file_2 = $req->file_2;
							if(isset($file_2)){

								$url='local/public/files_register/2/'.date('Ym');
								$f_name =  date('YmdHis').'_'.$id.'_2'.'.'.$file_1->getClientOriginalExtension();
								if($file_2->move($url,$f_name)){
									DB::table('register_files')
									->insert(['customer_id'=>$id,'type'=>'2','url'=>$url,'file'=>$f_name,'status'=>'W']);

								}
							}

							$file_3 = $req->file_3;
							if(isset($file_3)){

								$url='local/public/files_register/3/'.date('Ym');
								$f_name =  date('YmdHis').'_'.$id.'_3'.'.'.$file_1->getClientOriginalExtension();
								if($file_3->move($url,$f_name)){
									DB::table('register_files')
									->insert(['customer_id'=>$id,'type'=>'3','url'=>$url,'file'=>$f_name,'status'=>'W']);

								}
							}

							$data = ['status'=>'success','massage'=>'Add User Success'];


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

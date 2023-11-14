<?php

namespace App\Http\Controllers\backend;

use DB;
use File;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use App\Models\Frontend\Customer;
use Auth;
use Session;

class Member_pvController extends Controller
{

    public function index(Request $request)
    {

       ini_set('max_execution_time', '0');
       ini_set('memory_limit', '-1');

       $sApprover = DB::select(" select * from ck_users_admin where branch_id_fk=".(\Auth::user()->branch_id_fk)."  ");
       $sBusiness_location = \App\Models\Backend\Business_location::get();
       $sBranchs = \App\Models\Backend\Branchs::get();
       $Supplier = DB::select(" select * from dataset_supplier ");
       $po_number = DB::select(" SELECT po_number FROM `db_po_supplier` where branch_id_fk=".(\Auth::user()->branch_id_fk)." ");
       $filetype = DB::select(" SELECT * FROM `dataset_regis_filetype` ");
       $regis_doc_status = DB::select(" SELECT * FROM `dataset_regis_doc_status` ");

       // dd($regis_doc_status);

      // $sPay_product_status = \App\Models\Backend\Pay_product_status::get();
      $sInvoice_code = DB::select(" SELECT
        db_add_ai_cash.invoice_code
        FROM
        db_add_ai_cash where invoice_code is not null
        ");




      return View('backend.member_pv.index')->with(
        array(
           'sBusiness_location'=>$sBusiness_location,'sBranchs'=>$sBranchs,'Supplier'=>$Supplier,
           'sApprover'=>$sApprover,
           'po_number'=>$po_number,
           'filetype'=>$filetype,
           'regis_doc_status'=>$regis_doc_status,

        ) );
      // return view('backend.member_pv.index');

    }

    public function create()
    {
    }
    public function store(Request $request)
    {
    }
    public function edit($id)
    {

      $customer = DB::table('customers')
        ->select(
          'customers.*',
          'detail.house_no',
          'detail.house_name',
          'detail.moo',
          'detail.soi',
          'detail.road',
          'detail.province_id_fk',
          'detail.amphures_id_fk',
          'detail.district_id_fk',
          'detail.bank_account',
          'detail.bank_no',
          'detail.bank_name',
          'detail.bank_branch',
          'detail.bank_type',
          'detail.tel_mobile',
          'detail.tel_home',
          DB::raw(
            "(SELECT CONCAT(introduce.first_name, ' ', introduce.last_name) FROM customers as introduce
            WHERE introduce.user_name = customers.introduce_id) as introduce_name")
        )
        ->leftJoin('customers_detail as detail', 'detail.customer_id', '=', 'customers.id')
        ->where('customers.id', $id)
        ->first();

      $addressCard = DB::table('customers_address_card')
          ->where('customer_id', $id)
          ->first();

      $qualifications = DB::table('dataset_qualification')->pluck('business_qualifications', 'id');
      $packages = DB::table('dataset_package')->pluck('dt_package', 'id');

      $provinces = DB::table('dataset_provinces')->pluck('name_th', 'id');
      $file_img = DB::table('register_files')->where('customer_id',$customer->id)->where('type',4)->first();

      $dataset_business_location = DB::table('dataset_business_location')
      ->where('status','=',1)
      ->get();


      $dataset_business_name = DB::table('dataset_business_location')
      ->where('id',$customer->business_location_id)
      ->first();

      if($dataset_business_name){
        $b_name = $dataset_business_name->txt_desc;
      }else{
        $b_name = 'THAI';
      }


      return view('backend.member_pv.edit')->with([
        'customer' => $customer,
        'dataset_business_location' => $dataset_business_location,
        'business_location_name' => $b_name,
        'qualifications' => $qualifications,
        'packages' => $packages,
        'addressCard' => $addressCard,
        'file_img' => $file_img,
        'provinces' => $provinces
      ]);
    }



   public function update(Request $request, $customer_id)
    {
      // dd($request->all());
      $detail = DB::table('customers_detail')->select('id')->where('customer_id',$customer_id)->first();
      if(!$detail){
        DB::table('customers_detail')->insert([
          'customer_id' => $customer_id,
          'created_at' => date('Y-m-d H:i:s'),
          'updated_at' => date('Y-m-d H:i:s'),
        ]);
      }

      if ($request->type == 'customer') {

        $this->updatePersonalInformation($request, $customer_id);
        return back()->with(['alert' =>['status'=>'success', 'msg'=>'บันทึกข้อมูลทั่วไปสำเร็จ']]);
      } elseif($request->type == 'customer_address_card') {
        $this->updateAddressCardInformation($request, $customer_id);
        return back()->with(['alert' =>['status'=>'success', 'msg'=>'บันทึกข้อมูลที่อยู่ตามบัตรประชาชนสำเร็จ']]);
      } elseif($request->type == 'address_send') {
        $this->updateAddresSendInformation($request, $customer_id);
        return back()->with(['alert' =>['status'=>'success', 'msg'=>'บันทึกข้อมูลที่อยู่จัดส่งสำเร็จ']]);
      } elseif($request->type == 'bank') {
        // dd('l');
        $this->updateBankInformation($request, $customer_id);
        return back()->with(['alert' =>['status'=>'success', 'msg'=>'บันทึกข้อมูลธนาคารสำเร็จ']]);
      }else{
        return back()->with(['alert' =>['status'=>'fail', 'msg'=>'การบันทึกข้อมูลไม่ถูกต้อง']]);
      }

    }

    public function updatePersonalInformation($request, $customer_id)
    {
      $customer = Customer::find($customer_id);
      $customer->update($request->except(['_method', '_token','type']));
      //$request->session()->flash('status_personal', 'แก้ไขข้อมูลสมาชิกเรียบร้อย');
    }

    public function updateAddressCardInformation($request, $customer_id)
    {
      $rs = DB::table('customers_address_card')->updateOrInsert([
        'customer_id' => $customer_id
      ],$request->except(['_method', '_token','type']));


     // $request->session()->flash('status_bank', 'แก้ไขข้อมูลธนาคารเรียบร้อย');
    }

    public function updateAddresSendInformation($request, $customer_id)
    {
      DB::table('customers_detail')->updateOrInsert([
        'customer_id' => $customer_id
      ], $request->except(['_method', '_token','type']));
    }
    public function updateBankInformation($request, $customer_id)
    {
      // DB::table('customers_detail')->updateOrInsert([
      //   'customer_id' => $customer_id
      // ],$request->except(['_method', '_token','type']));

      DB::table('customers_detail')->where('customer_id',$customer_id)->update(
        [
          'bank_account' => $request->bank_account,
          'bank_no' => $request->bank_no,
          'bank_name' => $request->bank_name,
          'bank_branch' => $request->bank_branch,
          'bank_type' => $request->bank_type,
        ]
      );

      if(isset($request->file_bank)){
        if($request->file_bank!=null){

      //     $file_4 = $request->file_bank;
      //     if(isset($file_4)){
      //      $url='local/public/files_register/4/'.date('Ym');
      //      $f_name =  date('YmdHis').'_'.$id.'_4'.'.'.$file_4->getClientOriginalExtension();
      //      $f_name =strtolower($f_name);
      //      if($file_4->move($url,$f_name)){
      //         DB::table('register_files')
      //         ->insert(['customer_id'=>$id,'type'=>'4','url'=>$url,'file'=>$f_name,'regis_doc_status'=>'0','business_location_id_fk'=>$business_location]);
      //     }
      // }

      $cus = DB::table('customers')
                      ->select('business_location_id','id')
                      ->where('id',$customer_id)
                      ->first();

          $file_4 = $request->file_bank;
                  // $f_name = $file_3->getClientOriginalName().'_'.date('YmdHis').'.'.$file_3->getClientOriginalExtension();
            $url='local/public/files_register/4/'.date('Ym');
            // $f_name =  date('YmdHis').'_'.Auth::user()->id.'_4'.'.'.$file_4->getClientOriginalExtension();
            $f_name =  date('YmdHis').'_'.$cus->id.'_4'.'.'.$file_4->getClientOriginalExtension();
            $f_name =strtolower($f_name);
            if($file_4->move($url,$f_name)){
             $data = DB::table('register_files')
             ->select('comment')
              ->where('customer_id',$customer_id)->where('type',4)->first();
// dd($customer_id);
                    if($data){

                      DB::table('register_files')
                      ->where('customer_id',$customer_id)->where('type',4)
                      ->update(
                    [
                    'url' => $url,
                    'file' => $f_name,
                    'approve_date' => null,
                    'comment' => $data->comment.' (ถูกแก้ไขจากหน้าข้อมูลส่วนตัวโดย '.\Auth::user()->name.' วันที่ : '.date('Y-m-d H:i:s').')',
                    'approver' => null,
                    'regis_doc_status' => 0,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                    ]
                      );

                      DB::table('customers')
                      ->where('id',$customer_id)
                      ->update([
                        'regis_doc4_status' => 0
                      ]);
                    }else{
                      if($cus->business_location_id==''){
                        $cus->business_location_id = 1;
                      }
                      DB::table('register_files')
                      ->insert(
                    [

                    'business_location_id_fk' => $cus->business_location_id,
                    'branch_id_fk' => 0,
                    'customer_id' => $cus->id,
                    'type' => 4,
                    'url' => $url,
                    'file' => $f_name,
                    'comment' => ' (ถูกแก้ไขจากหน้าข้อมูลส่วนตัวโดย '.\Auth::user()->name.' วันที่ : '.date('Y-m-d H:i:s').')',
                    'approve_date' => null,
                    'approver' => null,
                    'regis_doc_status' => 0,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                    'item_checked' => 0
                    ]
                      );

                      DB::table('customers')
                      ->where('id',$customer_id)
                      ->update([
                        'regis_doc4_status' => 0
                      ]);
                    }


                  // $update_use->regis_doc4_status = 0;
            }
                    }

            }
    }


   public function form($id=NULL)
    {
      \DB::beginTransaction();
      try {

           // \DB::commit();
           return redirect()->to(url("backend/member_regis"));
      } catch (\Exception $e) {
        echo $e->getMessage();
        \DB::rollback();
        return redirect()->action('backend\Member_pvController@index')->with(['alert'=>\App\Models\Alert::e($e)]);
      }
    }

    public function destroy($id)
    {
    }


    public function Datatable(Request $req){

      if(!empty($req->customer_id)){
        $w01 = $req->customer_id;
        $Operator01 = "=";
      }else{

        if(!empty($req->customer_id_username)){
          $w01 = $req->customer_id_username;
          $Operator01 = "=";
        }else{
          $w01 = 0 ;
          $Operator01 = "!=";
        }


      }




      if(!empty($req->business_name)){
        $w02 = $req->business_name;
        $Operator02 = "=";
      }else{
        $w02 = "0" ;
        $Operator02 = "!=";
      }

      if(!empty($req->introduce_id)){
        $w03 = $req->introduce_id;
        $Operator03 = "=";
      }else{
        $w03 = "0" ;
        $Operator03 = "!=";
      }

      if(!empty($req->upline_id)){
        $w04 = $req->upline_id;
        $Operator04 = "=";
      }else{
        $w04 = "0" ;
        $Operator04 = "!=";
      }

      if($req->id_card!=''){
        $w05 = '%' .$req->id_card;
        $Operator05 = "like";
      }else{
        $w05 = "0" ;
        $Operator05 = "!=";
      }
      // 1282213 =
      // dd($req->all());


      $sTable = \App\Models\Backend\Customers::where('id','!=',0)
      ->where('customers.id',$Operator01,$w01)
      // ->where('customers.business_name',$Operator02,trim($w02))
      ->where('customers.introduce_id',$Operator03,$w03)
      ->where('customers.upline_id',$Operator04,$w04)
      ->where('customers.id_card',$Operator05,$w05)
      ;

      $sQuery = \DataTables::of($sTable);
      return $sQuery
       ->addColumn('customer_name', function($row) {
        if($row->user_name){
          return @$row->user_name." : ".trim(@$row->prefix_name).trim(@$row->first_name)." ".trim(@$row->last_name);
        }else{
          return '';
        }
      })
      ->addColumn('qualification', function($row) {
        if($row->qualification_id){
          $d = DB::select(" SELECT * FROM `dataset_qualification` where id= ".$row->qualification_id."  ");
          return @$d[0]->business_qualifications;
        }else{
          return '-';
        }
      })
      ->addColumn('package', function($row) {
        if($row->qualification_id){
          $d = DB::select(" SELECT * FROM `dataset_package` where id= ".$row->package_id."  ");
          return @$d[0]->dt_package;
        }else{
          return '-';
        }
      })
      ->addColumn('regis_status', function($row) {
        // สถานะกรณีนี้ ต้อง ดึงมาจากตาราง customers
        //   `regis_doc1_status` int(1) DEFAULT '0' COMMENT 'ภาพถ่ายบัตรประชาชน 0=ยังไม่ส่ง 1=ผ่าน 2=ไม่ผ่าน',
        //   `regis_doc2_status` int(1) DEFAULT '0' COMMENT 'ภายถ่ายหน้าตรง 0=ยังไม่ส่ง 1=ผ่าน 2=ไม่ผ่าน',
        //   `regis_doc3_status` int(1) DEFAULT '0' COMMENT 'ภาพถ่ายหน้าตรงถือบัตรประชาชน 0=ยังไม่ส่ง 1=ผ่าน 2=ไม่ผ่าน',
        //   `regis_doc4_status` int(1) DEFAULT '0' COMMENT 'ภาพถ่ายหน้าบัญชีธนาคาร 0=ยังไม่ส่ง 1=ผ่าน 2=ไม่ผ่าน',

        $ic = '';

        if($row->regis_doc1_status==1){
          $icon = DB::select(" select * from dataset_regis_filetype where id=1 ");
          $ic .= '<span  data-toggle="tooltip" data-placement="right" title="ผ่าน">'.@$icon[0]->icon_pass.'</span>';
        }elseif($row->regis_doc1_status==2){
          $icon = DB::select(" select * from dataset_regis_filetype where id=1 ");
          $ic .= '<span  data-toggle="tooltip" data-placement="right" title="ไม่ผ่าน">'.@$icon[0]->icon_nopass.'</span>';
        }else{
          $icon = DB::select(" select * from dataset_regis_filetype where id=1 ");
          $ic .= '<span  data-toggle="tooltip" data-placement="right" title="ยังไม่ส่ง">'.@$icon[0]->icon_nosend.'</span>';
        }

        if($row->regis_doc2_status==1){
          $icon = DB::select(" select * from dataset_regis_filetype where id=2 ");
          $ic .= '<span  data-toggle="tooltip" data-placement="right" title="ผ่าน">'.@$icon[0]->icon_pass.'</span>';
        }elseif($row->regis_doc2_status==2){
          $icon = DB::select(" select * from dataset_regis_filetype where id=2 ");
          $ic .= '<span  data-toggle="tooltip" data-placement="right" title="ไม่ผ่าน">'.@$icon[0]->icon_nopass.'</span>';
        }else{
          $icon = DB::select(" select * from dataset_regis_filetype where id=2 ");
          $ic .= '<span  data-toggle="tooltip" data-placement="right" title="ยังไม่ส่ง">'.@$icon[0]->icon_nosend.'</span>';
        }

        if($row->regis_doc3_status==1){
          $icon = DB::select(" select * from dataset_regis_filetype where id=3 ");
          $ic .= '<span  data-toggle="tooltip" data-placement="right" title="ผ่าน">'.@$icon[0]->icon_pass.'</span>';
        }elseif($row->regis_doc3_status==2){
          $icon = DB::select(" select * from dataset_regis_filetype where id=3 ");
          $ic .= '<span  data-toggle="tooltip" data-placement="right" title="ไม่ผ่าน">'.@$icon[0]->icon_nopass.'</span>';
        }else{
          $icon = DB::select(" select * from dataset_regis_filetype where id=3 ");
          $ic .= '<span  data-toggle="tooltip" data-placement="right" title="ยังไม่ส่ง">'.@$icon[0]->icon_nosend.'</span>';
        }

        if($row->regis_doc4_status==1){
          $icon = DB::select(" select * from dataset_regis_filetype where id=4 ");
          $ic .= '<span  data-toggle="tooltip" data-placement="right" title="ผ่าน">'.@$icon[0]->icon_pass.'</span>';
        }elseif($row->regis_doc4_status==2){
          $icon = DB::select(" select * from dataset_regis_filetype where id=4 ");
          $ic .= '<span  data-toggle="tooltip" data-placement="right" title="ไม่ผ่าน">'.@$icon[0]->icon_nopass.'</span>';
        }else{
          $icon = DB::select(" select * from dataset_regis_filetype where id=4 ");
          $ic .= '<span  data-toggle="tooltip" data-placement="right" title="ยังไม่ส่ง">'.@$icon[0]->icon_nosend.'</span>';
        }

        return $ic;

      })
      ->escapeColumns('regis_status')
      ->addColumn('regis_date_doc', function($row) {
        if($row->regis_date_doc){
           return $row->regis_date_doc;
        }else{
           return '';
        }
      })
      ->addColumn('routes_user', function ($user) {
        if(@$user->user_name){
          return route('admin.access', Crypt::encryptString(@$user->user_name));
        }else{
          return 0;
        }
      })
      ->addColumn('edit_user', function ($user) {

        $routeEdit = route('backend.member_pv.edit', $user->id);



        $sPermission = Auth::user()->permission ;
          // $menu_id = @$_REQUEST['menu_id'];
          $menu_id = Session::get('session_menu_id');
        if($sPermission==1){
          $sC = '';
          $sU = '';
          $sD = '';
        }else{
          $role_group_id = Auth::user()->role_group_id_fk;
          $menu_permit = DB::table('role_permit')->where('role_group_id_fk',$role_group_id)->where('menu_id_fk',$menu_id)->first();
          $sC = @$menu_permit->c==1?'1':'0';
          $sU = @$menu_permit->u==1?'1':'0';
          $sD = @$menu_permit->d==1?'1':'0';
        }


        if ($sU == 0) {
          return '';
        }else{
          return "
          <a class='btn btn-sm btn-warning' href='{$routeEdit}' target='_blank' class='btn btn-primary'>
            <i class='bx bx-edit font-size-16 align-middle'></i>
          </a>
        ";
        }


      })
      ->addColumn('aistockist_status', function ($row) {
          if($row->aistockist_status==1){
            return '<i class="bx bx-check-circle" style="color:green;font-weight:bold;font-size:16px;" data-toggle="tooltip" data-placement="right" title="เป็นแล้ว" ></i>';
          }else{
            return '<i class="bx bx-x-circle" style="color:darkred;" data-toggle="tooltip" data-placement="right" title="ยังไม่เป็น" ></i>';
          }
      })
      ->escapeColumns('aistockist_status')
      ->addColumn('introduce', function($row) {
        if($row->introduce_id){
          return @$row->introduce_id."/".@$row->introduce_type;
        }else{
          return '';
        }
      })
      ->escapeColumns('introduce')
      ->addColumn('upline', function($row) {
        if($row->upline_id){
          return @$row->upline_id."/".@$row->line_type;
        }else{
          return '';
        }
      })
      ->escapeColumns('upline')
      ->make(true);
    }



    public static function tranfer_file(Request $rs){



      $data = DB::table('customers') //อัพ Pv ของตัวเอง
          ->select('id','user_name')
          ->where('user_name',$rs->user_name)
          ->first();
          $arr = array();

    if(empty($data)){
      //fail
      return redirect('backend/member_pv/'.$data->id.'/edit')->with(['alert' =>['status'=>'fail', 'msg'=>'ไม่พบรหัส']]);

    }

      $gv = \App\Helpers\Frontend::get_gitfvoucher($data->user_name);
      if($gv){
        $gv_value = 0;
      }else{
        $gv_value = $gv;
      }

      if($gv_value>0){
        //dd($data->user_name,'fail');
        return redirect('backend/member_pv/'.$data->id.'/edit')->with(['alert' =>['status'=>'fail', 'msg'=>'มียอด Gitfvoucher คงเหลือ ไม่สามารถย้ายได้']]);
      }


    $customers = DB::table('customers')
    ->where('id',$data->id)
    ->update(['business_location_id' => $rs->business_location]); //ลงข้อมูลบิลชำระเงิน

    $file = DB::table('register_files')
    ->where('customer_id',$data->id)
    ->update(['business_location_id_fk' => $rs->business_location]); //ลงข้อมูลบิลชำระเงิน

    return redirect('backend/member_pv/'.$data->id.'/edit')->with(['alert' =>['status'=>'success', 'msg'=>'ย้ายข้อมูลสำเร็จ']]);





}


}

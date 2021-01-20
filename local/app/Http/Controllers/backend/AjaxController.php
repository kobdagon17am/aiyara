<?php
namespace App\Http\Controllers\Backend;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\Datatables\Datatables;
use Validator;
use DB;
use PDO;
use Session;
use Storage;
use PDF;
use Redirect;
// use App\Model\CourseModel;
// use App\Model\MemberModel;
// use App\Model\MemberAddModel;  
// use App\Http\Controllers\backend\ActiveMemberController;
// use App\Http\Controllers\backend\ActiveCourseController;
use App\Models\Backend\PromotionCode_add;

class AjaxController extends Controller
{

    public function ajaxSetSession(Request $request)
    {
        Session::put('session_menu_id', $request->session_menu_id);
         // echo "session created";
        echo (Session::get('session_menu_id'));

        $roleApprove = DB::select(" SELECT can_approve from role_permit WHERE role_group_id_fk=(SELECT role_group_id_fk FROM ck_users_admin WHERE id=".(\Auth::user()->id).") and menu_id_fk='$request->session_menu_id'  ; ");
        // echo $roleApprove[0]->can_approve;
        Session::put('roleApprove', $roleApprove[0]->can_approve);
        echo (Session::get('roleApprove'));

    }


    public function ajaxClearDataPm_broadcast()
    {
        DB::delete(" TRUNCATE pm_broadcast ");
    }



    public function ajaxFetchData(Request $request)
    {
        // DB::delete(" TRUNCATE course_event_regis ");

        // return($request->search);

        // if(isset($request->search)){

			    // $result = DB::select("SELECT * FROM course_event_regis WHERE ticket_number like'%".$request->search."%'");
			    // $response = [];
			    // while($result){
			    //     $response[] = array("value"=>$result[0]->id,"label"=>$result[0]->ticket_number);
			    // }
			    // return(@$result[0]->ticket_number);
			    // return compact($result);
			// }

    	// if(isset($request->search)){
    	// if($request->has('term')){
            // return Product::where('name','like','%'.$request->input('term').'%')->get();
    	// return DB::select("SELECT * FROM course_event_regis WHERE ticket_number like'%".$request->search."%'");
        // }


    }

    public function ajaxSelectAddr(Request $request)
    {

          $data = DB::select(" 
                SELECT
                db_delivery_packing.id,
                db_delivery.customer_id,customers.prefix_name,
                customers.first_name,
                customers.last_name,
                customers_detail.house_no,
                customers_detail.house_name,
                customers_detail.moo,
                customers_detail.zipcode,
                customers_detail.soi,
                customers_detail.district,
                customers_detail.district_sub,
                customers_detail.road,
                customers_detail.province,
                customers_detail.id as addr_id
                FROM
                db_delivery_packing
                Left Join db_delivery ON db_delivery_packing.delivery_id_fk = db_delivery.id
                Left Join customers ON db_delivery.customer_id = customers.id
                Left Join customers_detail ON db_delivery.customer_id = customers_detail.customer_id
                WHERE db_delivery_packing.packing_code=".$request->v."

           ");

            $tb = "<table class='minimalistBlack'>
                        <thead>
                        <tr><th>ชื่อลูกค้า</th><th>ที่อยู่</th><th>เลือก</th></tr>
                        </thead>
                        <tbody>";

                      $i = 1;

                      foreach ($data as $key => $value) {

                            $addr = $value->house_no?$value->house_no.", ":'';
                            $addr .= $value->house_name;
                            $addr .= $value->moo?", หมู่ ".$value->moo:'';
                            $addr .= $value->soi?", ซอย".$value->soi:'';
                            $addr .= $value->road?", ถนน".$value->road:'';
                            $addr .= $value->district_sub?", ต.".$value->district_sub:'';
                            $addr .= $value->district?", อ.".$value->district:'';
                            $addr .= $value->province?", จ.".$value->province:'';
                            $addr .= $value->zipcode?", ".$value->zipcode:'';

                            if($value->addr_id!=''){
                                if($i==1){
                                    $addr_id = "<input type='radio' name='addr' value='".$value->addr_id."' checked >";
                                }else{
                                    $addr_id = "<input type='radio' name='addr' value='".$value->addr_id."'>";
                                }
                                
                            }else{
                                $addr_id = "";
                            }

                            $tb .= "
                                   <tr>
                                      <td>
                                      ".$value->prefix_name."
                                      ".$value->first_name." 
                                      ".$value->last_name."
                                      </td> 
                                      <td>
                                      ".($addr?$addr:'-ไม่พบข้อมูลที่อยู่-')."
                                      </td>
                                      <td><center> ".$addr_id."
                                      </td>
                                   </tr> 
                            ";

                            $i++;
                      }
            $tb .= "</tbody></table> <input type='hidden' name='id' value='".$request->v."'> ";

            return $tb;

        
    }


    public function ajaxSelectAddrEdit(Request $request)
    {


          $data = DB::select(" 
                SELECT
                db_delivery_packing.id,
                db_delivery.customer_id,customers.prefix_name,
                customers.first_name,
                customers.last_name,
                customers_detail.house_no,
                customers_detail.house_name,
                customers_detail.moo,
                customers_detail.zipcode,
                customers_detail.soi,
                customers_detail.district,
                customers_detail.district_sub,
                customers_detail.road,
                customers_detail.province,
                customers_detail.id as addr_id,
                db_delivery_packing_code.addr_id AS addr_id2
                FROM
                db_delivery_packing
                Left Join db_delivery ON db_delivery_packing.delivery_id_fk = db_delivery.id
                Left Join customers ON db_delivery.customer_id = customers.id
                Left Join customers_detail ON db_delivery.customer_id = customers_detail.customer_id
                Left Join db_delivery_packing_code ON db_delivery_packing.packing_code = db_delivery_packing_code.id
                WHERE db_delivery_packing.packing_code=".$request->v."

           ");

            $tb = "<table class='minimalistBlack'>
                        <thead>
                        <tr><th>ชื่อลูกค้า</th><th>ที่อยู่</th><th>เลือก</th></tr>
                        </thead>
                        <tbody>";

                      $i = 1;

                      foreach ($data as $key => $value) {

                            $addr = $value->house_no?$value->house_no.", ":'';
                            $addr .= $value->house_name;
                            $addr .= $value->moo?", หมู่ ".$value->moo:'';
                            $addr .= $value->soi?", ซอย".$value->soi:'';
                            $addr .= $value->road?", ถนน".$value->road:'';
                            $addr .= $value->district_sub?", ต.".$value->district_sub:'';
                            $addr .= $value->district?", อ.".$value->district:'';
                            $addr .= $value->province?", จ.".$value->province:'';
                            $addr .= $value->zipcode?", ".$value->zipcode:'';

                            if($value->addr_id!=''){
                                if($value->addr_id==$value->addr_id2){
                                    $addr_id = "<input type='radio' name='addr' value='".$value->addr_id."' checked >";
                                }else{
                                    $addr_id = "<input type='radio' name='addr' value='".$value->addr_id."'>";
                                }
                                
                            }else{
                                $addr_id = "";
                            }

                            $tb .= "
                                   <tr>
                                      <td>
                                      ".$value->prefix_name."
                                      ".$value->first_name." 
                                      ".$value->last_name."
                                      </td> 
                                      <td>
                                      ".($addr?$addr:'-ไม่พบข้อมูลที่อยู่-')."
                                      </td>
                                      <td><center> ".$addr_id."
                                      </td>
                                   </tr> 
                            ";

                            $i++;
                      }
            $tb .= "</tbody></table> <input type='hidden' name='id' value='".$request->v."'> ";

            return $tb;



    }

    public function createPDFCoverSheet01($id)
     {
        // dd($id);
        $data = [$id];
        $qrcode = \QrCode::size(150)->generate('0003-BL002');
        // dd($qrcode);
        $pdf = PDF::loadView('backend.delivery.pdf_view',compact('data','qrcode'))->setPaper('a6', 'landscape');
        // return $pdf->download('cover_sheet.pdf'); // โหลดทันที
        return $pdf->stream('cover_sheet.pdf'); // เปิดไฟลฺ์

    }

    public function createPDFReceipt01($id)
     {
        // dd($id);
        $data = [$id];
        $pdf = PDF::loadView('backend.delivery.print_receipt',compact('data'));
        // return $pdf->download('cover_sheet.pdf'); // โหลดทันที
        return $pdf->stream('receipt_sheet.pdf'); // เปิดไฟลฺ์

    }


    public function createPDFCoverSheet02($id)
     {
        // dd($id);
        $data = [$id];
        $qrcode = \QrCode::size(150)->generate('0003-BL002');
        // dd($qrcode);
        $pdf = PDF::loadView('backend.delivery_packing.pdf_view',compact('data','qrcode'))->setPaper('a6', 'landscape');
        // return $pdf->download('cover_sheet.pdf'); // โหลดทันที
        return $pdf->stream('cover_sheet.pdf'); // เปิดไฟลฺ์

    }

    public function createPDFReceipt02($id)
     {
        // dd($id);
        $data = [$id];
        $pdf = PDF::loadView('backend.delivery_packing.print_receipt',compact('data'));
        // return $pdf->download('cover_sheet.pdf'); // โหลดทันที
        return $pdf->stream('receipt_sheet.pdf'); // เปิดไฟลฺ์

    }


    public function createPDFReceipt03($id)
     {
        // dd($id);
        $data = [$id];
        $pdf = PDF::loadView('backend.pickup_goods.print_receipt',compact('data'));
        // return $pdf->download('cover_sheet.pdf'); // โหลดทันที
        return $pdf->stream('receipt_sheet.pdf'); // เปิดไฟลฺ์

    }


    public function createPDFReceipt04($id)
     {
        // dd($id);
        $data = [$id];
        $pdf = PDF::loadView('backend.pickup_goods.print_receipt_pack',compact('data'));
        // return $pdf->download('cover_sheet.pdf'); // โหลดทันที
        return $pdf->stream('receipt_sheet.pdf'); // เปิดไฟลฺ์

    }

    public function createPDFTransfer($id)
     {
        // dd($id);
        $data = [$id];
        $pdf = PDF::loadView('backend.transfer_warehouses.print_transfer',compact('data'));
        $pdf->setPaper('A4', 'landscape');
        
        // $pdf->showWatermarkImage = true;
        // $pdf->showWatermarkImage(public_path('images/logo.png'));
        // return $pdf->download('cover_sheet.pdf'); // โหลดทันที
        return $pdf->stream('receipt_sheet.pdf'); // เปิดไฟลฺ์

    }

    public function createPDFTransfer_branch($id)
     {
        // dd($id);
        $data = [$id];
        $pdf = PDF::loadView('backend.transfer_branch.print_transfer',compact('data'));
        $pdf->setPaper('A4', 'landscape');
        // return $pdf->download('cover_sheet.pdf'); // โหลดทันที
        return $pdf->stream('receipt_sheet.pdf'); // เปิดไฟลฺ์

    }



    public function createPDFBorrow($id)
     {
        // dd($id);
        $data = [$id];
        $pdf = PDF::loadView('backend.products_borrow.print_products_borrow',compact('data'));
        $pdf->setPaper('A4', 'landscape');
        
        // $pdf->showWatermarkImage = true;
        // $pdf->showWatermarkImage(public_path('images/logo.png'));
        // return $pdf->download('cover_sheet.pdf'); // โหลดทันที
        return $pdf->stream('receipt_sheet.pdf'); // เปิดไฟลฺ์

    }

        
    public function ajaxApprovePickupGoods(Request $req)
    {
        // return($req->id);
        $d = DB::select(" select * from db_delivery WHERE id=".$req->id." ");
        // return $d;
        if($d[0]->approver=="0"){
            $user = \Auth::user()->id;
            DB::select(" UPDATE db_delivery SET approver='$user',approved_date=CURDATE() WHERE id=".$req->id." ");
        }else{
            DB::select(" UPDATE db_delivery SET approver=0,approved_date=NULL WHERE id=".$req->id." ");
        }

    }


    public function ajaxGetWarehouse(Request $request)
    {
        if($request->ajax()){
          $query = \App\Models\Backend\Warehouse::where('branch_id_fk',$request->branch_id_fk)->get()->toArray();
          return response()->json($query);      
        }
    }   

    public function ajaxGetZone(Request $request)
    {
        if($request->ajax()){
          $query = \App\Models\Backend\Zone::where('warehouse_id_fk',$request->warehouse_id_fk)->get()->toArray();
          return response()->json($query);      
        }
    }    

    public function ajaxGetShelf(Request $request)
    {
        if($request->ajax()){
          $query = \App\Models\Backend\Shelf::where('zone_id_fk',$request->zone_id_fk)->get()->toArray();
          return response()->json($query);      
        }
    }    


    public function ajaxGetAmphur(Request $request)
    {
        if($request->ajax()){

          $query = DB::select(" select *,name_th as amphur_name from dataset_amphures where province_id=".$request->province_id." order by name_th ");

          return response()->json($query);      
        }
    } 

    public function ajaxGetTambon(Request $request)
    {
        if($request->ajax()){

          $query = DB::select(" select *,name_th as tambon_name from dataset_districts where amphure_id=".$request->amphur_id." order by name_th ");

          return response()->json($query);      
        }
    } 


    public function ajaxGetZipcode(Request $request)
    {
        if($request->ajax()){

          $query = DB::select(" select * from dataset_districts where id='".$request->tambon_id."'  ");

          return response()->json($query);      
        }
    } 

    public function ajaxGetSetToWarehouse(Request $request)
    {
       // return $request->id;
        $sRow = \App\Models\Backend\Transfer_choose::where('id',$request->id)->get();
        // $Branch = \App\Models\Backend\Branchs::find($sRow[0]->branch_id_fk);
        // $Zone = \App\Models\Backend\Zone::find($sRow[0]->zone_id_fk);
        // $Shelf = \App\Models\Backend\Shelf::find($sRow[0]->shelf_id_fk);

        foreach ($sRow as $key => $value) {
            $json_result[] = [
                'id'=>$value->id ,
                'branch_id_fk'=>$value->branch_id_fk ,
                'warehouse_id_fk'=>$value->warehouse_id_fk ,
                'zone_id_fk'=>$value->zone_id_fk ,
                'shelf_id_fk'=>$value->shelf_id_fk ,
            ];
        }

        return json_encode($json_result);


    }    

    public function ajaxGetSetToWarehouseBranch(Request $request)
    {
       // return $request->id;
        $sRow = \App\Models\Backend\Transfer_choose_branch::where('action_user','=',\Auth::user()->id)->get();
        // $Branch = \App\Models\Backend\Branchs::find($sRow[0]->branch_id_fk);
        // $Zone = \App\Models\Backend\Zone::find($sRow[0]->zone_id_fk);
        // $Shelf = \App\Models\Backend\Shelf::find($sRow[0]->shelf_id_fk);

        foreach ($sRow as $key => $value) {
            $json_result[] = [
                'id'=>$value->id ,
                'branch_id_fk'=>$value->branch_id_fk ,
                'branch_id_fk_to'=>$value->branch_id_fk_to ,
            ];
        }

        return json_encode($json_result);


    } 


    public function ajaxGetSetToBranch(Request $request)
    {
       // return $request->id;
        $sRow = \App\Models\Backend\Transfer_choose_branch::where('id',$request->id)->get();
        // $Branch = \App\Models\Backend\Branchs::find($sRow[0]->branch_id_fk);
        // $Zone = \App\Models\Backend\Zone::find($sRow[0]->zone_id_fk);
        // $Shelf = \App\Models\Backend\Shelf::find($sRow[0]->shelf_id_fk);

        foreach ($sRow as $key => $value) {
            $json_result[] = [
                'id'=>$value->id ,
                'branch_id_fk'=>$value->branch_id_fk ,
                'warehouse_id_fk'=>$value->warehouse_id_fk ,
                'zone_id_fk'=>$value->zone_id_fk ,
                'shelf_id_fk'=>$value->shelf_id_fk ,
            ];
        }

        return json_encode($json_result);


    }  


    public function createPDFReceiptFrontstore($id)
     {
        // dd($id);
        $data = [$id];
        $pdf = PDF::loadView('backend.frontstore.print_receipt',compact('data'));
        // return $pdf->download('cover_sheet.pdf'); // โหลดทันที
        return $pdf->stream('receipt_sheet.pdf'); // เปิดไฟลฺ์

    }



   public function ajaxGetProduct(Request $request)
    {
        // echo $request->product_id_fk;

          $Products = DB::select(" 
                SELECT products.id as product_id,
                  products.product_code,
                  (CASE WHEN products_details.product_name is null THEN '* ไม่ได้กรอกชื่อสินค้า' ELSE products_details.product_name END) as product_name ,
                  products_cost.selling_price,
                  products_cost.pv
                  FROM
                  products_details
                  Left Join products ON products_details.product_id_fk = products.id
                  LEFT JOIN products_cost on products.id = products_cost.product_id_fk
                  WHERE lang_id=1 AND products.id= ".$request->product_id_fk."

           ");

            $pn = @$Products[0]->product_code." : ".@$Products[0]->product_name;
            $pv = @$Products[0]->pv;
            $selling_price = @$Products[0]->selling_price;


            $p_unit = DB::select("
              SELECT product_unit
              FROM
              dataset_product_unit
              WHERE id = ".$request->product_id_fk." AND  lang_id=1 ");

            $unit =  @$p_unit[0]->product_unit;

            $p_amt = DB::select("
              SELECT amt
              FROM
              db_frontstore_products_list
              WHERE product_id_fk = ".$request->product_id_fk." AND frontstore_id_fk=".$request->frontstore_id." ");
            $p_amt =  @$p_amt[0]->amt;

            $v = "รหัส : ชื่อสินค้า : ".$pn." \rหน่วย : ".$unit." \rPV : ".$pv." \rราคาขาย (บาท) : ".$selling_price." ";

            $tb = '<textarea class="form-control" rows="5" disabled style="text-align: left !important;background: #f2f2f2;" >'.trim($v).'</textarea>
            <input type="hidden" id="p_amt" value="'.$p_amt.'">';

            return $tb;



    }



   public function ajaxGetProductPromotionCus(Request $request)
    {
        // echo $request->product_id_fk;

          $Products = DB::select(" 
                SELECT products.id as product_id,
                  products.product_code,
                  (CASE WHEN products_details.product_name is null THEN '* ไม่ได้กรอกชื่อสินค้า' ELSE products_details.product_name END) as product_name ,
                  products_cost.selling_price,
                  products_cost.pv
                  FROM
                  products_details
                  Left Join products ON products_details.product_id_fk = products.id
                  LEFT JOIN products_cost on products.id = products_cost.product_id_fk
                  WHERE lang_id=1 AND products.id= ".$request->product_id_fk."

           ");

            $pn = @$Products[0]->product_code." : ".@$Products[0]->product_name;
            $pv = @$Products[0]->pv;
            $selling_price = @$Products[0]->selling_price;

            $v = "รหัส : ชื่อสินค้า : ".$pn." \rPV : ".$pv." \rราคาขาย (บาท) : ".$selling_price." ";

            $tb = '<textarea class="form-control" rows="4" disabled style="text-align: left !important;background: #f2f2f2;" >'.trim($v).'</textarea>
            ';

            return $tb;



    }

         
    public function generate_string($input, $strength = 6 )  {
        $input_length = strlen($input);
        $random_string = '';
        for($i = 0; $i < $strength; $i++) {
            $random_character = $input[mt_rand(0, $input_length - 1)];
            $random_string .= $random_character;
        }
        return $random_string;
    }


    public function ajaxGenPromotionCode(Request $request)
    {

        // dd ($request->all());
        // return ($request->promotion_name);
        // dd ($request->all());
        // return $request->promotion_code_id_fk.":".$request->amt_gen.":".$request->amt_random.":".$request->prefix_coupon.":".$request->suffix_coupon;
        // dd();

         if( $request->promotion_code_id_fk ){
            $sRow = \App\Models\Backend\PromotionCode::find($request->promotion_code_id_fk );
          }else{
            $sRow = new \App\Models\Backend\PromotionCode;
          }

            $sRow->promotion_name = $request->promotion_name;
            $sRow->pro_sdate = $request->pro_sdate;
            $sRow->pro_edate = $request->pro_edate;
            // $sRow->pro_status = 5 ;
            $sRow->created_at = date('Y-m-d H:i:s');
            $sRow->save();


         try {
             $obtion = array(PDO::MYSQL_ATTR_INIT_COMMAND=>'SET NAMES utf8',);
             $conn = new PDO('mysql:host=localhost;dbname=aiyara_db', 'root', '',$obtion);
             $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch(Exception $e) {
              exit('Unable to connect to database.');
         }

        $conn->prepare(" TRUNCATE rand_code ; ")->execute();
        $permitted_chars = '23456789ABCDEFGHJKMNPQRSTUVWXYZ';

        $str_digit = $request->amt_random ; 
        $amt = $request->amt_gen; 
        $percent = $amt * (20/100); 

        // return $amt.":".$str_digit.":".$percent;
        // dd();


        $arr = [];
        for ($i=1; $i <= ($amt + $percent) ; $i++) { 
            array_push($arr,$this->generate_string($permitted_chars, $str_digit));
        }

        // return $arr;
        // dd();

        $unique = array_unique($arr);
        $duplicates = array_diff_assoc($arr, $unique);
        $result = array_unique($arr);
        $ch1 = (intval($amt)-intval(sizeof($result)));
        if($ch1>0){
            for ($i=1; $i <= $ch1 ; $i++) { 
                array_push($result,$this->generate_string($permitted_chars, $str_digit));
            }

            $unique = array_unique($result);
            $duplicates = array_diff_assoc($result, $unique);

        }

        $r = array_slice($unique, 0, $amt);

            foreach ($r as $key => $value) {
                 $conn->prepare(" INSERT IGNORE INTO rand_code (rand_code) VALUES ('".$value."')  ")->execute();
            }

        $sql = " SELECT * FROM rand_code "; 
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $num_rows = $stmt->rowCount();

        $sql = " SELECT * FROM rand_code "; 
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $num_rows = $stmt->rowCount();
        // echo " ข้อมูลที่ต้องการ = ".$amt." ข้อมูลในตาราง = ".$num_rows;

        if($num_rows < $amt){
            // echo " จำนวนหลักไม่พอ"; 
            return 'x';
            exit;
        }

        $rows = DB::select(" SELECT * from rand_code ");

        foreach ($rows as $key => $value) {
              $insertData = array(
                 "promotion_code_id_fk"=>$sRow->id,
                 "promotion_code"=>@$request->prefix_coupon.($value->rand_code).@$request->suffix_coupon,
                 "customer_id_fk"=>'0',
                 "pro_status"=> '5' ,
                 "created_at"=>now());
               PromotionCode_add::insertData($insertData);
        }
         
        // for ($i=1; $i <= $amt ; $i++) { 

        //       $insertData = array(
        //          "promotion_code_id_fk"=>$sRow->id,
        //          "promotion_code"=>@$request->prefix_coupon.strtoupper(generate_string($permitted_chars, $str_digit)).@$request->suffix_coupon,
        //          "customer_id_fk"=>'0',
        //          "pro_status"=> '5' ,
        //          "created_at"=>now());
        //        PromotionCode_add::insertData($insertData);
        // }

        // return $sRow->id ;


    }

    public function ajaxCheckCouponUsed(Request $request)
    {
        // return $request->txtSearchPro;
        $promotion_cus = DB::select(" 
            select * from db_promotion_cus 
            Left Join db_promotion_code ON db_promotion_cus.promotion_code_id_fk = db_promotion_code.id
            Left Join promotions ON db_promotion_code.promotion_id_fk = promotions.id
            WHERE db_promotion_cus.promotion_code='".$request->txtSearchPro."' AND promotions.status=1 AND promotions.promotion_coupon_status=1 ; ");
        // return $promotion_cus[0]->promotion_code_id_fk;

        $promotion_code = DB::select("
            select * from db_promotion_code
            Left Join promotions ON db_promotion_code.promotion_id_fk = promotions.id
            WHERE db_promotion_code.promotion_id_fk='".@$promotion_cus[0]->promotion_code_id_fk."' AND promotions.status=1 AND promotions.promotion_coupon_status=1;
            ; ");

        // return($promotion_code[0]->approve_status);

        $rs = DB::select(" select count(*) as cnt from db_frontstore_products_list WHERE promotion_code='".$request->txtSearchPro."' ; ");
        // return $rs[0]->cnt;
        if($rs[0]->cnt > 0){
            return "InActive";
        }else if(@$promotion_code[0]->approve_status==0){
            return "InActive";
        }else{
            return true;
        }
        

    }

    public function ajaxGetPromotionCode(Request $request)
    {
        // return $request->txtSearchPro;
        $rs = DB::select(" 
            select * from db_promotion_cus 
            Left Join db_promotion_code ON db_promotion_cus.promotion_code_id_fk = db_promotion_code.id
            Left Join promotions ON db_promotion_code.promotion_id_fk = promotions.id
            WHERE db_promotion_cus.promotion_code='".$request->txtSearchPro."' AND promotions.status=1 ;
          ");
        return $rs[0]->promotion_code_id_fk;
    }

    public function ajaxGetPromotionName(Request $request)
    {
        // return $request->txtSearchPro;
        $rs = DB::select(" SELECT promotions.*, name_thai as pro_name FROM promotions WHERE id=(SELECT promotion_code_id_fk FROM db_promotion_cus WHERE promotion_code='".$request->txtSearchPro."')  ");

        return response()->json($rs);      


    }

    public function ajaxGenPromotionCodePrefixCoupon(Request $request)
    {
        DB::update(" UPDATE db_promotion_cus SET promotion_code=concat('".$request->prefix_coupon."',promotion_code) WHERE pro_status=5 ; ");
    }
    


    public function ajaxClearDataPromotionCode(Request $request)
    {
        if($request->param=="Import"){
            DB::delete(" DELETE FROM db_promotion_cus WHERE promotion_code_id_fk =".$request->promotion_code_id_fk." and pro_status=4 ; ");
        }
        if($request->param=="Gen"){
            DB::delete(" DELETE FROM db_promotion_cus WHERE promotion_code_id_fk =".$request->promotion_code_id_fk." and pro_status=5 ; ");
        }        
        DB::select(" ALTER table db_promotion_cus AUTO_INCREMENT=1; ");
    }



}
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
// use App\Model\CourseModel;
// use App\Model\MemberModel;
// use App\Model\MemberAddModel;  
// use App\Http\Controllers\backend\ActiveMemberController;
// use App\Http\Controllers\backend\ActiveCourseController;

class AjaxController extends Controller
{

    public function ajaxClearDataPm_broadcast()
    {
        DB::delete(" TRUNCATE `pm_broadcast` ");
    }

    public function ajaxFetchData(Request $request)
    {
        // DB::delete(" TRUNCATE `course_event_regis` ");

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

        // return $request->v;

          // $DP = DB::table('db_delivery_pending')->where('pending_code',$request->v)->get();
          // $array = array();
          // foreach ($DP as $key => $value) {
          //   $rs = DB::table('db_delivery')->where('id',$value->delivery_id_fk)->get();
          //   $Customer = DB::select(" select * from customers where id=".@$rs[0]->customer_id." ");
          //   array_push($array, $Customer[0]->prefix_name.$Customer[0]->first_name." ".$Customer[0]->last_name);
          // }

          $data = DB::select(" 
                SELECT
                db_delivery_pending.id,
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
                db_delivery_pending
                Left Join db_delivery ON db_delivery_pending.delivery_id_fk = db_delivery.id
                Left Join customers ON db_delivery.customer_id = customers.id
                Left Join customers_detail ON db_delivery.customer_id = customers_detail.customer_id
                WHERE pending_code=".$request->v."

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
                db_delivery_pending.id,
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
                db_delivery_pending_code.addr_id AS addr_id2
                FROM
                db_delivery_pending
                Left Join db_delivery ON db_delivery_pending.delivery_id_fk = db_delivery.id
                Left Join customers ON db_delivery.customer_id = customers.id
                Left Join customers_detail ON db_delivery.customer_id = customers_detail.customer_id
                Left Join db_delivery_pending_code ON db_delivery_pending.pending_code = db_delivery_pending_code.id
                WHERE pending_code=".$request->v."

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

    public function createPDFCoverSheet($id)
     {
        // dd($id);
        $data = [$id];
        $pdf = PDF::loadView('backend.delivery.pdf_view',compact('data'))->setPaper('a6', 'landscape');
        // return $pdf->download('cover_sheet.pdf'); // โหลดทันที
        return $pdf->stream('cover_sheet.pdf'); // เปิดไฟลฺ์

    }

    public function createPDFReceipt($id)
     {
        // dd($id);
        $data = [$id];
        $pdf = PDF::loadView('backend.delivery.print_receipt',compact('data'));
        // return $pdf->download('cover_sheet.pdf'); // โหลดทันที
        return $pdf->stream('receipt_sheet.pdf'); // เปิดไฟลฺ์

    }


}
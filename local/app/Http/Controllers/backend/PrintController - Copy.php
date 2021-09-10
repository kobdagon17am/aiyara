<?php
namespace App\Http\Controllers\backend;
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
use Auth;
use App\Models\Backend\PromotionCode_add;
use App\Models\Backend\GiftvoucherCode_add;
use App\Models\Frontend\CourseCheckRegis;
use App\Models\Frontend\PvPayment;

class PrintController extends Controller
{

 
    public function frontstore_print_receipt_02($id)
     {
        // dd($id);

$sRow = \App\Models\Backend\Frontstore::find($id);
// $Delivery_location = DB::select(" select id,txt_desc from dataset_delivery_location  ");
// $CusAddrFrontstore = \App\Models\Backend\CusAddrFrontstore::where('frontstore_id_fk',$data[0])->get();

$TABLE = 'temp_z01_print_frontstore_print_receipt_02'.\Auth::user()->id;
DB::select(" DROP TABLE IF EXISTS $TABLE ; ");
DB::select(" 
    CREATE TABLE $TABLE (
      `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
      `a` text,
      `b` text,
      `c` text,
      `d` text,
      `e` text,
      `f` text,
      `g` text,
      PRIMARY KEY (`id`) USING BTREE
    ) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='เป็นตารางชั่วคราว เอาไว้ประมวลผล พิมพ์ใบเสร็จ';
");

   $db_orders = DB::select("
            SELECT db_orders.*,branchs.b_code as branch_code
            FROM db_orders
            LEFT Join branchs ON db_orders.branch_id_fk = branchs.id
            WHERE
            db_orders.id = '$id'
       ");

    if(@$sRow->delivery_location!=0){ 
       $branch_code = $db_orders[0]->branch_code;
    }else{
       $branch_code = '';
    }
  
    $action_user = DB::select(" select * from ck_users_admin where id=".@$db_orders[0]->action_user." ");
    $action_user_name = @$action_user[0]->name;


 $address = "หมู่บ้าน 90/16 ( หลังตลาดเทวราช ) ต.บ้านระกาศ อ.บางบ่อ จ.สมุทรปราการ รหัส ปณ. 10560";

 DB::select(" INSERT INTO $TABLE VALUES ('1', '$branch_code\r', null, null, null, null, null, null); ");
 DB::select(" UPDATE $TABLE SET a='$branch_code' where id=1 ; ");

 DB::select(" INSERT INTO $TABLE VALUES ('2', 'A0000009', null, null, null, null, null, null); ");
 DB::select(" INSERT INTO $TABLE VALUES ('3', 'คุณ ปริยากรโสภณ  พงศ์บุณยภา', null, null, null, null, null, null); ");
 DB::select(" INSERT INTO $TABLE VALUES ('4', 'O121090300002 ', null, null, null, null, null, null); ");
 DB::select(" INSERT INTO $TABLE VALUES ('5', '03/09/2564', null, null, null, null, null, null); ");
 DB::select(" INSERT INTO $TABLE VALUES ('6', '1', 'FITME TO EXCLUSIVE', null, '15,000', '2500pv', '1', '15,000.00'); ");
 DB::select(" INSERT INTO $TABLE VALUES ('7', null, '[Pro1015] AIMMURA-E ', '2 x 1= 2 กล่อง', null, null, null, null); ");
 DB::select(" INSERT INTO $TABLE VALUES ('8', null, '[Pro1028] ITEM PRO ', '3 x 1= 3 กล่อง', null, null, null, null); ");
 DB::select(" INSERT INTO $TABLE VALUES ('9', null, '[Pro1028] ITEM PRO ', '3 x 1= 3 กล่อง', null, null, null, null); ");
 DB::select(" INSERT INTO $TABLE VALUES ('10', null, '[Pro1028] ITEM PRO ', '3 x 1= 3 กล่อง', null, null, null, null); ");
 DB::select(" INSERT INTO $TABLE VALUES ('11', null, '[Pro1028] ITEM PRO ', '3 x 1= 3 กล่อง', null, null, null, null); ");
 DB::select(" INSERT INTO $TABLE VALUES ('12', null, '[Pro1028] ITEM PRO ', '3 x 1= 3 กล่อง', null, null, null, null); ");
 DB::select(" INSERT INTO $TABLE VALUES ('13', null, '[Pro1028] ITEM PRO ', '3 x 1= 3 กล่อง', null, null, null, null); ");
 DB::select(" INSERT INTO $TABLE VALUES ('14', null, '[Pro1028] ITEM PRO ', '3 x 1= 3 กล่อง', null, null, null, null); ");
 DB::select(" INSERT INTO $TABLE VALUES ('15', null, '[Pro1028] ITEM PRO ', '3 x 1= 3 กล่อง', null, null, null, null); ");
 DB::select(" INSERT INTO $TABLE VALUES ('19', null, null, null, null, null, null, '&nbsp;'); ");
 DB::select(" INSERT INTO $TABLE VALUES ('18', null, null, null, null, null, null, '&nbsp;'); ");
 DB::select(" INSERT INTO $TABLE VALUES ('16', 'REF : [ 2 ] AG : [ $branch_code ] SK : [ - ] คะแนนครั้งนี้ : [ 2500 pv ]', null, null, null, null, null, null); ");
 DB::select(" INSERT INTO $TABLE VALUES ('17', 'ชำระ : [ สด=15,100.00 ] พนักงาน : [ $action_user_name ] การจัดส่ง : [ 4/100 ]', null, null, null, null, null, null); ");
 DB::select(" INSERT INTO $TABLE VALUES ('20', null, null, null, null, null, null, '&nbsp;'); ");
 DB::select(" INSERT INTO $TABLE VALUES ('21', '&nbsp;', null, null, null, null, null, null); ");
 DB::select(" INSERT INTO $TABLE VALUES ('22', null, null, null, null, null, null, '(หน้า 1/3 ต่อหน้า 2)'); ");
 DB::select(" INSERT INTO $TABLE VALUES ('23', '$branch_code', null, null, null, null, null, null); ");
 DB::select(" INSERT INTO $TABLE VALUES ('24', 'A0000009', null, null, null, null, null, null); ");
 DB::select(" INSERT INTO $TABLE VALUES ('25', 'คุณ ปริยากรโสภณ  พงศ์บุณยภา', null, null, null, null, null, null); ");
 DB::select(" INSERT INTO $TABLE VALUES ('26', 'O121090300002 ', null, null, null, null, null, null); ");
 DB::select(" INSERT INTO $TABLE VALUES ('27', '03/09/2564', null, null, null, null, null, null); ");
 DB::select(" INSERT INTO $TABLE VALUES ('28', null, '[Pro1015] AIMMURA-E ', '2 x 1= 2 กล่อง', null, null, null, null); ");
 DB::select(" INSERT INTO $TABLE VALUES ('29', null, '[Pro1015] AIMMURA-E ', '2 x 1= 2 กล่อง', null, null, null, null); ");
 DB::select(" INSERT INTO $TABLE VALUES ('30', null, '[Pro1028] ITEM PRO ', '3 x 1= 3 กล่อง', null, null, null, null); ");
 DB::select(" INSERT INTO $TABLE VALUES ('31', null, '[Pro1028] ITEM PRO ', '3 x 1= 3 กล่อง', null, null, null, null); ");
 DB::select(" INSERT INTO $TABLE VALUES ('32', null, '[Pro1028] ITEM PRO ', '3 x 1= 3 กล่อง', null, null, null, null); ");
 DB::select(" INSERT INTO $TABLE VALUES ('33', null, '[Pro1028] ITEM PRO ', '3 x 1= 3 กล่อง', null, null, null, null); ");
 DB::select(" INSERT INTO $TABLE VALUES ('34', null, '[Pro1028] ITEM PRO ', '3 x 1= 3 กล่อง', null, null, null, null); ");
 DB::select(" INSERT INTO $TABLE VALUES ('35', null, '[Pro1028] ITEM PRO ', '3 x 1= 3 กล่อง', null, null, null, null); ");
 DB::select(" INSERT INTO $TABLE VALUES ('36', null, '[Pro1028] ITEM PRO ', '3 x 1= 3 กล่อง', null, null, null, null); ");
 DB::select(" INSERT INTO $TABLE VALUES ('37', null, '[Pro1028] ITEM PRO ', '3 x 1= 3 กล่อง', null, null, null, null); ");
 DB::select(" INSERT INTO $TABLE VALUES ('38', '&nbsp;', null, null, null, null, null, null); ");
 DB::select(" INSERT INTO $TABLE VALUES ('39', '&nbsp;', null, null, null, null, null, null); ");
 DB::select(" INSERT INTO $TABLE VALUES ('40', null, null, null, null, null, null, '&nbsp;'); ");
 DB::select(" INSERT INTO $TABLE VALUES ('41', null, null, null, null, null, null, '&nbsp;'); ");
 DB::select(" INSERT INTO $TABLE VALUES ('42', null, null, null, null, null, null, '&nbsp;'); ");
 DB::select(" INSERT INTO $TABLE VALUES ('43', '&nbsp;', null, null, null, null, null, null); ");
 DB::select(" INSERT INTO $TABLE VALUES ('44', null, null, null, null, null, null, '(หน้า 2/3 ต่อหน้า 3)'); ");
 DB::select(" INSERT INTO $TABLE VALUES ('46', 'A0000009', null, null, null, null, null, null); ");
 DB::select(" INSERT INTO $TABLE VALUES ('47', 'คุณ ปริยากรโสภณ  พงศ์บุณยภา', null, null, null, null, null, null); ");
 DB::select(" INSERT INTO $TABLE VALUES ('48', 'O121090300002 ', null, null, null, null, null, null); ");
 DB::select(" INSERT INTO $TABLE VALUES ('49', '03/09/2564', null, null, null, null, null, null); ");
 DB::select(" INSERT INTO $TABLE VALUES ('45', '$branch_code', null, null, null, null, null, null); ");
 DB::select(" INSERT INTO $TABLE VALUES ('50', null, '[Pro1028] ITEM PRO ', '3 x 1= 3 กล่อง', null, null, null, null); ");
 DB::select(" INSERT INTO $TABLE VALUES ('51', null, '[Pro1015] AIMMURA-E ', '2 x 1= 2 กล่อง', null, null, null, null); ");
 DB::select(" INSERT INTO $TABLE VALUES ('52', null, '[Pro1028] ITEM PRO ', '3 x 1= 3 กล่อง', null, null, null, null); ");
 DB::select(" INSERT INTO $TABLE VALUES ('53', null, '[Pro1028] ITEM PRO ', '3 x 1= 3 กล่อง', null, null, null, null); ");
 DB::select(" INSERT INTO $TABLE VALUES ('54', null, '[Pro1028] ITEM PRO ', '3 x 1= 3 กล่อง', null, null, null, null); ");
 DB::select(" INSERT INTO $TABLE VALUES ('55', null, '[Pro1028] ITEM PRO ', '3 x 1= 3 กล่อง', null, null, null, null); ");
 DB::select(" INSERT INTO $TABLE VALUES ('56', null, '[Pro1028] ITEM PRO ', '3 x 1= 3 กล่อง', null, null, null, null); ");
 DB::select(" INSERT INTO $TABLE VALUES ('57', null, '[Pro1028] ITEM PRO ', '3 x 1= 3 กล่อง', null, null, null, null); ");
 DB::select(" INSERT INTO $TABLE VALUES ('58', null, 'รหัสโปร : S2108FME2', null, null, null, null, null); ");
 DB::select(" INSERT INTO $TABLE VALUES ('59', null, null, null, null, null, null, null); ");
 DB::select(" INSERT INTO $TABLE VALUES ('60', '&nbsp;', null, null, null, null, null, null); ");
 DB::select(" INSERT INTO $TABLE VALUES ('61', '&nbsp;', null, null, null, null, null, null); ");
 DB::select(" INSERT INTO $TABLE VALUES ('62', null, null, null, null, null, null, '15,000.00'); ");
 DB::select(" INSERT INTO $TABLE VALUES ('63', null, null, null, null, null, null, '981.31'); ");
 DB::select(" INSERT INTO $TABLE VALUES ('64', null, null, null, null, null, null, '15,100.00'); ");
 DB::select(" INSERT INTO $TABLE VALUES ('65', 'ชื่อ-ที่อยู่ผู้รับ: $address ', null, null, null, null, null, null); ");
 //DB::select(" INSERT INTO $TABLE VALUES ('65', 'ชื่อ-ที่อยู่ผู้รับ: หมู่บ้าน 90/16 ( หลังตลาดเทวราช ) ต.บ้านระกาศ อ.บางบ่อ จ.สมุทรปราการ รหัส ปณ. 10560', null, null, null, null, null, null); ");
 DB::select(" INSERT INTO $TABLE VALUES ('66', null, null, null, null, null, null, 'หน้า 3/3'); ");

        $data = [$id];
        // width and height $customPaper = The end result was: 10CM X 20CM = array(0,0,567.00,283.80); size 9.5" x 5.5"  24.13 cm x 13.97 cm
        $customPaper = array(0,0,370,565); 
        $pdf = PDF::loadView('backend.frontstore.test_print_receipt_02',compact('data'))->setPaper($customPaper, 'landscape');
        // return $pdf->download('cover_sheet.pdf'); // โหลดทันที
        return $pdf->stream('receipt_sheet.pdf'); // เปิดไฟลฺ์

    }
// medthod


}
// Class
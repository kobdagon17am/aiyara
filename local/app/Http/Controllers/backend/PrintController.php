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


    public function frontstore_print_receipt_022($id,$user_id=0)
     {
      if($user_id!=0){
        if($user_id!=@Auth::guard('c_user')->user()->id){
          return redirect()->to('')->withError('คุณไม่สามารถทำรายการได้');
        }
        $user_admin_id = @Auth::guard('c_user')->user()->id;
      }else{
        $user_admin_id = @Auth::user()->id;
      }

        $data = [$id,$user_admin_id];
        // width and height $customPaper = The end result was: 10CM X 20CM = array(0,0,567.00,283.80); size 9.5" x 5.5"  24.13 cm x 13.97 cm
        $customPaper = array(0,0,370,565);
        $pdf = PDF::loadView('backend.frontstore.print_receipt_022',compact('data'))->setPaper($customPaper, 'landscape');
        // return $pdf->download('cover_sheet.pdf'); // โหลดทันที
        return $pdf->stream('receipt_sheet.pdf'); // เปิดไฟลฺ์

    }

    // วุฒิเพิ่มมา
    public function print_receipt_lading($id)
    {
       $data = [$id];
       // width and height $customPaper = The end result was: 10CM X 20CM = array(0,0,567.00,283.80); size 9.5" x 5.5"  24.13 cm x 13.97 cm
       $customPaper = array(0,0,370,565);
       $pdf = PDF::loadView('backend.frontstore.print_receipt_lading',compact('data'))->setPaper($customPaper, 'landscape');
       // return $pdf->download('cover_sheet.pdf'); // โหลดทันที
       return $pdf->stream('receipt_sheet.pdf'); // เปิดไฟลฺ์

   }
// medthod


    public function frontstore_print_receipt_023(Request $request,$id)
     {

        // $data = [$id];
        // $data = [$request->pick_pack_packing_code_id_fk];
        $data = array($id, $request->pick_pack_packing_code_id_fk);
        // $pick_pack_packing_code_id_fk = [$request->pick_pack_packing_code_id_fk];
        // dd($data);
        // width and height $customPaper = The end result was: 10CM X 20CM = array(0,0,567.00,283.80); size 9.5" x 5.5"  24.13 cm x 13.97 cm
        $customPaper = array(0,0,370,565);
        $pdf = PDF::loadView('backend.frontstore.print_receipt_023',compact('data'))->setPaper($customPaper, 'landscape');
        // return $pdf->download('cover_sheet.pdf'); // โหลดทันที
        return $pdf->stream('receipt_sheet.pdf'); // เปิดไฟลฺ์

    }
// medthod
}
// Class

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
use App\Models\Frontend\LineModel;
use App\Http\Controllers\Frontend\Fc\CancelOrderController;


class AjaxController extends Controller
{

    public function ajaxMenuPermissionControl(Request $request)
    {

        // $position_level = DB::select("select * from dataset_position_level");
        // 1   Super Admin
        // 2   Director
        // 3   Manager
        // 4   Supervisor
        // 5   User
        // return  $request->menu_id;

        $sC = 1;
        $sU = 1;
        $sD = 1;
        $can_cancel_bill = 0;
        $can_cancel_bill_across_day = 0;

        $sPermission = \Auth::user()->permission ;
        $menu_id = $request->menu_id;

        if($sPermission==1){
            $role_group_id = '%';
        }else{
            $role_group_id = \Auth::user()->role_group_id_fk;
            $menu_permit = DB::table('role_permit')->where('role_group_id_fk',$role_group_id)->where('menu_id_fk',$menu_id)->first();

            $sC = @$menu_permit->c;
            $sU = @$menu_permit->u;
            $sD = @$menu_permit->d;

            $can_cancel_bill = @$menu_permit->can_cancel_bill;
            $can_cancel_bill_across_day = @$menu_permit->can_cancel_bill_across_day;
            $can_approve = @$menu_permit->can_approve;

        }

        return response()->json([
            'menu_id' => $request->menu_id,
            'sPermission' => \Auth::user()->permission,
            'sC' => $sC,
            'sU' => $sU,
            'sD' => $sD,
            'can_cancel_bill' => @$can_cancel_bill,
            'can_cancel_bill_across_day' => @$can_cancel_bill_across_day,
            'can_approve' => @$can_approve,
        ]);

    }


    public function ajaxSetSession(Request $request)
    {
        Session::put('session_menu_id', $request->session_menu_id);
         // echo "session created";
        echo (Session::get('session_menu_id'));

        $roleApprove = DB::select(" SELECT can_approve from role_permit WHERE role_group_id_fk=(SELECT role_group_id_fk FROM ck_users_admin WHERE id=".(\Auth::user()->id).") and menu_id_fk='$request->session_menu_id'  ; ");
        // echo $roleApprove[0]->can_approve;
        Session::put('roleApprove', @$roleApprove[0]->can_approve);
        echo (Session::get('roleApprove'));

    }


    public function ajaxClearDataPm_broadcast()
    {
        DB::delete(" TRUNCATE pm_broadcast ");
    }


    public function ajaxSelectAddr(Request $request)
    {

            $rs1 = DB::select("select * from db_delivery_packing where packing_code=".$request->v." ");
            $arr1 = [];
            foreach ($rs1 as $key => $value) {
              array_push($arr1,$value->delivery_id_fk);
            }
            $id = implode(',', $arr1);
            $rs2 = DB::select("select * from db_delivery where id in ($id) ");
            $arr2 = [];
            $arr22 = [];
            foreach ($rs2 as $key => $value) {
              array_push($arr2,"'".$value->receipt."'");
              array_push($arr22,$value->receipt);
            }
            $receipt = implode(',', $arr2);
            $receipt2 = implode(',', $arr22);

            $data = DB::select("select * from customers_addr_sent where receipt_no in($receipt) ");

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
                            $addr .= $value->amphures?", อ.".$value->amphures:'';
                            $addr .= $value->province?", จ.".$value->province:'';
                            $addr .= $value->zipcode?", ".$value->zipcode:'';

                            if($value->id!=''){
                                if($i==1){
                                    $addr_id = "<input type='hidden' name='receipt_no' value='".$receipt2."'><input type='radio' name='id' value='".$value->id."' checked >";
                                }else{
                                    $addr_id = "<input type='hidden' name='receipt_no' value='".$receipt2."'><input type='radio' name='id' value='".$value->id."'>";
                                }

                            }else{
                                $addr_id = "";
                            }

                            $tb .= "
                                   <tr>
                                      <td>
                                      ".$value->recipient_name."
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
            $tb .= "</tbody></table> ";

            return $tb;


    }


    public function ajaxSelectAddrEdit(Request $request)
    {
          $receipt_no = explode(",",$request->receipt_no);
          $arr = [];
          $arr2 = [];
          for ($i=0; $i < sizeof($receipt_no); $i++) {
              array_push($arr, "'".$receipt_no[$i]."'");
              array_push($arr2, $receipt_no[$i]);
          }
          $arr = implode(",",$arr);
          $arr2 = implode(",",$arr2);
          $data = DB::select("
                SELECT
                *
                FROM
                customers_addr_sent
                WHERE receipt_no in ($arr)

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
                            $addr .= $value->amphures?", อ.".$value->amphures:'';
                            $addr .= $value->province?", จ.".$value->province:'';
                            $addr .= $value->zipcode?", ".$value->zipcode:'';

                           if($value->id!=''){
                                if($value->id_choose==1){
                                    $addr_id = "<input type='hidden' name='receipt_no' value='".$arr2."'><input type='radio' name='id' value='".$value->id."' checked >";
                                }else{
                                    $addr_id = "<input type='hidden' name='receipt_no' value='".$arr2."'><input type='radio' name='id' value='".$value->id."'>";
                                }

                            }else{
                                $addr_id = "";
                            }

                            $tb .= "
                                   <tr>
                                      <td>
                                      ".$value->recipient_name."
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
            $tb .= "</tbody></table> ";

            return $tb;



    }

    public function createPDFStock_account($id)
     {
        // dd($id);
        $data = [$id];
        // dd($qrcode);
        $pdf = PDF::loadView('backend.check_stock_account.print_receipt',compact('data'))->setPaper('a4', 'landscape');
        $pdf->getDomPDF()->set_option("enable_php", true);

        // return $pdf->download('cover_sheet.pdf'); // โหลดทันที
        return $pdf->stream('cover_sheet.pdf'); // เปิดไฟลฺ์

    }

    public function createPDFStock_card(Request $request,$id)
     {
        // dd($request->lot_number);
        // dd($id);
        $data['id'] = [$id];
        $data['lot_number'] = [$request->lot_number];
        // dd($qrcode);
        $pdf = PDF::loadView('backend.check_stock.print',compact('data'))->setPaper('a4', 'landscape');
        // return $pdf->download('cover_sheet.pdf'); // โหลดทันที
        return $pdf->stream('cover_sheet.pdf'); // เปิดไฟลฺ์

    }

    public function createPDFPick_warehouse(Request $request,$id)
     {
        // dd($request->lot_number);
        // dd($id);
        $data['id'] = [$id];
        // $data['lot_number'] = [$request->lot_number];
        // dd($qrcode);
        $pdf = PDF::loadView('backend.pick_warehouse.print',compact('data'))->setPaper('a4', 'landscape');
        // return $pdf->download('cover_sheet.pdf'); // โหลดทันที
        return $pdf->stream('cover_sheet.pdf'); // เปิดไฟลฺ์

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

    public function createPDFReceipt022($id)
     {
        // dd($id);
        $data = [$id];
        $pdf = PDF::loadView('backend.frontstore.print_receipt_02',compact('data'));
        // return $pdf->download('cover_sheet.pdf'); // โหลดทันที
        return $pdf->stream('receipt_sheet.pdf'); // เปิดไฟลฺ์

    }

    public function createPDFReceiptPacking($id)
     {
        // dd($id);
        $data = [$id];
        $pdf = PDF::loadView('backend.frontstore.print_receipt_packing',compact('data'));
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
        $customPaper = array(0,0,370,565); // width and height $customPaper = The end result was: 10CM X 20CM = array(0,0,567.00,283.80); size 9.5" x 5.5"  24.13 cm x 13.97 cm
        $pdf = PDF::loadView('backend.frontstore.print_receipt_02',compact('data'))->setPaper($customPaper, 'landscape');
        // return $pdf->download('cover_sheet.pdf'); // โหลดทันที
        return $pdf->stream('receipt_sheet.pdf'); // เปิดไฟลฺ์

    }


    public function createPDFReceiptQr($id)
     {
        // dd($id);
        $data = [$id];
        // $pdf = PDF::loadView('backend.delivery_packing.print_receipt',compact('data'));
        $pdf = PDF::loadView('backend.pick_warehouse.print_receipt_03',compact('data'));
        // return $pdf->download('cover_sheet.pdf'); // โหลดทันที
        return $pdf->stream('receipt_sheet.pdf'); // เปิดไฟลฺ์

    }

    public function createPDFEnvelope($id)
     {
        // dd($id);
        $data = [$id];
        // $pdf = PDF::loadView('backend.delivery_packing.print_receipt',compact('data'));
        $pdf = PDF::loadView('backend.pick_warehouse.print_envelope',compact('data','data'))->setPaper('a6', 'landscape');
        // return $pdf->download('cover_sheet.pdf'); // โหลดทันที
        return $pdf->stream('receipt_sheet.pdf'); // เปิดไฟลฺ์

    }

    public function createPDFRequisition($id)
     {
        // dd($id);
        $data = [$id];
        // $pdf = PDF::loadView('backend.delivery_packing.print_receipt',compact('data'));
        $pdf = PDF::loadView('backend.pick_warehouse.print_requisition',compact('data'));
        // return $pdf->download('cover_sheet.pdf'); // โหลดทันที
        return $pdf->stream('receipt_sheet.pdf'); // เปิดไฟลฺ์

    }


    public function createPDFRequisitionDetail($id,$packing_code_id)
     {
        // dd($id);
        $data = [$id,$packing_code_id];
        // $pdf = PDF::loadView('backend.delivery_packing.print_receipt',compact('data'));
        $pdf = PDF::loadView('backend.pick_warehouse.print_requisition_detail',compact('data'));
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
        // $pdf->setPaper('A4', 'landscape');

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
        // $pdf->setPaper('A4', 'landscape');
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



    public function ajaxAcceptCheckStock(Request $req)
    {
        // return($req->id);
        // 0=NEW,1=PENDDING,2=REQUEST,3=ACCEPTED/APPROVED,4=NO APPROVED,5=CANCELED
        DB::select(" UPDATE db_stocks_account SET status_accepted=3 ");

    }



    public function ajaxGetBranch(Request $request)
    {
        if($request->ajax()){
          $query = \App\Models\Backend\Branchs::where('business_location_id_fk',$request->business_location_id_fk)
            ->when(auth()->user()->permission !== 1, function ($query) {
              return $query->where('id', auth()->user()->business_location_id_fk);
            })
            ->get()
            ->toArray();
          return response()->json($query);
        }
    }


    public function ajaxCheckDubWarehouse(Request $request)
    {
        // return $request;

        if($request->ajax()){
            $r1 = DB::select(" select * from db_stocks
                where id=".(@$request->stocks_id_fk?$request->stocks_id_fk:0)."
                and branch_id_fk=".(@$request->branch_id_fk_c?$request->branch_id_fk_c:0)."
                and warehouse_id_fk=".(@$request->warehouse_id_fk_c?$request->warehouse_id_fk_c:0)."
                and zone_id_fk=".(@$request->zone_id_fk_c?$request->zone_id_fk_c:0)."
                and shelf_id_fk=".(@$request->shelf_id_fk_c?$request->shelf_id_fk_c:0)."
                and shelf_floor=".(@$request->shelf_floor_c?$request->shelf_floor_c:0)."
                ");
            if(!empty($r1)){
                return 1;
            }else{
                return 0;
            }
        }
    }

    public function ajaxGetWarehouseFrom(Request $request)
    {
        // return $request;

        if($request->ajax()){
            $r1 = DB::select("
                SELECT
                    db_stocks.id,
                    db_stocks.business_location_id_fk,
                    db_stocks.branch_id_fk,
                    db_stocks.product_id_fk,
                    db_stocks.lot_number,
                    db_stocks.lot_expired_date,
                    db_stocks.amt,
                    db_stocks.product_unit_id_fk,
                    db_stocks.date_in_stock,
                    db_stocks.warehouse_id_fk,
                    db_stocks.zone_id_fk,
                    db_stocks.shelf_id_fk,
                    db_stocks.shelf_floor,
                    branchs.b_name,
                    warehouse.w_name,
                    zone.z_name,
                    shelf.s_name
                    FROM
                    db_stocks
                    Inner Join branchs ON db_stocks.branch_id_fk = branchs.id
                    Inner Join warehouse ON db_stocks.warehouse_id_fk = warehouse.id
                    Inner Join zone ON db_stocks.zone_id_fk = zone.id
                    Inner Join shelf ON db_stocks.shelf_id_fk = shelf.id
                    WHERE db_stocks.id=
                     ".(@$request->stocks_id_fk?$request->stocks_id_fk:0)."
                    ");
            if(!empty($r1)){
                return @$r1[0]->b_name.' > '.@$r1[0]->w_name.' > '.@$r1[0]->s_name.' > ชั้น '.@$r1[0]->shelf_floor;
            }else{
                return 'No data';
            }
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

    public function ajaxGetLotnumber(Request $request)
    {
        if($request->ajax()){
          // $query = \App\Models\Backend\Check_stock::where('product_id_fk',$request->product_id_fk)->get()->toArray();
          if(@\Auth::user()->permission==1){

            $query = DB::select(" select * from db_stocks where product_id_fk=".$request->product_id_fk." GROUP BY  product_id_fk,lot_number ");

          }else{

             $query = DB::select(" select * from db_stocks where product_id_fk=".$request->product_id_fk." and business_location_id_fk=".@\Auth::user()->business_location_id_fk." AND branch_id_fk=".@\Auth::user()->branch_id_fk." GROUP BY  product_id_fk,lot_number ");
          }

          return response()->json($query);
        }
    }


    public function ajaxGetLotnumber2(Request $request)
    {
        if($request->ajax()){

           $query = \App\Models\Backend\Check_stock::where('product_id_fk',$request->product_id_fk)->get();
          // return response()->json($query);

            $response = array();

            foreach ($query as $key => $value) {
             $response[] = array("value"=>$value->lot_number,"lot_expired_date"=>$value->lot_expired_date);
            }

            return json_encode($response);

           }
    }

    public function ajaxGetLotnumber3(Request $request)
    {
        if($request->ajax()){
          // $query = \App\Models\Backend\Check_stock::where('product_id_fk',$request->product_id_fk)->get()->toArray();
          if(@\Auth::user()->permission==1){

            $query = DB::select(" select * from db_stocks where product_id_fk=".$request->product_id_fk." GROUP BY  product_id_fk,lot_number ");

          }else{

             $query = DB::select(" select * from db_stocks where product_id_fk=".$request->product_id_fk." and business_location_id_fk=".$request->business_location_id_fk." AND branch_id_fk=".$request->branch_id_fk." GROUP BY  product_id_fk,lot_number ");
          }

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
        $json_result = [];
        foreach ($sRow as $key => $value) {
            $json_result[] = [
                'id'=>$value->id ,
                'branch_id_fk'=>$value->branch_id_fk ,
                'warehouse_id_fk'=>$value->warehouse_id_fk ,
                'zone_id_fk'=>$value->zone_id_fk ,
                'shelf_id_fk'=>$value->shelf_id_fk ,
                'shelf_floor'=>$value->shelf_floor ,
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
        $json_result = [];
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
        $json_result = [];
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

    public function createPDFReceiptFrontstore02($id)
     {
        // dd($id);
        $data = [$id];
        $pdf = PDF::loadView('backend.frontstore.print_receipt_02',compact('data'))->setPaper('a5', 'landscape');
        // return $pdf->download('cover_sheet.pdf'); // โหลดทันที
        return $pdf->stream('receipt_sheet.pdf'); // เปิดไฟลฺ์

    }

    public function createPDFpo_supplier_products($id)
     {
        // dd($id);
        $data = [$id];
        $pdf = PDF::loadView('backend.po_supplier_products.print_receipt',compact('data'));
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
                  products_cost.member_price,
                  products_cost.pv
                  FROM
                  products_details
                  Left Join products ON products_details.product_id_fk = products.id
                  LEFT JOIN products_cost on products.id = products_cost.product_id_fk
                  WHERE lang_id=1 AND products.id= ".$request->product_id_fk."

           ");

            $pn = @$Products[0]->product_code." : ".@$Products[0]->product_name;
            $pv = @$Products[0]->pv;
            $member_price = @$Products[0]->member_price;


            $p_unit = DB::select("
              SELECT product_unit
              FROM
              dataset_product_unit
              WHERE id = ".$request->product_id_fk." AND  lang_id=1 ");

            $unit =  @$p_unit[0]->product_unit;

            $p_amt = DB::select("
              SELECT amt
              FROM
              db_order_products_list
              WHERE product_id_fk = ".$request->product_id_fk." AND frontstore_id_fk=".$request->frontstore_id." ");
            $p_amt =  @$p_amt[0]->amt;

            $v = "รหัส : ชื่อสินค้า : ".$pn." \rหน่วย : ".$unit." \rPV : ".$pv." \rราคาขาย (บาท) : ".$member_price." ";

            $tb = '<textarea class="form-control" rows="5" disabled style="text-align: left !important;background: #f2f2f2;" >'.trim($v).'</textarea>
            <input type="hidden" id="p_amt" value="'.$p_amt.'">';

            return $tb;



    }

   public function ajaxGetFeeValue(Request $request)
    {
        // echo $request->product_id_fk;
      if(!empty($request->fee_id)){

          $fee = DB::select("
                SELECT * from dataset_fee where id = ".$request->fee_id."
          ");
                if($fee[0]->fee_type==1){
                    return $fee[0]->txt_value;
                }else{
                    return $fee[0]->txt_fixed_rate;
                }
          }else{
            return null;
          }
    }


   public function ajaxGetDBfrontstore(Request $request)
    {
            $id = $request->frontstore_id_fk;
            if($id){
              $rs = DB::select(" SELECT * FROM db_orders WHERE id=$id ");
              return response()->json($rs);
            }

    }


   public function ajaxCheckDBfrontstore(Request $request)
    {
        $rs = DB::select(" SELECT count(*) as cnt FROM db_order_products_list WHERE frontstore_id_fk='".$request->frontstore_id_fk."' ");
        return $rs[0]->cnt;
    }



   public function ajaxShippingCalculate(Request $request)
    {
        // return $request;
        // dd();
        $sum_price = str_replace(',','',$request->sum_price);
        $frontstore_id = $request->frontstore_id;
        $delivery_location = $request->delivery_location;
        $frontstore = DB::select(" SELECT * FROM db_orders WHERE id=$frontstore_id ");


        // return $sum_price;
        // return $frontstore_id;
        // return $delivery_location;
        // return $frontstore;
        // return $frontstore[0]->check_press_save;

        if($frontstore[0]->check_press_save==2){

     /*
            1   ส่งฟรี / Shipping Free
            2   กรุงเทพฯ และปริมณฑล / Metropolitan area
            3   ต่างจังหวัด / Upcountry
            4   ส่งแบบพิเศษ / Premium  > $request->delivery_location_05
        */
        if(!empty($request->province_id)){
            $province_id = $request->province_id;
        }else{
            $province_id = 0 ;
        }

        if(!empty($request->shipping_special)){

            // return $province_id;
            // dd();

            $shipping = DB::select(" SELECT * FROM dataset_shipping_cost WHERE business_location_id_fk='".$frontstore[0]->business_location_id_fk."' AND shipping_type_id=4 ");
            DB::select(" UPDATE db_orders SET delivery_location=$delivery_location , delivery_province_id=$province_id , shipping_price='".$shipping[0]->shipping_cost."', shipping_free=0 ,shipping_special=1,transfer_price=(transfer_price+".$shipping[0]->shipping_cost.") WHERE id=$frontstore_id ");


        }else{


            DB::select(" UPDATE db_orders SET shipping_special=0  WHERE id=$frontstore_id ");

            // กรณีส่งฟรี
            $shipping = DB::select(" SELECT * FROM dataset_shipping_cost WHERE business_location_id_fk='".$frontstore[0]->business_location_id_fk."' AND shipping_type_id=1 ");

            if($sum_price>=$shipping[0]->purchase_amt){

                DB::select(" UPDATE db_orders SET delivery_location=$delivery_location , delivery_province_id=$province_id , shipping_price=0, shipping_free=1 WHERE id=$frontstore_id ");
            }else{

                DB::select(" UPDATE db_orders SET shipping_price=0,shipping_free=0  WHERE id=$frontstore_id ");

                 if($delivery_location==0 || $delivery_location==4){ //รับสินค้าด้วยตัวเอง / จัดส่งพร้อมบิลอื่น

                }else{

                        $branchs = DB::select("SELECT * FROM branchs WHERE id=".$request->branch_id_fk." ");

                        if($province_id==$branchs[0]->province_id_fk){
                            DB::select(" UPDATE db_orders SET delivery_location=$delivery_location ,delivery_province_id=$province_id ,shipping_price=0  WHERE id=$frontstore_id ");
                        }else{
                             // ต่าง จ. กัน เช็คดูว่า อยู่ในเขตปริมณทฑลหรือไม่
                            $shipping_cost = DB::select("SELECT * FROM dataset_shipping_cost where business_location_id_fk =".$branchs[0]->business_location_id_fk." AND shipping_type_id=2  ");

                            $shipping_vicinity = DB::select("SELECT * FROM dataset_shipping_vicinity where business_location_id_fk =".$branchs[0]->business_location_id_fk." AND shipping_type_id =".$shipping_cost[0]->shipping_type_id." AND province_id_fk=$province_id ");

                            if(count($shipping_vicinity)>0){

                                DB::select(" UPDATE db_orders SET delivery_location=$delivery_location ,delivery_province_id=$province_id ,shipping_price=".$shipping_cost[0]->shipping_cost."  WHERE id=$frontstore_id ");


                            }else{

                                $shipping_cost = DB::select("SELECT * FROM dataset_shipping_cost where business_location_id_fk =".$branchs[0]->business_location_id_fk." AND shipping_type_id=3 ");

                                DB::select(" UPDATE db_orders SET delivery_location=$delivery_location ,delivery_province_id=$province_id ,shipping_price=".$shipping_cost[0]->shipping_cost."  WHERE id=$frontstore_id ");


                            }

                        }

                }

            }

        }


         // if(กรณีเงินโอน ได้แก่ 1,8,10,11,12)

        if(@$request->pay_type_id_fk){
            if($request->pay_type_id_fk==10){

                DB::select(" UPDATE db_orders SET cash_pay=(sum_price-transfer_price)+shipping_price WHERE id=$request->frontstore_id ");

            }
         }

          $frontstore = DB::select(" SELECT * FROM db_orders WHERE id=$frontstore_id ");

          return   @$frontstore[0]->shipping_price;

}else{

        /*
            1   ส่งฟรี / Shipping Free
            2   กรุงเทพฯ และปริมณฑล / Metropolitan area
            3   ต่างจังหวัด / Upcountry
            4   ส่งแบบพิเศษ / Premium  > $request->delivery_location_05
        */
        if(!empty($request->province_id)){
            $province_id = $request->province_id;
        }else{
            $province_id = 0 ;
        }

// return $request->shipping_special;

        if(!empty($request->shipping_special)){

            $frontstore = DB::select(" SELECT * FROM db_orders WHERE id=$frontstore_id ");
            if($frontstore){

            // return $province_id;
            // return $frontstore;
            // return $frontstore[0]->business_location_id_fk;
            // dd();

            if(!empty($frontstore[0]->business_location_id_fk) && $frontstore[0]->business_location_id_fk>0){
                @$business_location_id_fk = @$frontstore[0]->business_location_id_fk?@$frontstore[0]->business_location_id_fk:@\Auth::user()->business_location_id_fk;
                // return @$business_location_id_fk;
                $shipping = DB::select(" SELECT * FROM dataset_shipping_cost WHERE business_location_id_fk='".@$business_location_id_fk."' AND shipping_type_id=4 ");
                // return $shipping;
                if($shipping){
                     DB::select(" UPDATE db_orders SET delivery_location=@$delivery_location , delivery_province_id=@$province_id , shipping_price='".@$shipping[0]->shipping_cost."', shipping_free=0 ,shipping_special=1,transfer_price=(transfer_price+".@$shipping[0]->shipping_cost.") WHERE id=$frontstore_id ");

                     return @$shipping[0]->shipping_cost;
                     // exit;
                }else{
                    return 0;
                }

                // dd();
            }else{
                return 0;
                // dd();
            }

        }else{
            return 0;
        }

            // dd();

        }else{

            DB::select(" UPDATE db_orders SET shipping_special=0  WHERE id=$frontstore_id ");

            // return $province_id;
            // dd();

            // กรณีส่งฟรี
            $shipping = DB::select(" SELECT * FROM dataset_shipping_cost WHERE business_location_id_fk='".$frontstore[0]->business_location_id_fk."' AND shipping_type_id=1 ");

            if($sum_price>=$shipping[0]->purchase_amt){
                // dd($sum_price);
                DB::select(" UPDATE db_orders SET delivery_location=$delivery_location , delivery_province_id=$province_id , shipping_price=0, shipping_free=1 WHERE id=$frontstore_id ");
                return 0 ;
            }else{

                DB::select(" UPDATE db_orders SET shipping_price=0,shipping_free=0  WHERE id=$frontstore_id ");

                 if($delivery_location==0 || $delivery_location==4){ //รับสินค้าด้วยตัวเอง / จัดส่งพร้อมบิลอื่น
                    // รับสินค้าด้วยตัวเอง จะรับที่สาขาใด ก็ไม่มีค่าใช้จ่าย
                    // return $request->branch_id_fk;
                    // return $request->branch_id_fk;
                    // dd();
                    return 0 ;
                    // dd();
                }else{

                        $branchs = DB::select("SELECT * FROM branchs WHERE id=".$request->branch_id_fk." ");

                        if($province_id==$branchs[0]->province_id_fk){
                            DB::select(" UPDATE db_orders SET delivery_location=$delivery_location ,delivery_province_id=$province_id ,shipping_price=0  WHERE id=$frontstore_id ");
                            return 0;
                        }else{
                             // ต่าง จ. กัน เช็คดูว่า อยู่ในเขตปริมณทฑลหรือไม่
                            $shipping_cost = DB::select("SELECT * FROM dataset_shipping_cost where business_location_id_fk =".$branchs[0]->business_location_id_fk." AND shipping_type_id=2  ");

                            $shipping_vicinity = DB::select("SELECT * FROM dataset_shipping_vicinity where business_location_id_fk =".$branchs[0]->business_location_id_fk." AND shipping_type_id =".$shipping_cost[0]->shipping_type_id." AND province_id_fk=$province_id ");

                            if(count($shipping_vicinity)>0){

                                DB::select(" UPDATE db_orders SET delivery_location=$delivery_location ,delivery_province_id=$province_id ,shipping_price=".$shipping_cost[0]->shipping_cost."  WHERE id=$frontstore_id ");

                                return $shipping_cost[0]->shipping_cost;

                            }else{

                                $shipping_cost = DB::select("SELECT * FROM dataset_shipping_cost where business_location_id_fk =".$branchs[0]->business_location_id_fk." AND shipping_type_id=3 ");

                                DB::select(" UPDATE db_orders SET delivery_location=$delivery_location ,delivery_province_id=$province_id ,shipping_price=".$shipping_cost[0]->shipping_cost."  WHERE id=$frontstore_id ");

                                return $shipping_cost[0]->shipping_cost;

                            }

                        }

                }

            }

        }


}

/*

1 เงินโอน
2 บัตรเครดิต
3 Ai-Cash
4 Gift Voucher
5 เงินสด
6 เงินสด + Ai-Cash
7 เครดิต + เงินสด
8 เครดิต + เงินโอน
9 เครดิต + Ai-Cash
10  เงินโอน + เงินสด
11  เงินโอน + Ai-Cash
12  Gift Voucher + เงินโอน
13  Gift Voucher + บัตรเครดิต
14  Gift Voucher + Ai-Cash
15  PromptPay
16  TrueMoney
17  Gift Voucher + PromptPay
18  Gift Voucher + TrueMoney

กรณีเงินโอน ได้แก่ 1,8,10,11,12

*/



    }

   public function ajaxClearAfterAddAiCash(Request $request)
    {
        // return $request;

        DB::select(" UPDATE db_orders SET
            aicash_price='0',
            member_id_aicash='0',
            transfer_price='0',
            credit_price='0',
            shipping_price='0',

            charger_type='0',
            fee='0',
            fee_amt='0',
            sum_credit_price='0',

            total_price='0',
            cash_price='0',
            cash_pay='0'
            WHERE id=".$request->frontstore_id_fk." ");

        // $rs = DB::select(" SELECT * FROM db_orders WHERE id=".$request->frontstore_id_fk." ");
        // return response()->json($rs);

    }


   public function ajaxFeeCalculate(Request $request)
    {


     /*
        $pay_type_id_fk
          1 เงินสด
          2 เงินสด + Ai-Cash
          3 เครดิต + เงินสด
          4 เครดิต + เงินโอน
          5 เครดิต + Ai-Cash
          6 เงินโอน + เงินสด
          7 เงินโอน + Ai-Cash

1	0	เงินโอน
2	0	บัตรเครดิต
3	0	Ai-Cash
4	0	Gift Voucher

5	1	เงินสด
6	2	เงินสด + Ai-Cash
7	3	เครดิต + เงินสด
8	4	เครดิต + เงินโอน
9	5	เครดิต + Ai-Cash
10	6	เงินโอน + เงินสด
11	7	เงินโอน + Ai-Cash



          */

        // return $request;
        // dd();
        $pay_type_id_fk = $request->pay_type_id_fk;
        $frontstore_id =  $request->frontstore_id ;
        $aicash_price = str_replace(',','',$request->aicash_price);
        $sum_price = str_replace(',','',$request->sum_price);
        $shipping_price = str_replace(',','',$request->shipping_price);
        $sum_price = ($sum_price+$shipping_price) ;


        DB::select(" UPDATE db_orders SET
            aicash_price='0',
            member_id_aicash='0',
            transfer_price='0',
            credit_price='0',
            shipping_price='0',

            charger_type='0',
            fee='0',
            fee_amt='0',
            sum_credit_price='0',

            total_price='0',
            cash_price='0',
            cash_pay='0'
            WHERE id=$frontstore_id ");

        if($pay_type_id_fk==5){
            DB::select(" UPDATE db_orders SET cash_price=($sum_price-$shipping_price),cash_pay=$sum_price WHERE id=$frontstore_id ");
        }

        if($pay_type_id_fk==6){
            $aicash_price = $aicash_price>$sum_price?$sum_price:$aicash_price;
            DB::select(" UPDATE db_orders SET aicash_price=$aicash_price,cash_price=($sum_price-$aicash_price),cash_pay=$sum_price WHERE id=$frontstore_id ");
        }

        if($pay_type_id_fk == '') {
            DB::select(" UPDATE db_orders SET pay_type_id_fk=0,cash_price=0,cash_pay=0 WHERE id=$frontstore_id ");
        }

        $rs = DB::select(" SELECT * FROM db_orders WHERE id=$frontstore_id ");
        return response()->json($rs);

    }


  public function ajaxCalPriceFrontstore01(Request $request)
    {
        // return $request;
        // dd();
     /*
        $pay_type_id_fk
          1 เงินสด
          2 เงินสด + Ai-Cash
          3 เครดิต + เงินสด
          4 เครดิต + เงินโอน
          5 เครดิต + Ai-Cash
          6 เงินโอน + เงินสด
          7 เงินโอน + Ai-Cash
          */


        $sum_price = str_replace(',','',$request->sum_price);
        $pay_type_id_fk = $request->pay_type_id_fk?$request->pay_type_id_fk:0;
        $frontstore_id =  $request->frontstore_id ;
        $shipping_price = str_replace(',','',$request->shipping_price);
        $shipping_price = $shipping_price>0?$shipping_price:0;

        $sum_price = ($sum_price+$shipping_price) ;

        if($request->purchase_type_id_fk==5){
            if($pay_type_id_fk==19){
                $gift_voucher_cost = str_replace(',','',$request->gift_voucher_cost);   // ที่มีอยู่
                $gift_voucher_price = str_replace(',','',$request->gift_voucher_price); // ที่กรอก
                if($gift_voucher_price==''){
                    $gift_voucher_price = 0;
                }
            }else{
                $gift_voucher_cost = str_replace(',','',$request->gift_voucher_cost);   // ที่มีอยู่
                $gift_voucher_price = str_replace(',','',$request->gift_voucher_price); // ที่กรอก
                $gift_voucher_price = $gift_voucher_price>$gift_voucher_cost?$gift_voucher_cost:$gift_voucher_price;
                $gift_voucher_price = $gift_voucher_price>$sum_price?$sum_price:$gift_voucher_price;
                $sum_price = $sum_price - $gift_voucher_price ;
            }


        }


        // DB::select(" UPDATE db_orders SET

        //     aicash_price='0',
        //     member_id_aicash='0',
        //     transfer_price='0',
        //     credit_price='0',

        //     charger_type='0',
        //     fee='0',
        //     fee_amt='0',
        //     sum_credit_price='0',
        //     account_bank_id='0',

        //     transfer_money_datetime=NULL ,
        //     file_slip=NULL,

        //     total_price='0',
        //     cash_price='0',
        //     cash_pay='0'
        //     WHERE id=$frontstore_id ");


        DB::select(" UPDATE db_orders SET pay_type_id_fk=($pay_type_id_fk) WHERE id=$frontstore_id ");

        if($pay_type_id_fk==5){
            DB::select(" UPDATE db_orders SET cash_price=($sum_price-$shipping_price),cash_pay=($sum_price),total_price=($sum_price) WHERE id=$frontstore_id ");
        }

        if($pay_type_id_fk==4){
            DB::select(" UPDATE db_orders SET gift_voucher_price=($sum_price) WHERE id=$frontstore_id ");
        }

        // Gift Voucher + เงินสด
        if($pay_type_id_fk==19){
            DB::select(" UPDATE db_orders SET gift_voucher_price=($gift_voucher_price) WHERE id=$frontstore_id ");
            DB::select(" UPDATE db_orders SET cash_price=($sum_price-$shipping_price-$gift_voucher_price),cash_pay=($sum_price-$gift_voucher_price),total_price=($sum_price-$gift_voucher_price) WHERE id=$frontstore_id ");
        }

         // กรณีส่งฟรี
        $sFrontstore = \App\Models\Backend\Frontstore::find($frontstore_id);
        $shipping = DB::select(" SELECT * FROM dataset_shipping_cost WHERE business_location_id_fk='".$sFrontstore->business_location_id_fk."' AND shipping_type_id=1 ");

        if($sum_price>=$shipping[0]->purchase_amt){
            DB::select(" UPDATE db_orders SET  shipping_price=0, shipping_free=1 WHERE id=$frontstore_id ");
            $shipping_price = 0 ;
        }


        $rs = DB::select(" SELECT * FROM db_orders WHERE id=$frontstore_id ");
        return response()->json($rs);

    }

    public function ajaxClearCostFrontstore(Request $request)
    {
          // $frontstore_id_fk =  $request->frontstore_id_fk ;

          // DB::select(" UPDATE db_orders SET

          //   aicash_price='0',
          //   member_id_aicash='0',
          //   transfer_price='0',
          //   credit_price='0',

          //   charger_type='0',
          //   fee='0',
          //   fee_amt='0',
          //   sum_credit_price='0',
          //   account_bank_id='0',

          //   transfer_money_datetime=NULL ,
          //   file_slip=NULL,

          //   total_price='0',
          //   cash_price='0',
          //   cash_pay='0'
          //   WHERE id=$frontstore_id_fk ");
        return null;
    }



    public function ajaxClearPayTypeFrontstore(Request $request)
    {

        // return ($request);
        // dd();

          $frontstore_id_fk =  $request->frontstore_id_fk ;
          $pay_type_id_fk =  $request->pay_type_id_fk>0?$request->pay_type_id_fk:0 ;

          DB::select(" UPDATE db_orders SET

            pay_type_id_fk='$pay_type_id_fk',

            aicash_price='0',
            member_id_aicash='0',
            transfer_price='0',
            credit_price='0',

            charger_type='0',
            fee='0',
            fee_amt='0',
            sum_credit_price='0',
            account_bank_id='0',

            transfer_money_datetime=NULL ,
            file_slip=NULL,

            total_price='0',
            cash_price='0',
            cash_pay='0',

            `approve_status`='0'

            WHERE id=$frontstore_id_fk ");

            DB::select(" UPDATE db_orders SET

            `check_press_save`='0'

            WHERE id=$frontstore_id_fk AND check_press_save<>2 ");

          // UPDATE `db_orders` SET `check_press_save`='0' WHERE (`id`='5')

          // เช็คว่าเป็นการซื้อด้วย Aicash หรือไม่ ถ้าซื้อ ให้ประมวลผลตาม introduce_id
// 6 เงินสด + Ai-Cash
// 9 เครดิต + Ai-Cash
// 11  เงินโอน + Ai-Cash

          if(!empty($request->pay_type_id_fk)){

            // return @$request->pay_type_id_fk;
            // dd();

                  if(@$request->pay_type_id_fk==6 || @$request->pay_type_id_fk==9 || @$request->pay_type_id_fk==11 ){

                    // DB::select("  TRUNCATE temp_cus_ai_cash ; ");
                    DB::select("  DELETE FROM temp_cus_ai_cash where admin_id_login ='".\Auth::user()->id."'; ");
                    DB::select("  ALTER TABLE temp_cus_ai_cash  AUTO_INCREMENT=1; ");

                    // return @$request->user_name;

                    if(@$request->user_name){
                        $this->fnSearchIntroduceId($request->user_name);
                    }

                  }
          }


    }



    public function ajaxForCheck_press_save(Request $request)
    {
          // return $request->frontstore_id_fk;
        if(!empty($request->frontstore_id_fk)){
            DB::select(" UPDATE db_orders set check_press_save='1' where product_value>0 and check_press_save<>'2' and id=".$request->frontstore_id_fk."
             ");
        }

          // return response()->json($rs);
    }


   public function ajaxCalPriceFrontstore02(Request $request)
    {
        // return $request;
        // dd();
     /*
        $pay_type_id_fk
5   เงินสด
6   เงินสด + Ai-Cash
7   เครดิต + เงินสด
8   เครดิต + เงินโอน
9   เครดิต + Ai-Cash
10  เงินโอน + เงินสด
11  เงินโอน + Ai-Cash

          */
          // return $request->pay_type_id_fk;
          // dd();

        $pay_type_id_fk = $request->pay_type_id_fk;
        $frontstore_id =  $request->frontstore_id ;
        $sum_price = str_replace(',','',$request->sum_price);
        $shipping_price = str_replace(',','',$request->shipping_price);


         // กรณีส่งฟรี
        $sFrontstore = \App\Models\Backend\Frontstore::find($frontstore_id);
        $shipping = DB::select(" SELECT * FROM dataset_shipping_cost WHERE business_location_id_fk='".$sFrontstore->business_location_id_fk."' AND shipping_type_id=1 ");

        if($sum_price>=$shipping[0]->purchase_amt){
            DB::select(" UPDATE db_orders SET  shipping_price=0, shipping_free=1 WHERE id=$frontstore_id ");
            $shipping_price = 0 ;
        }

        $sum_price = ($sum_price+$shipping_price) ;


        if($request->purchase_type_id_fk==5){

            $gift_voucher_cost = str_replace(',','',$request->gift_voucher_cost);   // ที่มีอยู่
            $gift_voucher_price = str_replace(',','',$request->gift_voucher_price); // ที่กรอก
            $gift_voucher_price = $gift_voucher_price>$gift_voucher_cost?$gift_voucher_cost:$gift_voucher_price;
            $gift_voucher_price = $gift_voucher_price>$sum_price?$sum_price:$gift_voucher_price;

            $sum_price = $sum_price - $gift_voucher_price ;

        }


        if($pay_type_id_fk==5){
            DB::select(" UPDATE db_orders SET member_id_aicash='0',aicash_price='0',cash_price=($sum_price-$shipping_price),cash_pay=($sum_price),total_price=($sum_price) WHERE id=$frontstore_id ");
        }

        if($pay_type_id_fk==6){
            $aicash_price = str_replace(',','',@$request->aicash_price);
            $aicash_remain = str_replace(',','',@$request->aicash_remain);
            if(!empty($aicash_remain)){
                $aicash_price = $aicash_remain ;
                $aicash_price = is_numeric($aicash_price) > is_numeric($sum_price) ? is_numeric($sum_price) : is_numeric($aicash_price) ;
            }else{
                $aicash_price = is_numeric($aicash_price) > is_numeric($sum_price) ? is_numeric($sum_price) : is_numeric($aicash_price) ;
            }

            $cash_pay = is_numeric(@$sum_price) - is_numeric($aicash_price) ;
            $member_id_aicash = !empty(@$request->member_id_aicash)?@$request->member_id_aicash:0;

            DB::select(" UPDATE db_orders SET member_id_aicash=".$member_id_aicash.",aicash_price=$aicash_price, cash_price=$cash_pay, cash_pay=$cash_pay,total_price=($sum_price) WHERE id=$frontstore_id ");
        }

        if($pay_type_id_fk==7){

            if(empty($request->credit_price)){
                exit;
            }

            $credit_price = str_replace(',','',$request->credit_price);
            $credit_price = $credit_price>$sum_price?$sum_price:$credit_price;
            DB::select(" UPDATE db_orders SET credit_price=$credit_price WHERE id=$frontstore_id ");

            if(!empty($request->fee)){
                $fee = DB::select(" SELECT * from dataset_fee where id =".$request->fee." ");
                $fee_type = $fee[0]->fee_type;
                if($fee_type==1){
                    $fee = $fee[0]->txt_value;
                    $fee_amt    = $credit_price * (@$fee/100) ;
                    $sum_credit_price = $credit_price + $fee_amt ;
                }else{
                    $fee = $fee[0]->txt_fixed_rate;
                    $fee_amt =  $fee ;
                    $sum_credit_price = $credit_price + $fee_amt ;
                }
           }else{
              $fee_id = 0 ;
              $fee_amt  = 0 ;
              $sum_credit_price  = 0 ;
           }

          if(!empty($credit_price) && intval($credit_price) != 0){

                $fee_id = $request->fee?$request->fee:0;
                $charger_type = $request->charger_type;

                if($charger_type==1){

                    if($credit_price==$sum_price){
                        DB::select(" UPDATE db_orders SET credit_price=$credit_price, fee=$fee_id,fee_amt=$fee_amt,sum_credit_price=$sum_credit_price,cash_price=0,cash_pay=0 WHERE id=$frontstore_id ");
                    }else{
                        DB::select(" UPDATE db_orders SET credit_price=$credit_price, fee=$fee_id,fee_amt=$fee_amt,sum_credit_price=$sum_credit_price,cash_price=($sum_price-$credit_price),cash_pay=($sum_price-$credit_price) WHERE id=$frontstore_id ");
                    }

                }else{

                    DB::select(" UPDATE db_orders SET charger_type=$charger_type,credit_price=$credit_price, fee=$fee_id,fee_amt=$fee_amt,sum_credit_price=$credit_price,cash_price=($sum_price-$credit_price),cash_pay=(($sum_price-$credit_price)+$fee_amt) WHERE id=$frontstore_id ");

                }

            }

        }

     if($pay_type_id_fk==8){

            if(empty($request->credit_price)){
                exit;
            }

            $credit_price = str_replace(',','',$request->credit_price);
            $transfer_price = str_replace(',','',$request->transfer_price);
            $credit_price = $credit_price>$sum_price?$sum_price:$credit_price;
            $transfer_price = $transfer_price>$sum_price?$sum_price:$transfer_price;
            DB::select(" UPDATE db_orders SET credit_price=$credit_price WHERE id=$frontstore_id ");

            if(!empty($request->fee)){
                $fee = DB::select(" SELECT * from dataset_fee where id =".$request->fee." ");
                $fee_type = $fee[0]->fee_type;
                if($fee_type==1){
                    $fee = $fee[0]->txt_value;
                    $fee_amt    = $credit_price * (@$fee/100) ;
                    $sum_credit_price = $credit_price + $fee_amt ;
                }else{
                    $fee = $fee[0]->txt_fixed_rate;
                    $fee_amt =  $fee ;
                    $sum_credit_price = $credit_price + $fee_amt ;
                }
           }else{
              $fee_id = 0 ;
              $fee_amt  = 0 ;
              $sum_credit_price  = 0 ;
           }

          if(!empty($credit_price) && intval($credit_price) != 0){

                $fee_id = $request->fee?$request->fee:0;
                $charger_type = $request->charger_type;
                $transfer_money_datetime = $request->transfer_money_datetime;

                if($charger_type==1){

                    if($credit_price==$sum_price){
                        DB::select(" UPDATE db_orders SET credit_price=$credit_price, fee=$fee_id,fee_amt=$fee_amt,sum_credit_price=$sum_credit_price,cash_price=0,cash_pay=0 WHERE id=$frontstore_id ");
                    }else{
                        DB::select(" UPDATE db_orders SET credit_price=$credit_price, fee=$fee_id,fee_amt=$fee_amt,sum_credit_price=$sum_credit_price,transfer_price=($sum_price-$credit_price),transfer_money_datetime='$transfer_money_datetime',cash_price=0,cash_pay=0 WHERE id=$frontstore_id ");
                    }

                }else{

                    DB::select(" UPDATE db_orders SET charger_type=$charger_type,credit_price=$credit_price, fee=$fee_id,fee_amt=$fee_amt,sum_credit_price=$credit_price,transfer_price=(($sum_price-$credit_price)+$fee_amt),transfer_money_datetime='$transfer_money_datetime',cash_price=0,cash_pay=0 WHERE id=$frontstore_id ");

                }

            }

        }


   if($pay_type_id_fk==9){

           // return response()->json($pay_type_id_fk);

            if(empty($request->credit_price)){
                exit;
            }

            // return response()->json($request->credit_price);

            $credit_price = str_replace(',','',$request->credit_price);
            $credit_price = $credit_price>$sum_price?$sum_price:$credit_price;

            // return response()->json($credit_price);


            DB::select(" UPDATE db_orders SET credit_price=$credit_price,file_slip=NULL WHERE id=$frontstore_id ");

            if(!empty($request->fee)){
                $fee = DB::select(" SELECT * from dataset_fee where id =".$request->fee." ");
                $fee_type = $fee[0]->fee_type;
                if($fee_type==1){
                    $fee = $fee[0]->txt_value;
                    $fee_amt    = $credit_price * (@$fee/100) ;
                    $sum_credit_price = $credit_price + $fee_amt ;
                }else{
                    $fee = $fee[0]->txt_fixed_rate;
                    $fee_amt =  $fee ;
                    $sum_credit_price = $credit_price + $fee_amt ;
                }
           }else{
              $fee_id = 0 ;
              $fee_amt  = 0 ;
              $sum_credit_price  = 0 ;
           }

          if(!empty($credit_price) && intval($credit_price) != 0){

                $fee_id = $request->fee?$request->fee:0;
                $charger_type = $request->charger_type;

            // return response()->json($fee_id);
            // return response()->json($charger_type);

                if($charger_type==1){

                    if($credit_price==$sum_price){

                        // return response()->json('to this');

                        DB::select(" UPDATE db_orders SET credit_price=$credit_price, fee=$fee_id,fee_amt=$fee_amt,sum_credit_price=$sum_credit_price,cash_price=0,cash_pay=0,aicash_price=0 WHERE id=$frontstore_id ");

                    }else{

                        if($credit_price<$sum_price){

                                   // ถ้ายอดเครดิต ลบ ราคาสินค้ารวม แล้ว มากกว่า ยอด ai-cash ที่มี ให้ ยอด ai-cash = ยอด ai-cash ที่มี แล้ว ส่วนเอาที่เหลือ เอาไปรวมกับยอด เครดิต อีกรอบ

                            // ยังไม่ได้เลือก icash เลย โฟกัสที่ เครดิต ก่อน
                            // return response()->json('to this');

                                    DB::select(" UPDATE db_orders SET credit_price=$credit_price, fee=$fee_id,fee_amt=$fee_amt,sum_credit_price=$sum_credit_price,cash_price=0,cash_pay=0 WHERE id=$frontstore_id ");

                        }else{

                            // จะไม่เข้าเคสนี้

                                   // ถ้ายอดเครดิต ลบ ราคาสินค้ารวม แล้ว มากกว่า ยอด ai-cash ที่มี ให้ ยอด ai-cash = ยอด ai-cash ที่มี แล้ว ส่วนเอาที่เหลือ เอาไปรวมกับยอด เครดิต อีกรอบ
                                    // $sCustomer = DB::select(" select * from customers where id=".$request->customers_id_fk." ");
                                    // $Cus_Aicash = $sCustomer[0]->ai_cash;

                                    // $AiCashInput =  $sum_price - $credit_price ;
                                    // if($AiCashInput > $Cus_Aicash){
                                    //     $AiCash = $Cus_Aicash;
                                    //     $credit_price = $sum_price - $AiCash ;
                                    // }else{
                                    //     $AiCash = $AiCashInput ;
                                    //     $credit_price = $credit_price ;
                                    // }

                                    // $member_id_aicash = !empty(@$request->member_id_aicash)?@$request->member_id_aicash:0;

                                    // DB::select(" UPDATE db_orders SET credit_price=$credit_price, fee=$fee_id,fee_amt=$fee_amt,sum_credit_price=$sum_credit_price,member_id_aicash=".$member_id_aicash.",aicash_price=($AiCash),cash_price=0,cash_pay=0 WHERE id=$frontstore_id ");

                        }


                    }

                }else{

                    // ยังไม่ได้เลือก ไอแคช เลย

                        $sCustomer = DB::select(" select * from customers where id=".$request->customers_id_fk." ");
                        $Cus_Aicash = $sCustomer[0]->ai_cash;

                        $AiCashInput =  ($sum_price - $credit_price) + $fee_amt ;

                        if($AiCashInput > $Cus_Aicash){
                            $AiCash = $Cus_Aicash;
                            $credit_price = ($sum_price - $AiCash) + $fee_amt  ;
                        }else{
                            $AiCash = $AiCashInput  ;
                            $credit_price = $credit_price + $fee_amt ;
                        }


                        if($credit_price==$sum_price){
                            $member_id_aicash = !empty(@$request->member_id_aicash)?@$request->member_id_aicash:0;
                            DB::select(" UPDATE db_orders SET credit_price=$credit_price, fee=$fee_id,fee_amt=$fee_amt,sum_credit_price=$sum_credit_price,cash_price=0,cash_pay=0,member_id_aicash=".$member_id_aicash.",aicash_price=$fee_amt WHERE id=$frontstore_id ");
                        }else{
                           $member_id_aicash = !empty(@$request->member_id_aicash)?@$request->member_id_aicash:0;
                           DB::select(" UPDATE db_orders SET charger_type=$charger_type,credit_price=$credit_price, fee=$fee_id,fee_amt=$fee_amt,sum_credit_price=$credit_price,member_id_aicash=".$member_id_aicash.",aicash_price=($AiCash),cash_price=0,cash_pay=0 WHERE id=$frontstore_id ");

                        }

                }



            }

        }


        if($pay_type_id_fk==10){

                    if(empty($request->transfer_price)){
                        exit;
                    }

                    $transfer_price = str_replace(',','',$request->transfer_price);
                    $transfer_price = $transfer_price>$sum_price?$sum_price:$transfer_price;
                    $transfer_money_datetime = $request->transfer_money_datetime;

                    DB::select(" UPDATE db_orders SET transfer_price=$transfer_price,cash_price=($sum_price-$transfer_price),cash_pay=($sum_price-$transfer_price),transfer_money_datetime='$transfer_money_datetime' WHERE id=$frontstore_id ");


         }
             // 12 Gift Voucher + เงินโอน
             if($pay_type_id_fk==12){

                if(empty($request->transfer_price)){
                    exit;
                }

                $transfer_price = str_replace(',','',$request->transfer_price);
                $gift_voucher_price = str_replace(',','',$request->gift_voucher_price);
                $transfer_price = $transfer_price>$sum_price?$sum_price:$transfer_price;
                $transfer_money_datetime = $request->transfer_money_datetime;

                $shipping_price = 0;
                if($request->shipping_price!=null){
                    $shipping_price = str_replace(',','',$request->shipping_price);
                }
                $fee_amt = str_replace(',','',$request->fee_amt);
                $sum_price = str_replace(',','',$request->sum_price);
                $sum_total = $fee_amt+$sum_price+$shipping_price;
                $transfer_price = $sum_total-$gift_voucher_price;

                DB::select(" UPDATE db_orders SET transfer_price=$transfer_price,cash_price=($sum_price-$transfer_price),cash_pay=($sum_price-$transfer_price),transfer_money_datetime='$transfer_money_datetime' WHERE id=$frontstore_id ");


     }
    // Gift Voucher + บัตรเครดิต
     if($pay_type_id_fk==13){

        if(empty($request->credit_price)){
            exit;
        }

        $credit_price = str_replace(',','',$request->credit_price);
        $gift_voucher_price = str_replace(',','',$request->gift_voucher_price);
        $gift_voucher_cost = str_replace(',','',$request->gift_voucher_cost);
        // $credit_price = $credit_price>$sum_price?$sum_price:$credit_price;
        if($credit_price>$sum_price){
            $credit_price = $credit_price;
        }else{
            $credit_price = $sum_price;
        }
        // gift_voucher_price
        DB::select(" UPDATE db_orders SET credit_price=$credit_price WHERE id=$frontstore_id ");

        if(!empty($request->fee)){
            $fee = DB::select(" SELECT * from dataset_fee where id =".$request->fee." ");
            $fee_type = $fee[0]->fee_type;
            if($fee_type==1){
                $fee = $fee[0]->txt_value;
                $fee_amt    = $credit_price * (@$fee/100) ;
                $sum_credit_price = $credit_price + $fee_amt ;
            }else{
                $fee = $fee[0]->txt_fixed_rate;
                $fee_amt =  $fee ;
                $sum_credit_price = $credit_price + $fee_amt ;
            }
       }else{
          $fee_id = 0 ;
          $fee_amt  = 0 ;
          $sum_credit_price  = 0 ;
       }

      if(!empty($credit_price) && intval($credit_price) != 0){

            $fee_id = $request->fee?$request->fee:0;
            $charger_type = $request->charger_type;

    // dd($sum_price);
            if($charger_type==1){
                // dd('ok2');
                if($credit_price==$sum_price){
                    DB::select(" UPDATE db_orders SET credit_price=$credit_price, fee=$fee_id,fee_amt=$fee_amt,sum_credit_price=$sum_credit_price,gift_voucher_cost=$gift_voucher_cost,gift_voucher_price=0 WHERE id=$frontstore_id ");
                }else{
                    DB::select(" UPDATE db_orders SET credit_price=$credit_price, fee=$fee_id,fee_amt=$fee_amt,sum_credit_price=$sum_credit_price,gift_voucher_cost=($gift_voucher_cost),gift_voucher_price=($gift_voucher_price) WHERE id=$frontstore_id ");
                    // DB::select(" UPDATE db_orders SET credit_price=$credit_price, fee=$fee_id,fee_amt=$fee_amt,sum_credit_price=$sum_credit_price,cash_price=($sum_price-$credit_price),cash_pay=($sum_price-$credit_price) WHERE id=$frontstore_id ");
                }

            }else{
                // dd('ok1');
                DB::select(" UPDATE db_orders SET charger_type=$charger_type,credit_price=$credit_price, fee=$fee_id,fee_amt=$fee_amt,sum_credit_price=$credit_price+$fee_amt,gift_voucher_cost=($gift_voucher_cost),gift_voucher_price=($gift_voucher_price) WHERE id=$frontstore_id ");

            }

        }

    }


        if($pay_type_id_fk==11){

                    if(empty($request->transfer_price)){
                        exit;
                    }

                    $transfer_price = str_replace(',','',$request->transfer_price);
                    $transfer_price = $transfer_price>$sum_price?$sum_price:$transfer_price;
                    $transfer_money_datetime = $request->transfer_money_datetime;
                    $member_id_aicash = !empty(@$request->member_id_aicash)?@$request->member_id_aicash:0;
                    DB::select(" UPDATE db_orders SET transfer_price=$transfer_price,aicash_price=($sum_price-$transfer_price),transfer_money_datetime='$transfer_money_datetime',member_id_aicash=".$member_id_aicash.",cash_price=0,cash_pay=0 WHERE id=$frontstore_id ");

         }

        if(!empty($request->this_element)){
            if($request->this_element=="aicash_price"){

                $sCustomer = DB::select(" select * from customers where id=".$request->member_id_aicash." ");
                $Cus_Aicash = $sCustomer[0]->ai_cash;

                $aicash_price = str_replace(',','',$request->aicash_price);
                $aicash_remain = str_replace(',','',@$request->aicash_remain);
                if(!empty($aicash_remain)){
                    $aicash_price = $aicash_remain ;
                }else{
                    $aicash_price = $aicash_price ;
                }
                $aicash_price = $aicash_price>$Cus_Aicash?$Cus_Aicash:$aicash_price;
                $cash_price = $sum_price-$aicash_price;
                $cash_pay = $sum_price-$aicash_price;

                if($aicash_price>$sum_price){
                    $member_id_aicash = !empty(@$request->member_id_aicash)?@$request->member_id_aicash:0;
                    DB::select(" UPDATE db_orders SET member_id_aicash=".$member_id_aicash.",aicash_price=$sum_price, cash_price=0, cash_pay=0,total_price=($sum_price) WHERE id=$frontstore_id ");
                }else{
                    $member_id_aicash = !empty(@$request->member_id_aicash)?@$request->member_id_aicash:0;
                    DB::select(" UPDATE db_orders SET member_id_aicash=".$member_id_aicash.",aicash_price=$aicash_price, cash_price=$cash_pay, cash_pay=$cash_pay,total_price=($sum_price) WHERE id=$frontstore_id ");
                }


            }
        }


        $rs = DB::select(" SELECT * FROM db_orders WHERE id=$frontstore_id ");
        return response()->json($rs);

    }




   public function ajaxCalPriceFrontstore03(Request $request)
    {
        // return $request;
        // dd();

        $pay_type_id_fk = $request->pay_type_id_fk;
        $frontstore_id =  $request->frontstore_id ;
        $sum_price = str_replace(',','',$request->sum_price);
        $shipping_price = str_replace(',','',$request->shipping_price);
        $sum_price = ($sum_price+$shipping_price) ;

               // return response()->json($pay_type_id_fk);
       // return response()->json($frontstore_id);
       // return response()->json($sum_price);
       // return response()->json($shipping_price);

       // return response()->json($pay_type_id_fk);



        if($pay_type_id_fk==6){

            $aicash_price = str_replace(',','',@$request->aicash_price);
            // return response()->json($aicash_price);
            $aicash_remain = str_replace(',','',@$request->aicash_remain);
            // return response()->json($aicash_remain);

            if(!empty($aicash_remain)){
                $aicash_price = $aicash_remain ;
                $aicash_price = $aicash_price > $sum_price ? $sum_price : $aicash_price ;
            }else{
                $aicash_price = $aicash_price > $sum_price ? $sum_price : $aicash_price ;
            }

            // return response()->json($sum_price);
            // return response()->json($aicash_price);

            $cash_pay = @$sum_price - @$aicash_price ;
            // return response()->json($cash_pay);


             DB::select(" UPDATE db_orders SET member_id_aicash=".@$request->member_id_aicash.",aicash_price=".$aicash_price.", cash_price=".$cash_pay.", cash_pay=".$cash_pay.",total_price=(".$sum_price.") WHERE id=".$frontstore_id." ");

            // return response()->json($request->member_id_aicash);
            // return response()->json($aicash_price);
            // return response()->json($cash_pay);
            // return response()->json($sum_price);
            // return response()->json($frontstore_id);

        }


        // if(!empty($request->this_element)){
        //     if($request->this_element=="aicash_price"){

        //         $sCustomer = DB::select(" select * from customers where id=".$request->member_id_aicash." ");
        //         $Cus_Aicash = $sCustomer[0]->ai_cash;

        //         $aicash_price = str_replace(',','',$request->aicash_price);
        //         $aicash_remain = str_replace(',','',@$request->aicash_remain);
        //         if(!empty($aicash_remain)){
        //             $aicash_price = $aicash_remain ;
        //         }else{
        //             $aicash_price = $aicash_price ;
        //         }
        //         $aicash_price = $aicash_price>$Cus_Aicash?$Cus_Aicash:$aicash_price;
        //         $cash_price = $sum_price-$aicash_price;
        //         $cash_pay = $sum_price-$aicash_price;

        //         if($aicash_price>$sum_price){

        //             DB::select(" UPDATE db_orders SET member_id_aicash=".$request->member_id_aicash.",aicash_price=$sum_price, cash_price=0, cash_pay=0,total_price=($sum_price) WHERE id=$frontstore_id ");
        //         }else{
        //             DB::select(" UPDATE db_orders SET member_id_aicash=".$request->member_id_aicash.",aicash_price=$aicash_price, cash_price=$cash_pay, cash_pay=$cash_pay,total_price=($sum_price) WHERE id=$frontstore_id ");
        //         }


        //     }
        // }


        $rs = DB::select(" SELECT * FROM db_orders WHERE id=$frontstore_id ");
        return response()->json($rs);

    }


   public function ajaxCalPriceFrontstore04(Request $request)
    {
        // return $request;
        // dd();
        // return response()->json("to this");

        $pay_type_id_fk = $request->pay_type_id_fk;
        $frontstore_id =  $request->frontstore_id ;
        $sum_price = $request->sum_price?str_replace(',','',$request->sum_price):0;
        $shipping_price = $request->shipping_price?str_replace(',','',$request->shipping_price):0;
        $sum_price = ($sum_price+$shipping_price) ;

       // return response()->json($pay_type_id_fk);
       // return response()->json($frontstore_id);
       // return response()->json($sum_price);
       // return response()->json($shipping_price);

        if($pay_type_id_fk==6){

            $aicash_price = str_replace(',','',@$request->aicash_price);
            // return response()->json($aicash_price);
            $aicash_remain = str_replace(',','',@$request->aicash_remain);
            // return response()->json($aicash_remain);

            if(!empty($aicash_remain)){
                $aicash_price = $aicash_remain ;
                $aicash_price = $aicash_price > $sum_price ? $sum_price : $aicash_price ;
            }else{
                $aicash_price = $aicash_price > $sum_price ? $sum_price : $aicash_price ;
            }

            // return response()->json($sum_price);
            // return response()->json($aicash_price);

            $cash_pay = @$sum_price - @$aicash_price ;
            // return response()->json($cash_pay);


             DB::select(" UPDATE db_orders SET member_id_aicash=".@$request->member_id_aicash.",aicash_price=".$aicash_price.", cash_price=".$cash_pay.", cash_pay=".$cash_pay.",total_price=(".$sum_price.") WHERE id=".$frontstore_id." ");

            // return response()->json($request->member_id_aicash);
            // return response()->json($aicash_price);
            // return response()->json($cash_pay);
            // return response()->json($sum_price);
            // return response()->json($frontstore_id);

        }

      if($pay_type_id_fk==9){

            // sum_credit_price

            // return response()->json($request->sum_credit_price);
            // return response()->json($request->aicash_remain);
            // return response()->json($request->member_id_aicash);

            // $aicash_price = str_replace(',','',@$request->aicash_price);
            // return response()->json($aicash_price);
            $aicash_remain = str_replace(',','',@$request->aicash_remain);
            $sum_credit_price = str_replace(',','',@$request->sum_credit_price);
            // return response()->json($aicash_remain);

            // if(!empty($aicash_remain)){
            //     $aicash_price = $aicash_remain ;
            //     $aicash_price = $aicash_price > $sum_price ? $sum_price : $aicash_price ;
            // }else{
            //     $aicash_price = $aicash_price > $sum_price ? $sum_price : $aicash_price ;
            // }

            // return response()->json($sum_price);
            // return response()->json($aicash_price);

            $cash_pay = @$sum_price - @$sum_credit_price  ;
            // return response()->json($cash_pay);


             DB::select(" UPDATE db_orders SET member_id_aicash=".@$request->member_id_aicash.",aicash_price=".$cash_pay.", cash_price=".$cash_pay.", cash_pay=".$cash_pay.",total_price=(".$sum_price.") WHERE id=".$frontstore_id." ");

            // return response()->json($request->member_id_aicash);
            // return response()->json($aicash_price);
            // return response()->json($cash_pay);
            // return response()->json($sum_price);
            // return response()->json($frontstore_id);

        }


        $rs = DB::select(" SELECT * FROM db_orders WHERE id=".$frontstore_id." ");
        return response()->json($rs);

    }




   public function ajaxGetProductPromotionCus(Request $request)
    {
        // echo $request->product_id_fk;

          $Products = DB::select("
                SELECT products.id as product_id,
                  products.product_code,
                  (CASE WHEN products_details.product_name is null THEN '* ไม่ได้กรอกชื่อสินค้า' ELSE products_details.product_name END) as product_name ,
                  products_cost.member_price,
                  products_cost.pv
                  FROM
                  products_details
                  Left Join products ON products_details.product_id_fk = products.id
                  LEFT JOIN products_cost on products.id = products_cost.product_id_fk
                  WHERE lang_id=1 AND products.id= ".$request->product_id_fk."

           ");

            $pn = @$Products[0]->product_code." : ".@$Products[0]->product_name;
            $pv = @$Products[0]->pv;
            $member_price = @$Products[0]->member_price;

            $v = "รหัส : ชื่อสินค้า : ".$pn." \rPV : ".$pv." \rราคาขาย (บาท) : ".$member_price." ";

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


            // if($request->promotion_id_fk){
            // $sRow = \App\Models\Backend\PromotionCode::find($request->promotion_id_fk);
            // }else{
            // $sRow = new \App\Models\Backend\PromotionCode;
            // }
            // $sRow->promotion_id_fk = $request->promotion_id_fk;
            // $sRow->pro_sdate = $request->pro_sdate;
            // $sRow->pro_edate = $request->pro_edate;
            // $sRow->created_at = date('Y-m-d H:i:s');
            // $sRow->save();

            // return "this";
            // dd();

        // return ($request->promotion_id_fk);

            $sRow = DB::select("SELECT * FROM `db_promotion_code` where promotion_id_fk = ".$request->promotion_id_fk." order by id desc limit 1 ");


              $value=DB::table('db_promotion_code')
              ->where('promotion_id_fk', $request->promotion_id_fk)
              ->get();

              if($value->count() == 0){
                       DB::table('db_promotion_code')->insert(array(
                        'promotion_id_fk' =>  $request->promotion_id_fk,
                        'pro_sdate' =>  $request->pro_sdate,
                        'pro_edate' =>  $request->pro_edate,
                        'status' =>  1 ,
                        'created_at' => date("Y-m-d H:i:s"),
                      ));
              }else{
               $value=   DB::table('db_promotion_code')
                    ->where('promotion_id_fk', $request->promotion_id_fk)
                    ->update(array(
                        'pro_sdate' =>  $request->pro_sdate,
                        'pro_edate' =>  $request->pro_edate,
                        'status' => 1 ,
                        'created_at' => date("Y-m-d H:i:s"),
                    ));
              }

            DB::select(" TRUNCATE rand_code ; ");
            $permitted_chars = '23456789ABCDEFGHJKMNPQRSTUVWXYZ';
            $str_digit = $request->amt_random ;
            $amt = $request->GenAmt;
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
                 DB::select(" INSERT IGNORE INTO rand_code (rand_code) VALUES ('".$value."')  ");
            }

            // return "to this";
            // dd();

        $rand_code = DB::select(" SELECT * FROM rand_code ");
        if(count($rand_code) < $amt){
            // echo " จำนวนหลักไม่พอ";
            return 'x';
        }


    // return "to this";
            // dd();

        $rows = DB::select(" SELECT * from rand_code ");

        foreach ($rows as $key => $value) {
              $insertData = array(
                 "promotion_code_id_fk"=>$sRow[0]->id,
                 "promotion_code"=>@$request->prefix_coupon.($value->rand_code).@$request->suffix_coupon,
                 "customer_id_fk"=>'0',
                 "user_name"=>'0',
                 "pro_status"=> '5' ,
                 "created_at"=>now());
               PromotionCode_add::insertData($insertData);
        }

        return $sRow[0]->id;


    }



    public function ajaxGenPromotionSaveDate(Request $request)
    {
//
        // return $request;

         if($request->id){

            $sRow = \App\Models\Backend\PromotionCode::find($request->id);
            $sRow->promotion_id_fk = $request->promotion_id_fk;
            $sRow->coupon_terms = $request->coupon_terms;
            $sRow->pro_sdate = $request->pro_sdate;
            $sRow->pro_edate = $request->pro_edate;
            $sRow->status = 1;
            // $sRow->save();
            if($sRow->save()){
              DB::select(" UPDATE db_promotion_cus SET pro_status=1 WHERE promotion_code_id_fk=".$request->id." AND pro_status<>2 ; ");
              DB::select(" UPDATE db_promotion_cus SET pro_status=1 WHERE promotion_code_id_fk=".$request->id." AND used_user_name is null ; ");
              DB::select(" UPDATE db_promotion_cus SET pro_status=2 WHERE promotion_code_id_fk=".$request->id." AND used_user_name is not null ; ");
              if($request->pro_edate<date("Y-m-d")){
                 DB::select(" UPDATE db_promotion_cus SET pro_status=3 WHERE promotion_code_id_fk=".$request->id." AND used_user_name is null AND pro_status<>2 ; ");
              }

            }


          }

    }


    public function ajaxGiftVoucherSaveDate(Request $request)
    {
        // return $request;
        // dd();
         if($request->id){
            $sRow = \App\Models\Backend\GiftvoucherCode::find($request->id);
            $sRow->pro_sdate = $request->pro_sdate;
            $sRow->pro_edate = $request->pro_edate;
            $sRow->save();

            $sRow2 = \App\Models\Backend\GiftvoucherCus::where('giftvoucher_code_id_fk', $sRow->id)->first();
            $sRow2->pro_sdate = $request->pro_sdate;
            $sRow2->pro_edate = $request->pro_edate;
            $sRow2->save();

          }

    }


    public function ajaxGetPromotionCode(Request $request)
    {
        // return $request->txtSearchPro;
        $rs = DB::select("
            select * from db_promotion_cus
            Left Join db_promotion_code ON db_promotion_cus.promotion_code_id_fk = db_promotion_code.id
            Left Join promotions ON db_promotion_code.promotion_id_fk = promotions.id
            WHERE db_promotion_cus.promotion_code='".trim($request->txtSearchPro)."' AND promotions.status=1
            AND date(db_promotion_code.pro_edate) >= curdate() ;
          ");
        return @$rs[0]->promotion_id_fk;
    }


    public function ajaxCheckCouponUsed(Request $request)
    {
        // return($request);
        // return $request->txtSearchPro;
        // $customers_id_fk = $request->customers_id_fk;

        $msg = [];

        $p1 = DB::select("
            select promotions.id from db_promotion_cus
            Left Join db_promotion_code ON db_promotion_cus.promotion_code_id_fk = db_promotion_code.id
            Left Join promotions ON db_promotion_code.promotion_id_fk = promotions.id
          WHERE
           db_promotion_cus.promotion_code='".$request->txtSearchPro."'
             ;
        ");

        if(empty($p1)){
            $msg[] = "ไม่พบรหัสคูปองที่ค้นในฐานข้อมูล โปรดตรวจสอบอีกครั้ง";
            // return "InActive";
            return $msg;
        }

       $p2 = DB::select("

            select * from db_promotion_cus
            Left Join db_promotion_code ON db_promotion_cus.promotion_code_id_fk = db_promotion_code.id
            Left Join promotions ON db_promotion_code.promotion_id_fk = promotions.id
            WHERE
            db_promotion_cus.promotion_code='".trim($request->txtSearchPro)."'
            AND ( date(db_promotion_code.pro_edate) < curdate() OR date(promotions.show_enddate) < curdate() )

             ;

            ");

        if(!empty($p2)){
            $msg[] = 'รหัสคูปองนี้ หมดอายุการใช้งานแล้ว ';
            // return "InActive";
            return $msg;
        }

       $p3 = DB::select("

            select * from db_promotion_cus
            Left Join db_promotion_code ON db_promotion_cus.promotion_code_id_fk = db_promotion_code.id
            Left Join promotions ON db_promotion_code.promotion_id_fk = promotions.id
            WHERE
            db_promotion_cus.promotion_code='".trim($request->txtSearchPro)."'
            AND curdate() >= date(db_promotion_code.pro_sdate)
            AND date(db_promotion_code.pro_edate) >= curdate()
            AND curdate() >= date(promotions.show_startdate)
            AND date(promotions.show_enddate) >= curdate()

             ;

            ");

        if(empty($p3)){
            $msg[] = 'รหัสคูปองนี้ ไม่อยู่ในช่วงวันของโปรโมชั่น ';
            // return "InActive";
            return $msg;
        }

        // return $msg;

        // return $p1;

  // `minimum_package_purchased` int(11) DEFAULT '0' COMMENT 'package ขั้นต่ำที่ซื้อได้',
  // `reward_qualify_purchased` int(11) DEFAULT '0' COMMENT 'คุณวุฒิ reward ที่ซื้อได้',
  // `keep_personal_quality` int(11) DEFAULT '0' COMMENT 'รักษาคุณสมบัติส่วนตัว',
  // `maintain_travel_feature` int(11) DEFAULT '0' COMMENT 'รักษาคุณสมบัติท่องเที่ยว',
  // `aistockist` int(11) DEFAULT '0' COMMENT 'aistockist',
  // `agency` int(11) DEFAULT '0' COMMENT 'agency',

        // promotion
        $a1=array(0,0,0,0,0,0);
        $sPromotions = \App\Models\Backend\Promotions::find($p1[0]->id);
        // return $sPromotions->minimum_package_purchased; // Package  ขั้นต่ำที่ซื้อได้
        if($sPromotions->minimum_package_purchased){
            $a1[0] = $sPromotions->minimum_package_purchased;
            // $msg += ['cause' => "ไม่เข้าเงื่อนไข: Package  ขั้นต่ำที่ซื้อได้"];
            $msg[] = "ไม่เข้าเงื่อนไข: Package  ขั้นต่ำที่ซื้อได้";
        }

        // return $sPromotions->reward_qualify_purchased; //คุณวุฒิ reward ที่ซื้อได้
        if($sPromotions->reward_qualify_purchased){
            $a1[1] = $sPromotions->reward_qualify_purchased;
            $msg[] = "ไม่เข้าเงื่อนไข: คุณวุฒิ reward ที่ซื้อได้ ";
        }

        // return $sPromotions->keep_personal_quality; //รักษาคุณสมบัติส่วนตัว
        if($sPromotions->keep_personal_quality){
            $a1[2] = 1;
            $msg[] = "ไม่เข้าเงื่อนไข: รักษาคุณสมบัติส่วนตัว ";
        }

        // return $sPromotions->maintain_travel_feature; //รักษาคุณสมบัติท่องเที่ยว
        if($sPromotions->maintain_travel_feature){
            $a1[3] = 1;
            $msg[] = "ไม่เข้าเงื่อนไข: รักษาคุณสมบัติท่องเที่ยว ";
        }

        // return $sPromotions->aistockist; //aistockist
        if($sPromotions->aistockist){
            $a1[4] = 1;
            $msg[] = "ไม่เข้าเงื่อนไข: aistockist ";
        }

        // return $sPromotions->agency; //agency
        if($sPromotions->agency){
            $a1[5] = 1;
            $msg[] = "ไม่เข้าเงื่อนไข: agency ";
        }

        // return $a1;

        // return $msg;


        // customer
        $a2=array(0,0,0,0,0,0);
        $sCustomers = \App\Models\Backend\Customers::find($request->customers_id_fk);
        // return $sCustomer;
        // return $sCustomers->package_id; // Package  ขั้นต่ำที่ซื้อได้
        if($sCustomers->package_id){
            if($a1[0]){
                if($sCustomers->package_id>=$a1[0]){
                    $a1[0] = 1;
                    $a2[0] = 1;
                }else{
                    $a1[0] = 1;
                    $a2[0] = 0;
                }
            }else{
                $a1[0] = 0;
                $a2[0] = 0 ;
            }
        }
        // return $sCustomers->reward_qualify_purchased; //คุณวุฒิ reward ที่ซื้อได้
        if($sCustomers->reward_qualify_purchased){
            if($a1[1]){
                $a2[1] = $sCustomers->reward_qualify_purchased;
            }else{
                $a2[1] = 0 ;
            }
        }
        // return $sCustomers->keep_personal_quality; //รักษาคุณสมบัติส่วนตัว
        if($sCustomers->keep_personal_quality){
            if($a1[2]){
                $a2[2] = 1;
            }else{
                $a2[2] = 0 ;
            }
        }
        // return $sCustomers->maintain_travel_feature; //รักษาคุณสมบัติท่องเที่ยว
        if($sCustomers->maintain_travel_feature){
            if($a1[3]){
                $a2[3] = 1;
            }else{
                $a2[3] = 0 ;
            }
        }
        // return $sCustomers->aistockist; //aistockist
        if($sCustomers->aistockist){
            if($a1[4]){
                $a2[4] = 1;
            }else{
                $a2[4] = 0 ;
            }
        }
        // return $sCustomers->agency; //agency
        if($sCustomers->agency){
             if($a1[5]){
                $a2[5] = 1;
            }else{
                $a2[5] = 0 ;
            }
        }

        // return $a1;
        // return $a2;

        $arraysAreEqual = ($a1 === $a2);
        $arraysAreEqual = $arraysAreEqual==true?1:0;
        // return($arraysAreEqual);
        // dd();

        $promotion_cus = DB::select("

            select * from db_promotion_cus
            Left Join db_promotion_code ON db_promotion_cus.promotion_code_id_fk = db_promotion_code.id
            Left Join promotions ON db_promotion_code.promotion_id_fk = promotions.id
            WHERE
            db_promotion_cus.promotion_code='".trim($request->txtSearchPro)."'
            AND promotions.status=1
            AND promotions.promotion_coupon_status=1
            AND db_promotion_cus.pro_status=1
            AND curdate() >= date(db_promotion_code.pro_sdate)
            AND date(db_promotion_code.pro_edate) >= curdate()
            AND curdate() >= date(promotions.show_startdate)
            AND date(promotions.show_enddate) >= curdate()

             ;

            ");
        // return $promotion_cus[0]->promotion_code_id_fk;
//   AND ".$request->purchase_type_id_fk." in (promotions.orders_type_id)
        // // return $promotion_cus;
        // return count($promotion_cus);
        // dd();

        if( count($promotion_cus) == 0 || $arraysAreEqual == 0 ){
            // $msg[] = 'รหัสคูปองนี้ หมดอายุการใช้งานแล้ว หรือ ถูกใช้ไปแล้ว หรือ ยังไม่อยู่ในช่วงวันของโปรโมชั่น ';
            $p4 = DB::select("

            select * from db_promotion_cus
            Left Join db_promotion_code ON db_promotion_cus.promotion_code_id_fk = db_promotion_code.id
            Left Join promotions ON db_promotion_code.promotion_id_fk = promotions.id
            WHERE
            db_promotion_cus.promotion_code='".trim($request->txtSearchPro)."'
             ;

            ");
        // retur
            $p4_use1 = @$p4[0]->used_user_name?' ด้วยรหัส '.$p4[0]->used_user_name:'';
            $p4_use2 = @$p4[0]->used_date?' เมื่อ '.$p4[0]->used_date:'';
            $msg[] = 'รหัสคูปองนี้ ถูกใช้ไปแล้ว '.$p4_use1.$p4_use2;
            return $msg;
        }else{

            // return @$promotion_cus[0]->promotion_code_id_fk;
            // dd();

            // สรุปล่าสุดว่า ไม่ต้องดักคนใช้

                // $promotion_code = DB::select("
                //     select * from db_promotion_code
                //     Left Join promotions ON db_promotion_code.promotion_id_fk = promotions.id
                //     WHERE db_promotion_code.promotion_id_fk='".@$promotion_cus[0]->promotion_code_id_fk."' AND promotions.status=1 AND promotions.promotion_coupon_status=1 AND db_promotion_code.approve_status=1
                //     ; ");

                // return count($promotion_code) ;
                // dd();

            // if( count($promotion_code) == 0){
            //     return "InActive";
            // }else{

                    // $rs = DB::select(" select count(*) as cnt from db_order_products_list WHERE promotion_code='".$request->txtSearchPro."' ; ");
                    // return $rs[0]->cnt;
                    // dd();

                    // if($rs[0]->cnt > 0){
                        // return "InActive";
                    // }else if(@$promotion_code[0]->approve_status==0){
                        // return "InActive";
                    // }else{
                        return true;
                    // }

            // }
        }


    }

    public function ajaxApproveCouponCode(Request $request)
    {
        DB::select("
            UPDATE db_promotion_cus  SET pro_status=1
            WHERE promotion_code_id_fk = '".$request->promotion_code_id_fk."' AND pro_status in (4,5) ;
          ");

    }

    public function ajaxApproveGiftvoucherCode(Request $request)
    {

      $data = DB::table('db_giftvoucher_cus')
              ->where('giftvoucher_code_id_fk', '=', $request->giftvoucher_code_id_fk)
              ->where('pro_status', '=',4)
              ->get();

      // DB::select("
      //         UPDATE db_giftvoucher_cus  SET pro_status=1
      //         WHERE giftvoucher_code_id_fk = '".$request->giftvoucher_code_id_fk."' AND pro_status = 4 ;
      //       ");


        foreach($data as $value){
          $gv = \App\Helpers\Frontend::get_gitfvoucher($value->customer_username);
          $gv_banlance = $gv->sum_gv + $value->giftvoucher_value;

          $customer = DB::table('customers')
          ->select('id')
          ->where('user_name','=',$value->customer_username)
          ->first();

          $giftvoucher_cus_update = DB::table('db_giftvoucher_cus')
          ->where('id', $value->id)
          ->update(['pro_status' => 1]);

          $insert_log_gift_voucher = DB::table('log_gift_voucher')->insert([
              'customer_id_fk' =>  $customer->id,
              'order_id_fk' => '',
              // 'code_order' => $value->code_order,
              'giftvoucher_cus_code_order' =>$value->code_order,
              'giftvoucher_cus_id_fk' => $value->id,
              'type_action_giftvoucher'=>1,
              'giftvoucher_value_old' => $gv->sum_gv,
              'giftvoucher_value_use' => $value->giftvoucher_value,
              'giftvoucher_value_banlance' => $gv_banlance,
              'detail' => 'ได้รับจากกิจกรรม',
              'status' => 'success',
              'type' => 'Add',
          ]);



      }

    }


    public function ajaxGetPromotionName(Request $request)
    {
        // return $request->txtSearchPro;
        // $rs = DB::select(" SELECT promotions.*, name_thai as pro_name FROM promotions WHERE id=(SELECT promotion_code_id_fk FROM db_promotion_cus WHERE promotion_code='".$request->txtSearchPro."')  ");
        $rs = DB::select(" SELECT promotions.*, name_thai as pro_name FROM promotions WHERE id=(SELECT promotion_id_fk from db_promotion_code WHERE id=(SELECT promotion_code_id_fk FROM db_promotion_cus WHERE promotion_code='".$request->txtSearchPro."')) ");

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


    public function ajaxClearDataGiftvoucherCode(Request $request)
    {
        if($request->param=="Import"){
            DB::delete(" DELETE FROM db_giftvoucher_cus WHERE giftvoucher_code_id_fk =".$request->giftvoucher_code_id_fk." and pro_status=4 ; ");
        }
        if($request->param=="ByCase"){
           DB::delete(" DELETE FROM db_giftvoucher_cus WHERE id =".$request->id." and pro_status<>2 ; ");
        }
        DB::select(" ALTER table db_giftvoucher_cus AUTO_INCREMENT=1; ");
    }


    public function ajaxClearConsignment(Request $request)
    {
        DB::delete(" DELETE FROM db_consignments_import where pick_pack_requisition_code_id_fk=$request->id ");
        DB::select(" ALTER table db_consignments_import AUTO_INCREMENT=1; ");
        DB::select(" UPDATE db_consignments set consignment_no='' where pick_pack_requisition_code_id_fk=$request->id  ; ");
    }


    public function ajaxGetPayType(Request $request)
    {
        if($request->ajax()){
       /* dataset_orders_type
        1 ทำคุณสมบัติ
        2 รักษาคุณสมบัติรายเดือน
        3 รักษาคุณสมบัติท่องเที่ยว
        4 เติม Ai-Stockist
        5 แลก Gift Voucher
        */
        /* dataset_pay_type
        1   โอนชำระ
        2   บัตรเครดิต
        3   Ai-Cash
        4   Gift Voucher
        5   เงินสด
        */
            if($request->order_type==5){
                $query = DB::select(" select * from dataset_pay_type where id=4 and status=1  ");
            }else{
                $query = DB::select(" select * from dataset_pay_type where id in(1,2,3,4) and status=1  ");
            }

          return response()->json($query);

        }
    }

    public function ajaxGetLabelPayType(Request $request)
    {
        if($request->ajax()){
       /* dataset_orders_type
        1 ทำคุณสมบัติ
        2 รักษาคุณสมบัติรายเดือน
        3 รักษาคุณสมบัติท่องเที่ยว
        4 เติม Ai-Stockist
        5 แลก Gift Voucher
        */
        /* dataset_pay_type
        1   โอนชำระ
        2   บัตรเครดิต
        3   Ai-Cash
        4   Gift Voucher
        5   เงินสด
        */
            if($request->id){
              $query = DB::select(" select id,detail as pay_type from dataset_pay_type where id=".$request->id."  ");
              return response()->json($query);
            }

        }
    }

    public function ajaxGetLabelOthersPrice(Request $request)
    {
        if($request->ajax()){
       /* dataset_orders_type
        1 ทำคุณสมบัติ
        2 รักษาคุณสมบัติรายเดือน
        3 รักษาคุณสมบัติท่องเที่ยว
        4 เติม Ai-Stockist
        5 แลก Gift Voucher
        */
        /* dataset_pay_type
        1   โอนชำระ
        2   บัตรเครดิต
        3   Ai-Cash
        4   Gift Voucher
        5   เงินสด
        */
            if($request->id){
              $query = DB::select(" select id,detail as pay_type from dataset_pay_type where id=".$request->id."  ");
              return response()->json($query);
            }

        }
    }


    public function ajaxGetVoucher(Request $request)
    {
        // return $request;
        // dd();

        if($request->ajax()){
            if($request->id){
              $query = DB::select(" select * from gift_voucher where id=".$request->id." AND banlance>0  ");
              return response()->json($query);
            }
        }
    }


    public function ajaxDelFileSlip(Request $request)
    {
        // return $request;
        // dd();
        if($request->ajax()){
            // if($request->id){
            //   $sRow = DB::select(" select * from db_orders where id=".$request->id."  ");
            //   @UNLINK(@$sRow[0]->file_slip);
            //   DB::select(" UPDATE db_orders SET file_slip='',transfer_money_datetime=NULL,note_fullpayonetime=NULL where id=".$request->id."  ");
            //   $r = DB::select(" SELECT url,file FROM `payment_slip` WHERE `order_id`=".$request->id." ");
            //   @UNLINK(@$r[0]->url.@$r[0]->file);
            //   DB::select(" DELETE FROM `payment_slip` WHERE `order_id`=".$request->id." ");
            // }

            if($request->id){
              $r = DB::select(" SELECT url,file FROM `payment_slip` WHERE `id`=".$request->id." ");
              @UNLINK(@$r[0]->url.@$r[0]->file);
              DB::select(" DELETE FROM `payment_slip` WHERE `id`=".$request->id." ");
            }


        }
    }

  public function ajaxDelFileSlip_04(Request $request)
    {
        if($request->ajax()){
            if($request->id){
              $r = DB::select(" SELECT url,file,order_id FROM `payment_slip` WHERE `id`=".$request->id." ");
              DB::select(" UPDATE db_orders SET file_slip='',transfer_money_datetime=NULL,note_fullpayonetime=NULL,`transfer_bill_status`='1' where id=".@$r[0]->order_id."  ");
              @UNLINK(@$r[0]->url.@$r[0]->file);
              DB::select(" DELETE FROM `payment_slip` WHERE `id`=".$request->id." ");
            }
        }
    }


    public function ajaxDelFileSlip_02(Request $request)
    {
        // return $request;
        // dd();

        if($request->ajax()){
            if($request->id){
              $sRow = DB::select(" select * from db_orders where id=".$request->id."  ");
              @UNLINK(@$sRow[0]->file_slip_02);
              DB::select(" UPDATE db_orders SET file_slip_02='',transfer_money_datetime_02=NULL,note_fullpayonetime_02=NULL where id=".$request->id."  ");
              $r = DB::select(" SELECT url,file FROM `payment_slip` WHERE `order_id`=".$request->id." ");
              @UNLINK(@$r[0]->url.@$r[0]->file);
              DB::select(" DELETE FROM `payment_slip` WHERE `order_id`=".$request->id." ");
            }
        }
    }

    public function ajaxDelFileSlip_03(Request $request)
    {
        // return $request;
        // dd();

        if($request->ajax()){
            if($request->id){
              $sRow = DB::select(" select * from db_orders where id=".$request->id."  ");
              @UNLINK(@$sRow[0]->file_slip_03);
              DB::select(" UPDATE db_orders SET file_slip_03='',transfer_money_datetime_03=NULL,note_fullpayonetime_03=NULL where id=".$request->id."  ");
              $r = DB::select(" SELECT url,file FROM `payment_slip` WHERE `order_id`=".$request->id." ");
              @UNLINK(@$r[0]->url.@$r[0]->file);
              DB::select(" DELETE FROM `payment_slip` WHERE `order_id`=".$request->id." ");
            }
        }
    }

    public function ajaxDelFileSlipGiftVoucher(Request $request)
    {
        // return $request;
        // dd();

        if($request->ajax()){
            if($request->id){
              $sRow = DB::select(" select * from db_add_ai_cash where id=".$request->id."  ");
              @UNLINK(@$sRow[0]->file_slip);
              DB::select(" UPDATE db_add_ai_cash SET file_slip='' where id=".$request->id."  ");
            }
        }
    }


    public function ajaxGetAicashAmt(Request $request)
    {
        // return $request;
        // dd();
        if($request->ajax()){

            $id = $request->id;

            if($id){

                DB::select(" UPDATE db_add_ai_cash SET pay_type_id_fk=0 , cash_price='0', cash_pay='0', credit_price='0', fee='0', fee_amt='0', charger_type='0', sum_credit_price='0', total_amt='0',transfer_price=0 ,account_bank_id=0 ,transfer_money_datetime=null,file_slip=null WHERE (id='$id') ");

                $rs = DB::select(" SELECT * FROM db_add_ai_cash WHERE id=$id ");
                return response()->json($rs);

            }

        }
    }



    public function ajaxCheckAddAiCash(Request $request)
    {
        // return $request;
        // dd();
        if($request->ajax()){

               $sRow = \App\Models\Backend\Add_ai_cash::find($request->id);

              // เช็คเรื่องการตัดยอด Ai-Cash
               $ch_aicash_01 = DB::select(" select * from customers where id=".$request->customer_id_fk." ");
               // return($ch_aicash_01[0]->ai_cash);
               $ch_aicash_02 = DB::select(" select * from db_add_ai_cash where id=".$request->id." ");
               // return($ch_aicash_02[0]->aicash_amt);

                // return($ch_aicash_02[0]->aicash_amt ." : ". $ch_aicash_01[0]->ai_cash);


               // เช็คสถานะก่อนว่า รออนุมัติ หรือไม่ ถ้า ใช่ ให้ลบได้ ถ้าเป็น สถานะอื่นๆ จะไม่ให้ลบ
               // ล้อตาม db_orders>approve_status : 1=รออนุมัติ,2=อนุมัติแล้ว,3=รอชำระ,4=รอจัดส่ง,5=ยกเลิก,6=ไม่อนุมัติ,9=สำเร็จ(ถึงขั้นตอนสุดท้าย ส่งของให้ลูกค้าเรียบร้อย > Ref>dataset_approve_status>id
               // if($ch_aicash_02[0]->approve_status==1){
               //    return true;
               // }else{
               //   if($ch_aicash_02[0]->aicash_amt>$ch_aicash_01[0]->ai_cash){
               //       return 'no';
               //     }
               // }

          if(@$ch_aicash_01[0]->ai_cash>0){

                 if($ch_aicash_02[0]->aicash_amt>$ch_aicash_01[0]->ai_cash){
                     return 'no';
                 }else{
                    // if($ch_aicash_02[0]->approve_status==1){
                    //     DB::select(" delete from db_add_ai_cash where id=".$request->id." ");
                    //     return true;
                    // }else{
                        DB::select(" UPDATE customers SET ai_cash=(ai_cash-".$sRow->aicash_amt.") where id=".$sRow->customer_id_fk." ");
                        DB::select(" UPDATE db_add_ai_cash SET approve_status=5 where id=".$request->id." ");
                        return true;
                    // }
                 }

           }else{
                    // if($ch_aicash_02[0]->approve_status==1){
                    //     DB::select(" delete from db_add_ai_cash where id=".$request->id." ");
                    //     return true;
                    // }else{
                        DB::select(" UPDATE customers SET ai_cash=(ai_cash-".$sRow->aicash_amt.") where id=".$sRow->customer_id_fk." ");
                        DB::select(" UPDATE db_add_ai_cash SET approve_status=5 where id=".$request->id." ");
                        return true;
                    // }
           }




        }
    }


    public function ajaxGetDBAddAiCash(Request $request)
    {
        if($request->ajax()){
            if($request->id){
                $rs = DB::select(" SELECT * FROM db_add_ai_cash WHERE id=$request->id ");
                return response()->json($rs);
            }
        }
    }

    public function ajaxCheckAddAiCashStatus(Request $request)
    {
        if($request->ajax()){
            //  $rs = DB::select(" SELECT * FROM db_add_ai_cash WHERE bill_status=1 ");
            //  `approve_status` int(11) DEFAULT '0' COMMENT 'ล้อตาม db_orders>approve_status : 1=รออนุมัติ,2=อนุมัติแล้ว,3=รอชำระ,4=รอจัดส่ง,5=ยกเลิก,6=ไม่อนุมัติ,9=สำเร็จ(ถึงขั้นตอนสุดท้าย ส่งของให้ลูกค้าเรียบร้อย > Ref>dataset_approve_status>id',
            $rs = DB::select(" SELECT * FROM db_add_ai_cash WHERE approve_status=3 ");
            return count($rs);
        }
    }


    public function ajaxSaveGiftvoucherCode(Request $request)
    {
        if($request->ajax()){
            // return $request;

              if( !empty(@$request->giftvoucher_code_id_fk) ){
                $sRow = \App\Models\Backend\GiftvoucherCode::find($request->giftvoucher_code_id_fk );
              }else{

                $sRow = new \App\Models\Backend\GiftvoucherCode;
                $number = 5;
                $alphabet = 'abcdefghjkmnopqrstuvwxyz123456789';
                $code = array();

                $alphaLength = strlen($alphabet) - 1;
                for($i = 0; $i < $number; $i++) {
                  $n = rand(0, $alphaLength);
                  $code[] = $alphabet[$n];
                }
                $code = implode($code);
                $sRow->code = $code;
              }

              $sRow->descriptions = $request->descriptions;
              $sRow->pro_sdate = $request->pro_sdate;
              $sRow->pro_edate = $request->pro_edate;
              $sRow->created_at = date('Y-m-d H:i:s');
              $sRow->save();

              if($sRow->id){

                $data_gv = DB::table('db_giftvoucher_cus')
                ->select('id')
                ->orderby('id','DESC')
                ->first();
                if($data_gv){
                  $code_1 = $data_gv->id + 1;
                }else{
                  $code_1 = 1;
                }

                $insertData = array(
                 "giftvoucher_code_id_fk"=>$sRow->id,
                 "code_order"=>$sRow->code.''.$code_1,
                 "customer_username"=>$request->customer_username,
                 "giftvoucher_value"=>$request->giftvoucher_value,
                 "giftvoucher_banlance"=>$request->giftvoucher_value,
                 "pro_sdate"=> $request->pro_sdate ,
                 "pro_edate"=> $request->pro_edate ,
                 "pro_status"=> '4' ,
                 "created_at"=>now());
                GiftvoucherCode_add::insertData($insertData);
              }

             // return redirect()->to(url("backend/giftvoucher_code/".$sRow->id."/edit"));
              return $sRow->id;
        }

    }


    public function ajaxClearAfterSelChargerType(Request $request)
    {
        if($request->ajax()){
            // return $request;
            // dd();
                   // $("#credit_price").val('');
                   //  $("#fee_amt").val('');
                   //  $("#sum_credit_price").val('');
                   //  $("#aicash_price").val('');
                   //  $("#transfer_price").val('');
                   //  $("#cash_pay").val('');

            DB::select(" UPDATE db_orders SET
                credit_price='0.00',
                fee_amt='0.00',
                sum_credit_price='0.00',
                aicash_price='0.00',
                transfer_price='0.00',
                cash_pay='0.00'
                where id=".$request->frontstore_id_fk."  ");

        }

    }

    public function createPDFReceiptAicash($id)
     {
        // dd($id);
        $data = [$id];
        $customPaper = array(0,0,300,500);
        $pdf = PDF::loadView('backend.add_ai_cash.print_receipt',compact('data'))->setPaper($customPaper, 'landscape');
        // $pdf = PDF::loadView('backend.add_ai_cash.print_receipt',compact('data'))->setPaper('a5', 'landscape');
        // return $pdf->download('cover_sheet.pdf'); // โหลดทันที
        return $pdf->stream('receipt_sheet.pdf'); // เปิดไฟลฺ์

    }



   public function ajaxCalGiftVoucherPrice(Request $request)
    {
        // return $request;
        // dd();
      // return ($request->purchase_type_id_fk);

    if($request->purchase_type_id_fk!=5){

    }else{

        // Gift Voucher

            /*
                $pay_type_id_fk
                  1 เงินสด
                  2 เงินสด + Ai-Cash
                  3 เครดิต + เงินสด
                  4 เครดิต + เงินโอน
                  5 เครดิต + Ai-Cash
                  6 เงินโอน + เงินสด
                  7 เงินโอน + Ai-Cash
            */

            $pay_type_id_fk = $request->pay_type_id_fk;
            $frontstore_id =  $request->frontstore_id ;
            $sum_price = str_replace(',','',$request->sum_price);
            $shipping_price = str_replace(',','',$request->shipping_price);

            $sum_price = ($sum_price+$shipping_price) ;

            $gift_voucher_cost = str_replace(',','',$request->gift_voucher_cost);   // ที่มีอยู่
            $gift_voucher_price = str_replace(',','',$request->gift_voucher_price); // ที่กรอก

            if($pay_type_id_fk==19){
                if($gift_voucher_price==''){
                    $gift_voucher_price = 0;
                }
            }else{
                $gift_voucher_price = $gift_voucher_price>$gift_voucher_cost?$gift_voucher_cost:$gift_voucher_price;
                $gift_voucher_price = $gift_voucher_price>$sum_price?$sum_price:$gift_voucher_price;
                $sum_price = $sum_price-$gift_voucher_price;
            }



            // return ($sum_price);
            // return ($gift_voucher_cost);

            // $sRow = \App\Models\Backend\Frontstore::find($frontstore_id);
            // $ThisCustomer = DB::select(" select * from customers where id=".$sRow->customers_id_fk." ");
            // $data_gv = \App\Helpers\Frontend::get_gitfvoucher(@$ThisCustomer[0]->user_name);
            // $gv = @$data_gv->sum_gv;


            if($gift_voucher_price>0){
                // Gift Voucher + เงินโอน
                if($pay_type_id_fk==12){
                    DB::select(" UPDATE db_orders SET gift_voucher_cost='$gift_voucher_cost',gift_voucher_price='$gift_voucher_price' ,transfer_price='$sum_price' WHERE id=$frontstore_id ");
                }else
                // Gift Voucher + บัตรเครดิต
                if($pay_type_id_fk==13){
                    DB::select(" UPDATE db_orders SET gift_voucher_cost='$gift_voucher_cost',gift_voucher_price='$gift_voucher_price' ,credit_price='0',sum_credit_price='0' WHERE id=$frontstore_id ");
                }
                else
                // Gift Voucher + เงินสด
                if($pay_type_id_fk==19){
                    DB::select(" UPDATE db_orders SET gift_voucher_cost='$gift_voucher_cost',gift_voucher_price='$gift_voucher_price'  WHERE id=$frontstore_id ");
                    DB::select(" UPDATE db_orders SET cash_price=(0),cash_pay=($sum_price-$gift_voucher_price),total_price=($sum_price-$gift_voucher_price) WHERE id=$frontstore_id ");
                }
                else{
                    DB::select(" UPDATE db_orders SET gift_voucher_cost='$gift_voucher_cost',gift_voucher_price='$gift_voucher_price' WHERE id=$frontstore_id ");
                }
            }else{
                DB::select(" UPDATE db_orders SET gift_voucher_cost='0',gift_voucher_price='0' WHERE id=$frontstore_id ");
            }

        }

        $rs = DB::select(" SELECT * FROM db_orders WHERE id=$frontstore_id ");
        return response()->json($rs);

    }



  public function ajaxCalAddAiCashFrontstore(Request $request)
    {
        // return $request;
        // dd();
     /*
        $pay_type_id_fk
          1 เงินสด
          2 เงินสด + Ai-Cash
          3 เครดิต + เงินสด
          4 เครดิต + เงินโอน
          5 เครดิต + Ai-Cash
          6 เงินโอน + เงินสด
          7 เงินโอน + Ai-Cash
          */

        $pay_type_id_fk = $request->pay_type_id_fk;
        $id =  $request->id ;

        // return $pay_type_id_fk;
        // return $id;
        // dd();
        // if($pay_type_id_fk==''){

        // Clear ก่อน
 //        DB::select(" UPDATE db_add_ai_cash SET pay_type_id_fk=$pay_type_id_fk , cash_pay='0', credit_price='0', fee='0', fee_amt='0', charger_type='0', sum_credit_price='0', total_amt='0',transfer_money_datetime=null,file_slip=null WHERE (id='$id') ");
 // $pay_type_id_fk = request('pay_type_id_fk');
            DB::select(" UPDATE db_add_ai_cash SET pay_type_id_fk=$pay_type_id_fk , cash_pay='0', credit_price='0', fee='0', fee_amt='0', charger_type='0', sum_credit_price='0', total_amt='0',account_bank_id=0,transfer_price=0,transfer_money_datetime=null,file_slip=null WHERE (id='$id') ");
        // }

        $sum_price = str_replace(',','',$request->aicash_amt);

        // return $sum_price;
        // dd();


        if($pay_type_id_fk==5){
            DB::select(" UPDATE db_add_ai_cash SET cash_price=($sum_price),cash_pay=($sum_price),total_amt=($sum_price) WHERE id=$id ");
        }

         // dd();

        if($pay_type_id_fk==7){


                if(empty($request->credit_price)){
                    exit;
                }

                $credit_price = str_replace(',','',$request->credit_price);
                $credit_price = $credit_price>$sum_price?$sum_price:$credit_price;

                DB::select(" UPDATE db_add_ai_cash SET credit_price=$credit_price WHERE id=$id ");

                if(!empty($request->fee)){
                    $fee = DB::select(" SELECT * from dataset_fee where id =".$request->fee." ");
                    $fee_type = $fee[0]->fee_type;
                    if($fee_type==1){
                        $fee = $fee[0]->txt_value;
                        $fee_amt    = $credit_price * (@$fee/100) ;
                        $sum_credit_price = $credit_price + $fee_amt ;
                    }else{
                        $fee = $fee[0]->txt_fixed_rate;
                        $fee_amt =  $fee ;
                        $sum_credit_price = $credit_price + $fee_amt ;
                    }
               }else{
                  $fee_id = 0 ;
                  $fee_amt  = 0 ;
                  $sum_credit_price  = 0 ;
               }

              if(!empty($credit_price) && intval($credit_price) != 0){

                    $fee_id = $request->fee?$request->fee:0;
                    $charger_type = $request->charger_type;

                    if($charger_type==1){

                        if($credit_price==$sum_price){
                            DB::select(" UPDATE db_add_ai_cash SET credit_price=$credit_price, fee=$fee_id,fee_amt=$fee_amt,sum_credit_price=$sum_credit_price,cash_price=0,cash_pay=0,total_amt=($sum_price) WHERE id=$id ");
                        }else{
                            DB::select(" UPDATE db_add_ai_cash SET credit_price=$credit_price, fee=$fee_id,fee_amt=$fee_amt,sum_credit_price=$sum_credit_price,cash_price=($sum_price-$credit_price),cash_pay=($sum_price-$credit_price),total_amt=($sum_price) WHERE id=$id ");
                        }

                    }else{

                        DB::select(" UPDATE db_add_ai_cash SET charger_type=$charger_type,credit_price=$credit_price, fee=$fee_id,fee_amt=$fee_amt,sum_credit_price=$credit_price,cash_price=($sum_price-$credit_price),cash_pay=(($sum_price-$credit_price)+$fee_amt),total_amt=($sum_price) WHERE id=$id ");

                    }

                }

        }


        // return $pay_type_id_fk;
        // dd();
     if($pay_type_id_fk==8){

            if(empty($request->credit_price)){
                exit;
            }

            $credit_price = str_replace(',','',$request->credit_price);
            $transfer_price = str_replace(',','',$request->transfer_price);
            $credit_price = $credit_price>$sum_price?$sum_price:$credit_price;
            $transfer_price = $transfer_price>$sum_price?$sum_price:$transfer_price;
            DB::select(" UPDATE db_add_ai_cash SET credit_price=$credit_price WHERE id=$id ");

            if(!empty($request->fee)){
                $fee = DB::select(" SELECT * from dataset_fee where id =".$request->fee." ");
                $fee_type = $fee[0]->fee_type;
                if($fee_type==1){
                    $fee = $fee[0]->txt_value;
                    $fee_amt    = $credit_price * (@$fee/100) ;
                    $sum_credit_price = $credit_price + $fee_amt ;
                }else{
                    $fee = $fee[0]->txt_fixed_rate;
                    $fee_amt =  $fee ;
                    $sum_credit_price = $credit_price + $fee_amt ;
                }
           }else{
              $fee_id = 0 ;
              $fee_amt  = 0 ;
              $sum_credit_price  = 0 ;
           }

          if(!empty($credit_price) && intval($credit_price) != 0){

                $fee_id = $request->fee?$request->fee:0;
                $charger_type = $request->charger_type;
                $transfer_money_datetime = $request->transfer_money_datetime;

                if($charger_type==1){

                    if($credit_price==$sum_price){
                        DB::select(" UPDATE db_add_ai_cash SET credit_price=$credit_price, fee=$fee_id,fee_amt=$fee_amt,sum_credit_price=$sum_credit_price,cash_price=0,cash_pay=0,total_amt=($sum_price) WHERE id=$id ");
                    }else{
                        DB::select(" UPDATE db_add_ai_cash SET credit_price=$credit_price, fee=$fee_id,fee_amt=$fee_amt,sum_credit_price=$sum_credit_price,transfer_price=($sum_price-$credit_price),transfer_money_datetime='$transfer_money_datetime',cash_price=0,cash_pay=0,total_amt=($sum_price) WHERE id=$id ");
                    }

                }else{

                    DB::select(" UPDATE db_add_ai_cash SET charger_type=$charger_type,credit_price=$credit_price, fee=$fee_id,fee_amt=$fee_amt,sum_credit_price=$credit_price,transfer_price=(($sum_price-$credit_price)+$fee_amt),transfer_money_datetime='$transfer_money_datetime',cash_price=0,cash_pay=0,total_amt=($sum_price) WHERE id=$id ");

                }

            }

        }



        if($pay_type_id_fk==10){

                    if(empty($request->transfer_price)){
                        exit;
                    }
                    $transfer_price = str_replace(',','',$request->transfer_price);
                    $transfer_price = $transfer_price>$sum_price?$sum_price:$transfer_price;
                    $transfer_money_datetime = $request->transfer_money_datetime;
                    DB::select(" UPDATE db_add_ai_cash SET transfer_price=$transfer_price,cash_price=($sum_price-$transfer_price),cash_pay=($sum_price-$transfer_price),transfer_money_datetime='$transfer_money_datetime',total_amt=($sum_price) WHERE id=$id ");

         }

        if($pay_type_id_fk==1){

                    if(empty($request->transfer_price)){
                        exit;
                    }
                    $transfer_price = str_replace(',','',$request->transfer_price);
                    $transfer_price = $transfer_price>$sum_price?$sum_price:$transfer_price;
                    $transfer_money_datetime = $request->transfer_money_datetime;
                    DB::select(" UPDATE db_add_ai_cash SET transfer_price=$transfer_price,cash_price=0,cash_pay=0,transfer_money_datetime='$transfer_money_datetime',total_amt=($sum_price) WHERE id=$id ");

         }


        // if(!empty($request->this_element)){
        //     if($request->this_element=="aicash_price"){

        //         $sCustomer = DB::select(" select * from customers where id=".$request->customers_id_fk." ");
        //         $Cus_Aicash = $sCustomer[0]->ai_cash;

        //         $aicash_price = str_replace(',','',$request->aicash_price);
        //         $aicash_price = $aicash_price>$Cus_Aicash?$Cus_Aicash:$aicash_price;
        //         $cash_price = $sum_price-$aicash_price;
        //         $cash_pay = $sum_price-$aicash_price;

        //         if($aicash_price>$sum_price){

        //             DB::select(" UPDATE db_add_ai_cash SET aicash_price=$sum_price, cash_price=0, cash_pay=0,total_amt=($sum_price) WHERE id=$id ");
        //         }else{
        //             DB::select(" UPDATE db_add_ai_cash SET aicash_price=$aicash_price, cash_price=$cash_pay, cash_pay=$cash_pay,total_amt=($sum_price) WHERE id=$id ");
        //         }


        //     }

        // }


        $rs = DB::select(" SELECT * FROM db_add_ai_cash WHERE id=$id ");
        return response()->json($rs);

    }



  public function ajaxCalSaveTransferType(Request $request)
    {

        // return $request;
         $transfer_price = str_replace(',', '', $request->transfer_price);
         $cash_pay = str_replace(',', '', $request->cash_pay);
         DB::select(" UPDATE db_orders SET transfer_price=$transfer_price,cash_pay=$cash_pay,cash_price=$cash_pay WHERE id=$request->frontstore_id_fk ");

    }


    public function ajaxFifoApproved(Request $request)
    {
        // return $request;
        // dd();

        if($request->ajax()){
            // if($request->id){
                DB::select(" UPDATE db_pick_warehouse_fifo_topicked SET status='1' WHERE (status='0') ");

                // return response()->json($rs);
            // }


          // $c = "SELECT db_orders.invoice_code FROM
          //   db_order_products_list
          //   Left Join db_orders ON db_order_products_list.frontstore_id_fk = db_orders.id
          //   WHERE  db_order_products_list.product_id_fk in(SELECT product_id_fk FROM db_pick_warehouse_fifo_topicked where status=1)";

          // $sTable = DB::select(" SELECT * from db_products_fifo_bill where recipient_code in ($c) ");

        DB::select(" TRUNCATE TABLE db_pick_warehouse_tmp; ");
          DB::select("

          INSERT INTO db_pick_warehouse_tmp (invoice_code, product_code, product_name, amt, product_unit, amt_get, status, status_scan_qrcode, product_id_fk, created_at, updated_at)
          SELECT
          db_orders.invoice_code,
          (SELECT product_code FROM products WHERE id=db_order_products_list.product_id_fk limit 1) as product_code,
          (SELECT product_name FROM products_details WHERE product_id_fk=db_order_products_list.product_id_fk and lang_id=1 limit 1) as product_name,
          db_order_products_list.amt,
          dataset_product_unit.product_unit,
          0,0,0,
          db_order_products_list.product_id_fk,
          now(),
          now()
          FROM
          db_order_products_list
          Left Join db_orders ON db_order_products_list.frontstore_id_fk = db_orders.id
          Left Join dataset_product_unit ON db_order_products_list.product_unit_id_fk = dataset_product_unit.id
          WHERE db_order_products_list.product_id_fk in(SELECT product_id_fk FROM db_pick_warehouse_fifo_topicked)
          AND db_orders.invoice_code<>'' ");


        }
    }



    public function ajaxSetProductToBil(Request $request)
    {
        if($request->ajax()){

          // return $request;
          // dd();

            DB::select(" UPDATE db_pick_warehouse_tmp SET amt_get = db_pick_warehouse_tmp.amt ");

            // $data = DB::select(" SELECT invoice_code from db_pick_warehouse_tmp GROUP BY invoice_code ");

            // foreach ($data as $key => $value) {

            //           $d=DB::table('db_consignments')
            //           ->where('recipient_code', $value->invoice_code)
            //           ->get();

            //         if($d->count() == 0){

            //              DB::table('db_consignments')->insert(array(
            //             'recipient_code' => $value->invoice_code,
            //           ));

            //         }
            // }


            $data_addr = DB::select(" SELECT
              db_orders.invoice_code,
              customers_addr_sent.recipient_name,
              customers_addr_sent.house_no,
              customers_addr_sent.house_name,
              customers_addr_sent.moo,
              customers_addr_sent.road,
              customers_addr_sent.soi,
              customers_addr_sent.amphures,
              customers_addr_sent.district,
              customers_addr_sent.province,
              customers_addr_sent.zipcode,
              customers_addr_sent.tel,
              customers_addr_sent.tel_home,
              customers_addr_sent.id_choose
              FROM
              db_orders
              Left Join customers_addr_sent ON db_orders.address_sent_id_fk = customers_addr_sent.id
              Left Join dataset_amphures ON customers_addr_sent.amphures_id_fk = dataset_amphures.id
               ");

            // return $data_addr;
            // dd();

            foreach ($data_addr as $key => $v) {

              // if($v->recipient_name!='' && $v->invoice_code!=''){

                 $addr = $v->house_no." ";
                 $addr .= $v->house_name." ";
                 $addr .= $v->moo." ";
                 $addr .= $v->road." ";
                 $addr .= $v->soi." ";
                 $addr .= $v->amphures." ";
                 $addr .= $v->district." ";
                 $addr .= $v->province." ";
                 $addr .= $v->zipcode." ";
                 $addr .= $v->tel." ";
                 $addr .= $v->tel_home." ";



            }

        }
    }



    public function ajaxMapConsignments(Request $request)
    {
        if($request->ajax()){


            DB::select(" UPDATE
              db_consignments
              Inner Join db_consignments_import ON db_consignments.recipient_code = db_consignments_import.recipient_code
              SET
              db_consignments.consignment_no=db_consignments_import.consignment_no ,
              db_consignments.delivery_id_fk=db_consignments_import.delivery_id_fk

              ");
        }
    }



    public function ajaxProcessTaxdata(Request $request)
    {
        // return $request;
        // dd();
        // business_location_id_fk: "1"
        // end_date: "2021-04-22"
        // start_date: "2021-04-01"
        if($request->ajax()){
               DB::select(" TRUNCATE db_taxdata ");
               DB::select(' INSERT INTO db_taxdata select * from db_taxdata_test ');
        }
    }





    public function ajaxOfferToApprove(Request $request)
    {
        // return  $request->product_id_fk ;
        // product_id_fk: "2", start_date: "2021-04-02", end_date: "2021-04-02", _token: "zNKz86gXYZxaj7qtsvaDUvs0WR12I5aBW2LhGtYj"
        // dd();
        if($request->ajax()){


    // ปรับใหม่ ถ้า Status = NEW จะยังไม่สร้างรหัส
          $REF_CODE = DB::select(" SELECT business_location_id_fk,REF_CODE,SUBSTR(REF_CODE,4) AS REF_NO FROM DB_STOCKS_ACCOUNT_CODE ORDER BY REF_CODE DESC LIMIT 1 ");
          if($REF_CODE){
              $ref_code = 'ADJ'.sprintf("%05d",intval(@$REF_CODE[0]->REF_NO)+1);
          }else{
              $ref_code = 'ADJ'.sprintf("%05d",1);
          }

          $business_location_id_fk =  @$REF_CODE[0]->business_location_id_fk;

          //0=NEW,1=PENDDING,2=REQUEST,3=ACCEPTED/APPROVED,4=NO APPROVED,5=CANCELED
          DB::select(" UPDATE  db_stocks_account_code SET status_accepted=2,cuase_desc='".$request->cuase_desc."' where id=".$request->id." ; ");
          DB::select(" UPDATE  db_stocks_account_code SET ref_code='$ref_code' where id=".$request->id." AND (ref_code='' or ref_code is null) ; ");

          $branchs = DB::select("SELECT * FROM branchs where id=$business_location_id_fk ");

          $db_stocks_account =DB::select(" select * from db_stocks_account where stocks_account_code_id_fk =".$request->id." AND (run_code='' or run_code is null) ; ");

          $inv = DB::select(" SELECT run_code,SUBSTR(run_code,-5) AS REF_NO FROM DB_STOCKS_ACCOUNT ORDER BY run_code DESC LIMIT 1 ");
          if($inv){
                $REF_CODE = 'A'.$business_location_id_fk.date("ymd");
                $REF_NO = intval(@$inv[0]->REF_NO);
          }else{
                $REF_CODE = 'A'.$business_location_id_fk.date("ymd");
                $REF_NO = 0;
          }

          $i=1;
          foreach ($db_stocks_account as $key => $value) {

             $run_code = $REF_CODE.(sprintf("%05d",$REF_NO+$i));

             DB::select(" UPDATE db_stocks_account SET run_code = '$run_code' where stocks_account_code_id_fk =".$request->id." AND id=".$value->id." AND (run_code='' or run_code is null) ");

             $i++;
         }

        }
    }


    public function ajaxScanQrcodeProduct(Request $request)
    {

      if($request->ajax()){
        // return $request->all();
        // dd();
        // DB::select(" UPDATE db_pick_warehouse_qrcode SET qr_code = '' where id = $request->id ");
                    $value=DB::table('db_pick_warehouse_qrcode')
                    ->where('item_id', $request->item_id)
                    ->where('invoice_code', $request->invoice_code)
                    ->where('product_id_fk', $request->product_id_fk)
                    ->get();
                    if($value->count() == 0){
                          DB::table('db_pick_warehouse_qrcode')->insert(array(
                            'item_id' => $request->item_id,
                            'invoice_code' => $request->invoice_code,
                            'product_id_fk' => $request->product_id_fk,
                            'qr_code' => $request->qr_code,
                            'created_at' => date("Y-m-d H:i:s"),
                          ));
                    }else{
                          DB::table('db_pick_warehouse_qrcode')
                          ->where('item_id', $request->item_id)
                          ->where('invoice_code', $request->invoice_code)
                          ->where('product_id_fk', $request->product_id_fk)
                          ->update(array(
                            'qr_code' => $request->qr_code,
                          ));
                    }

      }

    }


    public function ajaxDeleteQrcodeProduct(Request $request)
    {

        if($request->ajax()){
        //  DB::select(" UPDATE db_pick_warehouse_qrcode SET qr_code = '' where id = $request->id ");
            DB::table('db_pick_warehouse_qrcode')
            ->where('item_id', $request->item_id)
            ->where('invoice_code', $request->invoice_code)
            ->where('product_id_fk', $request->product_id_fk)
            ->update(array(
              'qr_code' => '',
            ));
        }

    }


    public function ajaxScanQrcodeProductPacking(Request $request)
    {

      if($request->ajax()){
        // return $request->all();
        // dd();
        // DB::select(" UPDATE db_pick_warehouse_qrcode SET qr_code = '' where id = $request->id ");
                    $value=DB::table('db_pick_warehouse_qrcode')
                    ->where('item_id', $request->item_id)
                    ->where('packing_code', $request->packing_code)
                    ->where('product_id_fk', $request->product_id_fk)
                    ->get();
                    if($value->count() == 0){
                          DB::table('db_pick_warehouse_qrcode')->insert(array(
                            'item_id' => $request->item_id,
                            'packing_code' => $request->packing_code,
                            'product_id_fk' => $request->product_id_fk,
                            'qr_code' => $request->qr_code,
                            'created_at' => date("Y-m-d H:i:s"),
                          ));
                    }else{
                          DB::table('db_pick_warehouse_qrcode')
                          ->where('item_id', $request->item_id)
                          ->where('packing_code', $request->packing_code)
                          ->where('product_id_fk', $request->product_id_fk)
                          ->update(array(
                            'qr_code' => $request->qr_code,
                          ));
                    }

      }

    }



    public function ajaxProductPackingSize(Request $request)
    {

      if($request->ajax()){
            // $value=DB::table('db_pick_pack_packing')
            // ->where('id', $request->id)
            // ->get();
            // if($value->count() == 0){
            // }else{
                  DB::table('db_pick_pack_boxsize')
                  ->where('id',$request->box_id)
                  ->update(array(
                    'p_size' => $request->p_size,
                  ));
            // }
      }

    }


    public function ajaxProductPackingWeight(Request $request)
    {

      if($request->ajax()){
            // $value=DB::table('db_pick_pack_packing')
            // ->where('id', $request->id)
            // ->get();
            // if($value->count() == 0){
            // }else{
                DB::table('db_pick_pack_boxsize')
                ->where('id',$request->box_id)
                ->update(array(
                  'p_weight' => $request->p_weight,
                ));
            // }

      }

    }

   public function ajaxProductPackingAmtBox(Request $request)
    {

      if($request->ajax()){
            // $value=DB::table('db_pick_pack_packing')
            // ->where('id', $request->id)
            // ->get();
            // if($value->count() == 0){
            // }else{
                DB::table('db_pick_pack_boxsize')
                ->where('id',$request->box_id)
                ->update(array(
                  'p_amt_box' => $request->p_amt_box,
                ));
            // }

      }

    }



    public function ajaxDeleteQrcodeProductPacking(Request $request)
    {

        if($request->ajax()){
        //  DB::select(" UPDATE db_pick_warehouse_qrcode SET qr_code = '' where id = $request->id ");
            DB::table('db_pick_warehouse_qrcode')
            ->where('item_id', $request->item_id)
            ->where('packing_code', $request->packing_code)
            ->where('product_id_fk', $request->product_id_fk)
            ->update(array(
              'qr_code' => '',
            ));
        }

    }



    public function ajaxGetAmtInStock(Request $request)
    {

      if($request->ajax()){
         // $r= DB::select(" SELECT amt FROM db_stocks where product_id_fk = $request->product_id_fk AND id = $request->id ");
         // return @$r[0]->amt;
          $rs = DB::select("
            SELECT db_stocks.*,dataset_product_unit.product_unit,dataset_product_unit.id as product_unit_id_fk,
            dataset_business_location.txt_desc AS business_location,
            branchs.b_name AS branch,
            warehouse.w_name,warehouse.id as warehouse_id_fk,
            zone.z_name,zone.id as zone_id_fk,
            shelf.s_name,shelf.id as shelf_id_fk,db_stocks.shelf_floor
             FROM db_stocks
            Left Join dataset_product_unit ON db_stocks.product_unit_id_fk = dataset_product_unit.id
            Left Join dataset_business_location ON db_stocks.business_location_id_fk = dataset_business_location.id
            Left Join branchs ON db_stocks.branch_id_fk = branchs.id
            Left Join warehouse ON db_stocks.warehouse_id_fk = warehouse.id
            Left Join zone ON db_stocks.zone_id_fk = zone.id
            Left Join shelf ON db_stocks.shelf_id_fk = shelf.id
            WHERE db_stocks.id=$request->id ");
          return response()->json($rs);

      }

    }


    public function ajaxGentoExportConsignments(Request $request)
    {

      if($request->ajax()){

            // DB::select(" TRUNCATE db_consignments; ");

            $data = DB::select(" SELECT invoice_code from db_pick_warehouse_tmp GROUP BY invoice_code ");

            foreach ($data as $key => $value) {

                      $d=DB::table('db_consignments')
                      ->where('recipient_code', $value->invoice_code)
                      ->get();

                    if($d->count() == 0){

                         DB::table('db_consignments')->insert(array(
                        'recipient_code' => $value->invoice_code,
                      ));

                    }

            }



            $data_addr = DB::select(" SELECT
              db_orders.invoice_code,
              customers_addr_sent.recipient_name,
              customers_addr_sent.house_no,
              customers_addr_sent.house_name,
              customers_addr_sent.moo,
              customers_addr_sent.road,
              customers_addr_sent.soi,
              customers_addr_sent.amphures,
              customers_addr_sent.district,
              customers_addr_sent.province,
              customers_addr_sent.zipcode,
              customers_addr_sent.tel,
              customers_addr_sent.tel_home,
              customers_addr_sent.id_choose
              FROM
              db_orders
              Left Join customers_addr_sent ON db_orders.address_sent_id_fk = customers_addr_sent.id
              Left Join dataset_amphures ON customers_addr_sent.amphures_id_fk = dataset_amphures.id
               ");

            foreach ($data_addr as $key => $v) {
              if($v->recipient_name!='' && $v->invoice_code!=''){
                 $addr = $v->house_no." ";
                 $addr .= $v->house_name." ";
                 $addr .= $v->moo." ";
                 $addr .= $v->road." ";
                 $addr .= $v->soi." ";
                 $addr .= $v->amphures." ";
                 $addr .= $v->district." ";
                 $addr .= $v->province." ";
                 $addr .= $v->zipcode." ";
                 $addr .= $v->tel." ";
                 $addr .= $v->tel_home." ";
                 DB::select(" UPDATE db_consignments set recipient_name='".$v->recipient_name."',address='$addr' WHERE recipient_code='".$v->invoice_code."'  ");
               }

            }

          //  DB::select(" UPDATE db_consignments set address='ไม่ได้ระบุที่อยู่ กรุณาตรวจสอบ' WHERE  address is null  ");
            DB::select(" UPDATE db_consignments set address='' WHERE  address is null  ");


      }

    }


    public function ajaxSyncStockToNotify(Request $request)
    {

      if($request->ajax()){

          DB::select(" INSERT IGNORE INTO `db_stocks_notify`
          (`business_location_id_fk`, `branch_id_fk`, `warehouse_id_fk`, `product_id_fk`, `lot_number`, `lot_expired_date`, `amt`, `product_unit_id_fk`, `created_at`, `updated_at`)

          SELECT
          `business_location_id_fk`, `branch_id_fk`, `warehouse_id_fk`, `product_id_fk`, `lot_number`, `lot_expired_date`, `amt`, `product_unit_id_fk`, `created_at`, `updated_at`
          FROM
          db_stocks
          WHERE lot_expired_date >= CURDATE() ");

      }

    }

// หน้าค้น
    public function ajaxGetCusToPayReceiptForSearch(Request $request)
    {

      if($request->ajax()){

          $temp_ppr_003 = "temp_ppr_003".\Auth::user()->id; // เก็บสถานะการส่ง และ ที่อยู่ในการจัดส่ง
          $temp_ppr_004 = "temp_ppr_004".\Auth::user()->id; // เก็บสถานะการส่ง และ ที่อยู่ในการจัดส่ง

          $rs =  DB::select("
             SELECT
             $temp_ppr_003.id,
             $temp_ppr_003.business_location_id_fk,
             $temp_ppr_003.branch_id_fk,
             $temp_ppr_003.invoice_code,
             (select name from ck_users_admin where id=$temp_ppr_003.action_user)  AS user_action,
             $temp_ppr_003.bill_date,
             (select name from ck_users_admin where id=$temp_ppr_003.pay_user)  AS pay_user,
             $temp_ppr_003.pay_date,
             $temp_ppr_003.status_sent,
             $temp_ppr_003.customer_id_fk,
             $temp_ppr_003.address_send AS user_address,
             $temp_ppr_003.address_send_type,
             customers.user_name AS user_code,
             CONCAT(customers.prefix_name,customers.first_name,' ',customers.last_name) AS user_name,
             dataset_pay_product_status.txt_desc as bill_status,
             (SELECT sum(amt_lot) FROM $temp_ppr_004 WHERE invoice_code=$temp_ppr_003.invoice_code GROUP BY invoice_code) as sum_amt_lot
             FROM
             $temp_ppr_003
             Left Join customers ON $temp_ppr_003.customer_id_fk = customers.id
             Left Join dataset_pay_product_status ON $temp_ppr_003.status_sent = dataset_pay_product_status.id
            where $temp_ppr_003.invoice_code = '".$request->txtSearch."'
             ");

          return response()->json($rs);

      }

    }

// หลังเซฟลงตารางจริงแล้ว
    public function ajaxGetCusToPayReceiptAfterSave(Request $request)
    {

      if($request->ajax()){

          $temp_ppr_003 = "temp_ppr_003".\Auth::user()->id; // เก็บสถานะการส่ง และ ที่อยู่ในการจัดส่ง

          $rs =  DB::select("
             SELECT
             $temp_ppr_003.id,
             $temp_ppr_003.business_location_id_fk,
             $temp_ppr_003.branch_id_fk,
             $temp_ppr_003.invoice_code,
             (select name from ck_users_admin where id=$temp_ppr_003.action_user)  AS user_action,
             $temp_ppr_003.bill_date,
             (select name from ck_users_admin where id=$temp_ppr_003.pay_user)  AS pay_user,
             $temp_ppr_003.pay_date,
             $temp_ppr_003.status_sent,
             $temp_ppr_003.customer_id_fk,
             $temp_ppr_003.address_send AS user_address,
             $temp_ppr_003.address_send_type,
             customers.user_name AS user_code,
             CONCAT(customers.prefix_name,customers.first_name,' ',customers.last_name) AS user_name,
             dataset_pay_product_status.txt_desc as bill_status
             FROM
             $temp_ppr_003
             Left Join customers ON $temp_ppr_003.customer_id_fk = customers.id
             Left Join dataset_pay_product_status ON $temp_ppr_003.status_sent = dataset_pay_product_status.id
            where $temp_ppr_003.invoice_code = '".$request->txtSearch."'
             ");

          return response()->json($rs);

      }

    }



  public function ajaxGetCEUserRegis(Request $request)
    {

      if($request->ajax()){

          $rs =  DB::select("
                SELECT
                course_event_regis.id,
                course_event_regis.customers_id_fk,
                course_event_regis.status_in,
                course_event_regis.note,
                concat(
                            customers.user_name,' : ',
                            customers.prefix_name,
                            customers.first_name,' ',
                            customers.last_name) AS cus_name,
                dataset_package.dt_package AS cus_package,
                course_event_regis.ce_regis_gift AS ce_regis_gift,
                course_event.ce_name
                FROM
                course_event_regis
                Left Join customers ON course_event_regis.customers_id_fk = customers.id
                Left Join dataset_package ON customers.package_id = dataset_package.id
                Left  Join course_event ON course_event_regis.ce_id_fk = course_event.id
                where course_event_regis.id = '".$request->id."'
             ");
          return response()->json($rs);

      }

    }




  public function ajaxGetCEQrcode(Request $request)
    {

      if($request->ajax()){

          $rs =  DB::select("
            SELECT
            course_event_regis.id
            FROM
            course_event_regis
            where course_event_regis.qr_code = '".$request->txtSearch."'
             ");
          return @$rs[0]->id;

      }

    }


  public function ajaxGetCe_regis_gift(Request $request)
    {

      if($request->ajax()){
          // return $request->ce_regis_gift;
          $rsCe = explode(",",$request->ce_regis_gift);
          $Ce_regis_gift = DB::select(" select * from dataset_ce_regis_gift ");
          $response ='';
          foreach ($Ce_regis_gift as $key => $value) {

              $checked = '';
              foreach($rsCe as $v){
                 if($value->id==$v) $checked = 'checked';
              }

            $response .= "<div class='checkbox-color checkbox-primary Ce_regis_gift '>
            <input type='checkbox' id='regis_gift".$value->id."' name='regis_gift[]' value='".$value->id."' ".$checked." >
            <label for='regis_gift".$value->id."'>".$value->txt_desc."</label>
            </div>";
          }
          return $response;

      }

    }



  public function ajaxCheckRemain_pay_product_receipt(Request $request)
    {

      if($request->ajax()){

          $rs_pay_history = DB::select(" SELECT id FROM `db_pay_product_receipt_002_pay_history` WHERE invoice_code='".$request->txtSearch."' AND status in (2) ");
          return count($rs_pay_history);

      }

    }

  public function ajaxPackingFinished(Request $request)
    {

      if($request->ajax()){
// 1=รอเบิก, 2=อนุมัติแล้วรอจัดกล่อง (มีค้างจ่ายบางรายการ), 3=อนุมัติแล้วรอจัดกล่อง (ไม่มีค้างจ่าย), 4=Packing กล่องแล้ว, 5=บ.ขนส่งเข้ามารับสินค้าแล้ว, 6=ยกเลิกใบเบิก
          DB::select(" UPDATE `db_pay_requisition_001` SET status_sent=4 WHERE pick_pack_requisition_code_id_fk='".$request->id."' ");
          DB::select(" UPDATE `db_pick_pack_packing_code` SET status=4 WHERE id='".$request->id."' ");

      }

    }




  public function ajaxShippingFinished(Request $request)
    {

      if($request->ajax()){
// 1=รอเบิก, 2=อนุมัติแล้วรอจัดกล่อง (มีค้างจ่ายบางรายการ), 3=อนุมัติแล้วรอจัดกล่อง (ไม่มีค้างจ่าย), 4=Packing กล่องแล้ว, 5=บ.ขนส่งเข้ามารับสินค้าแล้ว, 6=ยกเลิกใบเบิก
          DB::select(" UPDATE `db_pay_requisition_001` SET status_sent=5 WHERE pick_pack_requisition_code_id_fk='".$request->id."' ");
          DB::select(" UPDATE `db_pick_pack_packing_code` SET sender=".(\Auth::user()->id).",sent_date=now(),status=5 WHERE id='".$request->id."' ");

      }

    }


  public function ajaxSentMoneyDaily(Request $request)
    {
      // return($request);

      if($request->ajax()){

        //  $r0 = DB::select(" SELECT * FROM `db_orders`  WHERE date(updated_at)=CURDATE() AND action_user=".(\Auth::user()->id)." AND status_sent_money=0  ");
        // `approve_status` int(11) DEFAULT '0' COMMENT ' 0=รออนุมัติ,1=อนุมัติแล้ว,2=รอชำระ,3=รอจัดส่ง,4=ยกเลิก,5=ไม่อนุมัติ,9=สำเร็จ(ถึงขั้นตอนสุดท้าย ส่งของให้ลูกค้าเรียบร้อย)''',
// ของเดิม
// `approve_status` int(11) DEFAULT '0' COMMENT '0=รออนุมัติ,1=อนุมัติแล้ว,2=รอชำระ,3=รอจัดส่ง,4=ยกเลิก,5=ไม่อนุมัติ',
// แก้ใหม่
// `approve_status` int(11) DEFAULT '0' COMMENT ' 1=รออนุมัติ,2=อนุมัติแล้ว,3=รอชำระ,4=รอจัดส่ง,5=ยกเลิก,6=ไม่อนุมัติ,9=สำเร็จ(ถึงขั้นตอนสุดท้าย ส่งของให้ลูกค้าเรียบร้อย',
          $sPermission = \Auth::user()->permission ;
          if($sPermission==1){
              $wh = "";
          }else{
              // $wh = " AND db_orders.action_user = ".(\Auth::user()->id)." AND branch_id_fk=".@\Auth::user()->branch_id_fk." ";
              $wh = " AND db_orders.action_user = '".(\Auth::user()->id)."' ";
          }

          // return($wh);

          $r0 = DB::select(" SELECT * FROM `db_orders`
            WHERE date(updated_at)=CURDATE()
            $wh
            AND approve_status in (2,4,9)
            AND approve_status not in (5,6)
            AND status_sent_money <> 1
            AND code_order <> ''
            AND (db_orders.cash_price>0 or db_orders.credit_price>0 or db_orders.transfer_price>0 or db_orders.aicash_price>0 or db_orders.total_price>0)  ");

          // return $r0;

          if($r0){

            $arr_orders_id_fk = [];
            foreach ($r0 as $key => $v) {
                array_push($arr_orders_id_fk,$v->id);
            }
            $arr_orders_id_fk = implode(",",$arr_orders_id_fk);
            // return $arr_orders_id_fk;

            $r1= DB::select(" SELECT time_sent FROM `db_sent_money_daily`  WHERE date(updated_at)=CURDATE() AND sender_id=".(\Auth::user()->id)."
             ");

            // return $r1;

            if($r1){

                  $time_sent = $r1[0]->time_sent + 1 ;

                  $r2 = DB::select(" INSERT INTO db_sent_money_daily(time_sent,sender_id,orders_ids,created_at) values ($time_sent,".(\Auth::user()->id).",'$arr_orders_id_fk',now()) ");
                  $id = DB::getPdo()->lastInsertId();

                  DB::select(" UPDATE `db_orders` SET status_sent_money=1,sent_money_daily_id_fk=$id WHERE id in ($arr_orders_id_fk) ");

                  // return $id;

            }else{

                  $r2 = DB::select(" INSERT INTO db_sent_money_daily(time_sent,sender_id,orders_ids,created_at) values ('1',".(\Auth::user()->id).",'$arr_orders_id_fk',now()) ");
                  $id = DB::getPdo()->lastInsertId();

                  DB::select(" UPDATE `db_orders` SET status_sent_money=1,sent_money_daily_id_fk=$id WHERE id in ($arr_orders_id_fk) ");
              }

          }


      }

    }


  public function ajaxCancelSentMoney(Request $request)
    {

      if($request->ajax()){

          $r1= DB::select(" SELECT * FROM `db_sent_money_daily`  WHERE id=".$request->id."  ");
          if($r1){
            DB::select(" UPDATE `db_orders` SET status_sent_money=0,sent_money_daily_id_fk=0 WHERE sent_money_daily_id_fk=".$request->id." ");
            DB::select(" UPDATE `db_sent_money_daily` SET `status_cancel`='1' WHERE (`id`='".$request->id."') ");
          }

      }

    }


  public function ajaxCacelStatusPackingSent(Request $request)
    {

      // return($request);

      if($request->ajax()){

        // 3=สินค้าพอต่อการจ่ายครั้งนี้ 2=สินค้าไม่พอ มีบางรายการค้างจ่าย
          // DB::select(" UPDATE `db_pay_requisition_001` SET status_sent=3 WHERE pick_pack_requisition_code_id_fk='".$request->id."' ");
// 1=รอเบิก, 2=อนุมัติแล้วรอจัดกล่อง (มีค้างจ่ายบางรายการ), 3=อนุมัติแล้วรอจัดกล่อง (ไม่มีค้างจ่าย), 4=Packing กล่องแล้ว, 5=บ.ขนส่งเข้ามารับสินค้าแล้ว, 6=ยกเลิกใบเบิก
          // DB::select(" UPDATE `db_pick_pack_packing_code` SET status=3 WHERE id='".$request->id."' ");

            // $r_ch_t = '';
            // $r_ch = DB::select("SELECT * FROM `db_pay_requisition_002_pay_history` where pick_pack_packing_code_id_fk=".$row->id." AND `status`=2  ");
            // if(count($r_ch)>0){
            //    $r_ch_t = '(รายการนี้ค้างจ่ายในรอบนี้ เนื่องจากไม่มีในคลัง)';
            // }else{
            //    $r_ch_t = '';
            // }



      }

    }


  public function cancelBill(Request $request)
    {

      // return($request);

      if($request->ajax()){

      //    DB::select(" UPDATE `db_pay_requisition_001` SET `status_sent`='3' WHERE (`id`='".$request->id."') ");

          DB::select(" UPDATE `db_pay_requisition_001` SET action_user=".(\Auth::user()->id).",action_date=now(),status_sent=6 WHERE pick_pack_requisition_code_id_fk='".$request->id."' ");
          DB::select(" UPDATE `db_pick_pack_packing_code` SET who_cancel=".(\Auth::user()->id).",cancel_date=now(),status=6 WHERE id='".$request->id."' ");
          DB::select(" UPDATE
            db_pay_requisition_002
            SET
            status_cancel=1
            WHERE pick_pack_requisition_code_id_fk='".$request->id."'
            ORDER BY time_pay DESC LIMIT 1 ");

          // เอาสินค้าคืนคลัง
          $r = DB::select("

            SELECT
            time_pay,business_location_id_fk, branch_id_fk, product_id_fk, lot_number, lot_expired_date, amt_get, product_unit_id_fk, warehouse_id_fk, zone_id_fk, shelf_id_fk, shelf_floor,pick_pack_requisition_code_id_fk, created_at,now()
            FROM db_pay_requisition_002
            WHERE pick_pack_requisition_code_id_fk='".$request->id."'
            ORDER BY time_pay DESC LIMIT 1 ; ");

          if($r){

            foreach ($r as $key => $v) {
                   $_choose=DB::table("db_stocks_return")
                      ->where('time_pay', $v->time_pay)
                      ->where('business_location_id_fk', $v->business_location_id_fk)
                      ->where('branch_id_fk', $v->branch_id_fk)
                      ->where('product_id_fk', $v->product_id_fk)
                      ->where('lot_number', $v->lot_number)
                      ->where('lot_expired_date', $v->lot_expired_date)
                      ->where('amt', $v->amt_get)
                      ->where('product_unit_id_fk', $v->product_unit_id_fk)
                      ->where('warehouse_id_fk', $v->warehouse_id_fk)
                      ->where('zone_id_fk', $v->zone_id_fk)
                      ->where('shelf_id_fk', $v->shelf_id_fk)
                      ->where('shelf_floor', $v->shelf_floor)
                      ->where('pick_pack_requisition_code_id_fk', $request->id)
                      ->get();
                      if($_choose->count() == 0){

                             DB::select(" INSERT INTO db_stocks_return (time_pay,business_location_id_fk, branch_id_fk, product_id_fk, lot_number, lot_expired_date, amt, product_unit_id_fk, warehouse_id_fk, zone_id_fk, shelf_id_fk,shelf_floor, pick_pack_requisition_code_id_fk, created_at, updated_at,status_cancel)
                            SELECT
                            time_pay,business_location_id_fk, branch_id_fk, product_id_fk, lot_number, lot_expired_date, amt_get, product_unit_id_fk, warehouse_id_fk, zone_id_fk, shelf_id_fk, shelf_floor,pick_pack_requisition_code_id_fk, created_at,now(),1
                             FROM db_pay_requisition_002 WHERE pick_pack_requisition_code_id_fk='".$request->id."' AND time_pay='".$v->time_pay."' ; ");

                       }


                         DB::select(" UPDATE db_stocks SET db_stocks.amt=db_stocks.amt+(".$v->amt_get.")
                                  WHERE
                                  business_location_id_fk= ".$v->business_location_id_fk." AND
                                  branch_id_fk= ".$v->branch_id_fk." AND
                                  product_id_fk= ".$v->product_id_fk." AND
                                  lot_number= '".$v->lot_number."' AND
                                  lot_expired_date= '".$v->lot_expired_date."' AND
                                  warehouse_id_fk= ".$v->warehouse_id_fk." AND
                                  zone_id_fk= ".$v->zone_id_fk." AND
                                  shelf_id_fk= ".$v->shelf_id_fk." AND
                                  shelf_floor= ".$v->shelf_floor."
                              ");


               }



                             $insertStockMovement = new  AjaxController();

                              // รับคืนจากการยกเลิกใบเบิก db_stocks_return
                              $Data = DB::select("
                                      SELECT
                                      db_stocks_return.business_location_id_fk,
                                      db_stocks_return.invoice_code as doc_no,
                                      db_stocks_return.updated_at as doc_date,
                                      db_stocks_return.branch_id_fk,
                                      product_id_fk,
                                      lot_number,
                                      lot_expired_date,
                                      amt,
                                      1 as 'in_out',
                                      product_unit_id_fk,
                                      warehouse_id_fk,zone_id_fk,shelf_id_fk,shelf_floor,db_stocks_return.status_cancel as status,
                                      'รับคืนจากการยกเลิกใบเบิก' as note,
                                      db_stocks_return.updated_at as dd,
                                      db_pick_pack_requisition_code.action_user as action_user,'' as approver,'' as approve_date

                                      FROM db_stocks_return
                                      LEFT JOIN db_pick_pack_requisition_code on db_pick_pack_requisition_code.id=db_stocks_return.pick_pack_requisition_code_id_fk
                                      WHERE
                                      db_stocks_return.status_cancel=1

                                ");

                              if(@$Data){

                                  foreach ($Data as $key => $value) {

                                       $insertData = array(
                                          "doc_no" =>  @$value->doc_no?$value->doc_no:NULL,
                                          "doc_date" =>  @$value->doc_date?$value->doc_date:NULL,
                                          "business_location_id_fk" =>  @$value->business_location_id_fk?$value->business_location_id_fk:0,
                                          "branch_id_fk" =>  @$value->branch_id_fk?$value->branch_id_fk:0,
                                          "product_id_fk" =>  @$value->product_id_fk?$value->product_id_fk:0,
                                          "lot_number" =>  @$value->lot_number?$value->lot_number:NULL,
                                          "lot_expired_date" =>  @$value->lot_expired_date?$value->lot_expired_date:NULL,
                                          "amt" =>  @$value->amt?$value->amt:0,
                                          "in_out" =>  @$value->in_out?$value->in_out:0,
                                          "product_unit_id_fk" =>  @$value->product_unit_id_fk?$value->product_unit_id_fk:0,
                                          "warehouse_id_fk" =>  @$value->warehouse_id_fk?$value->warehouse_id_fk:0,
                                          "zone_id_fk" =>  @$value->zone_id_fk?$value->zone_id_fk:0,
                                          "shelf_id_fk" =>  @$value->shelf_id_fk?$value->shelf_id_fk:0,
                                          "shelf_floor" =>  @$value->shelf_floor?$value->shelf_floor:0,
                                          "status" =>  @$value->status?$value->status:0,
                                          "note" =>  @$value->note?$value->note:NULL,

                                          "action_user" =>  @$value->action_user?$value->action_user:NULL,
                                          "action_date" =>  @$value->action_date?$value->action_date:NULL,
                                          "approver" =>  @$value->approver?$value->approver:NULL,
                                          "approve_date" =>  @$value->approve_date?$value->approve_date:NULL,

                                          "created_at" =>@$value->dd?$value->dd:NULL
                                      );

                                        $insertStockMovement->insertStockMovement($insertData);

                                    }

                                    DB::select(" INSERT IGNORE INTO db_stock_movement SELECT * FROM db_stock_movement_tmp ORDER BY doc_date asc ");

                               }


          }

          $r2 = DB::select("
            SELECT * FROM db_pay_requisition_002 WHERE pick_pack_requisition_code_id_fk='".$request->id."' AND status_cancel=1 ORDER BY time_pay DESC LIMIT 1 ; ");

             foreach ($r2 as $key => $v) {
                   $_choose=DB::table("db_pay_requisition_002_cancel_log")
                      ->where('time_pay', $v->time_pay)
                      ->where('business_location_id_fk', $v->business_location_id_fk)
                      ->where('branch_id_fk', $v->branch_id_fk)
                      ->where('customers_id_fk', $v->customers_id_fk)
                      ->where('pick_pack_requisition_code_id_fk', $v->pick_pack_requisition_code_id_fk)
                      ->where('product_id_fk', $v->product_id_fk)
                      ->where('amt_need', $v->amt_need)
                      ->where('amt_get', $v->amt_get)
                      ->where('amt_lot', $v->amt_lot)
                      ->where('amt_remain', $v->amt_remain)
                      ->where('lot_number', $v->lot_number)
                      ->where('lot_expired_date', $v->lot_expired_date)
                      ->where('product_unit_id_fk', $v->product_unit_id_fk)
                      ->where('warehouse_id_fk', $v->warehouse_id_fk)
                      ->where('zone_id_fk', $v->zone_id_fk)
                      ->where('shelf_id_fk', $v->shelf_id_fk)
                      ->where('shelf_floor', $v->shelf_floor)
                      ->get();
                      if($_choose->count() == 0){

                              DB::select(" INSERT IGNORE INTO db_pay_requisition_002_cancel_log (time_pay, business_location_id_fk, branch_id_fk, customers_id_fk, pick_pack_requisition_code_id_fk, product_id_fk, product_name, amt_need, amt_get, amt_lot, amt_remain, product_unit_id_fk, product_unit, lot_number, lot_expired_date, warehouse_id_fk, zone_id_fk, shelf_id_fk, shelf_floor, status_cancel, created_at)
                                VALUES
                               (".$v->time_pay.", ".$v->business_location_id_fk.", ".$v->branch_id_fk.", ".$v->customers_id_fk.", '".$v->pick_pack_requisition_code_id_fk."', ".$v->product_id_fk.", '".$v->product_name."', ".$v->amt_get.", 0 , 0, ".$v->amt_get.", ".$v->product_unit_id_fk.", '".$v->product_unit."', '".$v->lot_number."', '".$v->lot_expired_date."', ".$v->warehouse_id_fk.", ".$v->zone_id_fk.", ".$v->shelf_id_fk.", ".$v->shelf_floor.", ".$v->status_cancel.", '".$v->created_at."') ");

                       }


               }


                 // $ch_status_cancel = DB::select(" SELECT * FROM db_pay_requisition_002 WHERE pick_pack_requisition_code_id_fk='".$request->id."' AND status_cancel in (0) ");
                 // if(count(@$ch_status_cancel)==0 || empty(@$ch_status_cancel)){
                 //    DB::select(" UPDATE db_pay_requisition_001 SET status_sent=1 WHERE pick_pack_requisition_code_id_fk='".$request->id."' ");
                 // }




      }

    }


  public function ajaxSaveChangePurchaseType(Request $request)
    {

      // return($request);
      // dd();
      /*
      agency: "A0002"
      aistockist: "A0001"
      orders_id_fk: "4"
      purchase_type_id_fk: "1"
      _token: "EOhngZfLgYNC9tyHC2nejkNGWryFV0Li6yzn3Kxn"
      */

      if($request->ajax()){

           $r1 =  DB::select(" SELECT * FROM `db_orders` WHERE id=".$request->orders_id_fk." AND purchase_type_id_fk=".$request->purchase_type_id_fk." ");
           if($r1){

            // return ($request->aistockist);
            // dd();

                  // agency = '".($request->agency?$request->agency:0)."'
                    if(!empty($request->aistockist)){
                          DB::select(" UPDATE `db_orders`
                          SET
                          aistockist = '".$request->aistockist."',
                          agency = '".$request->agency."'
                          WHERE id=".$request->orders_id_fk." ");
                    }

               return "ไม่มีการเปลี่ยนแปลง ประเภทการซื้อ";

           }else{

                DB::select(" UPDATE `db_orders`
                  SET purchase_type_id_fk=".$request->purchase_type_id_fk." ,
                     aistockist = '".$request->aistockist."',
                      agency = '".$request->agency."'
                      WHERE id=".$request->orders_id_fk." ");

                return "มีการเปลี่ยนแปลง ประเภทการซื้อ";
           }


      }

    }




  public function ajaxCheckAmt_get_po_product(Request $request)
    {

      if($request->ajax()){

           $r1 = DB::select("  SELECT db_po_supplier_products.*,product_amt-product_amt_receive as remain FROM `db_po_supplier_products`  WHERE po_supplier_id_fk='".$request->po_supplier_products_id_fk."' AND product_id_fk='".$request->product_id_fk."' ");
           // return $request->product_id_fk;
           if($r1){
                if($r1[0]->remain > 0){
                    if($request->amt_get > $r1[0]->remain){
                        return 1;
                    }else{
                        return 0;
                    }
                }else{
                    return 0;
                }
           }else{
            return 0;
           }


      }

    }


  public function ajaxCheckAmt_get_transfer_branch_get_products(Request $request)
    {

      if($request->ajax()){

           $r1 = DB::select("  SELECT db_transfer_branch_get_products.*,product_amt-product_amt_receive as remain FROM `db_transfer_branch_get_products`  WHERE transfer_branch_get_id_fk='".$request->transfer_branch_get_id_fk."' AND product_id_fk='".$request->product_id_fk."' ");
           // return $request->product_id_fk;
           if($r1){
                if($r1[0]->remain > 0){
                    if($request->amt_get > $r1[0]->remain){
                        return 1;
                    }else{
                        return 0;
                    }
                }else{
                    return 0;
                }
           }else{
            return 0;
           }


      }

    }


  public function ajaxGetFilepath(Request $request)
    {

      if($request->ajax()){

          $rs =  DB::select("
                SELECT
                register_files.id,
                register_files.business_location_id_fk,
                register_files.branch_id_fk,
                register_files.customer_id,
                register_files.type,
                register_files.url,
                register_files.file,
                register_files.`comment`,
                register_files.regis_doc_status as status,
                register_files.approve_date,
                register_files.approver,
                register_files.item_checked,
                register_files.created_at,
                register_files.updated_at,
                register_files.deleted_at,
                concat(url,'/',file) AS file_path,
                customers.user_name,
                concat(
                customers.user_name,' : ',
                customers.prefix_name,
                customers.first_name,' ',
                customers.last_name) as cus_name,
                customers.id_card,
                customers_detail.bank_no,
                customers_detail.bank_name
                FROM
                register_files
                LEFT Join customers ON register_files.customer_id = customers.id
                Left Join customers_detail ON register_files.customer_id = customers_detail.customer_id
                where register_files.id= '".$request->id."'
             ");

            return response()->json($rs);

      }

    }



  public function ajaxGetFilepath02(Request $request)
    {

      if($request->ajax()){

         $rs1 =  DB::select("
                SELECT
                *
                FROM
                register_files
                where id = '".$request->id."'
             ");

         // return ($request->id);
         // return $rs1[0]->customer_id;


          $rs =  DB::select("
                SELECT
                register_files.id,
                register_files.business_location_id_fk,
                register_files.branch_id_fk,
                register_files.customer_id,
                register_files.type,
                register_files.url,
                register_files.file,
                register_files.`comment`,
                register_files.regis_doc_status as status,
                register_files.approve_date,
                register_files.approver,
                register_files.item_checked,
                register_files.created_at,
                register_files.updated_at,
                register_files.deleted_at,
                concat(url,'/',file) AS file_path,
                customers.user_name,
                concat(
                customers.user_name,' : ',
                customers.prefix_name,
                customers.first_name,' ',
                customers.last_name) as cus_name,
                customers.id_card,
                customers_detail.bank_no,
                customers_detail.bank_name
                FROM
                register_files
                LEFT Join customers ON register_files.customer_id = customers.id
                Left Join customers_detail ON register_files.customer_id = customers_detail.customer_id
                where register_files.customer_id =(".$rs1[0]->customer_id.") AND register_files.regis_doc_status='1' AND register_files.id<>".$request->id." AND register_files.item_checked=1
             ");

            return response()->json($rs);

      }

    }




  public function ajaxGetRegis_date_doc(Request $request)
    {

      if($request->ajax()){

            $rs =  DB::select("
                SELECT regis_date_doc from customers where id=".$request->customer_id."
             ");

            return response()->json($rs);

      }

    }


    public function ajaxGetCustomer(Request $request)
    {
        if($request->ajax()){

            // สำหรับกรณี Autocomplete
            // $query = \App\Models\Backend\Customers::where('user_name','LIKE',"%$request->txt%")
            // ->orWhere('first_name','LIKE',"%$request->txt%")
            // ->orWhere('last_name','LIKE',"%$request->txt%")
            // ->take(15)->get();
            // $response = array();
            // foreach ($query as $key => $value) {
            //     $response[] = array("value"=>$value->user_name.':'.$value->first_name.' '.$value->last_name,"id"=>$value->id);
            // }
            // return json_encode($response);

            if(empty($request->term)){
                $customers = DB::table('customers')->take(15)->get();
            }else{
                $customers = DB::table('customers')
                ->where('user_name', 'LIKE', '%'.$request->term.'%')
                ->orWhere('first_name','LIKE', '%'.$request->term.'%')
                ->orWhere('last_name','LIKE', '%'.$request->term.'%')
                ->take(15)
                ->orderBy('user_name', 'asc')
                ->get();
            }
            $json_result = [];
            foreach($customers as $k => $v){
                $json_result[] = [
                    'id'    => $v->id,
                    'text'  => $v->user_name.':'.$v->first_name.' '.$v->last_name,
                ];
            }
            return json_encode($json_result);

           }
    }



    public function ajaxGetCustomerDelivery(Request $request)
    {
        if($request->ajax()){

            if(!empty($request->term)){

                if(@\Auth::user()->permission==1){

                    $customers = DB::select("

                        select customers.id,customers.user_name,customers.prefix_name,customers.first_name,customers.last_name from db_delivery
                        left join customers on customers.id=db_delivery.customer_id
                        where
                        customers.user_name like '%".$request->term."%' or
                        customers.first_name like '%".$request->term."%' or
                        customers.last_name like '%".$request->term."%'

                    ");

                }else{

                      $customers = DB::select("

                        select customers.id,customers.user_name,customers.prefix_name,customers.first_name,customers.last_name from db_delivery
                        left join customers on customers.id=db_delivery.customer_id
                        where
                        db_delivery.branch_id_fk=".@\Auth::user()->branch_id_fk." AND
                        (customers.user_name like '%".$request->term."%' or
                        customers.first_name like '%".$request->term."%' or
                        customers.last_name like '%".$request->term."%')

                    ");

                }

            }
            $json_result = [];
            foreach($customers as $k => $v){
                $json_result[] = [
                    'id'    => $v->id,
                    'text'  => $v->user_name.' : '.$v->prefix_name.$v->first_name.' '.$v->last_name,
                ];
            }
            return json_encode($json_result);

           }
    }

    public function ajaxGetCustomerForFrontstore(Request $request)
    {
        if($request->ajax()){

            if(empty($request->term)){
                $customers = DB::table('customers')->take(15)->get();
            }else{
                $customers = DB::table('customers')
                // ->whereNotNull('regis_date_doc')
                ->where('user_name', 'LIKE', '%'.$request->term.'%')
                ->orWhere('first_name','LIKE', '%'.$request->term.'%')
                ->orWhere('last_name','LIKE', '%'.$request->term.'%')
                ->take(15)
                ->orderBy('user_name', 'asc')
                ->get();
            }
            $json_result = [];
            foreach($customers as $k => $v){
                $json_result[] = [
                    'id'    => $v->id,
                    'text'  => $v->user_name.':'.$v->first_name.' '.$v->last_name,
                ];
            }
            return json_encode($json_result);

           }
    }


    public function ajaxGetCustomerAistockist(Request $request)
    {
        if($request->ajax()){

            if(empty($request->term)){
                $customers = DB::table('customers')->take(15)->get();
            }else{
                // $customers = DB::table('customers')
                // ->where('user_name', 'LIKE', '%'.$request->term.'%')
                // ->orWhere('first_name','LIKE', '%'.$request->term.'%')
                // ->orWhere('last_name','LIKE', '%'.$request->term.'%')
                // ->where('aistockist_status', '1')
                // ->take(15)
                // ->get();

                $customers = DB::select(" select id,user_name,first_name,last_name from customers where aistockist_status='1' and (user_name LIKE '%".$request->term."%' OR first_name LIKE '%".$request->term."%' OR last_name LIKE '%".$request->term."%'); ");

            }
            $json_result = [];
            foreach($customers as $k => $v){
                $json_result[] = [
                    'id'    => $v->id,
                    'text'  => $v->user_name.':'.$v->first_name.' '.$v->last_name,
                ];
            }
            return json_encode($json_result);

           }
    }



    public function ajaxGetCustomerAgency(Request $request)
    {
        if($request->ajax()){

            if(empty($request->term)){
                 $customers = DB::table('customers')->take(15)->get();
            }else{
                 $customers = DB::select(" select id,user_name,first_name,last_name from customers where agency_status='1' and (user_name LIKE '%".$request->term."%' OR first_name LIKE '%".$request->term."%' OR last_name LIKE '%".$request->term."%'); ");
            }
            $json_result = [];
            foreach($customers as $k => $v){
                $json_result[] = [
                    'id'    => $v->id,
                    'text'  => $v->user_name.':'.$v->first_name.' '.$v->last_name,
                ];
            }
            return json_encode($json_result);

           }
    }



     public function fnSearchIntroduceId($user_name) {

            $sql =  DB::select("
                SELECT * FROM customers where user_name ='$user_name' UNION
                SELECT * FROM customers where upline_id ='$user_name'
             ");
            $arr = [];
            foreach ($sql as $key => $row) {
                if ($row->line_type=="AA" || !$row) break;
                // if (!$row) break;
                 DB::select("  INSERT IGNORE INTO `temp_cus_ai_cash`
                    (`admin_id_login`, `user_name`, `introduce_id`, `line_type`, `ai_cash`, `cus_id_fk`, `prefix_name`, `first_name`, `last_name`, `upline_id`) VALUES
                    ('".\Auth::user()->id."', '".$row->user_name."', '".$row->introduce_id."', '".$row->line_type."', '".$row->ai_cash."', '".$row->id."', '".$row->prefix_name."', '".$row->first_name."', '".$row->last_name."', '".$row->upline_id."') ");
                 // $this->fnSearchIntroduceId($row->introduce_id);
                 $this->fnSearchIntroduceId($row->upline_id);
            }

    }


    public function ajaxGetCustomerForAicashSelect(Request $request)
    {
        if($request->ajax()){

            $customers = DB::table('temp_cus_ai_cash')
            ->where('user_name', 'LIKE', '%'.$request->term.'%')
            ->orWhere('first_name','LIKE', '%'.$request->term.'%')
            ->orWhere('last_name','LIKE', '%'.$request->term.'%')
            ->take(15)
            ->groupBy('user_name')
            ->orderBy('user_name', 'asc')
            ->get();
            $json_result = [];
            foreach($customers as $k => $v){
                $json_result[] = [
                    'id'    => $v->cus_id_fk,
                    'text'  => $v->user_name.':'.$v->first_name.' '.$v->last_name.' ('.$v->ai_cash.')',
                ];
            }
            return json_encode($json_result);

           }
    }




   public function ajaxGetAicash(Request $request)
    {

        DB::select(" UPDATE db_orders set member_id_aicash=".$request->customer_id." where id=".$request->frontstore_id_fk." ");
        $rs =  DB::select(" select ai_cash,user_name,prefix_name,first_name,last_name from customers where id=".$request->customer_id." ");
        return response()->json($rs);
    }



   public function ajaxSearchMemberAicash(Request $request)
    {

       // $resule = LineModel::check_line_backend($request->username_buy,$request->username_check);
       // $resule = LineModel::check_line_backend('A567838','A10263');
       $resule = LineModel::check_line_backend('A567838','A10263');
       // return($resule);
       if (@$resule['status'] == 'success') {

            if (@$resule['data']) {
               return $resule['data']->id;
            // }

                 // $customers = DB::table('customers')
                 //        ->where('id', $resule['data']->id)
                 //        ->get();
                 //        $json_result = [];
                 //        foreach($customers as $k => $v){
                            // $json_result[] = [
                            //     'id'    => $resule['data']->id,
                            //     'text'  => $resule['data']->user_name.':'.$resule['data']->first_name.' '.$resule['data']->last_name.' ('.$resule['data']->ai_cash.')',
                            // ];
                        // }
                        // return json_encode($json_result);

             }

       }

    }


    public function ajaxGetCustomerCode(Request $request)
    {
        if($request->ajax()){

            if(empty($request->term)){
                $customers = DB::table('customers')->take(15)->get();
            }else{
                $customers = DB::table('customers')
                ->where('user_name', 'LIKE', '%'.$request->term.'%')
                ->orWhere('first_name','LIKE', '%'.$request->term.'%')
                ->orWhere('last_name','LIKE', '%'.$request->term.'%')
                ->take(15)
                ->orderBy('user_name', 'asc')
                ->get();
            }
            $json_result = [];
            foreach($customers as $k => $v){
                $json_result[] = [
                    'id'    => $v->user_name,
                    'text'  => $v->user_name.':'.$v->first_name.' '.$v->last_name,
                ];
            }
            return json_encode($json_result);

           }
    }




    public function ajaxGetCustomerCodeOnly(Request $request)
    {
        if($request->ajax()){

            if(empty($request->term)){
                $customers = DB::table('customers')->take(15)->get();
            }else{
                $customers = DB::table('customers')
                ->where('user_name', 'LIKE', '%'.$request->term.'%')
               // ->orWhere('first_name','LIKE', '%'.$request->term.'%')
              //  ->orWhere('last_name','LIKE', '%'.$request->term.'%')
                ->take(15)
                ->orderBy('user_name', 'asc')
                ->get();
            }
            $json_result = [];
            foreach($customers as $k => $v){
                $json_result[] = [
                    'id'    => $v->id,
                    'text'  => $v->user_name,
                ];
            }
            return json_encode($json_result);

           }
    }


    public function ajaxGetCustomerNameOnly(Request $request)
    {
        if($request->ajax()){

            if(empty($request->term)){
                $customers = DB::table('customers')->take(15)->get();
            }else{
                $customers = DB::table('customers')
                // ->where('user_name', 'LIKE', '%'.$request->term.'%')
                ->where('first_name','LIKE', '%'.$request->term.'%')
                // ->orWhere('last_name','LIKE', '%'.$request->term.'%')
                ->take(1000)
                ->orderBy('first_name', 'asc')
                ->orderBy('last_name', 'asc')
                ->get();
            }
            $json_result = [];
            foreach($customers as $k => $v){
                $json_result[] = [
                    'id'    => $v->id,
                    'text'  => $v->first_name.' '.$v->last_name,
                ];
            }
            return json_encode($json_result);

           }
    }

    public function ajaxGetBusinessName(Request $request)
    {
        if($request->ajax()){

            if(empty($request->term)){
                $customers = DB::table('customers')->take(15)->get();
            }else{
                $customers = DB::table('customers')
                ->where('business_name', 'LIKE', '%'.$request->term.'%')
                ->take(15)
                ->groupBy('business_name')
                ->orderBy('business_name', 'asc')
                ->get();
            }
            $json_result = [];
            foreach($customers as $k => $v){
                $json_result[] = [
                    'id'    => $v->business_name,
                    'text'  => $v->business_name,
                ];
            }
            return json_encode($json_result);

           }
    }


    public function ajaxGetIntroduce_id(Request $request)
    {
        if($request->ajax()){

            if(empty($request->term)){
                $customers = DB::table('customers')->take(15)->get();
            }else{
                // เอารหัสที่ filter มาหาใน customers ว่ามี user_name นั้นๆ หรือไม่
                $customers = DB::table('customers')
                ->where('introduce_id', 'LIKE', '%'.$request->term.'%')
                ->take(15)
                ->get();

                  $json_result = [];

                if($customers){

                        //  $customers_introduce = DB::table('customers')
                        // ->where('introduce_id', @$customers[0]->introduce_id)
                        // ->groupBy('introduce_id')
                        // ->orderBy('introduce_id', 'asc')
                        // ->get();

                                foreach($customers as $k => $v){
                                    $json_result[] = [
                                        'id'    => @$v->introduce_id,
                                        'text'  => @$v->introduce_id,
                                    ];
                                }


                        }
                         return json_encode($json_result);
              }


           }
    }




    public function ajaxGetUpline_id(Request $request)
    {
        if($request->ajax()){

            if(empty($request->term)){
                $customers = DB::table('customers')->take(15)->get();
            }else{
                // เอารหัสที่ filter มาหาใน customers ว่ามี user_name นั้นๆ หรือไม่
                $customers = DB::table('customers')
                ->where('upline_id', 'LIKE', '%'.$request->term.'%')
                ->take(15)
                ->get();

                  $json_result = [];

                if($customers){

                        //  $customers_introduce = DB::table('customers')
                        // ->where('introduce_id', @$customers[0]->introduce_id)
                        // ->groupBy('introduce_id')
                        // ->orderBy('introduce_id', 'asc')
                        // ->get();

                                foreach($customers as $k => $v){
                                    $json_result[] = [
                                        'id'    => @$v->upline_id,
                                        'text'  => @$v->upline_id,
                                    ];
                                }


                        }
                         return json_encode($json_result);
              }


           }
    }

    public function ajaxCancelOrderBackend(Request $request)
    {
      $Orders = \App\Models\Backend\Frontstore::where('id',$request->id)->get();
      if($request->ajax()){

          // check กรณีคูปอง ให้ return pro_status กลับไปเป็น 1
            $Frontstorelist = \App\Models\Backend\Frontstorelist::where('frontstore_id_fk',$request->id)->get();
            foreach ($Frontstorelist as $key => $v) {

                 if($v->promotion_code!=""){
                        DB::select(" UPDATE `db_promotion_cus` SET `pro_status`='1',`used_user_name`=NULL,`used_date`=NULL WHERE (`promotion_code`='".$v->promotion_code."') ");
                  }

            }

           // $cancel = \App\Http\Controllers\Frontend\Fc\cancel_order($r[0]->id, \Auth::user()->id , 'admin', 'admin');
           // dd($cancel);
  // `approve_status` int(11) DEFAULT '0' COMMENT '1=รออนุมัติ,2=อนุมัติแล้ว,3=รอชำระ,4=รอจัดส่ง,5=ยกเลิก,6=ไม่อนุมัติ,9=สำเร็จ(ถึงขั้นตอนสุดท้าย ส่งของให้ลูกค้าเรียบร้อย) > Ref>dataset_approve_status>id',
  // `order_status_id_fk` int(11) DEFAULT NULL COMMENT '(ยึดตาม* dataset_order_status )',


      // return $Orders[0]->approve_status;
      // dd();
      if($Orders[0]->approve_status==0){
        $resule = \App\Http\Controllers\Frontend\Fc\DeleteOrderController::delete_order($request->id);

       }else{
          //DB::select(" UPDATE db_orders SET approve_status=5,order_status_id_fk=8 where id=$request->id ");
          $resule = CancelOrderController::cancel_order($request->id, @\Auth::user()->id , 0, 'admin');


      }

      DB::select(" DELETE FROM `db_delivery` where orders_id_fk=$request->id ");


      // $resule = CancelOrderController::cancel_order($rs->cancel_order_id, $customer_id, 1, 'customer');

      // dd($resule);


      // return response()->json(\App\Models\Alert::Msg('success'));
       // return redirect()->to(url("backend/frontstore"));
       }
    }

    public function ajaxDelUser(Request $request)
    {
      // return ($request);
      if($request->ajax()){
           DB::select(" DELETE FROM ck_users_admin where id=$request->id ");
       }
    }


    public function ajaxDelPickpack(Request $request)
    {
      // return ($request);
      if($request->ajax()){

         $id = $request->id;

          $delivery_id_fk = DB::select(" SELECT * FROM db_pick_pack_packing WHERE packing_code_id_fk=$id ");
          $arr_delivery_id_fk = [];
          foreach ($delivery_id_fk as $key => $value) {
              array_push($arr_delivery_id_fk,$value->delivery_id_fk);
          }
          $arr_delivery_id_fk = implode(',', $arr_delivery_id_fk);

          DB::update(" UPDATE db_delivery SET status_pick_pack='0' WHERE id in (".$arr_delivery_id_fk.")");

          DB::update(" DELETE FROM db_pick_pack_packing WHERE packing_code = $id  ");
          DB::update(" ALTER TABLE db_pick_pack_packing AUTO_INCREMENT=1; ");

          $sRow = \App\Models\Backend\Pick_packPackingCode::find($id);
          if( $sRow ){
            $sRow->forceDelete();
            DB::update(" ALTER TABLE db_pick_pack_packing_code AUTO_INCREMENT=1; ");

          }


       }
    }



    public function ajaxDelDelivery(Request $request)
    {
      // return ($request);
      if($request->ajax()){

          $id = $request->id;

          $DP = DB::table('db_delivery_packing')->where('packing_code_id_fk',$id)->get();
          foreach ($DP as $key => $value) {
              DB::update(" UPDATE db_delivery SET status_pack='0',`packing_code`='0', `packing_code_desc`=NULL WHERE id = ".$value->delivery_id_fk."  ");
          }

          DB::update(" DELETE FROM db_delivery_packing WHERE packing_code_id_fk = $id  ");
          DB::select(" ALTER TABLE db_delivery_packing AUTO_INCREMENT=1; ");

          $sRow = \App\Models\Backend\DeliveryPackingCode::find($id);
          if( $sRow ){
            $sRow->forceDelete();
            DB::select(" ALTER TABLE db_delivery_packing_code AUTO_INCREMENT=1; ");
          }


       }
    }


    public function ajaxDelPromoProduct(Request $request)
    {
      // return ($request);
      if($request->ajax()){
           DB::select(" DELETE FROM `promotions_products` where id=$request->id ");
       }
    }


    public function ajaxDeLProductOrderBackend(Request $request)
    {
      // return ($request);
      // dd();

      if($request->ajax()){

            // check กรณีคูปอง ให้ return pro_status กลับไปเป็น 1
            $Frontstorelist = \App\Models\Backend\Frontstorelist::find($request->id);
            if($Frontstorelist->promotion_code){
                DB::select(" UPDATE `db_promotion_cus` SET `pro_status`='1',`used_user_name`=NULL,`used_date`=NULL WHERE (`promotion_code`='".$Frontstorelist->promotion_code."') ");
            }


          DB::select(" DELETE FROM `db_order_products_list` where id=$request->id ");
          // $sumprice = DB::select(" SELECT sum(total_price) as sumprice FROM `db_order_products_list` where frontstore_id_fk=$request->id ");

          // $sumprice = $sumprice[0]->sumprice>0?$sumprice[0]->sumprice:0;

          // DB::select(" UPDATE `db_orders` SET `cash_price`='$sumprice' WHERE (`id`=$request->frontstore_id_fk) ");
          // clear เลย ให้ใส่ใหม่
           $id=   @$request->frontstore_id_fk;

           $sFrontstoreDataTotal = DB::select(" select SUM(total_price) as total,SUM(total_pv) as total_pv from db_order_products_list WHERE frontstore_id_fk=$id GROUP BY frontstore_id_fk ");
           // dd($sFrontstoreDataTotal);
           if($sFrontstoreDataTotal){
              $vat = floatval(@$sFrontstoreDataTotal[0]->total) - (floatval(@$sFrontstoreDataTotal[0]->total)/1.07) ;
              $vat = $vat > 0  ? $vat : 0 ;
              $product_value = str_replace(",","",floatval(@$sFrontstoreDataTotal[0]->total) - $vat) ;
              $total = @$sFrontstoreDataTotal[0]->total>0?@$sFrontstoreDataTotal[0]->total:0;
              $total_pv = @$sFrontstoreDataTotal[0]->total_pv>0?@$sFrontstoreDataTotal[0]->total_pv:0;
              DB::select(" UPDATE db_orders SET product_value=".($product_value).",tax=".($vat).",sum_price=".($total).",pv_total=".($total_pv)." WHERE id=$id ");
              DB::select(" UPDATE db_orders SET pv_total=0 WHERE pv_total is null; ");
            }else{
              DB::select(" UPDATE db_orders SET product_value=0,tax=0,sum_price=0 WHERE id=$id  ");
            }



/*
1 เงินโอน
2 บัตรเครดิต
3 Ai-Cash
4 Gift Voucher
5 เงินสด
6 เงินสด + Ai-Cash
7 เครดิต + เงินสด
8 เครดิต + เงินโอน
9 เครดิต + Ai-Cash
10  เงินโอน + เงินสด
11  เงินโอน + Ai-Cash
12  Gift Voucher + เงินโอน
13  Gift Voucher + บัตรเครดิต
14  Gift Voucher + Ai-Cash
15  PromptPay
16  TrueMoney
17  Gift Voucher + PromptPay
18  Gift Voucher + TrueMoney
*/

       DB::select(" UPDATE db_orders SET

            pay_type_id_fk='0',

            aicash_price='0',
            member_id_aicash='0',
            transfer_price='0',
            credit_price='0',

            charger_type='0',
            fee='0',
            fee_amt='0',
            sum_credit_price='0',
            account_bank_id='0',

            transfer_money_datetime=NULL ,
            file_slip=NULL,

            total_price='0',
            cash_price='0',
            cash_pay='0'

            WHERE id=$request->frontstore_id_fk ");


          // DB::select(" UPDATE `db_orders` SET `cash_price`='0' WHERE `id`=$request->frontstore_id_fk and pay_type_id_fk=5 ");

       }
    }


    public function ajaxCourseCheckRegis(Request $request)
    {
      // return ($request);
      if($request->ajax()){
          $CourseCheckRegis = CourseCheckRegis::cart_check_register($request->id_course,$request->amt_apply ,$request->user_name);
          return $CourseCheckRegis['status'];

       }
    }


    public function fnCheckStock($branch_id_fk,$product_id_fk,$amt,$lot_number,$lot_expired_date,$warehouse_id_fk,$zone_id_fk,$shelf_id_fk,$shelf_floor)
    {

       // return $branch_id_fk.":".$product_id_fk.":".$amt.":".$lot_number.":".$lot_expired_date.":".$warehouse_id_fk.":".$zone_id_fk.":".$shelf_id_fk.":".$shelf_floor;

                  $_check=DB::table('db_stocks')
                      ->where('branch_id_fk', @$branch_id_fk?@$branch_id_fk:0)
                      ->where('product_id_fk', @$product_id_fk?@$product_id_fk:0)
                      ->where('amt','>=', @$amt?@$amt:9999999999999)
                      ->where('lot_number', @$lot_number?@$lot_number:NULL)
                      ->where('lot_expired_date', @$lot_expired_date?@$lot_expired_date:NULL)
                      ->where('warehouse_id_fk', @$warehouse_id_fk?@$warehouse_id_fk:0)
                      ->where('zone_id_fk', @$zone_id_fk?@$zone_id_fk:0)
                      ->where('shelf_id_fk', @$shelf_id_fk?@$shelf_id_fk:0)
                      ->where('shelf_floor', @$shelf_floor?@$shelf_floor:0)
                      ->get();
                      if($_check->count() > 0){
                        return 1;
                      }else{
                        return 0;
                      }


    }


    public function ajaxCheckDescGiftvoucher(Request $request)
    {

          $_check=DB::table('db_giftvoucher_code')
              ->where('descriptions', $request->descriptions)
              ->get();
              if($_check->count() > 0){
                return 1;
              }else{
                return 0;
              }

    }



    public function ajaxProcessStockcard(Request $request)
    {
        // return  $request ;


        if(isset($request->business_location_id_fk)){
            $w01 = " AND  business_location_id_fk =".$request->business_location_id_fk."  " ;
        }else{
            $w01 = '';
        }

        if(isset($request->branch_id_fk)){
            $w02 = " AND  branch_id_fk ='".$request->branch_id_fk."'  " ;
        }else{
            $w02 = '';
        }

        if(isset($request->product_id_fk)){
            $w03 = " AND  product_id_fk ='".$request->product_id_fk."'  " ;
        }else{
            $w03 = '';
        }

        if(isset($request->lot_number)){
            $t = explode(":",$request->lot_number);
            // $w04 = " AND  lot_number ='".$t[0]."' AND lot_expired_date = '".$t[1]."'  " ;
            $w04 = " AND  lot_number ='".$t[0]."' " ;
        }else{
            $w04 = '';
        }

        if(isset($request->start_date) && isset($request->end_date)){
            $w05 = " and date(db_stock_movement.doc_date) BETWEEN '".$request->start_date."' AND '".$request->end_date."'  " ;
            $w052 = " and date(db_stocks.lot_expired_date) >= CURDATE()  " ;
        }else{
            $w05 = '';
            $w052 = '';
        }


        $temp_db_stock_card = "temp_db_stock_card".\Auth::user()->id;
        DB::select(" DROP TABLE IF EXISTS $temp_db_stock_card ; ");
        DB::select(" CREATE TABLE $temp_db_stock_card LIKE db_stock_card ");


        if($request->ajax()){

       //   DB::select(" INSERT INTO $temp_db_stock_card (details,amt_in) VALUES ('ยอดคงเหลือยกมา',".$d2.") ") ;
        $amt_balance_stock =  DB::select(" SELECT sum(amt) as amt FROM db_stocks
            WHERE 1
            $w01
            $w02
            $w03
            $w052
            GROUP BY product_id_fk

             ") ;
        $amt_balance_stock = @$amt_balance_stock[0]->amt ? $amt_balance_stock[0]->amt : 0 ;

        // return  $amt_balance_stock;

        $amt_in_out =  DB::select(" SELECT
                SUM(CASE WHEN in_out=2 THEN amt*-1 ELSE amt END
                ) as amt
                FROM db_stock_movement
            WHERE 1
                $w01
                $w02
                $w03
                $w05
            ") ;
        $amt_in_out = @$amt_in_out[0]->amt ? $amt_in_out[0]->amt : 0 ;
        // return $amt_in_out;
        $check = 0;
        // if($amt_in_out>$amt_balance_stock){
        //     $amt_balance_stock = $amt_balance_stock + $amt_in_out;
        //     $check = 1;
        // }else{
            $amt_balance_stock = $amt_balance_stock - $amt_in_out;
        // }

        // $amt_balance_stock = 1044;
        // return $amt_balance_stock;

        // $amt_in_stock =  DB::select(" SELECT SUM(amt) as amt FROM db_stock_movement
        //     WHERE 1
        //         $w01
        //         $w02
        //         $w03
        //         $w05
        //     AND in_out=1
        //     GROUP BY product_id_fk,doc_no

        //     ") ;

        // $amt_in_stock = @$amt_in_stock[0]->amt ? $amt_in_stock[0]->amt : 0 ;

        // $amt_out_stock =  DB::select(" SELECT SUM(amt) as amt FROM db_stock_movement

        //     WHERE 1
        //         $w01
        //         $w02
        //         $w03
        //         $w05
        //     AND in_out=2
        //     GROUP BY product_id_fk,doc_no

        //     ") ;

        // $amt_out_stock = @$amt_out_stock[0]->amt ? $amt_out_stock[0]->amt : 0 ;

        // $num = $amt_in_stock - $amt_out_stock ;

        // // return $amt_balance_stock;
        // // return $amt_in_stock;
        // // return $amt_out_stock;

        // if($num>=0){
        //     $amt_balance_stock = $amt_balance_stock - $num ;
        // }else{
        //     $amt_balance_stock = $amt_balance_stock + $num ;
        // }

        if($amt_balance_stock < 0 ) {
            // $amt_balance_stock = 0 ;
            $txt = "* ถ้ายอดยกมา ติดลบ อาจเกิดจากข้อมูลทดสอบ ปรับยอดคลังอีกครั้ง ";
            // $txt = "ยอดคงเหลือยกมา";
        } else {
            $txt = "ยอดคงเหลือยกมา";
        }

          // DB::select(" INSERT INTO $temp_db_stock_card (details) VALUES ('ยอดคงเหลือยกมา') ") ;
          DB::select(" INSERT INTO $temp_db_stock_card (details,amt_in) VALUES ('".$txt."',".$amt_balance_stock.") ") ;

        // return "to here" ;
        // dd();

// กรณีโอนย้ายภายในสาขา ไม่ต้องดึงมาแสดง เพราะยอดเท่าเดิม
          $Data = DB::select("
                SELECT * FROM db_stock_movement where SUBSTRING(doc_no, 1, 2)<>'TR'
                $w03
                GROUP BY product_id_fk,doc_no,in_out
          ");


        // return  $Data ;
        // return "to here" ;
        // dd();

          foreach ($Data as $key => $value) {

               $insertData = array(
                  "ref_inv" =>  @$value->doc_no?$value->doc_no:NULL,
                  "action_date" =>  @$value->doc_date?$value->doc_date:NULL,
                  "action_user" =>  @$value->action_user?$value->action_user:NULL,
                  "approver" =>  @$value->approver?$value->approver:NULL,
                  "approve_date" =>  @$value->approve_date?$value->approve_date:NULL,
                  "sender" =>  @$value->sender?$value->sender:NULL,
                  "sent_date" =>  @$value->sent_date?$value->sent_date:NULL,
                  "who_cancel" =>  @$value->who_cancel?$value->who_cancel:NULL,
                  "cancel_date" =>  @$value->cancel_date?$value->cancel_date:NULL,
                  "business_location_id_fk" =>  @$value->business_location_id_fk?$value->business_location_id_fk:0,
                  "branch_id_fk" =>  @$value->branch_id_fk?$value->branch_id_fk:0,
                  "product_id_fk" =>  @$value->product_id_fk?$value->product_id_fk:0,
                  "lot_number" =>  @$value->lot_number?$value->lot_number:NULL,
                  "details" =>  @$value->note?$value->note:NULL,
                  "amt_in" =>  @$value->in_out==1?$value->amt:0,
                  "amt_out" =>  @$value->in_out==2?$value->amt:0,

                  "warehouse_id_fk" =>  @$value->warehouse_id_fk>0?$value->warehouse_id_fk:0,
                  "zone_id_fk" =>  @$value->zone_id_fk>0?$value->zone_id_fk:0,
                  "shelf_id_fk" =>  @$value->shelf_id_fk>0?$value->shelf_id_fk:0,
                  "shelf_floor" =>  @$value->shelf_floor>0?$value->shelf_floor:0,

                  "created_at" =>@$value->dd?$value->dd:NULL
              );

                AjaxController::insertStockCard($insertData);

            }

            // if($amt_balance_stock_02<0){
            //      DB::select(" INSERT INTO $temp_db_stock_card (details,amt_in) VALUES ('หมายเหตุ',0) ") ;
            // }

                if($check==1){
                      $txt = "ปรับยอด";
                      // DB::select(" INSERT INTO $temp_db_stock_card (details,amt_out) VALUES ('".$txt."',932) ") ;
                }


            return "db_stock_card => success";



        }
    }



    public function ajaxProcessStockcard_01(Request $request)
    {
        // return  $request ;


        $Stock = \App\Models\Backend\Check_stock::where('id',$request->db_stocks_id)->get();


        if(isset($Stock[0]->business_location_id_fk)){
            $w01 = " AND  business_location_id_fk =".$Stock[0]->business_location_id_fk."  " ;
        }else{
            $w01 = '';
        }

        if(isset($Stock[0]->branch_id_fk)){
            $w02 = " AND  branch_id_fk ='".$Stock[0]->branch_id_fk."'  " ;
        }else{
            $w02 = '';
        }

        if(isset($Stock[0]->product_id_fk)){
            $w03 = " AND  product_id_fk ='".$Stock[0]->product_id_fk."'  " ;
        }else{
            $w03 = '';
        }

        if(isset($Stock[0]->lot_number)){
            $w04 = " AND  lot_number ='".$Stock[0]->lot_number."' " ;
        }else{
            $w04 = '';
        }

        if(isset($request->start_date) && isset($request->end_date)){
            $w05 = " and date(db_stock_movement.doc_date) BETWEEN '".$request->start_date."' AND '".$request->end_date."'  " ;
            $w052 = " and date(db_stocks.lot_expired_date) >= CURDATE()  " ;
        }else{
            $w05 = '';
            $w052 = '';
        }

        if(isset($Stock[0]->lot_expired_date)){
            $w06 = " AND  lot_expired_date ='".$Stock[0]->lot_expired_date."' " ;
        }else{
            $w06 = '';
        }

        if(isset($Stock[0]->warehouse_id_fk)){
            $w07 = " AND  warehouse_id_fk ='".$Stock[0]->warehouse_id_fk."' " ;
        }else{
            $w07 = '';
        }

        if(isset($Stock[0]->zone_id_fk)){
            $w08 = " AND  zone_id_fk ='".$Stock[0]->zone_id_fk."' " ;
        }else{
            $w08 = '';
        }

        if(isset($Stock[0]->shelf_id_fk)){
            $w09 = " AND  shelf_id_fk ='".$Stock[0]->shelf_id_fk."' " ;
        }else{
            $w09 = '';
        }


        if(isset($Stock[0]->shelf_floor)){
            $w10 = " AND  shelf_floor ='".$Stock[0]->shelf_floor."' " ;
        }else{
            $w10 = '';
        }


        $temp_db_stock_card = "temp_db_stock_card".\Auth::user()->id;
        DB::select(" DROP TABLE IF EXISTS $temp_db_stock_card ; ");
        DB::select(" CREATE TABLE $temp_db_stock_card LIKE db_stock_card ");


        if($request->ajax()){

            // return $w01 ;
            // return $w02 ;
            // return $w03 ;
            // return $w052 ;
            // return $w06 ;

       //   DB::select(" INSERT INTO $temp_db_stock_card (details,amt_in) VALUES ('ยอดคงเหลือยกมา',".$d2.") ") ;
        $amt_balance_stock =  DB::select(" SELECT sum(amt) as amt FROM db_stocks
            WHERE 1
            $w01
            $w02
            $w03
            $w04
            $w052
            $w06
            $w07
            $w08
            $w09
            $w10
            GROUP BY product_id_fk

             ") ;
        $amt_balance_stock = @$amt_balance_stock[0]->amt ? $amt_balance_stock[0]->amt : 0 ;

        // return  $amt_balance_stock;

        $amt_in_out =  DB::select(" SELECT
                SUM(CASE WHEN in_out=2 THEN amt*-1 ELSE amt END
                ) as amt
                FROM db_stock_movement
            WHERE 1
                $w01
                $w02
                $w03
                $w04
                $w05
                $w06
                $w07
                            $w08
            $w09
            $w10
            ") ;
        $amt_in_out = @$amt_in_out[0]->amt ? $amt_in_out[0]->amt : 0 ;
        // return $amt_in_out;
        $check = 0;
        // if($amt_in_out>$amt_balance_stock){
        //     $amt_balance_stock = $amt_balance_stock + $amt_in_out;
        //     $check = 1;
        // }else{
            $amt_balance_stock = $amt_balance_stock - $amt_in_out;
        // }



        if($amt_balance_stock < 0 ) {
            // $amt_balance_stock = 0 ;
            $txt = "* ถ้ายอดยกมา ติดลบ อาจเกิดจากข้อมูลทดสอบ ปรับยอดคลังอีกครั้ง ";
            // $txt = "ยอดคงเหลือยกมา";
        } else {
            $txt = "ยอดคงเหลือยกมา";
        }

        // return $amt_balance_stock;

          // DB::select(" INSERT INTO $temp_db_stock_card (details) VALUES ('ยอดคงเหลือยกมา') ") ;
          DB::select(" INSERT INTO $temp_db_stock_card (details,amt_in) VALUES ('".$txt."',".$amt_balance_stock.") ") ;

        // return "to here" ;
        // dd();


          $Data = DB::select("
                SELECT * FROM db_stock_movement where 1
                $w01
                $w02
                $w03
                $w04
                $w05
                $w06
                $w07
                            $w08
            $w09
            $w10
                GROUP BY product_id_fk,doc_no,in_out
          ");


        // return  $Data ;
        // return "to here" ;
        // dd();

          foreach ($Data as $key => $value) {

               $insertData = array(
                  "ref_inv" =>  @$value->doc_no?$value->doc_no:NULL,
                  "action_date" =>  @$value->doc_date?$value->doc_date:NULL,
                  "action_user" =>  @$value->action_user?$value->action_user:NULL,
                  "approver" =>  @$value->approver?$value->approver:NULL,
                  "approve_date" =>  @$value->approve_date?$value->approve_date:NULL,
                  "sender" =>  @$value->sender?$value->sender:NULL,
                  "sent_date" =>  @$value->sent_date?$value->sent_date:NULL,
                  "who_cancel" =>  @$value->who_cancel?$value->who_cancel:NULL,
                  "cancel_date" =>  @$value->cancel_date?$value->cancel_date:NULL,
                  "business_location_id_fk" =>  @$value->business_location_id_fk?$value->business_location_id_fk:0,
                  "branch_id_fk" =>  @$value->branch_id_fk?$value->branch_id_fk:0,
                  "product_id_fk" =>  @$value->product_id_fk?$value->product_id_fk:0,
                  "lot_number" =>  @$value->lot_number?$value->lot_number:NULL,
                  "details" => (@$value->note?$value->note:NULL).' '.(@$value->note2?$value->note2:NULL),
                  "amt_in" =>  @$value->in_out==1?$value->amt:0,
                  "amt_out" =>  @$value->in_out==2?$value->amt:0,
                  "created_at" =>@$value->dd?$value->dd:NULL
              );

                AjaxController::insertStockCard($insertData);

            }

            // if($amt_balance_stock_02<0){
            //      DB::select(" INSERT INTO $temp_db_stock_card (details,amt_in) VALUES ('หมายเหตุ',0) ") ;
            // }

                if($check==1){
                      $txt = "ปรับยอด";
                      // DB::select(" INSERT INTO $temp_db_stock_card (details,amt_out) VALUES ('".$txt."',932) ") ;
                }


            return "db_stock_card => success";



        }
    }


   public function insertStockCard($data){
        $temp_db_stock_card = "temp_db_stock_card".\Auth::user()->id;
        DB::table($temp_db_stock_card)->insert($data);
   }


   public function insertStockCard_01($data){
        $temp_db_stock_card_01 = "temp_db_stock_card_01".\Auth::user()->id;
        DB::table($temp_db_stock_card_01)->insert($data);
   }


  public function truncateStockMovement(){
        DB::select(" TRUNCATE db_stock_movement_tmp; ");
        DB::select(" TRUNCATE db_stock_movement; ");
   }

  public function insertStockMovement($data){
        DB::table('db_stock_movement_tmp')->insertOrIgnore($data);
   }


  public function insertStockMovement_From_db_general_receive(Request $request)
    {

      if($request->ajax()){

        // ดึงจากตารางรับเข้า > db_general_receive
        $Data = DB::select("
                SELECT db_general_receive.business_location_id_fk,
                (
                CASE WHEN loan_ref_number='-' or loan_ref_number is null THEN CONCAT('CODE',db_general_receive.id) ELSE loan_ref_number END
                ) as doc_no
                ,db_general_receive.created_at as doc_date,branch_id_fk,
                db_general_receive.product_id_fk, db_general_receive.lot_number, lot_expired_date, db_general_receive.amt,1 as 'in_out',product_unit_id_fk,warehouse_id_fk,zone_id_fk,shelf_id_fk,shelf_floor,approve_status as status,
                concat('รับเข้า ',dataset_product_in_cause.txt_desc) as note, db_general_receive.created_at as dd,
                db_general_receive.recipient as action_user,db_general_receive.approver as approver,db_general_receive.updated_at as approve_date,db_general_receive.description  as note2,
                (CASE WHEN product_status_id_fk=2 THEN 'สินค้าชำรุดเสียหาย' ELSE '' END) as note3
                FROM
                db_general_receive
                left Join dataset_product_in_cause ON db_general_receive.product_in_cause_id_fk = dataset_product_in_cause.id
          ");

          foreach ($Data as $key => $value) {

               $insertData = array(
                  "doc_no" =>  @$value->doc_no?$value->doc_no:NULL,
                  "doc_date" =>  @$value->doc_date?$value->doc_date:NULL,
                  "business_location_id_fk" =>  @$value->business_location_id_fk?$value->business_location_id_fk:0,
                  "branch_id_fk" =>  @$value->branch_id_fk?$value->branch_id_fk:0,
                  "product_id_fk" =>  @$value->product_id_fk?$value->product_id_fk:0,
                  "lot_number" =>  @$value->lot_number?$value->lot_number:NULL,
                  "lot_expired_date" =>  @$value->lot_expired_date?$value->lot_expired_date:NULL,
                  "amt" =>  @$value->amt?$value->amt:0,
                  "in_out" =>  @$value->in_out?$value->in_out:0,
                  "product_unit_id_fk" =>  @$value->product_unit_id_fk?$value->product_unit_id_fk:0,

                  "warehouse_id_fk" =>  @$value->warehouse_id_fk?$value->warehouse_id_fk:0,
                  "zone_id_fk" =>  @$value->zone_id_fk?$value->zone_id_fk:0,
                  "shelf_id_fk" =>  @$value->shelf_id_fk?$value->shelf_id_fk:0,
                  "shelf_floor" =>  @$value->shelf_floor?$value->shelf_floor:0,

                  "status" =>  @$value->status?$value->status:0,
                  "note" =>  @$value->note?$value->note:NULL,
                  "note2" =>  (@$value->note2?$value->note2:NULL).' '.(@$value->note3?$value->note3:NULL),

                  "action_user" =>  @$value->action_user?$value->action_user:NULL,
                  "action_date" =>  @$value->action_date?$value->action_date:NULL,
                  "approver" =>  @$value->approver?$value->approver:NULL,
                  "approve_date" =>  @$value->approve_date?$value->approve_date:NULL,

                  "created_at" =>@$value->dd?$value->dd:NULL
              );

                AjaxController::insertStockMovement($insertData);

            }

            return "(1) รับเข้า : db_general_receive => success";


      }

    }



  public function insertStockMovement_From_db_general_takeout(Request $request)
    {

      if($request->ajax()){

        // ดึงจากตารางนำสินค้าออกทั่วไป > db_general_takeout
        $Data = DB::select("
                SELECT db_general_takeout.business_location_id_fk,CONCAT('CODE',db_general_takeout.id) as doc_no,db_general_takeout.created_at as doc_date,branch_id_fk,
                db_general_takeout.product_id_fk, db_general_takeout.lot_number, lot_expired_date, db_general_takeout.amt,2 as 'in_out',product_unit_id_fk,warehouse_id_fk,zone_id_fk,shelf_id_fk,shelf_floor,approve_status as status,
                concat('นำสินค้าออกทั่วไป ',dataset_product_out_cause.txt_desc) as note, db_general_takeout.created_at as dd,
                db_general_takeout.recipient as action_user,db_general_takeout.approver as approver,db_general_takeout.updated_at as approve_date
                FROM
                db_general_takeout
                left Join dataset_product_out_cause ON db_general_takeout.product_out_cause_id_fk = dataset_product_out_cause.id
          ");

          foreach ($Data as $key => $value) {

               $insertData = array(
                  "doc_no" =>  @$value->doc_no?$value->doc_no:NULL,
                  "doc_date" =>  @$value->doc_date?$value->doc_date:NULL,
                  "business_location_id_fk" =>  @$value->business_location_id_fk?$value->business_location_id_fk:0,
                  "branch_id_fk" =>  @$value->branch_id_fk?$value->branch_id_fk:0,
                  "product_id_fk" =>  @$value->product_id_fk?$value->product_id_fk:0,
                  "lot_number" =>  @$value->lot_number?$value->lot_number:NULL,
                  "lot_expired_date" =>  @$value->lot_expired_date?$value->lot_expired_date:NULL,
                  "amt" =>  @$value->amt?$value->amt:0,
                  "in_out" =>  @$value->in_out?$value->in_out:0,
                  "product_unit_id_fk" =>  @$value->product_unit_id_fk?$value->product_unit_id_fk:0,

                  "warehouse_id_fk" =>  @$value->warehouse_id_fk?$value->warehouse_id_fk:0,
                  "zone_id_fk" =>  @$value->zone_id_fk?$value->zone_id_fk:0,
                  "shelf_id_fk" =>  @$value->shelf_id_fk?$value->shelf_id_fk:0,
                  "shelf_floor" =>  @$value->shelf_floor?$value->shelf_floor:0,

                  "status" =>  @$value->status?$value->status:0,
                  "note" =>  @$value->note?$value->note:NULL,

                  "action_user" =>  @$value->action_user?$value->action_user:NULL,
                  "action_date" =>  @$value->action_date?$value->action_date:NULL,
                  "approver" =>  @$value->approver?$value->approver:NULL,
                  "approve_date" =>  @$value->approve_date?$value->approve_date:NULL,

                  "created_at" =>@$value->dd?$value->dd:NULL
              );

                AjaxController::insertStockMovement($insertData);

            }

             return "(2) นำสินค้าออกทั่วไป : db_general_takeout => success";


      }

    }




  public function insertStockMovement_From_db_stocks_account(Request $request)
    {

      if($request->ajax()){

        // ดึงจากตารางกาปรับยอดคลัง > db_stocks_account_code , db_stocks_account
        // ปรับปรุงยอด (ลด)
        $Data = DB::select("
                SELECT db_stocks_account.business_location_id_fk,
                (
                CASE WHEN run_code='-' or run_code is null THEN CONCAT('CODE',db_stocks_account.id) ELSE CONCAT('CODE',run_code) END
                ) as doc_no
                ,db_stocks_account.updated_at as doc_date,db_stocks_account.branch_id_fk,
                db_stocks_account.product_id_fk, db_stocks_account.lot_number,lot_expired_date,
                (CASE WHEN  db_stocks_account.amt_diff <0 THEN  db_stocks_account.amt_diff * (-1) ELSE  db_stocks_account.amt_diff END) as amt,
                2 as 'in_out',product_unit_id_fk,
                warehouse_id_fk,zone_id_fk,shelf_id_fk,shelf_floor,db_stocks_account.status_accepted as status,
                'ปรับปรุงยอด (ลด)' as note,
                 db_stocks_account_code.updated_at as dd,
                 db_stocks_account.action_user as action_user,db_stocks_account.approver as approver,db_stocks_account.approve_date as approve_date
                FROM
                db_stocks_account LEFT JOIN db_stocks_account_code ON db_stocks_account.stocks_account_code_id_fk=db_stocks_account_code.id
                WHERE
                db_stocks_account.amt_diff<0
          ");

          foreach ($Data as $key => $value) {

               $insertData = array(
                  "doc_no" =>  @$value->doc_no?$value->doc_no:NULL,
                  "doc_date" =>  @$value->doc_date?$value->doc_date:NULL,
                  "business_location_id_fk" =>  @$value->business_location_id_fk?$value->business_location_id_fk:0,
                  "branch_id_fk" =>  @$value->branch_id_fk?$value->branch_id_fk:0,
                  "product_id_fk" =>  @$value->product_id_fk?$value->product_id_fk:0,
                  "lot_number" =>  @$value->lot_number?$value->lot_number:NULL,
                  "lot_expired_date" =>  @$value->lot_expired_date?$value->lot_expired_date:NULL,
                  "amt" =>  @$value->amt?$value->amt:0,
                  "in_out" =>  @$value->in_out?$value->in_out:0,
                  "product_unit_id_fk" =>  @$value->product_unit_id_fk?$value->product_unit_id_fk:0,
                  "warehouse_id_fk" =>  @$value->warehouse_id_fk?$value->warehouse_id_fk:0,
                  "zone_id_fk" =>  @$value->zone_id_fk?$value->zone_id_fk:0,
                  "shelf_id_fk" =>  @$value->shelf_id_fk?$value->shelf_id_fk:0,
                  "shelf_floor" =>  @$value->shelf_floor?$value->shelf_floor:0,
                  "status" =>  @$value->status?$value->status:0,
                  "note" =>  @$value->note?$value->note:NULL,

                  "action_user" =>  @$value->action_user?$value->action_user:NULL,
                  "action_date" =>  @$value->action_date?$value->action_date:NULL,
                  "approver" =>  @$value->approver?$value->approver:NULL,
                  "approve_date" =>  @$value->approve_date?$value->approve_date:NULL,


                  "created_at" =>@$value->dd?$value->dd:NULL
              );

                AjaxController::insertStockMovement($insertData);

            }

            // ปรับปรุงยอด (เพิ่ม)
                $Data = DB::select("
                        SELECT db_stocks_account.business_location_id_fk,
                        (
                            CASE WHEN run_code='-' or run_code is null THEN CONCAT('CODE',db_stocks_account.id) ELSE CONCAT('CODE',run_code) END
                            ) as doc_no
                            ,db_stocks_account.updated_at as doc_date,db_stocks_account.branch_id_fk,
                        db_stocks_account.product_id_fk, db_stocks_account.lot_number,lot_expired_date,
                        db_stocks_account.amt_diff as amt,
                        1 as 'in_out',product_unit_id_fk,
                        warehouse_id_fk,zone_id_fk,shelf_id_fk,shelf_floor,db_stocks_account.status_accepted as status,
                        'ปรับปรุงยอด (เพิ่ม)' as note,
                         db_stocks_account_code.updated_at as dd,
                         db_stocks_account.action_user as action_user,db_stocks_account.approver as approver,db_stocks_account.approve_date as approve_date
                        FROM
                        db_stocks_account LEFT JOIN db_stocks_account_code ON db_stocks_account.stocks_account_code_id_fk=db_stocks_account_code.id
                        WHERE
                        db_stocks_account.amt_diff>0
                  ");

                  foreach ($Data as $key => $value) {

                       $insertData = array(
                          "doc_no" =>  @$value->doc_no?$value->doc_no:NULL,
                          "doc_date" =>  @$value->doc_date?$value->doc_date:NULL,
                          "business_location_id_fk" =>  @$value->business_location_id_fk?$value->business_location_id_fk:0,
                          "branch_id_fk" =>  @$value->branch_id_fk?$value->branch_id_fk:0,
                          "product_id_fk" =>  @$value->product_id_fk?$value->product_id_fk:0,
                          "lot_number" =>  @$value->lot_number?$value->lot_number:NULL,
                          "lot_expired_date" =>  @$value->lot_expired_date?$value->lot_expired_date:NULL,
                          "amt" =>  @$value->amt?$value->amt:0,
                          "in_out" =>  @$value->in_out?$value->in_out:0,
                          "product_unit_id_fk" =>  @$value->product_unit_id_fk?$value->product_unit_id_fk:0,
                          "warehouse_id_fk" =>  @$value->warehouse_id_fk?$value->warehouse_id_fk:0,
                          "zone_id_fk" =>  @$value->zone_id_fk?$value->zone_id_fk:0,
                          "shelf_id_fk" =>  @$value->shelf_id_fk?$value->shelf_id_fk:0,
                          "shelf_floor" =>  @$value->shelf_floor?$value->shelf_floor:0,
                          "status" =>  @$value->status?$value->status:0,
                          "note" =>  @$value->note?$value->note:NULL,

                            "action_user" =>  @$value->action_user?$value->action_user:NULL,
                            "action_date" =>  @$value->action_date?$value->action_date:NULL,
                            "approver" =>  @$value->approver?$value->approver:NULL,
                            "approve_date" =>  @$value->approve_date?$value->approve_date:NULL,

                          "created_at" =>@$value->dd?$value->dd:NULL
                      );

                        AjaxController::insertStockMovement($insertData);

                    }

             return "(3) ปรับปรุงยอด (ลด) / ปรับปรุงยอด (เพิ่ม) : db_stocks_account => success";


      }

    }





  public function insertStockMovement_From_db_products_borrow_code(Request $request)
    {

      if($request->ajax()){

        // ดึงจาก การเบิก/ยืม > db_products_borrow_code > db_products_borrow_details
        $Data = DB::select("
                SELECT db_products_borrow_code.business_location_id_fk,
                (
                CASE WHEN borrow_number='-' or borrow_number is null THEN CONCAT('CODE',db_products_borrow_details.id) ELSE borrow_number END
                ) as doc_no
                ,db_products_borrow_code.updated_at as doc_date,db_products_borrow_details.branch_id_fk,

                db_products_borrow_details.product_id_fk, db_products_borrow_details.lot_number,lot_expired_date,
                db_products_borrow_details.amt,2 as 'in_out',product_unit_id_fk
                ,warehouse_id_fk,zone_id_fk,shelf_id_fk,shelf_floor,db_products_borrow_code.approve_status as status,
                'เบิก/ยืม' as note, db_products_borrow_code.created_at as dd,
                db_products_borrow_details.action_user as action_user,db_products_borrow_details.approver as approver,db_products_borrow_details.approve_date as approve_date
                FROM
                db_products_borrow_details LEFT JOIN db_products_borrow_code ON db_products_borrow_details.products_borrow_code_id=db_products_borrow_code.id
          ");

          foreach ($Data as $key => $value) {

               $insertData = array(
                  "doc_no" =>  @$value->doc_no?$value->doc_no:NULL,
                  "doc_date" =>  @$value->doc_date?$value->doc_date:NULL,
                  "business_location_id_fk" =>  @$value->business_location_id_fk?$value->business_location_id_fk:0,
                  "branch_id_fk" =>  @$value->branch_id_fk?$value->branch_id_fk:0,
                  "product_id_fk" =>  @$value->product_id_fk?$value->product_id_fk:0,
                  "lot_number" =>  @$value->lot_number?$value->lot_number:NULL,
                  "lot_expired_date" =>  @$value->lot_expired_date?$value->lot_expired_date:NULL,
                  "amt" =>  @$value->amt?$value->amt:0,
                  "in_out" =>  @$value->in_out?$value->in_out:0,
                  "product_unit_id_fk" =>  @$value->product_unit_id_fk?$value->product_unit_id_fk:0,
                  "warehouse_id_fk" =>  @$value->warehouse_id_fk?$value->warehouse_id_fk:0,
                  "zone_id_fk" =>  @$value->zone_id_fk?$value->zone_id_fk:0,
                  "shelf_id_fk" =>  @$value->shelf_id_fk?$value->shelf_id_fk:0,
                  "shelf_floor" =>  @$value->shelf_floor?$value->shelf_floor:0,
                  "status" =>  @$value->status?$value->status:0,
                  "note" =>  @$value->note?$value->note:NULL,

                    "action_user" =>  @$value->action_user?$value->action_user:NULL,
                    "action_date" =>  @$value->action_date?$value->action_date:NULL,
                    "approver" =>  @$value->approver?$value->approver:NULL,
                    "approve_date" =>  @$value->approve_date?$value->approve_date:NULL,

                  "created_at" =>@$value->dd?$value->dd:NULL
              );

                AjaxController::insertStockMovement($insertData);

            }

             return "(4) การเบิก/ยืม : db_products_borrow_code => success";


      }

    }





  public function insertStockMovement_From_db_transfer_warehouses_code(Request $request)
    {

      if($request->ajax()){

// มี 2 ฝั่ง ๆ นึง รับเข้า อีกฝั่ง จ่ายออก

        // ดึงจาก การโอนภายในสาขา > db_transfer_warehouses_code > db_transfer_warehouses_details
        $Data = DB::select("

                 SELECT
                          db_transfer_warehouses_code.business_location_id_fk,
                          db_transfer_warehouses_code.tr_number as doc_no,
                          db_transfer_warehouses_code.updated_at as doc_date,
                          db_transfer_warehouses_code.branch_id_fk,
                          db_transfer_warehouses_details.product_id_fk,
                          db_transfer_warehouses_details.lot_number,
                          db_transfer_warehouses_details.lot_expired_date,
                          db_transfer_warehouses_details.amt,
                          1 as 'in_out',
                          product_unit_id_fk,warehouse_id_fk,zone_id_fk,shelf_id_fk,shelf_floor,db_transfer_warehouses_code.approve_status as status,

                          'โอนภายในสาขา' as note,
                          db_transfer_warehouses_code.updated_at as dd,
                          db_transfer_warehouses_details.action_user as action_user,db_transfer_warehouses_code.approver as approver,db_transfer_warehouses_code.approve_date as approve_date
                          FROM
                          db_transfer_warehouses_details
                          Left Join db_transfer_warehouses_code ON db_transfer_warehouses_details.transfer_warehouses_code_id = db_transfer_warehouses_code.id
                          where db_transfer_warehouses_details.remark = 1

          ");

          foreach ($Data as $key => $value) {

               $insertData = array(
                  "doc_no" =>  @$value->doc_no?$value->doc_no:NULL,
                  "doc_date" =>  @$value->doc_date?$value->doc_date:NULL,
                  "business_location_id_fk" =>  @$value->business_location_id_fk?$value->business_location_id_fk:0,
                  "branch_id_fk" =>  @$value->branch_id_fk?$value->branch_id_fk:0,
                  "product_id_fk" =>  @$value->product_id_fk?$value->product_id_fk:0,
                  "lot_number" =>  @$value->lot_number?$value->lot_number:NULL,
                  "lot_expired_date" =>  @$value->lot_expired_date?$value->lot_expired_date:NULL,
                  "amt" =>  @$value->amt?$value->amt:0,
                  "in_out" =>  @$value->in_out?$value->in_out:0,
                  "product_unit_id_fk" =>  @$value->product_unit_id_fk?$value->product_unit_id_fk:0,
                  "warehouse_id_fk" =>  @$value->warehouse_id_fk?$value->warehouse_id_fk:0,
                  "zone_id_fk" =>  @$value->zone_id_fk?$value->zone_id_fk:0,
                  "shelf_id_fk" =>  @$value->shelf_id_fk?$value->shelf_id_fk:0,
                  "shelf_floor" =>  @$value->shelf_floor?$value->shelf_floor:0,
                  "status" =>  @$value->status?$value->status:0,
                  "note" =>  @$value->note?$value->note:NULL,

                    "action_user" =>  @$value->action_user?$value->action_user:NULL,
                    "action_date" =>  @$value->action_date?$value->action_date:NULL,
                    "approver" =>  @$value->approver?$value->approver:NULL,
                    "approve_date" =>  @$value->approve_date?$value->approve_date:NULL,

                  "created_at" =>@$value->dd?$value->dd:NULL
              );

                AjaxController::insertStockMovement($insertData);

            }

   $Data2 = DB::select("

                 SELECT
                          db_transfer_warehouses_code.business_location_id_fk,
                          db_transfer_warehouses_code.tr_number as doc_no,
                          db_transfer_warehouses_code.updated_at as doc_date,
                          db_transfer_warehouses_code.branch_id_fk,
                          db_transfer_warehouses_details.product_id_fk,
                          db_transfer_warehouses_details.lot_number,
                          db_transfer_warehouses_details.lot_expired_date,
                          db_transfer_warehouses_details.amt,
                          2 as 'in_out',
                          product_unit_id_fk,warehouse_id_fk,zone_id_fk,shelf_id_fk,shelf_floor,db_transfer_warehouses_code.approve_status as status,

                          'โอนภายในสาขา' as note,
                          db_transfer_warehouses_code.updated_at as dd,
                          db_transfer_warehouses_details.action_user as action_user,db_transfer_warehouses_code.approver as approver,db_transfer_warehouses_code.approve_date as approve_date
                          FROM
                          db_transfer_warehouses_details
                          Left Join db_transfer_warehouses_code ON db_transfer_warehouses_details.transfer_warehouses_code_id = db_transfer_warehouses_code.id
                          where db_transfer_warehouses_details.remark = 2

          ");

          foreach ($Data2 as $key => $value) {

               $insertData = array(
                  "doc_no" =>  @$value->doc_no?$value->doc_no:NULL,
                  "doc_date" =>  @$value->doc_date?$value->doc_date:NULL,
                  "business_location_id_fk" =>  @$value->business_location_id_fk?$value->business_location_id_fk:0,
                  "branch_id_fk" =>  @$value->branch_id_fk?$value->branch_id_fk:0,
                  "product_id_fk" =>  @$value->product_id_fk?$value->product_id_fk:0,
                  "lot_number" =>  @$value->lot_number?$value->lot_number:NULL,
                  "lot_expired_date" =>  @$value->lot_expired_date?$value->lot_expired_date:NULL,
                  "amt" =>  @$value->amt?$value->amt:0,
                  "in_out" =>  @$value->in_out?$value->in_out:0,
                  "product_unit_id_fk" =>  @$value->product_unit_id_fk?$value->product_unit_id_fk:0,
                  "warehouse_id_fk" =>  @$value->warehouse_id_fk?$value->warehouse_id_fk:0,
                  "zone_id_fk" =>  @$value->zone_id_fk?$value->zone_id_fk:0,
                  "shelf_id_fk" =>  @$value->shelf_id_fk?$value->shelf_id_fk:0,
                  "shelf_floor" =>  @$value->shelf_floor?$value->shelf_floor:0,
                  "status" =>  @$value->status?$value->status:0,
                  "note" =>  @$value->note?$value->note:NULL,

                    "action_user" =>  @$value->action_user?$value->action_user:NULL,
                    "action_date" =>  @$value->action_date?$value->action_date:NULL,
                    "approver" =>  @$value->approver?$value->approver:NULL,
                    "approve_date" =>  @$value->approve_date?$value->approve_date:NULL,

                  "created_at" =>@$value->dd?$value->dd:NULL
              );

                AjaxController::insertStockMovement($insertData);

            }

             return "(5) โอนภายในสาขา : db_transfer_warehouses_code => success";


      }

    }



  public function insertStockMovement_From_db_transfer_branch_code(Request $request)
    {

      if($request->ajax()){

        // ดึงจาก การโอนระหว่างสาขา > db_transfer_branch_code > db_transfer_branch_details
        // โอนออก
        $Data = DB::select("

                SELECT
                db_transfer_branch_code.business_location_id_fk,
                db_transfer_branch_code.tr_number as doc_no,
                db_transfer_branch_code.updated_at as doc_date,
                db_transfer_branch_code.branch_id_fk,
                db_transfer_branch_details.product_id_fk,
                db_transfer_branch_details.lot_number,
                db_transfer_branch_details.lot_expired_date,
                db_transfer_branch_details.amt,
                2 as 'in_out',
                product_unit_id_fk,warehouse_id_fk,zone_id_fk,shelf_id_fk,shelf_floor,db_transfer_branch_code.approve_status as status,

                'โอนระหว่างสาขา (จ่ายออกจากการโอน)' as note,
                db_transfer_branch_code.updated_at as dd,
                db_transfer_branch_details.action_user as action_user,db_transfer_branch_code.approver as approver,db_transfer_branch_code.approve_date as approve_date
                FROM
                db_transfer_branch_details
                Left Join db_transfer_branch_code ON db_transfer_branch_details.transfer_branch_code_id = db_transfer_branch_code.id

          ");

          foreach ($Data as $key => $value) {

               $insertData = array(
                  "doc_no" =>  @$value->doc_no?$value->doc_no:NULL,
                  "doc_date" =>  @$value->doc_date?$value->doc_date:NULL,
                  "business_location_id_fk" =>  @$value->business_location_id_fk?$value->business_location_id_fk:0,
                  "branch_id_fk" =>  @$value->branch_id_fk?$value->branch_id_fk:0,
                  "product_id_fk" =>  @$value->product_id_fk?$value->product_id_fk:0,
                  "lot_number" =>  @$value->lot_number?$value->lot_number:NULL,
                  "lot_expired_date" =>  @$value->lot_expired_date?$value->lot_expired_date:NULL,
                  "amt" =>  @$value->amt?$value->amt:0,
                  "in_out" =>  @$value->in_out?$value->in_out:0,
                  "product_unit_id_fk" =>  @$value->product_unit_id_fk?$value->product_unit_id_fk:0,
                  "warehouse_id_fk" =>  @$value->warehouse_id_fk?$value->warehouse_id_fk:0,
                  "zone_id_fk" =>  @$value->zone_id_fk?$value->zone_id_fk:0,
                  "shelf_id_fk" =>  @$value->shelf_id_fk?$value->shelf_id_fk:0,
                  "shelf_floor" =>  @$value->shelf_floor?$value->shelf_floor:0,
                  "status" =>  @$value->status?$value->status:0,
                  "note" =>  @$value->note?$value->note:NULL,
                    "action_user" =>  @$value->action_user?$value->action_user:NULL,
                    "action_date" =>  @$value->action_date?$value->action_date:NULL,
                    "approver" =>  @$value->approver?$value->approver:NULL,
                    "approve_date" =>  @$value->approve_date?$value->approve_date:NULL,

                  "created_at" =>@$value->dd?$value->dd:NULL
              );

                AjaxController::insertStockMovement($insertData);

            }


     // รับเข้าจากการโอน
        $Data = DB::select("
                SELECT
                db_transfer_branch_get.business_location_id_fk,
                db_transfer_branch_get.tr_number as doc_no,
                db_transfer_branch_get.updated_at as doc_date,
                db_transfer_branch_get.branch_id_fk,
                db_transfer_branch_get_products_receive.product_id_fk,
                db_transfer_branch_get_products_receive.lot_number,
                db_transfer_branch_get_products_receive.lot_expired_date,
                db_transfer_branch_get_products_receive.amt_get as amt,1 as 'in_out',
                product_unit_id_fk,warehouse_id_fk,zone_id_fk,shelf_id_fk,shelf_floor,db_transfer_branch_get.approve_status as status
                ,(case WHEN db_transfer_branch_get.tr_status_get=6 THEN 'โอนระหว่างสาขา (รับคืนจากการฏิเสธการรับ)'
                ELSE 'โอนระหว่างสาขา (รับเข้าจากการโอน)' END) as note,
                db_transfer_branch_get.updated_at as dd,
                db_transfer_branch_get_products_receive.action_user as action_user,db_transfer_branch_get.approver as approver,db_transfer_branch_get.approve_date as approve_date
                FROM
                db_transfer_branch_get_products_receive
                Left Join db_transfer_branch_get ON db_transfer_branch_get_products_receive.transfer_branch_get_id_fk = db_transfer_branch_get.id

          ");

          foreach ($Data as $key => $value) {

               $insertData = array(
                  "doc_no" =>  @$value->doc_no?$value->doc_no:NULL,
                  "doc_date" =>  @$value->doc_date?$value->doc_date:NULL,
                  "business_location_id_fk" =>  @$value->business_location_id_fk?$value->business_location_id_fk:0,
                  "branch_id_fk" =>  @$value->branch_id_fk?$value->branch_id_fk:0,
                  "product_id_fk" =>  @$value->product_id_fk?$value->product_id_fk:0,
                  "lot_number" =>  @$value->lot_number?$value->lot_number:NULL,
                  "lot_expired_date" =>  @$value->lot_expired_date?$value->lot_expired_date:NULL,
                  "amt" =>  @$value->amt?$value->amt:0,
                  "in_out" =>  @$value->in_out?$value->in_out:0,
                  "product_unit_id_fk" =>  @$value->product_unit_id_fk?$value->product_unit_id_fk:0,
                  "warehouse_id_fk" =>  @$value->warehouse_id_fk?$value->warehouse_id_fk:0,
                  "zone_id_fk" =>  @$value->zone_id_fk?$value->zone_id_fk:0,
                  "shelf_id_fk" =>  @$value->shelf_id_fk?$value->shelf_id_fk:0,
                  "shelf_floor" =>  @$value->shelf_floor?$value->shelf_floor:0,
                  "status" =>  @$value->status?$value->status:0,
                  "note" =>  @$value->note?$value->note:NULL,

                    "action_user" =>  @$value->action_user?$value->action_user:NULL,
                    "action_date" =>  @$value->action_date?$value->action_date:NULL,
                    "approver" =>  @$value->approver?$value->approver:NULL,
                    "approve_date" =>  @$value->approve_date?$value->approve_date:NULL,

                  "created_at" =>@$value->dd?$value->dd:NULL
              );

                AjaxController::insertStockMovement($insertData);

            }

             return "(6) โอนระหว่างสาขา (จ่ายออกจากการโอน) / (รับเข้าจากการโอน) : db_transfer_branch_code => success";


      }

    }





  public function insertStockMovement_From_db_pay_product_receipt_001(Request $request)
    {

      if($request->ajax()){

        // ดึงจาก จ่ายสินค้าตามใบเสร็จ db_pay_product_receipt_001
        $Data = DB::select("

            SELECT
            db_pay_product_receipt_002.invoice_code  as doc_no,
            db_pay_product_receipt_001.action_date as doc_date,
                db_pay_product_receipt_002.branch_id_fk,
            product_id_fk,
            lot_number,
            lot_expired_date,amt_get as amt,2 as 'in_out',product_unit_id_fk,
            warehouse_id_fk,zone_id_fk,shelf_id_fk,shelf_floor,
            (SELECT status_sent from db_pay_product_receipt_001 WHERE invoice_code=db_pay_product_receipt_002.invoice_code limit 1) as status,
            'จ่ายสินค้าตามใบเสร็จ' as note,
            db_pay_product_receipt_002.updated_at as dd
            FROM db_pay_product_receipt_002
            LEFT JOIN db_pay_product_receipt_001 on db_pay_product_receipt_001.orders_id_fk=db_pay_product_receipt_002.orders_id_fk


          ");

        if(@$Data){

                if(count($Data) == 1){

                            $Data1 = DB::select("

            SELECT
            db_pay_product_receipt_002.invoice_code  as doc_no,
            db_pay_product_receipt_001.action_date as doc_date,
                db_pay_product_receipt_002.branch_id_fk,
            product_id_fk,
            lot_number,
            lot_expired_date,amt_get as amt,2 as 'in_out',product_unit_id_fk,
            warehouse_id_fk,zone_id_fk,shelf_id_fk,shelf_floor,
            (SELECT status_sent from db_pay_product_receipt_001 WHERE invoice_code=db_pay_product_receipt_002.invoice_code limit 1) as status,
            'จ่ายสินค้าตามใบเสร็จ' as note,
            db_pay_product_receipt_002.updated_at as dd
            FROM db_pay_product_receipt_002
            LEFT JOIN db_pay_product_receipt_001 on db_pay_product_receipt_001.orders_id_fk=db_pay_product_receipt_002.orders_id_fk


                          ");

                    if(@$Data1){

                          foreach ($Data1 as $key => $value) {

                               $insertData = array(
                                  "doc_no" =>  @$value->doc_no?$value->doc_no:NULL,
                                  "doc_date" =>  @$value->doc_date?$value->doc_date:NULL,
                                  "business_location_id_fk" =>  @$value->business_location_id_fk?$value->business_location_id_fk:0,
                                  "branch_id_fk" =>  @$value->branch_id_fk?$value->branch_id_fk:0,
                                  "product_id_fk" =>  @$value->product_id_fk?$value->product_id_fk:0,
                                  "lot_number" =>  @$value->lot_number?$value->lot_number:NULL,
                                  "lot_expired_date" =>  @$value->lot_expired_date?$value->lot_expired_date:NULL,
                                  "amt" =>  @$value->amt?$value->amt:0,
                                  "in_out" =>  @$value->in_out?$value->in_out:0,
                                  "product_unit_id_fk" =>  @$value->product_unit_id_fk?$value->product_unit_id_fk:0,
                                  "warehouse_id_fk" =>  @$value->warehouse_id_fk?$value->warehouse_id_fk:0,
                                  "zone_id_fk" =>  @$value->zone_id_fk?$value->zone_id_fk:0,
                                  "shelf_id_fk" =>  @$value->shelf_id_fk?$value->shelf_id_fk:0,
                                  "shelf_floor" =>  @$value->shelf_floor?$value->shelf_floor:0,
                                  "status" =>  @$value->status?$value->status:0,
                                  "note" =>  @$value->note?$value->note:NULL,


                    "action_user" =>  @$value->action_user?$value->action_user:NULL,
                    "action_date" =>  @$value->action_date?$value->action_date:NULL,
                    "approver" =>  @$value->approver?$value->approver:NULL,
                    "approve_date" =>  @$value->approve_date?$value->approve_date:NULL,


                                  "created_at" =>@$value->dd?$value->dd:NULL
                              );

                                AjaxController::insertStockMovement($insertData);

                        }
                    }

                }else{

                     if(count($Data) > 0 ){


                            $Data1 = DB::select("

SELECT
db_pay_product_receipt_002.business_location_id_fk,
db_pay_product_receipt_002.invoice_code  as doc_no,
(SELECT pay_date from db_pay_product_receipt_001 WHERE invoice_code=db_pay_product_receipt_002.invoice_code limit 1) as doc_date,
db_pay_product_receipt_002.branch_id_fk,
product_id_fk,
lot_number,
lot_expired_date,amt_get as amt,2 as 'in_out',product_unit_id_fk,
warehouse_id_fk,zone_id_fk,shelf_id_fk,shelf_floor,
(SELECT status_sent from db_pay_product_receipt_001 WHERE invoice_code=db_pay_product_receipt_002.invoice_code limit 1) as status,
concat('จ่ายสินค้าตามใบเสร็จ  ครั้งที่ '  , db_pay_product_receipt_002.time_pay) as note,
db_pay_product_receipt_002.updated_at as dd,
db_pay_product_receipt_001.action_user as action_user,db_pay_product_receipt_001.pay_user as approver,db_pay_product_receipt_001.pay_date as approve_date
FROM db_pay_product_receipt_002
LEFT JOIN db_pay_product_receipt_001 on db_pay_product_receipt_001.orders_id_fk=db_pay_product_receipt_002.orders_id_fk

                          ");

                        if(@$Data1){

                          foreach ($Data1 as $key => $value) {

                               $insertData = array(
                                  "doc_no" =>  @$value->doc_no?$value->doc_no:NULL,
                                  "doc_date" =>  @$value->doc_date?$value->doc_date:NULL,
                                  "business_location_id_fk" =>  @$value->business_location_id_fk?$value->business_location_id_fk:0,
                                  "branch_id_fk" =>  @$value->branch_id_fk?$value->branch_id_fk:0,
                                  "product_id_fk" =>  @$value->product_id_fk?$value->product_id_fk:0,
                                  "lot_number" =>  @$value->lot_number?$value->lot_number:NULL,
                                  "lot_expired_date" =>  @$value->lot_expired_date?$value->lot_expired_date:NULL,
                                  "amt" =>  @$value->amt?$value->amt:0,
                                  "in_out" =>  @$value->in_out?$value->in_out:0,
                                  "product_unit_id_fk" =>  @$value->product_unit_id_fk?$value->product_unit_id_fk:0,
                                  "warehouse_id_fk" =>  @$value->warehouse_id_fk?$value->warehouse_id_fk:0,
                                  "zone_id_fk" =>  @$value->zone_id_fk?$value->zone_id_fk:0,
                                  "shelf_id_fk" =>  @$value->shelf_id_fk?$value->shelf_id_fk:0,
                                  "shelf_floor" =>  @$value->shelf_floor?$value->shelf_floor:0,
                                  "status" =>  @$value->status?$value->status:0,
                                  "note" =>  @$value->note?$value->note:NULL,

                    "action_user" =>  @$value->action_user?$value->action_user:NULL,
                    "action_date" =>  @$value->action_date?$value->action_date:NULL,
                    "approver" =>  @$value->approver?$value->approver:NULL,
                    "approve_date" =>  @$value->approve_date?$value->approve_date:NULL,


                                  "created_at" =>@$value->dd?$value->dd:NULL
                              );

                                AjaxController::insertStockMovement($insertData);
                         }
                        }
                     }

              }
      }

      return "(7) จ่ายสินค้าตามใบเสร็จ : db_pay_product_receipt_001 => success";

      }
    }





  public function insertStockMovement_From_db_stocks_return(Request $request)
    {

      if($request->ajax()){

            // รับคืนจากการยกเลิกใบสั่งซื้อ db_stocks_return
            $Data = DB::select("
                    SELECT
                    db_stocks_return.business_location_id_fk,
                    db_stocks_return.invoice_code as doc_no,
                    db_stocks_return.updated_at as doc_date,
                    db_stocks_return.branch_id_fk,
                    product_id_fk,
                    lot_number,
                    lot_expired_date,
                    amt,
                    1 as 'in_out',
                    product_unit_id_fk,
                    warehouse_id_fk,zone_id_fk,shelf_id_fk,shelf_floor,db_stocks_return.status_cancel as status,
                    'รับคืนจากการยกเลิกใบสั่งซื้อ' as note,
                    db_stocks_return.updated_at as dd,
                    db_pick_pack_requisition_code.action_user as action_user,'' as approver,'' as approve_date

                    FROM db_stocks_return
                    LEFT JOIN db_pick_pack_requisition_code on db_pick_pack_requisition_code.id=db_stocks_return.pick_pack_requisition_code_id_fk
                    WHERE
                    db_stocks_return.status_cancel=1

              ");

                if(@$Data){

                  foreach ($Data as $key => $value) {

                       $insertData = array(
                            "doc_no" =>  @$value->doc_no?$value->doc_no:NULL,
                            "doc_date" =>  @$value->doc_date?$value->doc_date:NULL,
                            "business_location_id_fk" =>  @$value->business_location_id_fk?$value->business_location_id_fk:0,
                            "branch_id_fk" =>  @$value->branch_id_fk?$value->branch_id_fk:0,
                            "product_id_fk" =>  @$value->product_id_fk?$value->product_id_fk:0,
                            "lot_number" =>  @$value->lot_number?$value->lot_number:NULL,
                            "lot_expired_date" =>  @$value->lot_expired_date?$value->lot_expired_date:NULL,
                            "amt" =>  @$value->amt?$value->amt:0,
                            "in_out" =>  @$value->in_out?$value->in_out:0,
                            "product_unit_id_fk" =>  @$value->product_unit_id_fk?$value->product_unit_id_fk:0,
                            "warehouse_id_fk" =>  @$value->warehouse_id_fk?$value->warehouse_id_fk:0,
                            "zone_id_fk" =>  @$value->zone_id_fk?$value->zone_id_fk:0,
                            "shelf_id_fk" =>  @$value->shelf_id_fk?$value->shelf_id_fk:0,
                            "shelf_floor" =>  @$value->shelf_floor?$value->shelf_floor:0,
                            "status" =>  @$value->status?$value->status:0,
                            "note" =>  @$value->note?$value->note:NULL,

                            "action_user" =>  @$value->action_user?$value->action_user:NULL,
                            "action_date" =>  @$value->action_date?$value->action_date:NULL,
                            "approver" =>  @$value->approver?$value->approver:NULL,
                            "approve_date" =>  @$value->approve_date?$value->approve_date:NULL,

                            "created_at" =>@$value->dd?$value->dd:NULL
                      );

                        AjaxController::insertStockMovement($insertData);

                    }

                }

             return "(8) รับคืนจากการยกเลิกใบสั่งซื้อ : db_stocks_return => success";


      }

    }




  public function insertStockMovement_From_db_pay_requisition_001(Request $request)
    {

      if($request->ajax()){

        // ดึงจาก จ่ายสินค้าตามใบเบิก db_pay_requisition_001
        $Data = DB::select("

                SELECT
                db_pay_requisition_002.business_location_id_fk,
                db_pick_pack_packing.packing_code as doc_no,
                db_pick_pack_packing.created_at as doc_date,
                db_pay_requisition_002.branch_id_fk,
                product_id_fk,
                db_pay_requisition_002.lot_number,
                db_pay_requisition_002.lot_expired_date,
                amt_get as amt,
                2 as 'in_out',
                db_pay_requisition_002.product_unit_id_fk,
                warehouse_id_fk,zone_id_fk,shelf_id_fk,shelf_floor,
                (SELECT status_sent from db_pay_requisition_001 WHERE pick_pack_requisition_code_id_fk=db_pay_requisition_002.pick_pack_requisition_code_id_fk limit 1) as status,
                concat('จ่ายสินค้าตามใบเบิก') as note,
                (SELECT pay_date from db_pay_requisition_001 WHERE pick_pack_requisition_code_id_fk=db_pay_requisition_002.pick_pack_requisition_code_id_fk limit 1) as dd
                FROM db_pay_requisition_002
                Left Join db_pick_pack_requisition_code ON db_pay_requisition_002.pick_pack_requisition_code_id_fk = db_pick_pack_requisition_code.id
                Left Join db_pick_pack_packing ON db_pick_pack_requisition_code.pick_pack_packing_code_id_fk = db_pick_pack_packing.packing_code_id_fk

          ");

        if(@$Data){

        if(count(@$Data) == 1){

                    $Data1 = DB::select("

                        SELECT
                        db_pay_requisition_002.business_location_id_fk,
                        db_pick_pack_packing.packing_code as doc_no,
                        db_pick_pack_packing.created_at as doc_date,
                        db_pay_requisition_002.branch_id_fk,
                        product_id_fk,
                        db_pay_requisition_002.lot_number,
                        db_pay_requisition_002.lot_expired_date,
                        amt_get as amt,
                        2 as 'in_out',
                        db_pay_requisition_002.product_unit_id_fk,
                        warehouse_id_fk,zone_id_fk,shelf_id_fk,shelf_floor,
                        (SELECT status_sent from db_pay_requisition_001 WHERE pick_pack_requisition_code_id_fk=db_pay_requisition_002.pick_pack_requisition_code_id_fk limit 1) as status,
                        concat('จ่ายสินค้าตามใบเบิก') as note,
                        (SELECT pay_date from db_pay_requisition_001 WHERE pick_pack_requisition_code_id_fk=db_pay_requisition_002.pick_pack_requisition_code_id_fk limit 1) as dd
                        ,
                        db_pick_pack_requisition_code.action_user as action_user,db_pick_pack_packing_code.approver as approver, db_pick_pack_packing_code.aprove_date as approve_date

                        FROM db_pay_requisition_002
                        Left Join db_pick_pack_requisition_code ON db_pay_requisition_002.pick_pack_requisition_code_id_fk = db_pick_pack_requisition_code.id
                        Left Join db_pick_pack_packing ON db_pick_pack_requisition_code.pick_pack_packing_code_id_fk = db_pick_pack_packing.packing_code_id_fk
                        Left Join db_pick_pack_packing_code ON db_pick_pack_packing_code.id = db_pick_pack_packing.packing_code_id_fk


                  ");

                if(@$Data1){

                  foreach (@$Data1 as $key => $value) {
                       $insertData = array(
                            "doc_no" =>  @$value->doc_no?$value->doc_no:NULL,
                            "doc_date" =>  @$value->doc_date?$value->doc_date:NULL,
                            "business_location_id_fk" =>  @$value->business_location_id_fk?$value->business_location_id_fk:0,
                            "branch_id_fk" =>  @$value->branch_id_fk?$value->branch_id_fk:0,
                            "product_id_fk" =>  @$value->product_id_fk?$value->product_id_fk:0,
                            "lot_number" =>  @$value->lot_number?$value->lot_number:NULL,
                            "lot_expired_date" =>  @$value->lot_expired_date?$value->lot_expired_date:NULL,
                            "amt" =>  @$value->amt?$value->amt:0,
                            "in_out" =>  @$value->in_out?$value->in_out:0,
                            "product_unit_id_fk" =>  @$value->product_unit_id_fk?$value->product_unit_id_fk:0,
                            "warehouse_id_fk" =>  @$value->warehouse_id_fk?$value->warehouse_id_fk:0,
                            "zone_id_fk" =>  @$value->zone_id_fk?$value->zone_id_fk:0,
                            "shelf_id_fk" =>  @$value->shelf_id_fk?$value->shelf_id_fk:0,
                            "shelf_floor" =>  @$value->shelf_floor?$value->shelf_floor:0,
                            "status" =>  @$value->status?$value->status:0,
                            "note" =>  @$value->note?$value->note:NULL,

                            "action_user" =>  @$value->action_user?$value->action_user:NULL,
                            "action_date" =>  @$value->action_date?$value->action_date:NULL,
                            "approver" =>  @$value->approver?$value->approver:NULL,
                            "approve_date" =>  @$value->approve_date?$value->approve_date:NULL,

                            "created_at" =>@$value->dd?$value->dd:NULL
                      );

                        AjaxController::insertStockMovement($insertData);

                }
            }

        }else{

             if(count(@$Data) > 0 ){


                    $Data1 = DB::select("

                        SELECT
                        db_pay_requisition_002.business_location_id_fk,
                        db_pick_pack_packing.packing_code as doc_no,
                        db_pick_pack_packing.created_at as doc_date,
                        db_pay_requisition_002.branch_id_fk,
                        product_id_fk,
                        db_pay_requisition_002.lot_number,
                        db_pay_requisition_002.lot_expired_date,
                        amt_get as amt,
                        2 as 'in_out',
                        db_pay_requisition_002.product_unit_id_fk,
                        warehouse_id_fk,zone_id_fk,shelf_id_fk,shelf_floor,
                        (SELECT status_sent from db_pay_requisition_001 WHERE pick_pack_requisition_code_id_fk=db_pay_requisition_002.pick_pack_requisition_code_id_fk limit 1) as status,
                        concat('จ่ายสินค้าตามใบเบิก ครั้งที่ ', time_pay) as note,
                        (SELECT pay_date from db_pay_requisition_001 WHERE pick_pack_requisition_code_id_fk=db_pay_requisition_002.pick_pack_requisition_code_id_fk limit 1) as dd,
                        db_pick_pack_requisition_code.action_user as action_user,db_pick_pack_packing_code.approver as approver, db_pick_pack_packing_code.aprove_date as approve_date

                        FROM db_pay_requisition_002
                        Left Join db_pick_pack_requisition_code ON db_pay_requisition_002.pick_pack_requisition_code_id_fk = db_pick_pack_requisition_code.id
                        Left Join db_pick_pack_packing ON db_pick_pack_requisition_code.pick_pack_packing_code_id_fk = db_pick_pack_packing.packing_code_id_fk
                        Left Join db_pick_pack_packing_code ON db_pick_pack_packing_code.id = db_pick_pack_packing.packing_code_id_fk

                  ");


              if(@$Data1){


                  foreach (@$Data1 as $key => $value) {

                       $insertData = array(
                            "doc_no" =>  @$value->doc_no?$value->doc_no:NULL,
                            "doc_date" =>  @$value->doc_date?$value->doc_date:NULL,
                            "business_location_id_fk" =>  @$value->business_location_id_fk?$value->business_location_id_fk:0,
                            "branch_id_fk" =>  @$value->branch_id_fk?$value->branch_id_fk:0,
                            "product_id_fk" =>  @$value->product_id_fk?$value->product_id_fk:0,
                            "lot_number" =>  @$value->lot_number?$value->lot_number:NULL,
                            "lot_expired_date" =>  @$value->lot_expired_date?$value->lot_expired_date:NULL,
                            "amt" =>  @$value->amt?$value->amt:0,
                            "in_out" =>  @$value->in_out?$value->in_out:0,
                            "product_unit_id_fk" =>  @$value->product_unit_id_fk?$value->product_unit_id_fk:0,
                            "warehouse_id_fk" =>  @$value->warehouse_id_fk?$value->warehouse_id_fk:0,
                            "zone_id_fk" =>  @$value->zone_id_fk?$value->zone_id_fk:0,
                            "shelf_id_fk" =>  @$value->shelf_id_fk?$value->shelf_id_fk:0,
                            "shelf_floor" =>  @$value->shelf_floor?$value->shelf_floor:0,
                            "status" =>  @$value->status?$value->status:0,
                            "note" =>  @$value->note?$value->note:NULL,

                            "action_user" =>  @$value->action_user?$value->action_user:NULL,
                            "action_date" =>  @$value->action_date?$value->action_date:NULL,
                            "approver" =>  @$value->approver?$value->approver:NULL,
                            "approve_date" =>  @$value->approve_date?$value->approve_date:NULL,

                            "created_at" =>@$value->dd?$value->dd:NULL
                      );
                        AjaxController::insertStockMovement($insertData);
                }
              }

             }

        }
      }

      return "(9) จ่ายสินค้าตามใบเบิก : db_pay_requisition_001 => success";

      }
    }




  public function insertStockMovement_Final(Request $request)
    {

      if($request->ajax()){
         sleep(5);
         DB::select(" INSERT IGNORE INTO db_stock_movement SELECT * FROM db_stock_movement_tmp ORDER BY doc_date asc ");
         return "(10) insertStockMovement_Final => success";

      }
    }



  public function ajaxGetOrdersIDtoDeliveryAddr(Request $request)
    {

      if($request->ajax()){

        // return $request->id;

        $d1 =  DB::select(" SELECT * FROM `db_delivery_packing` where packing_code_id_fk in ($request->id) LIMIT 1 ");
        $d2 =  DB::select(" SELECT * FROM `db_delivery` where id in (".$d1[0]->delivery_id_fk.") LIMIT 1 ");
        $d3 = DB::select("select * from customers_addr_frontstore where frontstore_id_fk=".$d2[0]->orders_id_fk." ");
        if($d3){
            return response()->json($d3);
        }else{
            $d2 =  DB::select(" SELECT orders_id_fk as frontstore_id_fk,customer_id FROM `db_delivery` where id in (".$d1[0]->delivery_id_fk.") LIMIT 1 ");
            return response()->json($d2);
        }


      }
    }



  public function ajaxGetOrdersIDtoDeliveryAddr02(Request $request)
    {

      if($request->ajax()){

        // return $request->id;
        $d2 =  DB::select(" SELECT * FROM `db_delivery` where id in ($request->id) LIMIT 1 ");
        $d3 = DB::select("select * from customers_addr_frontstore where frontstore_id_fk=".$d2[0]->orders_id_fk." ");
        if($d3){
            return response()->json($d3);
        }else{
            $d2 =  DB::select(" SELECT orders_id_fk as frontstore_id_fk,customer_id FROM `db_delivery` where id in ($request->id) LIMIT 1 ");
            return response()->json($d2);
        }


      }
    }




  public function ajaxDelFunction(Request $request)
    {

      if($request->ajax()){
        // return $request->id;
        if(!empty(@$request->id) && !empty(@$request->table)){
            DB::select(" DELETE FROM ".$request->table." where id =".$request->id." ");
        }

        if(!empty(@$request->file)){
            @UNLINK(@$request->file);
        }

      }
    }


    public function ajaxProductPackingAddBox(Request $request)
    {
        if($request->ajax()){
            $value=DB::table('db_pick_pack_packing')
            ->where('id', $request->id)
            ->first();
            if($value){
               $box_id = DB::table('db_pick_pack_boxsize')->insertGetId([
                    'pick_pack_packing_id_fk' => $request->id,
                    'created_at' => date('Y-m-d H:i:s'),
                ]);
            }
            return response()->json(['box_id'=>$box_id]);
        }
    }

    public function ajaxProductPackingRemoveBox(Request $request)
    {
        if($request->ajax()){
            $value=DB::table('db_pick_pack_packing')
            ->where('id', $request->id)
            ->first();
            if($value){
               $box_id = DB::table('db_pick_pack_boxsize')->where('id',$request->box_id)->update([
                    'updated_at' => date('Y-m-d H:i:s'),
                    'deleted_at' => date('Y-m-d H:i:s'),
                ]);
            }
            return response()->json();
        }
    }

}

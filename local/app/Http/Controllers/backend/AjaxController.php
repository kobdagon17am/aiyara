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
use App\Models\Backend\PromotionCode_add;
use App\Models\Backend\GiftvoucherCode_add;

class AjaxController extends Controller
{

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



    public function ajaxAcceptCheckStock(Request $req)
    {
        // return($req->id);
        // 0=NEW,1=PENDDING,2=REQUEST,3=ACCEPTED/APPROVED,4=NO APPROVED,5=CANCELED
        DB::select(" UPDATE db_stocks_account SET status_accepted=3 ");

    }


    public function ajaxGetBranch(Request $request)
    {
        if($request->ajax()){
          $query = \App\Models\Backend\Branchs::where('business_location_id_fk',$request->business_location_id_fk)->get()->toArray();
          return response()->json($query);
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
          $query = \App\Models\Backend\Check_stock::where('product_id_fk',$request->product_id_fk)->get()->toArray();
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
             $response[] = array("value"=>$value->lot_number,"label"=>$value->lot_number);
            }

            return json_encode($response);

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

    public function createPDFReceiptFrontstore02($id)
     {
        // dd($id);
        $data = [$id];
        $pdf = PDF::loadView('backend.frontstore.print_receipt_02',compact('data'))->setPaper('a5', 'landscape');
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
            $rs = DB::select(" SELECT * FROM db_frontstore WHERE id=$id ");
            return response()->json($rs);
    }


   public function ajaxCheckDBfrontstore(Request $request)
    {
        $rs = DB::select(" SELECT count(*) as cnt FROM db_frontstore_products_list WHERE frontstore_id_fk='".$request->frontstore_id_fk."' ");
        return $rs[0]->cnt;
    }



   public function ajaxShippingCalculate(Request $request)
    {
        // return $request;
        // dd();
        $sum_price = str_replace(',','',$request->sum_price);
        $frontstore_id = $request->frontstore_id;
        $delivery_location = $request->delivery_location;
        $frontstore = DB::select(" SELECT * FROM db_frontstore WHERE id=$frontstore_id ");
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

        // return $province_id;
        // dd();

        // return $request->shipping_special;
        // return $province_id;
        // dd();

        if(!empty($request->shipping_special)){

            // return $province_id;
            // dd();

            $shipping = DB::select(" SELECT * FROM dataset_shipping_cost WHERE business_location_id_fk='".$frontstore[0]->business_location_id_fk."' AND shipping_type_id=4 ");
            DB::select(" UPDATE db_frontstore SET delivery_location=$delivery_location , delivery_province_id=$province_id , shipping_price='".$shipping[0]->shipping_cost."', shipping_free=0 ,shipping_special=1 WHERE id=$frontstore_id ");

            return $shipping[0]->shipping_cost;
            // dd();

        }else{

            DB::select(" UPDATE db_frontstore SET shipping_special=0  WHERE id=$frontstore_id ");

            // return $province_id;
            // dd();

            // กรณีส่งฟรี
            $shipping = DB::select(" SELECT * FROM dataset_shipping_cost WHERE business_location_id_fk='".$frontstore[0]->business_location_id_fk."' AND shipping_type_id=1 ");

            if($sum_price>=$shipping[0]->purchase_amt){
                DB::select(" UPDATE db_frontstore SET delivery_location=$delivery_location , delivery_province_id=$province_id , shipping_price=0, shipping_free=1 WHERE id=$frontstore_id ");
                return 0 ;
            }else{

                DB::select(" UPDATE db_frontstore SET shipping_price=0,shipping_free=0  WHERE id=$frontstore_id ");

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
                            DB::select(" UPDATE db_frontstore SET delivery_location=$delivery_location ,delivery_province_id=$province_id ,shipping_price=0  WHERE id=$frontstore_id ");
                            return 0;
                        }else{
                             // ต่าง จ. กัน เช็คดูว่า อยู่ในเขตปริมณทฑลหรือไม่
                            $shipping_cost = DB::select("SELECT * FROM dataset_shipping_cost where business_location_id_fk =".$branchs[0]->business_location_id_fk." AND shipping_type_id=2  ");

                            $shipping_vicinity = DB::select("SELECT * FROM dataset_shipping_vicinity where shipping_cost_id_fk =".$shipping_cost[0]->id." AND province_id_fk=$province_id ");

                            if(count($shipping_vicinity)>0){

                                DB::select(" UPDATE db_frontstore SET delivery_location=$delivery_location ,delivery_province_id=$province_id ,shipping_price=".$shipping_cost[0]->shipping_cost."  WHERE id=$frontstore_id ");

                                return $shipping_cost[0]->shipping_cost;

                            }else{

                                $shipping_cost = DB::select("SELECT * FROM dataset_shipping_cost where business_location_id_fk =".$branchs[0]->business_location_id_fk." AND shipping_type_id=3 ");

                                DB::select(" UPDATE db_frontstore SET delivery_location=$delivery_location ,delivery_province_id=$province_id ,shipping_price=".$shipping_cost[0]->shipping_cost."  WHERE id=$frontstore_id ");

                                return $shipping_cost[0]->shipping_cost;

                            }

                        }

                }


            }



        }



    }

   public function ajaxClearAfterAddAiCash(Request $request)
    {
        // return $request;

        DB::select(" UPDATE db_frontstore SET
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

        // $rs = DB::select(" SELECT * FROM db_frontstore WHERE id=".$request->frontstore_id_fk." ");
        // return response()->json($rs);

    }


   public function ajaxFeeCalculate(Request $request)
    {


     /*
        $pay_type_id
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
        $pay_type_id = $request->pay_type_id;
        $frontstore_id =  $request->frontstore_id ;
        $aicash_price = str_replace(',','',$request->aicash_price);
        $sum_price = str_replace(',','',$request->sum_price);
        $shipping_price = str_replace(',','',$request->shipping_price);
        $sum_price = ($sum_price+$shipping_price) ;


        DB::select(" UPDATE db_frontstore SET
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

        if($pay_type_id==5){
            DB::select(" UPDATE db_frontstore SET cash_price=($sum_price-$shipping_price),cash_pay=$sum_price WHERE id=$frontstore_id ");
        }

        if($pay_type_id==6){
            $aicash_price = $aicash_price>$sum_price?$sum_price:$aicash_price;
            DB::select(" UPDATE db_frontstore SET aicash_price=$aicash_price,cash_price=($sum_price-$aicash_price),cash_pay=$sum_price WHERE id=$frontstore_id ");
        }

        if($pay_type_id == '') {
            DB::select(" UPDATE db_frontstore SET pay_type_id=0,cash_price=0,cash_pay=0 WHERE id=$frontstore_id ");
        }

        $rs = DB::select(" SELECT * FROM db_frontstore WHERE id=$frontstore_id ");
        return response()->json($rs);

    }


  public function ajaxCalPriceFrontstore01(Request $request)
    {
        // return $request;
        // dd();
     /*
        $pay_type_id
          1 เงินสด
          2 เงินสด + Ai-Cash
          3 เครดิต + เงินสด
          4 เครดิต + เงินโอน
          5 เครดิต + Ai-Cash
          6 เงินโอน + เงินสด
          7 เงินโอน + Ai-Cash
          */


        $sum_price = str_replace(',','',$request->sum_price);
        $pay_type_id = $request->pay_type_id?$request->pay_type_id:0;
        $frontstore_id =  $request->frontstore_id ;
        $shipping_price = str_replace(',','',$request->shipping_price);

        $sum_price = ($sum_price+$shipping_price) ;

        if($request->purchase_type_id_fk==5){

            $gift_voucher_cost = str_replace(',','',$request->gift_voucher_cost);   // ที่มีอยู่
            $gift_voucher_price = str_replace(',','',$request->gift_voucher_price); // ที่กรอก
            $gift_voucher_price = $gift_voucher_price>$gift_voucher_cost?$gift_voucher_cost:$gift_voucher_price;
            $gift_voucher_price = $gift_voucher_price>$sum_price?$sum_price:$gift_voucher_price;

            $sum_price = $sum_price - $gift_voucher_price ;

        }


        DB::select(" UPDATE db_frontstore SET

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
            WHERE id=$frontstore_id ");


        DB::select(" UPDATE db_frontstore SET pay_type_id=($pay_type_id) WHERE id=$frontstore_id ");

        if($pay_type_id==5){
            DB::select(" UPDATE db_frontstore SET cash_price=($sum_price-$shipping_price),cash_pay=($sum_price),total_price=($sum_price) WHERE id=$frontstore_id ");
        }

        $rs = DB::select(" SELECT * FROM db_frontstore WHERE id=$frontstore_id ");
        return response()->json($rs);

    }

    public function ajaxCearCostFrontstore(Request $request)
    {
          $frontstore_id_fk =  $request->frontstore_id_fk ;

          DB::select(" UPDATE db_frontstore SET

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
            WHERE id=$frontstore_id_fk ");
    }



   public function ajaxCalPriceFrontstore02(Request $request)
    {
        // return $request;
        // dd();
     /*
        $pay_type_id
          1 เงินสด
          2 เงินสด + Ai-Cash
          3 เครดิต + เงินสด
          4 เครดิต + เงินโอน
          5 เครดิต + Ai-Cash
          6 เงินโอน + เงินสด
          7 เงินโอน + Ai-Cash
          */
          // return $request;
          // dd();

        $pay_type_id = $request->pay_type_id;
        $frontstore_id =  $request->frontstore_id ;
        $sum_price = str_replace(',','',$request->sum_price);
        $shipping_price = str_replace(',','',$request->shipping_price);
        $sum_price = ($sum_price+$shipping_price) ;

        if($request->purchase_type_id_fk==5){

            $gift_voucher_cost = str_replace(',','',$request->gift_voucher_cost);   // ที่มีอยู่
            $gift_voucher_price = str_replace(',','',$request->gift_voucher_price); // ที่กรอก
            $gift_voucher_price = $gift_voucher_price>$gift_voucher_cost?$gift_voucher_cost:$gift_voucher_price;
            $gift_voucher_price = $gift_voucher_price>$sum_price?$sum_price:$gift_voucher_price;

            $sum_price = $sum_price - $gift_voucher_price ;

        }


        if($pay_type_id==5){
            DB::select(" UPDATE db_frontstore SET member_id_aicash='0',aicash_price='0',cash_price=($sum_price-$shipping_price),cash_pay=($sum_price),total_price=($sum_price) WHERE id=$frontstore_id ");
        }

        if($pay_type_id==6){
            $aicash_price = str_replace(',','',@$request->aicash_price);
            $aicash_remain = str_replace(',','',@$request->aicash_remain);
            if(!empty($aicash_remain)){
                $aicash_price = $aicash_remain ;
                $aicash_price = is_numeric($aicash_price) > is_numeric($sum_price) ? is_numeric($sum_price) : is_numeric($aicash_price) ;
            }else{
                $aicash_price = is_numeric($aicash_price) > is_numeric($sum_price) ? is_numeric($sum_price) : is_numeric($aicash_price) ;
            }

            $cash_pay = is_numeric(@$sum_price) - is_numeric($aicash_price) ;
            DB::select(" UPDATE db_frontstore SET member_id_aicash=".@$request->member_id_aicash.",aicash_price=$aicash_price, cash_price=$cash_pay, cash_pay=$cash_pay,total_price=($sum_price) WHERE id=$frontstore_id ");
        }

        if($pay_type_id==7){

            if(empty($request->credit_price)){
                exit;
            }

            $credit_price = str_replace(',','',$request->credit_price);
            $credit_price = $credit_price>$sum_price?$sum_price:$credit_price;
            DB::select(" UPDATE db_frontstore SET credit_price=$credit_price WHERE id=$frontstore_id ");

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
                        DB::select(" UPDATE db_frontstore SET credit_price=$credit_price, fee=$fee_id,fee_amt=$fee_amt,sum_credit_price=$sum_credit_price,cash_price=0,cash_pay=0 WHERE id=$frontstore_id ");
                    }else{
                        DB::select(" UPDATE db_frontstore SET credit_price=$credit_price, fee=$fee_id,fee_amt=$fee_amt,sum_credit_price=$sum_credit_price,cash_price=($sum_price-$credit_price),cash_pay=($sum_price-$credit_price) WHERE id=$frontstore_id ");
                    }

                }else{

                    DB::select(" UPDATE db_frontstore SET charger_type=$charger_type,credit_price=$credit_price, fee=$fee_id,fee_amt=$fee_amt,sum_credit_price=$credit_price,cash_price=($sum_price-$credit_price),cash_pay=(($sum_price-$credit_price)+$fee_amt) WHERE id=$frontstore_id ");

                }

            }

        }

     if($pay_type_id==8){

            if(empty($request->credit_price)){
                exit;
            }

            $credit_price = str_replace(',','',$request->credit_price);
            $transfer_price = str_replace(',','',$request->transfer_price);
            $credit_price = $credit_price>$sum_price?$sum_price:$credit_price;
            $transfer_price = $transfer_price>$sum_price?$sum_price:$transfer_price;
            DB::select(" UPDATE db_frontstore SET credit_price=$credit_price WHERE id=$frontstore_id ");

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
                        DB::select(" UPDATE db_frontstore SET credit_price=$credit_price, fee=$fee_id,fee_amt=$fee_amt,sum_credit_price=$sum_credit_price,cash_price=0,cash_pay=0 WHERE id=$frontstore_id ");
                    }else{
                        DB::select(" UPDATE db_frontstore SET credit_price=$credit_price, fee=$fee_id,fee_amt=$fee_amt,sum_credit_price=$sum_credit_price,transfer_price=($sum_price-$credit_price),transfer_money_datetime='$transfer_money_datetime',cash_price=0,cash_pay=0 WHERE id=$frontstore_id ");
                    }

                }else{

                    DB::select(" UPDATE db_frontstore SET charger_type=$charger_type,credit_price=$credit_price, fee=$fee_id,fee_amt=$fee_amt,sum_credit_price=$credit_price,transfer_price=(($sum_price-$credit_price)+$fee_amt),transfer_money_datetime='$transfer_money_datetime',cash_price=0,cash_pay=0 WHERE id=$frontstore_id ");

                }

            }

        }


   if($pay_type_id==9){

            if(empty($request->credit_price)){
                exit;
            }

            $credit_price = str_replace(',','',$request->credit_price);
            $credit_price = $credit_price>$sum_price?$sum_price:$credit_price;

            DB::select(" UPDATE db_frontstore SET credit_price=$credit_price,file_slip=NULL WHERE id=$frontstore_id ");

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
                        DB::select(" UPDATE db_frontstore SET credit_price=$credit_price, fee=$fee_id,fee_amt=$fee_amt,sum_credit_price=$sum_credit_price,cash_price=0,cash_pay=0,aicash_price=0 WHERE id=$frontstore_id ");
                    }else{

                        // ถ้ายอดเครดิต ลบ ราคาสินค้ารวม แล้ว มากกว่า ยอด ai-cash ที่มี ให้ ยอด ai-cash = ยอด ai-cash ที่มี แล้ว ส่วนเอาที่เหลือ เอาไปรวมกับยอด เครดิต อีกรอบ
                        $sCustomer = DB::select(" select * from customers where id=".$request->customers_id_fk." ");
                        $Cus_Aicash = $sCustomer[0]->ai_cash;

                        $AiCashInput =  $sum_price - $credit_price ;
                        if($AiCashInput > $Cus_Aicash){
                            $AiCash = $Cus_Aicash;
                            $credit_price = $sum_price - $AiCash ;
                        }else{
                            $AiCash = $AiCashInput ;
                            $credit_price = $credit_price ;
                        }

                        DB::select(" UPDATE db_frontstore SET credit_price=$credit_price, fee=$fee_id,fee_amt=$fee_amt,sum_credit_price=$sum_credit_price,member_id_aicash=".$request->member_id_aicash.",aicash_price=($AiCash),cash_price=0,cash_pay=0 WHERE id=$frontstore_id ");
                    }

                }else{

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
                            DB::select(" UPDATE db_frontstore SET credit_price=$credit_price, fee=$fee_id,fee_amt=$fee_amt,sum_credit_price=$sum_credit_price,cash_price=0,cash_pay=0,member_id_aicash=".$request->member_id_aicash.",aicash_price=$fee_amt WHERE id=$frontstore_id ");
                        }else{

                           DB::select(" UPDATE db_frontstore SET charger_type=$charger_type,credit_price=$credit_price, fee=$fee_id,fee_amt=$fee_amt,sum_credit_price=$credit_price,member_id_aicash=".$request->member_id_aicash.",aicash_price=($AiCash),cash_price=0,cash_pay=0 WHERE id=$frontstore_id ");

                        }

                }



            }

        }


        if($pay_type_id==10){

                    if(empty($request->transfer_price)){
                        exit;
                    }
                    $transfer_price = str_replace(',','',$request->transfer_price);
                    $transfer_price = $transfer_price>$sum_price?$sum_price:$transfer_price;
                    $transfer_money_datetime = $request->transfer_money_datetime;
                    DB::select(" UPDATE db_frontstore SET transfer_price=$transfer_price,cash_price=($sum_price-$transfer_price),cash_pay=($sum_price-$transfer_price),transfer_money_datetime='$transfer_money_datetime' WHERE id=$frontstore_id ");

         }

        if($pay_type_id==11){

                    if(empty($request->transfer_price)){
                        exit;
                    }

                    $transfer_price = str_replace(',','',$request->transfer_price);
                    $transfer_price = $transfer_price>$sum_price?$sum_price:$transfer_price;
                    $transfer_money_datetime = $request->transfer_money_datetime;
                    DB::select(" UPDATE db_frontstore SET transfer_price=$transfer_price,aicash_price=($sum_price-$transfer_price),transfer_money_datetime='$transfer_money_datetime',member_id_aicash=".$request->member_id_aicash.",cash_price=0,cash_pay=0 WHERE id=$frontstore_id ");

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

                    DB::select(" UPDATE db_frontstore SET member_id_aicash=".$request->member_id_aicash.",aicash_price=$sum_price, cash_price=0, cash_pay=0,total_price=($sum_price) WHERE id=$frontstore_id ");
                }else{
                    DB::select(" UPDATE db_frontstore SET member_id_aicash=".$request->member_id_aicash.",aicash_price=$aicash_price, cash_price=$cash_pay, cash_pay=$cash_pay,total_price=($sum_price) WHERE id=$frontstore_id ");
                }


            }
        }



        $rs = DB::select(" SELECT * FROM db_frontstore WHERE id=$frontstore_id ");
        return response()->json($rs);

    }




   public function ajaxCalPriceFrontstore03(Request $request)
    {
        // return $request;
        // dd();

        $pay_type_id = $request->pay_type_id;
        $frontstore_id =  $request->frontstore_id ;
        $sum_price = str_replace(',','',$request->sum_price);
        $shipping_price = str_replace(',','',$request->shipping_price);
        $sum_price = ($sum_price+$shipping_price) ;

        if($pay_type_id==6){

            // return $pay_type_id;
            // dd();
            $aicash_price = str_replace(',','',@$request->aicash_price);
            $aicash_remain = str_replace(',','',@$request->aicash_remain);
            $aicash_price = $aicash_price > $aicash_remain ? $aicash_remain : $aicash_price ;
            $aicash_price = $aicash_price > $sum_price ? $sum_price : $aicash_price ;
            $cash_pay = $sum_price - $aicash_price ;

            // return @$request->member_id_aicash;
            // return $aicash_price;
            // dd();

            DB::select(" UPDATE db_frontstore SET member_id_aicash=".@$request->member_id_aicash.",aicash_price=$aicash_price, cash_price=$cash_pay, cash_pay=$cash_pay,total_price=($sum_price) WHERE id=$frontstore_id ");
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

        //             DB::select(" UPDATE db_frontstore SET member_id_aicash=".$request->member_id_aicash.",aicash_price=$sum_price, cash_price=0, cash_pay=0,total_price=($sum_price) WHERE id=$frontstore_id ");
        //         }else{
        //             DB::select(" UPDATE db_frontstore SET member_id_aicash=".$request->member_id_aicash.",aicash_price=$aicash_price, cash_price=$cash_pay, cash_pay=$cash_pay,total_price=($sum_price) WHERE id=$frontstore_id ");
        //         }


        //     }
        // }


        $rs = DB::select(" SELECT * FROM db_frontstore WHERE id=$frontstore_id ");
        return response()->json($rs);

    }


   public function ajaxCalPriceFrontstore04(Request $request)
    {
        // return $request;
        // dd();

        $pay_type_id = $request->pay_type_id;
        $frontstore_id =  $request->frontstore_id ;
        $sum_price = str_replace(',','',$request->sum_price);
        $shipping_price = str_replace(',','',$request->shipping_price);
        $sum_price = ($sum_price+$shipping_price) ;

        if($pay_type_id==6){
            $aicash_price = str_replace(',','',@$request->aicash_price);
            $aicash_remain = str_replace(',','',@$request->aicash_remain);
            if(!empty($aicash_remain)){
                $aicash_price = $aicash_remain ;
                $aicash_price = $aicash_price > $sum_price ? $sum_price : $aicash_price ;
            }else{
                $aicash_price = $aicash_price > $sum_price ? $sum_price : $aicash_price ;
            }

            $cash_pay = @$sum_price - $aicash_price ;

            DB::select(" UPDATE db_frontstore SET member_id_aicash=".@$request->member_id_aicash.",aicash_price=$aicash_price, cash_price=$cash_pay, cash_pay=$cash_pay,total_price=($sum_price) WHERE id=$frontstore_id ");
        }


        $rs = DB::select(" SELECT * FROM db_frontstore WHERE id=$frontstore_id ");
        return response()->json($rs);

    }



   public function ajaxCalAicash(Request $request)
    {
        $rs =  DB::select(" select * from customers where id=".$request->customer_id." ");
        // $Cus_Aicash = $sCustomer[0]->ai_cash;
        return response()->json($rs);

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

           // return($request->all());
           // dd();

         if($request->promotion_id_fk){
            $sRow = \App\Models\Backend\PromotionCode::find($request->promotion_id_fk);
          }else{
            $sRow = new \App\Models\Backend\PromotionCode;
          }
           // return($request->promotion_id_fk);
           // dd();
            $sRow->promotion_id_fk = $request->promotion_id_fk;
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



    public function ajaxGenPromotionSaveDate(Request $request)
    {

        // return $request;
        // dd();
         if($request->id){
            $sRow = \App\Models\Backend\PromotionCode::find($request->id);
            $sRow->promotion_id_fk = $request->promotion_id_fk;
            $sRow->pro_sdate = $request->pro_sdate;
            $sRow->pro_edate = $request->pro_edate;
            $sRow->save();
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
          }

    }


    public function ajaxCheckCouponUsed(Request $request)
    {

        // return($request);
        // dd();
        // return $request->txtSearchPro;
        $promotion_cus = DB::select("
            select * from db_promotion_cus
            Left Join db_promotion_code ON db_promotion_cus.promotion_code_id_fk = db_promotion_code.id
            Left Join promotions ON db_promotion_code.promotion_id_fk = promotions.id
            WHERE db_promotion_cus.promotion_code='".$request->txtSearchPro."' AND promotions.status=1 AND promotions.promotion_coupon_status=1 AND db_promotion_cus.pro_status=1 ; ");
        // return $promotion_cus[0]->promotion_code_id_fk;

        // // return $promotion_cus;
        // return count($promotion_cus);
        // dd();

        if( count($promotion_cus) == 0){
            return "InActive";
        }else{

            // return @$promotion_cus[0]->promotion_code_id_fk;
            // dd();

                $promotion_code = DB::select("
                    select * from db_promotion_code
                    Left Join promotions ON db_promotion_code.promotion_id_fk = promotions.id
                    WHERE db_promotion_code.promotion_id_fk='".@$promotion_cus[0]->promotion_code_id_fk."' AND promotions.status=1 AND promotions.promotion_coupon_status=1 AND db_promotion_code.approve_status=1
                    ; ");

                // return count($promotion_code) ;
                // dd();

            if( count($promotion_code) == 0){
                return "InActive";
            }else{

                    $rs = DB::select(" select count(*) as cnt from db_frontstore_products_list WHERE promotion_code='".$request->txtSearchPro."' ; ");
                    // return $rs[0]->cnt;
                    // dd();

                    if($rs[0]->cnt > 0){
                        return "InActive";
                    }else if(@$promotion_code[0]->approve_status==0){
                        return "InActive";
                    }else{
                        return true;
                    }

            }
        }


    }

    public function ajaxApproveCouponCode(Request $request)
    {
        DB::select("
            UPDATE db_promotion_cus  SET pro_status=1
            WHERE promotion_code_id_fk = '".$request->promotion_code_id_fk."' AND pro_status = 5 ;
          ");

    }

    public function ajaxApproveGiftvoucherCode(Request $request)
    {
        DB::select("
            UPDATE db_giftvoucher_cus  SET pro_status=1
            WHERE giftvoucher_code_id_fk = '".$request->giftvoucher_code_id_fk."' AND pro_status = 4 ;
          ");

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
        DB::delete(" DELETE FROM db_consignments_import ");
        DB::select(" ALTER table db_consignments_import AUTO_INCREMENT=1; ");
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
            if($request->id){
              $sRow = DB::select(" select * from db_frontstore where id=".$request->id."  ");
              @UNLINK(@$sRow[0]->file_slip);
              DB::select(" UPDATE db_frontstore SET file_slip='' where id=".$request->id."  ");
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


    public function ajaxCalAicashAmt(Request $request)
    {
        // return $request;
        // dd();
        if($request->ajax()){

            $id = $request->id;

            if($id){

                DB::select(" UPDATE db_add_ai_cash SET pay_type_id=0 , cash_price='0', cash_pay='0', credit_price='0', fee='0', fee_amt='0', charger_type='0', sum_credit_price='0', total_amt='0',transfer_price=0 ,account_bank_id=0 ,transfer_money_datetime=null,file_slip=null WHERE (id='$id') ");

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
              // เช็คเรื่องการตัดยอด Ai-Cash
               $ch_aicash_01 = DB::select(" select * from customers where id=".$request->customer_id_fk." ");
               // return($ch_aicash_01[0]->ai_cash);
               $ch_aicash_02 = DB::select(" select * from db_add_ai_cash where id=".$request->id." ");
               // return($ch_aicash_02[0]->aicash_amt);

               if($ch_aicash_02[0]->aicash_amt>$ch_aicash_01[0]->ai_cash){
                 return 'no';
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



    public function ajaxSaveGiftvoucherCode(Request $request)
    {
        if($request->ajax()){
            // return $request;
            // dd();
              if( !empty(@$request->giftvoucher_code_id_fk) ){
                $sRow = \App\Models\Backend\GiftvoucherCode::find($request->giftvoucher_code_id_fk );
              }else{
                $sRow = new \App\Models\Backend\GiftvoucherCode;
              }

              $sRow->descriptions = $request->descriptions;
              $sRow->pro_sdate = $request->pro_sdate;
              $sRow->pro_edate = $request->pro_edate;
              $sRow->created_at = date('Y-m-d H:i:s');
              $sRow->save();

              if($sRow->id){
                $insertData = array(
                 "giftvoucher_code_id_fk"=>$sRow->id,
                 "customer_code"=>$request->customer_code,
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

            DB::select(" UPDATE db_frontstore SET
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


    if($request->purchase_type_id_fk!=5){
    }else{

        // Gift Voucher

            /*
                $pay_type_id
                  1 เงินสด
                  2 เงินสด + Ai-Cash
                  3 เครดิต + เงินสด
                  4 เครดิต + เงินโอน
                  5 เครดิต + Ai-Cash
                  6 เงินโอน + เงินสด
                  7 เงินโอน + Ai-Cash
            */

            $pay_type_id = $request->pay_type_id;
            $frontstore_id =  $request->frontstore_id ;
            $sum_price = str_replace(',','',$request->sum_price);
            $shipping_price = str_replace(',','',$request->shipping_price);

            $sum_price = ($sum_price+$shipping_price) ;

            $gift_voucher_cost = str_replace(',','',$request->gift_voucher_cost);   // ที่มีอยู่
            $gift_voucher_price = str_replace(',','',$request->gift_voucher_price); // ที่กรอก
            $gift_voucher_price = $gift_voucher_price>$gift_voucher_cost?$gift_voucher_cost:$gift_voucher_price;
            $gift_voucher_price = $gift_voucher_price>$sum_price?$sum_price:$gift_voucher_price;


            if($gift_voucher_price>0){
                DB::select(" UPDATE db_frontstore SET gift_voucher_cost='$gift_voucher_cost',gift_voucher_price='$gift_voucher_price' WHERE id=$frontstore_id ");
            }

        }

        $rs = DB::select(" SELECT * FROM db_frontstore WHERE id=$frontstore_id ");
        return response()->json($rs);

    }



  public function ajaxCalAddAiCashFrontstore(Request $request)
    {
        // return $request;
        // dd();
     /*
        $pay_type_id
          1 เงินสด
          2 เงินสด + Ai-Cash
          3 เครดิต + เงินสด
          4 เครดิต + เงินโอน
          5 เครดิต + Ai-Cash
          6 เงินโอน + เงินสด
          7 เงินโอน + Ai-Cash
          */

        $pay_type_id = $request->pay_type_id;
        $id =  $request->id ;

        // return $pay_type_id;
        // return $id;
        // dd();
        // if($pay_type_id==''){

        // Clear ก่อน
 //        DB::select(" UPDATE db_add_ai_cash SET pay_type_id=$pay_type_id , cash_pay='0', credit_price='0', fee='0', fee_amt='0', charger_type='0', sum_credit_price='0', total_amt='0',transfer_money_datetime=null,file_slip=null WHERE (id='$id') ");
 // $pay_type_id = request('pay_type_id');
            DB::select(" UPDATE db_add_ai_cash SET pay_type_id=$pay_type_id , cash_pay='0', credit_price='0', fee='0', fee_amt='0', charger_type='0', sum_credit_price='0', total_amt='0',account_bank_id=0,transfer_price=0,transfer_money_datetime=null,file_slip=null WHERE (id='$id') ");
        // }

        $sum_price = str_replace(',','',$request->aicash_amt);

        // return $sum_price;
        // dd();


        if($pay_type_id==5){
            DB::select(" UPDATE db_add_ai_cash SET cash_price=($sum_price),cash_pay=($sum_price),total_amt=($sum_price) WHERE id=$id ");
        }

         // dd();

        if($pay_type_id==7){


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


        // return $pay_type_id;
        // dd();
     if($pay_type_id==8){

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



        if($pay_type_id==10){

                    if(empty($request->transfer_price)){
                        exit;
                    }
                    $transfer_price = str_replace(',','',$request->transfer_price);
                    $transfer_price = $transfer_price>$sum_price?$sum_price:$transfer_price;
                    $transfer_money_datetime = $request->transfer_money_datetime;
                    DB::select(" UPDATE db_add_ai_cash SET transfer_price=$transfer_price,cash_price=($sum_price-$transfer_price),cash_pay=($sum_price-$transfer_price),transfer_money_datetime='$transfer_money_datetime',total_amt=($sum_price) WHERE id=$id ");

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


    public function ajaxFifoApproved(Request $request)
    {
        // return $request;
        // dd();

        if($request->ajax()){
            // if($request->id){
                DB::select(" UPDATE db_pick_warehouse_fifo_topicked SET status='1' WHERE (status='0') ");
                // DB::select(' UPDATE db_pick_pack_packing_code SET status_picked=1 WHERE id in ('.$request->picking_id.') ');

                // return response()->json($rs);
            // }


          // $c = "SELECT db_frontstore.invoice_code FROM
          //   db_frontstore_products_list
          //   Left Join db_frontstore ON db_frontstore_products_list.frontstore_id_fk = db_frontstore.id
          //   WHERE  db_frontstore_products_list.product_id_fk in(SELECT product_id_fk FROM db_pick_warehouse_fifo_topicked where status=1)";

          // $sTable = DB::select(" SELECT * from db_products_fifo_bill where recipient_code in ($c) ");

        DB::select(" TRUNCATE TABLE db_pick_warehouse_tmp; ");
          DB::select(" 

          INSERT INTO db_pick_warehouse_tmp (invoice_code, product_code, product_name, amt, product_unit, amt_get, status, status_scan_qrcode, product_id_fk, created_at, updated_at) 
          SELECT
          db_frontstore.invoice_code,
          (SELECT product_code FROM products WHERE id=db_frontstore_products_list.product_id_fk limit 1) as product_code,
          (SELECT product_name FROM products_details WHERE product_id_fk=db_frontstore_products_list.product_id_fk and lang_id=1 limit 1) as product_name,
          db_frontstore_products_list.amt,
          dataset_product_unit.product_unit,
          0,0,0,
          db_frontstore_products_list.product_id_fk,
          now(),
          now()
          FROM
          db_frontstore_products_list
          Left Join db_frontstore ON db_frontstore_products_list.frontstore_id_fk = db_frontstore.id
          Left Join dataset_product_unit ON db_frontstore_products_list.product_unit_id_fk = dataset_product_unit.id
          WHERE db_frontstore_products_list.product_id_fk in(SELECT product_id_fk FROM db_pick_warehouse_fifo_topicked) 
          AND db_frontstore.invoice_code<>'' ");


        }
    }



    public function ajaxSetProductToBil(Request $request)
    {
        if($request->ajax()){
            DB::select(" UPDATE db_pick_warehouse_tmp SET amt_get = db_pick_warehouse_tmp.amt ");
        }
    }



    public function ajaxMapConsignments(Request $request)
    {
        if($request->ajax()){

            // DB::select(" TRUNCATE db_pick_warehouse_consignments ; ");
            // DB::select(" INSERT IGNORE INTO db_pick_warehouse_consignments(invoice_code) SELECT invoice_code FROM db_pick_warehouse_tmp ; ");
            // DB::select(" UPDATE
            //   db_pick_warehouse_consignments
            //   Inner Join db_consignments_import ON db_pick_warehouse_consignments.invoice_code = db_consignments_import.recipient_code
            //   SET
            //   db_pick_warehouse_consignments.consignments=
            //   db_consignments_import.consignment_no ");

            DB::select(" UPDATE
              db_consignments
              Inner Join db_consignments_import ON db_consignments.recipient_code = db_consignments_import.recipient_code
              SET
              db_consignments.consignment_no=
              db_consignments_import.consignment_no ");
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


    public function ajaxProcessStockcard(Request $request)
    {
        // return  $request->product_id_fk ;
        // product_id_fk: "2", start_date: "2021-04-02", end_date: "2021-04-02", _token: "zNKz86gXYZxaj7qtsvaDUvs0WR12I5aBW2LhGtYj" 
        // dd();
        if($request->ajax()){

              DB::select(" TRUNCATE db_stock_card_tmp; ");
              DB::select(" TRUNCATE db_stock_card; ");

              DB::select(" INSERT INTO db_stock_card_tmp (details) VALUES ('ยอดคงเหลือยกมา') ") ;

           if($request->product_id_fk){
          // ดึงจากตารางรับเข้า > db_general_receive
               $value=DB::table('db_general_receive')
                ->where('product_id_fk', $request->product_id_fk)
                ->where('lot_number', $request->lot_number)
                ->where('approve_status', '1')
                ->whereBetween('pickup_firstdate', [$request->start_date, $request->end_date])
                ->get();
                if($value->count() > 0){
                   DB::select(" INSERT INTO db_stock_card_tmp (product_id_fk, lot_number, details, ref_inv, amt_in,action_user,approver, action_date) 
                  SELECT
                  db_general_receive.product_id_fk, db_general_receive.lot_number, concat('รับเข้า > ',dataset_product_in_cause.txt_desc), db_general_receive.id, db_general_receive.amt,db_general_receive.recipient,db_general_receive.approver, db_general_receive.created_at
                  FROM
                  db_general_receive 
                  left Join dataset_product_in_cause ON db_general_receive.product_in_cause_id_fk = dataset_product_in_cause.id
                  WHERE product_id_fk='".$request->product_id_fk."' AND lot_number='".$request->lot_number."' AND approve_status=1 AND pickup_firstdate between '".$request->start_date."' AND '".$request->end_date."' ") ;
                }
            }

            if($request->product_id_fk){
          // ดึงจากตารางนำออก > db_general_takeout
               $value=DB::table('db_general_takeout')
                ->where('product_id_fk', $request->product_id_fk)
                ->where('lot_number', $request->lot_number)
                ->where('approve_status', '1')
                ->whereBetween('pickup_firstdate', [$request->start_date, $request->end_date])
                ->get();
                if($value->count() > 0){
                   DB::select(" INSERT INTO db_stock_card_tmp (product_id_fk, lot_number, details, ref_inv, amt_out,action_user,approver, action_date) 
                  SELECT
                  db_general_takeout.product_id_fk, db_general_takeout.lot_number, concat('นำออก > ',dataset_product_out_cause.txt_desc), db_general_takeout.id, db_general_takeout.amt,db_general_takeout.recipient,db_general_takeout.approver, db_general_takeout.created_at
                  FROM
                  db_general_takeout 
                  Left Join dataset_product_out_cause ON db_general_takeout.product_out_cause_id_fk = dataset_product_out_cause.id
                  WHERE product_id_fk='".$request->product_id_fk."' AND lot_number='".$request->lot_number."' AND approve_status=1 AND pickup_firstdate between '".$request->start_date."' AND '".$request->end_date."' ") ; 
                }              
              
            }

            if($request->product_id_fk){
          // ดึงจากตารางกาปรับยอดคลัง > db_stocks_account_code , db_stocks_account

              $value=DB::select("
                SELECT
                  db_stocks_account.product_id_fk, db_stocks_account.lot_number, 'ปรับปรุงยอด (ลด)', db_stocks_account_code.id,
                  (CASE WHEN  db_stocks_account.amt_diff <0 THEN  db_stocks_account.amt_diff * (-1) ELSE  db_stocks_account.amt_diff END)
                  ,db_stocks_account_code.action_user,db_stocks_account_code.approver, db_stocks_account_code.updated_at
                  FROM
                  db_stocks_account LEFT JOIN db_stocks_account_code ON db_stocks_account.stocks_account_code_id_fk=db_stocks_account_code.id
                  WHERE
                  db_stocks_account.amt_diff<0
                  AND
                  db_stocks_account.product_id_fk='".$request->product_id_fk."' AND db_stocks_account.lot_number='".$request->lot_number."' AND db_stocks_account_code.status_accepted=3 AND DATE(db_stocks_account_code.action_date) between '".$request->start_date."' AND '".$request->end_date."'
                ");

              if(count($value) > 0){
                  DB::select(" INSERT INTO db_stock_card_tmp (product_id_fk, lot_number, details, ref_inv, amt_out,action_user,approver, action_date) 
                  SELECT
                  db_stocks_account.product_id_fk, db_stocks_account.lot_number, 'ปรับปรุงยอด (ลด)', db_stocks_account_code.id,
                  (CASE WHEN  db_stocks_account.amt_diff <0 THEN  db_stocks_account.amt_diff * (-1) ELSE  db_stocks_account.amt_diff END)
                  ,db_stocks_account_code.action_user,db_stocks_account_code.approver, db_stocks_account_code.updated_at
                  FROM
                  db_stocks_account LEFT JOIN db_stocks_account_code ON db_stocks_account.stocks_account_code_id_fk=db_stocks_account_code.id
                  WHERE
                  db_stocks_account.amt_diff<0
                  AND
                  db_stocks_account.product_id_fk='".$request->product_id_fk."' AND db_stocks_account.lot_number='".$request->lot_number."' AND db_stocks_account_code.status_accepted=3 AND DATE(db_stocks_account_code.action_date) between '".$request->start_date."' AND '".$request->end_date."' ") ; 
                }
              
            }

            if($request->product_id_fk){     

                $value=DB::select("
                SELECT
                  db_stocks_account.product_id_fk, db_stocks_account.lot_number, 'ปรับปรุงยอด (เพิ่ม)', db_stocks_account_code.id,
                  db_stocks_account.amt_diff
                  ,db_stocks_account_code.action_user,db_stocks_account_code.approver, db_stocks_account_code.updated_at
                  FROM
                  db_stocks_account LEFT JOIN db_stocks_account_code ON db_stocks_account.stocks_account_code_id_fk=db_stocks_account_code.id
                  WHERE
                  db_stocks_account.amt_diff>0
                  AND
                  db_stocks_account.product_id_fk='".$request->product_id_fk."' AND db_stocks_account.lot_number='".$request->lot_number."' AND db_stocks_account_code.status_accepted=3 AND DATE(db_stocks_account_code.action_date) between '".$request->start_date."' AND '".$request->end_date."'
                ");

                if(count($value) > 0){
                  
                   DB::select(" INSERT INTO db_stock_card_tmp (product_id_fk, lot_number, details, ref_inv, amt_in,action_user,approver, action_date) 
                  SELECT
                  db_stocks_account.product_id_fk, db_stocks_account.lot_number, 'ปรับปรุงยอด (เพิ่ม)', db_stocks_account_code.id,
                  db_stocks_account.amt_diff
                  ,db_stocks_account_code.action_user,db_stocks_account_code.approver, db_stocks_account_code.updated_at
                  FROM
                  db_stocks_account LEFT JOIN db_stocks_account_code ON db_stocks_account.stocks_account_code_id_fk=db_stocks_account_code.id
                  WHERE
                  db_stocks_account.amt_diff>0
                  AND
                  db_stocks_account.product_id_fk='".$request->product_id_fk."' AND db_stocks_account.lot_number='".$request->lot_number."' AND db_stocks_account_code.status_accepted=3 AND DATE(db_stocks_account_code.action_date) between '".$request->start_date."' AND '".$request->end_date."' ") ; 

              }

            }

          if($request->product_id_fk){
          // ดึงจาก การเบิก/ยืม > db_products_borrow_code > db_products_borrow_details
            $value=DB::select("
              SELECT
              db_products_borrow_details.product_id_fk, db_products_borrow_details.lot_number, 'เบิก/ยืม', db_products_borrow_code.borrow_number,
              db_products_borrow_details.amt
              ,db_products_borrow_code.action_user,db_products_borrow_code.approver, db_products_borrow_code.created_at
              FROM
              db_products_borrow_details LEFT JOIN db_products_borrow_code ON db_products_borrow_details.products_borrow_code_id=db_products_borrow_code.id
              WHERE
              db_products_borrow_code.approve_status=1 AND 
              db_products_borrow_code.created_at BETWEEN '".$request->start_date."' AND '".$request->end_date."' AND
              db_products_borrow_details.product_id_fk='".$request->product_id_fk."' AND db_products_borrow_details.lot_number='".$request->lot_number."'
                ");

                if(count($value) > 0){

                     DB::select(" INSERT INTO db_stock_card_tmp (product_id_fk, lot_number, details, ref_inv, amt_out,action_user,approver, action_date) 
                      SELECT
                        db_products_borrow_details.product_id_fk, db_products_borrow_details.lot_number, 'เบิก/ยืม', db_products_borrow_code.borrow_number,
                        db_products_borrow_details.amt
                        ,db_products_borrow_code.action_user,db_products_borrow_code.approver, db_products_borrow_code.created_at
                        FROM
                        db_products_borrow_details LEFT JOIN db_products_borrow_code ON db_products_borrow_details.products_borrow_code_id=db_products_borrow_code.id
                        WHERE
                        db_products_borrow_code.approve_status=1 AND 
                        db_products_borrow_code.created_at BETWEEN '".$request->start_date."' AND '".$request->end_date."' AND
                        db_products_borrow_details.product_id_fk='".$request->product_id_fk."' AND db_products_borrow_details.lot_number='".$request->lot_number."' ") ; 
                }                
          } 

          if($request->product_id_fk){
           // ดึงจาก การโอนภายในสาขา > db_transfer_warehouses_code > db_transfer_warehouses_details
            $value=DB::select("
              SELECT
              db_transfer_warehouses_details.product_id_fk,
              db_transfer_warehouses_details.lot_number
              ,'โอนภายในสาขา',
              db_transfer_warehouses_details.id,
              db_transfer_warehouses_details.amt,
              db_transfer_warehouses_details.action_user,
              db_transfer_warehouses_details.approver,
              db_transfer_warehouses_details.updated_at
              FROM
              db_transfer_warehouses_details
              Left Join db_transfer_warehouses_code ON db_transfer_warehouses_details.transfer_warehouses_code_id = db_transfer_warehouses_code.id
              WHERE
              db_transfer_warehouses_code.approve_status=1 AND 
              db_transfer_warehouses_code.action_date BETWEEN '".$request->start_date."' AND '".$request->end_date."' AND
              db_transfer_warehouses_details.product_id_fk='".$request->product_id_fk."' AND db_transfer_warehouses_details.lot_number='".$request->lot_number."'
                ");

                if(count($value) > 0){
                      
                      DB::select(" INSERT INTO db_stock_card_tmp (product_id_fk, lot_number, details, ref_inv, amt_out,action_user,approver, action_date) 
                      SELECT
                      db_transfer_warehouses_details.product_id_fk,
                      db_transfer_warehouses_details.lot_number
                      ,'โอนภายในสาขา',
                      db_transfer_warehouses_code.id,
                      db_transfer_warehouses_details.amt,
                      db_transfer_warehouses_details.action_user,
                      db_transfer_warehouses_code.approver,
                      db_transfer_warehouses_code.updated_at
                      FROM
                      db_transfer_warehouses_details
                      Left Join db_transfer_warehouses_code ON db_transfer_warehouses_details.transfer_warehouses_code_id = db_transfer_warehouses_code.id
                      WHERE
                      db_transfer_warehouses_code.approve_status=1 AND 
                      db_transfer_warehouses_code.action_date BETWEEN '".$request->start_date."' AND '".$request->end_date."' AND
                      db_transfer_warehouses_details.product_id_fk='".$request->product_id_fk."' AND db_transfer_warehouses_details.lot_number='".$request->lot_number."' 

                      ") ; 

                }                

          }               
              
         if($request->product_id_fk){
           // ดึงจาก การโอนระหว่างสาขา > db_transfer_branch_code > db_transfer_branch_details
             $value=DB::select("
                    SELECT
                    db_transfer_branch_details.product_id_fk,
                    db_transfer_branch_details.lot_number
                    ,'โอนระหว่างสาขา',
                    db_transfer_branch_code.id,
                    db_transfer_branch_details.amt,
                    db_transfer_branch_details.action_user,
                    db_transfer_branch_code.approver,
                    db_transfer_branch_code.updated_at
                    FROM
                    db_transfer_branch_details
                    Left Join db_transfer_branch_code ON db_transfer_branch_details.transfer_branch_code_id = db_transfer_branch_code.id
                    WHERE
                    db_transfer_branch_code.approve_status=1 AND
                    db_transfer_branch_code.action_date BETWEEN '".$request->start_date."' AND '".$request->end_date."' AND
                      db_transfer_branch_details.product_id_fk='".$request->product_id_fk."' AND db_transfer_branch_details.lot_number='".$request->lot_number."' 
                ");

                if(count($value) > 0){
                       DB::select(" INSERT INTO db_stock_card_tmp (product_id_fk, lot_number, details, ref_inv, amt_out,action_user,approver, action_date) 
                        SELECT
                        db_transfer_branch_details.product_id_fk,
                        db_transfer_branch_details.lot_number
                        ,'โอนระหว่างสาขา',
                        db_transfer_branch_code.id,
                        db_transfer_branch_details.amt,
                        db_transfer_branch_details.action_user,
                        db_transfer_branch_code.approver,
                        db_transfer_branch_code.updated_at
                        FROM
                        db_transfer_branch_details
                        Left Join db_transfer_branch_code ON db_transfer_branch_details.transfer_branch_code_id = db_transfer_branch_code.id
                        WHERE
                        db_transfer_branch_code.approve_status=1 AND
                        db_transfer_branch_code.action_date BETWEEN '".$request->start_date."' AND '".$request->end_date."' AND
                          db_transfer_branch_details.product_id_fk='".$request->product_id_fk."' AND db_transfer_branch_details.lot_number='".$request->lot_number."' 

                      ") ; 
                      
                }                

          } 

         if($request->product_id_fk){
           // ดึงจาก จ่ายสินค้าตามใบเสร็จ
             $value=DB::select("
                   SELECT
                          db_frontstore_products_list.product_id_fk,
                          db_pick_warehouse_fifo_topicked.lot_number,
                          'จ่ายสินค้าตามใบเสร็จ',
                          db_frontstore.invoice_code,
                          db_frontstore_products_list.amt,
                          db_frontstore.action_user,
                          db_consignments.approver,
                          db_consignments.sent_date
                          FROM
                          db_frontstore_products_list
                          Left Join db_frontstore ON db_frontstore_products_list.frontstore_id_fk = db_frontstore.id
                          Left Join db_consignments ON db_frontstore.invoice_code = db_consignments.recipient_code
                          Left Join db_pick_warehouse_fifo_topicked ON db_frontstore_products_list.product_id_fk = db_pick_warehouse_fifo_topicked.product_id_fk AND db_frontstore_products_list.amt = db_pick_warehouse_fifo_topicked.amt
                          WHERE
                          db_consignments.status_sent=1
                          AND
                          db_frontstore_products_list.product_id_fk=1 AND
                          db_consignments.sent_date BETWEEN '".$request->start_date."' AND '".$request->end_date."' AND
                          db_frontstore_products_list.product_id_fk='".$request->product_id_fk."' AND db_pick_warehouse_fifo_topicked.lot_number='".$request->lot_number."'
                ");

                if(count($value) > 0){
                       DB::select(" 
                        INSERT INTO db_stock_card_tmp (product_id_fk, lot_number, details, ref_inv, amt_out,action_user,approver, action_date) 
                        SELECT
                          db_frontstore_products_list.product_id_fk,
                          db_pick_warehouse_fifo_topicked.lot_number,
                          'จ่ายสินค้าตามใบเสร็จ',
                          db_frontstore.invoice_code,
                          db_frontstore_products_list.amt,
                          db_frontstore.action_user,
                          db_consignments.approver,
                          db_consignments.sent_date
                          FROM
                          db_frontstore_products_list
                          Left Join db_frontstore ON db_frontstore_products_list.frontstore_id_fk = db_frontstore.id
                          Left Join db_consignments ON db_frontstore.invoice_code = db_consignments.recipient_code
                          Left Join db_pick_warehouse_fifo_topicked ON db_frontstore_products_list.product_id_fk = db_pick_warehouse_fifo_topicked.product_id_fk AND db_frontstore_products_list.amt = db_pick_warehouse_fifo_topicked.amt
                          WHERE
                          db_consignments.status_sent=1
                          AND
                          db_frontstore_products_list.product_id_fk=1 AND
                          db_consignments.sent_date BETWEEN '".$request->start_date."' AND '".$request->end_date."' AND
                          db_frontstore_products_list.product_id_fk='".$request->product_id_fk."' AND db_pick_warehouse_fifo_topicked.lot_number='".$request->lot_number."'
                      ") ; 
                      
                }                

          } 


          // ยอดยกมา

              $x = DB::select(" SELECT sum(amt) as amt  FROM db_stocks_in WHERE date(action_date) < '".$request->start_date."' AND  product_id_fk = '".$request->product_id_fk."' AND lot_number ='".$request->lot_number."' ORDER BY action_date desc ; ");

              $d_x = DB::select(" SELECT action_date FROM db_stocks_in WHERE date(action_date) < '".$request->start_date."' AND  product_id_fk = '".$request->product_id_fk."' AND lot_number ='".$request->lot_number."' ORDER BY action_date desc LIMIT 1; ");

              $y = DB::select(" SELECT sum(amt) as amt  FROM db_stocks_out WHERE date(action_date) < '".$request->start_date."' AND  product_id_fk = '".$request->product_id_fk."' AND lot_number ='".$request->lot_number."' ORDER BY action_date desc ; ");

              $d_y = DB::select(" SELECT action_date FROM db_stocks_out WHERE date(action_date) < '".$request->start_date."' AND  product_id_fk = '".$request->product_id_fk."' AND lot_number ='".$request->lot_number."' ORDER BY action_date desc LIMIT 1; ");

              $r = $x[0]->amt - $y[0]->amt ;

              $d = @$d_x[0]->action_date > @$d_y[0]->action_date ? @$d_x[0]->action_date : @$d_y[0]->action_date;

              if(!empty($d)){
                DB::select(" UPDATE db_stock_card_tmp SET action_date='$d' ,amt_in='$r' WHERE (id='1') ; ");
              }else{
                DB::select(" UPDATE db_stock_card_tmp SET action_date=NULL ,amt_in='$r' WHERE (id='1') ; ");
              }

           // final > นำเข้าตารางจริง   
              DB::select(" INSERT IGNORE INTO db_stock_card select * from db_stock_card_tmp order by action_date ; ");

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
    public function ajaxDeleteQrcodeProduct(Request $request)
    {

      if($request->ajax()){
        DB::select(" UPDATE db_pick_warehouse_qrcode SET qr_code = '' where id = $request->id ");
      }

    }




    public function ajaxGetAmtInStock(Request $request)
    {

      if($request->ajax()){
         // $r= DB::select(" SELECT amt FROM db_stocks where product_id_fk = $request->product_id_fk AND id = $request->id ");
         // return @$r[0]->amt;
          $rs = DB::select(" 
            SELECT db_stocks.*,dataset_product_unit.product_unit,
            dataset_business_location.txt_desc AS business_location,
            branchs.b_name AS branch,
            warehouse.w_name,
            zone.z_name,
            shelf.s_name
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
         
            DB::select(" TRUNCATE db_consignments; ");

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
              db_frontstore.invoice_code,
              customers_addr_sent.recipient_name,
              customers_addr_sent.house_no,
              customers_addr_sent.house_name,
              customers_addr_sent.moo,
              customers_addr_sent.road,
              customers_addr_sent.soi,
              customers_addr_sent.district,
              customers_addr_sent.district_sub,
              customers_addr_sent.province,
              customers_addr_sent.zipcode,
              customers_addr_sent.tel,
              customers_addr_sent.tel_home,
              customers_addr_sent.id_choose
              FROM
              db_frontstore
              Left Join customers_addr_sent ON db_frontstore.address_sent_id_fk = customers_addr_sent.id
              Left Join dataset_amphures ON customers_addr_sent.district_id = dataset_amphures.id
               ");

            foreach ($data_addr as $key => $v) {
              if($v->recipient_name!='' && $v->invoice_code!=''){
                 $addr = $v->house_no." ";
                 $addr .= $v->house_name." ";
                 $addr .= $v->moo." ";
                 $addr .= $v->road." ";
                 $addr .= $v->soi." ";
                 $addr .= $v->district_sub." ";
                 $addr .= $v->district." ";
                 $addr .= $v->province." ";
                 $addr .= $v->zipcode." ";
                 $addr .= $v->tel." ";
                 $addr .= $v->tel_home." ";
                 DB::select(" UPDATE db_consignments set recipient_name='".$v->recipient_name."',address='$addr' WHERE recipient_code='".$v->invoice_code."'  ");
               }
             
            }

            DB::select(" UPDATE db_consignments set address='ไม่ได้ระบุที่อยู่ กรุณาตรวจสอบ' WHERE  address is null  ");


      }

    }


}
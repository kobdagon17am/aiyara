<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;
use App\Models\Backend\Branchs;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Backend\RequisitionBetweenBranch;
use Yajra\DataTables\DataTables;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use File;

class ReportDataController extends Controller
{
    public function index()
    {
      $Products = DB::select("SELECT products.id as product_id,
      products.product_code,
      (CASE WHEN products_details.product_name is null THEN '* ไม่ได้กรอกชื่อสินค้า' ELSE products_details.product_name END) as product_name
      FROM
      products_details
      Left Join products ON products_details.product_id_fk = products.id
      WHERE lang_id=1
      order by products.product_code

      ");

      $Check_stock = \App\Models\Backend\Check_stock::get();

      $lot_number = DB::select(" select lot_number from db_stocks where business_location_id_fk=".@\Auth::user()->business_location_id_fk." GROUP BY lot_number  ");


      $User_branch_id = \Auth::user()->branch_id_fk;
      // $sBusiness_location = \App\Models\Backend\Business_location::when(auth()->user()->permission !== 1, function ($query) {
      //   return $query->where('id', auth()->user()->business_location_id_fk);
      // })->get();
      $sBusiness_location = \App\Models\Backend\Business_location::get();
      $sBranchs = \App\Models\Backend\Branchs::when(auth()->user()->permission !== 1, function ($query) {
        return $query->where('id', auth()->user()->branch_id_fk);
      })->get();
      $Warehouse = \App\Models\Backend\Warehouse::get();
      // dd($Warehouse);
      // dd(\Auth::user()->branch_id_fk);
      // if(@\Auth::user()->permission==1){
        $Warehouse = \App\Models\Backend\Warehouse::get();
      // }else{
      //   $Warehouse = \App\Models\Backend\Warehouse::where('branch_id_fk',\Auth::user()->branch_id_fk)->get();
      // }

      $Zone = \App\Models\Backend\Zone::get();
      $Shelf = \App\Models\Backend\Shelf::get();
        return view('backend.report_data.index',[
          'Products'=>$Products,
          'Check_stock'=>$Check_stock,
          'Warehouse'=>$Warehouse,
          'Zone'=>$Zone,'Shelf'=>$Shelf,
          'sBranchs'=>$sBranchs,
          'User_branch_id'=>$User_branch_id,
          'sBusiness_location'=>$sBusiness_location,
          'lot_number'=>$lot_number
        ]);
    }

    // public function inventory()
    // {
    //     return view('backend.report_data.inventory');
    // }

    public function export_excel(Request $request)
		{
      // dd($request->all());
			$spreadsheet = new Spreadsheet();
			$amt_sheet = 1;

			 $styleArray = array(
			   'font'  => array(
			        'bold'  => true,
			        'color' => array('rgb' => '00000'),
			        'size'  => 10,
			        'name'  => 'Verdana'
         ),
        //  'fill' => array(
        //   'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
        //   'startColor' => array('argb' => 'D9D9D9')
        //  ),
         'alignment' => [
          'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
          'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
      ],

        );

			for ($j=0; $j < $amt_sheet ; $j++) {

				if($j>0){
					$spreadsheet->createSheet();
				}

				$spreadsheet->setActiveSheetIndex($j);
				$sheet = $spreadsheet->getActiveSheet();
				$sheet->setTitle("Sheet".($j+1));

        $head = 2;
        $date = 1;
        $td_data = 3;
        // $sheet->mergeCells("A".$date.":E".$date);
        $sheet->setCellValue('A'.$date, 'วันที่ '.$request->startDate_data.' ถึง '.$request->endDate_data);

        if($request->report_data=='inventory_in'){
          $sheet->setCellValue('A'.$head, 'รหัสสินค้า');
          $sheet->setCellValue('B'.$head, 'ชื่อสินค้า');
          $sheet->setCellValue('C'.$head, 'หน่วยนับ');
          $sheet->setCellValue('D'.$head, 'จำนวนยกมา');
          $sheet->setCellValue('E'.$head, 'รับเข้า');
          $sheet->setCellValue('F'.$head, 'จำนวนยกไป');
        }

        if($request->report_data=='inventory_out'){
          $sheet->setCellValue('A'.$head, 'รหัสสินค้า');
          $sheet->setCellValue('B'.$head, 'ชื่อสินค้า');
          $sheet->setCellValue('C'.$head, 'หน่วยนับ');
          $sheet->setCellValue('D'.$head, 'จำนวนยกมา');
          $sheet->setCellValue('E'.$head, 'จ่ายออก');
          $sheet->setCellValue('F'.$head, 'จำนวนยกไป');
        }

        if($request->report_data=='inventory_borrow'){
          $sheet->setCellValue('A'.$head, 'รหัสสินค้า');
          $sheet->setCellValue('B'.$head, 'ชื่อสินค้า');
          $sheet->setCellValue('C'.$head, 'หน่วยนับ');
          $sheet->setCellValue('D'.$head, 'ยอดยืม');
          $sheet->setCellValue('E'.$head, 'ยอดคืน');
          $sheet->setCellValue('F'.$head, 'คงค้าง');
        }

        if($request->report_data=='inventory_claim'){
          $sheet->setCellValue('A'.$head, 'รหัสสินค้า');
          $sheet->setCellValue('B'.$head, 'ชื่อสินค้า');
          $sheet->setCellValue('C'.$head, 'หน่วยนับ');
          $sheet->setCellValue('D'.$head, 'ยอดเคลม');
        }

        if($request->report_data=='inventory_remain'){
          $sheet->setCellValue('A'.$head, 'รหัสสินค้า');
          $sheet->setCellValue('B'.$head, 'ชื่อสินค้า');
          $sheet->setCellValue('C'.$head, 'หน่วยนับ');
          $sheet->setCellValue('D'.$head, 'ยอดคงเหลือ');
        }

        // if($request->report_data=='sale_report'){
        // //   $sheet->setCellValue('A'.$head, 'ลำดับ');
        // //   $sheet->setCellValue('B'.$head, 'วันที่อนุมัติ');
        // //   $sheet->setCellValue('C'.$head, 'รายการ');
        // //   $sheet->setCellValue('D'.$head, 'จำนวน');
        // //   $sheet->setCellValue('E'.$head, 'ผู้ขาย');

        //   $sheet->setCellValue('A'.$head, 'ลำดับ');
        //   $sheet->setCellValue('B'.$head, 'รายการ');
        //   $sheet->setCellValue('C'.$head, 'จำนวน');
        //   $sheet->setCellValue('D'.$head, 'ผู้ขาย');
        // }

				// $sheet->setCellValue('A1', 'รหัสสินค้า');
				// $sheet->setCellValue('B1', 'ชื่อสินค้า');
				// $sheet->setCellValue('C1', 'หน่วยนับ');
				// $sheet->setCellValue('D1', 'จำนวนยกมา');
				// $sheet->setCellValue('E1', 'ยอดเข้า');
				// $sheet->setCellValue('F1', 'ยอดออก');
				// $sheet->setCellValue('G1', 'Postcode (รหัสไปรษณีย์ผู้รับ)');
				// $sheet->setCellValue('H1', 'Mobile (เบอร์มือถือผู้รับ)');
				// $sheet->setCellValue('I1', 'Contact Person (ชื่อผู้ติดต่อ)');
				// $sheet->setCellValue('J1', 'Phone No. (เบอร์ผู้รับ)');
				// $sheet->setCellValue('K1', 'Email (อีเมลผู้รับ)');
				// $sheet->setCellValue('L1', 'Declare Value (มูลค่าสินค้า)');
				// $sheet->setCellValue('M1', 'COD Amount (ยอดเก็บเงินปลายทาง)');
				// $sheet->setCellValue('N1', 'Remark (หมายเหตุ)');
				// $sheet->setCellValue('O1', 'Total Box (จำนวนกล่อง)');
				// $sheet->setCellValue('P1', 'Sat Del (จัดส่งวันเสาร์)');
				// $sheet->setCellValue('Q1', 'HCR (Y/N)');
				// $sheet->setCellValue('R1', 'INVR (Y/N)');
				// $sheet->setCellValue('S1', 'Service Code (รหัสบริการ)');

				$sheet->getStyle('A1:S1')->applyFromArray($styleArray);
        $sheet->getStyle('A2:S2')->applyFromArray($styleArray);

        if($request->report_data=='inventory_remain' || $request->report_data=='inventory_in' || $request->report_data=='inventory_out' || $request->report_data=='inventory_borrow'){

          $Stock = \App\Models\Backend\Check_stock::select('db_stocks.product_id_fk',
          'db_stocks.updated_at',
          'db_stocks.warehouse_id_fk',
          'db_stocks.zone_id_fk',
          'db_stocks.shelf_id_fk',
          'db_stocks.shelf_floor',
          'dataset_product_unit.product_unit',
          'products.product_code',
            DB::raw("(CASE WHEN products_details.product_name is null THEN '* ไม่ได้กรอกชื่อสินค้า' ELSE products_details.product_name END) as product_name"),
          )
          ->join('products','products.id','db_stocks.product_id_fk')
          ->join('products_details','products_details.product_id_fk','db_stocks.product_id_fk')
          ->join('products_units','products_units.product_id_fk','db_stocks.product_id_fk')
          ->join('dataset_product_unit','dataset_product_unit.id','products_units.product_unit_id_fk')
          ->where('db_stocks.business_location_id_fk', "=", $request->business_location_id_fk)
          ->where('db_stocks.branch_id_fk', "=", $request->branch_id_fk)
          // ->distinct('db_stocks.product_id_fk')
          ->groupBy('db_stocks.product_id_fk')
          ->orderBy('products.product_code','asc')
          ->get();
          if($request->startDate_data <= date('Y-m-d') ){
            // $Stock = $Stock->where(DB::raw("(DATE_FORMAT(db_stocks.updated_at,'%Y-%m-%d'))"), "<=", $request->startDate_data);
            // $Stock = $Stock->where(DB::raw("(DATE_FORMAT(db_stocks.updated_at,'%Y-%m-%d'))"), "<=", $request->endDate_data);
            $Stock = $Stock->whereBetween('updated_at', [$request->startDate_data.' 00:00:00', $request->endDate_data.' 59:59:59']);
            // $Stock = $Stock->where('updated_at','>=',$request->startDate_data.' 00:00:00')->where('updated_at','>=',$request->endDate_data.' 59:59:59');
            // $Stock = $Stock->whereBetween('updated_at', ['2022-01-01 16:36:12', '2022-01-30 16:36:12']);
            // $Stock = $Stock->where(DB::raw("(DATE_FORMAT(updated_at,'Y-m-d'))"), "==", $request->startDate_data);
            // $Stock = $Stock->where(DB::raw("(DATE_FORMAT(updated_at,'Y-m-d'))"), "<=", $request->endDate_data);
            // dd($Stock);
          }else{
            $Stock = $Stock->where(DB::raw("(DATE_FORMAT(updated_at,'Y-m-d'))"), "<=", $request->endDate_data);
          }

          if($request->warehouse_id_fk!=''){
            $Stock = $Stock->where('warehouse_id_fk', "=", $request->warehouse_id_fk);
          }
          if($request->zone_id_fk!=''){
            $Stock = $Stock->where('zone_id_fk', "=", $request->zone_id_fk);
          }
          if($request->shelf_id_fk!=''){
            $Stock = $Stock->where('shelf_id_fk', "=", $request->shelf_id_fk);
          }
          if($request->shelf_floor!=''){
            $Stock = $Stock->where('shelf_floor', "=", $request->shelf_floor);
          }

          if(count($Stock)!=0){

               if($request->report_data=='inventory_in'){
                foreach($Stock as $key => $st){
                      $amt_top = 0;
                      $product_code = "";
                      $product_name = "";
                      $product_unit = "";
                      // ยอดรับเข้า
                      $Stock_movement_in = \App\Models\Backend\Stock_movement::
                      select(
                        // DB::raw('(CASE WHEN amt > 0 THEN sum(amt) ELSE 0 END) AS amt'),
                        DB::raw('sum(amt) AS sum'),
                        'product_id_fk',
                        'updated_at',
                        'warehouse_id_fk',
                        'zone_id_fk',
                        'shelf_id_fk',
                        'shelf_floor',
                        'product_id_fk',
                        'id',
                      )
                      ->where('product_id_fk',($st->product_id_fk?$st->product_id_fk:0))
                      ->where('business_location_id_fk', "=", $request->business_location_id_fk)
                      ->where('branch_id_fk', "=", $request->branch_id_fk)
                      ->where('in_out','1')
                      ->where('updated_at','>=',$request->startDate_data.' 00:00:00')
                      ->where('updated_at','>=',$request->endDate_data.' 59:59:59')
                      ->get();

                      if($request->warehouse_id_fk!=''){
                        $Stock_movement_in = $Stock_movement_in->where('warehouse_id_fk', "=", $request->warehouse_id_fk);
                      }
                      if($request->zone_id_fk!=''){
                        $Stock_movement_in = $Stock_movement_in->where('zone_id_fk', "=", $request->zone_id_fk);
                      }
                      if($request->shelf_id_fk!=''){
                        $Stock_movement_in = $Stock_movement_in->where('shelf_id_fk', "=", $request->shelf_id_fk);
                      }
                      if($request->shelf_floor!=''){
                        $Stock_movement_in = $Stock_movement_in->where('shelf_floor', "=", $request->shelf_floor);
                      }

                      $amt_balance_stock = @$Stock_movement_in[0]->sum?$Stock_movement_in[0]->sum:0;
                      $product_code = $st->product_code;
                      $product_name = $st->product_name;
                      $product_unit = $st->product_unit;
                      $amt_top += $amt_balance_stock;
                      $sheet->setCellValue('A'.($td_data+$key), $product_code);
                      $sheet->setCellValue('B'.($td_data+$key), $product_name);
                      $sheet->setCellValue('C'.($td_data+$key), $product_unit);
                      $sheet->setCellValue('D'.($td_data+$key), 0);
                      $sheet->setCellValue('E'.($td_data+$key), $amt_top);
                      $sheet->setCellValue('F'.($td_data+$key), $amt_top+0);
                  }
              }

              if($request->report_data=='inventory_out'){

                foreach($Stock as $key => $st){
                      $amt_top = 0;
                      $product_code = "";
                      $product_name = "";
                      $product_unit = "";
                      // ยอดรับเข้า
                      $Stock_movement_out = \App\Models\Backend\Stock_movement::
                      select(
                        // DB::raw('(CASE WHEN amt > 0 THEN sum(amt) ELSE 0 END) AS amt'),
                        DB::raw('sum(amt) AS sum'),
                        'product_id_fk',
                        'updated_at',
                        'warehouse_id_fk',
                        'zone_id_fk',
                        'shelf_id_fk',
                        'shelf_floor',
                        'product_id_fk',
                        'id',
                      )
                      ->where('product_id_fk',($st->product_id_fk?$st->product_id_fk:0))
                      ->where('business_location_id_fk', "=", $request->business_location_id_fk)
                      ->where('branch_id_fk', "=", $request->branch_id_fk)
                      ->where('in_out','2')
                      ->where('updated_at','>=',$request->startDate_data.' 00:00:00')
                      ->where('updated_at','>=',$request->endDate_data.' 59:59:59')
                      ->get();

                      if($request->warehouse_id_fk!=''){
                        $Stock_movement_out = $Stock_movement_out->where('warehouse_id_fk', "=", $request->warehouse_id_fk);
                      }
                      if($request->zone_id_fk!=''){
                        $Stock_movement_out = $Stock_movement_out->where('zone_id_fk', "=", $request->zone_id_fk);
                      }
                      if($request->shelf_id_fk!=''){
                        $Stock_movement_out = $Stock_movement_out->where('shelf_id_fk', "=", $request->shelf_id_fk);
                      }
                      if($request->shelf_floor!=''){
                        $Stock_movement_out = $Stock_movement_out->where('shelf_floor', "=", $request->shelf_floor);
                      }

                      $amt_balance_stock = @$Stock_movement_out[0]->sum?$Stock_movement_out[0]->sum:0;
                      $product_code = $st->product_code;
                      $product_name = $st->product_name;
                      $product_unit = $st->product_unit;
                      $amt_top += $amt_balance_stock;
                      $sheet->setCellValue('A'.($td_data+$key), $product_code);
                      $sheet->setCellValue('B'.($td_data+$key), $product_name);
                      $sheet->setCellValue('C'.($td_data+$key), $product_unit);
                      $sheet->setCellValue('D'.($td_data+$key), 0);
                      $sheet->setCellValue('E'.($td_data+$key), $amt_top);
                      $sheet->setCellValue('F'.($td_data+$key), $amt_top+0);
                  }
              }

              if($request->report_data=='inventory_borrow'){

                foreach($Stock as $key => $st){
                      $amt_top = 0;
                      $product_code = "";
                      $product_name = "";
                      $product_unit = "";
                      // ยอดยืม
                      $Stock_movement_out = \App\Models\Backend\Stock_movement::
                      select(
                        // DB::raw('(CASE WHEN amt > 0 THEN sum(amt) ELSE 0 END) AS amt'),
                        DB::raw('sum(amt) AS sum'),
                        'product_id_fk',
                        'updated_at',
                        'warehouse_id_fk',
                        'zone_id_fk',
                        'shelf_id_fk',
                        'shelf_floor',
                        'product_id_fk',
                      )
                      ->where('product_id_fk',($st->product_id_fk?$st->product_id_fk:0))
                      ->where('business_location_id_fk', "=", $request->business_location_id_fk)
                      ->where('branch_id_fk', "=", $request->branch_id_fk)
                      ->where('in_out','2')
                      ->where('ref_table','db_products_borrow_details')
                      ->where('updated_at','>=',$request->startDate_data.' 00:00:00')
                      ->where('updated_at','>=',$request->endDate_data.' 59:59:59')
                      ->get();

                      if($request->warehouse_id_fk!=''){
                        $Stock_movement_out = $Stock_movement_out->where('warehouse_id_fk', "=", $request->warehouse_id_fk);
                      }
                      if($request->zone_id_fk!=''){
                        $Stock_movement_out = $Stock_movement_out->where('zone_id_fk', "=", $request->zone_id_fk);
                      }
                      if($request->shelf_id_fk!=''){
                        $Stock_movement_out = $Stock_movement_out->where('shelf_id_fk', "=", $request->shelf_id_fk);
                      }
                      if($request->shelf_floor!=''){
                        $Stock_movement_out = $Stock_movement_out->where('shelf_floor', "=", $request->shelf_floor);
                      }

                      $amt_balance_stock_out = @$Stock_movement_out[0]->sum?$Stock_movement_out[0]->sum:0;

                      // ยอดคืน
                      $Stock_movement_in = \App\Models\Backend\Stock_movement::
                      select(
                        // DB::raw('(CASE WHEN amt > 0 THEN sum(amt) ELSE 0 END) AS amt'),
                        DB::raw('sum(amt) AS sum'),
                        'product_id_fk',
                        'updated_at',
                        'warehouse_id_fk',
                        'zone_id_fk',
                        'shelf_id_fk',
                        'shelf_floor',
                        'product_id_fk',
                        'id',
                      )
                      ->where('product_id_fk',($st->product_id_fk?$st->product_id_fk:0))
                      ->where('business_location_id_fk', "=", $request->business_location_id_fk)
                      ->where('branch_id_fk', "=", $request->branch_id_fk)
                      ->where('in_out','1')
                      ->where('ref_table','db_products_borrow_details')
                      ->where('updated_at','>=',$request->startDate_data.' 00:00:00')
                      ->where('updated_at','>=',$request->endDate_data.' 59:59:59')
                      ->get();

                      if($request->warehouse_id_fk!=''){
                        $Stock_movement_in = $Stock_movement_in->where('warehouse_id_fk', "=", $request->warehouse_id_fk);
                      }
                      if($request->zone_id_fk!=''){
                        $Stock_movement_in = $Stock_movement_in->where('zone_id_fk', "=", $request->zone_id_fk);
                      }
                      if($request->shelf_id_fk!=''){
                        $Stock_movement_in = $Stock_movement_in->where('shelf_id_fk', "=", $request->shelf_id_fk);
                      }
                      if($request->shelf_floor!=''){
                        $Stock_movement_in = $Stock_movement_in->where('shelf_floor', "=", $request->shelf_floor);
                      }

                      $amt_balance_stock_in = @$Stock_movement_in[0]->sum?$Stock_movement_in[0]->sum:0;



                      $amt_balance_stock = $amt_balance_stock_out-$amt_balance_stock_in;

                      $product_code = $st->product_code;
                      $product_name = $st->product_name;
                      $product_unit = $st->product_unit;
                      $amt_top += $amt_balance_stock;
                      $sheet->setCellValue('A'.($td_data+$key), $product_code);
                      $sheet->setCellValue('B'.($td_data+$key), $product_name);
                      $sheet->setCellValue('C'.($td_data+$key), $product_unit);
                      $sheet->setCellValue('D'.($td_data+$key), $amt_balance_stock_out);
                      $sheet->setCellValue('E'.($td_data+$key), $amt_balance_stock_in);
                      $sheet->setCellValue('F'.($td_data+$key), $amt_top);
                  }
              }

              if($request->report_data=='inventory_remain'){
               // ถ้าวันที่ $request->start_date > ปัจจุบัน ให้เอายอด ใน stock คงเหลือปัจจุบัน เลย
                if($request->startDate_data > date('Y-m-d') ){
                  // วนสินค้าในคลัง
                  foreach($Stock as $key => $st){
                      $amt_top = 0;
                      $product_code = "";
                      $product_name = "";
                      $product_unit = "";
                          $sBalance = \App\Models\Backend\Check_stock::
                          select(
                            // DB::raw('(CASE WHEN amt > 0 THEN sum(amt) ELSE 0 END) AS amt'),
                            DB::raw('sum(amt) AS amt'),
                            'product_id_fk',
                            'updated_at',
                            'warehouse_id_fk',
                            'zone_id_fk',
                            'shelf_id_fk',
                            'shelf_floor',
                            )
                          ->where('product_id_fk',($st->product_id_fk?$st->product_id_fk:0))
                          ->where('business_location_id_fk', "=", $request->business_location_id_fk)
                          ->where('branch_id_fk', "=", $request->branch_id_fk)
                          ->groupBy('product_id_fk')
                          ->get();
                          // $sBalance = $sBalance->whereBetween('updated_at', [$request->startDate_data.' 00:00:00', $request->endDate_data.' 59:59:59']);
                          $sBalance = $sBalance->where('updated_at','>=',$request->startDate_data.' 00:00:00')->where('updated_at','>=',$request->endDate_data.' 59:59:59');
                          if($request->warehouse_id_fk!=''){
                            $sBalance = $sBalance->where('warehouse_id_fk', "=", $request->warehouse_id_fk);
                          }
                          if($request->zone_id_fk!=''){
                            $sBalance = $sBalance->where('zone_id_fk', "=", $request->zone_id_fk);
                          }
                          if($request->shelf_id_fk!=''){
                            $sBalance = $sBalance->where('shelf_id_fk', "=", $request->shelf_id_fk);
                          }
                          if($request->shelf_floor!=''){
                            $sBalance = $sBalance->where('shelf_floor', "=", $request->shelf_floor);
                          }
                            $amt_balance_stock = @$sBalance[0]->amt?$sBalance[0]->amt:0;
                            $amt_top += $amt_balance_stock;
                            $product_code = $st->product_code;
                            $product_name = $st->product_name;
                            $product_unit = $st->product_unit;
                            $sheet->setCellValue('A'.($td_data+$key), $product_code);
                            $sheet->setCellValue('B'.($td_data+$key), $product_name);
                            $sheet->setCellValue('C'.($td_data+$key), $product_unit);
                            $sheet->setCellValue('D'.($td_data+$key), $amt_top);
                  }
           }else{
            foreach($Stock as $key => $st){
              $amt_top = 0;
              $product_code = "";
              $product_name = "";
              $product_unit = "";
                            // รายการก่อน start_date เพื่อหายอดยกมา
                            // ยอดออก
                            $Stock_movement_in = \App\Models\Backend\Stock_movement::
                            select(
                              // DB::raw('(CASE WHEN amt > 0 THEN sum(amt) ELSE 0 END) AS amt'),
                              DB::raw('sum(amt) AS sum'),
                              'product_id_fk',
                              'updated_at',
                              'warehouse_id_fk',
                              'zone_id_fk',
                              'shelf_id_fk',
                              'shelf_floor',
                            )
                            ->where('product_id_fk',($st->product_id_fk?$st->product_id_fk:0))
                            ->where('business_location_id_fk', "=", $request->business_location_id_fk)
                            ->where('branch_id_fk', "=", $request->branch_id_fk)
                            ->where('in_out','1')
                            ->where(DB::raw("(DATE_FORMAT(updated_at,'%Y-%m-%d'))"), "<", $request->startDate_data)
                            // ->selectRaw('sum(amt) as sum')
                            ->get();
                            if($request->warehouse_id_fk!=''){
                              $Stock_movement_in = $Stock_movement_in->where('warehouse_id_fk', "=", $request->warehouse_id_fk);
                            }
                            if($request->zone_id_fk!=''){
                              $Stock_movement_in = $Stock_movement_in->where('zone_id_fk', "=", $request->zone_id_fk);
                            }
                            if($request->shelf_id_fk!=''){
                              $Stock_movement_in = $Stock_movement_in->where('shelf_id_fk', "=", $request->shelf_id_fk);
                            }
                            if($request->shelf_floor!=''){
                              $Stock_movement_in = $Stock_movement_in->where('shelf_floor', "=", $request->shelf_floor);
                            }

                            // ยอดเบิกออก
                            $amt_balance_in = @$Stock_movement_in[0]->sum?$Stock_movement_in[0]->sum:0;
                            $Stock_movement_out = \App\Models\Backend\Stock_movement::
                            select(
                              // DB::raw('(CASE WHEN amt > 0 THEN sum(amt) ELSE 0 END) AS amt'),
                              DB::raw('sum(amt) AS sum'),
                              'product_id_fk',
                              'updated_at',
                              'warehouse_id_fk',
                              'zone_id_fk',
                              'shelf_id_fk',
                              'shelf_floor',
                            )
                            ->where('product_id_fk',($st->product_id_fk?$st->product_id_fk:0))
                            ->where('business_location_id_fk', "=", $request->business_location_id_fk)
                            ->where('branch_id_fk', "=", $request->branch_id_fk)
                            ->where('in_out','2')
                            ->where(DB::raw("(DATE_FORMAT(updated_at,'%Y-%m-%d'))"), "<", $request->startDate_data)
                            // ->selectRaw('sum(amt) as sum')
                            ->get();
                            if($request->warehouse_id_fk!=''){
                              $Stock_movement_out = $Stock_movement_out->where('warehouse_id_fk', "=", $request->warehouse_id_fk);
                            }
                            if($request->zone_id_fk!=''){
                              $Stock_movement_out = $Stock_movement_out->where('zone_id_fk', "=", $request->zone_id_fk);
                            }
                            if($request->shelf_id_fk!=''){
                              $Stock_movement_out = $Stock_movement_out->where('shelf_id_fk', "=", $request->shelf_id_fk);
                            }
                            if($request->shelf_floor!=''){
                              $Stock_movement_out = $Stock_movement_out->where('shelf_floor', "=", $request->shelf_floor);
                            }
                               // ยอดรับเข้า
                            $amt_balance_out = @$Stock_movement_out[0]->sum?$Stock_movement_out[0]->sum:0;
                            $amt_balance_stock = $amt_balance_in - $amt_balance_out ;
                            $product_code = $st->product_code;
                            $product_name = $st->product_name;
                            $product_unit = $st->product_unit;
                            $amt_top += $amt_balance_stock;
                            $sheet->setCellValue('A'.($td_data+$key), $product_code);
                            $sheet->setCellValue('B'.($td_data+$key), $product_name);
                            $sheet->setCellValue('C'.($td_data+$key), $product_unit);
                            $sheet->setCellValue('D'.($td_data+$key), $amt_top);
            }
          }
        }

    }

      }

      if($request->report_data=='sale_report'){

        $action_user = DB::table('db_orders')
        ->select('action_user')
        ->where('db_orders.approve_date','>=',$request->startDate_data)
        ->where('db_orders.approve_date','<=',$request->endDate_data)
        ->where('business_location_id_fk','=',$request->business_location_id_fk)
        ->whereNotIn('db_orders.approve_status',[1,3,5,6])
        ->groupBy('action_user')
        ->get();

        $promotion_data = DB::table('promotions')
        ->select('id','name_thai','pcode')
        // ->where('db_orders.approve_date','>=',$request->startDate_data)
        // ->where('db_orders.approve_date','<=',$request->endDate_data)
        ->where('business_location','=',$request->business_location_id_fk)
        ->get();
        // dd($promotion_data);
        $promotion_data_arr = [];
        foreach($promotion_data as $pro_data){
          $promotion_data_arr[$pro_data->id] = [
            'id' => $pro_data->id,
            'name_thai' => $pro_data->name_thai,
            'pcode' => $pro_data->pcode,
          ];
        }
        $row_num = 0;
        $arr_product_total = [];
        $arr_product_amt_total = [];
        $arr_promotion_total = [];
        $arr_promotion_amt_total = [];
        foreach($action_user as $ac_key => $ac){
          $orders = DB::table('db_orders')
          ->select('id')
          // ->select('db_orders.id','db_orders.code_order','db_orders.invoice_code_id_fk','db_orders.approve_date','db_orders.customers_sent_id_fk','customers.user_name','customers.first_name','customers.last_name')
          // ->join('customers','customers.id','db_orders.customers_id_fk')
          ->where('db_orders.approve_date','>=',$request->startDate_data)
          ->where('db_orders.approve_date','<=',$request->endDate_data)
          ->where('business_location_id_fk','=',$request->business_location_id_fk)
          // ->where('db_orders.delivery_location_frontend','!=','sent_office')
          ->whereNotIn('db_orders.approve_status',[1,3,5,6])
          ->where('action_user','=',$ac->action_user)
          // ->get();
          ->pluck('id');

          $pro_list = DB::table('db_order_products_list')
          ->select('type_product','promotion_id_fk','giveaway_id_fk','promotion_code','amt','product_name','product_id_fk','frontstore_id_fk','id')
          ->whereIn('frontstore_id_fk',$orders)
          ->get();

          $arr_product = [];
          $arr_product_amt = [];

          $arr_promotion = [];
          $arr_promotion_amt = [];

          $arr_course = [];
          $arr_course_amt = [];

          $arr_giveaway = [];
          $arr_giveaway_amt = [];

          foreach($pro_list as $pro){

            if($pro->type_product=='product'){

              $arr_product[$pro->product_id_fk] = [
                'product_name' => $pro->product_name,
              ];
              if(isset($arr_product_amt[$pro->product_id_fk])){
                $arr_product_amt[$pro->product_id_fk] = $arr_product_amt[$pro->product_id_fk]+$pro->amt;
              }else{
                $arr_product_amt[$pro->product_id_fk] = $pro->amt;
              }

              $arr_product_total[$pro->product_id_fk] = [
                'product_name' => $pro->product_name,
              ];
              if(isset($arr_product_amt_total[$pro->product_id_fk])){
                $arr_product_amt_total[$pro->product_id_fk] = $arr_product_amt_total[$pro->product_id_fk]+$pro->amt;
              }else{
                $arr_product_amt_total[$pro->product_id_fk] = $pro->amt;
              }

            }elseif($pro->type_product=='promotion'){
              // if($pro->id==52812){
              //   dd($pro_data->id);
              // }

              $arr_promotion[$pro->promotion_id_fk] = [
                'product_name' => $promotion_data_arr[$pro->promotion_id_fk]['pcode'].' : '.$promotion_data_arr[$pro->promotion_id_fk]['name_thai'],
              ];
              if(isset($arr_promotion_amt[$pro->promotion_id_fk])){
                $arr_promotion_amt[$pro->promotion_id_fk] = $arr_promotion_amt[$pro->promotion_id_fk]+$pro->amt;
              }else{
                $arr_promotion_amt[$pro->promotion_id_fk] = $pro->amt;
              }

              $arr_promotion_total[$pro->promotion_id_fk] = [
                'product_name' => $promotion_data_arr[$pro->promotion_id_fk]['pcode'].' : '.$promotion_data_arr[$pro->promotion_id_fk]['name_thai'],
              ];
              if(isset($arr_promotion_amt_total[$pro->promotion_id_fk])){
                $arr_promotion_amt_total[$pro->promotion_id_fk] = $arr_promotion_amt_total[$pro->promotion_id_fk]+$pro->amt;
              }else{
                $arr_promotion_amt_total[$pro->promotion_id_fk] = $pro->amt;
              }

            }
            elseif($pro->type_product=='course'){

            }
            elseif($pro->type_product=='giveaway'){

              // $arr_giveaway[$pro->giveaway_id_fk] = [
              //   'product_name' => $pro->product_name,
              // ];
              // if(isset($arr_giveaway_amt[$pro->giveaway_id_fk])){
              //   $arr_giveaway_amt[$pro->giveaway_id_fk] = $arr_giveaway_amt[$pro->giveaway_id_fk]+$pro->amt;
              // }else{
              //   $arr_giveaway_amt[$pro->giveaway_id_fk] = $pro->amt;
              // }

            }


          }

          if($ac->action_user!=0){
            $user_data = DB::table('ck_users_admin')->select('name')->where('id',$ac->action_user)->first();
            if($user_data){
              $ac_name = $user_data->name;
            }else{
              $ac_name = '';
            }
          }else{
            $ac_name = 'V3';
          }

          $styleArray1 = array(
            'font'  => array(
                 'bold'  => true,
                 'color' => array('rgb' => '00000'),
                 'size'  => 10,
                 'name'  => 'Verdana'
            ),
            // 'fill' => array(
            //  'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            //  'startColor' => array('argb' => 'F2F2F2')
            // ),
            'alignment' => [
             'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
             'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
         ],
           );

           $styleArray2 = array(
            'font'  => array(
                 'bold'  => true,
                 'color' => array('rgb' => '00000'),
                 'size'  => 10,
                 'name'  => 'Verdana'
            ),
            'fill' => array(
             'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
             'startColor' => array('argb' => 'F2F2F2')
            ),
            'alignment' => [
             'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
             'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
         ],
           );

        $sheet->getStyle('A1:F1')->applyFromArray($styleArray);
        $sheet->getStyle('A2:F2')->applyFromArray($styleArray2);
        $sheet->getStyle('A3:A1000')->applyFromArray([
          'alignment' => [
            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
        ],
        ]);

          $branch_data = DB::table('branchs')->select('b_name')->where('id',$request->branch_id_fk)->first();
          $sheet->mergeCells("A1:F1");
          $sheet->setCellValue('A1', 'ศูนย์ธุรกิจ '.@$branch_data->b_name.' รายงานยอดขายสินค้ารายวัน ประจำวันที่ '.date('d/m/Y', strtotime($request->startDate_data)).' ถึง '.date('d/m/Y', strtotime($request->endDate_data)));
          // Head
          $sheet->setCellValue('A'.$head, 'ลำดับ');
          $sheet->setCellValue('B'.$head, 'รายการ');
          $sheet->setCellValue('C'.$head, 'จำนวน');
          $sheet->setCellValue('D'.$head, 'ผู้ขาย');
          // $sheet->setCellValue('A'.$head, 'รหัสสินค้า');
          // $sheet->setCellValue('B'.$head, 'รายงานขาย');
          // $sheet->setCellValue('C'.$head, 'จำนวน (ชิ้น)');
          // $sheet->setCellValue('D'.$head, 'ผู้ขาย');

          $row_num--;
          // ($td_data+$row_num+$ac_key).' / '.$td_data.' + '.$row_num.' + '.$ac_key);
          // ($td_data+$row_num+$ac_key)-2);
          foreach($arr_product as $key => $arr_pro){
            $sheet->setCellValue('A'.($td_data+$row_num+$ac_key), ($td_data+$row_num+$ac_key)-2);
            $sheet->setCellValue('B'.($td_data+$row_num+$ac_key), $arr_pro['product_name']);
            $sheet->setCellValue('C'.($td_data+$row_num+$ac_key), $arr_product_amt[$key]);
            $sheet->setCellValue('D'.($td_data+$row_num+$ac_key), $ac_name);
            $row_num++;
          }

          foreach($arr_promotion as $key => $arr_pro){
            $sheet->setCellValue('A'.($td_data+$row_num+$ac_key), ($td_data+$row_num+$ac_key)-2);
            $sheet->setCellValue('B'.($td_data+$row_num+$ac_key), $arr_pro['product_name']);
            $sheet->setCellValue('C'.($td_data+$row_num+$ac_key), $arr_promotion_amt[$key]);
            $sheet->setCellValue('D'.($td_data+$row_num+$ac_key), $ac_name);
            $row_num++;
          }

          // foreach($arr_giveaway as $key => $arr_pro){
          //   $sheet->setCellValue('A'.($td_data+$row_num+$ac_key), '');
          //   $sheet->setCellValue('B'.($td_data+$row_num+$ac_key), $arr_pro['product_name']);
          //   $sheet->setCellValue('C'.($td_data+$row_num+$ac_key), $arr_giveaway_amt[$key]);
          //   $sheet->setCellValue('D'.($td_data+$row_num+$ac_key), $ac_name);
          //   $row_num++;
          // }

        }
        // สรุปต่ออีก 1 รายการสินค้าทั้งหมด

        $ac_key_total = count($action_user);
        $sheet->getStyle('A'.($td_data+$row_num+$ac_key_total-1).':F'.($td_data+$row_num+$ac_key_total-1))->applyFromArray($styleArray2);
        $sheet->setCellValue('A'.($td_data+$row_num+$ac_key_total-1), 'ลำดับ');
        $sheet->setCellValue('B'.($td_data+$row_num+$ac_key_total-1), 'สรุปรายการสินค้าที่ขายทั้งหมด');
        $sheet->setCellValue('C'.($td_data+$row_num+$ac_key_total-1), 'จำนวน');
        $sheet->setCellValue('D'.($td_data+$row_num+$ac_key_total-1), '');

        $row_num_total = 1;

        // ($td_data+$row_num+$ac_key_total).' / '.$td_data.' + '.$row_num.' + '.$ac_key_total);
        foreach($arr_product_total as $key => $arr_pro){
          $sheet->setCellValue('A'.($td_data+$row_num+$ac_key_total), $row_num_total);
          $sheet->setCellValue('B'.($td_data+$row_num+$ac_key_total), $arr_pro['product_name']);
          $sheet->setCellValue('C'.($td_data+$row_num+$ac_key_total), $arr_product_amt_total[$key]);
          $sheet->setCellValue('D'.($td_data+$row_num+$ac_key_total), '');
          $row_num++;
          $row_num_total++;
        }

        $sheet->getStyle('A'.($td_data+$row_num+$ac_key_total).':F'.($td_data+$row_num+$ac_key_total))->applyFromArray($styleArray2);
        $sheet->setCellValue('A'.($td_data+$row_num+$ac_key_total), 'ลำดับ');
        $sheet->setCellValue('B'.($td_data+$row_num+$ac_key_total), 'สรุปรายการโปรโมชั่นที่ขายทั้งหมด');
        $sheet->setCellValue('C'.($td_data+$row_num+$ac_key_total), 'จำนวน');
        $sheet->setCellValue('D'.($td_data+$row_num+$ac_key_total), '');

        $row_num_total2 = 1;
        // $arr_promotion_id = [];
        foreach($arr_promotion_total as $key => $arr_pro){
          // $arr_promotion_id = array_push($a,$key);
          $sheet->setCellValue('A'.($td_data+$row_num+$ac_key_total+1), $row_num_total2);
          $sheet->setCellValue('B'.($td_data+$row_num+$ac_key_total+1), $arr_pro['product_name']);
          $sheet->setCellValue('C'.($td_data+$row_num+$ac_key_total+1), $arr_promotion_amt_total[$key]);
          $sheet->setCellValue('D'.($td_data+$row_num+$ac_key_total+1), '');

          // เด่วทำต่อ
          // $p_products = DB::table('promotions_products')->select('product_amt','product_id_fk','promotion_id_fk')->where('promotion_id_fk',$key)->get();

          // foreach($p_products as $p_p){
          //   $p_p_data = DB::table('products')->select('product_code')->where('id',$p_p->product_id_fk)->first();
          //   if($p_p_data){
          //     $p_p_detail = DB::table('products')->select('product_code')->where('id',$p_p->product_id_fk)->first();
          //   }

          // }

          // $arr_product_total[$pro->product_id_fk] = [
          //   'product_name' => $pro->product_name,
          // ];
          // if(isset($arr_product_amt_total[$pro->product_id_fk])){
          //   $arr_product_amt_total[$pro->product_id_fk] = $arr_product_amt_total[$pro->product_id_fk]+$pro->amt;
          // }else{
          //   $arr_product_amt_total[$pro->product_id_fk] = $pro->amt;
          // }


          $row_num++;
          $row_num_total2++;
        }




      }

			}

			foreach ($sheet->getColumnIterator() as $column) {
			    $sheet->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
			}

			$file = $request->report_data.'_'.date('YmdHis').'.xlsx';
			header('Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-disposition: attachment; filename='.$file);

			$writer = new Xlsx($spreadsheet);
			// $writer->save('local/public/excel_files/'.$file);
      $writer->save(public_path().'/excel_files_new/'.$file);
      // return response()->file(public_path('/excel_files_new/'.$file));
      // dd(public_path().'/excel_files_new/'.$file);
      return response()->json([
        'path' => $file
    ]);
		}

}

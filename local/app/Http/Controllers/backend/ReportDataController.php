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
      $sBusiness_location = \App\Models\Backend\Business_location::when(auth()->user()->permission !== 1, function ($query) {
        return $query->where('id', auth()->user()->business_location_id_fk);
      })->get();
      $sBranchs = \App\Models\Backend\Branchs::when(auth()->user()->permission !== 1, function ($query) {
        return $query->where('id', auth()->user()->branch_id_fk);
      })->get();
      $Warehouse = \App\Models\Backend\Warehouse::get();
      // dd($Warehouse);
      // dd(\Auth::user()->branch_id_fk);
      if(@\Auth::user()->permission==1){
        $Warehouse = \App\Models\Backend\Warehouse::get();
      }else{
        $Warehouse = \App\Models\Backend\Warehouse::where('branch_id_fk',\Auth::user()->branch_id_fk)->get();
      }

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

			$spreadsheet = new Spreadsheet();
			$amt_sheet = 1;

			 $styleArray = array(
			   'font'  => array(
			        'bold'  => true,
			        'color' => array('rgb' => '002699'),
			        'size'  => 10,
			        'name'  => 'Verdana'
         ),
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
        $sheet->mergeCells("A".$date.":B".$date);  
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

                            // // รายการตามช่วงวันที่ระบุ start_date to end_date
                            // $Stock_movement = \App\Models\Backend\Stock_movement::
                            // where('product_id_fk',($Stock[0]->product_id_fk?$Stock[0]->product_id_fk:0))
                            // ->where(DB::raw($w_business_location_id_fk_01), "=", $w_business_location_id_fk_02)
                            // ->where(DB::raw($w_branch_id_fk_01), "=", $w_branch_id_fk_02)
                            // ->where(DB::raw($w_warehouse_id_fk_01), "=", $w_warehouse_id_fk_02)
                            // ->where(DB::raw($w_zone_id_fk_01), "=", $w_zone_id_fk_02)
                            // ->where(DB::raw($w_shelf_id_fk_01), "=", $w_shelf_id_fk_02)
                            // ->where(DB::raw($w_shelf_floor_01), "=", $w_shelf_floor_02)
                            // ->where(DB::raw($w_lot_number_01), "=", $w_lot_number_02)
                            // ->where(DB::raw("(DATE_FORMAT(updated_at,'%Y-%m-%d'))"), ">=", $request->start_date)
                            // ->where(DB::raw("(DATE_FORMAT(updated_at,'%Y-%m-%d'))"), "<=", $request->end_date)
                            // // วุฒิเพิ่มมา
                            // ->where('amt','!=',0)
                            // ->get();

                            // if($Stock_movement->count() > 0){

                            //         foreach ($Stock_movement as $key => $value) {
                            //               $insertData = array(
                            //                 "ref_inv" =>  @$value->ref_doc?$value->ref_doc:NULL,
                            //                 "action_date" =>  @$value->updated_at?$value->updated_at:NULL,
                            //                 "action_user" =>  @$value->action_user?$value->action_user:NULL,
                            //                 "approver" =>  @$value->approver?$value->approver:NULL,
                            //                 "approve_date" =>  @$value->approve_date?$value->approve_date:NULL,
                            //                 "sender" =>  @$value->sender?$value->sender:NULL,
                            //                 "sent_date" =>  @$value->sent_date?$value->sent_date:NULL,
                            //                 "who_cancel" =>  @$value->who_cancel?$value->who_cancel:NULL,
                            //                 "cancel_date" =>  @$value->cancel_date?$value->cancel_date:NULL,
                            //                 "business_location_id_fk" =>  @$value->business_location_id_fk?$value->business_location_id_fk:0,
                            //                 "branch_id_fk" =>  @$value->branch_id_fk?$value->branch_id_fk:0,
                            //                 "product_id_fk" =>  @$value->product_id_fk?$value->product_id_fk:0,
                            //                 "lot_number" =>  @$value->lot_number?$value->lot_number:NULL,
                            //                 "details" =>  (@$value->note?$value->note:NULL).' '.(@$value->note2?$value->note2:NULL),
                            //                 "amt_in" =>  @$value->in_out==1?$value->amt:0,
                            //                 "amt_out" =>  @$value->in_out==2?$value->amt:0,
                            //                 "warehouse_id_fk" =>  @$value->warehouse_id_fk>0?$value->warehouse_id_fk:0,
                            //                 "zone_id_fk" =>  @$value->zone_id_fk>0?$value->zone_id_fk:0,
                            //                 "shelf_id_fk" =>  @$value->shelf_id_fk>0?$value->shelf_id_fk:0,
                            //                 "shelf_floor" =>  @$value->shelf_floor>0?$value->shelf_floor:0,
                            //                 "created_at" =>@$value->dd?$value->dd:NULL
                            //             );

                            //               DB::table($temp_db_stock_card)->insert($insertData);
                            //         }

                            // }
            }     
          }
        }
    }

  }
				
				// $p_i = 0;
				// for ($i=0; $i < count($sRow) ; $i++) {
				// 	$pick_pack_packing = DB::table('db_pick_pack_packing')->select('p_amt_box')->where('delivery_id_fk',$sRow[$i]->delivery_id_fk)->first();
				// 	if($pick_pack_packing){
				// 		if($pick_pack_packing->p_amt_box != null && $pick_pack_packing->p_amt_box != ''){
				// 			for($p=0; $p < $pick_pack_packing->p_amt_box; $p++){
				// 				$sheet->setCellValue('A'.($i+2+$p_i), $sRow[$i]->consignment_no);
				// 				$sheet->setCellValue('B'.($i+2+$p_i), $sRow[$i]->customer_ref_no);
				// 				$sheet->setCellValue('C'.($i+2+$p_i), $sRow[$i]->sender_code);
				// 				$sheet->setCellValue('D'.($i+2+$p_i), $sRow[$i]->recipient_code);
				// 				$sheet->setCellValue('E'.($i+2+$p_i), $sRow[$i]->recipient_name);
				// 				$sheet->setCellValue('F'.($i+2+$p_i), $sRow[$i]->address);
				// 				$sheet->setCellValue('G'.($i+2+$p_i), $sRow[$i]->postcode);
				// 				$sheet->setCellValue('H'.($i+2+$p_i), $sRow[$i]->mobile);
				// 				// $sheet->setCellValue('I'.($i+2), $sRow[$i]->contact_person);
				// 				$sheet->setCellValue('I'.($i+2+$p_i), $sRow[$i]->recipient_name);
				// 				$sheet->setCellValue('J'.($i+2+$p_i), $sRow[$i]->phone_no);
				// 				$sheet->setCellValue('K'.($i+2+$p_i), $sRow[$i]->email);
				// 				$sheet->setCellValue('L'.($i+2+$p_i), $sRow[$i]->declare_value);
				// 				$sheet->setCellValue('M'.($i+2+$p_i), $sRow[$i]->cod_amount);
				// 				$sheet->setCellValue('N'.($i+2+$p_i), $sRow[$i]->remark);
				// 				$sheet->setCellValue('O'.($i+2+$p_i), $sRow[$i]->total_box);
				// 				$sheet->setCellValue('P'.($i+2+$p_i), $sRow[$i]->sat_del);
				// 				$sheet->setCellValue('Q'.($i+2+$p_i), $sRow[$i]->hrc);
				// 				$sheet->setCellValue('R'.($i+2+$p_i), $sRow[$i]->invr);
				// 				// $sheet->setCellValue('S'.($i+2), $sRow[$i]->service_code);
				// 				// $sheet->setCellValue('S'.($i+2), $request->id);
				// 				$sheet->setCellValue('S'.($i+2+$p_i), '');
				// 				$p_i++;
				// 			}
				// 		}else{
				// 			$sheet->setCellValue('A'.($i+2+$p_i), $sRow[$i]->consignment_no);
				// 			$sheet->setCellValue('B'.($i+2+$p_i), $sRow[$i]->customer_ref_no);
				// 			$sheet->setCellValue('C'.($i+2+$p_i), $sRow[$i]->sender_code);
				// 			$sheet->setCellValue('D'.($i+2+$p_i), $sRow[$i]->recipient_code);
				// 			$sheet->setCellValue('E'.($i+2+$p_i), $sRow[$i]->recipient_name);
				// 			$sheet->setCellValue('F'.($i+2+$p_i), $sRow[$i]->address);
				// 			$sheet->setCellValue('G'.($i+2+$p_i), $sRow[$i]->postcode);
				// 			$sheet->setCellValue('H'.($i+2+$p_i), $sRow[$i]->mobile);
				// 			// $sheet->setCellValue('I'.($i+2), $sRow[$i]->contact_person);
				// 			$sheet->setCellValue('I'.($i+2+$p_i), $sRow[$i]->recipient_name);
				// 			$sheet->setCellValue('J'.($i+2+$p_i), $sRow[$i]->phone_no);
				// 			$sheet->setCellValue('K'.($i+2+$p_i), $sRow[$i]->email);
				// 			$sheet->setCellValue('L'.($i+2+$p_i), $sRow[$i]->declare_value);
				// 			$sheet->setCellValue('M'.($i+2+$p_i), $sRow[$i]->cod_amount);
				// 			$sheet->setCellValue('N'.($i+2+$p_i), $sRow[$i]->remark);
				// 			$sheet->setCellValue('O'.($i+2+$p_i), $sRow[$i]->total_box);
				// 			$sheet->setCellValue('P'.($i+2+$p_i), $sRow[$i]->sat_del);
				// 			$sheet->setCellValue('Q'.($i+2+$p_i), $sRow[$i]->hrc);
				// 			$sheet->setCellValue('R'.($i+2+$p_i), $sRow[$i]->invr);
				// 			// $sheet->setCellValue('S'.($i+2), $sRow[$i]->service_code);
				// 			// $sheet->setCellValue('S'.($i+2), $request->id);
				// 			$sheet->setCellValue('S'.($i+2+$p_i), '');
				// 		}
				// 	}else{
				// 		$sheet->setCellValue('A'.($i+2+$p_i), $sRow[$i]->consignment_no);
				// 		$sheet->setCellValue('B'.($i+2+$p_i), $sRow[$i]->customer_ref_no);
				// 		$sheet->setCellValue('C'.($i+2+$p_i), $sRow[$i]->sender_code);
				// 		$sheet->setCellValue('D'.($i+2+$p_i), $sRow[$i]->recipient_code);
				// 		$sheet->setCellValue('E'.($i+2+$p_i), $sRow[$i]->recipient_name);
				// 		$sheet->setCellValue('F'.($i+2+$p_i), $sRow[$i]->address);
				// 		$sheet->setCellValue('G'.($i+2+$p_i), $sRow[$i]->postcode);
				// 		$sheet->setCellValue('H'.($i+2+$p_i), $sRow[$i]->mobile);
				// 		// $sheet->setCellValue('I'.($i+2), $sRow[$i]->contact_person);
				// 		$sheet->setCellValue('I'.($i+2+$p_i), $sRow[$i]->recipient_name);
				// 		$sheet->setCellValue('J'.($i+2+$p_i), $sRow[$i]->phone_no);
				// 		$sheet->setCellValue('K'.($i+2+$p_i), $sRow[$i]->email);
				// 		$sheet->setCellValue('L'.($i+2+$p_i), $sRow[$i]->declare_value);
				// 		$sheet->setCellValue('M'.($i+2+$p_i), $sRow[$i]->cod_amount);
				// 		$sheet->setCellValue('N'.($i+2+$p_i), $sRow[$i]->remark);
				// 		$sheet->setCellValue('O'.($i+2+$p_i), $sRow[$i]->total_box);
				// 		$sheet->setCellValue('P'.($i+2+$p_i), $sRow[$i]->sat_del);
				// 		$sheet->setCellValue('Q'.($i+2+$p_i), $sRow[$i]->hrc);
				// 		$sheet->setCellValue('R'.($i+2+$p_i), $sRow[$i]->invr);
				// 		// $sheet->setCellValue('S'.($i+2), $sRow[$i]->service_code);
				// 		// $sheet->setCellValue('S'.($i+2), $request->id);
				// 		$sheet->setCellValue('S'.($i+2+$p_i), '');
				// 	}
					
				// }

			}

			// $cellIterator = $sheet->getRowIterator()->current()->getCellIterator();
			// $cellIterator->setIterateOnlyExistingCells( true );
			// foreach( $cellIterator as $cell ) {
			//     $sheet->getColumnDimension( $cell->getColumn() )->setAutoSize( true );
			// }
			foreach ($sheet->getColumnIterator() as $column) {
			    $sheet->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
			}

			$file = 'report_data.xlsx';
			header('Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-disposition: attachment; filename='.$file);

			$writer = new Xlsx($spreadsheet);
			$writer->save('local/public/excel_files/'.$file);

		}

}

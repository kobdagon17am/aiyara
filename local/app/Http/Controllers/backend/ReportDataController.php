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

        if($request->report_data=='inventory_remain'){
          // $sRow = \App\Models\Backend\Consignments::where('pick_pack_requisition_code_id_fk',$request->id)->get();

          $Stock = \App\Models\Backend\Check_stock::
          // where('product_id_fk',$request->product_id_fk)
           where(DB::raw($request->business_location_id_fk_01), "=", $request->business_location_id_fk_02)
          ->where(DB::raw($request->branch_id_fk_01), "=", $request->branch_id_fk_02)
          ->where(DB::raw($request->warehouse_id_fk_01), "=", $request->warehouse_id_fk_02)
          ->where(DB::raw($request->zone_id_fk_01), "=", $request->zone_id_fk_02)
          ->where(DB::raw($request->shelf_id_fk_01), "=", $request->shelf_id_fk_02)
          ->where(DB::raw($request->shelf_floor_01), "=", $request->shelf_floor_02)
          // ->where(DB::raw($request->lot_number_01), "=", $request->lot_number_02)
          ->where(DB::raw("(DATE_FORMAT(updated_at,'%Y-%m-%d'))"), ">=", $request->startDate_data)
          ->where(DB::raw("(DATE_FORMAT(updated_at,'%Y-%m-%d'))"), "<=", $request->endDate_data)
          ->get();
          dd($Stock);
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

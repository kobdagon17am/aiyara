<?php
	namespace App\Http\Controllers\backend;
	use Illuminate\Http\Request;
	use App\Http\Controllers\Controller;
	use PhpOffice\PhpSpreadsheet\Spreadsheet;
	use PhpOffice\PhpSpreadsheet\IOFactory;
	use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
	use DB;
	use File;

	class ExcelController extends Controller
	{

		public function excelExport()
		{

			$spreadsheet = new Spreadsheet();
			$amt_sheet = 1;

			for ($j=0; $j < $amt_sheet ; $j++) {

				if($j>0){
					$spreadsheet->createSheet();
				}

				$spreadsheet->setActiveSheetIndex($j);
				$sheet = $spreadsheet->getActiveSheet();
				$sheet->setTitle("Sheet".($j+1));

				$sRow = \App\Models\Backend\Pm_broadcast::get();

				$sheet->setCellValue('A1', 'CustomerId');
				$sheet->setCellValue('B1', 'Message');
				$sheet->setCellValue('C1', 'Show_from');
				$sheet->setCellValue('D1', 'Show_to');
				$sheet->setCellValue('E1', 'Remark');

				for ($i=0; $i < count($sRow) ; $i++) {
					$sheet->setCellValue('A'.($i+2), $sRow[$i]->customers_id_fk);
					$sheet->setCellValue('B'.($i+2), $sRow[$i]->txt_msg);
					$sheet->setCellValue('C'.($i+2), $sRow[$i]->show_from);
					$sheet->setCellValue('D'.($i+2), $sRow[$i]->show_to);
					$sheet->setCellValue('E'.($i+2), $sRow[$i]->remark);
				}


			}

			$file = 'pm_broadcast.xlsx';
			header('Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-disposition: attachment; filename='.$file);

			$writer = new Xlsx($spreadsheet);
			$writer->save('local/public/excel_files/'.$file);

		}


		public function excelExportConsignment(Request $request)
		{

			$spreadsheet = new Spreadsheet();
			$amt_sheet = 1;

			 $styleArray = array(
			   'font'  => array(
			        'bold'  => true,
			        'color' => array('rgb' => '002699'),
			        // 'size'  => 16,
			        'name'  => 'Verdana'
			    ));

			for ($j=0; $j < $amt_sheet ; $j++) {

				if($j>0){
					$spreadsheet->createSheet();
				}

				$spreadsheet->setActiveSheetIndex($j);
				$sheet = $spreadsheet->getActiveSheet();
				$sheet->setTitle("Sheet".($j+1));

				$sRow = \App\Models\Backend\Consignments::where('pick_pack_requisition_code_id_fk',$request->id)->get();

				$sheet->setCellValue('A1', 'Consignment No. (หมายเลขพัสดุ)');
				$sheet->setCellValue('B1', 'Customer Ref No. (เลขอ้างอิง)');
				$sheet->setCellValue('C1', 'Sender Code (รหัสผู้ส่ง)');
				$sheet->setCellValue('D1', 'Recipient Code (รหัสผู้รับ)');
				$sheet->setCellValue('E1', 'Recipient Name (ชื่อผู้รับ)');
				$sheet->setCellValue('F1', 'Address (ที่อยู่ผู้รับ)');
				$sheet->setCellValue('G1', 'Postcode (รหัสไปรษณีย์ผู้รับ)');
				$sheet->setCellValue('H1', 'Mobile (เบอร์มือถือผู้รับ)');
				$sheet->setCellValue('I1', 'Contact Person (ชื่อผู้ติดต่อ)');
				$sheet->setCellValue('J1', 'Phone No. (เบอร์ผู้รับ)');
				$sheet->setCellValue('K1', 'Email (อีเมลผู้รับ)');
				$sheet->setCellValue('L1', 'Declare Value (มูลค่าสินค้า)');
				$sheet->setCellValue('M1', 'COD Amount (ยอดเก็บเงินปลายทาง)');
				$sheet->setCellValue('N1', 'Remark (หมายเหตุ)');
				$sheet->setCellValue('O1', 'Total Box (จำนวนกล่อง)');
				$sheet->setCellValue('P1', 'Sat Del (จัดส่งวันเสาร์)');
				$sheet->setCellValue('Q1', 'HCR (Y/N)');
				$sheet->setCellValue('R1', 'INVR (Y/N)');
				$sheet->setCellValue('S1', 'Service Code (รหัสบริการ)');

				$sheet->getStyle('A1:S1')->applyFromArray($styleArray);
			
				$p_i = 0;
				for ($i=0; $i < count($sRow) ; $i++) {
					$pick_pack_packing = DB::table('db_pick_pack_packing')->select('p_amt_box')->where('delivery_id_fk',$sRow[$i]->delivery_id_fk)->first();
					
					// if($pick_pack_packing){
						if($pick_pack_packing->p_amt_box != null && $pick_pack_packing->p_amt_box != ''){
							for($p=0; $p < $pick_pack_packing->p_amt_box; $p++){
								// dd($i+2+$p_i);
								$sheet->setCellValue('A'.($i+2+$p_i), $sRow[$i]->consignment_no);
								$sheet->setCellValue('B'.($i+2+$p_i), $sRow[$i]->customer_ref_no);
								$sheet->setCellValue('C'.($i+2+$p_i), $sRow[$i]->sender_code);
								$sheet->setCellValue('D'.($i+2+$p_i), $sRow[$i]->recipient_code);
								$sheet->setCellValue('E'.($i+2+$p_i), $sRow[$i]->recipient_name);
								$sheet->setCellValue('F'.($i+2+$p_i), $sRow[$i]->address);
								$sheet->setCellValue('G'.($i+2+$p_i), $sRow[$i]->postcode);
								$sheet->setCellValue('H'.($i+2+$p_i), $sRow[$i]->mobile);
								// $sheet->setCellValue('I'.($i+2), $sRow[$i]->contact_person);
								$sheet->setCellValue('I'.($i+2+$p_i), $sRow[$i]->recipient_name);
								$sheet->setCellValue('J'.($i+2+$p_i), $sRow[$i]->phone_no);
								$sheet->setCellValue('K'.($i+2+$p_i), $sRow[$i]->email);
								$sheet->setCellValue('L'.($i+2+$p_i), $sRow[$i]->declare_value);
								$sheet->setCellValue('M'.($i+2+$p_i), $sRow[$i]->cod_amount);
								$sheet->setCellValue('N'.($i+2+$p_i), $sRow[$i]->remark);
								$sheet->setCellValue('O'.($i+2+$p_i), $sRow[$i]->total_box);
								$sheet->setCellValue('P'.($i+2+$p_i), $sRow[$i]->sat_del);
								$sheet->setCellValue('Q'.($i+2+$p_i), $sRow[$i]->hrc);
								$sheet->setCellValue('R'.($i+2+$p_i), $sRow[$i]->invr);
								// $sheet->setCellValue('S'.($i+2), $sRow[$i]->service_code);
								// $sheet->setCellValue('S'.($i+2), $request->id);
								$sheet->setCellValue('S'.($i+2+$p_i), '');
								$p_i++;
							}
						}else{
							$sheet->setCellValue('A'.($i+2+$p_i), $sRow[$i]->consignment_no);
							$sheet->setCellValue('B'.($i+2+$p_i), $sRow[$i]->customer_ref_no);
							$sheet->setCellValue('C'.($i+2+$p_i), $sRow[$i]->sender_code);
							$sheet->setCellValue('D'.($i+2+$p_i), $sRow[$i]->recipient_code);
							$sheet->setCellValue('E'.($i+2+$p_i), $sRow[$i]->recipient_name);
							$sheet->setCellValue('F'.($i+2+$p_i), $sRow[$i]->address);
							$sheet->setCellValue('G'.($i+2+$p_i), $sRow[$i]->postcode);
							$sheet->setCellValue('H'.($i+2+$p_i), $sRow[$i]->mobile);
							// $sheet->setCellValue('I'.($i+2), $sRow[$i]->contact_person);
							$sheet->setCellValue('I'.($i+2+$p_i), $sRow[$i]->recipient_name);
							$sheet->setCellValue('J'.($i+2+$p_i), $sRow[$i]->phone_no);
							$sheet->setCellValue('K'.($i+2+$p_i), $sRow[$i]->email);
							$sheet->setCellValue('L'.($i+2+$p_i), $sRow[$i]->declare_value);
							$sheet->setCellValue('M'.($i+2+$p_i), $sRow[$i]->cod_amount);
							$sheet->setCellValue('N'.($i+2+$p_i), $sRow[$i]->remark);
							$sheet->setCellValue('O'.($i+2+$p_i), $sRow[$i]->total_box);
							$sheet->setCellValue('P'.($i+2+$p_i), $sRow[$i]->sat_del);
							$sheet->setCellValue('Q'.($i+2+$p_i), $sRow[$i]->hrc);
							$sheet->setCellValue('R'.($i+2+$p_i), $sRow[$i]->invr);
							// $sheet->setCellValue('S'.($i+2), $sRow[$i]->service_code);
							// $sheet->setCellValue('S'.($i+2), $request->id);
							$sheet->setCellValue('S'.($i+2+$p_i), '');
						}
						$p_i = $p_i-1;
					// }else{
					// 	$sheet->setCellValue('A'.($i+2+$p_i), $sRow[$i]->consignment_no);
					// 	$sheet->setCellValue('B'.($i+2+$p_i), $sRow[$i]->customer_ref_no);
					// 	$sheet->setCellValue('C'.($i+2+$p_i), $sRow[$i]->sender_code);
					// 	$sheet->setCellValue('D'.($i+2+$p_i), $sRow[$i]->recipient_code);
					// 	$sheet->setCellValue('E'.($i+2+$p_i), $sRow[$i]->recipient_name);
					// 	$sheet->setCellValue('F'.($i+2+$p_i), $sRow[$i]->address);
					// 	$sheet->setCellValue('G'.($i+2+$p_i), $sRow[$i]->postcode);
					// 	$sheet->setCellValue('H'.($i+2+$p_i), $sRow[$i]->mobile);
					// 	// $sheet->setCellValue('I'.($i+2), $sRow[$i]->contact_person);
					// 	$sheet->setCellValue('I'.($i+2+$p_i), $sRow[$i]->recipient_name);
					// 	$sheet->setCellValue('J'.($i+2+$p_i), $sRow[$i]->phone_no);
					// 	$sheet->setCellValue('K'.($i+2+$p_i), $sRow[$i]->email);
					// 	$sheet->setCellValue('L'.($i+2+$p_i), $sRow[$i]->declare_value);
					// 	$sheet->setCellValue('M'.($i+2+$p_i), $sRow[$i]->cod_amount);
					// 	$sheet->setCellValue('N'.($i+2+$p_i), $sRow[$i]->remark);
					// 	$sheet->setCellValue('O'.($i+2+$p_i), $sRow[$i]->total_box);
					// 	$sheet->setCellValue('P'.($i+2+$p_i), $sRow[$i]->sat_del);
					// 	$sheet->setCellValue('Q'.($i+2+$p_i), $sRow[$i]->hrc);
					// 	$sheet->setCellValue('R'.($i+2+$p_i), $sRow[$i]->invr);
					// 	// $sheet->setCellValue('S'.($i+2), $sRow[$i]->service_code);
					// 	// $sheet->setCellValue('S'.($i+2), $request->id);
					// 	$sheet->setCellValue('S'.($i+2+$p_i), '');
					// }
					
				}

			}

			// $cellIterator = $sheet->getRowIterator()->current()->getCellIterator();
			// $cellIterator->setIterateOnlyExistingCells( true );
			// foreach( $cellIterator as $cell ) {
			//     $sheet->getColumnDimension( $cell->getColumn() )->setAutoSize( true );
			// }
			foreach ($sheet->getColumnIterator() as $column) {
			    $sheet->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
			}

			$file = 'consignments.xlsx';
			header('Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-disposition: attachment; filename='.$file);

			$writer = new Xlsx($spreadsheet);
			$writer->save('local/public/excel_files/'.$file);

		}



		public function csvExport()
			{

				$spreadsheet = new Spreadsheet();
				$sheet = $spreadsheet->getActiveSheet();
				$sheet->setTitle("Sheet1");
				$sRow = \App\Models\Backend\Pm_broadcast::get();

				$sheet->setCellValue('A1', 'CustomerId');
				$sheet->setCellValue('B1', 'Message');
				$sheet->setCellValue('C1', 'Show_from');
				$sheet->setCellValue('D1', 'Show_to');
				$sheet->setCellValue('E1', 'Remark');

				for ($i=0; $i < count($sRow) ; $i++) {
					$sheet->setCellValue('A'.($i+2), $sRow[$i]->customers_id_fk);
					$sheet->setCellValue('B'.($i+2), $sRow[$i]->txt_msg);
					$sheet->setCellValue('C'.($i+2), $sRow[$i]->show_from);
					$sheet->setCellValue('D'.($i+2), $sRow[$i]->show_to);
					$sheet->setCellValue('E'.($i+2), $sRow[$i]->remark);
				}

				$file = 'pm_broadcast.csv';
				header('Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
				header('Content-disposition: attachment; filename='.$file);

				$writer = new \PhpOffice\PhpSpreadsheet\Writer\Csv($spreadsheet);
				$writer->setEnclosureRequired(false);
				$writer->save('local/public/excel_files/'.$file);


			}


		public function excelExportCe_regis(Request $request)
		{

			$spreadsheet = new Spreadsheet();
			$sheet = $spreadsheet->getActiveSheet();
			$sheet->setTitle("Sheet1");

			$sRow = \App\Models\Backend\Ce_regis::where('ce_id_fk',$request->ce_id)->get();

// ce_id_fk	customers_id_fk	ticket_number	subject_recipient	regis_date
			$sheet->setCellValue('A1', 'CourseEvent-ID');
			$sheet->setCellValue('B1', 'Customer-ID');
			$sheet->setCellValue('C1', 'Ticket number');
			$sheet->setCellValue('D1', 'Recipient-ID');
			$sheet->setCellValue('E1', 'Register date');
			$sheet->setCellValue('F1', 'CourseEventID');
			$sheet->setCellValue('G1', 'Customer name');
			$sheet->setCellValue('H1', 'Recipient name');

			for ($i=0; $i < count($sRow) ; $i++) {
				$sRowCE = DB::table('course_event')->where('id',$sRow[$i]->ce_id_fk)->get();
				$Customer = DB::table('customers')->where('id',$sRow[$i]->customers_id_fk)->get();
				$sUser = \App\Models\Backend\Permission\Admin::where('id',$sRow[$i]->subject_recipient)->get();
				$sheet->setCellValue('A'.($i+2), $sRow[$i]->ce_id_fk);
				$sheet->setCellValue('B'.($i+2), $sRow[$i]->customers_id_fk);
				$sheet->setCellValue('C'.($i+2), $sRow[$i]->ticket_number);
				$sheet->setCellValue('D'.($i+2), $sRow[$i]->subject_recipient);
				$sheet->setCellValue('E'.($i+2), $sRow[$i]->regis_date);
				$sheet->setCellValue('F'.($i+2), $sRowCE[0]->ce_name);
				$sheet->setCellValue('G'.($i+2), $Customer[0]->prefix_name.$Customer[0]->first_name." ".$Customer[0]->last_name);
				$sheet->setCellValue('H'.($i+2), $sUser[0]->name);
			}

			$file = 'ce_regis.xlsx';
			header('Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-disposition: attachment; filename='.$file);

			$writer = new Xlsx($spreadsheet);
			$writer->save('local/public/excel_files/'.$file);


		}


		public function csvExportCe_regis(Request $request)
			{

				$spreadsheet = new Spreadsheet();
				$sheet = $spreadsheet->getActiveSheet();
				$sheet->setTitle("Sheet1");

				$sRow = \App\Models\Backend\Ce_regis::where('ce_id_fk',$request->ce_id)->get();

	// ce_id_fk	customers_id_fk	ticket_number	subject_recipient	regis_date
				$sheet->setCellValue('A1', 'CourseEvent-ID');
				$sheet->setCellValue('B1', 'Customer-ID');
				$sheet->setCellValue('C1', 'Ticket number');
				$sheet->setCellValue('D1', 'Recipient-ID');
				$sheet->setCellValue('E1', 'Register date');
				$sheet->setCellValue('F1', 'CourseEventID');
				$sheet->setCellValue('G1', 'Customer name');
				$sheet->setCellValue('H1', 'Recipient name');

				for ($i=0; $i < count($sRow) ; $i++) {
					$sRowCE = DB::table('course_event')->where('id',$sRow[$i]->ce_id_fk)->get();
					$Customer = DB::table('customers')->where('id',$sRow[$i]->customers_id_fk)->get();
					$sUser = \App\Models\Backend\Permission\Admin::where('id',$sRow[$i]->subject_recipient)->get();
					$sheet->setCellValue('A'.($i+2), $sRow[$i]->ce_id_fk);
					$sheet->setCellValue('B'.($i+2), $sRow[$i]->customers_id_fk);
					$sheet->setCellValue('C'.($i+2), $sRow[$i]->ticket_number);
					$sheet->setCellValue('D'.($i+2), $sRow[$i]->subject_recipient);
					$sheet->setCellValue('E'.($i+2), $sRow[$i]->regis_date);
					$sheet->setCellValue('F'.($i+2), $sRowCE[0]->ce_name);
					$sheet->setCellValue('G'.($i+2), $Customer[0]->prefix_name.$Customer[0]->first_name." ".$Customer[0]->last_name);
					$sheet->setCellValue('H'.($i+2), $sUser[0]->name);
				}

				$file = 'ce_regis.csv';
				header('Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
				header('Content-disposition: attachment; filename='.$file);

				$writer = new Xlsx($spreadsheet);
				$writer->save('local/public/excel_files/'.$file);


			}


				public function excelExportPromotionCus(Request $request)
					{

						$spreadsheet = new Spreadsheet();
						$amt_sheet = 1;

						for ($j=0; $j < $amt_sheet ; $j++) {

							if($j>0){
								$spreadsheet->createSheet();
							}

							$spreadsheet->setActiveSheetIndex($j);
							$sheet = $spreadsheet->getActiveSheet();
							$sheet->setTitle("Sheet".($j+1));

							$sRow = \App\Models\Backend\PromotionCus::where('promotion_code_id_fk',$request->promotion_code_id_fk)->get();

							$sheet->setCellValue('A1', 'promotion_code');
							// $sheet->setCellValue('B1', 'customer_id_fk');
							$sheet->setCellValue('B1', 'user_name');
							$sheet->setCellValue('C1', 'created_at');

							for ($i=0; $i < count($sRow) ; $i++) {
								$sheet->setCellValue('A'.($i+2), $sRow[$i]->promotion_code);
								// $sheet->setCellValue('B'.($i+2), $sRow[$i]->customer_id_fk);
								$sheet->setCellValue('B'.($i+2), $sRow[$i]->user_name);
								$sheet->setCellValue('C'.($i+2), $sRow[$i]->created_at);
							}


						}

						$file = 'promotion_cus.xlsx';
						header('Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
						header('Content-disposition: attachment; filename='.$file);

						$writer = new Xlsx($spreadsheet);
						$writer->save('local/public/excel_files/'.$file);

					}



				public function excelExportGiftvoucherCus(Request $request)
					{

						// return $request;
						// dd();

						$spreadsheet = new Spreadsheet();
						$amt_sheet = 1;

						for ($j=0; $j < $amt_sheet ; $j++) {

							if($j>0){
								$spreadsheet->createSheet();
							}

							$spreadsheet->setActiveSheetIndex($j);
							$sheet = $spreadsheet->getActiveSheet();
							$sheet->setTitle("Sheet".($j+1));

							$sRow = DB::table('db_giftvoucher_cus')->where('giftvoucher_code_id_fk',$request->giftvoucher_code_id_fk)->get();
							$GiftvoucherCode = \App\Models\Backend\GiftvoucherCode::find($request->giftvoucher_code_id_fk);

							$sheet->setCellValue('A1', 'ID');
							$sheet->setCellValue('B1', 'DESCRIPTIONS_ID');
							$sheet->setCellValue('C1', 'DESCRIPTIONS');
							$sheet->setCellValue('D1', 'CUSTOMER_USERNAME');
							$sheet->setCellValue('E1', 'GIFTVOUCHER_VALUE');
							$sheet->setCellValue('F1', 'STATUS');
							$sheet->setCellValue('G1', 'CREATED_AT');

							for ($i=0; $i < count($sRow) ; $i++) {

								$d = $sRow[$i]->pro_status;
								if($d==4){
		                            $dd= 'รออนุมัติ';
		                        }else if($d==1){
		                            $dd= 'ใช้งานได้';
		                        }else if($d==2){
		                            $dd= 'ถูกใช้แล้ว';
		                        }else if($d==3){
		                            $dd= 'หมดอายุแล้ว';
		                        }else{
		                            $dd= '';
		                        }

								$sheet->setCellValue('A'.($i+2), $sRow[$i]->id);
								$sheet->setCellValue('B'.($i+2), $request->giftvoucher_code_id_fk);
								$sheet->setCellValue('C'.($i+2), $GiftvoucherCode->descriptions);
								$sheet->setCellValue('D'.($i+2), $sRow[$i]->customer_username);
								$sheet->setCellValue('E'.($i+2), $sRow[$i]->giftvoucher_value);
								$sheet->setCellValue('F'.($i+2), $dd);
								$sheet->setCellValue('G'.($i+2), $sRow[$i]->created_at);
							}


						}

						$file = 'giftvoucher_cus.xlsx';
						header('Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
						header('Content-disposition: attachment; filename='.$file);

						$writer = new Xlsx($spreadsheet);
						$writer->save('local/public/excel_files/'.$file);

					}




	}

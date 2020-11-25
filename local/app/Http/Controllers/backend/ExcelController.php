<?php
	namespace App\Http\Controllers\Backend;
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

			$file = 'pm_broadcast.xlsx';
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

		
	}

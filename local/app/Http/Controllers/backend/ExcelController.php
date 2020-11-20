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


	
		
	}

<?php
	namespace App\Http\Controllers\backend;
	use Illuminate\Http\Request;
	use App\Http\Controllers\Controller;
	use PhpOffice\PhpSpreadsheet\Spreadsheet;
	use PhpOffice\PhpSpreadsheet\IOFactory;
	use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
	use DB;
	use File;
	// use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
	use PhpOffice\PhpSpreadsheet\Chart\Chart;
	use PhpOffice\PhpSpreadsheet\Chart\DataSeries;
	use PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;
	use PhpOffice\PhpSpreadsheet\Chart\Layout;
	use PhpOffice\PhpSpreadsheet\Chart\Legend;
	use PhpOffice\PhpSpreadsheet\Chart\PlotArea;
	use PhpOffice\PhpSpreadsheet\Chart\Title;
	// use PhpOffice\PhpSpreadsheet\IOFactory;
	// use PhpOffice\PhpSpreadsheet\Spreadsheet;
	use PhpOffice\PhpSpreadsheet\Chart\Axis;
	use PhpOffice\PhpSpreadsheet\Chart\GridLines;
	use PhpOffice\PhpSpreadsheet\Cell\Cell;
	use PhpOffice\PhpSpreadsheet\Style\Alignment;
	use PhpOffice\PhpSpreadsheet\Style\Border;
	use PhpOffice\PhpSpreadsheet\Style\Fill;
	use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

	class ExcelChart extends Controller
	{

		public function excelExportChart()
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

			$file = 'export_chart.xlsx';
			header('Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-disposition: attachment; filename='.$file);

			$writer = new Xlsx($spreadsheet);
			$writer->save('local/public/excel_files/'.$file);

		}

	
				// Remove the \ before every class if you're using plain php ( new \PHP_Something)
	   public function createexcelfileAction() {
				        // if you are using plain php use instead,
				        //$excel = new PHPExcel();
				 
				 // Set value binder
				//\PhpOffice\PhpSpreadsheet\Cell\Cell::setValueBinder( new \PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder() );
				 
				$spreadsheet = new Spreadsheet(); // สร้าง speadsheet object
				$sheet = $spreadsheet->getActiveSheet(); // กำหนดการทำงานที่่แผ่นงานปัจจุบัน
				 
				 
				// กรณีใช้ข้อมูลจากฐานข้อมูล ในที่นี้เราจะปิดไปก่อน จะใช้เป็นตัวแปร array ด้านล่างแทน
				/* $sql = "
				SELECT province_id,province_name,province_name_eng FROM tbl_provinces
				";
				$result = $mysqli->query($sql);
				if($result && $result->num_rows>0){  // คิวรี่ข้อมูลสำเร็จหรือไม่ และมีรายการข้อมูลหรือไม่
				    $arrayData = $result->fetch_all();
				    $totalRow = count($arrayData); // จำนวนแถวข้อมูลทั้งหมด
				    $result->free(); // สามารถใช้ $result->close() หรือ $result->free_result() แทนได้
				} */
				 
				// ชุดตัวแปร array ข้อมูลตัวอย่าง สมมติเป็นจำนวนสินค้าที่ขายได้ในแต่ละปี
				// แยกเป็นแต่ละไตรมาส
				$arrayData = [
				    ['', '2016', '2017','2018'],
				    ['Q1', 12, 15, 21],
				    ['Q2', 56, 73, 86],
				    ['Q3', 52, 61, 69],
				    ['Q4', 30, 32, 0],
				];
				 
				// กำหนดค่าให้กับพิกัด Cell ในรูปแบบข้อมูล array
				$sheet->fromArray(
				    $arrayData,  // ตัวแปร array ข้อมูล
				    NULL,        // ค่าข้อมูลที่ตรงตามค่านี้ จะไม่ถูกำหนด
				    'A1',         // จุดพิกัดเริ่มต้น ที่ใช้งานข้อมูล เริ่มทึ่มุมบนซ้าย  หากไม่กำหนดจะเป็น "A1" ค่าเริ่มต้น
				    true // มีเพิ่มการกำหนดให้มีการเปรียบข้อมูลที่เป็นค่า null ด้วย หรือก็คือ ถ้ามีค่า null ให้นำมาเปรียบเทียบด้วย
				);
				 
				  
				// กำหนด ชื่อกำกับ ของแต่ละชุดข้อมูล ที่จะสร้างแผนภูมิ ซึ่งประกอบด้วยค่าต่างๆ ดังนี้
				//     ชนิดข้อมูล DATASERIES_TYPE_STRING | DATASERIES_TYPE_NUMBER
				//     พิกัด cell อ้างอิงข้อมูล
				//     การจัดรูปแบบข้อมูล
				//     จำนวนจุดในชุดข้อมูล ในที่นี้คือ 1 คือ ปีนั้นๆ
				//     ค่าข้อมูล ในที่นี้ไม่มีการกำหนด เป็น array ค่าว่างเป็นค่าเริ่มต้น
				//     ข้อมูล marker ในที่นี้ไม่มีการกำหนด ใช้ค่าเริ่มต้นเป็น null
				$dataSeriesLabels = [
				    new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Worksheet!$B$1', null, 1), // 2016
				    new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Worksheet!$C$1', null, 1), // 2017
				    new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Worksheet!$D$1', null, 1), // 2018
				];
				// กำหนด ข้อความสำหรับแกน x ในที่นี้จะใช้ ข้อมูลที่เป็นการระบุ ไตรมาส จาก Q1 - Q4
				//     ชนิดข้อมูล DATASERIES_TYPE_STRING | DATASERIES_TYPE_NUMBER
				//     พิกัด cell อ้างอิงข้อมูล
				//     การจัดรูปแบบข้อมูล
				//     จำนวนจุดในชุดข้อมูล ในที่นี้คือ 1 คือ ปีนั้นๆ
				//     ค่าข้อมูล ในที่นี้ไม่มีการกำหนด เป็น array ค่าว่างเป็นค่าเริ่มต้น
				//     ข้อมูล marker ในที่นี้ไม่มีการกำหนด ใช้ค่าเริ่มต้นเป็น null
				$xAxisTickValues = [
				    new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Worksheet!$A$2:$A$5', null, 4), // Q1 to Q4
				];
				// กำหนด ค่าของแต่ละชุดข้อมูล ที่จะสร้างแผนภูมิ ซึ่งประกอบด้วยค่าต่างๆ ในที่นี้คือชุดข้อมูลของแต่ละปี
				//     ชนิดข้อมูล DATASERIES_TYPE_STRING | DATASERIES_TYPE_NUMBER
				//     พิกัด cell อ้างอิงข้อมูล
				//     การจัดรูปแบบข้อมูล
				//     จำนวนจุดในชุดข้อมูล ในที่นี้คือ 1 คือ ปีนั้นๆ
				//     ค่าข้อมูล ในที่นี้ไม่มีการกำหนด เป็น array ค่าว่างเป็นค่าเริ่มต้น
				//     ข้อมูล marker ในที่นี้ไม่มีการกำหนด ใช้ค่าเริ่มต้นเป็น null
				$dataSeriesValues = [
				    new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'Worksheet!$B$2:$B$5', null, 4),
				    new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'Worksheet!$C$2:$C$5', null, 4),
				    new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'Worksheet!$D$2:$D$5', null, 4),
				];
				// ปรับแต่งเพิ่มเติม กำหนดขนาดเส้นของ ข้อมูลปีสุดท้าย ปี 2018
				// $dataSeriesValues[2]->setLineWidth(60000);
				 
				// สร้างชุดข้อมูล ที่จะใช้สร้างแผนภูมิ
				$series = new DataSeries(
				    DataSeries::TYPE_LINECHART, // ประเภทของแผนภูมิ
				    DataSeries::GROUPING_STANDARD, // การจัดกลุ่มข้อมูล 
				    range(0, count($dataSeriesValues) - 1), // ลำดับข้อมูล เรียงจาก array 0 - 2
				    $dataSeriesLabels, // ชื่อกำกับชุดข้อมูล 
				    $xAxisTickValues, // ข้อความสำหรับแกน x
				    $dataSeriesValues        // ค่าข้อมูล
				);
				// นำชุดข้อมูลไปกำหนดใช้งานในพื้นที่พิกัดของชุดช้อมูล สำหรับสร้างแผนภูมิ 
				$plotArea = new PlotArea(null, [$series]);
				 
				// กำหนดคำอธิบายของแผนภูมิ และการจัดตำแหน่งคำอธิบาย
				$legend = new Legend(Legend::POSITION_TOP, null, false); // กำหนดตำแหน่งของคำอธิบายชุดข้อมูล
				// ตำแหน่งต่างๆ เพิ่มเติม 
				// https://phpoffice.github.io/PhpSpreadsheet/master/PhpOffice/PhpSpreadsheet/Chart/Legend.html
				// POSITION_RIGHT | POSITION_LEFT | POSITION_BOTTOM | POSITION_TOP | POSITION_TOPRIGHT
				$title = new Title('ยอดขายสินค้ารายไตรมาส ปี 2016 - 2018'); // หัวข้อแผนภูมิ
				// $xAxisLabel = new Title('xxxxx'); // ข้อความอธิบายกำกับแกน X // (ถ้ามี)
				$yAxisLabel = new Title('จำนวน (หน่วย:ล้าน)'); // ข้อความอธิบายกำกับแกน Y
				 
				// สร้างแผนภูมิ โดยใช้ค่าต่างๆ จากตัวแปรที่กำหนด 
				$chart = new Chart(
				    'chart1', // กำหนดชื่อ
				    $title, // กำหนดชื่อเรื่องหรือหัวข้อกำกับ
				    $legend, // ตำแหน่งคำอธิบายของชุดข้อมูล 
				    $plotArea, // พิกัดพื้นที่ชุดข้อมูล
				    true, // กำหนดให้แสดงแผนภูมิ
				    0, // กำหนดหากเป็นค่าว่าง ให้ใช้ค่านี้แทน ในที่นี้คือ 0
				    null,  // ข้อความอธิบายกำกับแกน X ในที่นี้ไม่มีกำหนด
				    $yAxisLabel  // ข้อความอธิบายกำกับแกน Y
				);
				 
				// กำหนดพิกัดมุมบนซ้าย และมุมล่างขวา เป็นพื้นที่สำหรับแสดงแผนภูมิ
				$chart->setTopLeftPosition('A7');
				$chart->setBottomRightPosition('H20');
				 
				// เพิ่มแผนภูมิ ไปในแผ่นงาน
				$sheet->addChart($chart);
				// ส่วนของการสร้างไฟล์ excel
				// $writer = new Xlsx($spreadsheet);
				// $output_file = "hello_world.xlsx"; // กำหนดชื่อไฟล์ excel ที่ต้องการ
				// $writer->save($output_file); // สร้าง excel
				 
							$writer = new Xlsx($spreadsheet);
							$writer->setIncludeCharts(true); // กำหนดให้มี แผนภูมิในไฟล์ที่สร้าง
							$writer->save('local/public/excel_files/test8.xlsx');


				// $helper->logWrite($writer, $filename, $callStartTime);

				}


		
	}

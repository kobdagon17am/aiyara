<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;

use Session;
use App\Models\Backend\Page;
use App\Models\Backend\Ce_regis_add;
use App\Models\Backend\PromotionCode_add;

class PagesController extends Controller{

  public function index(){
    // return view('index');
  }

  public function uploadFile(Request $request){

    // dd($request->input('submit'));


    if ($request->input('submit') != null ){

      $file = $request->file('file');

      // File Details 
      $filename = $file->getClientOriginalName();
      $extension = $file->getClientOriginalExtension();
      $tempPath = $file->getRealPath();
      $fileSize = $file->getSize();
      $mimeType = $file->getMimeType();

      // Valid File Extensions
      $valid_extension = array("csv");

      // 2MB in Bytes
      // $maxFileSize = 2097152; 
      // 5MB in Bytes
      $maxFileSize = 5242880; 

      // Check file extension
      if(in_array(strtolower($extension),$valid_extension)){

        // Check file size
        if($fileSize <= $maxFileSize){

          // File upload location
          $location = 'uploads/';
          $destinationPath = public_path($location);
          $file->move($destinationPath, $filename);

          $filepath = public_path("uploads/".$filename);

          // Reading file
          $file = fopen($filepath,"r");

          $importData_arr = array();
          $i = 0;

          while (($filedata = fgetcsv($file, 1000, ",")) !== FALSE) {
             $num = count($filedata );
             
             // Skip first row (Remove below comment if you want to skip the first row)
             if($i == 0){
                $i++;
                continue; 
             }
             
             for ($c=0; $c < $num; $c++) {
                $importData_arr[$i][] = $filedata [$c];
             }
             $i++;
          }
          fclose($file);

          // Insert to MySQL database
          foreach($importData_arr as $importData){

            $insertData = array(
               "customers_id_fk"=>@$importData[0],
               "txt_msg"=>@$importData[1],
               "show_from"=>@$importData[2],
               "show_to"=>@$importData[3],
               "remark"=>@$importData[4],
               "created_at"=>now()
             );
            Page::insertData($insertData);

          }

          Session::flash('message','Import Successful.');

          // dd(Session::get('message'));

        }else{
          Session::flash('message','File too large. File must be less than 5MB.');
        }

      }else{
         Session::flash('message','Invalid File Extension.');
      }

    }

    // Redirect to index
    // return redirect()->action('PagesController@index');
    return redirect()->to(url("backend/pm_broadcast"));
  }


  public function uploadFileXLS(Request $request){

      // dd($request->input('submit'));

    if ($request->input('submit') != null ){

      $file = $request->file('fileXLS');

       // dd($file);

      // File Details 
      $filename = $file->getClientOriginalName();
      $extension = $file->getClientOriginalExtension();
      $tempPath = $file->getRealPath();
      $fileSize = $file->getSize();
      $mimeType = $file->getMimeType();

      // Valid File Extensions
      $valid_extension = array("xlsx");

      // 2MB in Bytes
      // $maxFileSize = 2097152; 
      // 5MB in Bytes
      $maxFileSize = 5242880; 

      // Check file extension
      if(in_array(strtolower($extension),$valid_extension)){

        // Check file size
        if($fileSize <= $maxFileSize){

          // File upload location

          $location = 'uploads/';
          $destinationPath = public_path($location);
          $file->move($destinationPath, $filename);

          $filepath = public_path("uploads/".$filename);

          $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
          $spreadsheet = $reader->load($filepath);

          $worksheet = $spreadsheet->getActiveSheet();
          $highestRow = $worksheet->getHighestRow(); // total number of rows
          $highestColumn = $worksheet->getHighestColumn(); // total number of columns
          $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn); // e.g. 5

          $lines = $highestRow - 2; 
          if ($lines <= 0) {
                   // Exit ('There is no data in the Excel table');
              Session::flash('message','There is no data in the Excel table');

          }else{

              $i = 0;

              for ($row = 1; $row <= $highestRow; ++$row) {
                   $customers_id = $worksheet->getCellByColumnAndRow(1, $row)->getValue(); //customers_id
                   $txt_msg = $worksheet->getCellByColumnAndRow(2, $row)->getValue(); //txt_msg
                   $show_from = $worksheet->getCellByColumnAndRow(3, $row)->getValue(); //show_from
                   $show_to = $worksheet->getCellByColumnAndRow(4, $row)->getValue(); //show_to
                   $remark = $worksheet->getCellByColumnAndRow(5, $row)->getValue(); //remark

                    // Skip first row (Remove below comment if you want to skip the first row)
                     if($i == 0){
                        $i++;
                        continue; 
                     }

                   $insertData = array(
                     "customers_id_fk"=>@$customers_id,
                     "txt_msg"=>@$txt_msg,
                     "show_from"=>@$show_from,
                     "show_to"=>@$show_to,
                     "remark"=>@$remark,
                     "created_at"=>now());
                   Page::insertData($insertData);

                   $i++;

              }

              Session::flash('message','Import Successful.');

          }

        }else{
          Session::flash('message','File too large. File must be less than 5MB.');
        }

      }else{
         Session::flash('message','Invalid File Extension.');
      }

    }

    // Redirect to index
    // return redirect()->action('PagesController@index');
    return redirect()->to(url("backend/pm_broadcast"));

  }




      public function uploadCe_regis(Request $request){

          // dd($request->input('submit'));

        if ($request->input('submit') != null ){

          $file = $request->file('fileXLS');

           // dd($file);

          // File Details 
          $filename = $file->getClientOriginalName();
          $extension = $file->getClientOriginalExtension();
          $tempPath = $file->getRealPath();
          $fileSize = $file->getSize();
          $mimeType = $file->getMimeType();

          // Valid File Extensions
          $valid_extension = array("xlsx");

          // 2MB in Bytes
          // $maxFileSize = 2097152; 
          // 5MB in Bytes
          $maxFileSize = 5242880; 

          // Check file extension
          if(in_array(strtolower($extension),$valid_extension)){

            // Check file size
            if($fileSize <= $maxFileSize){

              // File upload location

              $location = 'uploads/';
              $destinationPath = public_path($location);
              $file->move($destinationPath, $filename);

              $filepath = public_path("uploads/".$filename);

              $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
              $spreadsheet = $reader->load($filepath);

              $worksheet = $spreadsheet->getActiveSheet();
              $highestRow = $worksheet->getHighestRow(); // total number of rows
              $highestColumn = $worksheet->getHighestColumn(); // total number of columns
              $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn); // e.g. 5

              $lines = $highestRow - 2; 
              if ($lines <= 0) {
                       // Exit ('There is no data in the Excel table');
                  Session::flash('message','There is no data in the Excel table');

              }else{

                  $i = 0;

                  for ($row = 1; $row <= $highestRow; ++$row) {

                       $ce_id_fk = $worksheet->getCellByColumnAndRow(1, $row)->getValue(); //ce_id_fk
                       $customers_id_fk = $worksheet->getCellByColumnAndRow(2, $row)->getValue(); //customers_id_fk
                       $ticket_number = $worksheet->getCellByColumnAndRow(3, $row)->getValue(); //ticket_number
                       $subject_recipient = $worksheet->getCellByColumnAndRow(4, $row)->getValue(); //subject_recipient
                       $regis_date = $worksheet->getCellByColumnAndRow(5, $row)->getValue(); //regis_date

                        // Skip first row (Remove below comment if you want to skip the first row)
                         if($i == 0){
                            $i++;
                            continue; 
                         }

                       $insertData = array(
                         "ce_id_fk"=>@$ce_id_fk,
                         "customers_id_fk"=>@$customers_id_fk,
                         "ticket_number"=>@$ticket_number,
                         "subject_recipient"=>@$subject_recipient,
                         "regis_date"=>@$regis_date,
                         "created_at"=>now());
                       Ce_regis_add::insertData($insertData);

                       $i++;

                  }

                  Session::flash('message','Import Successful.');

              }

            }else{
              Session::flash('message','File too large. File must be less than 5MB.');
            }

          }else{
             Session::flash('message','Invalid File Extension.');
          }

        }

        // Redirect to index
        return redirect()->to(url("backend/ce_regis"));
      }




     public function uploadCe_regisCSV(Request $request){

          // dd($request->input('submit'));

        if ($request->input('submit') != null ){

          $file = $request->file('fileCSV');

           // dd($file);

          // File Details 
          $filename = $file->getClientOriginalName();
          $extension = $file->getClientOriginalExtension();
          $tempPath = $file->getRealPath();
          $fileSize = $file->getSize();
          $mimeType = $file->getMimeType();

          // Valid File Extensions
          $valid_extension = array("csv");

          // 2MB in Bytes
          // $maxFileSize = 2097152; 
          // 5MB in Bytes
          $maxFileSize = 5242880; 

          // Check file extension
          if(in_array(strtolower($extension),$valid_extension)){

            // Check file size
            if($fileSize <= $maxFileSize){

              // File upload location

              $location = 'uploads/';
              $destinationPath = public_path($location);
              $file->move($destinationPath, $filename);

              $filepath = public_path("uploads/".$filename);

              $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
              $spreadsheet = $reader->load($filepath);

              $worksheet = $spreadsheet->getActiveSheet();
              $highestRow = $worksheet->getHighestRow(); // total number of rows
              $highestColumn = $worksheet->getHighestColumn(); // total number of columns
              $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn); // e.g. 5

              $lines = $highestRow - 2; 
              if ($lines <= 0) {
                       // Exit ('There is no data in the Excel table');
                  Session::flash('message','There is no data in the Excel table');

              }else{

                  $i = 0;

                  for ($row = 1; $row <= $highestRow; ++$row) {

                       $ce_id_fk = $worksheet->getCellByColumnAndRow(1, $row)->getValue(); //ce_id_fk
                       $customers_id_fk = $worksheet->getCellByColumnAndRow(2, $row)->getValue(); //customers_id_fk
                       $ticket_number = $worksheet->getCellByColumnAndRow(3, $row)->getValue(); //ticket_number
                       $subject_recipient = $worksheet->getCellByColumnAndRow(4, $row)->getValue(); //subject_recipient
                       $regis_date = $worksheet->getCellByColumnAndRow(5, $row)->getValue(); //regis_date

                        // Skip first row (Remove below comment if you want to skip the first row)
                         if($i == 0){
                            $i++;
                            continue; 
                         }

                       $insertData = array(
                         "ce_id_fk"=>@$ce_id_fk,
                         "customers_id_fk"=>@$customers_id_fk,
                         "ticket_number"=>@$ticket_number,
                         "subject_recipient"=>@$subject_recipient,
                         "regis_date"=>@$regis_date,
                         "created_at"=>now());
                       Ce_regis_add::insertData($insertData);

                       $i++;

                  }

                  Session::flash('message','Import Successful.');

              }

            }else{
              Session::flash('message','File too large. File must be less than 5MB.');
            }

          }else{
             Session::flash('message','Invalid File Extension.');
          }

        }

        // Redirect to index
        return redirect()->to(url("backend/ce_regis"));
      }




      public function uploadPromotionCus(Request $request){

          // dd($request->all());
          // dd($request->promotion_code_id_fk);

          // dd($request->input('submit'));

        if ($request->input('submit') != null ){

          $file = $request->file('fileXLS');

           // dd($file);

          // File Details 
          $filename = $file->getClientOriginalName();
          $extension = $file->getClientOriginalExtension();
          $tempPath = $file->getRealPath();
          $fileSize = $file->getSize();
          $mimeType = $file->getMimeType();

          // Valid File Extensions
          $valid_extension = array("xlsx");

          // 2MB in Bytes
          // $maxFileSize = 2097152; 
          // 5MB in Bytes
          $maxFileSize = 5242880; 

          // Check file extension
          if(in_array(strtolower($extension),$valid_extension)){

            // Check file size
            if($fileSize <= $maxFileSize){



            if( @$request->promotion_code_id_fk ){
              $sRow = \App\Models\Backend\PromotionCode::find($request->promotion_code_id_fk );
            }else{
              $sRow = new \App\Models\Backend\PromotionCode;
            }

            $sRow->promotion_name = $request->promotion_name;
            $sRow->pro_sdate = $request->pro_sdate;
            $sRow->pro_edate = $request->pro_edate;
            $sRow->pro_status = 4 ;
            $sRow->created_at = date('Y-m-d H:i:s');
            $sRow->save();


              // File upload location

              $location = 'uploads/';
              $destinationPath = public_path($location);
              $file->move($destinationPath, $filename);

              $filepath = public_path("uploads/".$filename);

              $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
              $spreadsheet = $reader->load($filepath);

              $worksheet = $spreadsheet->getActiveSheet();
              $highestRow = $worksheet->getHighestRow(); // total number of rows
              $highestColumn = $worksheet->getHighestColumn(); // total number of columns
              $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn); // e.g. 5

              $lines = $highestRow - 2; 
              if ($lines <= 0) {
                       // Exit ('There is no data in the Excel table');
                  Session::flash('message','There is no data in the Excel table');

              }else{

                  $i = 0;

                  for ($row = 1; $row <= $highestRow; ++$row) {

                       $promotion_code = $worksheet->getCellByColumnAndRow(1, $row)->getValue(); //ce_id_fk
                       $customers_id_fk = $worksheet->getCellByColumnAndRow(2, $row)->getValue(); //customers_id_fk

                        // Skip first row (Remove below comment if you want to skip the first row)
                         if($i == 0){
                            $i++;
                            continue; 
                         }

                       $insertData = array(
                         "promotion_code_id_fk"=>@$sRow->id,
                         "promotion_code"=>@$request->prefix_coupon.@$promotion_code,
                         "customer_id_fk"=>@$customers_id_fk,
                         "pro_status"=> '4' ,
                         "created_at"=>now());
                       PromotionCode_add::insertData($insertData);

                       $i++;

                  }

                  Session::flash('message','Import Successful.');

              }

            }else{
              Session::flash('message','File too large. File must be less than 5MB.');
            }

          }else{
             Session::flash('message','Invalid File Extension.');
          }

        }

        // Redirect to index
        // return redirect()->to(url("backend/promotion_cus"));
          // if( @$request->promotion_code_id_fk ){
          //      return redirect()->to(url("backend/promotion_cus/".@$request->promotion_code_id_fk."/edit"));
          // }else{
          //      return redirect()->to(url("backend/promotion_cus"));
          // }

           return redirect()->to(url("backend/promotion_cus/".$sRow->id."/edit"));

        

      }


}
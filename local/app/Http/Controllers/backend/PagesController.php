<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;

use Session;
use App\Models\Backend\Page;

class PagesController extends Controller{

  public function index(){
    // return view('index');
  }

  public function uploadFile(Request $request){

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
      $maxFileSize = 2097152; 

      // Check file extension
      if(in_array(strtolower($extension),$valid_extension)){

        // Check file size
        if($fileSize <= $maxFileSize){

          // File upload location
          $location = 'uploads/';


          $location = 'uploads/';
          $destinationPath = public_path($location);
          $file->move($destinationPath, $filename);
              // $sRow->img_url    = 'local/public/'.$location;

          // Upload file
          // $file->move($location,$filename);
          // Import CSV to Database
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

            // $insertData = array(
            //    "username"=>@$importData[0],
            //    "name"=>@$importData[1],
            //    "gender"=>@$importData[2],
            //    "email"=>@$importData[3]);
            // Page::insertData($insertData);

            $insertData = array(
               "customers_id_fk"=>@$importData[0],
               "txt_msg"=>@$importData[1]);
            Page::insertData($insertData);

          }

          Session::flash('message','Import Successful.');

          // dd(Session::get('message'));

        }else{
          Session::flash('message','File too large. File must be less than 2MB.');
        }

      }else{
         Session::flash('message','Invalid File Extension.');
      }

    }

    // Redirect to index
    // return redirect()->action('PagesController@index');
    return redirect()->to(url("backend/pm_broadcast"));
  }
}
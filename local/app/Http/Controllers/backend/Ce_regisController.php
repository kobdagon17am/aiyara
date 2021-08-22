<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;

class Ce_regisController extends Controller
{

    public function index(Request $request)
    {

      return view('backend.ce_regis.index');
      
    }

 public function create()
    {
      $sUser = \App\Models\Backend\Permission\Admin::get();
      $subject_recipient = $sUser[0]->name;

      $sCourse = \App\Models\Backend\Course_event::get();
       // dd($sCourse);

      $Customer = DB::select(" select * from customers limit 100 ");
      $Ce_regis_gift = DB::select(" select * from dataset_ce_regis_gift ");

      return View('backend.ce_regis.form')->with(
        array(
           'subject_recipient_name'=>$subject_recipient,'Customer'=>$Customer,'sCourse'=>$sCourse,'Ce_regis_gift'=>$Ce_regis_gift
        ) );
    }
    public function store(Request $request)
    {
      // dd($request->all());

      // array:6 [▼
      //     "save_ce_regis" => "1"
      //     "id_ce_regis" => "1"
      //     "_token" => "jKiBB4JPc7BpUWng55zeZ0dL2skf1OEbbw0uox5q"
      //     "regis_gift" => array:4 [▼
      //       0 => "1"
      //       1 => "2"
      //       2 => "3"
      //       3 => "4"
      //     ]
      //     "note" => null
      //     "status_in" => "1"
      //   ]

      if(isset($request->save_ce_regis)){

          if($request->id_ce_regis){
            $sRow = \App\Models\Backend\Ce_regis::find($request->id_ce_regis);
          }else{
            $sRow = new \App\Models\Backend\Ce_regis;
          }

          if(isset($request->status_in)){
              $status_in = 1;
              $sRow->regis_date    = date("Y-m-d");
          }else{
              $status_in = 0;
              $sRow->regis_date    = NULL ;
          }

          $ce_regis_gift = implode(',',request('regis_gift'));

          $sRow->ce_regis_gift    = $ce_regis_gift;
          $sRow->status_in    = $status_in;
          
          $sRow->note    = request('note');
          // $sRow->ce_id_fk    = request('ce_id_fk');
          // $sRow->customers_id_fk    = request('customers_id_fk');
          // $sRow->ticket_number    = request('ticket_number');
          // $sRow->regis_date    = request('regis_date');
          // $sRow->subject_recipient    = request('subject_recipient');
                    
          // $sRow->created_at = date('Y-m-d H:i:s');
          $sRow->save();

        return redirect()->to(url("backend/ce_regis/create?v=2"));

      }else{
        return $this->form();
      }
      
    }

    public function edit($id)
    {
      $sRow = \App\Models\Backend\Ce_regis::find($id);

      $sUser = \App\Models\Backend\Permission\Admin::get();
      $subject_recipient = $sUser[0]->name;

      $sCourse = \App\Models\Backend\Course_event::get();

      $Customer = DB::select(" select * from customers limit 100");
      return View('backend.ce_regis.form')->with(
        array(
           'sRow'=>$sRow, 'id'=>$id, 'subject_recipient_name'=>$subject_recipient,'Customer'=>$Customer,'sCourse'=>$sCourse
        ) );
    }

    public function update(Request $request, $id)
    {
      // dd($request->all());
      return $this->form($id);
    }

   public function form($id=NULL)
    {
      \DB::beginTransaction();
      try {
          if( $id ){
            $sRow = \App\Models\Backend\Ce_regis::find($id);
          }else{
            $sRow = new \App\Models\Backend\Ce_regis;
          }

          $sRow->ce_id_fk    = request('ce_id_fk');
          $sRow->customers_id_fk    = request('customers_id_fk');
          $sRow->ticket_number    = request('ticket_number');
          $sRow->regis_date    = request('regis_date');
          $sRow->subject_recipient    = request('subject_recipient');
                    
          $sRow->created_at = date('Y-m-d H:i:s');
          $sRow->save();

          \DB::commit();

           return redirect()->to(url("backend/ce_regis/".$sRow->id."/edit?role_group_id=".request('role_group_id')."&menu_id=".request('menu_id')));
           

      } catch (\Exception $e) {
        echo $e->getMessage();
        \DB::rollback();
        return redirect()->action('backend\Ce_regisController@index')->with(['alert'=>\App\Models\Alert::e($e)]);
      }
    }

    public function destroy($id)
    {
      $sRow = \App\Models\Backend\Ce_regis::find($id);
      if( $sRow ){
        $sRow->forceDelete();
      }
      return response()->json(\App\Models\Alert::Msg('success'));
    }

    public function Datatable(){
      $sTable = \App\Models\Backend\Ce_regis::search()->orderBy('id', 'asc');
      $sQuery = \DataTables::of($sTable);
      return $sQuery
      ->addColumn('customer_name', function($row) {
        $Customer = DB::select(" select * from customers where id=".$row->customers_id_fk." ");
        return $Customer[0]->user_name." : ".$Customer[0]->prefix_name.$Customer[0]->first_name." ".$Customer[0]->last_name;
      })
      ->addColumn('ce_name', function($row) {
        $Course_event = \App\Models\Backend\Course_event::find($row->ce_id_fk);
        return $Course_event->ce_name;
      })
      ->addColumn('regis_date', function($row) {
        if($row->regis_date==""){
          return "<font color=red>* ยังไม่ลงทะเบียน</font>";
        }else{
          return "<font color=blue>".$row->regis_date."</font>";;
        }
      })
      ->escapeColumns('regis_date')
      ->addColumn('cus_package', function($row) {
       $rs =  DB::select(" 
                SELECT
                 dataset_package.dt_package
                FROM
                customers
                Left Join dataset_package ON customers.package_id = dataset_package.id
                where customers.id = '".$row->customers_id_fk."'
             ");
       return $rs[0]->dt_package;
      })
      ->escapeColumns('cus_package')      
      ->addColumn('ce_regis_gift', function($row) {
        if($row->ce_regis_gift){
           $rs =  DB::select(" 
                    SELECT * FROM dataset_ce_regis_gift where id in (".$row->ce_regis_gift.")
                 ");
           // return $rs[0]->txt_desc;
           $arr = [];
           foreach ($rs AS $r){
             array_push($arr,$r->txt_desc);
           }
           return $arr;
        }
       // return $row->ce_regis_gift;
      })
      ->escapeColumns('ce_regis_gift')        
      ->make(true);
    }



}

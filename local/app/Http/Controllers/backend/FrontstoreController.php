<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;

class FrontstoreController extends Controller
{

    public function index(Request $request)
    {
      $Customer = DB::select(" select * from customers ");
      return View('backend.frontstore.index')->with(
        array(
           'Customer'=>$Customer
        ) );
      
    }

 public function create()
    {
      $sUser = \App\Models\Backend\Permission\Admin::get();
      $subject_recipient = $sUser[0]->name;

      $sCourse = \App\Models\Backend\Course_event::get();

      $Customer = DB::select(" select * from customers ");
      return View('backend.frontstore.form')->with(
        array(
           'subject_recipient_name'=>$subject_recipient,'Customer'=>$Customer,'sCourse'=>$sCourse
        ) );
    }
    public function store(Request $request)
    {
      return $this->form();
    }

    public function edit($id)
    {
       $sRow = \App\Models\Backend\Frontstore::find($id);
      $sUser = \App\Models\Backend\Permission\Admin::get();
      $subject_recipient = $sUser[0]->name;

      $sCourse = \App\Models\Backend\Course_event::get();

      $Customer = DB::select(" select * from customers ");
      return View('backend.frontstore.form')->with(
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
            $sRow = \App\Models\Backend\Frontstore::find($id);
          }else{
            $sRow = new \App\Models\Backend\Frontstore;
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
        return redirect()->action('backend\FrontstoreController@index')->with(['alert'=>\App\Models\Alert::e($e)]);
      }
    }

    public function destroy($id)
    {
      $sRow = \App\Models\Backend\Frontstore::find($id);
      if( $sRow ){
        $sRow->forceDelete();
      }
      return response()->json(\App\Models\Alert::Msg('success'));
    }

    public function Datatable(){
      $sTable = \App\Models\Backend\Frontstore::search()->orderBy('id', 'asc');
      $sQuery = \DataTables::of($sTable);
      return $sQuery
      ->addColumn('customer_name', function($row) {
        $Customer = DB::select(" select * from customers where id=".$row->customers_id_fk." ");
        return $Customer[0]->prefix_name.$Customer[0]->first_name." ".$Customer[0]->last_name;
      })
      ->addColumn('ce_name', function($row) {
        $Course_event = \App\Models\Backend\Course_event::find($row->ce_id_fk);
        return $Course_event->ce_name;
      })
      ->addColumn('updated_at', function($row) {
        return is_null($row->updated_at) ? '-' : $row->updated_at;
      })
      ->make(true);
    }



}

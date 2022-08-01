<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;

class PmController extends Controller
{

    public function index(Request $request)
    {

      return view('backend.pm.index');

    }

 public function create()
    {
      $sMainGroup = DB::select(" select * from role_group where id<>1 ");
      $sOperator = \App\Models\Backend\Permission\Admin::get();
      $Customer = DB::select(" select * from customers limit 100 ");
      return View('backend.pm.form')->with(
        array(
           'sOperator'=>$sOperator,'Customer'=>$Customer,'sMainGroup'=>$sMainGroup
        ) );
    }
    public function store(Request $request)
    {
      return $this->form();
    }

    public function edit($id)
    {
       $sRow = \App\Models\Backend\Pm::find($id);
       $sUser = \App\Models\Backend\Permission\Admin::where('id', $sRow->subject_recipient)->first();
        if($sUser){
          $subject_recipient = $sUser->name;
        }else{
          $subject_recipient = '';
        }
       $operator_name = \App\Models\Backend\Permission\Admin::where('id', $sRow->operator)->first();

       if($sUser){
        $operator_name = $operator_name->name;
      }else{
        $operator_name = '';
      }


      if($sRow->customers_id_fk!=0){
        $Customer = DB::table('customers')->where('id',$sRow->customers_id_fk)->get();
      }else{
       $Customer = DB::select(" select * from customers limit 100 ");
      }

       $sMainGroup = DB::select(" select * from role_group where id<>1 ");
       $ans_more = DB::table('pm_answers')->where('pm_id_fk',$sRow->id)->orderBy('created_at','asc')->get();

       return View('backend.pm.form')->with(array('sRow'=>$sRow,'ans_more'=>$ans_more, 'id'=>$id, 'subject_recipient_name'=>$subject_recipient,'operator_name'=>$operator_name,'Customer'=>$Customer,'sMainGroup'=>$sMainGroup));
    }

    public function anser($id)
    {
       $sRow = \App\Models\Backend\Pm::find($id);
       $sUser = \App\Models\Backend\Permission\Admin::where('id', $sRow->subject_recipient)->first();
        if($sUser){
          $subject_recipient = $sUser->name;
        }else{
          $subject_recipient = '';
        }
       $operator_name = \App\Models\Backend\Permission\Admin::where('id', $sRow->operator)->first();

       if($sUser){
        $operator_name = $operator_name->name;
      }else{
        $operator_name = '';
      }


      if($sRow->customers_id_fk!=0){
        $Customer = DB::table('customers')->where('id',$sRow->customers_id_fk)->get();
      }else{
       $Customer = DB::select(" select * from customers limit 100 ");
      }

       $sMainGroup = DB::select(" select * from role_group where id<>1 ");
       $ans_more = DB::table('pm_answers')->where('pm_id_fk',$sRow->id)->orderBy('created_at','asc')->get();

       return View('backend.pm.anser')->with(array('sRow'=>$sRow,'ans_more'=>$ans_more, 'id'=>$id, 'subject_recipient_name'=>$subject_recipient,'operator_name'=>$operator_name,'Customer'=>$Customer,'sMainGroup'=>$sMainGroup));
    }

    public function pm_anser_save(Request $request)
    {
       DB::table('pm_answers')->insert([
         'pm_id_fk' => $request->pm_id,
         'customers_id_fk' => $request->customers_id_fk,
         'txt_answers' => $request->txt_answers,
         'type' => 'admin',
         'created_at' => date('Y-m-d H:i:s'),
         'updated_at' => date('Y-m-d H:i:s')
       ]);

      //  DB::table('pm')->where('id',$request->pm_id)->update([
      //   'pm_id_fk' => $request->pm_id,
      //   'updated_at' => date('Y-m-d H:i:s')
      // ]);
       return redirect()->back();
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
            $sRow = \App\Models\Backend\Pm::find($id);
          }else{
            $sRow = new \App\Models\Backend\Pm;
          }

          $sRow->role_group_id_fk    = request('role_group_id_fk');
          $sRow->receipt_date    = request('receipt_date');
          $sRow->customers_id_fk    = request('customers_id_fk');
          $sRow->topics_question    = request('topics_question');
          $sRow->details_question    = request('details_question');
          $sRow->txt_answers    = request('txt_answers');
          $sRow->level_class    = request('level_class');
          $sRow->subject_recipient    = request('subject_recipient');
          $sRow->operator    = request('operator');
          $sRow->last_update    = request('last_update');

          $sRow->status_close_job    = request('status_close_job')?request('status_close_job'):0;
          $sRow->status    = request('status')?request('status'):0;

          $sRow->created_at = date('Y-m-d H:i:s');
          $sRow->save();

          DB::table('pm_answers')->insert([
            'pm_id_fk' => $sRow->id,
            'customers_id_fk' => request('customers_id_fk'),
            'operator' => request('operator'),
            'txt_answers' => request('txt_answers'),
            'type' => 'admin',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
          ]);


          \DB::commit();

         // return redirect()->action('backend\PmController@index')->with(['alert'=>\App\Models\Alert::Msg('success')]);
           return redirect()->to(url("backend/pm/".$sRow->id."/edit?role_group_id=".request('role_group_id')."&menu_id=".request('menu_id')));


      } catch (\Exception $e) {
        echo $e->getMessage();
        \DB::rollback();
        return redirect()->action('backend\PmController@index')->with(['alert'=>\App\Models\Alert::e($e)]);
      }
    }

    public function destroy($id)
    {
      $sRow = \App\Models\Backend\Pm::find($id);
      if( $sRow ){
        $sRow->forceDelete();
      }
      return response()->json(\App\Models\Alert::Msg('success'));
    }

    public function Datatable(){
      $sTable = \App\Models\Backend\Pm::search()->where('type','customer')->orderBy('id', 'asc');
      $sQuery = \DataTables::of($sTable);
      return $sQuery
      ->addColumn('role_name', function($row) {
        if($row->role_group_id_fk){
          $sRole_group = \App\Models\Backend\Role::where('id', $row->role_group_id_fk)->get();
          return $sRole_group[0]->role_name;
        }
      })
      ->addColumn('recipient_name', function($row) {
        if($row->subject_recipient){
          $sUser = \App\Models\Backend\Permission\Admin::where('id', $row->subject_recipient)->get();
          return $sUser[0]->name;
        }
      })
      ->addColumn('operator_name', function($row) {
        if($row->operator){
          $sUser = \App\Models\Backend\Permission\Admin::where('id', $row->operator)->get();
          return $sUser[0]->name;
        }
      })
      ->addColumn('updated_at', function($row) {
        return is_null($row->updated_at) ? '-' : $row->updated_at;
      })

      ->addColumn('topics_question', function($row) {
       $ans_pm = DB::table('pm_answers')->select('type')->where('pm_id_fk',$row->id)->orderBy('updated_at','desc')->first();
       $p = "";
       if($ans_pm){
          if($ans_pm->type == 'customer'){
            $p = '<br><label style="color:red;">มีข้อความที่ยังไม่ตอบ</label>';
          }
       }
        return $row->topics_question.$p;
      })
     ->escapeColumns('topics_question')

      ->make(true);
    }





}

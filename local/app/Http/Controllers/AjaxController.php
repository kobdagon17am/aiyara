<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\Datatables\Datatables;
use Validator;
use DB;
use PDO;
use Session;
use Storage;
// use App\Model\CourseModel;
// use App\Model\MemberModel;
// use App\Model\MemberAddModel;  
// use App\Http\Controllers\backend\ActiveMemberController;
// use App\Http\Controllers\backend\ActiveCourseController;

class AjaxController extends Controller
{
   

    public function saveEmailSubscribe(Request $request)
    {

        // return $request ;

        \DB::beginTransaction();
          try {
      
              $sRow = new \App\Models\Backend\Subscribe\Subscribe;
              $sRow->save_date    = date('Y-m-d');
              $sRow->save_time    = date('H:i:s');
              $sRow->email    = $request->email;
              $sRow->created_at = date('Y-m-d H:i:s');
              $sRow->save();

              \DB::commit();

             // return redirect()->action('Backend\SubscribeController@index')->with(['alert'=>\App\Models\Alert::Msg('success')]);

          } catch (\Exception $e) {
            echo $e->getMessage();
            \DB::rollback();
            return redirect()->action('Backend\SubscribeController@index')->with(['alert'=>\App\Models\Alert::e($e)]);
          }

    }
    

}
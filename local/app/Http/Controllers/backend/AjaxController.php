<?php
namespace App\Http\Controllers\Backend;
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

    public function ajaxClearDataPm_broadcast()
    {
        DB::delete(" TRUNCATE `pm_broadcast` ");
    }

    public function ajaxFetchData(Request $request)
    {
        // DB::delete(" TRUNCATE `course_event_regis` ");

        // return($request->search);

        // if(isset($request->search)){

			    // $result = DB::select("SELECT * FROM course_event_regis WHERE ticket_number like'%".$request->search."%'");
			    // $response = [];
			    // while($result){
			    //     $response[] = array("value"=>$result[0]->id,"label"=>$result[0]->ticket_number);
			    // }
			    // return(@$result[0]->ticket_number);
			    // return compact($result);
			// }

    	// if(isset($request->search)){
    	// if($request->has('term')){
            // return Product::where('name','like','%'.$request->input('term').'%')->get();
    	// return DB::select("SELECT * FROM course_event_regis WHERE ticket_number like'%".$request->search."%'");
        // }


    }



}
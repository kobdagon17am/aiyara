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


}
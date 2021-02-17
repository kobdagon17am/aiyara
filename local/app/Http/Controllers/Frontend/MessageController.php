<?php

namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;

class MessageController extends Controller
{
	public function index($active = ''){

		if($active){
			$contact = '';
			$inbox = 'active';
			$inbox_tab = 'show active';
			$contact_tab = '';
		}else{
			$contact = 'active';
			$inbox = '';
			$contact_tab = 'show active';
			$inbox_tab = '';
		}
		$customers_id_fk = Auth::guard('c_user')->user()->id;
		$data = DB::table('pm_answers')
		->select('pm.*','pm_answers.txt_answers as answers',DB::raw('MAX(pm_answers.created_at) as answers_create')) 
		->where('pm.customers_id_fk','=',$customers_id_fk)
		->leftjoin('pm', 'pm_answers.pm_id_fk','=','pm.id') 
		->groupby('pm_answers.pm_id_fk')
		->orderby('pm_answers.created_at','DESC')
		->get(); 

		return view('frontend/message',compact('data','contact','inbox','contact_tab','inbox_tab'));
	} 

	public function message_read($pm_id){

		$pm = DB::table('pm')
		->where('id','=',$pm_id)
		->update(['see_status' => 1,'last_update'=>date('Y-m-d')]);

		$customers_id_fk = Auth::guard('c_user')->user()->id;
		$pm_data = DB::table('pm')
		->select('*') 
		->where('id','=',$pm_id)
		->orderby('id','DESC')
		->first();

		$data = DB::table('pm_answers')
		->select('*') 
		->where('pm_id_fk','=',$pm_id)  
		->orderby('created_at','ASC')
		->get();


		return view('frontend/message_read',compact('pm_data','data'));
	}



	public function message_question(Request $request){

		try {

			DB::table('pm')->insert([
				'customers_id_fk'=>Auth::guard('c_user')->user()->id,
				'topics_question' => $request->subject,
				'details_question' => $request->question,
				'see_status'=>1,
			]);

			return redirect('message')->withSuccess('success');
			
		} catch (Exception $e){
			return redirect('message')->withSuccess($e);
			
		}

		
	}

	public function message_reply(Request $request){

		try {


			$pm = DB::table('pm')
			->where('id','=',$request->pm_id)
			->update(['see_status' => 0,'status'=>1 ,'last_update'=>date('Y-m-d')]);


			DB::table('pm_answers')->insert([ 
				'customers_id_fk'=>Auth::guard('c_user')->user()->id,
				'pm_id_fk' => $request->pm_id,
				'txt_answers' => $request->question_txt,
				'type' => 'customer',
			]);


			return redirect('message_read/'.$request->pm_id)->withSuccess('success');
			
		} catch (Exception $e){
			return redirect('message_read/'.$request->pm_id)->withSuccess($e);
			
		}
	}


}
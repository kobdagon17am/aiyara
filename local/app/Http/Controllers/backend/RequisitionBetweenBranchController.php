<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;
use App\Models\Backend\Branchs;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Backend\RequisitionBetweenBranch;
use Yajra\DataTables\DataTables;
use App\Helpers\General;

class RequisitionBetweenBranchController extends Controller
{
    public function index()
    {
        $fromBranchs = Branchs::when(auth()->user()->permission !== 1, function ($query) {
            return $query->where('id', auth()->user()->branch_id_fk);
        })->get();

        $toBranchs = Branchs::get();

        $products = DB::table('products')
            ->selectRaw('products.id as product_id, products.product_code, CASE WHEN products_details.product_name IS NULL THEN "* ไม่ได้กรอกชื่อสินค้า" ELSE products_details.product_name END as product_name')
            ->leftJoin('products_details', 'products_details.product_id_fk', '=', 'products.id')
            ->where('products_details.lang_id', 1)
            ->orderBy('products.product_code','asc')
            ->get();

        return view('backend.requisition.index')->with([
            'fromBranchs' => $fromBranchs,
            'toBranchs' => $toBranchs,
            'products' => $products,
        ]);
    }

    public function store(Request $request)
    {
        try {

            DB::transaction(function () use($request) {

                $requisiton = RequisitionBetweenBranch::create($request->only('from_branch_id', 'to_branch_id') + ['requisitioned_by' => auth()->id()]);

                $requisiton->requisition_details()->createMany($request->details);

            });

            return back()->withSuccess('ส่งคำขอเรียบร้อยแล้ว.');

        } catch (\Exception $e) {
            DB::rollback();
            \Log::info('>>>> Cannot Insert Requisition <<<<');
            \Log::info($e->getMessage());
            return back()->withError($e->getMessage());
        }
    }

    public function update(Request $request, RequisitionBetweenBranch $requisition_between_branch)
    {
        if ($request->is_approve == RequisitionBetweenBranch::APPROVED) {
          $data = $request->only('is_approve') + ['approved_by' => auth()->user()->id, 'approved_at' => now()];
        } else {
          $data = $request->only('is_approve');
        }

        $requisition_between_branch->update($data);

        $message = $request->is_approve == RequisitionBetweenBranch::APPROVED ? 'อนุมัติรายการแล้ว.' : 'ยกเลิกรายการแล้ว';

        return back()->withSuccess($message);
    }

    public function dtListApprove(Request $request)
    {
      $approves = RequisitionBetweenBranch::with('requisition_details:requisition_between_branch_id,product_name,amount')->orderBy('created_at','desc')->isApprove();

      return DataTables::of($approves)
        ->editColumn('to_branch_id', function ($approve) {
          return $approve->from_branch->b_name . ' => ' . $approve->to_branch->b_name;
        })
        ->addColumn('button_products', function ($approve) {
          $products = $approve->requisition_details;
          return "<button type='button' class='btn btn-success btn-sm waves-effect waves-light' data-toggle='modal' data-target='#modalProducts' data-products='$products'>ดูรายการสินค้า</button>";
        })
        ->addColumn('approved_by', function ($approve) {
          return $approve->approve_by->name;
        })
        ->editColumn('created_at', function ($approve) {
          return $approve->created_at->format('d/m/Y H:i:s');
        })
        ->editColumn('updated_at', function ($approve) {
          return $approve->updated_at->format('d/m/Y H:i:s');
        })
        ->rawColumns(['button_products'])
        ->make(true);
    }

    public function dtListWaitApprove(Request $request)
    {
        $wait_approves = RequisitionBetweenBranch::with('requisition_details:requisition_between_branch_id,product_name,amount')->waitApproveByBranch();
        $perm = 0;
        $permis = DB::table('role_permit')->where('role_group_id_fk',auth()->user()->role_group_id_fk)->where('menu_id_fk',83)->first();
        if($permis){
          if($permis->can_approve==1){
            $perm = 1;
          }
        }

      return DataTables::of($wait_approves)
        ->editColumn('from_branch_id', function ($wait_approve) {
          return $wait_approve->from_branch->b_name . ' => ' . $wait_approve->to_branch->b_name;
        })
        ->addColumn('requisition_by', function ($wait_approve) {
          return $wait_approve->requisition_by->name;
        })
        ->addColumn('button_products', function ($wait_approve) {
          $products = $wait_approve->requisition_details;
          return "<button type='button' class='btn btn-primary btn-sm waves-effect waves-light' data-toggle='modal' data-target='#modalProducts' data-products='$products'>ดูรายการสินค้า</button>";
        })
        ->editColumn('created_at', function ($wait_approve) {
          return $wait_approve->created_at->format('d/m/Y H:i:s');
        })
        ->editColumn('actions', function ($wait_approve) use($perm) {
          $routeUpdate = route('backend.requisition_between_branch.update', $wait_approve);
          $csrfToken = csrf_field();
          $methodField = "<input type='hidden' name='_method' value='PATCH' />";

          if (auth()->user()->permission != 1 && $wait_approve->from_branch_id === auth()->user()->branch_id_fk) {
            if($perm==1){
              return "
              <form action='$routeUpdate' method='POST' class='d-inline form-approve'>
                $csrfToken
                $methodField
                <input type='hidden' name='is_approve' value='1'>
                <input type='submit' class='btn btn-success btn-sm' value='อนุมัติ'>
              </form>
              <form action='$routeUpdate' method='POST' class='d-inline form-approve'>
                $csrfToken
                $methodField
                <input type='hidden' name='is_approve' value='2'>
                <input type='submit' class='btn btn-warning btn-sm' value='ไม่อนุมัติ'>
              </form>
              <form action='".url('backend/requisition_between_branch_cancel')."' method='post' enctype='multipart/form-data' class='d-inline'>
              $csrfToken
              <input type='hidden' name='is_cacel' value='3'>
              <input type='hidden' name='data_id' value='".$wait_approve->id."'>
              <input type='submit' class='btn btn-danger btn-sm' value='ยกเลิก'>
            </form>
            ";
            }else{
              return "
              <span class='badge badge-secondary font-size-15'>รอยืนยันการอนุมัติ</span>
              <form action='".url('backend/requisition_between_branch_cancel')."' method='post' enctype='multipart/form-data' class='d-inline'>
              $csrfToken
              <input type='hidden' name='is_cacel' value='3'>
              <input type='hidden' name='data_id' value='".$wait_approve->id."'>
              <input type='submit' class='btn btn-danger btn-sm' value='ยกเลิก'>
            </form>
              ";

            }

          } else {

            return "
              <form action='$routeUpdate' method='POST' class='d-inline form-approve'>
                $csrfToken
                $methodField
                <input type='hidden' name='is_approve' value='1'>
                <input type='submit' class='btn btn-success btn-sm' value='อนุมัติ'>
              </form>
              <form action='$routeUpdate' method='POST' class='d-inline form-approve'>
                $csrfToken
                $methodField
                <input type='hidden' name='is_approve' value='2'>
                <input type='submit' class='btn btn-danger btn-sm' value='ไม่อนุมัติ'>
              </form>
              <form action='".url('backend/requisition_between_branch_cancel')."' method='post' enctype='multipart/form-data' class='d-inline'>
              $csrfToken
              <input type='hidden' name='is_cacel' value='3'>
              <input type='hidden' name='data_id' value='".$wait_approve->id."'>
              <input type='submit' class='btn btn-danger btn-sm' value='ยกเลิก'>
            </form>
            ";
          }
        })
        ->rawColumns(['button_products', 'actions'])
        ->make(true);
    }


    public function requisition_between_branch_cancel(Request $request)
    {
      // dd($request->all());
      $data = RequisitionBetweenBranch::select('is_approve','id')->where('id',$request->data_id)->first();
      if(@$data->is_approve!=1){
        $data = RequisitionBetweenBranch::select('is_approve','id')->where('id',$request->data_id)->delete();
      }else{
        return redirect()->back()->with('error','รายการนี้ถูกอนุมัติแล้ว ไม่สามารถยกเลิกได้');
      }

      return redirect()->back()->with('success','ทำรายการสำเร็จ');
    }
}

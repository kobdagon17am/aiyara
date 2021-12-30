<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;
use App\Models\Backend\Branchs;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Backend\RequisitionBetweenBranch;

class RequisitionBetweenBranchController extends Controller
{
    public function index()
    {
        $sBranchs = Branchs::when(auth()->user()->permission !== 1, function ($query) {
            return $query->where('id', auth()->user()->branch_id_fk);
        })->get();

        $products = DB::table('products')
            ->selectRaw('products.id as product_id, products.product_code, CASE WHEN products_details.product_name IS NULL THEN "* ไม่ได้กรอกชื่อสินค้า" ELSE products_details.product_name END as product_name')
            ->leftJoin('products_details', 'products_details.product_id_fk', '=', 'products.id')
            ->where('products_details.lang_id', 1)
            ->get();

        $requisitons = RequisitionBetweenBranch::with('requisition_details')->waitApproveByBranch();
        $approve_requisitons = RequisitionBetweenBranch::with('requisition_details')->isApprove();
      
        return view('backend.requisition.index')->with([
            'sBranchs' => $sBranchs,
            'products' => $products,
            'requisitons' => $requisitons,
            'approve_requisitons' => $approve_requisitons,
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
        $requisition_between_branch->update($request->only('is_approve'));

        $message = $request->is_approve == 1 ? 'อนุมัติรายการแล้ว.' : 'ยกเลิกรายการแล้ว';

        return back()->withSuccess($message);
    }
}

<?php

namespace App\Models\Backend;

use DB;
use Illuminate\Database\Eloquent\Model;

class RequisitionBetweenBranch extends Model
{
    const WAIT_APPROVE = 0;
    const APPROVED = 1;
    const CANCEL = 2;
    const WAIT_TRANSFER = 0;

    protected $fillable = [
        'from_branch_id',
        'to_branch_id',
        'requisitioned_by',
        'approved_by',
        'is_approve',
        'is_transfer',
        'approved_at'
    ];

    protected $dates = ['approved_at'];

    /**
     *  Scopes
     */
    public function scopeWaitApproveByBranch($query)
    {
      $perm = 0;
      $permis = DB::table('role_permit')->where('role_group_id_fk',auth()->user()->role_group_id_fk)->where('menu_id_fk',83)->first();
      if($permis){
        if($permis->view_all_branch==1){
          $perm = 1;
        }
      }
        return $query->when(auth()->user()->permission != 1 && $perm != 1, function ($query) {
                return $query->where(function ($query) {
                    // $query->where('to_branch_id', auth()->user()->branch_id_fk);
                        // ->orWhere('from_branch_id', auth()->user()->branch_id_fk);
                        $query->Where('from_branch_id', auth()->user()->branch_id_fk);
                    });
            })->where('is_approve', static::WAIT_APPROVE);
    }

    public function scopeisApprove($query)
    {
        // auth()->user()->branch_id_fk

        $perm = 0;
        $permis = DB::table('role_permit')->where('role_group_id_fk',auth()->user()->role_group_id_fk)->where('menu_id_fk',83)->first();
        if($permis){
          if($permis->view_all_branch==1){
            $perm = 1;
          }
        }

        return $query->when(auth()->user()->permission != 1 && $perm != 1, function ($query) {
            return $query->where(function ($query) {
                // $query->where('to_branch_id', auth()->user()->branch_id_fk)
                //     ->orWhere('from_branch_id', auth()->user()->branch_id_fk);
                $query->Where('from_branch_id', auth()->user()->branch_id_fk);
                });
            })->where('is_approve', static::APPROVED);
    }

    public function scopeWaitApproveCount($query)
    {
      $perm = 0;
      $permis = DB::table('role_permit')->where('role_group_id_fk',auth()->user()->role_group_id_fk)->where('menu_id_fk',83)->first();
      if($permis){
        if($permis->view_all_branch==1){
          $perm = 1;
        }
      }
        return $query->when(auth()->user()->permission != 1 && $perm != 1, function ($query) {
            // return $query->where('to_branch_id', auth()->user()->branch_id_fk);
            return  $query->Where('from_branch_id', auth()->user()->branch_id_fk);
        })
        ->where('is_approve', static::WAIT_APPROVE)
        ->count();
    }

    /**
     *  Relations
     */
    public function requisition_details()
    {
        return $this->hasMany(RequisitionBetweenBranchDetail::class);
        // ->select('products.product_code')->join('products','products.id','requisition_between_branch_details.product_id')->orderBy('products.product_code','asc');
    }

    public function from_branch()
    {
        return $this->belongsTo(Branchs::class, 'from_branch_id', 'id');
    }

    public function to_branch()
    {
        return $this->belongsTo(Branchs::class, 'to_branch_id', 'id');
    }

    public function requisition_by()
    {
        return $this->belongsTo(\App\Models\Backend\Permission\Admin::class, 'requisitioned_by', 'id');
    }

    public function approve_by()
    {
        return $this->belongsTo(\App\Models\Backend\Permission\Admin::class, 'approved_by', 'id');
    }
}

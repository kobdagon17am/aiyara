<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Model;

class RequisitionBetweenBranch extends Model
{
    const WAIT_APPROVE = 0;
    const APPROVED = 1;
    const CANCEL = 2;

    protected $fillable = [
        'from_branch_id',
        'to_branch_id',
        'requisitioned_by',
        'is_approve',
    ];

    /**
     *  Scopes
     */
    public function scopeWaitApproveByBranch($query)
    {
        // auth()->user()->branch_id_fk
        return $query
            ->where('to_branch_id', auth()->user()->branch_id_fk)
            ->where('is_approve', static::WAIT_APPROVE)
            ->get();
    }

    public function scopeisApprove($query)
    {
        // auth()->user()->branch_id_fk
        return $query->where('from_branch_id', auth()->user()->branch_id_fk)
            ->where('is_approve', static::APPROVED)
            ->get();
    }

    /**
     *  Relations
     */
    public function requisition_details()
    {
        return $this->hasMany(RequisitionBetweenBranchDetail::class);
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
}

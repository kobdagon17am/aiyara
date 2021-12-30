<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Model;

class RequisitionBetweenBranchDetail extends Model
{
    protected $fillable = [
        'requisition_between_branch_id',
        'product_id',
        'product_name',
        'amount',
    ];

    public function requisition()
    {
        return $this->belongsTo(RequisitionBetweenBranch::class);
    }
}

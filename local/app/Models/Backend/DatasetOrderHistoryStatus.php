<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Model;

class DatasetOrderHistoryStatus extends Model
{
    const CREATE_ORDER = 4;
    const APPROVE_ORDER = 5;

    protected $fillable = ['name'];
}

<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Backend\Taxdata;

class TaxController extends Controller
{
    public function index()
    {
        $taxes = Taxdata::where('customer_id_fk', auth('c_user')->id())->get();

        return view('frontend/tax', compact('taxes'));
    }
}

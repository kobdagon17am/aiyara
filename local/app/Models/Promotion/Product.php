<?php

namespace App\Models\Promotion;

use App\Models\InitModel;

class Product extends InitModel
{
    protected $table = 'promotion_product';

    public function region()
    {
        return $this->belongsTo('App\Models\Region', 'region_id', 'rg_id');
    }
    public function staff()
    {
        return $this->belongsTo('App\Models\Staff', 'updated_by', 'id');
    }

    public function product()
    {
        return $this->belongsTo('App\Models\Product', 'product_id', 'pd_id');
    }

	
}

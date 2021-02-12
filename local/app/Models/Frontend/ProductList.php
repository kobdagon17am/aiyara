<?php
namespace App\Models\Frontend;

use Illuminate\Database\Eloquent\Model;
use Laraveldaily\Quickadmin\Observers\UserActionsObserver;
use DB;
use Auth;
class ProductList extends Model
{
	public static function product_list_html($products_id,$type,$img_url,$product_img,$product_name,$title,$icon,$member_price,$pv,$category_id =''){ 

        if($category_id == 9){//coupong 
            $promotion = '<div class="p-new" ><a href="" style="background: #f44336;"> CouPon </a></div>';
        }elseif ($category_id == 8) {//promotion
            $promotion = '<div class="p-new" ><a href="" > Promotion </a></div>';
        }else{
             $promotion ='';
        }

        $html = '<div class="col-xl-3 col-md-3 col-sm-6 col-xs-6" >
        <input type="hidden" id="item_id" value="'.$products_id.'">
        <div class="card prod-view">
        <div class="prod-item text-center">
        <div class="prod-img">
        <div class="option-hover">
        <a href="'.route("product-detail",['type'=>$type,'id'=>$products_id,'category_id'=>$category_id]).'" type="button" 
        class="btn btn-success btn-icon waves-effect waves-light m-r-15 hvr-bounce-in option-icon"> <i class="icofont icofont-cart-alt f-20"></i></a>

        <a href="'.route("product-detail",['type'=>$type,'id'=>$products_id,'category_id'=>$category_id]).'"
        class="btn btn-primary btn-icon waves-effect waves-light m-r-15 hvr-bounce-in option-icon">
        <i class="icofont icofont-eye-alt f-20"></i>
        </a>
        <!-- <button type="button" class="btn btn-danger btn-icon waves-effect waves-light hvr-bounce-in option-icon">
        <i class="icofont icofont-heart-alt f-20"></i>
        </button> -->
        </div>
        <a href="#!" class="hvr-shrink">
        <img src="'.asset($img_url.''.$product_img).'" class="img-fluid o-hidden" alt="">
        </a>'.$promotion.'
        </div>
        <div class="prod-info">
        <a href="'.route('product-detail',['type'=>$type,'id'=>$products_id,'category_id'=>$category_id]).'" class="txt-muted">
        <h5 style="font-size: 15px">'.$product_name.'</h5>
        <p class="text-left p-2 m-b-0" style="font-size: 12px">'.$title.'</p>
        </a>
        <!--<div class="m-b-10">
        <label class="label label-success">3.5 <i class="fa fa-star"></i></label><a class="text-muted f-w-600">14 Ratings &amp;  3 Reviews</a>
        </div> -->
        <span class="prod-price" style="font-size: 20px">'.$icon.' '.number_format($member_price,2).'<b
        style="color:#00c454">['.$pv.' PV]</b></span>
        </div>
        </div>
        </div>
        </div>';

        return $html;

    }

} 

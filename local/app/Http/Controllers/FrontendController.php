<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class FrontendController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    public function __construct()
    {



      /*
      if (!\Cache::has('Promotion')) {
        \Cache::remember('Promotion', 3600,  function () {
          $sPro = [];
          $sRow = \App\Model\Promotion\WebDiscount::where('region_id','3')
            ->where('status','Y')
            ->whereRaw("'".date('Y-m-d')."' BETWEEN date_start AND date_end")
                    ->orderBy('date_start', 'desc')
                    ->first();
                if( $sRow ){
                  $sPro['w'] = ['T'=>$sRow->type,'D'=>$sRow->discount];
                }

          $sRow = \App\Model\Promotion\Category::where('region_id','3')
            ->where('status','Y')
            ->whereRaw("'".date('Y-m-d')."' BETWEEN date_start AND date_end")
                    ->orderBy('date_start', 'asc')
                    ->get();
                if( $sRow->count() ){
                  foreach ($sRow as $r) {
                    $sPro['c'][$r->category_id] = ['T'=>$r->type,'D'=>$r->discount];
                  }
          }

          $sRow = \App\Model\Promotion\Product::where('region_id','3')
            ->where('status','Y')
            ->whereRaw("'".date('Y-m-d')."' BETWEEN date_start AND date_end")
                    ->orderBy('date_start', 'asc')
                    ->get();
                if( $sRow->count() ){
                  foreach ($sRow as $r) {
                    $sPro['p'][$r->product_id] = ['T'=>$r->type,'D'=>$r->discount];
                  }
          }
            return $sPro;
        });
      }
*/
    }
}

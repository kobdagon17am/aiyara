<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;
use PDO;
use App\Models\Frontend\Product;
use App\Http\Controllers\Frontend\Fc\GiveawayController;
use App\Models\DbOrderProductsList;

class FrontstorelistController extends Controller
{

    public function index(Request $request)
    {
    }

    public function create()
    {
    }

    public function plusPromotion(Request $request)
    {
      // return($request->all());
      // dd();
      if(isset($request->promotion_id_fk_pro)){

        $this->fnManageGiveaway(@$request->frontstore_id);

        for ($i=0; $i < count($request->promotion_id_fk_pro) ; $i++) {

// @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@

           $sFrontstore = \App\Models\Backend\Frontstore::find(@$request->frontstore_id);

           $sRow = \App\Models\Backend\Frontstorelist::where('frontstore_id_fk', @$request->frontstore_id)->where('promotion_id_fk', @$request->promotion_id_fk_pro[$i])->get();

         if(@$sRow[0]->promotion_id_fk == @$request->promotion_id_fk_pro[$i]){

                 $Promotions_cost = \App\Models\Backend\Promotions_cost::where('promotion_id_fk',@$request->promotion_id_fk_pro[$i])->get();

                  if(request('purchase_type_id_fk')==5){ //  Ai Voucher
                    $pv = 0;
                  }else{
                    $pv = @$Promotions_cost[0]->pv  ;
                  }

                  \App\Models\Backend\Frontstorelist::where('frontstore_id_fk', @$request->frontstore_id)->where('promotion_id_fk', @$request->promotion_id_fk_pro[$i])->update(
                        [
                          'amt' => @$request->quantity[$i] ,
                          'selling_price' => @$Promotions_cost[0]->member_price ,
                          'pv' => $pv ,
                          'total_pv' => $pv * @$request->quantity[$i] ,
                          'total_price' => @$Promotions_cost[0]->member_price * @$request->quantity[$i] ,
                          'type_product' => 'promotion' ,
                        ]
                    );


          }else{


            if(@$request->promotion_id_fk_pro[$i]!=0 && @$request->quantity[$i]!=0){

                $Promotions_cost = \App\Models\Backend\Promotions_cost::where('promotion_id_fk',@$request->promotion_id_fk_pro[$i])->get();


                  if(request('purchase_type_id_fk')==5){ //  Ai Voucher
                    $pv = 0;
                  }else{
                    $pv = @$Promotions_cost[0]->pv  ;
                  }

                  $promotion_data = DB::table('promotions')->select('id')->where('id',@$request->promotion_id_fk_pro[$i])->first();
                  if($promotion_data){
                    $sRow = new \App\Models\Backend\Frontstorelist;
                    $sRow->frontstore_id_fk    = @$request->frontstore_id ;

                    $sRow->customers_id_fk  = (@request('customers_id_fk'))?@request('customers_id_fk'): 0 ;
                    $sRow->action_user = (@request('action_user'))?@request('action_user'): 0 ;
                    $sRow->code_order  = (@request('code_order'))?@request('code_order'): 0 ;

                    $sRow->amt    =  @$request->quantity[$i];
                    $sRow->add_from    = '2';
                    $sRow->promotion_id_fk    = @$request->promotion_id_fk_pro[$i];

                    $sRow->selling_price    = @$Promotions_cost[0]->member_price;
                    $sRow->pv    = $pv ;
                    $sRow->total_pv    =  $pv * @$request->quantity[$i];
                    $sRow->total_price    =  @$Promotions_cost[0]->member_price * @$request->quantity[$i];
                    $sRow->type_product    =  'promotion' ;

                    $sRow->action_date    =  date('Y-m-d H:i:s');
                    $sRow->created_at = date('Y-m-d H:i:s');
                    $sRow->save();
                  }

              }

          }


             $id= @$request->frontstore_id;

             $sFrontstoreDataTotal = DB::select(" select SUM(total_price) as total,SUM(total_pv) as total_pv from db_order_products_list WHERE frontstore_id_fk in ($id) GROUP BY frontstore_id_fk ");
             // dd($sFrontstoreDataTotal);
             if($sFrontstoreDataTotal){
                    $vat = floatval(@$sFrontstoreDataTotal[0]->total) - (floatval(@$sFrontstoreDataTotal[0]->total)/1.07) ;
                    $vat = $vat > 0  ? $vat : 0 ;
                    $product_value = str_replace(",","",floatval(@$sFrontstoreDataTotal[0]->total) - $vat) ;
                    $total = @$sFrontstoreDataTotal[0]->total>0?@$sFrontstoreDataTotal[0]->total:0;
                    $total_pv = @$sFrontstoreDataTotal[0]->total_pv>0?@$sFrontstoreDataTotal[0]->total_pv:0;
                    DB::select(" UPDATE db_orders SET product_value=".($product_value).",tax=".($vat).",sum_price=".($total).",pv_total=".($total_pv)." WHERE id=$id ");
                    DB::select(" UPDATE db_orders SET pv_total=0 WHERE pv_total is null; ");
              }else{
                DB::select(" UPDATE db_orders SET product_value=0,tax=0,sum_price=0 WHERE id=$id  ");
              }


// @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@

        }

      }

    }



    public function minusPromotion(Request $request)
    {
      // return($request->all());
      // dd();
      if(isset($request->promotion_id_fk_pro)){

        $this->fnManageGiveaway(@$request->frontstore_id);

        for ($i=0; $i < count($request->promotion_id_fk_pro) ; $i++) {

// @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
           $sFrontstore = \App\Models\Backend\Frontstore::find(request('frontstore_id'));

           $sRow = \App\Models\Backend\Frontstorelist::where('frontstore_id_fk', @$request->frontstore_id)->where('promotion_id_fk', @$request->promotion_id_fk_pro[$i])->get();

         if(@$sRow[0]->promotion_id_fk == @$request->promotion_id_fk_pro[$i]){

                 $Promotions_cost = \App\Models\Backend\Promotions_cost::where('promotion_id_fk',@$request->promotion_id_fk_pro[$i])->get();
                  if(request('purchase_type_id_fk')==5){ //  Ai Voucher
                    $pv = 0;
                  }else{
                    $pv = @$Promotions_cost[0]->pv  ;
                  }

                  \App\Models\Backend\Frontstorelist::where('frontstore_id_fk', @$request->frontstore_id)->where('promotion_id_fk', @$request->promotion_id_fk_pro[$i])->update(
                        [
                          'amt' => @$request->quantity[$i] ,
                          'selling_price' => @$Promotions_cost[0]->member_price ,
                          'pv' => $pv ,
                          'total_pv' => $pv * @$request->quantity[$i] ,
                          'total_price' => @$Promotions_cost[0]->member_price * @$request->quantity[$i] ,
                        ]
                    );


          }

           $id=   @$request->frontstore_id;

           $sFrontstoreDataTotal = DB::select(" select SUM(total_price) as total,SUM(total_pv) as total_pv from db_order_products_list WHERE frontstore_id_fk in ($id)  GROUP BY frontstore_id_fk ");
           // dd($sFrontstoreDataTotal);
           if($sFrontstoreDataTotal){
              $vat = floatval(@$sFrontstoreDataTotal[0]->total) - (floatval(@$sFrontstoreDataTotal[0]->total)/1.07) ;
              $vat = $vat > 0  ? $vat : 0 ;
              $product_value = str_replace(",","",floatval(@$sFrontstoreDataTotal[0]->total) - $vat) ;
              $total = @$sFrontstoreDataTotal[0]->total>0?@$sFrontstoreDataTotal[0]->total:0;
              $total_pv = @$sFrontstoreDataTotal[0]->total_pv>0?@$sFrontstoreDataTotal[0]->total_pv:0;
              DB::select(" UPDATE db_orders SET product_value=".($product_value).",tax=".($vat).",sum_price=".($total).",pv_total=".($total_pv)." WHERE id=$id ");
            }else{
              DB::select(" UPDATE db_orders SET product_value=0,tax=0,sum_price=0 WHERE id=$id  ");
            }


            DB::delete(" DELETE FROM db_order_products_list WHERE amt=0 ;");


// @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@

        }

      }

    }

    public function fnManageGiveaway($frontstore_id)
    {

 // แถม
      if(!empty($frontstore_id)){

            $sFrontstore = \App\Models\Backend\Frontstore::find($frontstore_id);
            // return  $sFrontstore->business_location_id_fk;
            // return  $sFrontstore->pv_total;
            // return  $sFrontstore->customers_id_fk;
            // return  $sFrontstore->purchase_type_id_fk;
            $sCustomer = \App\Models\Backend\Customers::find($sFrontstore->customers_id_fk);
            // return $sCustomer->user_name;
            // ลยก่อน ถ้าเข้าเงื่อนไขค่อยเพิ่มเข้าไปใหม่
            DB::table('db_order_products_list_giveaway')->where('order_id_fk', '=', $sFrontstore->id)->delete();
            DB::table('db_order_products_list')
                  ->where('frontstore_id_fk', $sFrontstore->id)
                  ->where('code_order', $sFrontstore->code_order)
                  ->where('type_product', 'giveaway')
                  ->where('add_from', '4')
                  ->delete();

            if (!empty($sFrontstore->business_location_id_fk) and !empty($sFrontstore->pv_total)) {
                $check_giveaway = GiveawayController::check_giveaway($sFrontstore->purchase_type_id_fk, $sCustomer->user_name, $sFrontstore->pv_total);
                // return $check_giveaway;

                if(@$check_giveaway){

                foreach ($check_giveaway as $value) {
                  $insert_order_products_list_type_giveaway = new DbOrderProductsList();
                    if (@$value['status'] == 'success') {
                        if ($value['rs']) {

                             $_ch =DB::table('db_order_products_list')
                                ->where('frontstore_id_fk', $sFrontstore->id)
                                ->where('code_order', $sFrontstore->code_order)
                                ->where('customers_id_fk', $sFrontstore->customers_id_fk)
                                ->where('distribution_channel_id_fk', '2')
                                ->where('giveaway_id_fk', $value['rs']['giveaway_id'])
                                ->where('amt', $value['rs']['count_free'])
                                ->where('type_product', 'giveaway')
                                ->where('add_from', '4')
                                ->get();
                                if($_ch->count() == 0){

                                      $insert_order_products_list_type_giveaway->frontstore_id_fk = $sFrontstore->id;
                                      $insert_order_products_list_type_giveaway->code_order = $sFrontstore->code_order;
                                      $insert_order_products_list_type_giveaway->customers_id_fk = $sFrontstore->customers_id_fk;
                                      $insert_order_products_list_type_giveaway->distribution_channel_id_fk = 2;
                                      $insert_order_products_list_type_giveaway->giveaway_id_fk = $value['rs']['giveaway_id'];
                                      $insert_order_products_list_type_giveaway->product_name = $value['rs']['name'];
                                      $insert_order_products_list_type_giveaway->amt = $value['rs']['count_free'];
                                      $insert_order_products_list_type_giveaway->type_product = 'giveaway';
                                      $insert_order_products_list_type_giveaway->add_from = 4;
                                      $insert_order_products_list_type_giveaway->save();
                                }

                                if ($value['rs']['type'] == 1) { //product แถมเป็นสินค้า

                                    $product = DB::table('db_giveaway_products')
                                        ->select('products_details.product_id_fk', 'products_details.product_name', 'dataset_product_unit.product_unit'
                                            , 'dataset_product_unit.group_id as unit_id', 'db_giveaway_products.product_amt')
                                        ->leftJoin('products_details', 'products_details.product_id_fk', '=', 'db_giveaway_products.product_id_fk')
                                        ->leftJoin('dataset_product_unit', 'dataset_product_unit.group_id', '=', 'db_giveaway_products.product_unit')
                                        ->where('db_giveaway_products.giveaway_id_fk', '=', $value['rs']['giveaway_id'])
                                        ->where('products_details.lang_id', '=', $sFrontstore->business_location_id_fk)
                                        ->where('dataset_product_unit.lang_id', '=', $sFrontstore->business_location_id_fk)
                                        ->get();

                                    foreach ($value['rs']['product'] as $giveaway_product) {

                                        DB::table('db_order_products_list_giveaway')->insertOrignore([
                                            'order_id_fk' => $sFrontstore->id,
                                            'product_list_id_fk' => $insert_order_products_list_type_giveaway->id,
                                            'giveaway_id_fk' => $value['rs']['giveaway_id'],
                                            'code_order' => $sFrontstore->code_order,
                                            'product_id_fk' => $giveaway_product->product_id_fk,
                                            'product_name' => $giveaway_product->product_name,
                                            'product_unit_id_fk' => $giveaway_product->unit_id,
                                            'product_amt' => $giveaway_product->product_amt,
                                            'product_unit_name' => $giveaway_product->product_unit,
                                            'free' => $value['rs']['count_free'],
                                            'type_product' => 'giveaway_product',
                                        ]);
                                    }

                                } else { //gv แถมเป้นกิฟวอยเชอ

                                    DB::table('db_order_products_list_giveaway')->insert([
                                        'order_id_fk' => $sFrontstore->id,
                                        'product_list_id_fk' => $insert_order_products_list_type_giveaway->id,
                                        'product_name' => 'GiftVoucher',
                                        'code_order' => $sFrontstore->code_order,
                                        'giveaway_id_fk' => $value['rs']['giveaway_id'],
                                        'product_amt' => 1,
                                        'gv_free' => $value['rs']['gv'],
                                        'free' => $value['rs']['count_free'],
                                        'type_product' => 'giveaway_gv',

                                    ]);
                                }

                        }
                    }
                    }

                }
            }

         }

    }


    public function plus(Request $request)
    {
      // return($request->all());
      // return($request->all()); product_unit_id_fk
      // return(count($request->product_id_fk));
      // return($request->quantity[0]);
      // dd();
      // dd($request->all());

      // $this->fnManageGiveaway(@$request->frontstore_id);
      // $sBranchs = DB::select(" select * from branchs where id=" . $request->branch_id_fk . " ");
      //    for ($i=0; $i < count($request->product_id_fk) ; $i++) {
      //       // $Check_stock = \App\Models\Backend\Check_stock::find($request->id[$i]);
      //       // echo $Check_stock->product_id_fk;
      //   // dd($text);
      //   \DB::beginTransaction();
      //   try {
      //         $sProducts[] = DB::select("
      //           SELECT
      //           products.id,
      //           (
      //             SELECT product_unit_id_fk
      //             FROM
      //             products_units
      //             WHERE product_id_fk = products.id LIMIT 1
      //           ) as product_unit,

      //           products.category_id ,categories.category_name,
      //           (SELECT concat(img_url,product_img) FROM products_images WHERE products_images.product_id_fk=products.id) as p_img,
      //           (
      //           SELECT concat( products.product_code,' : '  ,
      //           products_details.product_name)
      //           FROM
      //           products_details
      //           WHERE products_details.lang_id=1 AND products_details.product_id_fk=products.id
      //           ) as pn,
      //           products_cost.member_price,
      //           products_cost.pv
      //           FROM
      //           products
      //           LEFT JOIN categories on products.category_id=categories.id
      //           LEFT JOIN products_cost on products.id = products_cost.product_id_fk
      //           WHERE products.id = ".$request->product_id_fk[$i]." AND products_cost.business_location_id = ".$sBranchs[0]->business_location_id_fk."
      //       ");

      //     } catch (\Exception $e) {
      //       echo $e->getMessage();
      //       dd($sProducts);
      //       dd($request->product_id_fk[$i]);
      //       \DB::rollback();
      //     }

      //           }


      if(isset($request->product_plus)){

        $this->fnManageGiveaway(@$request->frontstore_id);
        $sBranchs = DB::select(" select * from branchs where id=" . $request->branch_id_fk . " ");
        // if(!isset($sBranchs[0]->business_location_id_fk)){
        //   $sBranchs[0]->business_location_id_fk =
        // }
        for ($i=0; $i < count($request->product_id_fk) ; $i++) {
            // $Check_stock = \App\Models\Backend\Check_stock::find($request->id[$i]);
            // echo $Check_stock->product_id_fk;
        // dd($text);
              $sProducts = DB::select("
                SELECT
                products.id,
                (
                  SELECT product_unit_id_fk
                  FROM
                  products_units
                  WHERE product_id_fk = products.id LIMIT 1
                ) as product_unit,
                products.category_id ,categories.category_name,
                (SELECT concat(img_url,product_img) FROM products_images WHERE products_images.product_id_fk=products.id AND products_images.image_default=1 LIMIT 1) as p_img,
                (
                SELECT concat( products.product_code,' : '  ,
                products_details.product_name)
                FROM
                products_details
                WHERE products_details.lang_id=1 AND products_details.product_id_fk=products.id
                ) as pn,
                products_cost.member_price,
                products_cost.pv
                FROM
                products
                LEFT JOIN categories on products.category_id=categories.id
                LEFT JOIN products_cost on products.id = products_cost.product_id_fk
                WHERE products.id = ".$request->product_id_fk[$i]." AND products_cost.business_location_id = ".$sBranchs[0]->business_location_id_fk."
            ");

              // echo ($sProducts[0]->product_unit);

              // return $sProducts;
              // dd();

           $sFrontstore = \App\Models\Backend\Frontstore::find(request('frontstore_id'));

           $sRow = \App\Models\Backend\Frontstorelist::where('frontstore_id_fk', @$request->frontstore_id)->where('product_id_fk', @$request->product_id_fk[$i])->get();
           // echo count($sRow);
           // return @$request->frontstore_id;
          if( count($sRow)>0 ){

              if(@$request->product_id_fk[$i] == request('product_id_fk_this')){

                // echo @$request->quantity[$i];
                if(request('purchase_type_id_fk')==5){ //  Ai Voucher
                  $pv = 0;
                }else{
                  $pv = @$sProducts[0]->pv * @$request->quantity[$i] ;
                }

                  \App\Models\Backend\Frontstorelist::where('frontstore_id_fk', @$request->frontstore_id)->where('product_id_fk', @$request->product_id_fk[$i])->update(
                        [
                          // 'frontstore_id_fk' => request('frontstore_id') ,
                          'amt' => @$request->quantity[$i] ,
                          'total_pv' => $pv ,
                          'total_price' => @$sProducts[0]->member_price * @$request->quantity[$i] ,
                          // 'purchase_type_id_fk' => @$sFrontstore->purchase_type_id_fk,
                          'product_unit_id_fk' => @$sProducts[0]->product_unit,
                          'type_product' => 'product' ,
                        ]
                    );

              }

          }else{
                if(request('purchase_type_id_fk')==5){
                  $pv = 0;
                }else{
                  $pv = @$sProducts[0]->pv ;
                }

                $sRow = new \App\Models\Backend\Frontstorelist;
                $sRow->frontstore_id_fk    = request('frontstore_id') ;

                $sRow->frontstore_id_fk  = request('frontstore_id') ;
                $sRow->customers_id_fk  = (@request('customers_id_fk'))?@request('customers_id_fk'): 0 ;
                $sRow->action_user = (@request('action_user'))?@request('action_user'): 0 ;
                $sRow->code_order  = (@request('code_order'))?@request('code_order'): 0 ;

                $sRow->product_id_fk    = @$request->product_id_fk[$i];
                $sRow->purchase_type_id_fk    = @$sFrontstore->purchase_type_id_fk;
                $sRow->selling_price    = @$sProducts[0]->member_price;
                $sRow->pv    = $pv ;
                $sRow->amt    =  @$request->quantity[$i];
                $sRow->product_unit_id_fk    =  @$sProducts[0]->product_unit ;
                $sRow->total_pv    =  $pv * @$request->quantity[$i];
                $sRow->total_price    =  @$sProducts[0]->member_price * @$request->quantity[$i];
                $sRow->type_product    =  'product' ;
                $sRow->created_at = date('Y-m-d H:i:s');
                if(!empty(request('quantity')[$i])){
                  if(@$request->product_id_fk[$i] == request('product_id_fk_this')){
                      $sRow->save();
                  }
                }

          }


           $ProductsName = DB::select("SELECT products.id as product_id,
            products.product_code,
            (CASE WHEN products_details.product_name is null THEN '* ไม่ได้กรอกชื่อสินค้า' ELSE products_details.product_name END) as product_name
            FROM
            products_details
            Left Join products ON products_details.product_id_fk = products.id
            WHERE products.id=".@$request->product_id_fk[$i]." AND lang_id=1");

           foreach($ProductsName AS $r){
              DB::select(" UPDATE db_order_products_list SET product_name='".@$r->product_code." : ".@$r->product_name."' WHERE product_id_fk=".@$r->product_id."  ");
           }


          }

       $id=   @$request->frontstore_id;

       $sFrontstoreDataTotal = DB::select(" select SUM(total_price) as total,SUM(total_pv) as total_pv from db_order_products_list WHERE frontstore_id_fk in ($id) GROUP BY frontstore_id_fk ");
       // dd($sFrontstoreDataTotal);
       if($sFrontstoreDataTotal){
              $vat = floatval(@$sFrontstoreDataTotal[0]->total) - (floatval(@$sFrontstoreDataTotal[0]->total)/1.07) ;
              $vat = $vat > 0  ? $vat : 0 ;
              $product_value = str_replace(",","",floatval(@$sFrontstoreDataTotal[0]->total) - $vat) ;
              $total = @$sFrontstoreDataTotal[0]->total>0?@$sFrontstoreDataTotal[0]->total:0;
              $total_pv = @$sFrontstoreDataTotal[0]->total_pv>0?@$sFrontstoreDataTotal[0]->total_pv:0;
              DB::select(" UPDATE db_orders SET product_value=".($product_value).",tax=".($vat).",sum_price=".($total).",pv_total=".($total_pv)." WHERE id=$id ");
              DB::select(" UPDATE db_orders SET pv_total=0 WHERE pv_total is null; ");
        }else{
          DB::select(" UPDATE db_orders SET product_value=0,tax=0,sum_price=0 WHERE id=$id  ");
        }



          // $total_price = DB::select(" select SUM(total_price) as total from db_order_products_list WHERE frontstore_id_fk=".@$request->frontstore_id." GROUP BY frontstore_id_fk ");
          // DB::select(" UPDATE db_orders SET sum_price=".(@$total_price[0]->total?@$total_price[0]->total:0)." WHERE id=".@$request->frontstore_id." ");


          if(isset($request->product_plus_addlist)){
              // return redirect()->to(url("backend/frontstore/".request('frontstore_id')."/edit"));
             if($request->quantity[0]==0){
                DB::delete(" DELETE FROM db_order_products_list WHERE amt=0 ;");
             }
          }


                 DB::select(" UPDATE db_orders SET

                  pay_type_id_fk='0',

                  aicash_price='0',
                  member_id_aicash='0',
                  transfer_price='0',
                  credit_price='0',

                  charger_type='0',
                  fee='0',
                  fee_amt='0',
                  sum_credit_price='0',
                  account_bank_id='0',

                  transfer_money_datetime=NULL ,
                  file_slip=NULL,

                  total_price='0',
                  cash_price='0',
                  cash_pay='0'

                  WHERE id=$request->frontstore_id ");


      }

    }


    public function store(Request $request)
    {
        // dd($request->all());

        if(isset($request->add_course)){

            $sFrontstore = \App\Models\Backend\Frontstore::find(request('frontstore_id'));
            $id = request('id');

            for ($i=0; $i < count($id) ; $i++) {

                $sRow = new \App\Models\Backend\Frontstorelist;
                $sRow->frontstore_id_fk    = request('frontstore_id') ;
                $sRow->customers_id_fk    = $sFrontstore->customers_id_fk ;
                $sRow->purchase_type_id_fk    = 6 ;
                $sRow->distribution_channel_id_fk = '2';
                $sRow->action_user = \Auth::user()->id;
                $sRow->action_date = date('Y-m-d H:i:s');
                $sRow->created_at = date('Y-m-d H:i:s');
                $sRow->type_product = 'course' ;


                if(!empty(request('amt_apply')[$i])){

                    $Course_event = \App\Models\Backend\Course_event::find($id[$i]);
                    $sRow->course_id_fk    = $id[$i];
                    $sRow->product_name    = $Course_event->ce_name;
                    $sRow->selling_price    = $Course_event->ce_ticket_price;
                    $sRow->pv    = $Course_event->pv;
                    $sRow->total_pv    = $Course_event->pv * request('amt_apply')[$i] ;
                    $sRow->total_price    =  $Course_event->ce_ticket_price * request('amt_apply')[$i];
                    $sRow->amt    = request('amt_apply')[$i];
                    $sRow->save();

                    $sFrontstore->check_press_save = '2';
                    $sFrontstore->save();
                }

              }


             $id=   request('frontstore_id');

             $sFrontstoreDataTotal = DB::select(" select SUM(total_price) as total,SUM(total_pv) as total_pv from db_order_products_list WHERE frontstore_id_fk in ($id)  GROUP BY frontstore_id_fk ");
             // dd($sFrontstoreDataTotal);
             if($sFrontstoreDataTotal){
                    $vat = floatval(@$sFrontstoreDataTotal[0]->total) - (floatval(@$sFrontstoreDataTotal[0]->total)/1.07) ;
                    $vat = $vat > 0  ? $vat : 0 ;
                    $product_value = str_replace(",","",floatval(@$sFrontstoreDataTotal[0]->total) - $vat) ;
                    $total = @$sFrontstoreDataTotal[0]->total>0?@$sFrontstoreDataTotal[0]->total:0;
                    $total_pv = @$sFrontstoreDataTotal[0]->total_pv>0?@$sFrontstoreDataTotal[0]->total_pv:0;
                    DB::select(" UPDATE db_orders SET product_value=".($product_value).",tax=".($vat).",sum_price=".($total).",pv_total=".($total_pv)." WHERE id=$id ");
                    DB::select(" UPDATE db_orders SET pv_total=0 WHERE pv_total is null; ");
              }else{
                    DB::select(" UPDATE db_orders SET product_value=0,tax=0,sum_price=0 WHERE id=$id  ");
              }


            return redirect()->to(url("backend/frontstore/".request('frontstore_id')."/edit"));

      }

       if(isset($request->add_delivery_custom)){

            DB::insert(" INSERT INTO customers_addr_frontstore (frontstore_id_fk, customer_id, recipient_name, addr_no, province_id_fk , amphur_code, tambon_code, zip_code, tel,tel_home, created_at)
              VALUES
              ('".@$request->frontstore_id."',
               '".@$request->customers_id_fk."',
               '".@$request->delivery_cusname."',
                '".@$request->delivery_addr."',
                 '".@$request->delivery_province."',
                 '".@$request->delivery_amphur."',
                 '".@$request->delivery_tambon."',
                 '".@$request->delivery_zipcode."',
                 '".@$request->delivery_tel."',
                 '".@$request->delivery_tel_home."',
                 now()
              )
            ");

              $sRow = \App\Models\Backend\Frontstore::find($request->frontstore_id);
              $sRow->delivery_location    = '3';
              $sRow->delivery_province_id    = @$request->delivery_province;
              $sRow->action_date = date('Y-m-d H:i:s');
              $sRow->updated_at = date('Y-m-d H:i:s');
              $sRow->save();


                        $branchs = DB::select("SELECT * FROM branchs WHERE id=".(@\Auth::user()->branch_id_fk)." ");

                        if(@$request->delivery_province==$branchs[0]->province_id_fk){

                            DB::select(" UPDATE db_orders SET shipping_price=0 WHERE id=".$request->frontstore_id." ");

                        }else{
                             // ต่าง จ. กัน เช็คดูว่า อยู่ในเขตปริมณทฑลหรือไม่
                            $shipping_cost = DB::select("SELECT * FROM dataset_shipping_cost where business_location_id_fk =".$branchs[0]->business_location_id_fk." AND shipping_type_id=2  ");

                            $shipping_vicinity = DB::select("SELECT * FROM dataset_shipping_vicinity where business_location_id_fk =".$branchs[0]->business_location_id_fk." AND shipping_type_id =".$shipping_cost[0]->shipping_type_id." AND province_id_fk=".@$request->delivery_province." ");

                            if(count($shipping_vicinity)>0){

                                DB::select(" UPDATE db_orders SET shipping_price=".$shipping_cost[0]->shipping_cost." WHERE id=".$request->frontstore_id." ");

                            }else{

                                $shipping_cost = DB::select("SELECT * FROM dataset_shipping_cost where business_location_id_fk =".$branchs[0]->business_location_id_fk." AND shipping_type_id=3 ");

                                DB::select(" UPDATE db_orders SET shipping_price=".$shipping_cost[0]->shipping_cost."  WHERE id=".$request->frontstore_id." ");


                            }

                        }


        }


       if(isset($request->update_delivery_custom)){

            DB::select(" UPDATE db_orders SET

            pay_type_id_fk='0',

            aicash_price='0',
            member_id_aicash='0',
            transfer_price='0',
            credit_price='0',
            shipping_price='0',

            charger_type='0',
            fee='0',
            fee_amt='0',
            sum_credit_price='0',

            total_price='0',
            cash_price='0',
            cash_pay='0',
            created_at=NOW()

            WHERE id=".$request->frontstore_id." ");

        // dd($request->all());

       	    $ch = DB::select("select * from customers_addr_frontstore where frontstore_id_fk=".$request->customers_addr_frontstore_id." ");
       	    // dd(count($ch));

       	    if(count($ch)==0){

       	    	DB::insert(" INSERT INTO customers_addr_frontstore (frontstore_id_fk, customer_id, recipient_name, addr_no, province_id_fk , amphur_code, tambon_code, zip_code, tel,tel_home, created_at)
		              VALUES
		              ('".@$request->customers_addr_frontstore_id."',
		               '".@$request->customers_id_fk."',
		               '".@$request->delivery_cusname."',
		                '".@$request->delivery_addr."',
		                 '".@$request->delivery_province."',
		                 '".@$request->delivery_amphur."',
		                 '".@$request->delivery_tambon."',
		                 '".@$request->delivery_zipcode."',
		                 '".@$request->delivery_tel."',
                     '".@$request->delivery_tel_home."',
		                 now()
		              )
		            ");

       	    }else{

              // dd($request->all());

       	    	 DB::select(" UPDATE customers_addr_frontstore
	              SET recipient_name = '".@$request->delivery_cusname."',
	              addr_no = '".@$request->delivery_addr."',
	              province_id_fk  = '".@$request->delivery_province."',
	              amphur_code = '".@$request->delivery_amphur."',
	              tambon_code = '".@$request->delivery_tambon."',
	              zip_code = '".@$request->delivery_zipcode."',
	              tel = '".@$request->delivery_tel."',
                tel_home = '".@$request->delivery_tel_home."',
	              updated_at = now() where frontstore_id_fk=".@$request->customers_addr_frontstore_id."
	            ");

            }



            // ค่าขนส่ง

             $frontstore = DB::select("SELECT * FROM db_orders WHERE id=".$request->frontstore_id." ");
             $branchs = DB::select("SELECT * FROM branchs WHERE id=".(@$frontstore[0]->branch_id_fk?@$frontstore[0]->branch_id_fk:1)." ");
             // dd($request->all());
             // dd($frontstore[0]->branch_id_fk);
             // dd($branchs[0]->province_id_fk);
             // dd($request->delivery_province);
                // if($request->delivery_province==$branchs[0]->province_id_fk){
                //     DB::select("UPDATE db_orders SET shipping_price=0 WHERE id=".$request->frontstore_id." ");
                // }else{
                     // ต่าง จ. กัน เช็คดูว่า อยู่ในเขรปริมณทฑลหรือไม่
                    $shipping_cost = DB::select("SELECT * FROM dataset_shipping_cost where business_location_id_fk =".$branchs[0]->business_location_id_fk." AND shipping_type_id=2  ");

                    // dd($shipping_cost);
                    // dd($shipping_cost[0]->id);
                    // dd($request->delivery_province);

                    $shipping_vicinity = DB::select("SELECT * FROM dataset_shipping_vicinity where shipping_type_id =".$shipping_cost[0]->id." AND province_id_fk=".(@$request->delivery_province?@$request->delivery_province:0)." ");

                    // dd($shipping_vicinity);

                    if(!empty($shipping_vicinity)){

                        DB::select("UPDATE db_orders SET shipping_price=".$shipping_cost[0]->shipping_cost." WHERE id=".$request->frontstore_id." ");
                        // return $shipping_cost[0]->shipping_cost;
                    }else{

                        $shipping_cost = DB::select("SELECT * FROM dataset_shipping_cost where business_location_id_fk =".$branchs[0]->business_location_id_fk." AND shipping_type_id=1 AND shipping_cost<>0 ");

                        // dd($shipping_cost);
                        if(!empty($shipping_cost)){
                           DB::select("UPDATE db_orders SET shipping_price=".$shipping_cost[0]->shipping_cost." WHERE id=".$request->frontstore_id." ");
                         }
                        // return $shipping_cost[0]->shipping_cost;

                    }

                // }

// dd($request->all());

              $sRow = \App\Models\Backend\Frontstore::find($request->frontstore_id);
              $sRow->delivery_location    = '3';
              $sRow->action_date = date('Y-m-d H:i:s');
              $sRow->updated_at = date('Y-m-d H:i:s');
              $sRow->save();

                DB::select(" DELETE FROM customers_addr_sent WHERE receipt_no='".@$request->invoice_code."' ");

                $addr = DB::select("select customers_addr_frontstore.* ,dataset_provinces.name_th as provname,
                      dataset_amphures.name_th as ampname,dataset_districts.name_th as tamname
                      from customers_addr_frontstore
                      Left Join dataset_provinces ON customers_addr_frontstore.province_id_fk = dataset_provinces.id
                      Left Join dataset_amphures ON customers_addr_frontstore.amphur_code = dataset_amphures.id
                      Left Join dataset_districts ON customers_addr_frontstore.tambon_code = dataset_districts.id
                      where customers_addr_frontstore.frontstore_id_fk = ".@$request->frontstore_id." ");

                DB::select(" INSERT IGNORE INTO customers_addr_sent (customer_id, first_name, house_no, zipcode,amphures_id_fk, district_sub, province_id_fk, from_table, from_table_id, receipt_no) VALUES ('".@$request->customers_id_fk."', '".@$addr[0]->recipient_name."','".@$addr[0]->addr_no."','".@$addr[0]->zip_code."', '".@$addr[0]->ampname."', '".@$addr[0]->tamname."', '".@$addr[0]->provname."', 'customers_addr_frontstore', '".@$addr[0]->id."','".@$request->invoice_code."') ");


                        $branchs = DB::select("SELECT * FROM branchs WHERE id=".(@\Auth::user()->branch_id_fk)." ");

                        // if($request->delivery_province==$branchs[0]->province_id_fk){

                        //     DB::select(" UPDATE db_orders SET shipping_price=0 WHERE id=".$request->frontstore_id." ");

                        // }else{
                             // ต่าง จ. กัน เช็คดูว่า อยู่ในเขตปริมณทฑลหรือไม่
                            $shipping_cost = DB::select("SELECT * FROM dataset_shipping_cost where business_location_id_fk =".$branchs[0]->business_location_id_fk." AND shipping_type_id=2  ");

                            $shipping_vicinity = DB::select("SELECT * FROM dataset_shipping_vicinity where business_location_id_fk =".$branchs[0]->business_location_id_fk." AND shipping_type_id =".$shipping_cost[0]->shipping_type_id." AND province_id_fk=".@$request->delivery_province." ");

                            if(count($shipping_vicinity)>0){

                                DB::select(" UPDATE db_orders SET shipping_price=".$shipping_cost[0]->shipping_cost." WHERE id=".$request->frontstore_id." ");

                            }else{

                                $shipping_cost = DB::select("SELECT * FROM dataset_shipping_cost where business_location_id_fk =".$branchs[0]->business_location_id_fk." AND shipping_type_id=3 ");

                                DB::select(" UPDATE db_orders SET shipping_price=".$shipping_cost[0]->shipping_cost."  WHERE id=".$request->frontstore_id." ");


                            }

                        // }


        }

        if(isset($request->product_plus_pro)){

          // dd($request->product_plus_pro);
                $Promotions_cost = \App\Models\Backend\Promotions_cost::where('promotion_id_fk',@$request->promotion_id_fk)->get();
                if(request('purchase_type_id_fk')==5){ //  Ai Voucher
                  $pv = 0;
                }else{
                  $pv = @$Promotions_cost[0]->pv ;
                }
        //     return @$Promotions_cost[0]->pv;
                // dd($pv);
                $sPromotions = \App\Models\Backend\Promotions::find(@$request->promotion_id_fk);
                // dd($sPromotions);
                if(@$request->quantity>$sPromotions->limited_amt_person){
                  $amt = @$sPromotions->limited_amt_person;
                }else{
                  $amt = @$request->quantity;
                }

                if($sPromotions->limited_amt_person == ''){
                  $amt = @$request->quantity;
                }

                // dd($sPromotions->limited_amt_person);
                // วุฒิเพิ่มมาว่าโปรซ้ำไหม
                // $check_promotion_same = \App\Models\Backend\Frontstorelist::where('frontstore_id_fk',@$request->frontstore_id)->where('promotion_id_fk',@$request->promotion_id_fk)->first();
                // วุฒิเช็คว่าเคยมีโปคูปองไหม
                $check_promotion_same = \App\Models\Backend\Frontstorelist::select('id')->where('frontstore_id_fk',@$request->frontstore_id)->where('promotion_code','!=','')->first();
                if(!$check_promotion_same){

                  $sRow = new \App\Models\Backend\Frontstorelist;
                  $sRow->frontstore_id_fk    = @$request->frontstore_id ;
                  $sRow->amt    = @$amt;
                  $sRow->add_from    = '2';
                  $sRow->promotion_id_fk    = @$request->promotion_id_fk;
                  $sRow->promotion_code    = @$request->txtSearchPro;

                  $sRow->selling_price    = @$Promotions_cost[0]->member_price;
                  $sRow->pv    = $pv;
                  $sRow->total_pv    =  $pv * @$request->quantity;
                  $sRow->total_price    =  @$Promotions_cost[0]->member_price * @$request->quantity;
                  $sRow->type_product    =  "promotion";

                  $sRow->action_date    =  date('Y-m-d H:i:s');
                  $sRow->created_at = date('Y-m-d H:i:s');
                  $sRow->save();

                  $dbOrder = \App\Models\Backend\Frontstore::find($request->frontstore_id);
                  $Customers = \App\Models\Backend\Customers::find($dbOrder->customers_id_fk);

                  DB::select(" UPDATE `db_orders` SET `pv_total`='".$sRow->total_pv."' WHERE (`id`='".$request->frontstore_id."') ");
                  DB::select(" UPDATE `db_promotion_cus` SET `pro_status`='2',used_user_name='".$Customers->user_name."',used_date=now() WHERE (`promotion_code`='".$sRow->promotion_code."') AND (`pro_status`='1') LIMIT 1");

             $id =   @$request->frontstore_id;

             $sFrontstoreDataTotal = DB::select(" select SUM(total_price) as total,SUM(total_pv) as total_pv from db_order_products_list WHERE frontstore_id_fk in ($id)  GROUP BY frontstore_id_fk ");
             // dd($sFrontstoreDataTotal);
             if($sFrontstoreDataTotal){
                $vat = floatval(@$sFrontstoreDataTotal[0]->total) - (floatval(@$sFrontstoreDataTotal[0]->total)/1.07) ;
                $vat = $vat > 0  ? $vat : 0 ;
                $product_value = str_replace(",","",floatval(@$sFrontstoreDataTotal[0]->total) - $vat) ;
                $total = @$sFrontstoreDataTotal[0]->total>0?@$sFrontstoreDataTotal[0]->total:0;
                $total_pv = @$sFrontstoreDataTotal[0]->total_pv>0?@$sFrontstoreDataTotal[0]->total_pv:0;
                DB::select(" UPDATE db_orders SET product_value=".($product_value).",tax=".($vat).",sum_price=".($total).",pv_total=".($total_pv)." WHERE id=$id ");
              }else{
                DB::select(" UPDATE db_orders SET product_value=0,tax=0,sum_price=0 WHERE id=$id  ");
              }

                  // UPDATE `db_order_products_list` SET `type_product`='promotion' WHERE (`id`='1')
                  // UPDATE `db_order_products_list` SET `total_pv`='1000' WHERE (`id`='1')
                  // UPDATE `db_order_products_list` SET `pv`='1000' WHERE (`id`='1')
                  // UPDATE `db_orders` SET `pv_total`='1000' WHERE (`id`='1')
                  // UPDATE `db_promotion_cus` SET `pro_status`='2' WHERE (`id`='1')

                }


        }

       return redirect()->to(url("backend/frontstore/".request('frontstore_id')."/edit"));

    }



     public function minus(Request $request)
        {
          // return($request->all());
          // dd();

          if(isset($request->product_plus)){

            $this->fnManageGiveaway(@$request->frontstore_id);

            for ($i=0; $i < count($request->product_id_fk) ; $i++) {
                // $Check_stock = \App\Models\Backend\Check_stock::find($request->id[$i]);
                // echo $Check_stock->product_id_fk;
                  $sProducts = DB::select("
                    SELECT
                    products.id,
                    (
                      SELECT product_unit_id_fk
                      FROM
                      products_units
                      WHERE product_id_fk = products.id LIMIT 1
                    ) as product_unit,
                    products.category_id ,categories.category_name,
                    (SELECT concat(img_url,product_img) FROM products_images WHERE products_images.product_id_fk=products.id AND products_images.image_default=1 LIMIT 1) as p_img,
                    (
                    SELECT concat( products.product_code,' : '  ,
                    products_details.product_name)
                    FROM
                    products_details
                    WHERE products_details.lang_id=1 AND products_details.product_id_fk=products.id
                    ) as pn,
                    (
                    SELECT dataset_product_unit.product_unit
                    FROM
                    products_units
                    LEFT JOIN dataset_product_unit on dataset_product_unit.id=products_units.product_unit_id_fk
                    WHERE products_units.product_id_fk=products.id
                    ) as product_unit,
                    products_cost.member_price,
                    products_cost.pv
                    FROM
                    products
                    LEFT JOIN categories on products.category_id=categories.id
                    LEFT JOIN products_cost on products.id = products_cost.product_id_fk
                    WHERE products.id = ".$request->product_id_fk[$i]." AND products_cost.business_location_id = 1
                ");
                  // echo ($sProducts[0]->selling_price);
                  // echo request('product_id_fk_this');
               $sFrontstore = \App\Models\Backend\Frontstore::find(request('frontstore_id'));

               $sRow = \App\Models\Backend\Frontstorelist::where('frontstore_id_fk', @$request->frontstore_id)->where('product_id_fk', @$request->product_id_fk[$i])->get();
               // return count($sRow);

                if(request('purchase_type_id_fk')==5){ //  Ai Voucher
                  $pv = 0;
                }else{
                  $pv = @$sProducts[0]->pv ;
                }


                  if( count($sRow)>0 ){

                        \App\Models\Backend\Frontstorelist::where('frontstore_id_fk', @$request->frontstore_id)->where('product_id_fk', @$request->product_id_fk[$i])->update(
                              [
                                // 'frontstore_id_fk' => request('frontstore_id') ,
                                'amt' => @$request->quantity[$i] ,
                                'total_pv' => $pv * @$request->quantity[$i] ,
                                'total_price' => @$sProducts[0]->member_price * @$request->quantity[$i] ,
                                // 'purchase_type_id_fk' => @$sFrontstore->purchase_type_id_fk ,
                                // 'product_unit_id_fk' => @$sProducts[0]->product_unit,
                              ]
                          );


                         DB::delete(" DELETE FROM db_order_products_list WHERE amt=0 ;");

                  }

              }



           $id=   @$request->frontstore_id;

           $sFrontstoreDataTotal = DB::select(" select SUM(total_price) as total,SUM(total_pv) as total_pv from db_order_products_list WHERE frontstore_id_fk in ($id) GROUP BY frontstore_id_fk ");
           // dd($sFrontstoreDataTotal);
           if($sFrontstoreDataTotal){
              $vat = floatval(@$sFrontstoreDataTotal[0]->total) - (floatval(@$sFrontstoreDataTotal[0]->total)/1.07) ;
              $vat = $vat > 0  ? $vat : 0 ;
              $product_value = str_replace(",","",floatval(@$sFrontstoreDataTotal[0]->total) - $vat) ;
              $total = @$sFrontstoreDataTotal[0]->total>0?@$sFrontstoreDataTotal[0]->total:0;
              $total_pv = @$sFrontstoreDataTotal[0]->total_pv>0?@$sFrontstoreDataTotal[0]->total_pv:0;
              DB::select(" UPDATE db_orders SET product_value=".($product_value).",tax=".($vat).",sum_price=".($total).",pv_total=".($total_pv)." WHERE id=$id ");
            }else{
              DB::select(" UPDATE db_orders SET product_value=0,tax=0,sum_price=0 WHERE id=$id  ");
            }


              // $total_price = DB::select(" select SUM(total_price) as total from db_order_products_list WHERE frontstore_id_fk=".@$request->frontstore_id." GROUP BY frontstore_id_fk ");
              // DB::select(" UPDATE db_orders SET sum_price=".(@$total_price[0]->total?@$total_price[0]->total:0)." WHERE id=".@$request->frontstore_id." ");



                 DB::select(" UPDATE db_orders SET

                  pay_type_id_fk='0',

                  aicash_price='0',
                  member_id_aicash='0',
                  transfer_price='0',
                  credit_price='0',

                  charger_type='0',
                  fee='0',
                  fee_amt='0',
                  sum_credit_price='0',
                  account_bank_id='0',

                  transfer_money_datetime=NULL ,
                  file_slip=NULL,

                  total_price='0',
                  cash_price='0',
                  cash_pay='0'

                  WHERE id=$request->frontstore_id ");



          }

        }



    public function edit($id)
    {
      // dd($id);
    }

    public function update(Request $request, $id)
    {
    }

   public function form($id=NULL)
    {

    }

    public function destroy($id)
    {
      // dd($id);
      $sRow = \App\Models\Backend\Frontstorelist::find($id);
      if( $sRow ){

        // $sRow->forceDelete();

      }
    }

    public function Datatable(Request $req){

      if(@$req->frontstore_id_fk){
         $sTable = DB::select("
            SELECT * from db_order_products_list WHERE frontstore_id_fk = ".$req->frontstore_id_fk." and add_from=1 UNION
            SELECT * from db_order_products_list WHERE frontstore_id_fk = ".$req->frontstore_id_fk." and add_from=2
            ORDER BY add_from,id
        ");

        // GROUP BY promotion_id_fk,promotion_code

      }else{
         $sTable = DB::select("
            SELECT * from db_order_products_list
        ");
      }

      $sQuery = \DataTables::of($sTable);
      return $sQuery
      ->addColumn('product_name', function($row) {

        if($row->type_product=="course"){
              return $row->product_name;
        }else{


            if(!empty($row->product_id_fk) && $row->add_from==1){

                $Products = DB::select("SELECT products.id as product_id,
                products.product_code,
                (CASE WHEN products_details.product_name is null THEN '* ไม่ได้กรอกชื่อสินค้า' ELSE products_details.product_name END) as product_name
                FROM
                products_details
                Left Join products ON products_details.product_id_fk = products.id
                WHERE products.id=".$row->product_id_fk." AND lang_id=1");

                return @$Products[0]->product_code." : ".@$Products[0]->product_name;

            }else{

              // return $row->promotion_id_fk;

              // if(!empty($row->product_id_fk) && $row->add_from==2 && $row->promotion_code!=''){
              //   // SELECT * from db_order_products_list WHERE promotion_code='B1D3JVM59'

                $Products = DB::select("
                  SELECT
                  (SELECT product_code FROM products WHERE id=promotions_products.product_id_fk limit 1) as product_code,
                  (SELECT product_name FROM products_details WHERE product_id_fk=promotions_products.product_id_fk and lang_id=1 limit 1) as product_name,
                  (SELECT product_unit
                  FROM
                  dataset_product_unit
                  WHERE id = promotions_products.product_unit AND  lang_id=1 ) as product_unit,
                  promotions_products.product_amt
                  FROM
                  promotions_products
                  WHERE
                  promotions_products.promotion_id_fk='".$row->promotion_id_fk."'
                ");

                $pn = '<div class="divTable"><div class="divTableBody">';

                foreach ($Products as $key => $value) {
                 $pn .=
                      '<div class="divTableRow">
                      <div class="divTableCell">[Pro'.$value->product_code.'] '.$value->product_name.'</div>
                      <div class="divTableCell"><center>'.$value->product_amt.' x '.$row->amt.' = </div>
                      <div class="divTableCell"><center>'.($value->product_amt*$row->amt).'</div>
                      <div class="divTableCell">'.$value->product_unit.'</div>
                      </div>
                      ';
                 }

                  $pn .= '</div></div>';

                $sD = '';

                if($row->promotion_id_fk!='' && $row->promotion_code!=''){
                    $promotions = DB::select(" SELECT name_thai as pro_name FROM promotions WHERE id='".$row->promotion_id_fk."' ");
                    $sD .=  "ชื่อโปร : ".@$promotions[0]->pro_name . " > รหัสคูปอง : ".($row->promotion_code)."</br>";
                }else{
                    $promotions = DB::select(" SELECT name_thai as pro_name FROM promotions WHERE id='".$row->promotion_id_fk."' ");
                    $sD .=  "ชื่อโปร : ".@$promotions[0]->pro_name . "</br>";
                }


                $sD .=  $pn;
                return $sD;

           }

        }


      })
      ->escapeColumns('product_name')
      ->addColumn('product_unit', function($row) {
        if(!empty($row->product_unit_id_fk)){
          $p_unit = DB::select("
              SELECT product_unit
              FROM
              dataset_product_unit
              WHERE id = ".$row->product_unit_id_fk." AND  lang_id=1 ");
              return $p_unit[0]->product_unit;
          }else{
              return '';
          }
      })
      ->addColumn('purchase_type', function($row) {
          $Frontstore = \App\Models\Backend\Frontstore::find($row->frontstore_id_fk);
          if(@$Frontstore->purchase_type_id_fk){
              $purchase_type = DB::select(" select * from dataset_orders_type where id=".$Frontstore->purchase_type_id_fk." ");
              return $purchase_type[0]->orders_type;
          }else{
              return '';
          }

      })
      ->addColumn('pv', function($row) {
        // if(!empty($row->add_from) && $row->add_from==2 && @$row->promotion_id_fk!=''){
        //    // ต้องดึงจากตารางโปรโมชั่น promotions_cost
        //     $Promotions_cost = \App\Models\Backend\Promotions_cost::where('promotion_id_fk',$row->promotion_id_fk)->get();
        //     return @$Promotions_cost[0]->pv;
        // }else{
          // ดึงจาก db_order_products_list
            return @$row->pv;
        // }
      })
      ->addColumn('selling_price', function($row) {
        // if(!empty($row->add_from) && $row->add_from==2 && @$row->promotion_id_fk!=''){
        //   // ต้องดึงจากตารางโปรโมชั่น promotions_cost
        //     $Promotions_cost = \App\Models\Backend\Promotions_cost::where('promotion_id_fk',$row->promotion_id_fk)->get();
        //     return @$Promotions_cost[0]->selling_price;
        // }else{
          // ดึงจาก db_order_products_list
           return @$row->selling_price;
        // }
      })
      ->addColumn('total_pv', function($row) {
        // if(!empty($row->add_from) && $row->add_from==2 && @$row->promotion_id_fk!=''){
        //   // ต้องดึงจากตารางโปรโมชั่น promotions_cost
        //     $Promotions_cost = \App\Models\Backend\Promotions_cost::where('promotion_id_fk',$row->promotion_id_fk)->get();
        //     return @$Promotions_cost[0]->pv;
        // }else{
          // ดึงจาก db_order_products_list
           return @$row->total_pv;
        // }
      })
      ->addColumn('total_price', function($row) {
        // if(!empty($row->add_from) && $row->add_from==2 && @$row->promotion_id_fk!=''){
        //   // ต้องดึงจากตารางโปรโมชั่น promotions_cost
        //     $Promotions_cost = \App\Models\Backend\Promotions_cost::where('promotion_id_fk',$row->promotion_id_fk)->get();
        //     return @$Promotions_cost[0]->selling_price;
        // }else{
          // ดึงจาก db_order_products_list
           return @$row->total_price;
        // }
      })
      ->addColumn('sum_price_desc', function($row) {
          $total_price = DB::select(" select SUM(total_price) as total from db_order_products_list WHERE frontstore_id_fk=".$row->frontstore_id_fk." GROUP BY frontstore_id_fk ");
          return @$total_price[0]->total;
      })
      ->addColumn('pay_type', function($row) {
          $pay_type = DB::select(" select pay_type_id_fk from db_orders WHERE id=".$row->frontstore_id_fk." ");
          return @$pay_type[0]->pay_type_id_fk;
      })
      ->addColumn('check_press_save', function($row) {
          $d = DB::select(" select check_press_save from db_orders WHERE id=".$row->frontstore_id_fk." ");
          return @$d[0]->check_press_save;
      })
      ->make(true);
    }


    public function DatatablePro(Request $req){

      $branchs = DB::select("SELECT * FROM branchs WHERE id=".($req->branch_id_fk)." ");
// เอา รหัสคนซื้อ มาตรวจสอบกับเงื่อนไขของ promotions ทุกกรณีที่ระบุไว้ใน  /backend/promotions/1/edit

       $sTable = DB::select("
          SELECT promotions.*, (SELECT concat(img_url,promotion_img) FROM promotions_images WHERE promotions_images.promotion_id_fk=promotions.id AND image_default=1 limit 1) as p_img ,

          (SELECT amt from db_order_products_list WHERE promotion_id_fk = promotions.id AND frontstore_id_fk='". $req->frontstore_id_fk."' limit 1) as frontstore_promotions_list,
          (SELECT customers_id_fk FROM `db_orders` WHERE id='". $req->frontstore_id_fk."'  limit 1) as customers_id_fk,
          '". $req->frontstore_id_fk."' as frontstore_id_fk
          from promotions
          where promotions.status=1 AND
          promotions.promotion_coupon_status=0

           AND
            (
              ".$req->order_type." = SUBSTRING_INDEX(SUBSTRING_INDEX(orders_type_id, ',', 1), ',', -1)  OR
              ".$req->order_type." = SUBSTRING_INDEX(SUBSTRING_INDEX(orders_type_id, ',', 2), ',', -1) OR
              ".$req->order_type." = SUBSTRING_INDEX(SUBSTRING_INDEX(orders_type_id, ',', 3), ',', -1) OR
              ".$req->order_type." = SUBSTRING_INDEX(SUBSTRING_INDEX(orders_type_id, ',', 4), ',', -1) OR
              ".$req->order_type." = SUBSTRING_INDEX(SUBSTRING_INDEX(orders_type_id, ',', 5), ',', -1) OR
              ".$req->order_type." = SUBSTRING_INDEX(SUBSTRING_INDEX(orders_type_id, ',', 6), ',', -1) OR
              ".$req->order_type." = SUBSTRING_INDEX(SUBSTRING_INDEX(orders_type_id, ',', 7), ',', -1)
            )

            AND curdate() BETWEEN promotions.show_startdate and promotions.show_enddate

             AND business_location = ".@$branchs[0]->business_location_id_fk."

      ");
      // AND curdate() >= promotions.show_startdate AND curdate() <= promotions.show_enddate
    //




// AND promotions.all_available_purchase > 0
      $sQuery = \DataTables::of($sTable);
      return $sQuery
  ->addColumn('product_name', function($row) {

    if(!empty($row->id)){

            $Products = DB::select("
              SELECT
              (SELECT product_code FROM products WHERE id=promotions_products.product_id_fk limit 1) as product_code,
              (SELECT product_name FROM products_details WHERE product_id_fk=promotions_products.product_id_fk and lang_id=1 limit 1) as product_name,
              (SELECT product_unit
              FROM
              dataset_product_unit
              WHERE id = promotions_products.product_unit AND  lang_id=1 ) as product_unit,
              promotions_products.product_amt
              FROM
              promotions_products
              WHERE
              promotions_products.promotion_id_fk='".$row->id."'
            ");

            $pn = '<div class="divTable"><div class="divTableBody">';

            if(!empty($Products)){

              foreach ($Products as $key => $value) {
               $pn .=
                    '<div class="divTableRow">
                    <div class="divTableCell">[Pro'.$value->product_code.'] '.$value->product_name.'</div>
                    <div class="divTableCell"><center>'.$value->product_amt.'</div>
                    <div class="divTableCell">'.$value->product_unit.'</div>
                    </div>
                    ';
               }
             }

              $pn .= '</div></div>';

              $sD = '';

              if($row->id!=''){
                  $promotions = DB::select(" SELECT name_thai as pro_name FROM promotions WHERE id='".$row->id."' ");
                  $sD .=  "ชื่อโปร : ".@$promotions[0]->pro_name . " <br> รหัสโปร : ".@$row->pcode."</br>";
              }


            $sD .=  $pn;
            return $sD;
          }

      })
      ->escapeColumns('product_name')
      ->addColumn('pv', function($row) {
          $Promotions_cost = \App\Models\Backend\Promotions_cost::where('promotion_id_fk',$row->id)->get();
          return @$Promotions_cost[0]->pv;
      })
      ->addColumn('selling_price', function($row) {
          $Promotions_cost = \App\Models\Backend\Promotions_cost::where('promotion_id_fk',$row->id)->get();
          return @$Promotions_cost[0]->member_price;
      })
      ->addColumn('select_amt', function($row) {

        $amt = DB::select(" SELECT amt from db_order_products_list WHERE promotion_id_fk in (".$row->id.") AND frontstore_id_fk in (".$row->frontstore_id_fk.") AND amt>0 limit 1 ");
        $amt = @$amt[0]->amt > 0 ? $amt[0]->amt : 0 ;

        return $row->id.":".(@$row->limited_amt_person>0?$row->limited_amt_person:0).":".$amt;
      })
      ->addColumn('p_img', function($row) {
        if($row->p_img!=""){
          return $row->p_img;
        }else{
          return 'local/public/images/example_img.png';
        }
      })
      ->addColumn('approve_status', function($row) {
          $d = \App\Models\Backend\Frontstore::where('id',$row->frontstore_id_fk)->get();
          return $d[0]->approve_status;
      })
        ->addColumn('cuase_cannot_buy', function($row) {
           $d1 = \App\Models\Backend\Frontstore::where('id',$row->frontstore_id_fk)->get();
           $d2 = \App\Models\Backend\Customers::where('id',$d1[0]->customers_id_fk)->get();

           $Check = \App\Models\Frontend\Product::product_list_select_promotion_all($d1[0]->purchase_type_id_fk,$d2[0]->user_name);
           if($Check){
              $arr = [];
              for ($i=0; $i < count(@$Check) ; $i++) {
                   $c = array_column($Check,$i);
                   foreach ($c as $key => $value) {
                    if($value['status'] == "fail"){
                       array_push($arr,$value['message']);
                    }
                   }
                   $im = implode(',',$arr);
              }
              // dd($im);
              // return $im;
              return 0;
          }else{
            return 0;
          }

      })
      ->escapeColumns('cuase_cannot_buy')
      ->make(true);
    }



}

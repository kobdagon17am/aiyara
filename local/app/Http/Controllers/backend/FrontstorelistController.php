<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;
use PDO;

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
        
        for ($i=0; $i < count($request->promotion_id_fk_pro) ; $i++) { 

// @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
            $sFrontstore = \App\Models\Backend\Frontstore::find(request('frontstore_id'));

           $sRow = \App\Models\Backend\Frontstorelist::where('frontstore_id_fk', @$request->frontstore_id)->where('promotion_id_fk', @$request->promotion_id_fk_pro[$i])->get();

         if(@$sRow[0]->promotion_id_fk == @$request->promotion_id_fk_pro[$i]){

                 $Promotions_cost = \App\Models\Backend\Promotions_cost::where('promotion_id_fk',@$request->promotion_id_fk_pro[$i])->get();

                  \App\Models\Backend\Frontstorelist::where('frontstore_id_fk', @$request->frontstore_id)->where('promotion_id_fk', @$request->promotion_id_fk_pro[$i])->update(
                        [
                          'amt' => @$request->quantity[$i] ,
                          'selling_price' => @$Promotions_cost[0]->selling_price ,
                          'pv' => @$Promotions_cost[0]->pv ,
                          'total_pv' => @$Promotions_cost[0]->pv * @$request->quantity[$i] ,
                          'total_price' => @$Promotions_cost[0]->selling_price * @$request->quantity[$i] ,
                        ]
                    ); 


          }else{


            if(@$request->promotion_id_fk_pro[$i]!=0 && @$request->quantity[$i]!=0){

                $Promotions_cost = \App\Models\Backend\Promotions_cost::where('promotion_id_fk',@$request->promotion_id_fk_pro[$i])->get();

                $sRow = new \App\Models\Backend\Frontstorelist;
                $sRow->frontstore_id_fk    = @$request->frontstore_id ;
                $sRow->amt    =  @$request->quantity[$i];
                $sRow->add_from    = '2';
                $sRow->promotion_id_fk    = @$request->promotion_id_fk_pro[$i];

                $sRow->selling_price    = @$Promotions_cost[0]->selling_price;
                $sRow->pv    = @$Promotions_cost[0]->pv;
                $sRow->total_pv    =  @$Promotions_cost[0]->pv * @$request->quantity[$i];
                $sRow->total_price    =  @$Promotions_cost[0]->selling_price * @$request->quantity[$i];

                $sRow->action_date    =  date('Y-m-d H:i:s');
                $sRow->created_at = date('Y-m-d H:i:s');
                $sRow->save();

              }

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
        
        for ($i=0; $i < count($request->promotion_id_fk_pro) ; $i++) { 

// @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
            $sFrontstore = \App\Models\Backend\Frontstore::find(request('frontstore_id'));

           $sRow = \App\Models\Backend\Frontstorelist::where('frontstore_id_fk', @$request->frontstore_id)->where('promotion_id_fk', @$request->promotion_id_fk_pro[$i])->get();

         if(@$sRow[0]->promotion_id_fk == @$request->promotion_id_fk_pro[$i]){

                 $Promotions_cost = \App\Models\Backend\Promotions_cost::where('promotion_id_fk',@$request->promotion_id_fk_pro[$i])->get();

                  \App\Models\Backend\Frontstorelist::where('frontstore_id_fk', @$request->frontstore_id)->where('promotion_id_fk', @$request->promotion_id_fk_pro[$i])->update(
                        [
                          'amt' => @$request->quantity[$i] ,
                          'selling_price' => @$Promotions_cost[0]->selling_price ,
                          'pv' => @$Promotions_cost[0]->pv ,
                          'total_pv' => @$Promotions_cost[0]->pv * @$request->quantity[$i] ,
                          'total_price' => @$Promotions_cost[0]->selling_price * @$request->quantity[$i] ,
                        ]
                    ); 


          }

           DB::delete(" DELETE FROM db_frontstore_products_list WHERE amt=0 ;");
           
// @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@

        }

      }

    }



    public function plus(Request $request)
    {
      // return($request->all());
      // return(count($request->product_id_fk));
      // return($request->quantity[0]);
      // dd();

      if(isset($request->product_plus)){
        for ($i=0; $i < count($request->product_id_fk) ; $i++) { 
            // $Check_stock = \App\Models\Backend\Check_stock::find($request->id[$i]);
            // echo $Check_stock->product_id_fk;
              $sProducts = DB::select("
                SELECT
                products.id,
                (
                  SELECT id
                  FROM
                  dataset_product_unit
                  WHERE id = products.id AND lang_id=1 LIMIT 1
                ) as product_unit,                
                products.category_id ,categories.category_name,
                (SELECT concat(img_url,product_img) FROM products_images WHERE products_images.product_id_fk=products.id) as p_img,
                (
                SELECT concat( products.product_code,' : '  ,
                products_details.product_name)
                FROM
                products_details
                WHERE products_details.lang_id=1 AND products_details.product_id_fk=products.id
                ) as pn,
                products_cost.selling_price,
                products_cost.pv
                FROM
                products
                LEFT JOIN categories on products.category_id=categories.id
                LEFT JOIN products_cost on products.id = products_cost.product_id_fk
                WHERE products.id = ".$request->product_id_fk[$i]." AND products_cost.business_location_id = 1 
            ");
              // echo ($sProducts[0]->product_unit);
           
           $sFrontstore = \App\Models\Backend\Frontstore::find(request('frontstore_id'));

           $sRow = \App\Models\Backend\Frontstorelist::where('frontstore_id_fk', @$request->frontstore_id)->where('product_id_fk', @$request->product_id_fk[$i])->get();
           // echo count($sRow);
           // return @$request->frontstore_id;
          if( count($sRow)>0 ){

              if(@$request->product_id_fk[$i] == request('product_id_fk_this')){

                // echo @$request->quantity[$i];

                  \App\Models\Backend\Frontstorelist::where('frontstore_id_fk', @$request->frontstore_id)->where('product_id_fk', @$request->product_id_fk[$i])->update(
                        [
                          // 'frontstore_id_fk' => request('frontstore_id') ,
                          'amt' => @$request->quantity[$i] ,
                          'total_pv' => @$sProducts[0]->pv * @$request->quantity[$i] ,
                          'total_price' => @$sProducts[0]->selling_price * @$request->quantity[$i] ,
                          // 'purchase_type_id_fk' => @$sFrontstore->purchase_type_id_fk,
                          'product_unit_id_fk' => @$sProducts[0]->product_unit,
                        ]
                    ); 

              }

          }else{

                $sRow = new \App\Models\Backend\Frontstorelist;
                $sRow->frontstore_id_fk    = request('frontstore_id') ;
                $sRow->product_id_fk    = @$request->product_id_fk[$i];
                $sRow->purchase_type_id_fk    = @$sFrontstore->purchase_type_id_fk;
                $sRow->selling_price    = @$sProducts[0]->selling_price;
                $sRow->pv    = @$sProducts[0]->pv;
                $sRow->amt    =  @$request->quantity[$i];
                $sRow->product_unit_id_fk    =  @$sProducts[0]->product_unit ;
                $sRow->total_pv    =  @$sProducts[0]->pv * @$request->quantity[$i];
                $sRow->total_price    =  @$sProducts[0]->selling_price * @$request->quantity[$i];
                $sRow->created_at = date('Y-m-d H:i:s');
                if(!empty(request('quantity')[$i])){
                  if(@$request->product_id_fk[$i] == request('product_id_fk_this')){
                      $sRow->save();
                  }
                }

          }

   
          }


          $total_price = DB::select(" select SUM(total_price) as total from db_frontstore_products_list WHERE frontstore_id_fk=".@$request->frontstore_id." GROUP BY frontstore_id_fk ");
          DB::select(" UPDATE db_frontstore SET sum_price=".(@$total_price[0]->total?@$total_price[0]->total:0)." WHERE id=".@$request->frontstore_id." ");


          if(isset($request->product_plus_addlist)){
              // return redirect()->to(url("backend/frontstore/".request('frontstore_id')."/edit"));
             if($request->quantity[0]==0){
                DB::delete(" DELETE FROM db_frontstore_products_list WHERE amt=0 ;");
             }
          }

      }

    }


    public function store(Request $request)
    {
        // dd($request->all());
        // dd($request->frontstore_id);

       if(isset($request->add_delivery_custom)){

            DB::insert(" INSERT INTO customers_addr_frontstore (frontstore_id_fk, customers_id_fk, recipient_name, addr_no, province_id_fk , amphur_code, tambon_code, zip_code, tel, created_at) 
              VALUES 
              ('".$request->frontstore_id."',
               '".$request->customers_id_fk."',
               '".$request->delivery_cusname."',
                '".$request->delivery_addr."',
                 '".$request->delivery_province."',
                 '".$request->delivery_amphur."',
                 '".$request->delivery_tambon."',
                 '".$request->delivery_zipcode."',
                 '".$request->delivery_tel."',
                 now() 
              )
            ");       

              $sRow = \App\Models\Backend\Frontstore::find($request->frontstore_id);
              $sRow->delivery_location    = '3';
              
              $sRow->action_date = date('Y-m-d H:i:s');
              $sRow->updated_at = date('Y-m-d H:i:s');
              $sRow->save();    

        }

       if(isset($request->update_delivery_custom)){

        // dd($request->all());

       	    $ch = DB::select("select * from customers_addr_frontstore where frontstore_id_fk=".$request->customers_addr_frontstore_id." ");
       	    // dd(count($ch));

       	    if(count($ch)==0){

       	    	DB::insert(" INSERT INTO customers_addr_frontstore (frontstore_id_fk, customers_id_fk, recipient_name, addr_no, province_id_fk , amphur_code, tambon_code, zip_code, tel, created_at) 
		              VALUES 
		              ('".$request->customers_addr_frontstore_id."',
		               '".$request->customers_id_fk."',
		               '".$request->delivery_cusname."',
		                '".$request->delivery_addr."',
		                 '".$request->delivery_province."',
		                 '".$request->delivery_amphur."',
		                 '".$request->delivery_tambon."',
		                 '".$request->delivery_zipcode."',
		                 '".$request->delivery_tel."',
		                 now() 
		              )
		            ");

       	    }else{

       	    	 DB::insert(" UPDATE customers_addr_frontstore 
	              SET recipient_name = '".$request->delivery_cusname."', 
	              addr_no = '".$request->delivery_addr."', 
	              province_id_fk  = '".$request->delivery_province."', 
	              amphur_code = '".$request->delivery_amphur."',  
	              tambon_code = '".$request->delivery_tambon."', 
	              zip_code = '".$request->delivery_zipcode."', 
	              tel = '".$request->delivery_tel."', 
	              updated_at = now() where frontstore_id_fk=".$request->customers_addr_frontstore_id."
	            ");    

            }   

            // ค่าขนส่ง

             $frontstore = DB::select("SELECT * FROM db_frontstore WHERE id=".$request->frontstore_id." ");
             $branchs = DB::select("SELECT * FROM branchs WHERE id=".$frontstore[0]->branch_id_fk." ");
             // dd($frontstore[0]->branch_id_fk);
             // dd($branchs[0]->province_id_fk);
             // dd($request->delivery_province);
                if($request->delivery_province==$branchs[0]->province_id_fk){
                    DB::select("UPDATE db_frontstore SET shipping_price=0 WHERE id=".$request->frontstore_id." ");
                }else{
                     // ต่าง จ. กัน เช็คดูว่า อยู่ในเขรปริมณทฑลหรือไม่
                    $shipping_cost = DB::select("SELECT * FROM dataset_shipping_cost where business_location_id_fk =".$branchs[0]->business_location_id_fk." AND shipping_type=2  ");

                    $shipping_vicinity = DB::select("SELECT * FROM dataset_shipping_vicinity where shipping_cost_id_fk =".$shipping_cost[0]->id." AND province_id_fk=".($request->delivery_province?$request->delivery_province:0)." ");
                    if(count($shipping_vicinity)>0){

                        DB::select("UPDATE db_frontstore SET shipping_price=".$shipping_cost[0]->shipping_cost." WHERE id=".$request->frontstore_id." ");
                        // return $shipping_cost[0]->shipping_cost;
                    }else{
                        $shipping_cost = DB::select("SELECT * FROM dataset_shipping_cost where business_location_id_fk =".$branchs[0]->business_location_id_fk." AND shipping_type=1 AND shipping_cost<>0 ");

                        DB::select("UPDATE db_frontstore SET shipping_price=".$shipping_cost[0]->shipping_cost." WHERE id=".$request->frontstore_id." ");
                        // return $shipping_cost[0]->shipping_cost;

                    }

                }



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

                DB::select(" INSERT IGNORE INTO customers_addr_sent (customer_id, first_name, house_no, zipcode, district, district_sub, province, from_table, from_table_id, receipt_no) VALUES ('".@$request->customers_id_fk."', '".@$addr[0]->recipient_name."','".@$addr[0]->addr_no."','".@$addr[0]->zip_code."', '".@$addr[0]->ampname."', '".@$addr[0]->tamname."', '".@$addr[0]->provname."', 'customers_addr_frontstore', '".@$addr[0]->id."','".@$request->invoice_code."') "); 


        }


        if(isset($request->product_plus_pro)){
          // dd($request->product_plus_pro);
                $Promotions_cost = \App\Models\Backend\Promotions_cost::where('promotion_id_fk',@$request->promotion_id_fk)->get();

        //     return @$Promotions_cost[0]->pv;
                $sRow = new \App\Models\Backend\Frontstorelist;
                $sRow->frontstore_id_fk    = @$request->frontstore_id ;
                $sRow->amt    = @$request->quantity;
                $sRow->add_from    = '2';
                $sRow->promotion_id_fk    = @$request->promotion_id_fk;
                $sRow->promotion_code    = @$request->txtSearchPro;

                $sRow->selling_price    = @$Promotions_cost[0]->selling_price;
                $sRow->pv    = @$Promotions_cost[0]->pv;
                $sRow->total_pv    =  @$Promotions_cost[0]->pv * @$request->quantity;
                $sRow->total_price    =  @$Promotions_cost[0]->selling_price * @$request->quantity;

                $sRow->action_date    =  date('Y-m-d H:i:s');
                $sRow->created_at = date('Y-m-d H:i:s');
                $sRow->save();
        }

       return redirect()->to(url("backend/frontstore/".request('frontstore_id')."/edit"));

    }



     public function minus(Request $request)
        {
          // return($request->all());
          // dd();

          if(isset($request->product_plus)){
            for ($i=0; $i < count($request->product_id_fk) ; $i++) { 
                // $Check_stock = \App\Models\Backend\Check_stock::find($request->id[$i]);
                // echo $Check_stock->product_id_fk;
                  $sProducts = DB::select("
                    SELECT
                    products.id,
                    (
                      SELECT id
                      FROM
                      dataset_product_unit
                      WHERE id = products.id AND lang_id=1 LIMIT 1
                    ) as product_unit,                     
                    products.category_id ,categories.category_name,
                    (SELECT concat(img_url,product_img) FROM products_images WHERE products_images.product_id_fk=products.id) as p_img,
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
                    products_cost.selling_price,
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
                  if( count($sRow)>0 ){

                        \App\Models\Backend\Frontstorelist::where('frontstore_id_fk', @$request->frontstore_id)->where('product_id_fk', @$request->product_id_fk[$i])->update(
                              [
                                // 'frontstore_id_fk' => request('frontstore_id') ,
                                'amt' => @$request->quantity[$i] ,
                                'total_pv' => @$sProducts[0]->pv * @$request->quantity[$i] ,
                                'total_price' => @$sProducts[0]->selling_price * @$request->quantity[$i] ,
                                // 'purchase_type_id_fk' => @$sFrontstore->purchase_type_id_fk ,
                                // 'product_unit_id_fk' => @$sProducts[0]->product_unit,
                              ]
                          ); 


                         DB::delete(" DELETE FROM db_frontstore_products_list WHERE amt=0 ;");

                  }
       
              }

              $total_price = DB::select(" select SUM(total_price) as total from db_frontstore_products_list WHERE frontstore_id_fk=".@$request->frontstore_id." GROUP BY frontstore_id_fk ");
              DB::select(" UPDATE db_frontstore SET sum_price=".(@$total_price[0]->total?@$total_price[0]->total:0)." WHERE id=".@$request->frontstore_id." ");


          }

        }



    public function edit($id)
    {
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

        // $frontstore_products_list = DB::select(" select frontstore_id_fk from db_frontstore_products_list WHERE id=$id GROUP BY frontstore_id_fk ");

        $sRow->forceDelete();
        
        // $sFrontstoreDataTotal = DB::select(" select SUM(total_price) as total from db_frontstore_products_list WHERE frontstore_id_fk=".$frontstore_products_list[0]->frontstore_id_fk." GROUP BY frontstore_id_fk ");

        // if($sFrontstoreDataTotal){
        //   $vat = floatval(@$sFrontstoreDataTotal[0]->total) - (floatval(@$sFrontstoreDataTotal[0]->total)/1.07) ;
        //   $product_value = str_replace(",","",floatval(@$sFrontstoreDataTotal[0]->total) - $vat) ;
        //   DB::select(" UPDATE db_frontstore SET product_value=".($product_value).",tax=".($vat).",sum_price=".@$sFrontstoreDataTotal[0]->total." WHERE id=".$frontstore_products_list[0]->frontstore_id_fk." ");
        // }else{
        //   DB::select(" UPDATE db_frontstore SET product_value=0,tax=0,sum_price=0 WHERE id=".$frontstore_products_list[0]->frontstore_id_fk." ");
        //   return redirect()->to(url("backend/frontstore/".$frontstore_products_list[0]->frontstore_id_fk."/edit"));
        // }


      }
    }

    public function Datatable(Request $req){

      if(@$req->frontstore_id_fk){
         $sTable = DB::select("
            SELECT * from db_frontstore_products_list WHERE frontstore_id_fk = ".$req->frontstore_id_fk." and add_from=1 UNION
            SELECT * from db_frontstore_products_list WHERE frontstore_id_fk = ".$req->frontstore_id_fk." and add_from=2 GROUP BY promotion_id_fk,promotion_code
            ORDER BY add_from,id 
        ");        
        //  $sTable = DB::select("
        //     SELECT * from db_frontstore_products_list WHERE frontstore_id_fk = ".$req->frontstore_id_fk." 
        // ");
      }else{
         $sTable = DB::select("
            SELECT * from db_frontstore_products_list 
        ");
      }

      $sQuery = \DataTables::of($sTable);
      return $sQuery
      ->addColumn('product_name', function($row) {
        
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
          //   // SELECT * from db_frontstore_products_list WHERE promotion_code='B1D3JVM59'

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
          $purchase_type = DB::select(" select * from dataset_orders_type where id=".$Frontstore->purchase_type_id_fk." ");
          return $purchase_type[0]->orders_type;
      }) 
      ->addColumn('pv', function($row) {
        // if(!empty($row->add_from) && $row->add_from==2 && @$row->promotion_id_fk!=''){
        //    // ต้องดึงจากตารางโปรโมชั่น promotions_cost
        //     $Promotions_cost = \App\Models\Backend\Promotions_cost::where('promotion_id_fk',$row->promotion_id_fk)->get();
        //     return @$Promotions_cost[0]->pv;
        // }else{
          // ดึงจาก db_frontstore_products_list
            return @$row->pv;
        // }
      })     
      ->addColumn('selling_price', function($row) {
        // if(!empty($row->add_from) && $row->add_from==2 && @$row->promotion_id_fk!=''){
        //   // ต้องดึงจากตารางโปรโมชั่น promotions_cost
        //     $Promotions_cost = \App\Models\Backend\Promotions_cost::where('promotion_id_fk',$row->promotion_id_fk)->get();
        //     return @$Promotions_cost[0]->selling_price;
        // }else{
          // ดึงจาก db_frontstore_products_list
           return @$row->selling_price;
        // }
      })  
      ->addColumn('total_pv', function($row) {
        // if(!empty($row->add_from) && $row->add_from==2 && @$row->promotion_id_fk!=''){
        //   // ต้องดึงจากตารางโปรโมชั่น promotions_cost
        //     $Promotions_cost = \App\Models\Backend\Promotions_cost::where('promotion_id_fk',$row->promotion_id_fk)->get();
        //     return @$Promotions_cost[0]->pv;
        // }else{
          // ดึงจาก db_frontstore_products_list
           return @$row->total_pv;
        // }
      })    
      ->addColumn('total_price', function($row) {
        // if(!empty($row->add_from) && $row->add_from==2 && @$row->promotion_id_fk!=''){
        //   // ต้องดึงจากตารางโปรโมชั่น promotions_cost
        //     $Promotions_cost = \App\Models\Backend\Promotions_cost::where('promotion_id_fk',$row->promotion_id_fk)->get();
        //     return @$Promotions_cost[0]->selling_price;
        // }else{
          // ดึงจาก db_frontstore_products_list
           return @$row->total_price;
        // }
      })  
      ->addColumn('sum_price_desc', function($row) {
          $total_price = DB::select(" select SUM(total_price) as total from db_frontstore_products_list WHERE frontstore_id_fk=".$row->frontstore_id_fk." GROUP BY frontstore_id_fk ");
          return @$total_price[0]->total;
      })                   
      ->make(true);
    }


    public function DatatablePro(Request $req){

       $sTable = DB::select("
          SELECT promotions.*, (SELECT concat(img_url,promotion_img) FROM promotions_images WHERE promotions_images.promotion_id_fk=promotions.id AND image_default=1 limit 1) as p_img ,
          (SELECT amt from db_frontstore_products_list WHERE promotion_id_fk = promotions.id AND frontstore_id_fk='". $req->frontstore_id_fk."' limit 1) as frontstore_promotions_list
          from promotions where promotions.status=1 AND promotions.promotion_coupon_status=0
           AND 
            (
              ".$req->order_type." = SUBSTRING_INDEX(SUBSTRING_INDEX(orders_type_id, ',', 1), ',', -1)  OR 
              ".$req->order_type." = SUBSTRING_INDEX(SUBSTRING_INDEX(orders_type_id, ',', 2), ',', -1) OR 
              ".$req->order_type." = SUBSTRING_INDEX(SUBSTRING_INDEX(orders_type_id, ',', 3), ',', -1) OR 
              ".$req->order_type." = SUBSTRING_INDEX(SUBSTRING_INDEX(orders_type_id, ',', 4), ',', -1) OR 
              ".$req->order_type." = SUBSTRING_INDEX(SUBSTRING_INDEX(orders_type_id, ',', 5), ',', -1) 
            )
      ");
 
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

            foreach ($Products as $key => $value) {
             $pn .=     
                  '<div class="divTableRow">
                  <div class="divTableCell">[Pro'.$value->product_code.'] '.$value->product_name.'</div>
                  <div class="divTableCell"><center>'.$value->product_amt.'</div>
                  <div class="divTableCell">'.$value->product_unit.'</div>
                  </div>
                  ';
             }

              $pn .= '</div></div>';  

            $sD = '';

            if($row->id!=''){
                $promotions = DB::select(" SELECT name_thai as pro_name FROM promotions WHERE id='".$row->id."' ");
                $sD .=  "ชื่อโปร : ".@$promotions[0]->pro_name . " <br> รหัสโปร : ".$row->pcode."</br>";
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
          return @$Promotions_cost[0]->selling_price;        
      }) 
      ->addColumn('select_amt', function($row) {
          return $row->id.":".$row->limited_amt_person;        
      })   
      ->addColumn('p_img', function($row) {
        if($row->p_img!=""){
          return $row->p_img;        
        }else{
          return 'local/public/images/example_img.png';
        }
      }) 
      ->make(true);
    }



}

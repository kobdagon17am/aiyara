<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;

class ProductsListController extends Controller
{

    public function index(Request $request)
    {
    }

    public function create()
    {
    }
    public function store(Request $request)
    {
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
    }

    public function Datatable(Request $req){

      // return $req->order_type;

      $sBranchs = DB::select(" select * from branchs where id=" . $req->branch_id_fk . " ");
      switch ($req->category_id) {
         case '1':
          $category_id = '';
          break;
         case '2':
          $category_id = ' AND products.category_id = 2 ';
          break;
         case '3':
          $category_id = ' AND products.category_id = 3 ';
          break;
         case '4':
          $category_id = ' AND products.category_id = 4 ';
          break;
         case '8':
          $category_id = ' AND products.category_id = 8 ';
          break;
          case '5':
            $category_id = ' AND products.category_id = 5 ';
            break;
        default:
          # code...
          $category_id = '';
          break;
      }

      $order_type = !empty($req->order_type) ? $req->order_type : 0 ;

      // $sTable = DB::select("
      //       SELECT
      //       products.id,
      //       products.category_id ,categories.category_name,
      //       (SELECT concat(img_url,product_img) FROM products_images WHERE products_images.product_id_fk=products.id) as p_img,
      //       (
      //       SELECT concat( products.product_code,' : '  ,
      //       products_details.product_name)
      //       FROM
      //       products_details
      //       WHERE products_details.lang_id=1 AND products_details.product_id_fk=products.id
      //       ) as pn,
      //       (
      //       SELECT dataset_product_unit.product_unit
      //       FROM
      //       products_units
      //       LEFT JOIN dataset_product_unit on dataset_product_unit.id=products_units.product_unit_id_fk
      //       WHERE products_units.product_id_fk=products.id
      //       ) as product_unit,
      //       products_cost.member_price as price,
      //       products_cost.pv,
      //       (SELECT amt from db_order_products_list WHERE product_id_fk = products.id AND frontstore_id_fk=".$req->frontstore_id_fk.") as frontstore_products_list
      //       FROM
      //       products
      //       LEFT JOIN categories on products.category_id=categories.id
      //       LEFT JOIN products_cost on products.id = products_cost.product_id_fk
      //       WHERE products_cost.business_location_id = 1 AND products.status = 1
      //        AND
      //       (
      //         $order_type = SUBSTRING_INDEX(SUBSTRING_INDEX(orders_type_id, ',', 1), ',', -1) OR
      //         $order_type = SUBSTRING_INDEX(SUBSTRING_INDEX(orders_type_id, ',', 2), ',', -1) OR
      //         $order_type = SUBSTRING_INDEX(SUBSTRING_INDEX(orders_type_id, ',', 3), ',', -1) OR
      //         $order_type = SUBSTRING_INDEX(SUBSTRING_INDEX(orders_type_id, ',', 4), ',', -1) OR
      //         $order_type = SUBSTRING_INDEX(SUBSTRING_INDEX(orders_type_id, ',', 5), ',', -1)
      //       )
      //       $category_id
      //       ORDER BY pn
      //   ");
      $sTable = DB::select("
      SELECT
      products.id,
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
      products_cost.member_price as price,
      products_cost.pv,
      (SELECT amt from db_order_products_list WHERE product_id_fk = products.id AND frontstore_id_fk=".$req->frontstore_id_fk.") as frontstore_products_list
      FROM
      products
      LEFT JOIN categories on products.category_id=categories.id
      LEFT JOIN products_cost on products.id = products_cost.product_id_fk
      WHERE products_cost.business_location_id = ".$sBranchs[0]->business_location_id_fk." AND products.status = 1
       AND
      (
        $order_type = SUBSTRING_INDEX(SUBSTRING_INDEX(orders_type_id, ',', 1), ',', -1) OR
        $order_type = SUBSTRING_INDEX(SUBSTRING_INDEX(orders_type_id, ',', 2), ',', -1) OR
        $order_type = SUBSTRING_INDEX(SUBSTRING_INDEX(orders_type_id, ',', 3), ',', -1) OR
        $order_type = SUBSTRING_INDEX(SUBSTRING_INDEX(orders_type_id, ',', 4), ',', -1) OR
        $order_type = SUBSTRING_INDEX(SUBSTRING_INDEX(orders_type_id, ',', 5), ',', -1)
      )
      $category_id
      ORDER BY pn
  ");
      $sQuery = \DataTables::of($sTable);
      return $sQuery
      ->make(true);
    }


}

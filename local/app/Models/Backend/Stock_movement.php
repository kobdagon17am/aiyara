<?php

namespace App\Models\Backend;

use App\Models\InitModel;
use DB;

class Stock_movement extends InitModel
{
    protected $table = 'db_stock_movement';

    static function add_movement_po($stock_id = 0,$po_id = 0,$amt = 0){
        $stock =  DB::table('db_stocks')->where('id',$stock_id)->first();
        $po =  DB::table('db_po_supplier')->where('id',$po_id)->first();
        if($stock && $po){
            $move = new Stock_movement();
            $move->stock_type_id_fk = 4;
            $move->stock_id_fk = $stock->id;
            $move->ref_table = 'db_po_supplier';
            $move->ref_table_id = $po->id;
            $move->ref_doc = $po->po_number;
            $move->doc_date = $po->created_at;
            $move->business_location_id_fk = $stock->business_location_id_fk;
            $move->branch_id_fk = $stock->branch_id_fk;
            $move->product_id_fk = $stock->product_id_fk;
            $move->lot_number = $stock->lot_number;
            $move->lot_expired_date = $stock->lot_expired_date;
            $move->amt = $amt;
            $move->in_out = 1;
            $move->product_unit_id_fk = $stock->product_unit_id_fk;
            $move->warehouse_id_fk = $stock->warehouse_id_fk;
            $move->zone_id_fk = $stock->zone_id_fk;
            $move->shelf_id_fk = $stock->shelf_id_fk;
            $move->shelf_floor = $stock->shelf_floor;
            $move->status = 1;
            $move->note = 'รับสินค้าตาม PO';
            $move->note2 = ' ';
            $move->action_user = \Auth::user()->id;
            $move->action_date = date('Y-m-d H:i:s');
            $move->approver = \Auth::user()->id;
            $move->approve_date = date('Y-m-d H:i:s');
            $move->save();
        }
    }

    static function remove_movement_po($stock_id = 0,$po_id = 0,$amt = 0){
        $stock =  DB::table('db_stocks')->where('id',$stock_id)->first();
        $po =  DB::table('db_po_supplier')->where('id',$po_id)->first();
        if($stock && $po){
            $move = new Stock_movement();
            $move->stock_type_id_fk = 4;
            $move->stock_id_fk = $stock->id;
            $move->ref_table = 'db_po_supplier';
            $move->ref_table_id = $po->id;
            $move->ref_doc = $po->po_number;
            $move->doc_date = $po->created_at;
            $move->business_location_id_fk = $stock->business_location_id_fk;
            $move->branch_id_fk = $stock->branch_id_fk;
            $move->product_id_fk = $stock->product_id_fk;
            $move->lot_number = $stock->lot_number;
            $move->lot_expired_date = $stock->lot_expired_date;
            $move->amt = $amt;
            $move->in_out = 2;
            $move->product_unit_id_fk = $stock->product_unit_id_fk;
            $move->warehouse_id_fk = $stock->warehouse_id_fk;
            $move->zone_id_fk = $stock->zone_id_fk;
            $move->shelf_id_fk = $stock->shelf_id_fk;
            $move->shelf_floor = $stock->shelf_floor;
            $move->status = 1;
            $move->note = 'คืนรับสินค้าตาม PO';
            $move->note2 = ' ';
            $move->action_user = \Auth::user()->id;
            $move->action_date = date('Y-m-d H:i:s');
            $move->approver = \Auth::user()->id;
            $move->approve_date = date('Y-m-d H:i:s');
            $move->save();
        }
    }

}

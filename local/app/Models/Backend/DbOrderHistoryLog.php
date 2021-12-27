<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Model;

class DbOrderHistoryLog extends Model
{
    /*
    * Table References for Join
      + order_id -> db_orders.id
      + status_id -> dataset_order_history_statuses.id
      + user_id -> ck_users_admin.id
    */
    protected $fillable = ['order_id', 'status_id', 'user_id'];

    // Create Log Order
    public function store(int $order_id, int $status_id, int $user_id)
    {
      return $this->create([
        'order_id' => $order_id,
        'status_id' => $status_id,
        'user_id' => $user_id,
      ]);
    }

    public function queryLogs(int $order_id)
    {
      return $this->select(
          'dataset_order_history_statuses.name as status_name',
          'ck_users_admin.name as approve_name',
          'db_order_history_logs.created_at'
        )
        ->leftjoin('dataset_order_history_statuses', 'dataset_order_history_statuses.id', '=', 'db_order_history_logs.status_id')
        ->leftjoin('ck_users_admin', 'ck_users_admin.id', '=', 'db_order_history_logs.user_id')
        ->where('order_id', $order_id)
        ->get();
    }
}

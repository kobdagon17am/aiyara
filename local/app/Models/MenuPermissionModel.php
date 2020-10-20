<?php
 namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class MenuPermissionModel extends Model
{

    protected $table = "menu_admin";
    protected $primaryKey = "id_menu_admin";

    public $timestamps = false;

}

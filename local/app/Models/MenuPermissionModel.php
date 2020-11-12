<?php
 namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class MenuPermissionModel extends Model
{

    // protected $table = "menu_admin";
    protected $table = "role_permit";
    // protected $primaryKey = "id_menu_admin";
    protected $primaryKey = "id";

    public $timestamps = false;

}

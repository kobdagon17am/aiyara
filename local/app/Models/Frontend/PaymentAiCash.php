<?php
namespace App\Models\Frontend;

use App\Models\Frontend\RunNumberPayment;
use Auth;
use DB;
use Illuminate\Database\Eloquent\Model;

class PaymentAiCash extends Model
{
    public static function payment_uploadfile($rs)
    {
    }


    public static function credit_card($rs)
    {
      dd('Soon');
    }

}

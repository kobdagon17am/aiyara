<?php
namespace App\Models\Frontend;

use Illuminate\Database\Eloquent\Model;
use Laraveldaily\Quickadmin\Observers\UserActionsObserver;

class Random_code extends Model
{
	public static function random_code($number = ''){
		if(empty($number)){
			$number =8;
		}

		$alphabet = 'ABCDEFGHJKLMNOPQRSTUVWXYZ23456789';
		$code = array();

		$alphaLength = strlen($alphabet) - 1;
		for($i = 0; $i < $number; $i++) {
			$n = rand(0, $alphaLength);
			$code[] = $alphabet[$n];
		}
		$code = implode($code);
		return $code;
	}

}

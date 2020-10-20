<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InitModel extends Model
{
  use SoftDeletes;
  protected function serializeDate(\DateTimeInterface $date)
  {
    return $date->format('Y-m-d H:i:s');
  }

  /*
    public function newQuery($excludeDeleted = true)
    {
  		if( request('withTrashed') == 'true' ){
  			return parent::newQuery()->withTrashed();
  		}else if( request('onlyTrashed') == 'true' ){
  			if( request('onlyModel') == substr(strrchr(static::class, "\\"), 1) ){
  				return parent::newQuery()->onlyTrashed();
  			}else{
  				return parent::newQuery();
  			}
  		}else{
  			return parent::newQuery();
  		}
    }
  */

    public static function boot()
    {
        parent::boot();

        static::creating(function($sData)
        {
    			//$sData->created_by	= \Auth::user()->id??null;
    			$sData->created_at	= \Carbon\Carbon::now();
    			//$sData->updated_by	= \Auth::user()->id??null;
        });

        static::updating(function($sData)
        {
    			//$sData->updated_by	= \Auth::user()->id??null;
    			$sData->updated_at	= \Carbon\Carbon::now();
        });

        static::saving(function($sData)
        {

  			if( $sData->wasChanged() )
  			{
  				//$sData->updated_by	= \Auth::user()->id;
  				$sData->updated_at	= \Carbon\Carbon::now();
  			}
        });
/*
        static::deleting(function($sData)
        {
    			//$sData->updated_by	= \Auth::user()->id;
    			//$sData->updated_at	= \Carbon\Carbon::now();
        });

        static::restoring(function($sData)
        {
    			//$sData->updated_by	= \Auth::user()->id;
    			//$sData->updated_at	= \Carbon\Carbon::now();
        });
*/
    }

    public function scopesearch($sQuery)
    {
  		if( request('Where') ){
  			foreach(request('Where') AS $sKey => $sValue){
  				if( $sValue ){
  					if( strpos($sKey,'.') ){
  						list($sModel, $sField) = explode('.',$sKey);
  						$sQuery->whereHas($sModel, function ($query) use ($sField, $sValue) {
  							$query->where($sField, $sValue);
  						});
  					}else{
  						$sQuery->where($sKey, $sValue);
  					}
  				}
  			}
  		}

  		if( request('Like') ){
  			foreach(request('Like') AS $sKey => $sValue){
  				if( $sValue ){
  					if( strpos($sKey,'.') ){
  						list($sModel, $sField) = explode('.',$sKey);
  						$sQuery->whereHas($sModel, function ($query) use ($sField, $sValue) {
  							$query->where($sField, 'like', '%'.$sValue.'%');
  						});
  					}else{
  						$sQuery->where($sKey, 'like', '%'.$sValue.'%');
  					}
  				}
  			}
  		}
       return $sQuery;
    }


	public function TimeDiff($field){
		$time = '';
		$date1 = new \DateTime($this->{$field});
		$date2 = $date1->diff(new \DateTime());
		$time .= empty($date2->y)?'':$date2->y.' ปี ';
		$time .= empty($date2->m)?'':$date2->m.' เดือน ';
		$time .= empty($date2->d)?'':$date2->d.' วัน ';
		$time .= empty($date2->h)?'':$date2->h.' ชั่วโมง ';
		$time .= empty($date2->i)?'':$date2->i.' นาที ';
		if( $date2->i < 1 )	$time .= empty($date2->s)?'':$date2->s.' วินาที ';
		return $time;
	}

}

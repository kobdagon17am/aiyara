<?php
namespace App\Helpers;
use DateTime;
use DateInterval;
use DB;

class General {

	static function gen_thai_date($date, $year = '0') {
		if($date) {
			$ex     = explode('-', $date);
			$month  = str_pad($ex[1],2,'0',STR_PAD_LEFT);
			$day 	= str_pad($ex[2],2,'0',STR_PAD_LEFT);
			$return = $day.' '.General::gen_full_month($month,'th').' '.($ex[0]+$year);
		} else {
			$return = $date;
		}
		return $return;
	}

	static function N_A($text) {
		if(!$text) {
			$text = '-';
		} else {
			$text = $text;
		}
		return $text;
	}

	static function round_price_planforkids($price) {
		//ปัดเศษราคา //Main
		$round = round($price); //ตั้งแต่ .5 ปัดขึ้น
		return $round;
	}

	static function add_span_last_text($text) {
		$return = '';
		$array = [];
		$ex = explode(' ',$text);
		foreach ($ex as $key => $value) {
			if(($key+1) == count($ex)) {
				$array[] = '<span>'.$value.'</span>';
			} else {
				$array[] = $value;
			}
			$return = implode(' ',$array);
		}
		return $return;
	}
	
	static function gen_route($route,$name,$id) {
		$new_route = '';
        if(@$route) {
            $new_route = $route;
        } else {
            $new_route = str_replace(' ','_',$name).'__'.$id;
        }

        //clear (?)
        $new_route = str_replace('?','',$new_route);

        return $new_route;
	}

	static function check_http($url) {
		$new_url = '';
		if( stristr($url,'http') === FALSE) {
			//พบ
			$new_url = 'http://'.$url;
		} else {
			$new_url = $url;
		}
		return $new_url;
	}

	//คำนวนหาราคาหลังลดเปอเซ็น
	static function cal_price_discount_pecent($price,$discount_percent) {
		$price_after_discount = 0;
		$price_after_discount = $price - ($price * ($discount_percent/100));
		return $price_after_discount;
	}

	//คำนวนส่วนต่างเปอเซ็น
	static function cal_between_percent_discount($price,$discount_price) {
		$pecent = 0;
		
		if($price > $discount_price) {
			//ส่วนลดเท่าไหร่
			$range = $price - $discount_price;
			$pecent = ($range/$price)*100;
		} else if($price < $discount_price) {
			//กำไรเท่าไหร่
			$range = $discount_price - $price;
			$pecent = ($range/$price)*100;
		}

		return round($pecent);
	}

	//คำนวนส่วนต่างเปอเซ็น ไม่ปัด
	static function cal_between_percent_discount_real($price,$discount_price) {
		$pecent = 0;
		
		if($price > $discount_price) {
			//ส่วนลดเท่าไหร่
			$range = $price - $discount_price;
			$pecent = ($range/$price)*100;
		} else if($price < $discount_price) {
			//กำไรเท่าไหร่
			$range = $discount_price - $price;
			$pecent = ($range/$price)*100;
		}

		return $pecent;
	}

	static function tag_product($first,$text='') {

		$return = '';

		//Tag ลดราคา กับ 1 แถม 1 ขึ้นก่อนทุกอัน
		$discount_percent = 0;
		if(@$first->discount_percent_group > $discount_percent) {
			$discount_percent = @$first->discount_percent_group;
		}
		if(@$first->discount_percent_list > $discount_percent) {
			$discount_percent = @$first->discount_percent_list;
		}
		if($discount_percent > 0) {
			//ลดราคา
			$return = '<div class="tagesales">SALE!</div>';
		}

		$discount_get_1_free_1_percent = 0;
		$buy 	= '';//ซื้อ
		$free 	= '';//แถม
		if(@$first->get_1_free_1) {
			$ex = explode(',',$first->get_1_free_1); //1.00*1.00,3.00*1.00
			foreach ($ex as $key => $value) {
				$ex_in = explode('*',$value);
				$cal = $ex_in[0]+$ex_in[1];
				$percent_one = 100 / $cal;
				$percent_discount_all = ($percent_one*$ex_in[1]);
				if($percent_discount_all > $discount_get_1_free_1_percent) {
					$discount_get_1_free_1_percent = $percent_discount_all;
					$buy  = $ex_in[0];
					$free = $ex_in[1];
				}
			}
		}
		if($discount_get_1_free_1_percent > $discount_percent) {
			if(@$buy == @$free) {
				//สินค้าซื้อ  แถม  //พื้นหลังแดง
				$return =  '
				<div class="tagFree-red">
					<div>ซื้อ<span>แถม</span></div><div>'.number_format(@$buy,0).'</div>
				</div>';
			} else if(@$buy != @$free) {
				$return =  '
				<div class="tagFree-red">
					<div>ซื้อ '.number_format(@$buy,0).'<span>แถม</span></div><div>'.number_format(@$free,0).'</div>
				</div>';
			}
		}


		if($return == '') {
			if(@$first->sale == 1) {
				//ลดราคา
				$return = '<div class="tagesales">SALE!</div>';
			} else if(@$first->product_newarrival_active == 1) {
				//สินค้ามาใหม่
				$return = '<div class="tagenew">NEW</div>';
			} else if(@$first->product_recommend_active == 1) {
				//สินค้าแนะนำ
				$return = '<div class="tagReccom">Recommend</div>';
			} else if(@$first->product_bestseller_active == 1) {
				//สินค้าขายดี
				$return = '<div class="tagebestsell"><span>BEST</span><br>SELLER</div>';
			} else if(@$first->product_award_active == 1) {
				//สินค้าได้รางวัล
				$return = '<div class="tagawards">AWARD</div>';
			} else if($text == 'free_red') {
				if(@$first->get == @$first->free && @$first->get != '') {
					//สินค้าซื้อ  แถม  //พื้นหลังแดง
					$return =  '
					<div class="tagFree-red">
						<div>ซื้อ<span>แถม</span></div><div>'.@$first->get.'</div>
					</div>';
				} else if(@$first->get != @$first->free && @$first->get != '' && @$first->free != '') {
					$return =  '
					<div class="tagFree-red">
						<div>ซื้อ '.@$first->get.'<span>แถม</span></div><div>'.@$first->free.'</div>
					</div>';
				}
			} else if($text == 'free_purple') {
				if(@$first->get == @$first->free && @$first->get != '') {
					//สินค้าซื้อ  แถม  //พื้นหลังม่วง
					$return =  '
					<div class="tagFree tagFree-purple">
						<div>ซื้อ<span>แถม</span></div><div>'.@$first->get.'</div>
					</div>';
				} else if(@$first->get != @$first->free && @$first->get != '' && @$first->free != '') {
					$return =  '
					<div class="tagFree tagFree-purple">
						<div>ซื้อ '.@$first->get.'<span>แถม</span></div><div>'.@$first->free.'</div>
					</div>';
				}
			} else if($text == 'free_pink') {
				if(@$first->get == @$first->free && @$first->get != '') {
					//สินค้าซื้อ  แถม  //พื้นหลังชมพู
					$return =  '
					<div class="tagFree tagFree-pink">
						<div>ซื้อ<span>แถม</span></div><div>'.@$first->get.'</div>
					</div>';
				} else if(@$first->get != @$first->free && @$first->get != '' && @$first->free != '') {
					$return =  '
					<div class="tagFree tagFree-pink">
						<div>ซื้อ '.@$first->get.'<span>แถม</span></div><div>'.@$first->free.'</div>
					</div>';
				}
			} else if($text == 'free_green') {
				if(@$first->get == @$first->free && @$first->get != '') {
					//สินค้าซื้อ  แถม  //พื้นหลังเขียวน้ำทะเล
					$return =  '
					<div class="tagFree tagFree-green">
						<div>ซื้อ<span>แถม</span></div><div>'.@$first->get.'</div>
					</div>';
				} else if(@$first->get != @$first->free && @$first->get != '' && @$first->free != '') {
					$return =  '
					<div class="tagFree tagFree-green">
						<div>ซื้อ '.@$first->get.'<span>แถม</span></div><div>'.@$first->free.'</div>
					</div>';
				}		

			} else {
				$return = '';
			}
		}

		return $return;
	}

	static function replace_space_to_underscore($num) {
		if($num) {
			$num = str_replace(' ','_',$num);
		} else {
			$num = 0;
		}
		return $num;
	}

	static function get_youtube_src($youtube_id, $mode = 'vdo') {

		$src = '';
		if($mode == 'vdo') {
            $src = 'https://www.youtube.com/embed/'.$youtube_id;
		} else if($mode == 'cover') {
			$src = 'https://img.youtube.com/vi/'.$youtube_id.'/hqdefault.jpg';
		} else if($mode == 'cover_maxhd') {
			$src = 'https://img.youtube.com/vi/'.$youtube_id.'/maxresdefault.jpg';
		}
	   	return $src;
	}

	static function get_youtube_id_from_url($url) {
	    if (stristr($url,'youtu.be/')) {
	        {preg_match('/(https:|http:|)(\/\/www\.|\/\/|)(.*?)\/(.{11})/i', $url, $final_ID); return $final_ID[4]; }
	    } else {
	        {@preg_match('/(https:|http:|):(\/\/www\.|\/\/|)(.*?)\/(embed\/|watch.*?v=|)([a-z_A-Z0-9\-]{11})/i', $url, $IDD); return $IDD[5]; }
	    }
	}

	
	static function merge_address($data = []) { //ไว้เชื่อมที่อยู่

		$example = [
			'address' 		=> '', //ที่อยู่
			'soi'			=> '', //ซอย
			'street'		=> '', //ถนน
			'sub_district'	=> '', //ตำบล/แขวง
			'district'		=> '', //อำเภอ/เขต
			'province'		=> '', //จังหวัด,
			'postcode'		=> '', //รหัสไปรษณีย์
			'country'		=> '' //ประเทศ
		];

		$prefix = [
			'address' 		=> '',
			'soi'			=> '', //ซอย
			'street'		=> '', //ถนน
			'sub_district'	=> '', //ตำบล/แขวง
			'district'		=> '', //อำเภอ/เขต
			'province'		=> '', //จังหวัด
			'postcode'		=> '', //รหัสไปรษณีย์
			'country'		=> ''  //ประเทศ
		];

		$return = [
			'choice' 		=> '', //ตัวเลือก Dropdown List
			'address_1'  	=> '',
			'address_2'  	=> '',
			'address_3'     => ''
		];

		if($data) {
			foreach ($data as $key => $value) {
				if($value) {
					if($data['address']) {
						if($value != '-') {
							$text = ($prefix[$key] ? $prefix[$key].' : ' : '').$value.' ';

							$return['choice'] .= $text;

							if(in_array($key,['address','soi','street'])) {
								$return['address_1'] .= $text;
							} else if(in_array($key,['sub_district','district','postcode'])) {
								$return['address_2'] .= $text;
							} else if(in_array($key,['province','country'])) {
								$return['address_3'] .= $text;
							}
						}
					}
				}
			}
		}
		
		return $return;//Array

	}

	static function connect_string($remark_arr = [], $connect_by = ' ') { //ไว้เชื่อม Remark ที่มี หลายตัวต่อดว้ย BR

		if($remark_arr) {
			foreach ($remark_arr as $key => $value) {
				if(!$value && $value != '0') {
					unset($remark_arr[$key]);
				}
			}
			$return = implode($connect_by,$remark_arr);
		} else {
			$return = '';
		}
		
		return $return;

	}

	static function date_add_or_del( $date, $symbol = '+', $day = 0) {

		if($date) {
			$date = date('d/m/Y', strtotime($symbol." ".$day." days", strtotime($date))); 
		}
		return $date;
	}

	static function s_datediff( $str_interval, $dt_menor, $dt_maior, $relative=false){ //ช่วงเวลาที่ต้องการ เช่น ห่างกันกี่ ชม. ห่างกันกี่วันเป็นต้น

       if( is_string( $dt_menor)) $dt_menor = date_create( $dt_menor);
       if( is_string( $dt_maior)) $dt_maior = date_create( $dt_maior);

       if(@$dt_menor && @$dt_maior) {
	       $diff = date_diff( $dt_menor, $dt_maior, ! $relative);
	       
	       switch( $str_interval){
	           case "y": 
	               $total = $diff->y + $diff->m / 12 + $diff->d / 365.25; break;
	           case "m":
	               $total= $diff->y * 12 + $diff->m + $diff->d/30 + $diff->h / 24;
	               break;
	           case "d":
	               $total = $diff->y * 365.25 + $diff->m * 30 + $diff->d + $diff->h/24 + $diff->i / 60;
	               break;
	           case "h": 
	               $total = ($diff->y * 365.25 + $diff->m * 30 + $diff->d) * 24 + $diff->h + $diff->i/60;
	               break;
	           case "i": 
	               $total = (($diff->y * 365.25 + $diff->m * 30 + $diff->d) * 24 + $diff->h) * 60 + $diff->i + $diff->s/60;
	               break;
	           case "s": 
	               $total = ((($diff->y * 365.25 + $diff->m * 30 + $diff->d) * 24 + $diff->h) * 60 + $diff->i)*60 + $diff->s;
	               break;
	          }

	        if($dt_menor > $dt_maior) {
	        	$op = '<span class="text-danger">-';
	        } else {
	        	$op = '<span class="text-success">';
	        }

	        if( $diff->invert) {
	            return $op.(-1 * $total).'<span>';
	        } else {
	        	return $op.$total.'<span>';
	        } 
	    }
    }

    static function s_datediff_cal( $str_interval, $dt_menor, $dt_maior, $relative=false){ //ช่วงเวลาที่ต้องการ เช่น ห่างกันกี่ ชม. ห่างกันกี่วันเป็นต้น

       if( is_string( $dt_menor)) $dt_menor = date_create( $dt_menor);
       if( is_string( $dt_maior)) $dt_maior = date_create( $dt_maior);

       $diff = date_diff( $dt_menor, $dt_maior, ! $relative);
       
       switch( $str_interval){
           case "y": 
               $total = $diff->y + $diff->m / 12 + $diff->d / 365.25; break;
           case "m":
               $total= $diff->y * 12 + $diff->m + $diff->d/30 + $diff->h / 24;
               break;
           case "d":
               $total = $diff->y * 365.25 + $diff->m * 30 + $diff->d + $diff->h/24 + $diff->i / 60;
               break;
           case "h": 
               $total = ($diff->y * 365.25 + $diff->m * 30 + $diff->d) * 24 + $diff->h + $diff->i/60;
               break;
           case "i": 
               $total = (($diff->y * 365.25 + $diff->m * 30 + $diff->d) * 24 + $diff->h) * 60 + $diff->i + $diff->s/60;
               break;
           case "s": 
               $total = ((($diff->y * 365.25 + $diff->m * 30 + $diff->d) * 24 + $diff->h) * 60 + $diff->i)*60 + $diff->s;
               break;
          }

        if( $diff->invert) {
            return (-1 * $total);
        } else {
        	return $total;
        } 
    }

	static function time_elapsed_string_show($datetime, $datetime_end = '', $sum_min = '0', $full = false) {
		### ใช้ที่หน้า Dashboard ช่างเตรียมวัตถุดิบ

		if(!$datetime_end) { //ยังไม่พักงาน นับจากวันที่ปัจจุบัน ถึงวันที่เริ่มถึงปัจจุบัน
	    	$now = new DateTime; //เวลาปัจจุบัน
	    	$ago = new DateTime($datetime); //เวลาที่เริ่มทำงาน น้อยกว่า now

	    	if($sum_min > 0) {
				$now->add(new DateInterval("PT".$sum_min."M")); //นำนาที Sum ใน DB เข้ามา + เพิ่มเพื่อให้แสดงนาทีที่ใช้จริง
			}
		} else {
			$now = new DateTime($datetime); //เวลาที่เริ่มทำงาน
			$ago = new DateTime($datetime_end); //เวลาที่สิ้นสุดงาน, หยุดงาน

			if($sum_min > 0) {
				$ago->add(new DateInterval("PT".$sum_min."M")); //นำนาที Sum ใน DB เข้ามา + เพิ่มเพื่อให้แสดงนาทีที่ใช้จริง
			}
		}

	    $diff = $now->diff($ago);

	    //return General::print_r_($diff);

	    $diff->w = floor($diff->d / 7);
	    $diff->d -= $diff->w * 7;

	    $string = array(
	        'y' => '<span class="text-danger">ปี</span>',
	        'm' => '<span class="text-danger">เดือน</span>',
	        'w' => '<span class="text-danger">สัปดาห์</span>',
	        'd' => '<span class="text-danger">วัน</span>',
	        'h' => '<span class="text-danger">ชม.</span>',
	        'i' => '<span class="text-danger">น.</span>',
	        's' => 'วินาที',
	    );
	    foreach ($string as $k => &$v) {
	        if ($diff->$k) {
	            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? '' : '');
	        } else {
	            unset($string[$k]);
	        }
	    }

	    unset($string['s']); //ซ่อนวินาที

	    if (!$full) $string = array_slice($string, 0, 1);
	    return $string ? implode('<br/>', $string) . ' ' : '';
	}

	static function time_elapsed_string($datetime, $full = false) {
	    $now = new DateTime;
	    $ago = new DateTime($datetime);
	    $diff = $now->diff($ago);

	    $diff->w = floor($diff->d / 7);
	    $diff->d -= $diff->w * 7;

	    $string = array(
	        'y' => 'ปี',
	        'm' => 'เดือน',
	        'w' => 'สัปดาห์',
	        'd' => 'วัน',
	        'h' => 'ชั่วโมง',
	        'i' => 'นาที',
	        's' => 'วินาที',
	    );
	    foreach ($string as $k => &$v) {
	        if ($diff->$k) {
	            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? '' : '');
	        } else {
	            unset($string[$k]);
	        }
	    }

	    if (!$full) $string = array_slice($string, 0, 1);
	    return $string ? implode(', ', $string) . 'ที่แล้ว' : 'เมื่อครู่นี้';
	}

	
	static function check_image($mime_type) {
		$allowedMimeTypes = ['image/jpeg','image/gif','image/png','image/bmp','image/svg+xml'];
		$return = '';

		if(@in_array($mime_type, $allowedMimeTypes) ){
			$return = true;	
		} else {
			$return = false;
		}
		return $return;
	}

	static function genrevise_insert($reviselast) {
		$return = '';

		if($reviselast) {
			$return = $reviselast+1;	
		} else {
			$return = '00';
		}
		return General::str_pad_num($return,2);
	}

	static function gencode_lot($codelast) { //62/1 
		$return = '';

		$year_current = General::mb_substr_((date('Y')+543),2,2); //2 Digit พศ. 2562  = 62 

		//62/1  //โชว์ 62/1 
		if($codelast) {
			$ex = explode('-',$codelast);
			$year = @$ex[0];
			$run = @$ex[1];

			if($year == $year_current) {
				$return = $year.'-'.($run+1);
			} else {
				$return = $year_current.'-1';
			}
		} else {
			$return = $year_current.'-1';
		}
		return $return;
	}

	static function gencode_insert($codelast, $prefix = '', $run_digit = 4) {
		$return = '';

		if($codelast) {
			$length = General::mb_strlen_($codelast);
			$minus = $run_digit + 4;
			$prefix_code   = General::mb_substr_($codelast,0,($length-$minus));
			$prefix_ym   = General::mb_substr_($codelast,General::mb_strlen_($prefix_code),4); 
			if($prefix_ym == date('ym')) {
				$prefix = General::mb_substr_($codelast,0,($length-$run_digit));
				$no     = General::mb_substr_($codelast,($length-$run_digit),$length)+1;
				$return = $prefix.General::str_pad_num($no,$run_digit);
			} else {
				$return = $prefix_code.date('ym').General::str_pad_num('1',$run_digit);
			}		
		} else {
			$return = $prefix.date('ym').General::str_pad_num('1',$run_digit);
		}
		return $return;
	}

	static function gencode($prefix, $no, $run_digit = 4) {
		$return = '';
		if($prefix) {
			$return = $prefix.date('y').date('m').General::str_pad_num($no,$run_digit);
		}
		return $return;
	}


	static function send_sms($username, $password, $msisdn, $message, $sender = "COE Alert!", $ScheduledDelivery = "", $force = "premium") {
	    $url = "http://www.thaibulksms.com/sms_api.php";
	    if (extension_loaded('curl')) {
	        $data = array('username' => $username, 'password' => $password, 'msisdn' => $msisdn, 'message' => $message, 'sender' => $sender, 'ScheduledDelivery' => $ScheduledDelivery, 'force' => $force);
	        $data_string = http_build_query($data);
	        $agent = "ThaiBulkSMS API PHP Client";
	        $ch = curl_init();
	        curl_setopt($ch, CURLOPT_URL, $url);
	        curl_setopt($ch, CURLOPT_USERAGENT, $agent);
	        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
	        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
	        $xml_result = curl_exec($ch);
	        $code = curl_getinfo($ch);
	        curl_close($ch);
	        if ($code['http_code'] == 200) {
	            if (function_exists('simplexml_load_string')) {
	                $sms = new \SimpleXMLElement($xml_result);
	                $count = count($sms->QUEUE);
	                if ($count > 0) {
	                    $count_pass = 0;
	                    $count_fail = 0;
	                    $used_credit = 0;
	                    for ($i = 0; $i < $count; $i++) {
	                        if ($sms->QUEUE[$i]->Status) {
	                            $count_pass++;
	                            $used_credit += $sms->QUEUE[$i]->UsedCredit;
	                        } else {
	                            $count_fail++;
	                        }
	                    }
	                    if ($count_pass > 0) {
	                        $msg_string = "สามารถส่งออกได้จำนวน {$count_pass} หมายเลข, ใช้เครดิตทั้งหมด {$used_credit} เครดิต";
	                    }
	                    if ($count_fail > 0) {
	                        $msg_string = "ไม่สามารถส่งออกได้จำนวน {$count_fail} หมายเลข";
	                    }
	                } else {
	                    $msg_string = "เกิดข้อผิดพลาดในการทำงาน, (" . $sms->Detail . ")";
	                }
	            } else {
	                if (function_exists('xml_parse')) {
	                    $xml = sms::xml2array($xml_result);
	                    $count = count($xml['SMS']['QUEUE']);
	                    if ($count > 0) {
	                        $count_pass = 0;
	                        $count_fail = 0;
	                        $used_credit = 0;
	                        for ($i = 0; $i < $count; $i++) {
	                            if ($xml['SMS']['QUEUE'][$i]['Status']) {
	                                $count_pass++;
	                                $used_credit += $xml['SMS']['QUEUE'][$i]['UsedCredit'];
	                            } else {
	                                $count_fail++;
	                            }
	                        }
	                        if ($count_pass > 0) {
	                            $msg_string = "สามารถส่งออกได้จำนวน {$count_pass} หมายเลข, ใช้เครดิตทั้งหมด {$used_credit} เครดิต";
	                        }
	                        if ($count_fail > 0) {
	                            $msg_string = "ไม่สามารถส่งออกได้จำนวน {$count_fail} หมายเลข";
	                        }
	                    } else {
	                        $msg_string = "เกิดข้อผิดพลาดในการทำงาน, (" . $xml['SMS']['Detail'] . ")";
	                    }
	                } else {
	                    $msg_string = "เกิดข้อผิดพลาดในการทำงาน: <br /> ระบบไม่รองรับฟังก์ชั่น XML";
	                }
	            }
	        } else {
	            //$http_codes = parse_ini_file("http_code.ini");
	            //$msg_string = "เกิดข้อผิดพลาดในการทำงาน: <br />" . $code['http_code'] . " " . $http_codes[$code['http_code']];
	            $msg_string = "เกิดข้อผิดพลาดในการทำงาน: <br />" . $code['http_code'];
	        }
	    } else {
	        if (function_exists('fsockopen')) {
	            $msg_string = $this->sending_fsock($username, $password, $msisdn, $message, $sender, $ScheduledDelivery, $force);
	        } else {
	            $msg_string = "cURL OR fsockopen is not enabled";
	        }
	    }
	    return $msg_string;
	}

	static function encode($string,$key = 'xxx') {
		$j = 0;
		$hash = '';
	    $key = sha1($key);
	    $strLen = strlen($string);
	    $keyLen = strlen($key);
	    for ($i = 0; $i < $strLen; $i++) {
	        $ordStr = ord(substr($string,$i,1));
	        if ($j == $keyLen) { $j = 0; }
	        $ordKey = ord(substr($key,$j,1));
	        $j++;
	        $hash .= strrev(base_convert(dechex($ordStr + $ordKey),16,36));
	    }
	    return $hash;
	}

	static function decode($string,$key = 'xxx') {
		$j = 0;
		$hash = '';
	    $key = sha1($key);
	    $strLen = strlen($string);
	    $keyLen = strlen($key);
	    for ($i = 0; $i < $strLen; $i+=2) {
	        $ordStr = hexdec(base_convert(strrev(substr($string,$i,2)),36,16));
	        if ($j == $keyLen) { $j = 0; }
	        $ordKey = ord(substr($key,$j,1));
	        $j++;
	        $hash .= chr($ordStr - $ordKey);
	    }
	    return $hash;
	}

	static function formatSizeUnits($bytes) {
        if ($bytes >= 1073741824) {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            $bytes = number_format($bytes / 1024, 2) . ' kB';
        } elseif ($bytes > 1) {
            $bytes = $bytes . ' bytes';
        } elseif ($bytes == 1) {
            $bytes = $bytes . ' byte';
        } else {
            $bytes = '0 bytes';
        }

        return $bytes;
	}


	static function stock_check_return($qty = 0) { //เช็คว่าถ้าเป็นลบจำนวนสินค้า แปลงด้านหน้าเป็น + และกรณีเป็น + ให้นำไป -
		$q = explode('-',$qty);
		
		$qty_gen = [];
		if(count($q) == 2) {
			$qty_gen['operation'] = '+';
			$qty_gen['qty']  = $qty[1]; //นำ - ออก
		} else if(count($q) == 1) {
			$qty_gen['operation'] = '-';
			$qty_gen['qty'] = '-'.$q[0];
		} else {
			$qty_gen['operation'] = '';
			$qty_gen['qty'] = $qty;
		}
    	
    	return $qty_gen;
    }


	static function stock_check_del($type = '', $qty = 0) { //เช็คว่าถ้าเป็นลบจำนวนสินค้าให้ปรับ qty ด้านหน้าต้องมี เครื่องหมาย - และถ้าเป็น add ให้นำ - ออก
		$q = explode('-',$qty);
		$qty_gen = '';
		if($type == 'del') {
			if(count($q) == 2) {
				$qty_gen = $qty;
			} else if(count($q) == 1) {
				$qty_gen = '-'.$q[0];
			}
		} else if($type == 'add') {
			if(count($q) == 1) {
				$qty_gen = $qty;
			} else if(count($q) == 2) {
				$qty_gen = $q[1];
			}
		} else {
			$qty_gen = $qty;
		}
    	
    	return $qty_gen;
    }

    static function print_r_($Arr) {
    	echo '<pre>';
    	echo print_r($Arr);
    	echo '</pre>';
    }

    static function generateRandomString($length = 10) {
	    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	    $charactersLength = strlen($characters);
	    $randomString = '';
	    for ($i = 0; $i < $length; $i++) {
	        $randomString .= $characters[rand(0, $charactersLength - 1)];
	    }
	    return $randomString;
	}

    static function convert_period_promotion_to_between($date = 0) {
    	if($date) {
    		$ex = explode(' ',$date);
    		$date = $ex[0];
    		$time = $ex[1];

    		$ex = explode('/',$date);
    		$y = $ex[2];
    		$m = $ex[1];
    		$d = $ex[0];

    		$date = $y.'-'.$m.'-'.$d.' '.$time; 
    	}

    	return $date;
    }

    static function convert_period_between_to_promotion($date = 0) {
    	if($date) {
    		$ex = explode(' ',$date);
    		$date = $ex[0];
    		$time = $ex[1];

    		$ex = explode('-',$date);
    		$y = $ex[0];
    		$m = $ex[1];
    		$d = $ex[2];

    		$date = $d.'/'.$m.'/'.$y.' '.$time; 
    	} 

    	return $date;
    }

    static function gen_short_month($month_num = 0, $lang='en') {
    	$month_num = str_pad($month_num,2,'0',STR_PAD_LEFT);
    	if($lang == 'en') {
	    	$month_text = array(
	    		'0'  => '',
	    		'01' => 'JAN',
	    		'02' => 'FEB',
	    		'03' => 'MAR',
	    		'04' => 'APR',
	    		'05' => 'MAY',
	    		'06' => 'JUN',
	    		'07' => 'JUL',
	    		'08' => 'AUG',
	    		'09' => 'SEP',
	    		'10' => 'OCT',
	    		'11' => 'NOV',
	    		'12' => 'DEC',
	    	);
    	} else if($lang == 'th') {
    		$month_text = array(
	    		'0'  => '',
	    		'01' => 'ม.ค.',
	    		'02' => 'ก.พ.',
	    		'03' => 'มี.ค.',
	    		'04' => 'เม.ย.',
	    		'05' => 'พ.ค.',
	    		'06' => 'มิ.ย.',
	    		'07' => 'ก.ค.',
	    		'08' => 'ส.ค.',
	    		'09' => 'ก.ย.',
	    		'10' => 'ต.ค.',
	    		'11' => 'พ.ย.',
	    		'12' => 'ธ.ค.',
	    	);
    	}

	    return @$month_text[$month_num];
	}

	static function gen_full_month($month_num = 0, $lang='en') {
    	$month_num = str_pad($month_num,2,'0',STR_PAD_LEFT);
    	if($lang == 'en') {
	    	$month_text = array(
	    		'0'  => '',
	    		'01' => 'January',
	    		'02' => 'February',
	    		'03' => 'March',
	    		'04' => 'April',
	    		'05' => 'May',
	    		'06' => 'June',
	    		'07' => 'July',
	    		'08' => 'August',
	    		'09' => 'September',
	    		'10' => 'October',
	    		'11' => 'November',
	    		'12' => 'December',
	    	);
	    } else if($lang == 'th') {
	    	$month_text = array(
	    		'0'  => '',
	    		'01' => 'มกราคม',
	    		'02' => 'กุมภาพันธ์',
	    		'03' => 'มีนาคม',
	    		'04' => 'เมษายน',
	    		'05' => 'พฤษภาคม',
	    		'06' => 'มิถุนายน',
	    		'07' => 'กรกฎาคม',
	    		'08' => 'สิงหาคม',
	    		'09' => 'กันยายน',
	    		'10' => 'ตุลาคม',
	    		'11' => 'พฤศจิกายน',
	    		'12' => 'ธันวาคม',
	    	);
	    }

	    return @$month_text[$month_num];
	}

    static function preg_img($data) {
    	preg_match_all('/<img[^>]+>/i',$data, $result); 
    	return $result;
    }

	static function gen_date_between($date, $year = '0') {
		if($date) {
			$ex     = explode('/', $date);
			$month  = str_pad($ex[1],2,'0',STR_PAD_LEFT);
			$day 	= str_pad($ex[0],2,'0',STR_PAD_LEFT);
			$return = ($ex[2]+$year).'-'.$month.'-'.$day;
		} else {
			$return = $date;
		}
		return $return;
	}

	static function gen_date($date, $year = '0') {
		if($date) {
			$ex     = explode('-', $date);
			$month  = str_pad($ex[1],2,'0',STR_PAD_LEFT);
			$day 	= str_pad($ex[2],2,'0',STR_PAD_LEFT);
			$return = $day.'/'.$month.'/'.($ex[0]+$year);
		} else {
			$return = $date;
		}
		return $return;
	}

	static function gen_date_y2digit($date, $year = '0',$lang = 'en') { //en,th
		if($date) {
			$ex     = explode('-', $date);
			$month  = str_pad($ex[1],2,'0',STR_PAD_LEFT);
			$day 	= str_pad($ex[2],2,'0',STR_PAD_LEFT);
			$return = $day.'/'.$month.'/';
			if($lang == 'en') {
				$yy = ($ex[0]+$year);
			} else if($lang == 'th') {
				$yy = ($ex[0]+$year)+543;
			}
			$return .= General::mb_substr_($yy,2,2);
		} else {
			$return = $date;
		}
		return $return;
	}

	static function gen_date_not_switching($date, $year = '0') {
		if($date) $ex  = explode('-', $date);
		if(count($ex) == 3) {
			$month  = str_pad($ex[1],2,'0',STR_PAD_LEFT);
			$day 	= str_pad($ex[0],2,'0',STR_PAD_LEFT);
			$return = $day.'/'.$month.'/'.($ex[2]+$year);
		} else {
			$return = $date;
		}
		return $return;
	}

	static function gen_date_switching($date, $year = '0') {
		if($date) $ex  = explode('-', $date);
		if(count($ex) == 3) {
			$month  = str_pad($ex[1],2,'0',STR_PAD_LEFT);
			$day 	= str_pad($ex[2],2,'0',STR_PAD_LEFT);
			$return = $day.'/'.$month.'/'.($ex[0]+$year);
		} else {
			$return = $date;
		}
		return $return;
	}

	static function gen_date_between_import($date, $year = '0') {
		if($date) {
			$ex     = explode('-', $date);
			$month  = str_pad($ex[0],2,'0',STR_PAD_LEFT);
			$day 	= str_pad($ex[1],2,'0',STR_PAD_LEFT);
			$return = '20'.($ex[2]+$year).'-'.$month.'-'.$day;
		} else {
			$return = $date;
		}
		return $return;
	}

	static function gen_date_import($date, $year = '0') {
		if($date) {
			$ex     = explode('-', $date);
			$month  = str_pad($ex[0],2,'0',STR_PAD_LEFT);
			$day 	= str_pad($ex[1],2,'0',STR_PAD_LEFT);
			$return = $day.'/'.$month.'/20'.($ex[2]+$year);
		} else {
			$return = $date;
		}
		return $return;
	}

	static function gen_date_showtime($date, $year = '0') {
		if($date) {
			$t = strtotime($date);
			$y = date('Y',$t);
			$month = date('m',$t);
			$day = date('d',$t);
			$return = $day.'/'.$month.'/'.($y+$year).' '.date('H:i:s', $t);
		} else {
			$return = $date;
		}
		return $return;
	}

	static function gen_date_showtime_HI($date, $year = '0', $slash='') {
		if($date) {
			$t = strtotime($date);
			$y = date('Y',$t);
			$month = date('m',$t);
			$day = date('d',$t);
			$return = $day.'/'.$month.'/'.($y+$year).' '.$slash.' '.date('H:i', $t);
		} else {
			$return = $date;
		}
		return $return;
	}

	static function gen_date_show($date, $year = '0') {
		if($date) {
			$t = strtotime($date);
			$y = date('Y',$t);
			$month = date('m',$t);
			$day = date('d',$t);
			$return = $day.'/'.$month.'/'.($y+$year);
		} else {
			$return = $date;
		}
		return $return;
	}

	static function gen_cut_time($date) {
		if($date) {
			$ex     = explode(' ', $date);
			$return = $ex[0];
		} else {
			$return = $date;
		}
		return $return;
	}

	static function gen_cut_date($date) {
		if($date) {
			$ex     = explode(' ', $date);
			$return = $ex[1];
		} else {
			$return = $date;
		}
		return $return;
	}

	static function find_range_date($date_start, $date_end) {
		### ต้องรับเป็น Format Y-m-d เท่านั้น
		if($date_start && $date_end) {
			$ds = strtotime($date_start);
			$dt = strtotime($date_end);

			$date1 = date_create(date('Y-m-d',$ds));
			$date2 = date_create(date('Y-m-d',$dt));
			$diff  = date_diff($date2,$date1);
			return $diff->format("%R%a");
		} else {
			return 0;
		}
	}

	static function checkbox_data($str) {
		if(!empty($str)) {
			return 1;
		} else {
			return 0;
		}
	}

	static function clear_comma($num) {
		if($num) {
			$num = str_replace(',','',$num);
		} else {
			$num = 0;
		}
		return $num;
	}


	static function show_null($num) {
		if($num == 0) {
			$num = '';
		} 
		return $num;
	}

	static function isNumeric($str) {
		return (!is_numeric($str)) ? FALSE : TRUE;
	} 

	static function number_format_($str,$limit=2,$show=0) {
		if(!General::isNumeric($str) || $str == 0){
			//return 'ไม่ใช่ตัวเลข';
			if($show == 0) {
				if($limit == 2) {
					return '0.00';
				} else {
					return '0';
				}
			} else if($show == 1){
				return '';
			}
		} else {
			if($show == 0) {
				return @number_format(@$str,$limit);
			} else if($show == 1) {
				$ex = explode('.',@number_format(@$str,$limit));
				if(@$ex[1] == 00) {
					return $ex[0];
				} else {
					return $ex[0].'.'.$ex[1];
				}
			} else {
				return $str;
			}
		}
	} 

	static function number_format_show($str,$limit=2,$show=0) {
		if(!General::isNumeric($str) || $str == 0){
			//return 'ไม่ใช่ตัวเลข';
			if($show == 0) {
				if($limit == 2) {
					return '0';
				} else {
					return '0';
				}
			} else if($show == 1){
				return '';
			}
		} else {
			$num = number_format($str,$limit);
			$ex = @explode('.',$num);
			if(count($ex) == 2) {
				if($ex[1] == '00') {
					$return = $ex[0];	
				} else {
					$return = $num;
				}
			} else {
				$return = $num;
			}
			return $return;
		}
	} 

	static function clear_digit($str) {
		$ex = @explode('.',$str);
		if(count($ex) == 2) {
			if($ex[1] == '00') {
				$return = $ex[0];	
			} else {
				$return = $str;
			}
		} else {
			$return = $str;
		}
		return $return;
	} 

	static function mb_substr_($str,$a,$b) {
		return mb_substr($str,$a,$b,'UTF-8');
	}

	static function mb_strlen_($str) {
		return mb_strlen($str,'UTF-8');
	}

	static function ClaimDays($day1,$day2) { //หาจำนวนวันที่ห่างกัน
		return round((strtotime($day1)-strtotime($day2))/(24*60*60),0); //แปลงค่าเวลารูปแบบ Unix timestamp ให้เป็นจำนวนวัน
	}

	static function empty_($str) {
		if(!empty($str)) {
			return $str;
		} else {
			return '-';
		}
	}

	static function null_($str) {
		if(!empty($str) || $str == 0) {
			return $str;
		} else {
			return '';
		}
	}

	static function null_show($str) {
		if(!empty($str)) {
			return $str;
		} else {
			return '-';
		}
	}

	static function null_pdf($str) {
		if($str > 0) {
			return $str;
		} else {
			return '';
		}
	}

	static function Text_ucFirst($str) {
		if(!empty($str)) {
			return ucfirst(strtolower($str));
		} else {
			return '';
		}
	}
	
	static function CountDay($start,$end) {
		if($start && $end) {
			return round((strtotime($end)-strtotime($start))/(24*60*60),0);
		} else {
			return 0;
		}
	}

	static function PlusDay($day,$no=0) {
		if($day) {
			$no = $no*86400;
			return date('Y-m-d', (strtotime($day)+$no));
		} else {
			return 0;
		}
	}

	static function Last_Days_Month($month, $year) {
		if($month && $year) {
			return cal_days_in_month(CAL_GREGORIAN, $month, $year); 
		} else {
			return 0;
		}
	}

	static function weekOfMonth($Date) {
	    $dt = strtotime($Date);
	    $day   = date('j',$dt);
	    $month = date('m',$dt);
	    $year  = date('Y',$dt);
	    $totalDays = date('t',$dt);
	    $weekCnt = 1;
	    $retWeek = 0;
	    for($i=1;$i<=$totalDays;$i++) {
	        $curDay = date("N", mktime(0,0,0,$month,$i,$year));
	        if($curDay==7) {
	            if($i==$day) {
	                $retWeek = $weekCnt+1;
	            }
	            $weekCnt++;
	        } else {
	            if($i==$day) {
	                $retWeek = $weekCnt;
	            }
	        }
	    }
	    return $retWeek;
	}

	static function MatchDayOfMonth($Date, $MatchDay) {
		$dt = strtotime($Date);
		$day   = date('j',$dt);
		$month = date('m',$dt);
		$year  = date('Y',$dt);
		$totalDays = date('t',$dt);
		$DayCnt = 0;
		for($i=1;$i<=$day;$i++) {
			$curDay = date("N", mktime(0,0,0,$month,$i,$year));
			if($curDay==$MatchDay) {
				$DayCnt++;
			}
		}
		return $DayCnt;
	}

	static function getStartAndEndDate($week, $year) {
	  $dto = new DateTime();
	  $dto->setISODate($year, $week);
	  $ret['week_start'] = $dto->format('Y-m-d');
	  $dto->modify('+6 days');
	  $ret['week_end'] = $dto->format('Y-m-d');
	  return $ret;
	}

	static function getWeek_Year($date) {
		if($date) {
			$Week = strtotime($date);
			$Week = date('W',$Week);
		} else {
			$Week = '';
		}
        return $Week;
	}

	static function getDayNo_Week($date) {
		if($date) {
			$DayNo = strtotime($date);
			$DayNo = date('N',$DayNo);
		} else {
			$DayNo = '';
		}
        return $DayNo;
	}

	static function Null_Zero($num) {
		if(!$num) {
			$num = '0';
		}
        return $num;
	}

	static function str_pad_num($num, $position) {
		if($num) {
			$num = str_pad($num,$position,"0",STR_PAD_LEFT);
		} 
        return $num;
	}

	static function str_pad_num_zero($num, $position) {
		if($num || $num == '0') {
			$num = str_pad($num,$position,"0",STR_PAD_LEFT);
		} 
        return $num;
	}

	static function problem_type_($exp, $data, $data_2) {
		$text = '';
		if($exp && $data) {
			$data_2 = explode($exp,$data_2);
			$ex = explode($exp,$data);
			$x = 0;
			if($ex) {
				foreach($ex As $key => $e) {
					if($e) {
						if($x > 0 && $e) $text .= ', ';
						$text .= $e.' : '.$data_2[$key];
						$x++;
					}
				}
			}
		}
		return htmlspecialchars($text);
	}

	static function strtoupper_($str) { //ตัวใหญ่ทั้งหมด
		if($str) {
			$str = strtoupper($str);
		} 
        return $str;
	}

	static function user_ip() {
		//find user ip
		if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$output = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
			$output = $_SERVER['HTTP_CLIENT_IP'];
		} else {
			$output = $_SERVER['REMOTE_ADDR'];
		}
		return $output;
	}// user_ip

	static function user_ip_country() {
		$ip = General::user_ip();

		$urlWithoutProtocol = "http://ipinfo.io/".$ip."?token=89331599924263";
	    $request         = "";
	    $isRequestHeader = false;
	 
	    $exHeaderInfoArr   = array();
	    $exHeaderInfoArr[] = "Content-type: application/json";
	 
	    $ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL, $urlWithoutProtocol);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    $response = curl_exec($ch);
	    curl_close($ch);

	    if($response) {
	    	$response = json_decode($response);
	    	if(@$response->ip == '127.0.0.1') {
	    		return 'TH';	
	    	} else {
	    		return @$response->country; //US, TH 
	    	}
	    } else {
	  		return 'TH';
	  	}
	}

	static function replace_url($url) {
		$url = str_replace('%20',' ',$url);
		return $url;
	}

	static function postJSON_shipping_api($postData){

	    $url = "https://api.josoco.com/post";

	    $ch = curl_init($url);

	    $data_string = json_encode($postData);

	    curl_setopt($ch, CURLOPT_POST, true);

	    curl_setopt($ch, CURLOPT_POSTFIELDS, array("shipping"=>$data_string));

	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	    $output = curl_exec ($ch); // execute

	    if($output === false) {

	        echo "Error Number:".curl_errno($ch)."<br>";

	        echo "Error String:".curl_error($ch);

	    } else {

	    	curl_close($ch);

	    	return $output;

	    }
  	} /* ##### สิ้นสุด Function ##### */


  	//ธนาคารแห่งประเทศไทย
  	static function get_currency_bot_to_thb($from_Currency = 'USD') {
	    $data = [];
	    $from_Currency = urlencode($from_Currency);
	    $to_Currency = urlencode('THB');

	    $url = "https://iapi.bot.or.th/Stat/Stat-ExchangeRate/DAILY_AVG_EXG_RATE_V1/?start_period=".date('Y-m-d',(strtotime(date('Y-m-d'))-86400))."&end_period=".date('Y-m-d')."&currency=$from_Currency";

	    if($from_Currency != 'THB') {
		    $ch = curl_init();
		    $timeout = 0;
		    curl_setopt ($ch, CURLOPT_URL, $url);
		    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
		    curl_setopt ($ch, CURLOPT_USERAGENT,
		                 "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1)");

		    curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
		    curl_setopt ($ch, CURLOPT_HTTPHEADER,array('api-key: U9G1L457H6DCugT7VmBaEacbHV9RX0PySO05cYaGsm'));
		    $rawdata = curl_exec($ch);
		    curl_close($ch);
		    if($rawdata) { 
		    	$raw = json_decode($rawdata);
			    $thb = $raw->result->data->data_detail[0]->selling;
			} else {
				$thb = 1;
			}
		} else {
			$thb = 1;
		}

	    return round($thb, 2);
	}

  	static function get_currency($from_Currency = 'USD', $to_Currency = 'THB', $amount = '1') {
	    $data = [];
	    $amount = urlencode($amount);
	    $from_Currency = urlencode($from_Currency);
	    $to_Currency = urlencode($to_Currency);

	    $url = "http://www.google.com/finance/converter?a=$amount&from=$from_Currency&to=$to_Currency";

	    $ch = curl_init();
	    $timeout = 0;
	    curl_setopt ($ch, CURLOPT_URL, $url);
	    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
	    curl_setopt ($ch, CURLOPT_USERAGENT,
	                 "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1)");

	    curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
	    $rawdata = curl_exec($ch);
	    curl_close($ch);
	    if($rawdata) { 
		    $data = explode('bld>', $rawdata);
		    $data = explode($to_Currency, $data[1]);
		} else {
			return General::get_currency($from_Currency, $to_Currency, $amount);
		}

	    return round($data[0], 2);
	}

	function get_currency_bbk($from_Currency, $to_Currency, $amount) {
	    $amount = urlencode($amount);
	    $from_Currency = urlencode($from_Currency);
	    $to_Currency = urlencode($to_Currency);

	    $url = "http://www.bangkokbank.com/UserControls/ExchangeRates/FxCalculateService.asmx/FxCal";

	    $ch = curl_init();
	    $timeout = 0;
	    curl_setopt ($ch, CURLOPT_URL, $url);

	    $data = array("amount" => $amount, "currency1" => $from_Currency, "currency2" => $to_Currency);
	    $data_string = json_encode($data);                

	    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string); 
	    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
	    curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                         
	        'Content-Type: application/json; charset=UTF-8',                                                    
	        'Content-Length: ' . strlen($data_string))                                
	    );     

	    curl_setopt ($ch, CURLOPT_USERAGENT,
	                 "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1)");
	    curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
	    $rawdata = curl_exec($ch);
	    curl_close($ch);
	    
	    $return = json_decode($rawdata, true);
	    return $return['d'];
	}

	static function show_price_by_lang($price, $lang, $exchange_rate) {
		$show_price = 0;
		if($lang == 'en') {
			$show_price = $price / $exchange_rate;
		} else {
			$show_price = $price;
		}

		return General::clear_comma(General::number_format_($show_price,2));
	}

	static function using_http($link,$mode = 'http') { 
		$return = $link;
		if($link) {
			if(count(explode('http',$link)) == 1) {
				$return = $mode.'://'.$link;
			}
		}

		return $return;
	}

	static function get_exchange_rate_kbank(){

	    $url = "https://api.josoco.com/exchange/rate";

	    $ch = curl_init($url);

	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	    $output = curl_exec ($ch); // execute

	    if($output === false) {

	        echo "Error Number:".curl_errno($ch)."<br>";

	        echo "Error String:".curl_error($ch);

	    } else {

	    	curl_close($ch);

	    	return round($output,2);

	    }
  	}

  	static function check_utf8_bom($string){
		$bom = pack("CCC", 0xef, 0xbb, 0xbf);
		if (0 === strncmp($string, $bom, 3)) {
		    return true;
		}else{
			return false;
		}
	}


	static function remove_utf8_bom_head($text) {
	    if(substr(bin2hex($text), 0, 6) === 'efbbbf') {
	        $text = substr($text, 3);
	    }
	    return $text;
	}

	static function jason_validate($string) {
		if(is_string($string)){
			@json_decode($string);
			return (json_last_error() === JSON_ERROR_NONE);
		}
		return false;
	}


}

?>
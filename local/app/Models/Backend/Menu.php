<?php

namespace App\Models\Backend;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
  protected $table = 'ck_backend_menu';
  public $timestamps = false;

  public function setMenu($path)
  {
    // echo \Auth::user()->permission;
    $setMenu = array();
    $txtMenu = '';
    $pid = array();
    $row = \Cache::remember('Menu-'.\Auth::user()->id, 10, function() {
      $Menu = Menu::where('isActive', '<>', 'N')->orderBy('sort', 'asc')->orderBy('id', 'asc');
      return $Menu->get();
    });
    if( $row ){
      foreach( $row AS $r ){

        // echo $r->id;

        if( !isset($setMenu[$r->ref][$r->id]) )
        {
          $active = '';
          if( substr_count($r->url, '/') == 0 ){
            $active = $r->url==$path?'Y':'N';
          }else{
            $active = substr_count($path, (empty($r->url)?'1':$r->url)) > 0?'Y':'N';
          }
          $setMenu[$r->ref][] = (object) 
          ['id'=>$r->id,'name'=>$r->name,'link'=>$r->url,'icon'=>$r->icon,'active'=>$active,'ref2'=>$r->ref2,'menu_level'=>$r->menu_level];
          
        }
      }
    }

  //   echo "<pre>";

  // print_r($setMenu);

 $MenuPermission = \App\Models\MenuPermissionModel::where('admin_id',\Auth::user()->id)->get();


$arr_menu_id = [];
foreach ($MenuPermission as $key => $value) {
  # code...
  // echo @$value->main_menu_id;
  array_push($arr_menu_id, $value->main_menu_id);
}


// $arr_2 = implode(',', $arr_menu_id);
// echo $arr_2;

// if(in_array('1', $arr_menu_id)){
//   echo "EEEEEEEEEEEE";
// }


    if( $setMenu[0] ){
      /**
       *---------------------------------------------------------------------------------------------------------------------------------------------------------
       */
      foreach( $setMenu[0] AS $key => $mMenu ){

        $subMenu  = '';
        $subActive  = '';

        if( empty($setMenu[$mMenu->id]) ){

            
            if(\Auth::user()->permission==1){
                 $txtMenu .= '
                      <li>
                          <a href="'.asset($mMenu->link).'" class=" waves-effect">
                              <i class="'.$mMenu->icon.'"></i>
                              <span>'.$mMenu->name.'  </span>
                          </a>
                      </li>';
            }else{

              if(in_array($mMenu->id, $arr_menu_id)){
                $txtMenu .= '
                      <li>
                          <a href="'.asset($mMenu->link).'" class=" waves-effect">
                              <i class="'.$mMenu->icon.'"></i>
                              <span>'.$mMenu->name.'  </span>
                          </a>
                      </li>';
              }

            }

           

        }else{
    

          foreach( $setMenu[$mMenu->id] AS $sMenu ){


            if( empty($setMenu[$sMenu->id]) ){


              if(\Auth::user()->permission==1 ){

                     if($sMenu->ref2==0 && $sMenu->menu_level==2){
	              		
	                    	$subMenu .= '       
		                           <li><a href="javascript: void(0);" class="has-arrow">'.$sMenu->name.' </a>';

		                           foreach( $setMenu[$mMenu->id] AS $sMenu2 ){

		                           		if($sMenu->id==$sMenu2->ref2){
									  		$subMenu .= '
									            <ul class="sub-menu" aria-expanded="true">
								                    <li><a href="'.asset($sMenu2->link).'">'.$sMenu2->name.'</a></li>
								                </ul>';
								        }

								   }
								

		                $subMenu .= '</li>';

	            	}else{

	            		if($sMenu->menu_level==1){
		            		$subMenu .= '<li><a href="'.asset($sMenu->link).'">'.$sMenu->name.' </a></li>';
			                  if(in_array($sMenu->id, $arr_menu_id)){
			                    $subMenu .= '<li><a href="'.asset($sMenu->link).'">'.$sMenu->name.'  </a></li>';
			                  }
		              	}

	            	}
                    

              }else{

	                  if(in_array($sMenu->id, $arr_menu_id)){

	                      if($sMenu->ref2==0 && $sMenu->menu_level==2){
	              		
	                    	$subMenu .= '       
		                           <li><a href="javascript: void(0);" class="has-arrow">'.$sMenu->name.' </a>';

		                           foreach( $setMenu[$mMenu->id] AS $sMenu2 ){

		                           		if($sMenu->id==$sMenu2->ref2){
									  		$subMenu .= '
									            <ul class="sub-menu" aria-expanded="true">
								                    <li><a href="'.asset($sMenu2->link).'">'.$sMenu2->name.'</a></li>
								                </ul>';
								        }

								   }
								
				                $subMenu .= '</li>';

			            	}else{

			            		if($sMenu->menu_level==1){
					                  if(in_array($sMenu->id, $arr_menu_id)){
					                    $subMenu .= '<li><a href="'.asset($sMenu->link).'">'.$sMenu->name.' </a></li>';
					                  }
				              	}

			            	}


	                  }

              }

            }


          }

          if(\Auth::user()->permission==1){
                $txtMenu .= '
                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="'.$mMenu->icon.'"></i>
                        <span>'.$mMenu->name.' </span>
                    </a>
                    <ul class="sub-menu" aria-expanded="true">
                        '.$subMenu.' 
                    </ul>
                  </li>
                  ';
          }else{

                if(in_array($mMenu->id, $arr_menu_id)){

	                $txtMenu .= '
	                <li>
	                    <a href="javascript: void(0);" class="has-arrow waves-effect">
	                        <i class="'.$mMenu->icon.'"></i>
	                        <span>'.$mMenu->name.' </span>
	                    </a>
	                    <ul class="sub-menu" aria-expanded="true">
	                        '.$subMenu.'  
	                    </ul>
	                  </li>
	                  ';

              }
          }


        }
      }
    }

    return $txtMenu;
  }
}

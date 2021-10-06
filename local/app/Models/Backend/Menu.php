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
    // $row = \Cache::remember('Menu-'.\Auth::user()->id, 10, function() {
    $row = \Cache::remember('Menu-'.\Auth::user()->role_group_id_fk, 10, function() {
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
          ['id'=>$r->id,'name'=>$r->name,'link'=>$r->url,'icon'=>$r->icon,'active'=>$active,'ref2'=>$r->ref2,'menu_level'=>$r->menu_level, 'localizeName' => strpos($r->url, '/') !== false ? explode('/', $r->url)[1] : $r->url];

        }
      }
    }

    // echo "<pre>";

    // dump($setMenu);
    // print_r(\Auth::user()->role_group_id_fk);

 // $MenuPermission = \App\Models\MenuPermissionModel::where('admin_id',\Auth::user()->id)->get();
 $MenuPermission = \App\Models\MenuPermissionModel::where('role_group_id_fk',\Auth::user()->role_group_id_fk)->get();
 // print_r($MenuPermission[0]->role_group_id_fk);
 // print_r($MenuPermission[0]->menu_id_fk);
 // foreach ($MenuPermission as $key => $value) {
 //   echo  $value;
 // }


$arr_menu_id = [];
foreach ($MenuPermission as $key => $value) {
  # code...
  // echo @$value->main_menu_id;
  // array_push($arr_menu_id, $value->main_menu_id);
  array_push($arr_menu_id, $value->menu_id_fk);
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
                          <a data-id="'.$sMenu->id.'" href="'.asset($mMenu->link).'" class=" waves-effect click_link ">
                              <i class="'.$mMenu->icon.'"></i>
                              <span>'.trans('message.menus.'.$mMenu->localizeName).'</span>
                          </a>
                      </li>';
            }else{

              if(in_array($mMenu->id, $arr_menu_id)){
                $txtMenu .= '
                      <li>
                          <a data-id="'.$sMenu->id.'" href="'.asset($mMenu->link).'" class=" waves-effect click_link ">
                              <i class="'.$mMenu->icon.'"></i>
                              <span>'.trans('message.menus.'.$mMenu->localizeName).'</span>
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
		                           <li><a data-id="'.$sMenu->id.'" href="javascript: void(0);" class="has-arrow click_link ">'.trans('message.menus.'.$sMenu->localizeName).'</a>';

		                           foreach( $setMenu[$mMenu->id] AS $sMenu2 ){

		                           		if($sMenu->id==$sMenu2->ref2){
									  		$subMenu .= '
									            <ul class="sub-menu" aria-expanded="true">
								                    <li><a data-id="'.$sMenu->id.'" class="click_link"  href="'.asset($sMenu2->link).'">'.trans('message.menus.'.$sMenu2->localizeName).'</a></li>
								                </ul>';
								        }

								   }


		                $subMenu .= '</li>';

	            	}else{

	            		if($sMenu->menu_level==1){
		            		$subMenu .= '<li><a href="'.asset($sMenu->link).'" data-id="'.$sMenu->id.'" class="click_link" >'.trans('message.menus.'.$sMenu->localizeName).'</a></li>';
			                  if(in_array($sMenu->id, $arr_menu_id)){
			                    $subMenu .= '<li><a data-id="'.$sMenu->id.'" class="click_link"  href="'.asset($sMenu->link).'">'.trans('message.menus.'.$sMenu->localizeName).' </a></li>';
			                  }
		              	}

	            	}


              }else{

	                  if(in_array($sMenu->id, $arr_menu_id)){

	                      if($sMenu->ref2==0 && $sMenu->menu_level==2){

	                    	$subMenu .= '
		                           <li><a data-id="'.$sMenu->id.'" href="javascript: void(0);" class="has-arrow click_link ">'.trans('message.menus.'.$sMenu->localizeName).'</a>';

		                           foreach( $setMenu[$mMenu->id] AS $sMenu2 ){

		                           		if($sMenu->id==$sMenu2->ref2){
									  		$subMenu .= '
									            <ul class="sub-menu" aria-expanded="true">
								                    <li><a data-id="'.$sMenu->id.'" class="click_link" href="'.asset($sMenu2->link).'">'.trans('message.menus.'.$sMenu2->localizeName).'</a></li>
								                </ul>';
								        }

								   }

				                $subMenu .= '</li>';

			            	}else{

			            		if($sMenu->menu_level==1){
					                  if(in_array($sMenu->id, $arr_menu_id)){
					                    $subMenu .= '<li><a data-id="'.$sMenu->id.'" class="click_link" href="'.asset($sMenu->link).'">'.trans('message.menus.'.$sMenu->localizeName).'</a></li>';
					                  }
				              	}

			            	}


	                  }

              }

            }


          }

          if(\Auth::user()->permission==1){

                // $click_link = $sMenu->ref==0?'click_link':'';
                
                $txtMenu .= '
                <li>
                    <a data-id="'.$sMenu->id.'" href="javascript: void(0);" class="has-arrow waves-effect  ">
                        <i class="'.$mMenu->icon.'"></i>
                        <span>'.trans('message.menus.'.$mMenu->localizeName).'</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="true">
                        '.$subMenu.'
                    </ul>
                  </li>
                  ';
          }else{

                if(in_array($mMenu->id, $arr_menu_id)){

                  // $click_link = $sMenu->ref==0?'click_link':'';

	                $txtMenu .= '
	                <li>
	                    <a data-id="'.$sMenu->id.'" href="javascript: void(0);" class="has-arrow waves-effect  ">
	                        <i class="'.$mMenu->icon.'"></i>
	                        <span>'.trans('message.menus.'.$mMenu->localizeName).'</span>
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

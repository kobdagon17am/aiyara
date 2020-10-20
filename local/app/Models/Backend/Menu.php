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
          $setMenu[$r->ref][] = (object) ['id'=>$r->id,'name'=>$r->name,'link'=>$r->url,'icon'=>$r->icon,'active'=>$active];
          
        }
      }
    }

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
                              <span>'.$mMenu->name.'</span>
                          </a>
                      </li>';
            }else{

              if(in_array($mMenu->id, $arr_menu_id)){
                $txtMenu .= '
                      <li>
                          <a href="'.asset($mMenu->link).'" class=" waves-effect">
                              <i class="'.$mMenu->icon.'"></i>
                              <span>'.$mMenu->name.'</span>
                          </a>
                      </li>';
                      //  <span>'.$mMenu->id.$mMenu->name.'AAA</span>
              }

            }
        }else{
          /**
           *---------------------------------------------------------------------------------------------------------------------------------------------------------
           */
          foreach( $setMenu[$mMenu->id] AS $sMenu ){
            if( empty($setMenu[$sMenu->id]) ){
              if(\Auth::user()->permission==1){
                    $subMenu .= '<li><a href="'.asset($sMenu->link).'">'.$sMenu->name.'</a></li>';
              }else{
                  if(in_array($sMenu->id, $arr_menu_id)){
                    // $subMenu .= '<li><a href="'.asset($sMenu->link).'">'.$sMenu->id.$sMenu->name.'BBB</a></li>';
                    $subMenu .= '<li><a href="'.asset($sMenu->link).'">'.$sMenu->name.'</a></li>';
                  }
              }
            }else{
              /**
               *---------------------------------------------------------------------------------------------------------------------------------------------------------
               */
              $subMenu2   = '';
              $subActive2 = '';
              foreach( $setMenu[$sMenu->id] AS $sMenu2 ){
                if( empty($setMenu[$sMenu2->id]) ){
                  $subMenu2 .= '<li><a href="'.asset($sMenu2->link).'">'.$sMenu->name.'CCC</a></li>';
                }else{
                  /**
                   *---------------------------------------------------------------------------------------------------------------------------------------------------------
                   */
                  $subMenu3   = '';
                  $subActive3 = '';
                  foreach( $setMenu[$sMenu2->id] AS $sMenu3 ){
                    $subMenu3 .= '<li><a href="'.asset($sMenu3->link).'">'.$sMenu3->name.'DDD</a></li>';
                  }

                  $subMenu2 .= '
                  <li>
                    <a href="javascript: void(0);" class="has-arrow">
                        <i class="'.$sMenu2->icon.'"></i>
                        <span>'.$sMenu2->name.'EEE</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="true">
                        '.$subMenu3.'FFF
                    </ul>
                  </li>';
                }
              }

              $subMenu .= '
              <li>
                <a href="javascript: void(0);" class="has-arrow">
                    <i class="'.$sMenu2->icon.'"></i>
                    <span>'.$sMenu2->name.'GGG</span>
                </a>
                <ul class="sub-menu" aria-expanded="true">
                    '.$subMenu2.'HHH
                </ul>
              </li>';
            }
          }


          if(\Auth::user()->permission==1){
                $txtMenu .= '
                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="'.$mMenu->icon.'"></i>
                        <span>'.$mMenu->name.'</span>
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
                        <span>'.$mMenu->name.'</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="true">
                        '.$subMenu.'
                    </ul>
                  </li>
                  ';
                  // <span>'.$mMenu->id.$mMenu->name.'III</span>
              }
          }
        }
      }
    }

    return $txtMenu;
  }
}

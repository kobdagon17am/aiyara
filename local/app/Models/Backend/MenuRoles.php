<?php

namespace App\Models\Backend;
use Illuminate\Database\Eloquent\Model;

class MenuRoles extends Model
{
  protected $table = 'ck_backend_menu';
  public $timestamps = false;

  public function setMenu($path)
  {

    $setMenu = array();
    $txtMenu = '';
    $pid = array();
    $row = \Cache::remember('Menu-'.\Auth::user()->id, 10, function() {
      $Menu = Menu::where('isActive', '<>', 'N')->orderBy('sort', 'asc')->orderBy('id', 'asc');
      return $Menu->get();
    });
    if( $row ){
      foreach( $row AS $r ){
        if( !isset($setMenu[$r->ref][$r->id]) )
        {
          $active = '';
          if( substr_count($r->url, '/') == 0 ){
            $active = $r->url==$path?'Y':'N';
          }else{
            $active = substr_count($path, (empty($r->url)?'1':$r->url)) > 0?'Y':'N';
          }
          $setMenu[$r->ref][] = (object) ['id'=>$r->id,'name'=>$r->name,'link'=>$r->url,'icon'=>$r->icon,'active'=>$active,'ref'=>$r->ref,'level'=>$r->menu_level];
        }
      }
    }

    if( $setMenu[0] ){
  
      foreach( $setMenu[0] AS $key => $mMenu ){

            $subMenu  = '';
            if( empty($setMenu[$mMenu->id]) ){
              // เมนูส่วนล่างๆ
              $txtMenu .= '
                    <li>
                        <a href="'.asset($mMenu->link).'" class=" waves-effect">
                            <i class="'.$mMenu->icon.'"></i>
                            <span>'.$mMenu->name.'</span>
                        </a>
                    </li>';

            }else{
        
                  foreach( $setMenu[$mMenu->id] AS $sMenu ){

                     if( $sMenu->link == "" ){
                         $subMenu .= '
                         <li>

                          <a href="javascript: void(0);" class="has-arrow waves-effect">
                          <span>'.$sMenu->name.'</span>
                          </a>
                          <ul class="sub-menu" aria-expanded="true">';

                            foreach( $setMenu[$sMenu->id] AS $sMenu2 ){

                               if($sMenu2->level==2 && $sMenu2->link == ""){

                                $subMenu .= '
                                     <li>
                                     
                                      <a href="javascript: void(0);" class="has-arrow waves-effect">
                                      <span>'.$sMenu2->name.'</span>
                                      </a>
                                      <ul class="sub-menu" aria-expanded="true">';

                                      foreach( $setMenu[$sMenu2->id] AS $sMenu3 ){

                                           if($sMenu3->level==3 && $sMenu3->link != ""){

                                              $subMenu .= '<li><a href="'.asset($sMenu3->link).'">'.$sMenu3->name.'</a></li>';

                                           }

                                        }

                                      $subMenu .= '</ul>
                                  </li>';

                               }else{
                                  $subMenu .= '<li><a href="'.asset($sMenu2->link).'">'.$sMenu2->name.'</a></li>';
                               }

                            }

                          
                      $subMenu .= '</ul>
                      </li>';
                     }else{
                         $subMenu .= '<li><a href="'.asset($sMenu->link).'">'.$sMenu->name.'</a></li>';
                     }

                  
                  }

                  $txtMenu .= '
                  <li> 

                  <input type="checkbox" >

                      <a href="javascript: void(0);" class="has-arrow waves-effect">
                          <i class="'.$mMenu->icon.'"></i>
                          <span>'.$mMenu->name.'</span>
                      </a>
                      <ul class="sub-menu" aria-expanded="true">
                          '.$subMenu.'  
                      </ul>

                    </li>
                    ';


            }

      }



    }

    return $txtMenu;
  }
}

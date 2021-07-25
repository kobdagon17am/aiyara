<!-- ========== Left Sidebar Start ========== -->
<!-- <div class="vertical-menu" style='background-image: url("backend/images/footer-bg.jpg");'> -->
<div class="vertical-menu" style=' background: rgb(0,11,0);
background: linear-gradient(90deg, rgba(0,11,0,1) 90%, rgba(160,244,202,0.9977124638918067) 98%); '>

    <div data-simplebar class="h-100" >

        <!--- Sidemenu -->
        <div id="sidebar-menu"  >
            <!-- Left Menu Start  style="background-color: #003300;" -->
            <ul class="metismenu list-unstyled" id="side-menu" > 
                <!-- <li class="menu-title">Menu</li> -->
      <!--            <li>
                    <a href="backend" class=" waves-effect">
                        <i class="bx bx-home-circle"></i>
                        <span>Dashboards</span>
                    </a>
                </li>  -->
                @php
                if(!empty(Auth::user())){
                  @$Menu = new \App\Models\Backend\Menu;
                  echo @$Menu->setMenu(Request::path());
                }
                @endphp


        <!--          <li class="menu-title" style="margin-top: 150px;">Template Layout </li>
                <li>
                    <a href="backend/template" class=" waves-effect">
                        <i class="bx bx-file"></i>
                        <span>Admin Template</span>
                    </a>
                </li>  -->

            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>
<!-- Left Sidebar End -->

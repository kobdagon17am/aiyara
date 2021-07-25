<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>{{  config('global.siteTitle') }}</title>

  <!-- Custom fonts for this template-->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="../adminassets/css/sb-admin-2.min.css" rel="stylesheet">
  <link href="../adminassets/css/style.css" rel="stylesheet">
<!-- Picker -->
  <link href='../adminassets/picker/picker.css' rel='stylesheet'>
  <script src='../adminassets/picker/picker.js'></script>
</head>

<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

      <!-- Nav Item - Pages Collapse Menu -->
            @include('/cademy/cademymenu')
      <!-- Sidebar Toggler (Sidebar) 
      <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
      </div>-->
    </ul>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div class="container">

    <div class="card o-hidden border-0 shadow-lg my-5">
        <div class="card-body p-0" style="color: black;">
        <!-- Nested Row within Card Body -->
        <div class="row">
            <div class="col-lg-12">&nbsp;</div>
            <div class="col-lg-12">
              <div class="text-center">
                <h1 class="h4 text-gray-900">สินค้า</h1>
              </div>
            </div>
            
              <div class="col-lg-1 "></div>
              <div class="col-lg-10 ">
              <form class="user">
                <div class="form-group row" >
                  <div class="col-lg-2 col-sm-12">
                        <input id='img1' type="button" value="+ เพิ่ม สินค้า"  style="  background-color: #00c454; color: white;" class="btn btn-user btn-block" />
                  </div>
                </div>
                <div class="form-group row">
                    
                    <div class="col-lg-6 col-sm-12">
                        <select class="form-control " style=" width: 200px; font-size: 18px; color: black;">
                        <option value="0" selected>เลือกประเภทสินค้า</option>
                        <option value="1">ประเภทสินค้าที่ 1</option>
                        <option value="2">ประเภทสินค้าที่ 2</option>
                        <option value="3">ประเภทสินค้าที่ 3</option>
                        <option value="4">ประเภทสินค้าที่ 4</option>
                    </select><br/>
                        <input type="text" class="form-control form-control-user" id="ticketno" placeholder="ค้นหา">
                  </div>
                </div>
                <div class="form-group row" >
                    <div class="col-lg-12 col-sm-12">
                        <table class="table table-striped table-responsive " >
                                                        <tr style=" background-color:  #99ff99; text-align: center;">
                                                            <td>รหัสสินค้า.</td>
                                                            <td>ชื่อสินค้า</td>
                                                            <td>ราคาสมาชิก</td>
                                                            <td>สินค้า Voucher</td>
                                                            <td>สถานะ</td>
                                                            <td>จำนวนคงเหลือ</td>
                                                            <td>#</td>
                                                        </tr>
                                                        @for ($x=1;$x<=3;$x++)
                                                        <tr>
                                                            <td>A00{{ $x }}</td>
                                                            <td>ชื่อสินค้าที่ {{ $x }}</td>
                                                            <td align="right">100.00</td>
                                                            <td align="center" style="color: #00c454;">Voucher</td>
                                                            <td align="center" style="color: #00c454;">Active</td>
                                                            <td align="right">1,000</td>
                                                            <td align="center">
                                                                <input onclick="window.location.href='/aiyara/cademy/addnew_product';" type="button" value="แก้ไข" style="  background-color: #00c454; color: white; width: 120px;" class="btn btn-user" />
                                                                <input onclick="confirm_del()" type="button" value="ลบ" style="   background-color: #ffcc00; color: black; width: 120px;" class="btn btn-user" />
                                                            </td>
                                                        </tr>
                                                        @endfor
                                                        <tr>
                                                            <td>A004</td>
                                                            <td>ชื่อสินค้าที่ 4</td>
                                                            <td align="right">100.00</td>
                                                            <td align="center" style="color: #ffcc00;">None</td>
                                                            <td align="center" style="color: #00c454;">Active</td>
                                                            <td align="right">1,000</td>
                                                            <td align="center">
                                                                <input type="button" value="แก้ไข" style="  background-color: #00c454; color: white; width: 120px;" class="btn btn-user" />
                                                                <input type="button" value="ลบ" style="   background-color: #ffcc00; color: black; width: 120px;" class="btn btn-user" />
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>A005</td>
                                                            <td>ชื่อสินค้าที่ 5</td>
                                                            <td align="right">100.00</td>
                                                            <td align="center" style="color: #ffcc00;">None</td>
                                                            <td align="center" style="color: #ffcc00;">None</td>
                                                            <td align="right">1,000</td>
                                                            <td align="center">
                                                                <input type="button" value="แก้ไข" style="  background-color: #00c454; color: white; width: 120px;" class="btn btn-user" />
                                                                <input type="button" value="ลบ" style="   background-color: #ffcc00; color: black; width: 120px;" class="btn btn-user" />
                                                            </td>
                                                        </tr>
                                                </table>
                                
                    </div>
                </div>
              </form>
              </div>
              <div class="col-lg-1 "></div>
            
            </div>
        </div>
      </div>
    </div>

  </div>
    <!-- End of Content Wrapper -->

  </div>
  <!-- End of Page Wrapper -->

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

<script src="js/vendor/jquery-1.12.4.min.js"></script>
  <!-- Bootstrap core JavaScript-->
  <script src="../adminassets/vendor/jquery/jquery.min.js"></script>
  <script src="../adminassets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="../adminassets/vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="../adminassets/js/sb-admin-2.min.js"></script>

  <!-- Page level plugins -->
  <script src="../adminassets/vendor/chart.js/Chart.min.js"></script>

  <!-- Page level custom scripts -->
  <script src="../adminassets/js/demo/chart-area-demo.js"></script>
  <script src="../adminassets/js/demo/chart-pie-demo.js"></script>
      <script type="text/javascript">
        $(document).ready(function () {
            $('#img1').click(function(){
                   window.location.href='/aiyara/cademy/addnew_product';
		});
          });
          function confirm_del(){
              var txt;
                var r = confirm("ยืนยันการลบ");
                if (r == true) {
                  txt = "You pressed OK!";
                } else {
                  txt = "You pressed Cancel!";
                }
          }
    </script>
</body>

</html>

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
                <h1 class="h4 text-gray-900">รายละเอียดของ Course / Event</h1>
              </div>
            </div>
            
              <div class="col-lg-1 "></div>
              
              <div class="col-lg-10 ">
                  <form >
                <div class="form-group row">
                    <table style="width: 100%;" class="table table-striped  " id="courselist">
                       <tr style=" background-color:  #99ff99; text-align: center;">
                           <td>#</td>
                           <td>ชื่อกิจกรรม</td>
                           <td>วันที่เริ่มกิจกรรม</td>
                           <td>จำนวนผู้ลงทะเบียน</td>
                           <td>ดูรายชื่อ</td>
                           <td>Download</td>
                       </tr>
                        @for ($x=1;$x<=15;$x++)
                        <tr>
                            <td>{{ $x }}</td>
                            <td>กิจกรรม ที่ {{ $x }}</td>
                            <td>05/14/2020</td>
                            <td>500</td>
                            <td>
                                <input type="button" value="ตรวจสอบ" onclick="showregister()" style="  background-color: #00c454; color: white;" class="btn  btn-block" style=" background-color: #00c454; color: white;" />
                                
                            </td>
                            <td>
                                <input type="button" value="Download" onclick="showregister2()" style="  background-color: #00c454; color: white;" class="btn  btn-block" style=" background-color: #00c454; color: white;" />
                            </td>
                        </tr>
                        @endfor
                    </table>
                </div>
                  <div class="form-group row">
                   <input id="returnbutton" type="button" value="กลับหน้ารายการ" onclick="showcourse()" style="  background-color: #00c454; color: white;" class="btn btn-user btn-block" style=" background-color: #00c454; color: white;" />
                   <table style=" wifth: 100%;" class="table table-striped table-responsive " id="registerlist">
                       <tr style=" background-color:  #99ff99; text-align: center;">
                           <td>#</td>
                           <td>รายละเอียด</td>
                           <td>วันที่เข้าร่วมกิจกรรม</td>
                           <td>ตะแนน</td>
                           <td>คุณวุฒิ</td>
                       </tr>
                        @for ($x=1;$x<=25;$x++)
                        <tr>
                            <td>{{ $x }}</td>
                            <td>A123456 : 24Extra</td>
                            <td>05/14/2020</td>
                            <td>100</td>
                            <td>Member Gold Star</td>
                        </tr>
                        @endfor
                    </table>
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
        document.getElementById('courselist').style.display = 'block';
        document.getElementById('registerlist').style.display = 'none';
        document.getElementById('returnbutton').style.display = 'none';
        function showcourse(){
            document.getElementById('courselist').style.display = 'block';
            document.getElementById('registerlist').style.display = 'none';
            document.getElementById('returnbutton').style.display = 'none';
        }
        function showregister() {
            document.getElementById('courselist').style.display = 'none';
            document.getElementById('registerlist').style.display = 'block';
            document.getElementById('returnbutton').style.display = 'block';
        }
        function showregister2() {
            alert("Download Success.");
        }
    </script>
</body>

</html>

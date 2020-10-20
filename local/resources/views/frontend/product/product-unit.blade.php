 @extends('frontend.layouts.cademy.cademy_app')
 @section('css')

 @endsection
 @section('conten')
 <!-- Content Wrapper -->
 <div class="container">

  <div class="card o-hidden border-0 shadow-lg my-5">
    <div class="card-body p-0" style="color: black;">
      <!-- Nested Row within Card Body -->
      <div class="row">
        <div class="col-lg-12">&nbsp;</div>
        <div class="col-lg-12">
          <div class="text-center">
            <h1 class="h4 text-gray-900">หน่วยสินค้า</h1>
          </div>
        </div>
        
        <div class="col-lg-1 "></div>
        <div class="col-lg-10 ">
          <form class="user">
            <div class="form-group row" >
              <div class="col-lg-2 col-sm-12">
                <input id='img1' type="button" value="+ เพิ่ม หน่วยสินค้า"  style="  background-color: #00c454; color: white;" class="btn btn-user btn-block" />
              </div>
            </div>
            <div class="form-group row">
              <div class="col-lg-6 col-sm-12">
                <input type="text" class="form-control form-control-user" id="ticketno" placeholder="ค้นหา">
              </div>
            </div>
            <div class="form-group row" >
              <div class="col-lg-12 col-sm-12">
                <table class="table table-striped table-responsive " >
                  <tr style=" background-color:  #99ff99; text-align: center;">
                    <td>No.</td>
                    <td>หน่วยสินค้า</td>
                    <td>รายละเอียด</td>
                    <td>สถานะ</td>
                    <td>วันที่เพิ่ม</td>
                    <td>#</td>
                  </tr>
                  @for ($x=1;$x<=3;$x++)
                  <tr>
                    <td>{{ $x }}</td>
                    <td>หน่วยสินค้าที่ {{ $x }}</td>
                    <td>รายละเอียดหน่วยสินค้าที่ {{ $x }}</td>
                    <td style="color: #00c454;">Active</td>
                    <td >24/6/2563</td>
                    <td align="center">
                      <input type="button" value="แก้ไข" style="  background-color: #00c454; color: white; width: 120px;" class="btn btn-user" />
                      <input type="button" value="ลบ" style="   background-color: #ffcc00; color: black; width: 120px;" class="btn btn-user" />
                    </td>
                  </tr>
                  @endfor
                  <tr>
                    <td>{{ $x }}</td>
                    <td><span id='cat_1'>หน่วยสินค้าที่ {{ $x }}</span></td>
                    <td>รายละเอียดหน่วยสินค้าที่ {{ $x }}</td>
                    <td style="color: #ffcc00;">None</td>
                    <td >24/6/2563</td>
                    <td align="center">
                      <input onclick="edit_cat()" type="button" value="แก้ไข" style="  background-color: #00c454; color: white; width: 120px;" class="btn btn-user" />
                      <input onclick="confirm_del()" type="button" value="ลบ" style="   background-color: #ffcc00; color: black; width: 120px;" class="btn btn-user" />
                    </td>
                  </tr>
                </table>
                <div class='modal fade' id='myModal' role='dialog'>
                  <div class='modal-dialog modals-sm'>
                    <div class='modal-content'>
                      <div class='modal-header'>
                        <button type='button' class='close' data-dismiss='modal'>&times;</button>
                      </div>
                      <div class='modal-body' style=' text-align: left;'>
                        <input type="text" class="form-control form-control-user" id="ticketno1" placeholder="ชื่อหน่วยสินค้า">
                        <br/>
                        <input type="text" class="form-control form-control-user" id="ticketno1" placeholder="รายละเอียดหน่วยสินค้า">
                        <br/>
                        <input onclick="confirm_add()" data-dismiss='modal' type="button" value="บันทึก" style="  background-color: #00c454; color: white; width: 120px;" class="btn btn-user close" />
                        <input data-dismiss='modal' type="button" value="ยกเลิก" style="   background-color: #ffcc00; color: black; width: 120px;" class="btn btn-user close" />
                      </div>
                    </div>
                  </div>
                </div>
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


@endsection
@section('js') 
<script type="text/javascript">
  $(document).ready(function () {
    $('#img1').click(function(){
      $('#myModal').modal('show'); 
    });
  });
  function confirm_add(){
    var txt;
    var r = confirm("ยืนยันการบันทึก");
    if (r == true) {
      txt = "You pressed OK!";
    } else {
      txt = "You pressed Cancel!";
    }
  }
  function confirm_del(){
    var txt;
    var r = confirm("ยืนยันการลบ");
    if (r == true) {
      txt = "You pressed OK!";
    } else {
      txt = "You pressed Cancel!";
    }
  }
  function edit_cat(){
   var txt=$("#cat_1").html();
   $("#ticketno1").val(txt);  
   $('#img1').click();
 }
</script>
@endsection 



 @extends('frontend.layouts.customer.customer_app')
 @section('css')
 
 @endsection
 @section('conten')
 <div class="container">

    <div class="card o-hidden border-0 shadow-lg my-5">
        <div class="card-body p-0" style="color: black;">
            <!-- Nested Row within Card Body -->
            <div class="row">
                <div class="col-md-12">&nbsp;</div>
                <div class="col-md-12">
                  <div class="text-center">
                    <h1 class="h4 text-gray-900">สินค้า</h1>
                </div>
            </div>
        </div>    
        <div class="row">
          <div class="col-md-12 ">
            
            <div class="form-group row" >
                <div class="col-md-1"></div>
                <!--   <div class="col-md-10  col-md-offset-1 col-sm-12" >-->
                   <table  style="width: 75%;" >
                    <tr >
                        <td style=" width:150px; text-align: right;">ประเภทสินค้า :</td>
                        <td>
                            <select class="form-control " style=" width: 400px; font-size: 18px; color: black;">
                                <option value="0" selected>เลือกประเภทสินค้า</option>
                                <option value="1">ประเภทสินค้าที่ 1</option>
                                <option value="2">ประเภทสินค้าที่ 2</option>
                                <option value="3">ประเภทสินค้าที่ 3</option>
                                <option value="4">ประเภทสินค้าที่ 4</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td style=" width:150px; text-align: right;">รหัส :</td>
                        <td><input type="text" class="form-control form-control-user" id="ticketno" placeholder="รหัสสินค้า"></td>
                    </tr>
                    <tr>
                        <td style=" width:150px; text-align: right;">Shot ID :</td>
                        <td><input type="text" class="form-control form-control-user" id="ticketno" placeholder="Shot ID"></td>
                    </tr>
                    <tr>
                        <td style=" width:150px; text-align: right;">ชื่อสินค้า :</td>
                        <td><input type="text" class="form-control form-control-user" id="ticketno" placeholder="ชื่อสินค้า">
                            <input  onclick="show_more()" type="button" value="เพิ่มรายละเอียดในประเทศอื่น"  style="  background-color: #00c454; color: white;" class="btn btn-user btn-block" />
                        </td>
                    </tr>
                    
                    <tr>
                        <td style=" width:150px; text-align: right;">รายละเอียด :</td>
                        <td>
                            <div id="sample">
                                <script type="text/javascript" src="http://js.nicedit.com/nicEdit-latest.js"></script> <script type="text/javascript">
                                  bkLib.onDomLoaded(function() { nicEditors.allTextAreas() });
                              </script>
                              <textarea name="area1" style="width: 100%; height: 300px;">
                              </textarea><br />
                              
                          </div>
                      </td>
                  </tr>
                  <tr>
                    <td style=" width:150px; text-align: right;">ราคาขาย :</td>
                    <td><input type="text" class="form-control form-control-user" id="ticketno" placeholder="ราคาขาย"></td>
                </tr>
                <tr>
                    <td style=" width:150px; text-align: right;">ราคาสมาชิก :</td>
                    <td><input type="text" class="form-control form-control-user" id="ticketno" placeholder="ราคาสมาชิก"></td>
                </tr>
                <tr >
                    <td style=" width:150px; text-align: right;">ประเภทสินค้า :</td>
                    <td>
                        <select class="form-control " style=" width: 400px; font-size: 18px; color: black;">
                            <option value="0" selected>เลือกหน่วยสินค้า</option>
                            <option value="1">หน่วยสินค้าที่ 1</option>
                            <option value="2">หน่วยสินค้าที่ 2</option>
                            <option value="3">หน่วยสินค้าที่ 3</option>
                            <option value="4">หน่วยสินค้าที่ 4</option>
                        </select>
                    </td>
                </tr>
                
                <tr>
                    <td style=" width:150px; text-align: right;">จำนวนสินค้าต่อหน่วย :</td>
                    <td><input type="number" class="form-control form-control-user" id="ticketno" value="0"></td>
                </tr>
                <tr>
                    <td style=" width:150px; text-align: right;">มิติของขนาด(mm) :</td>
                    <td>
                        <div class="row">
                            <div class="col-3">
                                กว้าง<input type="number" style="width:100px;" class="form-control form-control-user" id="ticketno" value="0">
                            </div><div class="col-3">
                                ยาว<input type="number" style="width:100px;" class="form-control form-control-user" id="ticketno" value="0">
                            </div><div class="col-3">
                                สูง<input type="number" style="width:100px;" class="form-control form-control-user" id="ticketno" value="0">
                            </div>
                        </div>
                        
                    </td>
                </tr>
                <tr>
                    <td style=" width:150px; text-align: right;">&nbsp;</td>
                    <td><input type="checkbox" id="ticketno" >เป็นสินค้าที่สามารถซื้อด้วย Voucher</td>
                </tr>
                <tr>
                    <td style=" width:150px; text-align: right;">minimum stock :</td>
                    <td><input type="number" class="form-control form-control-user" id="ticketno" value="0"></td>
                </tr>
                <tr>
                    <td style=" width:150px; text-align: right;">&nbsp;</td>
                    <td><input type="checkbox" id="ticketno" checked="" >ตั้งสถาะนสินค้า Active</td>
                </tr>
                <tr>
                    
                    
                    <td></td>
                    <td align="center">
                        <input onclick="confirm_add()" type="button" value="บันทึก" style="  background-color: #00c454; color: white; width: 120px;" class="btn btn-user" />
                        <input onclick="window.location.href='/aiyara/cademy/addnew_product_menu'" type="button" value="ยกเลิก" style="   background-color: #ffcc00; color: black; width: 120px;" class="btn btn-user" />
                    </td>
                </tr>
                
            </table>
            
            <!--</div>-->
            <div class="col-md-1"></div>
        </div>
    </div>
</div>
</div>
</div>
</div>

</div>
<!-- End of Content Wrapper -->

</div>
<!-- End of Page Wrapper -->
<div class="modal fade" id="myModalone" role="dialog"  >
  <div class="modal-dialog modal-large" >
    <div class="modal-content" style=" font-size: 14px; width: 800px;" >
        <div class="modal-body" >
            <table  class="table " style=" margin-left: auto; margin-right: auto; width: 100%; ">
                <tr>
                    <td style=" width:150px; text-align: right;">เลือกโลเคชั่น :</td>
                    <td>
                        <select class="form-control " style=" width: 200px; font-size: 18px; color: black;">
                            <option value="0" selected>ประเทศไทย</option>
                            <option value="1">ประเทศกัมพูชา</option>
                            <option value="2">ประเทศพม่า</option>
                            <option value="3">ประเทศลาว</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td style=" width:150px; text-align: right;">ชื่อสินค้า :</td>
                    <td><input type="text" class="form-control form-control-user" id="ticketno" placeholder="ชื่อสินค้า"></td>
                </tr>
                
                <tr>
                    <td style=" width:150px; text-align: right;">รายละเอียด :</td>
                    <td>
                        <div id="sample">
                            <script type="text/javascript" src="http://js.nicedit.com/nicEdit-latest.js"></script> <script type="text/javascript">
                              bkLib.onDomLoaded(function() { nicEditors.allTextAreas() });
                          </script>
                          <textarea name="area2" style="width: 400px; height: 300px;">
                          </textarea><br />
                          
                      </div>
                  </td>
              </tr>
              <tr>
                <td style=" width:150px; text-align: right;">ราคาขาย :</td>
                <td><input type="text" class="form-control form-control-user" id="ticketno" placeholder="ราคาขาย"></td>
            </tr>
            <tr>
                <td style=" width:150px; text-align: right;">สกุลเงิน :</td>
                <td>
                    <select class="form-control " style=" width: 200px; font-size: 18px; color: black;">
                        <option value="0" selected>บาทไทย</option>
                        <option value="1">ดอลล่าสหรัฐฯ</option>
                        <option value="2">จัตพม่า</option>
                        <option value="3">กีบลาว</option>
                    </select>
                </td>
                
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>
                    <input type="button" data-dismiss="modal" value="ยกเลิก"  style=" background-color: #ffcc00; color: black;" class="btn btn-user">
                    <input type="button" data-dismiss="modal"value="บันทึก"  style="  background-color: #00c454; color: white;" class="btn btn-user">
                </td>
            </tr>
        </table>
    </div>
</div>
</div>
</div>
<!-- Scroll to Top Button-->
@endsection
@section('js') 
<script type="text/javascript">
    
  function show_more(){
      $('#myModalone').modal('show'); 
  }

  function confirm_add(){
      var txt;
      var r = confirm("ยืนยันการบันทึก");
      if (r == true) {
          window.location.href='/aiyara/cademy/addnew_product_menu';
      } else {
          txt = "You pressed Cancel!";
      }
  }
</script>
@endsection

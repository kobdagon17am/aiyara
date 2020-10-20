 @extends('frontend.layouts.customer.customer_app')
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
                <h1 class="h4 text-gray-900">รับสินค้าเข้าตาม PO</h1>
              </div>
            </div>
            
              <div class="col-lg-1 "></div>
              <div class="col-lg-10 ">
              <form class="user">
                <div class="form-group row" >
                    <div class="col-lg-3 col-sm-12">
                      <input type="button" value="+ ค้นหาช่วงเวลา" onclick="window.location.href=''" style="  background-color: #00c454; color: white;" class="btn btn-user btn-block" />
                  </div>
                    <div class="col-lg-12 col-sm-12">
                     <br/>
                    <label for="username">รายงานจากช่วงเวลา จากวันที่ </label>
                    <input style="border: none;" class="date-input-native" id="date" type="date" name="date" min="2020-01-01" max="2030-12-31">
                                <!--<input class="date-input-fallback" id="alt" type="text" placeholder="Pick A Date">--->
                                <div id="picker" hidden></div>
                    <label for="username"> ถึงวันที่ </label>
                                <input style="border: none;"  class="ddate-input-native" id="date" type="date" name="date" min="2020-01-01" max="2030-12-31">
                                <!--<input class="date-input-fallback" id="alt" type="text" placeholder="Pick A Date">--->
                                <div id="picker" hidden></div>
                                 <hr/>
                  </div>
                   
                    <div class="col-lg-3 col-sm-3">ค้นหาจากเงื่อนไข</div>
                    <div class="col-lg-3 col-sm-3">
                    <input  type="text"  id="productcode" class="form-control form-control-user"  placeholder="หมายเลข PO"></div>
                    <div class="col-lg-3 col-sm-3">
                    <input  type="text"  id="productcode" class="form-control form-control-user"  placeholder="รหัส Supplier"></div>
                    <div class="col-lg-3 col-sm-3">
                    <input  type="text"  id="productcode" class="form-control form-control-user"  placeholder="ชื่อ Supplier"></div>
                    <div class="col-lg-3 col-sm-3">&nbsp;</div>
                    <div class="col-lg-3 col-sm-3">
                    <input  type="text"  id="productcode" class="form-control form-control-user"  placeholder="เบอร์โทรศัพท์ Supplier"></div>
                    <div class="col-lg-3 col-sm-3">
                    <input  type="text"  id="productcode" class="form-control form-control-user"  placeholder="lot No."></div>
                    <div class="col-lg-3 col-sm-3">
                    <input type="button" value="ค้นหา" onclick="show_list()" style="  background-color: #00c454; color: white;" class="btn btn-user btn-block" />
                    <hr/>
                    </div> 
                        
                  </div>
                <div class="form-group row" >
                  <div class="col-lg-12 col-sm-12">
                      <table id="order_detail"  class="table table-striped ">
                          <tr style=" background-color:  #99ff99;">
                            <td>#</td>
                            <td align="center">หมายเลข PO</td>
                            <td style="width: 100px;">ชื่อ Supplier</td>
                            <td style="width: 200px;" align="center">จำนวนสินค้าที่รับทั้งหมด</td>
                            <td style="width: 200px;">วันที่รับครั้งแรก</td>
                            <td style="width: 200px;">วันที่รับล่าสุด</td>
                            <td style="width: 200px;">พนักงานที่รับ</td>
                            <td style="width: 200px;">ดำเนินการ</td>
                        </tr>
                        @for ($x=1;$x<=4;$x++)
                        <tr>
                            <td>{{ $x }}</td>
                            <td>PO000{{ $x }}</td>
                            <td>ชื่อ Supplier{{ $x }}</td>
                            <td align="center">100</td>
                            <td align="center">01/08/2563</td>
                            <td align="center">01/08/2563</td>
                            <td align="center">แอดมิน แอดมิน</td>
                            <td style="text-align: center;"><input onclick="adminconfirm()" type="button" value="บันทึกรับ"  style=" background-color: #ffcc00; color: black;" class="btn btn-user btn-block" /></td>
                        </tr>
                        
                        @endfor
                        
                    </table>
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
<div class='modal fade' id='myModal2' role='dialog'>
            <div class='modal-dialog modal-lg' >
                <div class='modal-content'>
                    <div class='modal-header'>
                        <button type='button' class='close' data-dismiss='modal'>&times;</button>
                    </div>
                    <div class='modal-body' >
                        <table class="table table-striped table-responsive " >
                            <tr style=" background-color:  #99ff99;">
                                <td colspan="5">PO. PO00001 </td>
                            </tr>
                            
                            <tr >
                                <td>รหัสสินค้า</td>
                                <td>ชื่อสินค้า</td>
                                <td>จำนวน</td>
                                <td>lot number</td>
                                <td>วันหมดอายุ</td>
                                <td>ชื่อคลัง</td>
                                <td>ยืนยัน</td>
                            </tr>
                            @for ($x=1;$x<=4;$x++)
                            <tr ">
                                <td>PD000{{ $x }}</td>
                                <td>ชื่อสินค้า{{ $x }}</td>
                                <td><input type="number" value="50" /></td>
                                <td><input type="text" value="101" /></td>
                                <td>
                                <input style="border: none;"  class="ddate-input-native" id="date" type="date" name="date" min="2020-01-01" max="2030-12-31">
                                <!--<input class="date-input-fallback" id="alt" type="text" placeholder="Pick A Date">--->
                                <div id="picker" hidden></div>
                                </td>
                                <td>สำนักงานใหญ่</td>
                                <td><input  type="button" value="ยืนยัน"  style="  background-color: #00c454; color: white;" class="btn btn-user"></td>
                            </tr>
                            @endfor
                            <tr  style=" background-color:  white;">
                                <td colspan="5">
                                    <input data-dismiss="modal" type="button" value="ยืนยันทั้งใบ"  style="  background-color: #ffcc00; color: black;" class="btn btn-user">
                                </td>
                            </tr>
                            
                        </table>
                    </div>
                </div>
            </div>
        </div>
  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>
    @endsection
    @section('js') 
      <script type="text/javascript">
        document.getElementById('order_detail').style.display = 'none';
       
    
    
    function show_list(){
        document.getElementById('order_detail').style.display = 'block';
    }
    
    (function(){

  'use strict';

  var dayNamesShort = ['Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa', 'Su'];
  var monthNames = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
  var icon = '<svg viewBox="0 0 512 512"><polygon points="268.395,256 134.559,121.521 206.422,50 411.441,256 206.422,462 134.559,390.477 "/></svg>';

  var root = document.getElementById('picker');
  var dateInput = document.getElementById('date');
  var altInput = document.getElementById('alt');
  var doc = document.documentElement;

  function format ( dt ) {
    return Picker.prototype.pad(dt.getDate()) + ' ' + monthNames[dt.getMonth()].slice(0,3) + ' ' + dt.getFullYear();
  }

  function show ( ) {
    root.removeAttribute('hidden');
  }

  function hide ( ) {
    root.setAttribute('hidden', '');
    doc.removeEventListener('click', hide);
  }

  function onSelectHandler ( ) {

    var value = this.get();

    if ( value.start ) {
      dateInput.value = value.start.Ymd();
      altInput.value = format(value.start);
      hide();
    }
  }

  var picker = new Picker(root, {
    min: new Date(dateInput.min),
    max: new Date(dateInput.max),
    icon: icon,
    twoCalendars: false,
    dayNamesShort: dayNamesShort,
    monthNames: monthNames,
    onSelect: onSelectHandler
  });

  root.parentElement.addEventListener('click', function ( e ) { e.stopPropagation(); });

  dateInput.addEventListener('change', function ( ) {

    if ( dateInput.value ) {
      picker.select(new Date(dateInput.value));
    } else {
      picker.clear();
    }
  });

  altInput.addEventListener('focus', function ( ) {
    altInput.blur();
    show();
    doc.addEventListener('click', hide, false);
  });

}());

function adminconfirm() {
          $('#myModal2').modal('show');
     }
     
     (function(){

  'use strict';

  var dayNamesShort = ['Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa', 'Su'];
  var monthNames = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
  var icon = '<svg viewBox="0 0 512 512"><polygon points="268.395,256 134.559,121.521 206.422,50 411.441,256 206.422,462 134.559,390.477 "/></svg>';

  var root = document.getElementById('picker');
  var dateInput = document.getElementById('date');
  var altInput = document.getElementById('alt');
  var doc = document.documentElement;

  function format ( dt ) {
    return Picker.prototype.pad(dt.getDate()) + ' ' + monthNames[dt.getMonth()].slice(0,3) + ' ' + dt.getFullYear();
  }

  function show ( ) {
    root.removeAttribute('hidden');
  }

  function hide ( ) {
    root.setAttribute('hidden', '');
    doc.removeEventListener('click', hide);
  }

  function onSelectHandler ( ) {

    var value = this.get();

    if ( value.start ) {
      dateInput.value = value.start.Ymd();
      altInput.value = format(value.start);
      hide();
    }
  }

  var picker = new Picker(root, {
    min: new Date(dateInput.min),
    max: new Date(dateInput.max),
    icon: icon,
    twoCalendars: false,
    dayNamesShort: dayNamesShort,
    monthNames: monthNames,
    onSelect: onSelectHandler
  });

  root.parentElement.addEventListener('click', function ( e ) { e.stopPropagation(); });

  dateInput.addEventListener('change', function ( ) {

    if ( dateInput.value ) {
      picker.select(new Date(dateInput.value));
    } else {
      picker.clear();
    }
  });

  altInput.addEventListener('focus', function ( ) {
    altInput.blur();
    show();
    doc.addEventListener('click', hide, false);
  });

}());

    </script>
    @endsection
    
 

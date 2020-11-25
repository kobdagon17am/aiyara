@extends('backend.layouts.master')

@section('title') Aiyara Planet @endsection

@section('css')

@endsection

@section('content')

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0 font-size-18"> การจัดส่งสินค้า  </h4>
        </div>
    </div>
</div>
<!-- end page title -->
  <?php 
      $sPermission = \Auth::user()->permission ;
      $menu_id = @$_REQUEST['menu_id'];
      if($sPermission==1){
        $sC = '';
        $sU = '';
        $sD = '';
        $role_group_id = '%';
      }else{
        $role_group_id = \Auth::user()->role_group_id_fk;
        // echo $role_group_id;
        // echo $menu_id;     
        $menu_permit = DB::table('role_permit')->where('role_group_id_fk',$role_group_id)->where('menu_id_fk',$menu_id)->first();
        $sC = @$menu_permit->c==1?'':'display:none;';
        $sU = @$menu_permit->u==1?'':'display:none;';
        $sD = @$menu_permit->d==1?'':'display:none;';
      }
      // echo $sPermission;
      // echo $role_group_id;
      // echo $menu_id;     
   ?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

               <div class="myBorder">

          <form id="frm-example" action="{{ route('backend.delivery.store') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
            <input type="hidden" name="save_to_pending" value="1" >

            {{ csrf_field() }}
         
                <div class="row">
                  <div class="col-8">
          <!--           <input type="text" class="form-control float-left text-center w130 myLike" placeholder="ค้น : เลขใบรับเรื่อง" name="subject_receipt_number">
                    <input type="text" class="form-control float-left text-center w130 myLike" placeholder="วันที่-เวลา รับเรื่อง" name="receipt_date">
                    <input type="text" class="form-control float-left text-center w130 myLike" placeholder="หัวข้อที่ลูกค้าแจ้ง" name="topics_reported">
                    <input type="text" class="form-control float-left text-center w130 myLike" placeholder="ผู้รับเรื่อง" name="subject_recipient">
                    <input type="text" class="form-control float-left text-center w130 myLike" placeholder="ผู้ดำเนินการ" name="operator"> -->
                  </div>

                  <div class="col-4 text-right" style="{{@$sC}}">
                    <a class="btn btn-info btn-sm mt-1 " href="{{ route('backend.delivery.create') }}?role_group_id={{$role_group_id}}&menu_id={{$menu_id}}">
                      <i class="bx bx-plus font-size-20 align-middle mr-1"></i>ADD 
                    </a>
                  </div>

                </div>

                <table id="data-table" class="table table-bordered dt-responsive" style="width: 100%;">
                </table>

                 <div class="col-md-6 text-right divBtnSave " style="display: none;">
                    <button type="submit" class="btn btn-primary btn-sm waves-effect btnSave ">
                    <i class="bx bx-save font-size-16 align-middle mr-1"></i> บันทึกข้อมูล
                    </button>
                  </div>

              <!--     <p><b>Selected rows data:</b></p>
              <pre id="example-console-rows"></pre>

              <p><b>Form data as submitted to the server:</b></p>
              <pre id="example-console-form"></pre>
            -->

              <div id="last_form"></div>

              </form>



 </div>

              <div class="myBorder">
                      <div style="">
                        <div class="form-group row">
                          <div class="col-md-12">
                            <span style="font-weight: bold;padding-right: 10px;"><i class="bx bx-play"></i> รายการรอจัดส่ง  </span>
                          </div>
                        </div>
                        <div class="form-group row">
                          <div class="col-md-12">
                            <table id="data-table-pending" class="table table-bordered dt-responsive" style="width: 100%;">
                            </table>
                          </div>
                        </div>
                      </div>
                   
                    </div>


            </div>
        </div>
    </div> <!-- end col -->
</div> <!-- end row -->

@endsection

@section('script')
<link type="text/css" href="//gyrocode.github.io/jquery-datatables-checkboxes/1.2.12/css/dataTables.checkboxes.css" rel="stylesheet" />
<script type="text/javascript" src="//gyrocode.github.io/jquery-datatables-checkboxes/1.2.12/js/dataTables.checkboxes.min.js"></script>
<script>
var role_group_id = "{{@$role_group_id?@$role_group_id:0}}"; //alert(sU);
var menu_id = "{{@$menu_id?@$menu_id:0}}"; //alert(sU);
var sU = "{{@$sU}}"; //alert(sU);
var sD = "{{@$sD}}"; //alert(sD);
var oTable;
$(function() {
    oTable = $('#data-table').DataTable({
    "sDom": "<'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>",
        processing: true,
        serverSide: true,
        scroller: true,
        scrollCollapse: true,
        scrollX: true,
        ordering: false,
        scrollY: ''+($(window).height()-370)+'px',
        iDisplayLength: 10,
        // stateSave: true,
        ajax: {
          url: '{{ route('backend.delivery.datatable') }}',
          data: function ( d ) {
            d.Where={};
            $('.myWhere').each(function() {
              if( $.trim($(this).val()) && $.trim($(this).val()) != '0' ){
                d.Where[$(this).attr('name')] = $.trim($(this).val());
              }
            });
            d.Like={};
            $('.myLike').each(function() {
              if( $.trim($(this).val()) && $.trim($(this).val()) != '0' ){
                d.Like[$(this).attr('name')] = $.trim($(this).val());
              }
            });
            d.Custom={};
            $('.myCustom').each(function() {
              if( $.trim($(this).val()) && $.trim($(this).val()) != '0' ){
                d.Custom[$(this).attr('name')] = $.trim($(this).val());
              }
            });
            oData = d;
          },
          method: 'POST'
        },
              columns: [
                  {data: 'id', title :'ID', className: 'text-center w50'},
                  {data: 'receipt', title :'<center>ใบเสร็จ </center>', className: 'text-center'},
                  {data: 'customer_name', title :'<center>ชื่อลูกค้า </center>', className: 'text-center'},
                  {data: 'tel', title :'<center>เบอร์โทร </center>', className: 'text-center'},
                  {data: 'province_name', title :'<center>สาขา </center>', className: 'text-center'},
                  // {data: 'level_class',   title :'<center>Class</center>', className: 'text-center ',render: function(d) {
                  //     return '<span class="badge badge-pill badge-soft-success font-size-16">'+d+'</span>';
                  // }},
                  // {data: 'cover_sheet',   title :'<center>พิมพ์</center>', className: 'text-center ',render: function(d) {
                  //     return '<a href="backend/delivery?'+d+'">ใบประหน้า</a>';
                  // }},
                  {data: 'id', title :'Tools', className: 'text-center w80'}, 
              ],
                    'columnDefs': [
               {
                  'targets': 0,
                  'checkboxes': {
                     'selectRow': true
                  }
               }
            ],
            'select': {
               'style': 'multi'
            },
              rowCallback: function(nRow, aData, dataIndex){
                if(sU!=''&&sD!=''){
                    $('td:last-child', nRow).html('-');
                }else{ 

                    $('td:last-child', nRow).html(''
                      + '<a href="{{ route('backend.delivery.index') }}/'+aData['id']+'/edit?role_group_id='+role_group_id+'&menu_id='+menu_id+'" class="btn btn-sm btn-primary" style="'+sU+'" ><i class="bx bx-edit font-size-16 align-middle"></i></a> '
                      + '<a href="javascript: void(0);" data-url="{{ route('backend.delivery.index') }}/'+aData['id']+'" class="btn btn-sm btn-danger cDelete" style="'+sD+'" ><i class="bx bx-trash font-size-16 align-middle"></i></a>'
                    ).addClass('input');

                }
              }
          });
              $('.myWhere,.myLike,.myCustom,#onlyTrashed').on('change', function(e){
                oTable.draw();
              });
          });



            $('#data-table').on( 'click', 'tr', function () {
              $('.divBtnSave').show();
            } );

           // Handle form submission event 
           $('#frm-example').on('submit', function(e){
              var form = this;

              console.log(form);
              
              var rows_selected = oTable.column(0).checkboxes.selected();

              console.log(rows_selected);

              // Iterate over all selected checkboxes
              $.each(rows_selected, function(index, rowId){

                console.log(rowId);
                // $("#last_form").after("<input type='text' name='row_id[]' value='"+rowId+"' >");
                 // Create a hidden element 
                $('#last_form').after(
                     $('<input>')
                        .attr('type', 'hidden')
                        .attr('name', 'row_id[]')
                        .val(rowId)
                 );
              });

              // FOR DEMONSTRATION ONLY
              // The code below is not needed in production
              
              // Output form data to a console     
              // $('#example-console-rows').text(rows_selected.join(","));
              
              // Output form data to a console     
              // $('#example-console-form').text($(form).serialize());
               
              // Remove added elements
              // $('input[name="id\[\]"]', form).remove();
               
              // Prevent actual form submission
              // e.preventDefault();

               });


          var oTable2;
          $(function() {
              oTable2 = $('#data-table-pending').DataTable({
              "sDom": "<'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>",
                  processing: true,
                  serverSide: true,
                  scroller: true,
                  scrollCollapse: true,
                  scrollX: true,
                  ordering: false,
                  scrollY: ''+($(window).height()-370)+'px',
                  iDisplayLength: 10,
                  // stateSave: true,
                  ajax: {
                    url: '{{ route('backend.delivery_pending.datatable') }}',
                    data: function ( d ) {
                      d.Where={};
                      $('.myWhere').each(function() {
                        if( $.trim($(this).val()) && $.trim($(this).val()) != '0' ){
                          d.Where[$(this).attr('name')] = $.trim($(this).val());
                        }
                      });
                      d.Like={};
                      $('.myLike').each(function() {
                        if( $.trim($(this).val()) && $.trim($(this).val()) != '0' ){
                          d.Like[$(this).attr('name')] = $.trim($(this).val());
                        }
                      });
                      d.Custom={};
                      $('.myCustom').each(function() {
                        if( $.trim($(this).val()) && $.trim($(this).val()) != '0' ){
                          d.Custom[$(this).attr('name')] = $.trim($(this).val());
                        }
                      });
                      oData = d;
                    },
                    method: 'POST'
                  },
                  columns: [
                      {data: 'id', title :'ID', className: 'text-center w50'},
                      // {data: 'receipt', title :'<center>ใบเสร็จ </center>', className: 'text-center'},
                      {data: 'receipt',   title :'<center>ใบเสร็จ</center>', className: 'text-center ',render: function(d) {
                          return '';
                      }},
                      {data: 'customer_name', title :'<center>ชื่อลูกค้า </center>', className: 'text-center'},
                      {data: 'tel', title :'<center>เบอร์โทร </center>', className: 'text-center'},
                      {data: 'province_name', title :'<center>สาขา </center>', className: 'text-center'},
                      {data: 'cover_sheet',   title :'<center>พิมพ์</center>', className: 'text-center ',render: function(d) {
                          return '<a href="backend/delivery?'+d+'">ใบประหน้า</a>';
                      }},
                      {data: 'id', title :'Tools', className: 'text-center w80'}, 
                  ],
                  rowCallback: function(nRow, aData, dataIndex){
                    if(sU!=''&&sD!=''){
                        $('td:last-child', nRow).html('-');
                    }else{ 

                        $('td:last-child', nRow).html(''
                          + '<a href="backend/delivery/" data-url="{{ route('backend.delivery_pending.index') }}/'+aData['id']+'" class="btn btn-sm btn-danger cDelete" style="'+sD+'" ><i class="bx bx-trash font-size-16 align-middle"></i></a>'
                        ).addClass('input');

                    }
                  }
              });


              $('.myWhere,.myLike,.myCustom,#onlyTrashed').on('change', function(e){
                oTable2.draw();
              });
          });

</script>

  <script> 

        

          $(document).ready(function() {

                $(document).on('click', '.dt-checkboxes', function(event) {

                    $('.divBtnSave').show();

                    var array = []; 
                    $("input:checkbox[name=type]:checked").each(function() { 
                        array.push($(this).val()); 
                    }); 

                    console.log(array);


                }); 

                $(document).on('click', '.btnSave', function(event) {

                    var array = []; 
                    $("input:checkbox[name=type]:checked").each(function() { 
                        array.push($(this).val()); 
                    }); 

                    console.log(array);


                }); 

          });
    </script> 


<script type="text/javascript">

  sessionStorage.setItem("role_group_id", role_group_id);
  var role_group_id = sessionStorage.getItem("role_group_id");
  var menu_id = sessionStorage.getItem("menu_id");
    window.onload = function() {
    if(!window.location.hash) {
       window.location = window.location + '?role_group_id=' + role_group_id + '&menu_id=' + menu_id + '#menu_id=' + menu_id ;
    }
  }
</script>

@endsection


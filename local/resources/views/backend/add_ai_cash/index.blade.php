@extends('backend.layouts.master')

@section('title') Aiyara Planet @endsection

@section('css')

@endsection

@section('content')

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0 font-size-18"> เติม Ai-Cash </h4>
        </div>
    </div>
</div>
<!-- end page title -->
  <?php 
    $sPermission = \Auth::user()->permission ;
      // $menu_id = @$_REQUEST['menu_id'];
      $menu_id = Session::get('session_menu_id');
    if($sPermission==1){
      $sC = '';
      $sU = '';
      $sD = '';
    }else{
      $role_group_id = \Auth::user()->role_group_id_fk;
      $menu_permit = DB::table('role_permit')->where('role_group_id_fk',$role_group_id)->where('menu_id_fk',$menu_id)->first();
      $sC = @$menu_permit->c==1?'':'display:none;';
      $sU = @$menu_permit->u==1?'':'display:none;';
      $sD = @$menu_permit->d==1?'':'display:none;';
    }
   ?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                  <div class="col-8">
                    <!-- <input type="text" class="form-control float-left text-center w130 myLike" placeholder="รหัสย่อ" name="short_code"> -->
                  </div>

                  <div class="col-4 text-right" style="{{@$sC}}" >
                    <a class="btn btn-info btn-sm mt-1" href="{{ route('backend.add_ai_cash.create') }}">
                      <i class="bx bx-plus font-size-20 align-middle mr-1"></i>ADD
                    </a>
                  </div>

                </div>

                <table id="data-table" class="table table-bordered dt-responsive" style="width: 100%;">
                </table>

            </div>
        </div>
    </div> <!-- end col -->
</div> <!-- end row -->

@endsection

@section('script')

<script>

var sU = "{{@$sU}}"; 
var sD = "{{@$sD}}";  
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
        iDisplayLength: 25,
        ajax: {
          url: '{{ route('backend.add_ai_cash.datatable') }}',
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
            {data: 'customer_name', title :'<center>ลูกค้า </center>', className: 'text-left'},
            {data: 'aicash_remain', title :'<center>ยอด Ai-Cash <br> คงเหลือล่าสุด</center>', className: 'text-center'},
            {data: 'aicash_amt', title :'<center>ยอด Ai-Cash <br>ที่เติมครั้งนี้</center>', className: 'text-center'},
            {data: 'action_user', title :'<center>พนักงาน <br> ที่ดำเนินการ </center>', className: 'text-center'},
            {data: 'pay_type_id', title :'<center>รูปแบบการชำระเงิน </center>', className: 'text-center'},
            {data: 'total_amt', title :'<center>ยอดชำระเงิน </center>', className: 'text-center'},
            {data: 'updated_at', title :'<center>วันที่ดำเนินการ </center>', className: 'text-center'},
            // {data: 'aicash_amt',   title :'ยอด Ai-Cash ', className: 'text-center ',render: function(d) {
            //     return (parseFloat(d)>0)?d:'-';
            // }},
            {data: 'id', title :'Tools', className: 'text-center w60'}, 
        ],
        rowCallback: function(nRow, aData, dataIndex){

          if(aData['approve_status']==4){
            for (var i = 0; i < 6; i++) {
              $('td:eq( '+i+')', nRow).html(aData[i]).css({'color':'#d9d9d9','text-decoration':'line-through','font-style':'italic'});
            }

            $('td:last-child', nRow).html('-ยกเลิก-');

          }else{

                 if(sU!=''&&sD!=''){
                          $('td:last-child', nRow).html('-');
                      }else{ 

                      $('td:last-child', nRow).html(''
                        + '<a href="{{ route('backend.add_ai_cash.index') }}/'+aData['id']+'/edit" class="btn btn-sm btn-primary"  style="'+sU+'" ><i class="bx bx-edit font-size-16 align-middle"></i></a> '
                        + '<a href="javascript: void(0);" data-url="{{ route('backend.add_ai_cash.index') }}/'+aData['id']+'" class="btn btn-sm btn-danger cDeleteX cDelete " customer_id_fk="'+aData['customer_id_fk']+'"  data-id="'+aData['id']+'" style="'+sD+'" ><i class="bx bx-trash font-size-16 align-middle"></i></a>'
                      ).addClass('input');

                    }

          }

     

        }
    });
    $('.myWhere,.myLike,.myCustom,#onlyTrashed').on('change', function(e){
      oTable.draw();
    });
});


  $(document).ready(function() {

             $(document).on('click', '.cDeleteX', function(event) {

                  var id = $(this).data('id');
                  var customer_id_fk = $(this).attr('customer_id_fk');
                  // alert(id);
                  $.ajax({
                       type:'POST',
                       url: " {{ url('backend/ajaxCheckAddAiCash') }} ", 
                       data: { id:id,customer_id_fk:customer_id_fk },
                        success:function(data){
                               console.log(data); 
                               if(data=="no"){
                                 alert("! ไม่สามารถยกเลิกได้หรือลบได้ เนื่องจากยอด Ai-Cash ถูกใช้ไปแล้ว ");
                                 return false;
                               }
                              $(".myloading").hide();
                          },
                       
                    });

           });

  });


</script>


@endsection


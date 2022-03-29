@extends('backend.layouts.master')

@section('title') Aiyara Planet @endsection

@section('css')
<style>
    @media screen and (min-width: 676px) {
        .modal-dialog {
          max-width: 1200px !important; /* New width for default modal */
        }
    }

    .select2-selection {height: 34px !important;margin-left: 3px;}
    .border-left-0 {height: 67%;}
</style>
@endsection

@section('content')
<div class="myloading"></div>
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0 font-size-18"> หนังสือรับรองการหักภาษี  ณ  ที่จ่าย ตามมาตรา 50 ทวิ  </h4>
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
                  <div class="col-11">
                    
                  <div class="row">
                    <div class="col-12 d-flex ">


                      <div class="col-md-2 ">
                        <div class="form-group row">
                          <select id="business_location_id_fk" name="business_location_id_fk" class="form-control select2-templating " required="" >
                              <option value="">- Business Location -</option>
                              @if(@$sBusiness_location)
                                @foreach(@$sBusiness_location AS $r)
                                <option value="{{$r->id}}" >
                                  {{$r->txt_desc}}
                                </option>
                                @endforeach
                              @endif
                            </select>
                        </div>
                      </div>

          <!--             <div class="col-md-3">
                        <div class="form-group row">
                          <select id="branch_id_fk" name="branch_id_fk" class="form-control select2-templating "  >
                            <option disabled selected >(สาขา) เลือก Business Location ก่อน</option>
                          </select>
                        </div>
                      </div> -->
                    
                      <div class="col-md-4 d-flex  ">
                         <input id="start_date"  autocomplete="off" placeholder="วันเริ่ม"  />
                         <input id="end_date"  autocomplete="off" placeholder="วันสิ้นสุด"  />
                      </div>

                      <div class="col-md-3">
                        <div class="form-group row">
                          <select id="customer_id" name="customer_id" class="form-control select2-templating " required="" >
                              <option value=""> รหัส-ชื่อลูกค้า </option>
                            </select>
                        </div>
                      </div>

                      <div class="col-md-2">
                        <div class="form-group row"> &nbsp; &nbsp;
                          <button type="button" class="btn btn-info btn-sm waves-effect btnProcess " style="font-size: 14px !important;" >
                          <i class="bx bx-cog font-size-16 align-middle mr-1"></i> ประมวลผลภาษีบุคคล
                          </button>
                        </div>
                      </div>
                    </div>
                  </div>
                  </div>

        <!--           <div class="col-1 text-right" style="{{@$sC}}" >
                    <a class="btn btn-info btn-sm mt-1" href="{{ route('backend.taxdata.create') }}">
                      <i class="bx bx-plus font-size-20 align-middle mr-1"></i>ADD
                    </a>
                  </div> -->

                </div>

                <table id="data-table" class="table table-bordered dt-responsive" style="width: 100%;">
                </table>

            </div>
        </div>
    </div> <!-- end col -->
</div> <!-- end row -->

@endsection

@section('script')

    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
    <script src="https://unpkg.com/gijgo@1.9.13/js/gijgo.min.js" type="text/javascript"></script>
    <link href="https://unpkg.com/gijgo@1.9.13/css/gijgo.min.css" rel="stylesheet" type="text/css" />
    <script>
        var today = new Date(new Date().getFullYear(), new Date().getMonth(), new Date().getDate());
        $('#start_date').datepicker({
            // format: 'dd/mm/yyyy',
            // format: 'yyyy-mm-dd',
            format: 'yyyy-mm-dd',
            uiLibrary: 'bootstrap4',
            iconsLibrary: 'fontawesome',
            // minDate: today,
            // maxDate: function () {
            //     return $('#end_date').val();
            // }
        });
        $('#end_date').datepicker({
            // format: 'dd/mm/yyyy',
            format: 'yyyy-mm-dd',
            uiLibrary: 'bootstrap4',
            iconsLibrary: 'fontawesome',
            minDate: function () {
                return $('#start_date').val();
            }
        });

         $('#start_date').change(function(event) {
           $('#end_date').val($(this).val());
         });

  </script>          

<script>

  $(document).ready(function(){
    $("#customer_id").select2({
          minimumInputLength: 2,
          allowClear: true,
          placeholder: '- รหัส-ชื่อลูกค้า -',
          ajax: {
          url: " {{ url('backend/ajaxGetCustomerForFrontstore') }} ",
          type  : 'POST',
          dataType : 'json',
          delay  : 250,
          cache: false,
          data: function (params) {
            console.log(params);
           return {          
            term: params.term  || '',   // search term
            page: params.page  || 1
           };
          },
          processResults: function (data, params) {
           return {
            results: data
           };
          }
         }
        });
  });

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
        // scrollY: ''+($(window).height()-370)+'px',
        iDisplayLength: 25,
        ajax: {
          url: '{{ route('backend.taxdata.datatable') }}',
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
            {data: 'business_location', title :'<center>Business Location </center>', className: 'text-left'},
            {data: 'customer_name', title :'<center>ลูกค้า </center>', className: 'text-left'},
            {data: 'commission_cost',   title :'ค่า Commission ', className: 'text-center ',render: function(d) {
                return (parseFloat(d)>0)?d:'-';
              }},
            {data: 'tax_amount',   title :'ยอดภาษี หัก ณ ที่จ่าย ', className: 'text-center ',render: function(d) {
                return (parseFloat(d)>0)?d:'-';
              }},  
            {data: 'start_date', title :'<center>วันเริ่ม </center>', className: 'text-center'},
            {data: 'end_date', title :'<center>วันสิ้นสุด </center>', className: 'text-center'},
            {data: 'tax_year', title :'<center>งวด/ปีภาษี </center>', className: 'text-center'},
            {data: 'customer_id_fk',   title :'พิมพ์ 50 ทวิ', className: 'text-center ',render: function(d) {
                  return '<center><a href="{{ URL::to('backend/taxdata/taxtvi') }}/'+d+'" target=_blank ><i class="bx bxs-file-pdf grow " style="font-size:24px;cursor:pointer;color:#0099cc;"></i></a></center>';
              }},
            // {data: 'id', title :'Tools', className: 'text-center w60'}, 
        ],
        rowCallback: function(nRow, aData, dataIndex){

            //   if(sU!=''&&sD!=''){
            //       $('td:last-child', nRow).html('-');
            //   }else{ 

            //   $('td:last-child', nRow).html(''
            //     + '<a href="{{ route('backend.taxdata.index') }}/'+aData['id']+'/edit" class="btn btn-sm btn-primary"  style="'+sU+'" ><i class="bx bx-edit font-size-16 align-middle"></i></a> '
            //     + '<a href="javascript: void(0);" data-url="{{ route('backend.taxdata.index') }}/'+aData['id']+'" class="btn btn-sm btn-danger cDelete"  style="'+sD+'" ><i class="bx bx-trash font-size-16 align-middle"></i></a>'
            //   ).addClass('input');

            // }

        }
    });
    $('.myWhere,.myLike,.myCustom,#onlyTrashed').on('change', function(e){
      oTable.draw();
    });
});
</script>

<script type="text/javascript">

       $('#business_location_id_fk').change(function(){

          var business_location_id_fk = this.value;

           if(business_location_id_fk != ''){
             $.ajax({
                  url: " {{ url('backend/ajaxGetBranch') }} ", 
                  method: "post",
                  data: {
                    business_location_id_fk:business_location_id_fk,
                    "_token": "{{ csrf_token() }}", 
                  },
                  success:function(data)
                  { 
                   if(data == ''){
                       alert('ไม่พบข้อมูลสาขา !!.');
                   }else{
                       var layout = '<option value="" selected>- เลือกสาขา -</option>';
                       $.each(data,function(key,value){
                        layout += '<option value='+value.id+'>'+value.b_name+'</option>';
                       });
                       $('#branch_id_fk').html(layout);
                   }
                  }
                })
           }

 
      });

</script>


  <script>


        $(document).ready(function() {
          
            $(document).on('click', '.btnProcess', function(event) {
                  event.preventDefault();
                  var business_location_id_fk = $('#business_location_id_fk').val();
                  var start_date = $('#start_date').val();
                  var end_date = $('#end_date').val();
                  var customer_id = $('#customer_id').val();
                   if(business_location_id_fk==''){
                      $("#business_location_id_fk").select2('open');
                      $("#spinner_frame").hide();
                       return false;
                    }
                    if(start_date==''){
                      $("#start_date").focus();
                      $("#spinner_frame").hide();
                       return false;
                    }
                  
                   if(end_date==''){
                      $("#end_date").focus();
                      $("#spinner_frame").hide();
                       return false;
                    }

                  $(".myloading").show();

                   setTimeout(function(){
                        $(".myloading").hide();
                    },3000);

                      // $.ajax({
                      //   url: " {{ url('backend/ajaxProcessTaxdata') }} ", 
                      //   method: "post",
                        // data: {
                        //   business_location_id_fk:business_location_id_fk,
                        //   start_date:start_date,
                        //   end_date:end_date,
                        //   customer_id:customer_id,
                        //   "_token": "{{ csrf_token() }}", 
                        // },
                      //   success:function(data)
                      //   { 
                      //     console.log(data);
                      //       location.reload();
                      //   }
                      // })

     $('#data-table').DataTable().clear();
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
                            destroy:true,
        ajax: {
          url: '{{ route('backend.taxdata.datatable2') }}',
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
          data: {
                          business_location_id_fk:business_location_id_fk,
                          start_date:start_date,
                          end_date:end_date,
                          customer_id:customer_id,
                          "_token": "{{ csrf_token() }}", 
                        },
          method: 'POST'
        },
     
        columns: [
            {data: 'id', title :'ID', className: 'text-center w50'},
            {data: 'business_location', title :'<center>Business Location </center>', className: 'text-left'},
            {data: 'customer_name', title :'<center>ลูกค้า </center>', className: 'text-left'},
            {data: 'commission_cost',   title :'ค่า Commission ', className: 'text-center ',render: function(d) {
                return (parseFloat(d)>0)?d:'-';
              }},
            {data: 'tax_amount',   title :'ยอดภาษี หัก ณ ที่จ่าย ', className: 'text-center ',render: function(d) {
                return (parseFloat(d)>0)?d:'-';
              }},  
            {data: 'start_date', title :'<center>วันเริ่ม </center>', className: 'text-center'},
            {data: 'end_date', title :'<center>วันสิ้นสุด </center>', className: 'text-center'},
            {data: 'tax_year', title :'<center>งวด/ปีภาษี </center>', className: 'text-center'},
            {data: 'customer_id_fk',   title :'พิมพ์ 50 ทวิ', className: 'text-center ',render: function(d) {
                  return '<center><a href="{{ URL::to('backend/taxdata/taxtvi') }}/'+d+'" target=_blank ><i class="bx bxs-file-pdf grow " style="font-size:24px;cursor:pointer;color:#0099cc;"></i></a></center>';
              }},
            // {data: 'id', title :'Tools', className: 'text-center w60'}, 
        ],
        rowCallback: function(nRow, aData, dataIndex){

            //   if(sU!=''&&sD!=''){
            //       $('td:last-child', nRow).html('-');
            //   }else{ 

            //   $('td:last-child', nRow).html(''
            //     + '<a href="{{ route('backend.taxdata.index') }}/'+aData['id']+'/edit" class="btn btn-sm btn-primary"  style="'+sU+'" ><i class="bx bx-edit font-size-16 align-middle"></i></a> '
            //     + '<a href="javascript: void(0);" data-url="{{ route('backend.taxdata.index') }}/'+aData['id']+'" class="btn btn-sm btn-danger cDelete"  style="'+sD+'" ><i class="bx bx-trash font-size-16 align-middle"></i></a>'
            //   ).addClass('input');

            // }

        }
    });
    $('.myWhere,.myLike,.myCustom,#onlyTrashed').on('change', function(e){
      oTable.draw();
    });
});

           }); 

        }); 
    </script>

@endsection


@extends('backend.layouts.master')

@section('title') Aiyara Planet @endsection

@section('css')
<style>
    .select2-selection {height: 34px !important;margin-left: 3px;}
    .border-left-0 {height: 67%;}

    .form-group {
        margin-bottom: 0rem  !important;
     }

    .btn-outline-secondary {
        margin-bottom: 36% !important;
    }
</style>
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
            <h4 class="mb-0 font-size-18"> Promotions </h4>
        </div>
    </div>
</div>
<!-- end page title -->

<div class="row" >
    <div class="col-12">
        <div class="card">
            <div class="card-body">



            <div class="row " >
                <div class="col-md-6 " >
                  <div class="form-group row">
                    <label for="startDate" class="col-md-3 col-form-label"> ช่วงวัน/Pro. date : </label>
                     <div class="col-md-9 d-flex">
                      <input id="startDate"  autocomplete="off" placeholder="Begin Date"  style="margin-left: 1.5%;border: 1px solid grey;font-weight: bold;color: black" />
                      <input id="endDate"  autocomplete="off" placeholder="End Date"  style="border: 1px solid grey;font-weight: bold;color: black" />
                    </div>
                  </div>
                </div>
                <div class="col-md-6 " >
                  <div class="form-group row">
                    <label for="pcode" class="col-md-3 col-form-label"> Pro. Code : </label>
                     <div class="col-md-9 ">
                      <select id="pcode" name="pcode" class="form-control select2-templating " >
                        <option value="">-Select-</option>
                        @if(@$sPromotions)
                        @foreach(@$sPromotions AS $r)
                        <option value="{{$r->pcode}}" >
                          {{$r->pcode}}
                        </option>
                        @endforeach
                        @endif
                      </select>
                    </div>
                  </div>
                </div>
              </div>

  <div class="row " >
              <div class="col-md-6 " >
                  <div class="form-group row">
                    <label for="pstatus" class="col-md-3 col-form-label"> สถานะ/Status :  </label>
                    <div class="col-md-9">
                      <select id="pstatus" name="pstatus" class="form-control select2-templating " >
                         <option value="">-Select-</option>
                         <option value="1">Show / แสดงใช้งาน</option>
                         <option value="0">Not Show / ไม่แสดง</option>
                      </select>
                    </div>
                  </div>
                </div>


                <div class="col-md-6 " >
                 <div class="form-group row">
                    <label for="branch_id_fk" class="col-md-3 col-form-label">  </label>
                     <div class="col-md-9" >
                     <a class="btn btn-info btn-sm btnSearch01 " href="#" style="font-size: 14px !important;margin-left: 0.8%;" >
                        <i class="bx bx-search align-middle "></i> SEARCH
                      </a>
                    </div>
                  </div>
                </div>
                <div class="col-md-6 " >
                    <div class="form-group row">

                  </div>
                </div>
              </div>

                <div class="row">
                  <div class="col-8">
                  </div>
                  <div class="col-4 text-right" >
                    <a class="btn btn-info btn-sm mt-1 class_btn_add " href="{{ route('backend.promotions.create') }}">
                      <i class="bx bx-plus font-size-20 align-middle mr-1"></i>ADD
                    </a>
                  </div>
                </div>

                <table id="data-table" class="table table-bordered " style="width: 100%;">
                </table>

            </div>
        </div>
    </div> <!-- end col -->
</div> <!-- end row -->

@endsection

@section('script')

<script>

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
          url: '{{ route('backend.promotions.datatable') }}',
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
            {data: 'id', title :'*', className: 'text-center w50'},
            {data: 'pcode', title :'<center>Promotion Code </center>', className: 'text-center'},
            {data: 'name_thai', title :'<center> Promotion name </center>', className: 'text-left'},
            {data: 'prodate', title :'<center> Promotion date</center>', className: 'text-center'},
            {data: 'proprice', title :'<center> Promotion Price / PV </center>', className: 'text-center'},
            {data: 'status',   title :'<center>Status</center>', className: 'text-center',render: function(d) {
               return d==1?'<span style="color:blue">แสดงใช้งาน</span>':'<span style="color:red">ปิด/ไม่แสดง</span>';
            }},
            {data: 'id', title :'Tools', className: 'text-center w60'},
        ],
        rowCallback: function(nRow, aData, dataIndex){

              var sPermission = "<?=\Auth::user()->permission?>";
              var sU = sessionStorage.getItem("sU");
              var sD = sessionStorage.getItem("sD");
              if(sPermission==1){
                sU = 1;
                sD = 1;
              }
              var str_U = '';
              if(sU=='1'){
                str_U = '<a href="{{ route('backend.promotions.index') }}/'+aData['id']+'/edit" class="btn btn-sm btn-primary"><i class="bx bx-edit font-size-16 align-middle"></i></a> ';
              }
              var str_D = '';
              if(sD=='1'){
                str_D = '<a href="javascript: void(0);" data-url="{{ route('backend.promotions.index') }}/'+aData['id']+'" class="btn btn-sm btn-danger cDelete"><i class="bx bx-trash font-size-16 align-middle"></i></a>';
              }
              if(sU!='1' && sD!='1'){
                 $('td:last-child', nRow).html('-');
              }else{
                $('td:last-child', nRow).html( str_U + str_D).addClass('input');
              }


        }
    });

});
</script>


<script>

        $(document).ready(function() {

            $(document).on('click', '.btnSearch01', function(event) {
                   $(".myloading").show();
                  event.preventDefault();

                  var startDate = $('#startDate').val();
                  var endDate = $('#endDate').val();
                  var pcode = $('#pcode').val();
                  var pstatus = $('#pstatus').val();
                  // console.log(tr_status);
                  // alert(pstatus);

                    // @@@@@@@@@@@@@@@@@@@@@@@@@@ datatables @@@@@@@@@@@@@@@@@@@@@@@@@@

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
                              // iDisplayLength: 25,
                              destroy:true,

                              ajax: {
                                      url: '{{ route('backend.promotions.datatable') }}',
                                      data :{
                                        _token: '{{csrf_token()}}',
                                            startDate:startDate,
                                            endDate:endDate,
                                            pcode:pcode,
                                            status:pstatus,
                                          },
                                        method: 'POST',
                                      },

                              columns: [
                                  {data: 'id', title :'*', className: 'text-center w50'},
                                  {data: 'pcode', title :'<center>Promotion Code </center>', className: 'text-center'},
                                  {data: 'name_thai', title :'<center> Promotion name </center>', className: 'text-left'},
                                  {data: 'prodate', title :'<center> Promotion date</center>', className: 'text-center'},
                                  {data: 'proprice', title :'<center> Promotion Price / PV </center>', className: 'text-center'},
                                  {data: 'status',   title :'<center>Status</center>', className: 'text-center',render: function(d) {
                                     return d==1?'<span style="color:blue">Active</span>':'<span style="color:red">In-Active</span>';
                                  }},
                                  {data: 'id', title :'Tools', className: 'text-center w60'},
                              ],
                              rowCallback: function(nRow, aData, dataIndex){

                                    var sPermission = "<?=\Auth::user()->permission?>";
                                    var sU = sessionStorage.getItem("sU");
                                    var sD = sessionStorage.getItem("sD");
                                    if(sPermission==1){
                                      sU = 1;
                                      sD = 1;
                                    }
                                    var str_U = '';
                                    if(sU=='1'){
                                      str_U = '<a href="{{ route('backend.promotions.index') }}/'+aData['id']+'/edit" class="btn btn-sm btn-primary"><i class="bx bx-edit font-size-16 align-middle"></i></a> ';
                                    }
                                    var str_D = '';
                                    if(sD=='1'){
                                      str_D = '<a href="javascript: void(0);" data-url="{{ route('backend.promotions.index') }}/'+aData['id']+'" class="btn btn-sm btn-danger cDelete"><i class="bx bx-trash font-size-16 align-middle"></i></a>';
                                    }
                                    if(sU!='1' && sD!='1'){
                                       $('td:last-child', nRow).html('-');
                                    }else{
                                      $('td:last-child', nRow).html( str_U + str_D).addClass('input');
                                    }


                              }
                          });

                      });
                    // @@@@@@@@@@@@@@@@@@@@@@@@@@ datatables @@@@@@@@@@@@@@@@@@@@@@@@@@

                setTimeout(function(){
                   $(".myloading").hide();
                }, 1500);


            });
          });

    </script>



  <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
    <script src="https://unpkg.com/gijgo@1.9.13/js/gijgo.min.js" type="text/javascript"></script>
    <link href="https://unpkg.com/gijgo@1.9.13/css/gijgo.min.css" rel="stylesheet" type="text/css" />
    <script>
      $('#startDate').datepicker({
          // format: 'dd/mm/yyyy',
          format: 'yyyy-mm-dd',
          uiLibrary: 'bootstrap4',
          iconsLibrary: 'fontawesome',
      });

      $('#endDate').datepicker({
          // format: 'dd/mm/yyyy',
          format: 'yyyy-mm-dd',
          uiLibrary: 'bootstrap4',
          iconsLibrary: 'fontawesome',
          minDate: function () {
              return $('#startDate').val();
          }
      });


      $('#startDate').change(function(event) {

        if($('#endDate').val()>$(this).val()){
        }else{
          $('#endDate').val($(this).val());
        }

      });



    </script>


@endsection


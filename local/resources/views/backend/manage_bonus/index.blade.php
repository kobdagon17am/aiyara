@extends('backend.layouts.master')

@section('title') Aiyara Planet @endsection

@section('css')

@endsection

@section('content')

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0 font-size-18"> โบนัสบริหารทีม </h4>
        </div>
    </div>
</div>
<!-- end page title -->

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                  <div class="col-8">
                    <!-- <input type="text" class="form-control float-left text-center w130 myLike" placeholder="" name="package_name"> -->
                  </div>

                </div>
                @if (session('success'))
                    <br>
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif
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
          url: '{{ route('backend.manage_bonus.datatable') }}',
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
            {data: 'package_name', title :'<center>Package</center>', className: 'text-left'},

            {data: 'bonus_perday',title :'<center>บาท/วัน</center>', className: 'text-center',render: function(d) {
                return d>0?d:'';
            }},  
            {data: 'bonus_perround',title :'<center>บาท/รอบ</center>', className: 'text-center',render: function(d) {
                return d>0?d:'';
            }},  
            {data: 'bonus_permonth',title :'<center>บาท/เดือน</center>', className: 'text-center',render: function(d) {
                return d>0?d:'';
            }},  
            {data: 'benefit',title :'<center>รักษาผลประโยชน์</center>', className: 'text-center',render: function(d) {
                return d;
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
                str_U = '<a href="{{ route('backend.manage_bonus.index') }}/'+aData['id']+'/edit" class="btn btn-sm btn-primary" ><i class="bx bx-edit font-size-16 align-middle"></i></a> ';
              }
              var str_D = '';
              if(sD=='1'){
                str_D = '<button class="btn btn-sm btn-danger" onclick="go_to_rem('+aData['id']+')"><i class="bx bx-trash font-size-16 align-middle"></i></button>';
                // str_D = '<a href="javascript: void(0);" data-url="{{ route('backend.manage_bonus.index') }}/'+aData['id']+'" class="btn btn-sm btn-danger cDelete" ><i class="bx bx-trash font-size-16 align-middle"></i></a>';
              }
              if(sU!='1' && sD!='1'){
                 $('td:last-child', nRow).html('-');
              }else{
                $('td:last-child', nRow).html( str_U + str_D).addClass('input');
              }
              

        }
    });
    $('.myWhere,.myLike,.myCustom,#onlyTrashed').on('change', function(e){
      oTable.draw();
    });
});
function go_to_rem(id){
      if (confirm('Are you sure?')) {
          window.location.replace("{{ route('backend.manage_bonus.index') }}/"+id)
      }
    }
</script>


@endsection


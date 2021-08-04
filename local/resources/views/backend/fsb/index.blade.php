@extends('backend.layouts.master')

@section('title') Aiyara Planet @endsection

@section('css')

@endsection

@section('content')

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0 font-size-18"> โบนัสค่าแนะนำ </h4>
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

                <table id="data-table" class="table table-bordered dt-responsive" style="width: 100%;">
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
          url: '{{ route('backend.fsb.datatable') }}',
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

            // {data: 'g1', title :'<center>G1</center>', className: 'text-center'},
            // {data: 'g2', title :'<center>G2</center>', className: 'text-center'},
            // {data: 'g3', title :'<center>G3</center>', className: 'text-center'},
            // {data: 'g4', title :'<center>G4</center>', className: 'text-center'},
            // {data: 'g5', title :'<center>G5</center>', className: 'text-center'},

            {data: 'g1',title :'<center>G1</center>', className: 'text-center',render: function(d) {
                return d>0?d:'';
            }},  
            {data: 'g2',title :'<center>G2</center>', className: 'text-center',render: function(d) {
                return d>0?d:'';
            }},  
            {data: 'g3',title :'<center>G3</center>', className: 'text-center',render: function(d) {
                return d>0?d:'';
            }},  
            {data: 'g4',title :'<center>G4</center>', className: 'text-center',render: function(d) {
                return d>0?d:'';
            }},  
            {data: 'g5',title :'<center>G5</center>', className: 'text-center',render: function(d) {
                return d>0?d:'';
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
                str_U = '<a href="{{ route('backend.fsb.index') }}/'+aData['id']+'/edit" class="btn btn-sm btn-primary"  ><i class="bx bx-edit font-size-16 align-middle"></i></a> ';
              }
              var str_D = '';
              if(sD=='1'){
                str_D = '<a href="javascript: void(0);" data-url="{{ route('backend.fsb.index') }}/'+aData['id']+'" class="btn btn-sm btn-danger cDelete" ><i class="bx bx-trash font-size-16 align-middle"></i></a>';
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
</script>


@endsection


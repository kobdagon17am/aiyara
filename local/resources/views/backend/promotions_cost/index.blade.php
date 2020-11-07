@extends('backend.layouts.master')

@section('title') Aiyara Planet @endsection

@section('css')

@endsection

@section('content')

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0 font-size-18"> ข้อมูลราคา </h4>
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
                    <!-- <input type="text" class="form-control float-left text-center w130 myLike" placeholder="รหัสย่อ" name="short_code"> -->

                  <!--   <div class="col-md-6">
                        <a class="btn btn-secondary btn-sm waves-effect" href="{{ route('backend.promotions.index') }}" }}">
                          <i class="bx bx-arrow-back font-size-16 align-middle mr-1"></i> ย้อนกลับ
                        </a>
                    </div> -->

                  </div>

                  <div class="col-4 text-right">
                    <a class="btn btn-info btn-sm mt-1" href="{{ route('backend.promotions_cost.create') }}">
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

  // var promotion_id_fk = "{{@$sRow->id?@$sRow->id:0}}";
  var promotion_id_fk = "0";

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
            url: '{{ route('backend.promotions_cost.datatable') }}',
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
            {data: 'business_location', title :'<center>BUSINESS LOCATION</center>', className: 'text-left'},
            {data: 'country', title :'<center>ประเทศ</center>', className: 'text-left'},
            {data: 'currency', title :'<center>สกุลเงิน</center>', className: 'text-left'},
            {data: 'cost_price', title :'<center>ราคาทุน</center>', className: 'text-left'},
            {data: 'selling_price', title :'<center>ราคาขาย</center>', className: 'text-left'},
            {data: 'member_price', title :'<center>ราคาสมาชิก</center>', className: 'text-left'},
            {data: 'pv', title :'<center>PV</center>', className: 'text-left'},
            {data: 'status',   title :'<center>สถานะ</center>', className: 'text-center',render: function(d) {
               return d==1?'<span style="color:blue">เปิดใชช้งาน</span>':'<span style="color:red">ปิด</span>';
            }},
            {data: 'id', title :'Tools', className: 'text-center w60'}, 
        ],
        rowCallback: function(nRow, aData, dataIndex){
          $('td:last-child', nRow).html(''
            + '<a href="{{ route('backend.promotions_cost.index') }}/'+aData['id']+'/edit" class="btn btn-sm btn-primary"><i class="bx bx-edit font-size-16 align-middle"></i></a> '
            + '<a href="javascript: void(0);" data-url="{{ route('backend.promotions_cost.index') }}/'+aData['id']+'" class="btn btn-sm btn-danger cDelete"><i class="bx bx-trash font-size-16 align-middle"></i></a>'
          ).addClass('input');
        }
    });
    $('.myWhere,.myLike,.myCustom,#onlyTrashed').on('change', function(e){
      oTable.draw();
    });
});
</script>
@endsection


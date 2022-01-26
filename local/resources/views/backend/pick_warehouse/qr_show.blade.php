
@extends('backend.layouts.master')

@section('title') Aiyara Planet @endsection

@section('css')
<style type="text/css">
  table.minimalistBlack {
    border: 2px solid #000000;
    width: 100%;
    text-align: left;
    border-collapse: collapse;
  }
  table.minimalistBlack td, table.minimalistBlack th {
    border: 1px solid #000000;
    padding: 5px 4px;
  }
  table.minimalistBlack tbody td {
    font-size: 13px;
  }
  table.minimalistBlack thead {
    background: #CFCFCF;
    background: -moz-linear-gradient(top, #dbdbdb 0%, #d3d3d3 66%, #CFCFCF 100%);
    background: -webkit-linear-gradient(top, #dbdbdb 0%, #d3d3d3 66%, #CFCFCF 100%);
    background: linear-gradient(to bottom, #dbdbdb 0%, #d3d3d3 66%, #CFCFCF 100%);
    border-bottom: 2px solid #000000;
  }
  table.minimalistBlack thead th {
    font-size: 15px;
    font-weight: bold;
    color: #000000;
    text-align: center;
  }
  table.minimalistBlack tfoot td {
    font-size: 14px;
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

  .tooltip_packing {
    position: relative ;
  }
  .tooltip_packing:hover::after {
    content: "Packing List" ;
    position: absolute ;
    /*top: 0.5em ;*/
    left: -4em ;
    min-width: 80px ;
    border: 1px #808080 solid ;
    padding: 1px ;
    color: black ;
    background-color: #cfc ;
    z-index: 9999 ;
  } 


.divTable{
    display: table;
    width: 100%;
    
  }
  .divTableRow {
    display: table-row;
  }
  .divTableHeading {
    background-color: #EEE;
    display: table-header-group;
  }
  .divTableCell, .divTableHead {
    border: 1px solid white;
    display: table-cell;
    padding: 3px 6px;
    word-break: break-all; 
  }
  .divTableHeading {
    background-color: #EEE;
    display: table-header-group;
    font-weight: bold;
  }
  .divTableFoot {
    background-color: #EEE;
    display: table-footer-group;
    font-weight: bold;
  }
  .divTableBody {
    display: table-row-group;
  }
  .divTH {text-align: right;}



</style>
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            รายการ QR CODE สินค้า {{$product->product_code}} : {{$product->product_name}}
            <br><br>
            @foreach($qrs as $qr)
            {{$qr->item_id}} : 
            <input type="text" class="in-tx qr_scan " data-item_id="{{$qr->item_id}}" invoice_code="{{$qr->invoice_code}}" data-packing_code="{{$qr->packing_code}}" data-product_id_fk="{{$qr->product_id_fk}}" placeholder="scan qr" value="{{$qr->qr_code}}">
            &nbsp;       &nbsp;       &nbsp;
            <input type="text" class="in-tx qr_scan_remark " data-item_id="{{$qr->item_id}}" invoice_code="{{$qr->invoice_code}}" data-packing_code="{{$qr->packing_code}}" data-product_id_fk="{{$qr->product_id_fk}}" placeholder="หมายเหตุ" value="{{$qr->remark}}">
            <br><br>
            @endforeach 
            <div class="myloading"></div>
        </div>
    </div>
</div>
     
@endsection
@section('script')
<link type="text/css" href="//gyrocode.github.io/jquery-datatables-checkboxes/1.2.12/css/dataTables.checkboxes.css" rel="stylesheet" />
<script type="text/javascript" src="//gyrocode.github.io/jquery-datatables-checkboxes/1.2.12/js/dataTables.checkboxes.min.js"></script>

<script>
     $(document).on('change', '.qr_scan', function(e) {
            var v = $(this).val();
            var item_id = $(this).data('item_id');
            var packing_code = $(this).data('packing_code');
            var product_id_fk = $(this).data('product_id_fk');
            var invoice_code = $(this).attr('invoice_code');
            // alert(v+":"+invoice_code+":"+product_id_fk);
            if($(this).val()!=''){
            $(this).css({ 'background-color' : '', 'opacity' : '' });
            }

            $(".myloading").show();

            $.ajax({
                type:'POST',
                url: " {{ url('backend/ajaxScanQrcodeProductPacking') }} ",
                data:{ _token: '{{csrf_token()}}',
                 item_id:item_id,
                invoice_code:invoice_code,
                qr_code:v,packing_code:packing_code,product_id_fk:product_id_fk },
                    success:function(data){
                        // console.log(data);
                        $.each(data,function(key,value){
                        });
                        $(".myloading").hide();
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        $(".myloading").hide();
                    }
                });
            });

            $(document).on('change', '.qr_scan_remark', function(e) {
            var v = $(this).val();
            var item_id = $(this).data('item_id');
            var packing_code = $(this).data('packing_code');
            var product_id_fk = $(this).data('product_id_fk');
            var invoice_code = $(this).attr('invoice_code');
            // alert(v+":"+invoice_code+":"+product_id_fk);
            if($(this).val()!=''){
            $(this).css({ 'background-color' : '', 'opacity' : '' });
            }

            $(".myloading").show();

            $.ajax({
                type:'POST',
                url: " {{ url('backend/ajaxScanQrcodeProductPackingRemark') }} ",
                data:{ _token: '{{csrf_token()}}',
                 item_id:item_id,
                invoice_code:invoice_code,
                remark:v,
                packing_code:packing_code,
                product_id_fk:product_id_fk },
                    success:function(data){
                        // console.log(data);
                        $.each(data,function(key,value){
                        });
                        $(".myloading").hide();
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        $(".myloading").hide();
                    }
                });
            });
</script>

@endsection




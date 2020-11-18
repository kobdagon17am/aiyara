@extends('backend.layouts.master')

@section('title') Aiyara Planet @endsection

@section('css')

@endsection

@section('content')

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0 font-size-18"> PM broadcast  </h4>
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
      }else{
        $role_group_id = \Auth::user()->role_group_id_fk;
        $menu_permit = DB::table('role_permit')->where('role_group_id_fk',$role_group_id)->where('menu_id_fk',$menu_id)->first();
        $sC = @$menu_permit->c==1?'':'display:none;';
        $sU = @$menu_permit->u==1?'':'display:none;';
        $sD = @$menu_permit->d==1?'':'display:none;';
      }
   ?>


  <div class="myBorder" >

    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-body">

                   

                        <form class="form-horizontal" method="POST" action="backend/uploadFile" enctype="multipart/form-data">
                            {{ csrf_field() }}

                            <div class="form-group{{ @$errors->has('csv_file') ? ' has-error' : '' }}">
                                <label for="csv_file" class="col-md-4 control-label"><b>CSV file to import</b></label>

                                <div class="col-md-6">
                                    <input type="file" accept=".csv" class="form-control" name="file" required>

                                    @if (@$errors->has('csv_file'))
                                        <span class="help-block">
                                        <strong>{{ @$errors->first('csv_file') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <div class="checkbox">
                                     <!--    <label>
                                            <input type="checkbox" name="header" checked> File contains header row?
                                        </label> -->
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-8 col-md-offset-4">
                                    <input type='submit' name='submit' class="btn btn-primary" value='Import CSV'>
                                    <input type='button' class="btn btn-danger btnClearData " value='(Test) Clear data' style="float: right;">
                                </div>
                            </div>

                             <!-- Message -->
                                 @if(Session::has('message'))
                                    <p style="color:green;font-weight:bold;margin-left: 2%;font-size: 16px;" >{{ Session::get('message') }}</p>
                                 @endif


                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                <table id="data-table" class="table table-bordered dt-responsive" style="width: 100%;">
                </table>

            </div>
        </div>
    </div> <!-- end col -->
</div> <!-- end row -->

@endsection

@section('script')

<script>
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
        iDisplayLength: 25,
        ajax: {
          url: '{{ route('backend.pm_broadcast.datatable') }}',
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
            {data: 'customers_id_fk', title :'<center>รหัสสมาชิก (ลูกค้า) </center>', className: 'text-center'},
            {data: 'txt_msg', title :'<center>ข้อความ</center>', className: 'text-center'},
        ],
        rowCallback: function(nRow, aData, dataIndex){
          
        }
    });
    $('.myWhere,.myLike,.myCustom,#onlyTrashed').on('change', function(e){
      oTable.draw();
    });
});

$(document).ready(function() {
    $(".btnClearData").click(function(event) {
        /* Act on the event */
        $.ajax({

               type:'POST',
               url: " {{ url('backend/ajaxClearDataPm_broadcast') }} ", 
               data:{ _token: '{{csrf_token()}}' },
                success:function(data){
                     console.log(data); 
                     location.reload();
                  },
                error: function(jqXHR, textStatus, errorThrown) { 
                    console.log(JSON.stringify(jqXHR));
                    console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                }
            });
    });
});


</script>


@endsection


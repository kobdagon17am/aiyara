@extends('backend.layouts.master')

@section('title') Aiyara Planet @endsection

@section('css')

@endsection

@section('content')
<div class="myloading"></div>
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
       
            <div class="col-12">
                <div class="panel panel-default">
                    <div class="panel-body">
                      
                      <div class="row">
                        <div class="col-4">
                          <form class="form-horizontal" method="POST" action="backend/uploadFile" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <div class="form-group{{ @$errors->has('csv_file') ? ' has-error' : '' }}">
                              <label for="csv_file" class="col-md-5 control-label"><b>CSV file to import</b></label>
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
                              <div class="col-md-10 col-md-offset-4">
                                <input type='submit' name='submit' class="btn btn-primary btnImCsv " value='Import CSV'>
                              </div>
                            </div>
                            
                          </form>
                        </div>

                        <div class="col-6">
                          <form class="form-horizontal" method="POST" action="backend/uploadFileXLS" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <div class="form-group">
                              <label for="csv_file" class="col-md-4 control-label"><b>XLSX file to import</b></label>
                              <div class="col-md-6">
                                <input type="file" accept=".xlsx" class="form-control" name="fileXLS" required>
                              </div>
                            </div>
                            <div class="form-group">
                              <div class="col-md-10 col-md-offset-4">
                                <input type='submit' name='submit' class="btn btn-primary btnImXlsx " value='Import XLSX'>
                              </div>
                            </div>
                          </form>

                        </div>

                         <div class="col-2">

                            @if(Session::has('message'))
                            <p style="color:green;font-weight:bold;font-size: 16px;" >{{ Session::get('message') }}</p>
                            @endif

                        </div>
                     

                </div>

                <hr>

                <div class="row">

                  <div class="col-4">
                    <div class="form-group"> &nbsp;&nbsp;
                      <input type='button' class="btn btn-success btnExportCSV " value='Export CSV'> &nbsp;&nbsp;
                    </div>
                  </div>
           
                  <div class="col-4">
                    <div class="form-group"> &nbsp;&nbsp;
                       <input type='button' class="btn btn-success btnExportElsx " value='Export Excel'>
                    </div>
                  </div>

                  <div class="col-4">
                    <div class="form-group"> &nbsp;&nbsp;
                      <input type='button' class="btn btn-danger btnClearData " value='Clear data' >
                    </div>
                  </div>


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

                <div class="row">
                    <input type="text" class="form-control float-left text-center w130 myLike" placeholder="ค้า : รหัสสมาชิก" name="customers_id_fk"> 
                    <input type="text" class="form-control float-left text-center w130 myLike" placeholder="ค้น : Remark" name="remark" style="margin-left: 1%;" >
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
            {data: 'show_from', title :'<center>From</center>', className: 'text-center'},
            {data: 'show_to', title :'<center>To</center>', className: 'text-center'},
            {data: 'remark', title :'<center>Remark</center>', className: 'text-center'},
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
        $(".myloading").show();

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
                    $(".myloading").hide();
                }
            });
    });


    $(".btnExportElsx").click(function(event) {
        /* Act on the event */
        $(".myloading").show();
        $.ajax({

               type:'POST',
               url: " {{ url('backend/excelExport') }} ", 
               data:{ _token: '{{csrf_token()}}' },
                success:function(data){
                     console.log(data); 
                     // location.reload();

                     setTimeout(function(){
                        var url='local/public/excel_files/pm_broadcast.xlsx';
                        window.open(url, 'Download');  
                        $(".myloading").hide();
                    },3000);

                  },
                error: function(jqXHR, textStatus, errorThrown) { 
                    console.log(JSON.stringify(jqXHR));
                    console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                    $(".myloading").hide();
                }
            });
    });


    $(".btnExportCSV").click(function(event) {
        /* Act on the event */
        $(".myloading").show();
        $.ajax({

               type:'POST',
               url: " {{ url('backend/csvExport') }} ", 
               data:{ _token: '{{csrf_token()}}' },
                success:function(data){
                     console.log(data); 
                     // location.reload();

                     setTimeout(function(){
                        var url='local/public/excel_files/pm_broadcast.csv';
                        window.open(url, 'Download');  
                        $(".myloading").hide();
                    },3000);

                  },
                error: function(jqXHR, textStatus, errorThrown) { 
                    console.log(JSON.stringify(jqXHR));
                    console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                    $(".myloading").hide();
                }
            });
    });

   $(".btnImCsv").click(function(event) {
          /* Act on the event */
          var v = $("input[name=file]").val();
          if(v!=''){
            $(".myloading").show();
          }
          
    });

   $(".btnImXlsx").click(function(event) {
          /* Act on the event */
          var v = $("input[name=fileXLS]").val();
          if(v!=''){
            $(".myloading").show();
          }
          
    });


});


</script>


@endsection


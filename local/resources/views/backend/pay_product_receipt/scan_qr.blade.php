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
            <h4 class="mb-0 font-size-20"> สแกน QR-CODE สินค้ารายชิ้น </h4>

                      <a class="btn btn-secondary btn-sm waves-effect" href="{{ url("backend/pay_product_receipt") }}">
                          <i class="bx bx-arrow-back font-size-16 align-middle mr-1"></i> ย้อนกลับ
                      </a>

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
        $role_group_id = '%';
        $can_packing_list = '1';
        $can_payproduct = '1';
      }else{
        $role_group_id = \Auth::user()->role_group_id_fk;
        // echo $role_group_id;
        // echo $menu_id;     
        $menu_permit = DB::table('role_permit')->where('role_group_id_fk',$role_group_id)->where('menu_id_fk',$menu_id)->first();
        $sC = @$menu_permit->c==1?'':'display:none;';
        $sU = @$menu_permit->u==1?'':'display:none;';
        $sD = @$menu_permit->d==1?'':'display:none;';
        $can_packing_list = @$menu_permit->can_packing_list==1?'1':'0';
        $can_payproduct = @$menu_permit->can_payproduct==1?'1':'0';
      }
      // echo $sPermission;
      // echo $role_group_id;
      // echo $menu_id;     
      // echo  @$menu_permit->can_packing_list;     
      // echo  @$menu_permit->can_payproduct;     
      // echo $can_packing_list."xxxxxxxxxxxxxxxxxxxxxxxxxxx";     
   ?>

<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-body">

        <div class="myBorder">

          <div class="form-group row ">
            <div class="col-md-12" style="font-size: 16px;color: black;font-weight: bold;">
             <i class="bx bx-play"></i> สินค้า : {{@$product_name}}  > จำนวน {{@$amt}} รายการ
          </div>
          </div>


          <form id="frm-example" action="{{ route('backend.pay_product_receipt.store') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
            <input type="hidden" name="save_to_qrscan" value="1" >
            <input type="hidden" name="product_id_fk" value="{{@$sRow[0]->product_id_fk}}" >
            <input type="hidden" name="pick_warehouse_tmp_id_fk" value="{{@$id}}" >

            {{ csrf_field() }}


<?php 
for ($i=1; $i <= @$amt ; $i++) { 

?>
          <div class="form-group row ">
            <div class="col-md-10 d-flex  ">
              <label class="col-5" > ({{$i}}) </label>
              <div class="col-md-5">

                <input type="text" class="form-control" name="txtScan[]" style="font-size: 16px !important;color: blue;" >

              </div>

            </div>
          </div>
<?php 
}
?>


          <div class="form-group row ">
            <div class="col-md-10 d-flex  ">
              <label class="col-5" ></label>
              <div class="col-md-5" >
    
                <button type="submit" class="btn btn-primary btn-sm waves-effect btnScan " style="font-size: 16px !important;">
                   <i class="bx bx-search align-middle "></i> SCAN
                    </button>

            </div>
            </div>
          </div>

</form>

        </div>
        
      </div>
    </div>
  </div>
</div>

@endsection

@section('script')

 

@endsection



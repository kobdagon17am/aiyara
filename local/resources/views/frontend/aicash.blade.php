  
@extends('frontend.layouts.customer.customer_app')
@section('conten')

<div class="row">


  <div class="col-md-4 col-xl-4">
    <div class="card widget-statstic-card borderless-card">
      <div class="card-header">
        <div class="card-header-left">
          <h4>Ai-Cash</h4>
          <p class="m-b-0">จำนวนเงินคงเหลือ{{-- <span class="f-right">$542</span> --}}</p>
        </div>
      </div>
      <div class="card-block">
        <i class="fa fa-money st-icon bg-success"></i>
        <div class="text-left">
          <h3 class="d-inline-block text-success">฿ {{ number_format(Auth::guard('c_user')->user()->ai_cash) }}</h3>
        </div>
      </div>
    </div>
  </div>

  <div class="col-md-8 col-xl-8">
    <div class="card">
      <div class="card-block">
        <h5>เติม Ai-Cash</h5>

        <div class="form-group row">
          <div class="col-md-6 m-t-5">
            <form action="{{ route('cart_payment_aicash') }}" id="cart_payment_aicash" method="post">
              @csrf 
              <div class="input-group input-group-button">
                <input type="text" id="price" name="price" class="form-control autonumber" data-v-max="999999" placeholder="กรุณาใส่จำนวนเงิน">
                <span class="input-group-addon btn btn-primary" onclick="add_aicash()">
                  <span class="">ทำรายการ</span>
                </span>
              </div>
            </form>
          </div>
        </div>

      </div>
    </div>
  </div>

</div>

<div class="row">
  <div class="col-md-12">
    <div class="card">
      <div class="card-header">
        <h5>ประวัติการเติม Ai-Cash</h5>
        {{-- <span>DataTables has most features enabled by default, so all you need to do to use it with your own ables is to call the construction function: $().DataTable();.</span> --}}
      </div>  

      <div class="card-block">
        <div class="dt-responsive table-responsive">
          <table id="simpletable" class="table table-striped table-bordered nowrap dataTable">
            <thead>
              <tr>
                <th>#</th>
                <th>Date</th>
                <th>Status</th>
                <th>Type</th>
                <th>จำนวน</th>
                <th>Banlance</th>
                <th>รายละเอียด</th>
              </tr>
            </thead>
            <tbody>
              <?php $i = 0; ?>
              @foreach($ai_cash as $value)
              <?php $i++; ?>
              <tr>
                <td>{{ $i }}</td>
                <td><span class="label label-inverse-info-border">{{ date('d/m/Y H:i:s',strtotime($value->created_at)) }}</span></td>

                <?php 

                if( $value->status == 'success'){
                   $status ='<span class="label label-inverse-success"><b style="color: #000">'.$value->order_status.'</b></span>';
 
                }elseif($value->status == 'panding'){
                  
                 $status ='<span class="label label-inverse-warning"><b style="color: #000">'.$value->order_status.'</b></span>';
               }else{
                  $status ='<span class="label label-inverse-warning"><b style="color: #000">'.$value->order_status.'</b></span>';
               }



               ?>
               <td>{!! $status !!}</td>
               <td>{{ $value->pay_type }}  </td>
               <td><b class="text-success">{{ number_format($value->ai_cash,2) }}</b></td>
               <td><b class="text-primary">{{  number_format($value->banlance,2) }}</b></td>
               <td>{{ $value->detail }}</td>
             </tr>
             @endforeach

           </tbody>
           <tfoot>
            <tr>
              <th>#</th>
              <th>Date</th>
              <th>Status</th>
              <th>Type</th>
              <th>จำนวน</th>
              <th>Banlance</th>
              <th>รายละเอียด</th>
            </tr>
          </tfoot>
        </table>
      </div>

    </div>
  </div>
</div>
</div>


@endsection
@section('js')
<!-- Masking js -->
<script src="{{asset('frontend/assets/pages/form-masking/inputmask.js')}}"></script>
<script src="{{asset('frontend/assets/pages/form-masking/jquery.inputmask.js')}}"></script>
<script src="{{asset('frontend/assets/pages/form-masking/autoNumeric.js')}}"></script>
<script src="{{asset('frontend/assets/pages/form-masking/form-mask.js')}}"></script>


<script src="{{asset('frontend/bower_components/datatables.net/js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('frontend/bower_components/datatables.net-buttons/js/dataTables.buttons.min.js')}}"></script>
<script src="{{asset('frontend/assets/pages/data-table/js/jszip.min.js')}}"></script>
<script src="{{asset('frontend/assets/pages/data-table/js/pdfmake.min.js')}}"></script>
<script src="{{asset('frontend/assets/pages/data-table/js/vfs_fonts.js')}}"></script>
<script src="{{asset('frontend/bower_components/datatables.net-buttons/js/buttons.print.min.js')}}"></script>
<script src="{{asset('frontend/bower_components/datatables.net-buttons/js/buttons.html5.min.js')}}"></script>
<script src="{{asset('frontend/bower_components/datatables.net-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('frontend/bower_components/datatables.net-responsive/js/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('frontend/bower_components/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js')}}"></script>
<!-- Custom js -->
<script src="{{asset('frontend/assets/pages/data-table/js/data-table-custom.js')}}"></script>


<script type="text/javascript">
  function add_aicash(){
    var price = $('#price').val();
    if(price == ''){
      Swal.fire({
        icon: 'error',
        title: 'กรุณาใส่จำนวนเงินที่ต้องการ',
              // text: 'Something went wrong!', 
              // footer: '<a href>Why do I have this issue?</a>'
            })

    }else{
      document.getElementById("cart_payment_aicash").submit();

    }
  }


</script>

@endsection


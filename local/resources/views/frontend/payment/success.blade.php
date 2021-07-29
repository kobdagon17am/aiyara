@extends('frontend.layouts.customer.customer_app')
@section('css')
{{--  <style type="text/css" media="screen">
  table td[class*=col-], table th[class*=col-] {
   position: static !important;
   display: table-cell !important;
   float: none !important;
}
</style> --}}
@endsection
@section('conten')
<div class="row justify-content-center">
<div class="col-md-8 col-lg-8 mt-3">
  <div class="card p-4">
      <div class="card-block text-center">
          <i class="fa fa-check text-c-green d-block f-70"></i>
          <h4 class="m-t-20"> Successful Payment </h4>
          <p class="m-b-20">ชำระเงินสำเร็จ</p>
          <a href="{{ route('product-history') }}" class="btn btn-success">ประวัติการสั่งซื้อ</a>
      </div>
  </div>
</div>
</div>

<!-- End Contact Info area-->
@endsection
@section('js')

@endsection


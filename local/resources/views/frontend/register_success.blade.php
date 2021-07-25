@extends('frontend.layouts.customer.customer_app')
@section('conten')
<div class="row justify-content-center">
    <div class="col-lg-4">

        <div class="card user-card">
            <div class="card-block">
                <div class="usre-image">
                    <img src="{{ asset('local/public/images/ex.png') }}" class="img-radius" width="100"> 
                </div>
                <h6 class="f-w-600 m-t-10 m-b-10">{{ $data['data']['business_name'] }}</h6>
                <h6 class="f-w-600 m-t-10 m-b-10">{{ $data['data']['prefix_name'] }} {{ $data['data']['first_name'] }} {{ $data['data']['last_name'] }}</h6>
                {{-- <p class="text-muted">Regiter Date | {{ data('Y-m-d',strtotime($data['data']['business_name'] )) }}</p> --}}
                
                <ul class="list-unstyled activity-leval m-b-10">
                    <li class="active"></li>
                    <li class="active"></li>
                    <li class="active"></li>
                    <li class="active"></li>
                    <li class="active"></li>
                </ul>

                <div class="input-group input-group-primary">
                    <span class="input-group-addon">
                     <i class="fa fa-user"></i>
                 </span>
                 <input type="text" class="form-control"  style="font-size: 18px;color: #000;font-weight: bold;" value="{{ $data['data']['user_name'] }}" disabled="">
             </div>

             <div class="input-group input-group-primary">
                <span class="input-group-addon">
                    <i class="fa fa-key"></i>
                </span>
                <input type="text" class="form-control"  style="font-size: 18px;color: #000;font-weight: bold;" value="{{ $data['pass'] }}" disabled="">
            </div>

            <p class="m-t-15 text-muted"><b class="text-success" >ลงทะเบียนสำเร็จ</b> <br>กรุณาบันทึกข้อมูลการลงทะเบียนเพื่อส่งต่อให้กับผู้ใช้งานในการเข้าสู่ระบบครั้งแรก </p>
            <hr>
            <div class="row">
                <div class="col-md-12 text-center">
                    <form action="{{ route('search') }}" method="post" id="home_search">
                      @csrf
                      <input type="hidden" id="home_search_id" name="home_search_id" value="{{ $data['data']['upline_id'] }}">

                      <button type="submit" class="btn btn-primary"><i class="fa fa-sitemap"></i> View </button>
                  </form>

              </div>
          </div>
         {{-- <div class="row justify-content-center user-social-link">
            <div class="col-auto"><a href="#!"><i class="fa fa-facebook text-facebook"></i></a></div>
            <div class="col-auto"><a href="#!"><i class="fa fa-twitter text-twitter"></i></a></div>
            <div class="col-auto"><a href="#!"><i class="fa fa-dribbble text-dribbble"></i></a></div>
        </div> --}}
    </div>
</div>
</div>

</div>

@endsection
@section('js')
@endsection


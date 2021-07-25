@extends('backend.layouts.master')

@section('title') Aiyara Planet @endsection

@section('css')
<link rel="stylesheet" type="text/css" href="{{ URL::asset('backend/libs/select2/select2.min.css')}}">
<style type="text/css">
    .select2-dropdown {
       font-size: 18px;
    }
</style>

@endsection

@section('content')

                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="">
                                    <center><b><h2 class="mb-1 font-size-24">จัดการคลังสินค้า</h2></b>
                                </div>
                            </div>
                        </div>     
                        <!-- end page title -->

                        <div class="row">
                            <div class="col-xl-3 col-md-6">
                                <div class="card" style="background-color: #cccccc;padding: 5%;">

                                    <div class="card-body text-center">
                                       <b><h2 class="mb-0 font-size-20"><i class="fas fa-database"></i> เลือกประเภทคลังสินค้า</h2></b>
                                    </div>

                                     <div class="col-md-12" >
                                      <select name="warehouse" class="form-control select2-templating " >
                                         <option value="">Select</option>
                                            @if($dsWarehouse)
                                                @foreach($dsWarehouse AS $r)
                                                    <option value="{{$r->id}}">{{$r->txt_desc}}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>


                                </div>
                            </div>
                        </div>


@endsection

@section('script')

    <script src="{{ URL::asset('backend/libs/select2/select2.min.js')}}"></script>
    <script>
      $('.select2-templating').select2();
</script>  

@endsection

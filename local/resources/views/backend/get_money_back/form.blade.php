@extends('backend.layouts.master')

@section('title') Aiyara Planet @endsection

@section('css')

@endsection

@section('content')

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0 font-size-18"> รับเงินคืน </h4>
        </div>
    </div>
</div>
<!-- end page title -->

<div class="row">
    <div class="col-10">
        <div class="card">
            <div class="card-body">
              @if( empty($sRow) )
              <form action="{{ route('backend.get_money_back.store') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
              @else
              <form action="{{ route('backend.get_money_back.update', @$sRow->id ) }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                <input name="_method" type="hidden" value="PUT">
              @endif
                {{ csrf_field() }}

                  <div class="form-group row">
                    <label for="" class="col-md-3 col-form-label"> Business Location : * </label>
                    <div class="col-md-8">
                      <select id="business_location_id_fk" name="business_location_id_fk" class="form-control select2-templating " required="" >
                        <option value="">-Business Location-</option>
                        @if(@$sBusiness_location)
                        @foreach(@$sBusiness_location AS $r)
                        <option value="{{$r->id}}" {{ (@$r->id==@$sRow->business_location_id_fk)?'selected':'' }} >
                          {{$r->txt_desc}}
                        </option>
                        @endforeach
                        @endif
                      </select>
                    </div>
                  </div>


                  <div class="form-group row">
                    <label for="" class="col-md-3 col-form-label"> Branch : * </label>
                    <div class="col-md-8">
                      <select id="branch_id_fk" name="branch_id_fk" class="form-control select2-templating " required="" >
                        <option value="">-Branch-</option>
                        @if(@$sBranchs)
                        @foreach(@$sBranchs AS $r)
                        <option value="{{$r->id}}" {{ (@$r->id==@$sRow->branch_id_fk)?'selected':'' }} >
                          {{$r->b_name}}
                        </option>
                        @endforeach
                        @endif
                      </select>
                    </div>
                  </div>


                  <div class="form-group row">
                    <label for="" class="col-md-3 col-form-label"> ประเภทของการรับคืน : * </label>
                    <div class="col-md-8">
                      <select id="get_back_type_id_fk" name="get_back_type_id_fk" class="form-control select2-templating " required="" >
                        <option value="">-Select-</option>
                        @if(@$sGet_money_back_type)
                        @foreach(@$sGet_money_back_type AS $r)
                        <option value="{{$r->id}}" {{ (@$r->id==@$sRow->get_back_type_id_fk)?'selected':'' }} >
                          {{$r->txt_desc}}
                        </option>
                        @endforeach
                        @endif
                      </select>
                    </div>
                  </div>

                <div class="form-group row">
                    <label for="amt" class="col-md-3 col-form-label">จำนวนเงิน :</label>
                    <div class="col-md-2">
                        <input class="form-control NumberOnly " type="text" value="{{ @$sRow->amt }}" name="amt" required>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="currency_type_id_fk" class="col-md-3 col-form-label"> หน่วยเงิน : * </label>
                    <div class="col-md-8">
                      <select id="currency_type_id_fk" name="currency_type_id_fk" class="form-control select2-templating " required="" >
                        <option value="">-Select-</option>
                        @if(@$sCurrency)
                        @foreach(@$sCurrency AS $r)
                        <option value="{{$r->id}}" {{ (@$r->id==@$sRow->currency_type_id_fk)?'selected':'' }} >
                          {{$r->txt_desc}}
                        </option>
                        @endforeach
                        @endif
                      </select>
                    </div>
                  </div>

                <div class="form-group row">
                  <label for="note" class="col-md-3 col-form-label">หมายเหตุ (ถ้ามี) :</label>
                  <div class="col-md-8">
                    <textarea class="form-control" rows="3" id="note" name="note" >{{ @$sRow->note }}</textarea>
                  </div>
                </div>

                <div class="form-group mb-0 row">
                    <div class="col-md-6">
                        <a class="btn btn-secondary btn-sm waves-effect" href="{{ url("backend/get_money_back") }}">
                          <i class="bx bx-arrow-back font-size-16 align-middle mr-1"></i> ย้อนกลับ
                        </a>
                    </div>
                    <div class="col-md-6 text-right">
                        <button type="submit" class="btn btn-primary btn-sm waves-effect">
                          <i class="bx bx-save font-size-16 align-middle mr-1"></i> บันทึกข้อมูล
                        </button>
                    </div>
                </div>

              </form>
            </div>
        </div>
    </div> <!-- end col -->
</div>
<!-- end row -->

@endsection

@section('script')

<script type="text/javascript">

       $('#business_location_id_fk').change(function(){
          $(".myloading").show();
          var business_location_id_fk = this.value;
          // alert(warehouse_id_fk);

           if(business_location_id_fk != ''){
             $.ajax({
                  url: " {{ url('backend/ajaxGetBranch') }} ",
                  method: "post",
                  data: {
                    business_location_id_fk:business_location_id_fk,
                    "_token": "{{ csrf_token() }}",
                  },
                  success:function(data)
                  {
                   if(data == ''){
                       alert('ไม่พบข้อมูลสาขา !!.');
                       $(".myloading").hide();
                   }else{
                       var layout = '<option value="" selected>- เลือกสาขา -</option>';
                       $.each(data,function(key,value){
                        layout += '<option value='+value.id+'>'+value.b_name+'</option>';
                       });
                       $('#branch_id_fk').html(layout);
                       $('#warehouse_id_fk').html('<option value="" selected>กรุณาเลือกสาขาก่อน</option>');
                       $('#zone_id_fk').html('<option value="" selected>กรุณาเลือกคลังก่อน</option>');
                       $('#shelf_id_fk').html('<option value="" selected>กรุณาเลือกโซนก่อน</option>');
                       $(".myloading").hide();
                   }
                  }
                })
           }else{
            $(".myloading").hide();
           }

      });


</script>
@endsection


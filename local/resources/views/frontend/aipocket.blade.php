  
@extends('frontend.layouts.customer.customer_app')
@section('conten')

<div class="row">


    <div class="col-md-3 col-xl-3">
        <div class="card bg-c-blue order-card">
            <div class="card-block">
                <h5 class="m-b-20">Ai-PocKet</h5>
                <h3 class="text-right"><i class="ti-wallet f-left"></i><span>9,562 PV</span></h3>
                <p class="m-b-0">จำนวนคะแนนที่เหลือ{{-- <span class="f-right">$542</span> --}}</p>
            </div>
        </div>
    </div>

    <div class="col-md-9 col-xl-9">
        <div class="card">
            <div class="card-block">
                <h6>จุดประสงค์การใช้</h6>

                <div class="form-group row">


                    <div class="col-md-6 m-t-5">
                        <div class="input-group input-group-button">
                            <input type="text" id="username" class="form-control" placeholder="รหัสสมาชิกที่ใช้">
                            <span class="input-group-addon btn btn-primary" onclick="check()">
                                <span class="">ทำรายการ</span>
                            </span>
                        </div>
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
                <h5>ประวัติการสั่งซื้อ</h5>
                {{-- <span>DataTables has most features enabled by default, so all you need to do to use it with your own ables is to call the construction function: $().DataTable();.</span> --}}
            </div>
            <div class="card-block">
                <div class="table-responsive dt-responsive">
                    <table id="aipocket" class="table table-striped table-bordered nowrap">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>วันที่</th>
                                <th>Type</th>
                                <th>ผู้รับ</th>
                                <th>PV</th>
                                <th>สถานะ</th>
                                <th>รายละเอียด</th>
                            </tr>
                        </thead>

                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="large-Modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-md" role="document">
        <form action="{{ route('upload_slip') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">ยืนยันการใช้ Ai-Pocket</h4>
                </div>

                <div class="modal-body">

                    <div class="col-md-12 col-xl-12">
                        <div class="card widget-statstic-card borderless-card">
                            <div class="card-header">
                                <div class="card-header-left">
                                    <h4 id="text_username"></h4>
                                    <p class="p-t-10 m-b-0 text-muted" id="name"> </p>
                                </div>
                            </div>
                            <div class="card-block">
                                <i class="fa fa-users st-icon bg-warning txt-lite-color"></i>
                                <div class="text-left">
                                    <h3 class="d-inline-block text-success" id="text_pv"></h3>
                                    {{-- <i class="fa fa-long-arrow-down text-danger f-24 m-l-15"></i>
                                    <span class="f-right bg-danger">-5%</span> --}}
                                    <div class="col-sm-12 m-t-5">
                                        <select class="form-control" name="type">
                                            @foreach($type as $value)
                                            <option value="{{ $value->group_id }}">{{ $value->orders_type }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-12 m-t-5">

                                     <input type="text" name="pv" class="form-control autonumber" data-v-max="99999" data-v-min="0" placeholder="จำนวน PV">
                                 </div>
                             </div>
                         </div>
                     </div>
                 </div>

              
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default waves-effect " data-dismiss="modal">Close</button>

            <button class="btn btn-success" type="submit" name="submit" id="submit_upload" value="upload">Confirm</button>

        </div>

    </div>
</form>
</div>
</div>



@endsection
@section('js')
<!-- Masking js -->
<script src="{{asset('frontend/assets/pages/form-masking/inputmask.js')}}"></script>
<script src="{{asset('frontend/assets/pages/form-masking/jquery.inputmask.js')}}"></script>
<script src="{{asset('frontend/assets/pages/form-masking/autoNumeric.js')}}"></script>
<script src="{{asset('frontend/assets/pages/form-masking/form-mask.js')}}"></script>
<script type="text/javascript">
    function check(){
    //var url = '{{ route('cart_delete') }}';
    var username =$('#username').val();
    $.ajax({
        url: '{{ route('check_customer_id')}}',
        type: 'POST',
        data: {_token:'{{ csrf_token() }}','user_name':username}
    })
    .done(function(data) {

        //console.log(data['data']['data']);
        if(data['status'] == 'success'){
            document.getElementById("text_username").innerHTML = "User ID : "+data['data']['data']['user_name']+' ('+ data['data']['data']['business_name'] +')';

            document.getElementById("name").innerHTML = data['data']['data']['prefix_name']+' '+ data['data']['data']['first_name'] +' '+data['data']['data']['last_name'];

            document.getElementById("text_pv").innerHTML = data['data']['data']['pv']+' PV';
            
            
            $("#large-Modal").modal();
            //alert(data['status']);

        }else {
         Swal.fire({ 
          title: data['data']['message'],
          // text: "You won't be able to revert this!",
          icon: 'warning',
          showConfirmButton: false,
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, delete it!'
      }).then((result) => {
          if (result.isConfirmed) {
            //$( "#cart_delete" ).attr('action',url);
           // $('#data_id').val(item_id);
            //$( "#cart_delete" ).submit();
            Swal.fire(
             'Deleted!',
             'Your file has been deleted.',
             'success'
             )

        }
    })
  }
})
    .fail(function() {
        console.log("error");
    })

    // Swal.fire({ 
    //   title: 'Are you sure?',
    //       // text: "You won't be able to revert this!",
    //       icon: 'warning',
    //       //showConfirmButton: false,
    //       showCancelButton: true,
    //       confirmButtonColor: '#3085d6',
    //       cancelButtonColor: '#d33',
    //       confirmButtonText: 'Yes, delete it!'
    //   }).then((result) => {
    //       if (result.isConfirmed) {
    //         //$( "#cart_delete" ).attr('action',url);
    //        // $('#data_id').val(item_id);
    //         //$( "#cart_delete" ).submit();
    //          Swal.fire(
    //            'Deleted!',
    //            'Your file has been deleted.',
    //            'success'
    //            )

    //     }
    // })
}
</script>

@endsection


  
@extends('frontend.layouts.customer.customer_app')
@section('conten')

<div class="row">


  <div class="col-md-5 col-xl-5"> 
    <div class="card">
    {{--   <div class="card-header">
        <h5>Setting Salepage</h5>

      </div> --}}

      <div class="card-header">
        <h5 class="card-header-text">Setting Salepage All </h5>
        <button id="edit-Contact" type="button" onclick="save_contact()" class="btn btn-primary waves-effect waves-light f-right">
          <i class="icofont icofont-edit"></i> Save
        </button>
      </div>

      <div class="card-block">


        <div class="row">
          <div class="col-sm-12">
            <div class="input-group input-group-primary">
              <span class="input-group-addon">
               <i class="ti-facebook"></i>
             </span>
             <input type="text" id="fb" class="form-control" placeholder="https://www.facebook.com/aiyaracorp" value="{{ @$data->url_fb }}">
           </div>
         </div>
       </div>

       <div class="input-group input-group-danger">
        <span class="input-group-addon">
         <i class="ti-instagram"></i>
       </span>
       <input type="text" id="ig" class="form-control" placeholder="https://www.instagram.com/aiyaracorp" value="{{ @$data->url_ig }}">
     </div>

     <div class="input-group input-group-success">
      <span class="input-group-addon">
        {{--  <i class="icofont icofont-volume-off"></i> --}}
        Line
      </span>
      <input type="text" id="line" class="form-control" placeholder="http://line.me/ti/p/Lineid" value="{{ @$data->url_line }}">
    </div>

      <div class="input-group input-group-success">
      <span class="input-group-addon">
        <i class="fa fa-phone"></i>
      </span>
      <input type="text" id="tel_number" class="form-control" placeholder="Tel Number" value="{{ @$data->tel_number }}">
    </div>

  </div>
</div>
</div>

<div class="col-md-7 col-xl-7">



 <div class="card">
    {{--   <div class="card-header">
        <h5>Setting Salepage</h5>

      </div> --}}

      <div class="card-header" style="margin-bottom: -24px;">
        <h5 class="card-header-text">Setting Salepage  </h5>
        <button type="button" onclick="save_js()" class="btn btn-primary waves-effect waves-light f-right">
          <i class="icofont icofont-edit"></i> Save
        </button>
      </div>

      <div class="card-block">


        <div class="form-group row">
          <label> Add Facebook Pixel Salepage 1  </label>
          <textarea rows="5" cols="5" id="js_page_1" class="form-control" placeholder="Tag javascript in hearder Page 1">@if($data->js_page_1){{ @$data->js_page_1 }}@endif</textarea>
          
        </div>
        <div class="form-group row">
          <label> Add Facebook Pixel Salepage 2  </label>
          <textarea rows="5" cols="5" id="js_page_2" class="form-control" placeholder="Tag javascript in hearder Page 2">@if($data->js_page_2){{ @$data->js_page_2 }}@endif</textarea>
          
        </div>
        <div class="form-group row">
          <label> Add Facebook Pixel Salepage 3  </label>
          <textarea rows="5" cols="5" id="js_page_3" class="form-control" placeholder="Tag javascript in hearder Page 3">@if($data->js_page_3){{ @$data->js_page_3 }}@endif</textarea>
          
        </div>
        <div class="form-group row">
          <label> Add Facebook Pixel Salepage 4  </label>
          <textarea rows="5" cols="5" id="js_page_4" class="form-control" placeholder="Tag javascript in hearder Page 4">@if($data->js_page_4){{ @$data->js_page_4 }}@endif</textarea>
          
        </div>

      </div>
    </div>

  </div>

</div>

{{-- Card Form URL name --}}
<div class="row">
  <div class="col-md-12">
    <div class="card">
      <div class="card-header">
        <h5 class="card-header-text mb-0">Setting Salepage Name URL</h5>
      </div>
      <div class="card-block">

        <div class="form-group">
          <label>Aiyara</label>
          <div class="input-group input-group-button mb-0">
            <input type="text" class="form-control" name="name_s1" id="name_s1" placeholder="Aiyara" value="{{ $data->name_s1 }}">
            <span class="input-group-addon btn btn-primary btn-save-url">
                <span class="">Save</span>
            </span>
          </div>
        </div>
        <div class="form-group">
          <label>Aimmura</label>
          <div class="input-group input-group-button mb-0">
            <input type="text" class="form-control" name="name_s2" id="name_s2" placeholder="Aimmura" value="{{ $data->name_s2 }}">
            <span class="input-group-addon btn btn-primary btn-save-url">
                <span class="">Save</span>
            </span>
          </div>
        </div>
        <div class="form-group">
          <label>Cashewy Drink</label>
          <div class="input-group input-group-button mb-0">
            <input type="text" class="form-control" name="name_s3" id="name_s3" placeholder="Cashewy Drink" value="{{ $data->name_s3 }}">
            <span class="input-group-addon btn btn-primary btn-save-url">
                <span class="">Save</span>
            </span>
          </div>
        </div>
        <div class="form-group">
          <label>Aifacad</label>
          <div class="input-group input-group-button mb-0">
            <input type="text" class="form-control" name="name_s4" id="name_s4" placeholder="Aifacad" value="{{ $data->name_s4 }}">
            <span class="input-group-addon btn btn-primary btn-save-url">
                <span class="">Save</span>
            </span>
          </div>
        </div>
        <div class="form-group">
          <label>Alada</label>
          <div class="input-group input-group-button mb-0">
            <input type="text" class="form-control" name="name_s5" id="name_s5" placeholder="Alada" value="{{ $data->name_s5 }}">
            <span class="input-group-addon btn btn-primary btn-save-url">
                <span class="">Save</span>
            </span>
          </div>
        </div>
        <div class="form-group">
          <label>TrimMax</label>
          <div class="input-group input-group-button mb-0">
            <input type="text" class="form-control" name="name_s6 btn-save-url" id="name_s6" placeholder="TrimMax" value="{{ $data->name_s6 }}">
            <span class="input-group-addon btn btn-primary">
                <span class="">Save</span>
            </span>
          </div>
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
  function save_contact(){

    Swal.fire({
      title: 'Do you want to save the changes?',
      // showDenyButton: true,
      showCancelButton: true,
      confirmButtonText: `Save`,
      // denyButtonText: `Don't save`,
    }).then((result) => {
      /* Read more about isConfirmed, isDenied below */
      if (result.isConfirmed) {
        fb = $('#fb').val();
        ig = $('#ig').val();
        line = $('#line').val();
        tel_number = $('#tel_number').val();

        $.ajax({
          url: '{{ route('salepage/save_contact') }}',
          type: 'POST',

          data: {_token:'{{ csrf_token() }}',fb:fb,ig:ig,line:line,'tel_number':tel_number},
        })
        .done(function(data) {
          
          if(data['status'] == 'success'){
            Swal.fire('Saved!', '', 'success');
          }else {
             Swal.fire({
            icon: 'error',
            title: 'Saved Fail',
            // text: 'Something went wrong!',
            // footer: '<a href>Why do I have this issue?</a>'
          })
            
          }
          
        })
        .fail(function() {

          Swal.fire({
            icon: 'error',
            title: 'Saved Fail',
            // text: 'Something went wrong!',
            // footer: '<a href>Why do I have this issue?</a>'
          })
          console.log("error");
        })

        


      }
    })

  }


  function save_js(){

    Swal.fire({
      title: 'Do you want to save the changes?',
      // showDenyButton: true,
      showCancelButton: true,
      confirmButtonText: `Save`,
      // denyButtonText: `Don't save`,
    }).then((result) => {
      /* Read more about isConfirmed, isDenied below */
      if (result.isConfirmed) {
        js_page_1 = $('#js_page_1').val();
        js_page_2 = $('#js_page_2').val();
        js_page_3 = $('#js_page_3').val();
        js_page_4 = $('#js_page_4').val();

        $.ajax({
          url: '{{ route('salepage/save_js') }}',
          type: 'POST',

          data: {_token:'{{ csrf_token() }}',js_page_1:js_page_1,js_page_2:js_page_2,js_page_3:js_page_3,js_page_4:js_page_4},
        })
        .done(function(data) {
          
          if(data['status'] == 'success'){
            Swal.fire('Saved!', '', 'success');
          }else {
             Swal.fire({
            icon: 'error',
            title: 'Saved Fail',
            // text: 'Something went wrong!',
            // footer: '<a href>Why do I have this issue?</a>'
          })
            
          }
          
        })
        .fail(function() {

          Swal.fire({
            icon: 'error',
            title: 'Saved Fail',
            // text: 'Something went wrong!',
            // footer: '<a href>Why do I have this issue?</a>'
          })
          console.log("error");
        })

        


      }
    })

  }


  $('input[name^="name_s"]').each(function () {
    $(this).on('input', function (e) {
      return e.target.value = e.target.value.replaceAll(' ', '-')
    })
  })

  $('.btn-save-url').each(function () {
    $(this).on('click', function () {
      saveUrl($(this).prev(), $(this).prev().val())
    })
  })

  function saveUrl(input, value) {


    let data = {
      _token: "{{ csrf_token() }}"
    }

    data[input.attr('name')] = value

    Swal.fire({
      title: 'Do you want to save the changes?',
      showCancelButton: true,
      confirmButtonText: `Save`,
    })
    .then(function(result) {
      if (result.isConfirmed) {
        $.ajax({
          url: "{{ route('salepage/save_url') }}",
          type: "POST",
          data: data,
          success: function(response) {
            if (response.success) {
              Swal.fire('Saved!', '', 'success');
              input.removeClass('is-invalid')
              input.parent().parent().find('.invalid-feedback').remove();
            }
          },
          error: function(response) {
            if (response.status === 422) {
              input.addClass('is-invalid')
              input.parent().parent().append(`
                <div class="invalid-feedback d-block">
                  ${response.responseJSON.errors[input.attr('name')]}
                </div>
              `)
            }
          }
        })
      }
    })

  }
</script>

@endsection


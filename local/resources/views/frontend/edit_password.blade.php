@extends('frontend.layouts.customer.customer_app')
@section('conten')
<div class="row justify-content-center">
  <div class="col-lg-4">

    <div class="card user-card">
      <div class="card-block">
        <div class="usre-image">
          <img src="{{ asset('local/public/images/ex.png') }}" class="img-radius" width="100"> 
        </div>

        <h5 class="f-w-600 m-t-10 m-b-10 text-primary">{{ Auth::guard('c_user')->user()->user_name }}</h5> 

        <ul class="list-unstyled activity-leval m-b-10">
          <li class="active"></li>
          <li class="active"></li> 
          <li class="active"></li>
          <li class="active"></li>
          <li class="active"></li>
        </ul>
        <form action="{{ route('edit_password_submit') }}" id="edit_password_submit" method="post" accept-charset="utf-8">
          @csrf
          <div class="input-group input-group-primary">
            <span class="input-group-addon">
             <i class="fa fa-lock"></i>
           </span>
           <input type="password" name="old_password" id="old_password" class="form-control" style="font-size: 18px;color: #000;font-weight: bold;" placeholder="Old Password" required="">
         </div>

         <div class="input-group input-group-primary">
          <span class="input-group-addon">
            <i class="fa fa-key"></i>
          </span>
          <input type="password" id="edit_password" name="edit_password" class="form-control" placeholder="Change Password" style="font-size: 18px;color: #000;font-weight: bold;" required="">
        </div>

        <div class="input-group input-group-primary">
          <span class="input-group-addon">
            <i class="fa fa-key"></i>
          </span> 
          <input type="password" id="confirm_edit_password" name="confirm_edit_password" placeholder="Confirm Change Password" class="form-control"  style="font-size: 18px;color: #000;font-weight: bold;" required="">
        </div>
      </form>

      <p class="m-t-15 text-muted"><b class="text-success" >Change your password</b></p>
      <hr>
      <div class="row">
        <div class="col-md-12 text-center">

          <button type="button" onclick="change_password()" class="btn btn-primary"> Change Password </button>

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
  <script type="text/javascript">
    function change_password(){
      var old_password = $('#old_password').val();
      var edit_password = $('#edit_password').val();
      var confirm_edit_password = $('#confirm_edit_password').val();

      if(old_password == ''){
        Swal.fire({
          icon: 'error',
          title: 'Old password is null',

        })


      }else if(edit_password == ''){
       Swal.fire({
        icon: 'error',
        title: 'Change password is null',

      })

     }else if(confirm_edit_password == ''){
       Swal.fire({
        icon: 'error',
        title: 'Change confirm password is null',

      })

     }else{
      if(edit_password.length < 6 || edit_password.length > 20){
        Swal.fire({
          icon: 'error',
          title: 'Your password must be between 6 and 20 characte',

        })
      }else if(edit_password != confirm_edit_password) {
       Swal.fire({
        icon: 'error',
        title: 'Change password not matching',

      })

     }else{

      document.getElementById("edit_password_submit").submit();

     }

   }

 }
</script>
@endsection


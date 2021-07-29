@extends('frontend.layouts.customer.customer_app')
@section('conten')
    <div class="row justify-content-center">
        <div class="col-lg-4 col-md-6">

            <div class="card user-card">
                <div class="card-block text-center">
                    {{-- <div class="usre-image"> --}}
                    <img src="{{ asset('frontend/assets/images/aicash.png') }}" class="img-radius" width="120">

                    {{-- </div> --}}

                    <h5 class="f-w-600 m-t-10 m-b-10 text-primary">Ai-Cash คงเหลือ
                        {{ number_format(Auth::guard('c_user')->user()->ai_cash) }}</h5>
                    @if ($aicash_data)
                        <p class="m-t-15 text-muted"><b class="text-success">Change your password Ai-Cash</b></p>
                    @else
                        <p class="m-t-15 text-muted"><b class="text-danger">คุณจะไม่สามารถใช้งาน Ai-Cash ได้ <br>
                                จนกว่าจะตั้งค่า PassWord Ai-Cash</b></p>
                    @endif

                    <ul class="list-unstyled activity-leval m-b-10">
                        <li class="active"></li>
                        <li class="active"></li>
                        <li class="active"></li>
                        <li class="active"></li>
                        <li class="active"></li>
                    </ul>
                    @if ($aicash_data)
                        <form action="{{ route('edit_password_aicash_submit') }}" id="edit_password_aicash_submit" method="post" accept-charset="utf-8">
                        @csrf
                        <div class="input-group input-group-primary">
                            <span class="input-group-addon">
                                <i class="fa fa-lock"></i>
                            </span>
                            <input type="password" name="old_password" id="old_password" class="form-control"
                                style="font-size: 18px;color: #000;font-weight: bold;" placeholder="Old Password"
                                required="">
                        </div>

                        <div class="input-group input-group-primary">
                            <span class="input-group-addon">
                                <i class="fa fa-key"></i>
                            </span>
                            <input type="password" id="edit_password" name="edit_password" class="form-control"
                                placeholder="Change Password" style="font-size: 18px;color: #000;font-weight: bold;"
                                required="">
                        </div>

                        <div class="input-group input-group-primary">
                            <span class="input-group-addon">
                                <i class="fa fa-key"></i>
                            </span>
                            <input type="password" id="confirm_edit_password" name="confirm_edit_password"
                                placeholder="Confirm Change Password" class="form-control"
                                style="font-size: 18px;color: #000;font-weight: bold;" required="">
                        </div>
                        </form>
                        <hr>
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <button type="button" onclick="change_password()" class="btn btn-primary"> Change Password Ai-Cash
                                </button>

                            </div>
                        </div>
                    @else

                        <form action="{{ route('add_password_aicash_submit') }}" id="add_password_aicash_submit"
                            method="post" accept-charset="utf-8">
                            @csrf


                            <div class="input-group input-group-primary">
                                <span class="input-group-addon">
                                    <i class="fa fa-key"></i>
                                </span>
                                <input type="password" id="add_password" name="add_password" class="form-control"
                                    placeholder="Password Ai-Cash" style="font-size: 18px;color: #000;font-weight: bold;"
                                    required="">
                            </div>

                            <div class="input-group input-group-primary">
                                <span class="input-group-addon">
                                    <i class="fa fa-key"></i>
                                </span>
                                <input type="password" id="confirm_add_password" name="confirm_add_password"
                                    placeholder="Confirm Password Ai-Cash" class="form-control"
                                    style="font-size: 18px;color: #000;font-weight: bold;" required="">
                            </div>
                        </form>

                        <hr>
                        @if($canAccess)
                        <div class="row">
                            <div class="col-md-12 text-center">

                                <button type="button" onclick="add_password()" class="btn btn-primary"> Submit </button>

                            </div>
                        </div>
                        @endif

                    @endif


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

    <script>
        function change_password() {
            var old_password = $('#old_password').val();
            var edit_password = $('#edit_password').val();
            var confirm_edit_password = $('#confirm_edit_password').val();

            if (old_password == '') {
                Swal.fire({
                    icon: 'error',
                    title: 'Old password is null',

                })


            } else if (edit_password == '') {
                Swal.fire({
                    icon: 'error',
                    title: 'Change password is null',
                })

            } else if (confirm_edit_password == '') {
                Swal.fire({
                    icon: 'error',
                    title: 'Change confirm password is null',
                })

            } else {
                if (edit_password.length < 6 || edit_password.length > 20) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Your password must be between 6 and 20 characte',

                    })
                } else if (edit_password != confirm_edit_password) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Change password not matching',

                    })

                } else {
                    document.getElementById("edit_password_aicash_submit").submit();
                }
            }
        }

        function add_password() {

            var add_password = $('#add_password').val();
            var confirm_add_password = $('#confirm_add_password').val();

            if (add_password == '') {
                Swal.fire({
                    icon: 'error',
                    title: 'Password is null',
                })

            } else if (confirm_add_password == '') {
                Swal.fire({
                    icon: 'error',
                    title: 'Confirm Password Ai-Cash is null',
                })
            } else {
                if (add_password.length < 6 || add_password.length > 20) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Your Password must be between 6 and 20 characte',
                    })
                } else if (add_password != confirm_add_password) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Add password not matching',
                    })
                } else {
                    document.getElementById("add_password_aicash_submit").submit();
                }

            }

        }

    </script>
@endsection

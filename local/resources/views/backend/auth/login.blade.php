@extends('backend.layouts.master-without-nav')

@section('title')
Login
@endsection

@section('body')
<body style="background-image: url('backend/images/footer-bg.jpg') !important;" >
@endsection

@section('content')

    <div class="account-pages my-5 pt-5" >
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6 col-xl-5">
                    <div class="card overflow-hidden">
                        <div class="bg-soft-primary">
                            <div class="row">
                            	<center>
                                <div class="col-7 align-self-end">
                                	
                                    <img src="backend/images/login.png" alt="" class="img-fluid">
                                </div>
                                	</center>

                            </div>
                        </div>
                        <div class="card-body pt-0">
              
                            <div class="p-2">
                            <form class="form-horizontal" method="POST" action="{{ route('backend.login') }}">
                                @csrf
                                    <div class="form-group">
                                        <label for="username">Username</label>
                                        <input name="email" type="email" class="form-control @error('email') is-invalid @enderror" @if(old('email')) value="{{ old('email') }}" @else value="admin@email.com" @endif id="username" placeholder="Enter username" autocomplete="email" autofocus>
                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="userpassword">Password</label>
                                        <input type="password" name="password" class="form-control  @error('password') is-invalid @enderror" id="userpassword" value="aaaa1111" placeholder="Enter password">
                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="customControlInline" checked="checked">
                                            <label class="custom-control-label" for="customControlInline">Remember me</label>
                                    </div>

                                    <div class="mt-3">
                                        <button class="btn btn-success btn-block waves-effect waves-light" type="submit">Log In</button>
                                    </div>
                          
                                </form>
                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

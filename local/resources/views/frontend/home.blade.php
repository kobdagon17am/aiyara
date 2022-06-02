@extends('frontend.layouts.customer.customer_app')
@section('conten')
@section('css')
<link rel="stylesheet" type="text/css" href="{{asset('frontend/bower_components/jstree/css/style.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('frontend/assets/pages/treeview/treeview.css')}}">
<link rel="stylesheet" href="{{ asset('frontend/assets/css/genealogy.css') }}">
<style type="text/css">
  .fa-a:before {
    font-weight: bold;
    font-size: 18px;
    content: 'A';
  }
  .fa-b:before {
    font-weight: bold;
    font-size: 18px;
    content: 'B';
  }
  .fa-c:before {
    font-weight: bold;
    font-size: 18px;
    content: 'C';
  }

  .bg-tree{
    background-image: url(local/public/images/line_tree.png);
    background-repeat: no-repeat;
    background-position: center;
    background-size: 109%;
    background-position-y: 0%;
    background-position-x: 51%;
  }

</style>
@endsection

<div class="row">
  <div class="col-md-12 col-lg-12">
    <div class="page-header card">
      <div class="card-block">
        <h5>@lang('message.jobstructure')</h5>
        <div class="card-header" style="padding: 0px">
          <div class="row">
            <div class="col-md-8">
              <a class="btn hor-grd btn-primary btn-outline-primary waves-effect md-trigger btn-sm btn-round m-t-5" href="{{route('tree_view')}}" style="color: black;font-size: 16px;"><i class="fa fa-user" ></i> <b class="font-primary">You</b></a>


              @if($data['lv1']->user_name == Auth::guard('c_user')->user()->user_name)
              <button class="btn btn-success btn-sm btn-disabled disabled m-t-5" style="color: #FFF;font-size: 16px">
                <i class="fa fa-sort-up"></i> <b>Up One Step</b></button>
                @else

                <form id="upline_id" action="{{ route('up_step') }}" style="display: initial;" method="POST"  >
                  @csrf
                  <input type="hidden" name="user_name" value="{{$data['lv1']->upline_id}}">

                  <button type="submit" class="btn hor-grd btn-success btn-sm m-t-5" style=" color: #FFF;font-size: 16px"><i class="fa fa-sort-up"></i> <b>Up one step</b></button>
                </form>
                @endif

                @if(empty($data['lv2_a']) || empty($data['lv3_a_a']))
                <button class="btn btn-primary btn-sm btn-disabled disabled m-t-5" style="color: #FFF;font-size: 16px" ><i class="fa fa-sort-down"></i> @lang('message.down_a') </button>

                @else


                <form id="under_a" action="{{ route('under_a') }}" style="display: initial;" method="POST">
                  @csrf
                  <input type="hidden" name="user_name" value="{{$data['lv3_a_a']->user_name}}">
                  <input type="hidden" name="line_type" value="A">
                  <button type="submit" class="btn btn-primary btn-sm m-t-5" style="color: #FFF;font-size:16px"><i class="fa fa-sort-down"></i> @lang('message.down_a')</button>
                </form>

                @endif


                @if(empty($data['lv2_b']) || empty($data['lv3_b_b']))
                <button class="btn btn-primary btn-sm btn-disabled disabled m-t-5" style="color: #FFF;font-size: 16px" ><i class="fa fa-sort-down"></i> @lang('message.down_b')</button>

                @else

                <form id="under_b" action="{{ route('under_b') }}" style="display: initial;" method="POST" >
                  @csrf
                  <input type="hidden" name="user_name" value="{{$data['lv3_b_b']->user_name}}">
                  <input type="hidden" name="line_type" value="B">
                  <button type="submit" class="btn btn-sm btn-primary m-t-5" style="color: #FFF;font-size: 16px"><i class="fa fa-sort-down"></i> @lang('message.down_b')</button>
                </form>
                @endif

                @if(empty($data['lv2_c']) || empty($data['lv3_c_c']))
                <button class="btn btn-primary btn-sm btn-disabled disabled m-t-5" style="color: #FFF;font-size:16px" disabled=""><i class="fa fa-sort-down"></i> @lang('message.down_c')</button>

                @else

                <form id="under_c" action="{{route('under_c')}}" style="display: initial;" method="POST">
                  @csrf
                  <input type="hidden" name="user_name" value="{{$data['lv3_c_c']->user_name}}">
                  <input type="hidden" name="line_type" value="C">
                  <button type="submit" class="btn btn-sm btn-primary m-t-5" style="color: #FFF;font-size:16px"><i class="fa fa-sort-down"></i> @lang('message.down_c')</button>
                </form>

                @endif

              </div>
              <div class="col-md-4">
               <div class="input-group input-group-button mt-2">
                <input type="text" class="form-control" id="search_username" name="search_username" placeholder="Search UserName or ID Card" value="{{ old('search_username') }}">
                <span class="input-group-addon btn btn-primary" id="basic-addon10" onclick="search_user()" style="margin-top: 0px;">
                  <span class="">Search</span>
                </span>
              </div>


            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Tab variant tab card start -->
<div class="card">
   {{--  <div class="card-header">
      <h5>Tab variant</h5>
    </div> --}}
    <div class="card-block tab-icon">
      <!-- Row start -->
      <div class="row">
        <div class="col-lg-12 col-xl-12">

          <!-- Nav tabs -->
          <ul class="nav nav-tabs md-tabs " role="tablist">
            <li class="nav-item">
              <a class="nav-link" data-toggle="tab" href="#tree" id="tree_tab" role="tab"><i class="fa fa-server"></i>Tree View</a>
              <div class="slide"></div>
            </li>
            <li class="nav-item">
              <a class="nav-link" data-toggle="tab" href="#board" id="board_tab" role="tab"><i class="fa fa-sitemap"></i>Board</a>
              <div class="slide"></div>
            </li>


          </ul>
          <!-- Tab panes -->
          <div class="tab-content card-block">
            <div class="tab-pane" id="tree" role="tabpanel">

              <div class="tree-view">
                <div id="basicTree">
                  <ul>
                    <li data-jstree='{"opened":true}'>
                      @if($data['lv1'])
                      @if($data['lv1']->id == Auth::guard('c_user')->user()->id)

                      <a href="#" onclick="modal_tree('{{ $data['lv1']->user_name }})'">
                        <b class="text-primary">@if($data['lv1']->business_name and $data['lv1']->business_name  != '-'){{ $data['lv1']->business_name }}@else {{$data['lv1']->prefix_name.' '.$data['lv1']->first_name }} @endif</b></a>
                        @else
                        <a href="#" onclick="modal_tree('{{ $data['lv1']->user_name }}')">
                          <b  class="text-primary">@if($data['lv1']->business_name and $data['lv1']->business_name  != '-'){{ $data['lv1']->business_name }}@else {{$data['lv1']->prefix_name.' '.$data['lv1']->first_name }} @endif</b></a>

                          @endif

                          @endif

                          <ul>
                           @for($i=1;$i<=3;$i++)

                           <?php
                           if($i==1){
                            $data_lv2 =$data['lv2_a'];
                            $model_lv2 = 'lv2_a';
                            $type = 'a';
                            $line_lv2 = 'A';
                          }elseif($i==2){
                            $data_lv2 =$data['lv2_b'];
                            $model_lv2 = 'lv2_b';
                            $type = 'b';
                            $line_lv2 = 'B';
                          }elseif($i==3){
                            $data_lv2 =$data['lv2_c'];
                            $model_lv2 = 'lv2_c';
                            $type = 'c';
                            $line_lv2 = 'C';
                          }else{
                            $data_lv2 = null;
                            $model_lv2 = null;
                            $line_lv2 = null;
                          }

                          ?>
                          @if($data_lv2)
                          <li data-jstree='{"opened":true}'>
                           <a href="#" onclick="modal_tree('{{ $data_lv2->user_name }}')"><b> @if($data_lv2->business_name and $data_lv2->business_name  != '-'){{ $data_lv2->business_name }}@else {{$data_lv2->prefix_name.' '.$data_lv2->first_name.' '.$data_lv2->last_name }} @endif</b></a>
                           <ul>
                            @for($j=1;$j<=3;$j++)
                            <?php
                            if($j==1){
                              $data_lv3 =$data['lv3_'.$type.'_a'];
                              $model_lv3 = 'lv3_'.$type.'_a';
                              $line_lv3 = 'A';
                            }elseif($j==2){
                              $data_lv3 =$data['lv3_'.$type.'_b'];
                              $model_lv3 = 'lv3_'.$type.'_b';
                              $line_lv3 = 'B';
                            }elseif($j==3){
                              $data_lv3 =$data['lv3_'.$type.'_c'];
                              $model_lv3 = 'lv3_'.$type.'_c';
                              $line_lv3 = 'C';
                            }else{
                              $data_lv3 = null;
                              $model_lv3 = null;
                              $line_lv3 = null;
                            }

                            ?>
                            @if($data_lv3)
                            <li data-jstree='{"type":"file"}'><a href="#" onclick="modal_tree('{{ $data_lv3->user_name }}')">@if($data_lv3->business_name and $data_lv3->business_name  != '-'){{ $data_lv3->business_name and $data_lv3->business_name  != '-' }}@else {{$data_lv3->prefix_name.' '.$data_lv3->first_name.' '.$data_lv3->last_name }} @endif</a></li>
                            @else
                            <li data-jstree='{"type":"file"}'><a href="#" onclick="modal_add('{{ $data_lv2->user_name }}','{{ $line_lv3 }}')"><b style="color:#28a745">@lang('message.add') {{$line_lv3}} (+)</b></a></li>
                            @endif
                            @endfor

                          </ul>
                        </li>
                        @else
                        <li data-jstree='{"opened":true}'>
                          <a href="#" onclick="modal_add('{{ $data['lv1']->user_name }}','{{ $line_lv2 }}')"><b style="color:#28a745"> @lang('message.add') {{$line_lv2}} (+) </b></a>
                          <ul>
                            <li data-jstree='{"type":"file"}'><b> A (+)</b></li>
                            <li data-jstree='{"type":"file"}'><b> B (+)</b></li>
                            <li data-jstree='{"type":"file"}'><b> C (+)</b></li>
                          </ul>
                        </li>
                        @endif
                        @endfor

                      </ul>

                    </li>

                  </ul>
                </div>
              </div>
            </div>

            {{--    ///////////////////////////////////////////////////////////////////////////// --}}


            <div class="tab-pane" id="board" role="tabpanel">

              <div class="body genealogy-body genealogy-scroll">
                <div class="genealogy-tree">
                  <ul>
                    <li>
                        @if ($data['lv1'])
                          <a href="javascript:void(0);" onclick="modal_tree('{{ $data['lv1']->user_name }}')">
                            <div class="member-view-box">
                                <div class="member-image">
                                    @if($data['lv1']->profile_img)
                                      <img src="{{ asset('local/public/profile_customer/'.$data['lv1']->profile_img) }}" alt="Member">
                                    @else
                                      <img src="{{ asset('local/public/images/ex.png') }}" alt="Member">
                                    @endif
                                    <div class="member-details">
                                        <h6 class="f-w-600 m-t-15">User : {{$data['lv1']->user_name}} </h6>
                                        <p class="text-muted">
                                          @if ($data['lv1']->business_name and $data['lv1']->business_name  != '-')
                                              {{ $data['lv1']->business_name }}
                                          @else
                                              {{$data['lv1']->prefix_name.' '.$data['lv1']->first_name.' '.$data['lv1']->last_name }}
                                          @endif
                                        </p>
                                    </div>
                                  </div>
                            </div>
                          </a>
                        @endif

                      <ul class="active">
                        @for ($i = 1; $i <= 3; $i++)
                            @php
                                if($i == 1) {
                                  $data_lv2 = $data['lv2_a'];
                                  $model_lv2 = 'lv2_a';
                                  $type = 'a';
                                  $line_lv2 = 'A';
                                } elseif ($i == 2) {
                                  $data_lv2 = $data['lv2_b'];
                                  $model_lv2 = 'lv2_b';
                                  $type = 'b';
                                  $line_lv2 = 'B';
                                } elseif($i == 3) {
                                  $data_lv2 = $data['lv2_c'];
                                  $model_lv2 = 'lv2_c';
                                  $type = 'c';
                                  $line_lv2 = 'C';
                                } else {
                                  $data_lv2 = null;
                                  $model_lv2 = null;
                                  $line_lv2 = null;
                                }
                            @endphp
                            <li>
                              @if ($data_lv2)
                                <a href="javascript:void(0);" onclick="modal_tree('{{ $data_lv2->user_name }}')">
                                  <div class="member-view-box">
                                      <div class="member-image">
                                          @if ($data_lv2->profile_img)
                                            <img src="{{ asset('local/public/profile_customer/'.$data_lv2->profile_img) }}" alt="Member">
                                          @else
                                            <img src="{{ asset('local/public/images/ex.png') }}" alt="Member">
                                          @endif
                                          <div class="member-details">
                                            <h6 class="f-w-600 m-t-15"> {{ $line_lv2 }} : {{ $data_lv2->user_name }}</h6>
                                            <p class="text-muted">
                                              @if($data_lv2->business_name and $data_lv2->business_name  != '-')
                                                {{ $data_lv2->business_name  }}
                                              @else
                                                {{ $data_lv2->prefix_name.' '.$data_lv2->first_name.' '.$data_lv2->last_name }}
                                              @endif
                                            </p>
                                          </div>
                                      </div>
                                  </div>
                                </a>
                              @else
                                <a href="javascript:void(0);" onclick="modal_add('{{ $data['lv1']->user_name }}','{{ $line_lv2 }}')">
                                  <div class="member-view-box">
                                    <div class="member-image">
                                      <img src="{{asset('frontend/assets/icon/add_user.png')}}" alt="img">
                                        <div class="member-details">
                                          <h6 class="f-w-600 m-t-15 m-b-10 text-success">@lang('message.add') {{ $line_lv2 }}</h6>
                                          <p class="text-muted">
                                            ภายใต้ :
                                            @if($data['lv1']->business_name and $data['lv1']->business_name  != '-')
                                              {{ $data['lv1']->business_name }}
                                            @else
                                              {{$data['lv1']->prefix_name.' '.$data['lv1']->first_name.' '.$data['lv1']->last_name }}
                                            @endif
                                          </p>
                                        </div>
                                    </div>
                                  </div>
                                </a>
                              @endif
                              <ul class="active leg-three">
                                @for ($j = 1; $j <= 3; $j++)
                                  @php
                                    if($j == 1) {
                                      $data_lv3 = $data['lv3_'.$type.'_a'];
                                      $model_lv3 = 'lv3_'.$type.'_a';
                                      $line_lv3 = 'A';
                                    } elseif ($j == 2) {
                                      $data_lv3 = $data['lv3_'.$type.'_b'];
                                      $model_lv3 = 'lv3_'.$type.'_b';
                                      $line_lv3 = 'B';
                                    } elseif ($j == 3) {
                                      $data_lv3 = $data['lv3_'.$type.'_c'];
                                      $model_lv3 = 'lv3_'.$type.'_c';
                                      $line_lv3 = 'C';
                                    } else {
                                      $data_lv3 = null;
                                      $model_lv3 = null;
                                      $line_lv3 = null;
                                    }
                                  @endphp
                                  <li>
                                    @if ($data_lv3)
                                      <a href="javascript:void(0);" onclick="modal_tree('{{ $data_lv3->user_name }}')">
                                        <div class="member-view-box">
                                            <div class="member-image">
                                                @if ($data_lv3->profile_img)
                                                  <img src="{{ asset('local/public/profile_customer/'.$data_lv3->profile_img) }}" alt="Member">
                                                @else
                                                  <img src="{{ asset('local/public/images/ex.png') }}" alt="Member">
                                                @endif
                                                <div class="member-details">
                                                  <h6 class="f-w-600 m-t-15">{{ $line_lv3 }} : {{ $data_lv3->user_name }}</h6>
                                                  <p class="text-muted">
                                                    @if($data_lv3->business_name and $data_lv3->business_name  != '-')
                                                      {{ $data_lv3->business_name }}
                                                    @else
                                                      {{ $data_lv3->prefix_name.' '.$data_lv3->first_name.' '.$data_lv3->last_name }}
                                                    @endif
                                                  </p>
                                                </div>
                                            </div>
                                        </div>
                                      </a>
                                    @else
                                      @if ($data_lv2)
                                        <a href="javascript:void(0);" onclick="modal_add('{{ $data_lv2->user_name }}','{{ $line_lv3 }}')">
                                          <div class="member-view-box">
                                              <div class="member-image">
                                                <img src="{{ asset('frontend/assets/icon/add_user.png') }}" alt="img" class="img-radius img-60 zoom">
                                                <div class="member-details">
                                                  <h6 class="m-t-15 m-b-0 text-success">@lang('message.add') {{ $line_lv3 }} </h6>
                                                </div>
                                              </div>
                                          </div>
                                        </a>
                                      @else
                                        <a href="javascript:void(0);" style="cursor: not-allowed;">
                                          <div class="member-view-box">
                                              <div class="member-image">
                                                <img src="{{ asset('frontend/assets/icon/add_user_not.png') }}" alt="img" class="img-radius img-60">
                                                <div class="member-details">
                                                  <h6 class="m-t-15 m-b-0">@lang('message.add') {{ $line_lv3 }}</h6>
                                                </div>
                                              </div>
                                          </div>
                                        </a>
                                      @endif
                                    @endif
                                  </li>
                                @endfor
                              </ul>
                            </li>
                        @endfor
                      </ul>
                    </li>
                  </ul>
                </div>
              </div>
        </div>
      </div>
    </div>
  </div>

</div>
<!-- Row end -->
</div>
</div>

<div id="modal_tree"></div>
<div id="modal_add"></div>

<form action="{{ route('search') }}" method="post" id="home_search">
  @csrf
  <input type="hidden" id="home_search_id" name="home_search_id" value="">
</form>

@endsection
@section('js')
<script  src="{{asset('frontend/bower_components/jstree/js/jstree.min.js')}}"></script>
<script  src="{{asset('frontend/assets/pages/treeview/jquery.tree.js')}}"></script>
<script>
     // document.getElementById("lv1_anchor ").classList.remove("");

     $(document).ready(function() {

      if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ){
        $('#tree_tab').addClass('active');
        $('#tree').addClass('active');
      }else{
        $('#board_tab').addClass('active');
        $('#board').addClass('active');

      }

      var lv1 = $('#j1_1_anchor');
      lv1.closest('a').find('.icofont-folder').removeClass('icofont icofont-folder').addClass('typcn typcn-flow-merge');

      var lv2_1 = $('#j1_2_anchor');
      var lv2_2 = $('#j1_6_anchor');
      var lv2_3 = $('#j1_10_anchor');
      lv2_1.closest('a').find('.icofont-folder').removeClass('icofont icofont-folder').addClass('fa fa-a');
      lv2_2.closest('a').find('.icofont-folder').removeClass('icofont icofont-folder').addClass('fa fa-b');
      lv2_3.closest('a').find('.icofont-folder').removeClass('icofont icofont-folder').addClass('fa fa-c');

      var lv3_1_1 = $('#j1_3_anchor');
      var lv3_1_2 = $('#j1_4_anchor');
      var lv3_1_3 = $('#j1_5_anchor');
      lv3_1_1.closest('a').find('.icofont-file-alt').removeClass('icofont icofont-file-alt').addClass('fa fa-a');
      lv3_1_2.closest('a').find('.icofont-file-alt').removeClass('icofont icofont-file-alt').addClass('fa fa-b');
      lv3_1_3.closest('a').find('.icofont-file-alt').removeClass('icofont icofont-file-alt').addClass('fa fa-c');

      var lv3_2_1 = $('#j1_7_anchor');
      var lv3_2_2 = $('#j1_8_anchor');
      var lv3_2_3 = $('#j1_9_anchor');
      lv3_2_1.closest('a').find('.icofont-file-alt').removeClass('icofont icofont-file-alt').addClass('fa fa-a');
      lv3_2_2.closest('a').find('.icofont-file-alt').removeClass('icofont icofont-file-alt').addClass('fa fa-b');
      lv3_2_3.closest('a').find('.icofont-file-alt').removeClass('icofont icofont-file-alt').addClass('fa fa-c');

      var lv3_3_1 = $('#j1_11_anchor');
      var lv3_3_2 = $('#j1_12_anchor');
      var lv3_3_3 = $('#j1_13_anchor');
      lv3_3_1.closest('a').find('.icofont-file-alt').removeClass('icofont icofont-file-alt').addClass('fa fa-a');
      lv3_3_2.closest('a').find('.icofont-file-alt').removeClass('icofont icofont-file-alt').addClass('fa fa-b');
      lv3_3_3.closest('a').find('.icofont-file-alt').removeClass('icofont icofont-file-alt').addClass('fa fa-c');

    });

     function modal_tree(user_name){

      $.ajax({
        url: '{{ route('modal_tree') }}',
        type: 'GET',
        data: {user_name:user_name},
      })
      .done(function(data) {
        console.log("success");
        $('#modal_tree').html(data);
        $('#modal_tree_show').modal('show');
      })
      .fail(function() {
        console.log("error");
      })
      .always(function() {
        console.log("complete");
      });

    }

    function modal_add(user_name,type){
      $.ajax({
        url: '{{ route('modal_add') }}',
        type: 'GET',
        data: {user_name:user_name,type:type},
      })
      .done(function(data) {
        console.log("success");
        $('#modal_add').html(data);
        $('#modal_add_show').modal('show');
      })
      .fail(function() {
        console.log("error");
      })
      .always(function() {
        console.log("complete");
      });

    }

    function search_user(){
      var user_name = $('#search_username').val();
      $.ajax({
        url: '{{ route('home_check_customer_id') }}',
        type: 'GET',
        data: {user_name:user_name},
      })
      .done(function(data) {
        if(data['status'] == 'success'){
          //alert(data['data'])

          $('#home_search_id').val(data['user_name']);
          document.getElementById("home_search").submit();

        }else{
          console.log();
          Swal.fire({
            icon: 'error',
            title: data['data']['message'],
          })

        }
        //console.log("success");
        //console.log(data);
      })
      .fail(function() {
        console.log("error");
      })
      .always(function() {
        console.log("complete");
      });

    }

  </script>
  @endsection

 
@extends('frontend.layouts.customer.customer_app')
@section('conten')
@section('css')
<!-- Data Table Css -->
<link rel="stylesheet" type="text/css" href="{{asset('frontend/bower_components/datatables.net-bs4/css/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('frontend/assets/pages/data-table/css/buttons.dataTables.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('frontend/bower_components/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css')}}">
@endsection


<div class="card">
  <!-- Email-card start -->
  <div class="card-block email-card">
 
      <div class="row">
        <!-- Left-side section start -->
        <div class="col-lg-3 col-xl-3 ">
          <div class="user-body">
            <div class="p-20 text-center">
              <button type="button" class="btn btn-primary waves-effect" data-toggle="modal" data-target="#large-Modal"> <i class="fa fa-plus-square"></i> สอบถาม</button>
              
            </div>

              <div class="modal fade" id="large-Modal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">

              <form action="{{ route('message_question') }}" method="POST">
                <div class="modal-content">

                  <div class="modal-header">
                    <h4 class="modal-title">สอบถาม</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">

                   
                    @csrf
                    <div class="form-group">
                      <input type="text" name="subject" class="form-control" placeholder="Subject" required="">
                    </div>
                    

                    <textarea name="question"></textarea>

                    
                    
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect " data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary waves-effect waves-light ">Submit</button>
                  </div>
                  
                </div>
              </form>
            </div>
          </div>

            <ul class="page-list nav nav-tabs flex-column" id="pills-tab" role="tablist">

     

              <li class="nav-item mail-section">
              <a class="nav-link active" data-toggle="pill" href="#e-inbox" role="tab">
                <i class="fa fa-commenting-o"></i> Message
                 {{-- <span class="label label-primary f-right">6</span> --}}
              </a>
            </li> 

            <li class="nav-item mail-section">
              <a class="nav-link " href="{{ route('message',['active'=>'inbox']) }}" >
                <i class="fa fa-envelope-o"></i> ติดต่อ/สอบถาม
                  <?php  
                  $noti = \App\Helpers\Frontend::notifications(Auth::guard('c_user')->user()->id);
                   
                   
                   ?>
                   @if($noti['count'] > 0)
                  <span class="label label-primary f-right">{{ $noti['count'] }}</span>
                  @endif
              </a>
            </li>
 

          </ul>
         {{--  <ul class="p-20 label-list">
            <li>
              <h5>Status</h5>
            </li>

            <li>
              <a class="mail-work">New Message</a>
            </li>
              <li>
                <a class="mail-work" href="">Work</a>
              </li>
              <li>
                <a class="mail-design" href="">Design</a>
              </li>
              
           <li>
                <a class="mail-friends" href="">Friends</a>
              </li>
              <li>
                <a class="mail-office" href="">Office</a>
              </li>  
            </ul> --}}
          </div>
        </div>
        <!-- Left-side section end -->
        <!-- Right-side section start -->
        <div class="col-lg-9 col-xl-9">
          <div class="tab-content" id="pills-tabContent">

    

       <div class="tab-pane fade show active" id="e-inbox" role="tabpanel">

        <div class="mail-body">
          <div class="mail-body-content email-read">
            <div class="card">
              <div class="card-header">

                <h5>{{ $pm_data->topics_question }}</h5>
                <h6 class="f-right">@if($pm_data->created_at){{ date('d/m/Y H:i:s',strtotime($pm_data->created_at)) }}@endif</h6>
              </div>
              <div class=""> 
                <div class="media m-b-20">
                  <div class="media-left photo-table">

                    @if(Auth::guard('c_user')->user()->profile_img)

                    <img class="img-radius" width="100" src="{{asset('local/public/profile_customer/'.Auth::guard('c_user')->user()->profile_img)}}" alt="User-Profile-Image">
                    @else

                    <img class="media-object img-radius" src="{{asset('local/public/images/ex.png')}}" alt="User-Profile-Image">
                    @endif


                  </div>
                  <div class="media-body photo-contant">

                    <h6 class="user-name">{{ Auth::guard('c_user')->user()->user_name }}</h6>


                    <h6  class="user-mail text-muted">{!! Auth::guard('c_user')->user()->business_name !!}</h6>

                    <div>
                      {{-- <h6 class="email-welcome-txt">Hello Dear...</h6> --}}
                      {!! $pm_data->details_question !!}

                    </div>

                    <hr>
                  </div>
                </div>

                @foreach($data as $value)

                <div class="media m-b-20" id="message">
                  <div class="media-left photo-table">
                    @if($value->type == 'admin')
                    <img class="img-radius" width="100" src="{{asset('local/public/images/admin_aiyara.png')}}" alt="User-Profile-Image">
                    @endif

                    @if($value->type == 'customer')
                    <img class="img-radius" width="100" src="{{asset('local/public/profile_customer/'.Auth::guard('c_user')->user()->profile_img)}}" alt="User-Profile-Image">
                    @endif

                  </div>
                  <div class="media-body photo-contant">
                   @if($value->type == 'admin')
                   <h6 class="user-name text-primary"> น้องใส่ใจ </h6>
                   @endif
                   @if($value->type == 'customer')
                   <h6 class="user-name">{{ Auth::guard('c_user')->user()->user_name }}</h6>
                   <h6  class="user-mail text-muted">{!! Auth::guard('c_user')->user()->business_name !!}</h6>
                   @endif

                   <div>
                    {{-- <h6 class="email-welcome-txt">Hello Dear...</h6> --}}
                    {!! $value->txt_answers !!}

                  </div>

                  <hr>
                </div>



              </div>

              @endforeach

              <div class="m-t-15">
                <i class="icofont icofont-paper-plane f-20 mb-5"></i>Reply Your Thoughts 
            {{--   <div class="row mail-img">
                <div class="col-sm-4 col-md-2 col-xs-12">
                  <a href="#"><img class="card-img-top img-fluid img-thumbnail" src="{{asset('frontend/assets/images/card-block/card1.jpg')}}" alt="Card image cap"></a>
                </div>
                <div class="col-sm-4 col-md-2 col-xs-12">
                  <a href="#"><img class="card-img-top img-fluid img-thumbnail" src="{{asset('frontend/assets/images/card-block/card2.jpg')}}" alt="Card image cap"></a>
                </div>
                <div class="col-sm-4 col-md-2 col-xs-12">
                  <a href="#"><img class="card-img-top img-fluid img-thumbnail" src="{{asset('frontend/assets/images/card-block/card13.jpg')}}" alt="Card image cap"></a>
                </div>
              </div> --}}
              <form action="{{ route('message_reply') }}" method="post">
                @csrf
                <input type="hidden" name="pm_id" value="{{ $pm_data->id }}">
              <textarea name="question_txt" class="form-control m-t-5 col-xs-12 email-textarea" id="exampleTextarea-1" placeholder="Reply Your Thoughts" rows="4"></textarea>
              <div class="row mt-2 f-right mr-0"> 
               <button type="submit" class="btn btn-primary">Reply</button>
             </div>
              </form>
           </div>
         </div>
       </div>
     </div>
   </div>

 </div>
 
</div>
</div>
<!-- Right-side section end -->
</div>
</div>
<!-- Email-card end -->
</div>

@endsection
@section('js')


<!-- tinymce js -->
<script src="{{asset('frontend/assets/pages/wysiwyg-editor/js/tinymce.min.js')}}"></script>
<!-- Custom js -->
<script src="{{asset('frontend/assets/pages/wysiwyg-editor/wysiwyg-editor.js')}}"></script>

<!-- data-table js -->
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


@endsection



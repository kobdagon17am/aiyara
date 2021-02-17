 
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
{{--       <div class="row">
        <div class="col-lg-12 col-xl-3">
          <div class="user-head row">
            <div class="user-face">
              <img class="img-fluid" src="../files/assets/images/logo.png" alt="Theme-Logo" />
            </div>
          </div>
        </div>
        <div class="col-lg-12 col-xl-9">
          <div class="mail-box-head row">
            <div class="col-md-12">
              <form class="f-right">
                <div class="right-icon-control">
                  <input type="text" class="form-control  search-text" placeholder="Search Friend" id="search-friends-2">
                  <div class="form-icon">
                    <i class="icofont icofont-search"></i>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div> --}}
      <div class="row">
        <!-- Left-side section start -->
        <div class="col-lg-3 col-xl-3 ">
          <div class="user-body">
            <div class="p-20 text-center">
              <h5> ติดต่อ/สอบถาม </h5>
              {{-- <a href="email-compose.html" class="btn btn-danger">ติดต่อแจ้งปัญหา</a> --}}
            </div>
            <ul class="page-list nav nav-tabs flex-column" id="pills-tab" role="tablist">

              <li class="nav-item mail-section">
                <a class="nav-link {{$contact}}" data-toggle="pill" href="#e-contact" role="tab">
                  <i class="icofont icofont-inbox"></i> ติดต่อ/สอบถาม
                  {{--  <span class="label label-primary f-right">6</span> --}}
                </a>
              </li> 

              <li class="nav-item mail-section">
                <a class="nav-link {{ $inbox }}" data-toggle="pill" href="#e-inbox" role="tab">
                  <i class="icofont icofont-inbox"></i> Inbox

                  <?php  
                  $noti = \App\Helpers\Frontend::notifications(Auth::guard('c_user')->user()->id);
                   
                  
                   ?>
                   @if($noti['count'] > 0)
                  <span class="label label-primary f-right">{{ $noti['count'] }}</span>
                  @endif
                </a>
              </li>

            
            </ul>
            <ul class="p-20 label-list">
              <li>
                <h5>Status</h5>
              </li>

              <li>
                <a class="mail-work">New Message</a>
              </li>
         {{--      <li>
                <a class="mail-work" href="">Work</a>
              </li>
              <li>
                <a class="mail-design" href="">Design</a>
              </li> --}}
              
           {{--    <li>
                <a class="mail-friends" href="">Friends</a>
              </li>
              <li>
                <a class="mail-office" href="">Office</a>
              </li> --}}
            </ul>
          </div>
        </div>
        <!-- Left-side section end -->
        <!-- Right-side section start -->
        <div class="col-lg-9 col-xl-9">
          <div class="tab-content" id="pills-tabContent">

            <div class="tab-pane fade {{ $contact_tab }}" id="e-contact" role="tabpanel">

              <div class="mail-body">

                <div class="mail-body-content">

                 <form action="{{ route('message_question') }}" method="POST">
                  @csrf
                  <div class="form-group">
                    <input type="text" name="subject" class="form-control" placeholder="Subject" required="">
                  </div>
               {{--  <div class="form-group">
                  <div class="row">
                    <div class="col-md-6">
                      <input type="email" class="form-control" placeholder="Cc">
                    </div>
                    <div class="col-md-6">
                      <input type="email" class="form-control" placeholder="Bcc">
                    </div>
                  </div>
                </div>  --}}

                <textarea name="question"></textarea>

                <div class="row mt-2 f-right mr-0"> 
                 <button type="submit" class="btn btn-primary">ส่งข้อมูลการสอบถาม</button>
               </div>
             </form>

           </div>
         </div>

       </div>

       <div class="tab-pane fade {{ $inbox_tab }}" id="e-inbox" role="tabpanel">

        <div class="mail-body">

          <div class="mail-body-content">
            <div class="dt-responsive table-responsive">
              <table id="simpletable" class="table table-striped table-bordered nowrap">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Title</th>

                    <th>Detail</th>
                    <th>Date time</th>

                  </tr>
                </thead>
                <tbody>
                  @foreach($data as $value)

                  <?php 

                  $answers = strip_tags($value->answers); 

                  $text = strlen($answers) > 60 ? substr( $answers,0,60)."..." : $answers; ?>
                  <tr>
                   <td>@if($value->see_status == 0)<a href="{{ route('message_read',['id'=>$value->id]) }}">
                    <i class="icofont icofont-star text-primary"></i></a>@endif</td>
                   <td><a href="{{ route('message_read',['id'=>$value->id]) }}">{!! $value->topics_question !!}</a></td>

                   <td><a href="{{ route('message_read',['id'=>$value->id]) }}">{!! $text !!}</a></td>

                   <td><a href="{{ route('message_read',['id'=>$value->id]) }}">@if($value->details_question) {!! date('d/m/Y H:i:s',strtotime($value->answers_create)) !!} @endif </a></td>
                 </tr>
                 @endforeach

               </tbody>

             </table>
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



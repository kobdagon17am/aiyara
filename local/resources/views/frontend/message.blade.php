
@extends('frontend.layouts.customer.customer_app')
@section('conten')
@section('css')
<!-- Data Table Css -->
<link rel="stylesheet" type="text/css" href="{{asset('frontend/bower_components/datatables.net-bs4/css/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('frontend/assets/pages/data-table/css/buttons.dataTables.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('frontend/bower_components/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css')}}">
@endsection




<div class="row">
  <div class="col-md-12">
    <div class="card">
      <div class="card-header">

        <div class="row">
          <div class="col-6">
            <h4>ติดต่อ/สอบถาม</h4>
          </div>
          <div class="col-6">
            <button type="button" class="btn btn-primary waves-effect f-right" data-toggle="modal" data-target="#large-Modal"> <i class="fa fa-plus-square"></i> สอบถาม</button>
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
                      <input type="text" name="subject" class="form-control" placeholder="หัวข้อ" required="">


                    </div>

                    <textarea rows="5" cols="5" class="form-control" name="question" placeholder="รายละเอียด"></textarea>



                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect " data-dismiss="modal">Close</button>
                    @if($canAccess)
                    <button type="submit" class="btn btn-primary waves-effect waves-light ">Send</button>
                    @endif
                  </div>

                </div>
              </form>
            </div>
          </div>


        </div>



        {{-- <span>DataTables has most features enabled by default, so all you need to do to use it with your own ables is to call the construction function: $().DataTable();.</span> --}}
      </div>

      <div class="card-block">
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

            $answers = strip_tags($value->details_question);

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

      <p><span><i class="icofont icofont-star text-primary"></i> New Message</span> | <span><i class="icofont icofont-star text-warning"></i> Sent Message</span></p>



    </div>
  </div>
</div>
</div>


@endsection
@section('js')




<!-- tinymce js -->
{{-- <script src="{{asset('frontend/assets/pages/wysiwyg-editor/js/tinymce.min.js')}}"></script> --}}
<!-- Custom js -->
{{-- <script src="{{asset('frontend/assets/pages/wysiwyg-editor/wysiwyg-editor.js')}}"></script> --}}

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



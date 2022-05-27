<div class="modal fade" id="modal_add_show" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">@lang('message.add') {{ $type }}</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        {{-- <h5>@lang('message.add') {{ $type }}</h5> --}}

        <p>@lang('message.under') @if(@$data->business_name != '-' and $data->business_name){{ $data->business_name }}
          @else {{$data->prefix_name.' '.$data->first_name.' '.$data->last_name }} @endif
          ( {{$data->user_name}} )</p>
      </div>
      <div class="modal-footer">
        @if($canAccess)
        <button type="button" class="btn btn-default waves-effect " data-dismiss="modal">Close</button>
        <a href="{{route('register',['id'=>$data->user_name,'line_type'=>$type])}}" type="button" class="btn btn-primary waves-effect waves-light ">Add</a>
        @endcan
      </div>
    </div>
  </div>
</div>

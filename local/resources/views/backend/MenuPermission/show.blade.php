<div class="modal fade" id="large-Modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Detail {{$data->name}}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @foreach($getRowData AS $row)
                    @php
                        $Menu = App\Model\MenuModel::query()->where('id_menu',$row->main_menu_id)->first();
                    @endphp
                    <ul>
                        <li><h5>{{$Menu->name_menu}}</h5></li>
                    @if(count(explode(',',$row->submenu_id)) > 0)
                        @foreach(explode(',',$row->submenu_id) AS $rowSubMenu)
                            @php
                                $subMenu = App\Model\SubMenuModel::query()->where('id_menu_sub',$rowSubMenu)->first();
                            @endphp
                                <li>*{{$subMenu->name_menu}}</li>
                        @endforeach
                    @endif
                    </ul>

                @endforeach
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default waves-effect " data-dismiss="modal">Close</button>
                {{--<button type="button" class="btn btn-primary waves-effect waves-light ">Save changes</button>--}}
            </div>
        </div>
    </div>
</div>
<script>
    // $('#large-Modal').modal({backdrop: 'static', keyboard: false});
</script>

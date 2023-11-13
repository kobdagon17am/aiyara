@extends('backend.layouts.master')

@section('title') Aiyara Planet @endsection

@section('css')

@section('css')
    <style>
        .select2-selection {
            height: 34px !important;
            margin-left: 3px;
        }

        .border-left-0 {
            height: 67%;
        }

        .form-group {
            margin-bottom: 0rem !important;
        }

        .btn-outline-secondary {
            margin-bottom: 36% !important;
        }
    </style>

    <style>
        #notification {
            position: absolute;
            top: 150;
            margin-left: 60%;
            /*margin-right: 5% ;*/
            /*float: right;*/
        }

        .toast-color {
            color: white;
            background-color: #33b5e5;
            border-radius: 5px;
        }

        h1 {
            color: green;
        }

        .column {
            float: left;
            width: 50%;
            padding: 10px;
            /* height: 200px; Should be removed. Only for demonstration */
        }

        /* Clear floats after the columns */
        .row:after {
            content: "";
            display: table;
            clear: both;
        }

        /* DivTable.com */
        .divTable {
            display: table;
            width: 100%;
        }

        .divTableRow {
            display: table-row;
        }

        .divTableHeading {
            background-color: #EEE;
            display: table-header-group;
        }

        .divTableCell,
        .divTableHead {
            /*border: 1px solid #999999;*/
            display: table-cell;
            padding: 3px 10px;
        }

        .divTableHeading {
            background-color: #EEE;
            display: table-header-group;
            font-weight: bold;
        }

        .divTableFoot {
            background-color: #EEE;
            display: table-footer-group;
            font-weight: bold;
        }

        .divTableBody {
            display: table-row-group;
        }


        .table.dataTable>tbody>tr>td {
            line-height: 2rem !important;
        }
    </style>

@endsection

@section('content')
    <div class="myloading"></div>
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0 font-size-18"> {{ __('message.member_register') }} </h4>
            </div>
        </div>
    </div>
    <!-- end page title -->
    <?php
    $sPermission = \Auth::user()->permission;
    // $menu_id = @$_REQUEST['menu_id'];
    $menu_id = Session::get('session_menu_id');
    if ($sPermission == 1) {
        $sC = '';
        $sU = '';
        $sD = '';
        $role_group_id = '%';
    } else {
        $role_group_id = \Auth::user()->role_group_id_fk;
        // echo $role_group_id;
        // echo $menu_id;
        $menu_permit = DB::table('role_permit')
            ->where('role_group_id_fk', $role_group_id)
            ->where('menu_id_fk', $menu_id)
            ->first();
        $sC = @$menu_permit->c == 1 ? '' : 'display:none;';
        $sU = @$menu_permit->u == 1 ? '' : 'display:none;';
        $sD = @$menu_permit->d == 1 ? '' : 'display:none;';
    }
    // echo $sPermission;
    // echo $role_group_id;
    // echo $menu_id;
    ?>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">


                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" role="tablist">

                        <li class="nav-item">
                            <a class="nav-link tab_a active " data-toggle="tab" href="#home" role="tab">
                                <span class="d-block d-sm-none"><i class="fas fa-home"></i></span>
                                <span class="d-none d-sm-block">{{ __('message.document_checking') }}</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link tab_b  " data-toggle="tab" href="#second_tab" role="tab">
                                <span class="d-block d-sm-none"><i class="far fa-user"></i></span>
                                <span class="d-none d-sm-block">{{ __('message.summary_document_checked_list') }}</span>
                            </a>
                        </li>

                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content p-3 text-muted">
                        <div class="tab-pane  active " id="home" role="tabpanel">
                            <p class="mb-0">

                            <div class="myBorder">

                                <div class="row">
                                    <div class="col-md-6 ">
                                        <div class="form-group row">
                                            <label for="business_location_id_fk"
                                                class="col-md-3 col-form-label">{{ __('message.location') }} : </label>
                                            <div class="col-md-9">
                                                <select id="business_location_id_fk" name="business_location_id_fk"
                                                    class="form-control select2-templating " required=""
                                                    @if ($sPermission !== 1) disabled @endif>
                                                    <option value="">-Business Location-</option>
                                                    @if (@$sBusiness_location)
                                                        @foreach (@$sBusiness_location as $r)
                                                            <option value="{{ @$r->id }}"
                                                                {{ @$r->id == \Auth::user()->business_location_id_fk ? 'selected' : '' }}>
                                                                {{ $r->txt_desc }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- <div class="col-md-6 " >
                      <div class="form-group row">
                            <label for="branch_id_fk" class="col-md-3 col-form-label"> {{ __('message.branch') }} : </label>
                            <div class="col-md-9">

                              <select id="branch_id_fk"  name="branch_id_fk" class="form-control select2-templating "  @if ($sPermission !== 1) disabled @endif>
                                 <option disabled selected value="">กรุณาเลือก Business Location ก่อน</option>
                                 @if (@$sBranchs)
                                  @foreach (@$sBranchs as $r)
                                   @if ($sPermission == 1)
                                    @if ($r->business_location_id_fk == \Auth::user()->business_location_id_fk)
                                    <option value="{{@$r->id}}" {{ (@$r->id==(\Auth::user()->branch_id_fk))?'selected':'' }} >{{$r->b_name}}</option>
                                    @endif
                                    @else
                                     @if ($r->business_location_id_fk == \Auth::user()->business_location_id_fk)
                                    <option value="{{@$r->id}}" {{ (@$r->id==(\Auth::user()->branch_id_fk))?'selected':'' }} >{{$r->b_name}}</option>
                                    @endif
                                    @endif
                                  @endforeach
                                @endif
                              </select>
                            </div>
                          </div>
                    </div> --}}
                                </div>

                                <div class="row">
                                    <div class="col-md-6 ">
                                        <div class="form-group row">
                                            <label for="customer_id" class="col-md-3 col-form-label">
                                                {{ __('message.info_member') }} : </label>
                                            <div class="col-md-9">
                                                <select id="customer_id" name="customer_id"
                                                    class="form-control customer_id_fk">
                                                    <option value="">-Select-</option>
                                                    {{-- @if (@$customer)
                                                        @foreach (@$customer as $r)
                                                            <option value="{{ $r->customer_id }}">
                                                                {{ $r->cus_code }} :
                                                                {{ $r->prefix_name }}{{ $r->first_name }}
                                                                {{ $r->last_name }}
                                                            </option>
                                                        @endforeach
                                                    @endif --}}
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 ">
                                        <div class="form-group row">
                                            <label for="po_status" class="col-md-3 col-form-label">
                                                {{ __('message.file_type') }} : </label>
                                            <div class="col-md-9">
                                                <select id="filetype" name="filetype"
                                                    class="form-control select2-templating ">
                                                    <option value="">-Select-</option>
                                                    @if (@$filetype)
                                                        @foreach (@$filetype as $r)
                                                            <option value="{{ $r->id }}">
                                                                {{ $r->txt_desc }}
                                                            </option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 ">
                                        <div class="form-group row">
                                            <label for="startDate" class="col-md-3 col-form-label">
                                                {{ __('message.date_register') }} : </label>
                                            <div class="col-md-9 d-flex">
                                                <input id="startDate" autocomplete="off" placeholder="Begin Date"
                                                    style="margin-left: 1.5%;border: 1px solid grey;font-weight: bold;color: black" />
                                                <input id="endDate" autocomplete="off" placeholder="End Date"
                                                    style="border: 1px solid grey;font-weight: bold;color: black" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 ">
                                        <div class="form-group row">
                                            <label for="approver" class="col-md-3 col-form-label">
                                                {{ __('message.approver') }} : </label>
                                            <div class="col-md-9 ">
                                                <select id="approver" name="approver"
                                                    class="form-control select2-templating ">
                                                    <option value="">-Select-</option>
                                                    @if (@$sApprover)
                                                        @foreach (@$sApprover as $r)
                                                            <option value="{{ $r->id }}">
                                                                {{ $r->name }}
                                                            </option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row" style="margin-bottom: 2% !important;">
                                    <div class="col-md-6 " style="margin-top: -1% !important;">
                                        <div class="form-group row">
                                            <label for="" class="col-md-3 col-form-label"> </label>
                                            <div class="col-md-9">

                                            </div>
                                        </div>
                                    </div>


                                    <div class="col-md-6 " style="margin-top: -0.5% !important;">
                                        <div class="form-group row">
                                            <label for="" class="col-md-3 col-form-label"> </label>
                                            <div class="col-md-9">
                                                <a class="btn btn-info btn-sm btnSearch01 " href="#"
                                                    style="font-size: 14px !important;margin-left: 0.8%;">
                                                    <i class="bx bx-search align-middle "></i> {{ __('message.search') }}
                                                </a>

                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <table id="data-table" class="table table-bordered " style="width: 100%;">
                                </table>

                            </div>

                            <div style="text-align: center;">
                                <b>{{ __('message.remark') }}</b> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <?php
                                foreach ($filetype as $key => $value) {
                                    echo $value->icon;
                                    echo ' : ';
                                    echo __('message.file_types.' . $value->id);
                                    echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
                                }
                                ?>
                            </div>
                            </p>
                        </div>


                        <!--Tab panes -->
                        <div class="tab-pane  " id="second_tab" role="tabpanel">
                            <p class="mb-0">


                            <div class="myBorder">

                                <div class="row">
                                    <div class="col-md-6 ">
                                        <div class="form-group row">
                                            <label for="startDate" class="col-md-3 col-form-label">
                                                {{ __('message.date_register') }} : </label>
                                            <div class="col-md-8 d-flex">
                                                <input id="startDate02" autocomplete="off" placeholder="Begin Date"
                                                    style="margin-left: 1.5%;border: 1px solid grey;font-weight: bold;color: black" />
                                                <input id="endDate02" autocomplete="off" placeholder="End Date"
                                                    style="border: 1px solid grey;font-weight: bold;color: black" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-5 ">
                                        <div class="form-group row">
                                            <label for="regis_doc_status" class="col-md-3 col-form-label">
                                                {{ __('message.status') }} : </label>
                                            <div class="col-md-9 ">
                                                <select id="regis_doc_status" name="regis_doc_status"
                                                    class="form-control select2-templating ">
                                                    <option value="">-Select-</option>
                                                    @if (@$regis_doc_status)
                                                        @foreach (@$regis_doc_status as $r)
                                                            <option value="{{ $r->id }}">
                                                                {{ $r->txt_desc }}
                                                            </option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row" style="margin-bottom: 2% !important;">
                                    <div class="col-md-6 " style="margin-top: -1% !important;">
                                        <div class="form-group row">
                                            <label for="" class="col-md-3 col-form-label"> </label>
                                            <div class="col-md-9">

                                            </div>
                                        </div>
                                    </div>


                                    <div class="col-md-5 " style="margin-top: -0.5% !important;">
                                        <div class="form-group row">
                                            <label for="" class="col-md-3 col-form-label"> </label>
                                            <div class="col-md-9">
                                                <a class="btn btn-info btn-sm btnSearch02 " href="#"
                                                    style="font-size: 14px !important;margin-left: 0.8%;">
                                                    <i class="bx bx-search align-middle "></i> {{ __('message.search') }}
                                                </a>

                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <table id="data-table-02" class="table table-bordered " style="width: 100%;">
                                </table>

                            </div>

                            </p>


                            <b style="font-size: 14px;">{{ __('message.remark') }}</b>
                            <div class="divTable">
                                <div class="divTableBody">
                                    <div class="divTableRow">
                                        <?php
                                        foreach ($filetype as $key => $value) {
                                            echo '<div class="divTableCell">' . $value->icon . ' : ' . __('message.file_types.' . $value->id) . ' </div>';
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>

                            <div class=""
                                style="
                  margin-left: 1%;
                  color: black;
                  ">
                                {!! __('message.member_pv_status_info') !!}
                            </div>

                        </div>

                    </div>
                    <!--end Tab panes -->

                </div>
                <!--end Tab  -->

            </div> <!-- end card-body -->
        </div> <!-- end card -->
    </div> <!-- end col-12 -->
    </div> <!-- end row -->



    <div class="modal fade" id="checkRegis" tabindex="-1" role="dialog" aria-labelledby="checkRegisTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document" style="max-width: 1000px !important;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="checkRegisTitle"><b><i
                                class="bx bx-play"></i>{{ __('message.register_checked_list') }} </b></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form action="{{ route('backend.member_regis.store') }}" method="POST" enctype="multipart/form-data"
                    autocomplete="off">
                    <input type="hidden" name="save_regis" value="1">
                    <input type="hidden" id="id" name="id" value="">
                    <input type="hidden" id="type" name="type">
                    {{ csrf_field() }}

                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <center>

                                            <h2 id="cus_name"></h2>

                                            <div class="row">
                                                <div class="column column_1 " style="background-color:#aaa;">
                                                    <h4 class="p_desc_1">(1) {{ __('message.file_types.1') }}</h4>
                                                    <p> <img id="file_path1" class="grow img-fluid" src=""
                                                            width="80%" style="cursor: pointer;"> </p>
                                                    <h4 class="p_desc_11"></h4>
                                                </div>
                                                <div class="column column_2 " style="background-color:#bbb;">
                                                    <h4 class="p_desc_2">(2) {{ __('message.file_types.2') }}</h4>
                                                    <p> <img id="file_path2" class="grow img-fluid" src=""
                                                            width="80%" style="cursor: pointer;"> </p>
                                                </div>
                                            </div>


                                            <div class="row">
                                                <div class="column column_3 " style="background-color:#bbb;color: black;">
                                                    <h4 class="p_desc_3">(3) {{ __('message.file_types.3') }}</h4>
                                                    <p> <img id="file_path3" class="grow img-fluid" src=""
                                                            width="80%" style="cursor: pointer;"> </p>
                                                    <h4 class="p_desc_33"></h4>
                                                </div>
                                                <div class="column column_4 " style="background-color:#aaa;">
                                                    <h4 class="p_desc_4">(4) {{ __('message.file_types.4') }}</h4>
                                                    <p> <img id="file_path4" class="grow img-fluid" src=""
                                                            width="80%" style="cursor: pointer;"> </p>
                                                    <h4 class="p_desc_44"> </h4>
                                                </div>
                                            </div>


                                            <br>

                                            {{-- วุฒิเพิ่มมา --}}

                                            <h4>ข้อมูลทั่วไป</h4>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group row">
                                                        <label for="comment" class="col-form-label">
                                                            {{ __('message.name_first') }} : <span
                                                                id="text_name_first"></span> </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group row">
                                                        <label for="comment" class="col-form-label">
                                                            {{ __('message.name_last') }} : <span
                                                                id="text_name_last"></span></label>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group row">
                                                        <label for="comment" class="col-form-label">
                                                            {{ __('message.name_business') }} : <span
                                                                id="text_name_business"></span></label>
                                                    </div>
                                                </div>
                                            </div>
                                            <br>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group row">
                                                        <label for="comment" class="col-form-label">
                                                            {{ __('message.ID_card_number') }} : <span
                                                                id="text_ID_card_number"></span> </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group row">
                                                        <label for="comment" class="col-form-label">
                                                            {{ __('message.email') }} : <span
                                                                id="text_email"></span></label>
                                                    </div>
                                                </div>
                                                {{-- <div class="col-md-4">
                                                    <div class="form-group row">
                                                        <label for="comment" class="col-form-label">
                                                            {{ __('message.upline') }} : <span
                                                                id="text_upline"></span></label>
                                                    </div>
                                                </div> --}}
                                            </div>
                                            <br>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group row">
                                                        <label for="comment" class="col-form-label">
                                                            {{ __('message.Referral_UserName') }} : <span
                                                                id="text_Referral_UserName"></span> </label>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                  <div class="form-group row">
                                                      <label for="comment" class="col-form-label">
                                                          {{ __('message.upline') }} : <span
                                                              id="text_upline"></span></label>
                                                  </div>
                                              </div>
                                            </div>
                                            <br>
                                            <hr>
                                            <h4>ที่อยู่ตามบัตรประชาชน</h4>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group row">
                                                        <label for="comment" class="col-form-label">
                                                            {{ __('message.no') }} : <span id="text_no"></span> </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group row">
                                                        <label for="comment" class="col-form-label">
                                                            {{ __('message.building') }} : <span
                                                                id="text_building"></span></label>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group row">
                                                        <label for="comment" class="col-form-label">
                                                            {{ __('message.villageno') }} : <span
                                                                id="text_villageno"></span></label>
                                                    </div>
                                                </div>
                                            </div>
                                            <br>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group row">
                                                        <label for="comment" class="col-form-label">
                                                            {{ __('message.lane') }} : <span id="text_lane"></span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group row">
                                                        <label for="comment" class="col-form-label">
                                                            {{ __('message.road') }} : <span
                                                                id="text_road"></span></label>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group row">
                                                        <label for="comment" class="col-form-label">
                                                            {{ __('message.province') }} : <span
                                                                id="text_province"></span></label>
                                                    </div>
                                                </div>
                                            </div>
                                            <br>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group row">
                                                        <label for="comment" class="col-form-label">
                                                            {{ __('message.district/area') }} : <span
                                                                id="text_district_area"></span> </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group row">
                                                        <label for="comment" class="col-form-label">
                                                            {{ __('message.sub-district/sub-area') }} : <span
                                                                id="text_sub-district_sub-area"></span></label>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group row">
                                                        <label for="comment" class="col-form-label">
                                                            {{ __('message.zipcode') }} : <span
                                                                id="text_zipcode"></span></label>
                                                    </div>
                                                </div>
                                            </div>
                                            <br>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group row">
                                                        <label for="comment" class="col-form-label">
                                                            {{ __('message.Mobile_Phone') }} : <span
                                                                id="text_Mobile_Phone"></span> </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group row">
                                                        <label for="comment" class="col-form-label">
                                                            {{ __('message.Home_Phone') }} : <span
                                                                id="text_Home_Phone"></span></label>
                                                    </div>
                                                </div>
                                            </div>
                                            <br>
                                            <hr>
                                            <h4>ข้อมูลธนาคาร</h4>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group row">
                                                        <label for="comment" class="col-form-label">
                                                            {{ __('message.accountname') }} : <span
                                                                id="text_accountname"></span> </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group row">
                                                        <label for="comment" class="col-form-label">
                                                            {{ __('message.accountno') }} : <span
                                                                id="text_accountno"></span></label>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group row">
                                                        <label for="comment" class="col-form-label">
                                                            {{ __('message.bankingaccount') }} : <span
                                                                id="text_bankingaccount"></span></label>
                                                    </div>
                                                </div>
                                            </div>
                                            <br>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group row">
                                                        <label for="comment" class="col-form-label">
                                                            {{ __('message.branch') }} : <span id="text_branch"></span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group row">
                                                        <label for="comment" class="col-form-label">
                                                            {{ __('message.accounttype') }} : <span
                                                                id="text_ประเภทบัญชี"></span></label>
                                                    </div>
                                                </div>
                                            </div>
                                            <br>
                                            {{--  --}}
                                            <hr>
                                            <h4>ยืนยันข้อมูล</h4>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group row">
                                                        <label for="comment" class="col-md-4 col-form-label">
                                                            {{ __('message.remark') }} : </label>
                                                        <div class="col-md-6">
                                                            <textarea class="form-control" rows="3" id="comment" name="comment"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <br>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group row">
                                                        <label for="regis_status" class="col-md-4 col-form-label">
                                                            {{ __('message.check_result') }} : </label>
                                                        <div class="col-md-4">
                                                            <select name="regis_status" id="regis_status"
                                                                class="form-control select2-templating " required>
                                                                <option value="">Select</option>
                                                                <option value="1">{{ __('message.pass') }}</option>
                                                                <option value="2">{{ __('message.fail') }}</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <br>

                                            <div class="row">
                                                <div class="col-md-12 text-center  ">
                                                    <button type="submit" class="btn btn-primary"
                                                        style="width: 10%;">Save</button>
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal"
                                                        style="margin-left: 1%;">Close</button>
                                                </div>
                                            </div>

                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                </form>

            </div>
        </div>
    </div>


@endsection

@section('script')


    <script>
        var role_group_id = "{{ @$role_group_id ? @$role_group_id : 0 }}"; //alert(sU);
        var menu_id = "{{ @$menu_id ? @$menu_id : 0 }}"; //alert(sU);
        var sU = "{{ @$sU }}"; //alert(sU);
        var sD = "{{ @$sD }}"; //alert(sD);
        var oTable;
        $(function() {
            oTable = $('#data-table').DataTable({
                "sDom": "<'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>",
                processing: true,
                serverSide: true,
                scroller: true,
                destroy: true,
                ordering: true,
                // order: [[1, 'desc']],
                iDisplayLength: 100,
                ajax: {
                    url: '{{ route('backend.member_regis.datatable_list') }}',
                    data: function(d) {
                        d.Where = {};
                        $('.myWhere').each(function() {
                            if ($.trim($(this).val()) && $.trim($(this).val()) != '0') {
                                d.Where[$(this).attr('name')] = $.trim($(this).val());
                            }
                        });
                        d.Like = {};
                        $('.myLike').each(function() {
                            if ($.trim($(this).val()) && $.trim($(this).val()) != '0') {
                                d.Like[$(this).attr('name')] = $.trim($(this).val());
                            }
                        });
                        d.Custom = {};
                        $('.myCustom').each(function() {
                            if ($.trim($(this).val()) && $.trim($(this).val()) != '0') {
                                d.Custom[$(this).attr('name')] = $.trim($(this).val());
                            }
                        });
                        oData = d;
                    },
                    method: 'POST'
                },

                columns: [{
                        data: 'DT_RowIndex',
                        title: 'NO.',
                        className: 'text-center w50'
                    },
                    {
                        data: 'id',
                        title: 'ID',
                        className: 'text-center w50'
                    },

                    {
                        data: 'customer_name',
                        title: '<center>{{ __('message.info_member') }} </center>',
                        className: 'text-left  w200'
                    },
                    {
                        data: 'filetype',
                        title: '<center> {{ __('message.file_type') }} </center>',
                        className: 'text-left w200'
                    },
                    {
                        data: 'regis_status',
                        title: '<center> {{ __('message.status') }} </center>',
                        className: 'text-center'
                    },
                    {
                        data: 'approver',
                        title: '<center>{{ __('message.approver') }} </center>',
                        className: 'text-center'
                    },
                    {
                        data: 'approve_date',
                        title: '<center>{{ __('message.date_approve') }} </center>',
                        className: 'text-center'
                    },
                    {
                        data: 'icon',
                        title: '<center> Icon </center>',
                        className: 'text-center'
                    },
                    {
                        data: 'tools',
                        title: 'Tools',
                        className: 'text-center w80'
                    },
                ],

                rowCallback: function(nRow, aData, dataIndex) {
                    // $('td:first-child', nRow).html(dataIndex + 1);
                    //  if(sU!=''&&sD!=''){
                    //     $('td:last-child', nRow).html('-');
                    //  }else{
                    //   // console.log(aData['customer_id']+" : "+aData['type']+" : "+aData['regis_status_02']+" : "+aData['item_checked']);
                    //   if(aData['regis_status_02']=='S' && aData['item_checked']==0){
                    //       $('td:last-child', nRow).html('-');
                    //   }else{
                    //      $('td:last-child', nRow).html(''
                    //         + '<a href="#" class="btn btn-sm btn-primary btnCheckRegis " data-id="'+aData['id']+'"  ><i class="bx bx-edit font-size-16 align-middle"></i> </a> '
                    //       ).addClass('input');
                    //   }
                    // }

                }
            });

        });
    </script>



    <script>
        var role_group_id = "{{ @$role_group_id ? @$role_group_id : 0 }}"; //alert(sU);
        var menu_id = "{{ @$menu_id ? @$menu_id : 0 }}"; //alert(sU);
        var sU = "{{ @$sU }}"; //alert(sU);
        var sD = "{{ @$sD }}"; //alert(sD);
        var oTable;
        // $(function() {
        //     oTable = $('#data-table-02').DataTable({
        //         "sDom": "<'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>",
        //         processing: true,
        //         serverSide: true,
        //         scroller: true,
        //         destroy: true,
        //         ordering: false,
        //         iDisplayLength: 25,
        //         searching: false,
        //         ajax: {
        //             url: '{{ route('backend.member_regis02.datatable') }}',
        //             data: function(d) {
        //                 d.Where = {};
        //                 $('.myWhere').each(function() {
        //                     if ($.trim($(this).val()) && $.trim($(this).val()) != '0') {
        //                         d.Where[$(this).attr('name')] = $.trim($(this).val());
        //                     }
        //                 });
        //                 d.Like = {};
        //                 $('.myLike').each(function() {
        //                     if ($.trim($(this).val()) && $.trim($(this).val()) != '0') {
        //                         d.Like[$(this).attr('name')] = $.trim($(this).val());
        //                     }
        //                 });
        //                 d.Custom = {};
        //                 $('.myCustom').each(function() {
        //                     if ($.trim($(this).val()) && $.trim($(this).val()) != '0') {
        //                         d.Custom[$(this).attr('name')] = $.trim($(this).val());
        //                     }
        //                 });
        //                 oData = d;
        //             },
        //             method: 'POST'
        //         },

        //         dom: 'frtipB',
        //         buttons: [{
        //                 extend: 'excelHtml5',
        //                 title: 'รายการตรวจเอกสาร'
        //             },

        //         ],
        //         columns: [{
        //                 data: 'id',
        //                 title: 'No.',
        //                 className: 'text-center w50'
        //             },
        //             {
        //                 data: 'created_at',
        //                 title: '<center>{{ __('message.date_register') }}</center>',
        //                 className: 'text-center'
        //             },
        //             {
        //                 data: 'customer_name',
        //                 title: '<center>{{ __('message.info_member') }}</center>',
        //                 className: 'text-left'
        //             },
        //             {
        //                 data: 'icon',
        //                 title: '<center> {{ __('message.doc') }} </center>',
        //                 className: 'text-center'
        //             },
        //             {
        //                 data: 'regis_date_doc',
        //                 title: '<center>{{ __('message.date_of_file_approved') }} </center>',
        //                 className: 'text-center'
        //             },
        //             {
        //                 data: 'updated_at',
        //                 title: '<center>{{ __('message.latest_editing') }} </center>',
        //                 className: 'text-center'
        //             },
        //         ],
        //         rowCallback: function(nRow, aData, dataIndex) {

        //             var info = $(this).DataTable().page.info();
        //             $("td:eq(0)", nRow).html(info.start + dataIndex + 1);


        //         }
        //     });
        //     $('.myWhere,.myLike,.myCustom,#onlyTrashed').on('change', function(e) {
        //         oTable.draw();
        //     });
        // });
    </script>


    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"
        integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
    <script src="https://unpkg.com/gijgo@1.9.13/js/gijgo.min.js" type="text/javascript"></script>
    <link href="https://unpkg.com/gijgo@1.9.13/css/gijgo.min.css" rel="stylesheet" type="text/css" />

    <script>
        var today = new Date(new Date().getFullYear(), new Date().getMonth(), new Date().getDate());
        $('#startDate').datepicker({
            // format: 'dd/mm/yyyy',
            format: 'yyyy-mm-dd',
            uiLibrary: 'bootstrap4',
            iconsLibrary: 'fontawesome',
        });

        $('#endDate').datepicker({
            // format: 'dd/mm/yyyy',
            format: 'yyyy-mm-dd',
            uiLibrary: 'bootstrap4',
            iconsLibrary: 'fontawesome',
            minDate: function() {
                return $('#startDate').val();
            }
        });

        $('#startDate').change(function(event) {

            if ($('#endDate').val() > $(this).val()) {} else {
                $('#endDate').val($(this).val());
            }
            $('#startPayDate').val('');
            $('#endPayDate').val('');

        });
    </script>

    <script>
        $('#startDate02').datepicker({
            // format: 'dd/mm/yyyy',
            format: 'yyyy-mm-dd',
            uiLibrary: 'bootstrap4',
            iconsLibrary: 'fontawesome',
        });

        $('#endDate02').datepicker({
            // format: 'dd/mm/yyyy',
            format: 'yyyy-mm-dd',
            uiLibrary: 'bootstrap4',
            iconsLibrary: 'fontawesome',
            minDate: function() {
                return $('#startDate').val();
            }
        });

        $('#startDate02').change(function(event) {

            if ($('#endDate02').val() > $(this).val()) {} else {
                $('#endDate02').val($(this).val());
            }
            $('#startPayDate02').val('');
            $('#endPayDate02').val('');

        });
    </script>

    <script>
        $('#startPayDate').datepicker({
            format: 'yyyy-mm-dd',
            uiLibrary: 'bootstrap4',
            iconsLibrary: 'fontawesome',
        });

        $('#endPayDate').datepicker({
            format: 'yyyy-mm-dd',
            uiLibrary: 'bootstrap4',
            iconsLibrary: 'fontawesome',
            minDate: function() {
                return $('#startPayDate').val();
            }
        });

        $('#startPayDate').change(function(event) {

            if ($('#endPayDate').val() > $(this).val()) {} else {
                $('#endPayDate').val($(this).val());
            }

        });
    </script>


    <script>
        $(document).ready(function() {

          $(".customer_id_fk").select2({
                            minimumInputLength: 3,
                            allowClear: true,
                            placeholder: '-Select-',
                            ajax: {
                                url: " {{ url('backend/ajaxGetCustomer') }} ",
                                type: 'POST',
                                dataType: 'json',
                                delay: 250,
                                cache: false,
                                data: function(params) {
                                    return {
                                        term: params.term || '', // search term
                                        page: params.page || 1
                                    };
                                },
                                processResults: function(data, params) {
                                    return {
                                        results: data
                                    };
                                }
                            }
                        });


            $(document).on('click', '.btnSearch01', function(event) {
                event.preventDefault();
                $('#data-table').DataTable().clear();
                $(".myloading").show();
                var business_location_id_fk = $('#business_location_id_fk').val();
                // var branch_id_fk = $('#branch_id_fk').val();
                var branch_id_fk = '';
                var startDate = $('#startDate').val();
                var endDate = $('#endDate').val();
                var approver = $('#approver').val();
                var regis_status = $('#regis_status').val();
                var filetype = $('#filetype').val();
                var customer_id = $('#customer_id').val();

                if (business_location_id_fk == '') {
                    $('#business_location_id_fk').select2('open');
                    $(".myloading").hide();
                    return false;
                }
                // alert(branch_id_fk);
                // if(branch_id_fk=='' || branch_id_fk === null ){
                //   $('#branch_id_fk').select2('open');
                //   $(".myloading").hide();
                //   return false;
                // }
                // @@@@@@@@@@@@@@@@@@@@@@@@@@ datatables @@@@@@@@@@@@@@@@@@@@@@@@@@
                var sU = "{{ @$sU }}";
                var sD = "{{ @$sD }}";
                var oTable;
                $(function() {
                    $.fn.dataTable.ext.errMode = 'throw';
                    oTable = $('#data-table').DataTable({
                        "sDom": "<'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>",
                        processing: true,
                        serverSide: true,
                        scroller: true,
                        destroy: true,
                          ordering: true,
                // order: [[1, 'desc']],
                iDisplayLength: 100,
                        iDisplayLength: 100,

                        ajax: {
                            url: '{{ route('backend.member_regis.datatable_list') }}',
                            data: {
                                _token: '{{ csrf_token() }}',
                                business_location_id_fk: business_location_id_fk,
                                branch_id_fk: branch_id_fk,
                                startDate: startDate,
                                endDate: endDate,
                                approver: approver,
                                regis_status: regis_status,
                                filetype: filetype,
                                customer_id: customer_id,
                            },
                            method: 'POST',
                        },
                        columns: [{
                                data: 'no',
                                title: 'NO.',
                                className: 'text-center w50'
                            }, {
                                data: 'id',
                                title: 'ID',
                                className: 'text-center w50'
                            },

                            {
                                data: 'customer_name',
                                title: '<center>{{ __('message.info_member') }} </center>',
                                className: 'text-left  w200'
                            },
                            {
                                data: 'filetype',
                                title: '<center> {{ __('message.file_type') }} </center>',
                                className: 'text-left w200'
                            },
                            {
                                data: 'regis_status',
                                title: '<center> {{ __('message.status') }} </center>',
                                className: 'text-center'
                            },
                            {
                                data: 'approver',
                                title: '<center>{{ __('message.approver') }} </center>',
                                className: 'text-center'
                            },
                            {
                                data: 'approve_date',
                                title: '<center>{{ __('message.date_approve') }} </center>',
                                className: 'text-center'
                            },
                            {
                                data: 'icon',
                                title: '<center> Icon </center>',
                                className: 'text-center'
                            },
                            {
                                data: 'tools',
                                title: 'Tools',
                                className: 'text-center w80'
                            },
                        ],
                        rowCallback: function(nRow, aData, dataIndex) {
                            $('td:first-child', nRow).html(dataIndex + 1);
                            // if(sU!=''&&sD!=''){
                            //     $('td:last-child', nRow).html('-');
                            // }else{

                            //     $('td:last-child', nRow).html(''
                            //       + '<a href="{{ route('backend.po_receive.index') }}/'+aData['id']+'/edit" class="btn btn-sm btn-primary" style="'+sU+'" ><i class="bx bx-edit font-size-16 align-middle"></i></a> '
                            //     ).addClass('input');

                            // }
                        }
                    });
                });

                // @@@@@@@@@@@@@@@@@@@@@@@@@@ datatables @@@@@@@@@@@@@@@@@@@@@@@@@@

                setTimeout(function() {
                    $(".myloading").hide();
                }, 1500);


            });
        });
    </script>



    <script>
        $(document).ready(function() {

            $(document).on('click', '.btnSearch02', function(event) {
                event.preventDefault();
                // $('#data-table-02').DataTable().clear();
                $(".myloading").show();
                var startDate02 = $('#startDate02').val();
                var endDate02 = $('#endDate02').val();
                var regis_doc_status = $('#regis_doc_status').val();

                if (startDate02 == '') {
                    $(".myloading").hide();
                    $("#startDate02").focus();
                    $("#startDate02").select('open');
                    return false;
                }

                // @@@@@@@@@@@@@@@@@@@@@@@@@@ datatables @@@@@@@@@@@@@@@@@@@@@@@@@@
                var sU = "{{ @$sU }}";
                var sD = "{{ @$sD }}";
                var oTable;
                $(function() {
                    $.fn.dataTable.ext.errMode = 'throw';
                    oTable = $('#data-table-02').DataTable({
                        "sDom": "<'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>",
                        processing: true,
                        serverSide: true,
                        scroller: true,
                        destroy: true,
                        ordering: false,
                        ajax: {
                            url: '{{ route('backend.member_regis02.datatable') }}',
                            data: {
                                _token: '{{ csrf_token() }}',
                                startDate: startDate02,
                                endDate: endDate02,
                                regis_doc_status: regis_doc_status,
                            },
                            method: 'POST',
                        },
                        columns: [{
                                data: 'id',
                                title: 'No.',
                                className: 'text-center w50'
                            },
                            {
                                data: 'created_at',
                                title: '<center>วันที่สมัคร</center>',
                                className: 'text-center'
                            },
                            {
                                data: 'customer_name',
                                title: '<center>รหัส : ชื่อสมาชิก </center>',
                                className: 'text-left'
                            },
                            {
                                data: 'icon',
                                title: '<center> เอกสาร </center>',
                                className: 'text-center'
                            },
                            {
                                data: 'updated_at',
                                title: '<center>ปรับปรุงล่าสุด </center>',
                                className: 'text-center'
                            },
                        ],
                        rowCallback: function(nRow, aData, dataIndex) {
                            var info = $(this).DataTable().page.info();
                            $("td:eq(0)", nRow).html(info.start + dataIndex + 1);

                        }
                    });
                });

                // @@@@@@@@@@@@@@@@@@@@@@@@@@ datatables @@@@@@@@@@@@@@@@@@@@@@@@@@

                setTimeout(function() {
                    $(".myloading").hide();
                }, 1500);


            });
        });
    </script>

    <script type="text/javascript">
        $('#business_location_id_fk').change(function() {

            $('.myloading').show();

            var business_location_id_fk = this.value;
            // alert(warehouse_id_fk);
            if (business_location_id_fk != '') {
                $.ajax({
                    url: " {{ url('backend/ajaxGetBranch') }} ",
                    method: "post",
                    data: {
                        business_location_id_fk: business_location_id_fk,
                        "_token": "{{ csrf_token() }}",
                    },
                    success: function(data) {
                        if (data == '') {
                            alert('ไม่พบข้อมูลสาขา !!.');
                        } else {
                            var layout = '<option value="" selected>- เลือกสาขา -</option>';
                            $.each(data, function(key, value) {
                                layout += '<option value=' + value.id + '>' + value.b_name +
                                    '</option>';
                            });
                            $('#branch_id_fk').html(layout);
                        }
                        $('.myloading').hide();
                    }
                })
            }

        });

        $(document).on('click', '.btnCheckRegis', function(event) {
            event.preventDefault();
            var id = $(this).data('id');
            $('#id').val(id);
            // console.log(id);


            $('#file_path1').hide();
            $('#file_path2').hide();
            $('#file_path3').hide();
            $('#file_path4').hide();

            console.log('id2 = '+id);
            // console.log('url' + ' : ' + value.file_path);
            // ajaxGetFilepath
            $.ajax({
                url: " {{ url('backend/ajaxGetFilepath') }} ",
                method: "post",
                data: {
                    id: id,
                    "_token": "{{ csrf_token() }}",
                },
                success: function(data) {

                    console.log(data);

                    $.each(data.rs, function(key, value) {
                        // if(value.file_path!=null){
                        // console.log(value.file_path);
                        // console.log(value.type);
                        // console.log(value.file);
                        // console.log(value.status);

                        $('#cus_name').html(value.cus_name);
                        // วุฒิเพิ่มมา
                        $('#text_name_first').html(value.name_first);
                        $('#text_name_last').html(value.name_last);
                        $('#text_name_business').html(value.business_name);
                        $('#text_ID_card_number').html(value.id_card);
                        $('#text_email').html(value.email);
                        console.log('upline '+data.upline_data.user_name);
                        if(data.upline_data!=0){

                                 $('#text_upline').html(data.upline_data.first_name+' '+data.upline_data.last_name+' ('+data.upline_data.user_name+')');
                                 $('#text_Referral_UserName').html(data.introduce_data.first_name+' '+data.introduce_data.last_name+' ('+data.introduce_data.user_name+')');
                        }

                        $('#text_no').html(value.card_house_no);
                        $('#text_building').html(value.card_house_name);
                        $('#text_villageno').html(value.card_moo);
                        $('#text_lane').html(value.card_soi);
                        $('#text_road').html(value.card_road);
                        $('#text_province').html(value.card_province_id_fk);
                        $('#text_province').html(value.pro_name);
                        $('#text_district_area').html(value.amp_name);
                        $('#text_sub-district_sub-area').html(value.dis_name);
                        $('#text_zipcode').html(value.card_zipcode);
                        $('#text_Mobile_Phone').html(value.tel);
                        $('#text_Home_Phone').html(value.tel_home);

                        $('#text_accountname').html(value.bank_account);
                        $('#text_accountno').html(value.bank_no);
                        $('#text_bankingaccount').html(value.bank_name);
                        $('#text_branch').html(value.bank_branch);
                        $('#text_ประเภทบัญชี').html(value.bank_type);

                        //

                        var strArray = value.file.split(".");
                        console.log(strArray);
                        // var arrXls = ['xls', 'xlsx'];
                        var arrXls = ['png','JPG','jpg', 'jpeg', 'gif'];

                        // console.log(arrXls.includes(strArray[1]));

                        let ch = arrXls.includes(strArray[1]);
                        console.log(ch);
                        if (ch == true) {
                            // $('#file_path').hide();
                            // $('#file_path_a').attr("href", value.file_path);
                            // $('#file_path_a').text("File : Excel");
                            // $('#file_path_a').css({"font-size": "24px"});

                            // console.log(value.type);
                            // console.log(value.status);
                            // console.log(value.comment);

                            $('#comment').val(value.comment);

                            $('#regis_status').val(value.status);
                            $('#regis_status').select2().trigger('change');
                            if (value.status == 0) {
                                $("#regis_status").select2('destroy').val("").select2();
                            }

                            // console.log(value.type);


                            $('.p_desc_1').css({
                                "background-color": "",
                                "color": ""
                            });
                            $('.column_1').css({
                                "border-style": "",
                                "border-width": "",
                                "border-color": "",
                                "background-color": "#aaa"
                            });
                            $('.p_desc_2').css({
                                "background-color": "",
                                "color": ""
                            });
                            $('.column_2').css({
                                "border-style": "",
                                "border-width": "",
                                "border-color": "",
                                "background-color": "#bbb"
                            });
                            $('.p_desc_3').css({
                                "background-color": "",
                                "color": ""
                            });
                            $('.column_3').css({
                                "border-style": "",
                                "border-width": "",
                                "border-color": "",
                                "background-color": "#bbb"
                            });
                            $('.p_desc_4').css({
                                "background-color": "",
                                "color": ""
                            });
                            $('.column_4').css({
                                "border-style": "",
                                "border-width": "",
                                "border-color": "",
                                "background-color": "#aaa"
                            });

                            if (value.type == "1") {
                                $('#file_path1').attr("src", value.file_path);
                                $('#file_path1').show();
                                $('.p_desc_1').css({
                                    "background-color": "bisque",
                                    "color": "blue"
                                });
                                $('.p_desc_11').html("เลขบัตรประชาชน : " + value.id_card);
                                $('.column_1').css({
                                    "border-style": "dotted",
                                    "border-width": "7px",
                                    "border-color": "coral",
                                    "background-color": "bisque"
                                });
                            }

                            if (value.type == "2") {
                                $('#file_path2').attr("src", value.file_path);
                                $('#file_path2').show();
                                $('.p_desc_2').css({
                                    "background-color": "bisque",
                                    "color": "blue"
                                });
                                $('.column_2').css({
                                    "border-style": "dotted",
                                    "border-width": "7px",
                                    "border-color": "coral",
                                    "background-color": "bisque"
                                });
                            }

                            if (value.type == "3") {
                                $('#file_path3').attr("src", value.file_path);
                                $('#file_path3').show();
                                $('.p_desc_3').css({
                                    "background-color": "bisque",
                                    "color": "blue"
                                });
                                // $('.p_desc_33').html("เลขบัตรประชาชน : "+value.id_card);
                                $('.column_3').css({
                                    "border-style": "dotted",
                                    "border-width": "7px",
                                    "border-color": "coral",
                                    "background-color": "bisque"
                                });
                            }

                            if (value.type == "4") {
                                console.log(value.file_path);
                                $('#file_path4').attr("src", value.file_path);
                                $('#file_path4').show();
                                $('.p_desc_4').css({
                                    "background-color": "bisque",
                                    "color": "blue"
                                });
                                $('.p_desc_44').html("บัญชีธนาคาร : " + value.bank_no + " " +
                                    value.bank_name);
                                $('.column_4').css({
                                    "border-style": "dotted",
                                    "border-width": "7px",
                                    "border-color": "coral",
                                    "background-color": "bisque"
                                });
                            }

                            // $('.file_path_desc').hide();
                            // var id = $(this).data('id');
                            // $('#id').val(id);

                            // กรณี type อื่นๆ ที่ผ่านก็แสดงเช่นเดียวกัน แต่ไม่มีกรอบ
                            // ต้องส่ง ajax ไปดึงมาแสดงต่างหาก เว้น type อันที่ระบุ
                            $.ajax({
                                url: " {{ url('backend/ajaxGetFilepath02') }} ",
                                method: "post",
                                data: {
                                    id: id,
                                    "_token": "{{ csrf_token() }}",
                                },
                                success: function(data2) {
                                    // console.log(data2);
                                    // @@@@@@@@@@@@@@@@@@@@@@@@@@@@
                                    $.each(data2, function(key, value) {


                                        var strArray = value.file.split(
                                            ".");

                                        var arrXls = ['png', 'jpg','JPG', 'jpeg',
                                            'gif'
                                        ];

                                        // console.log(arrXls.includes(strArray[1]));
                                        let ch = arrXls.includes(strArray[
                                            1]);

                                        if (ch == true && value.status ==
                                            "1") {

                                            console.log(value.type);

                                            if (value.type == "1") {

                                                $('#file_path1').attr("src",
                                                    value.file_path);
                                                $('#file_path1').show();
                                                $('.p_desc_11').html(
                                                    "เลขบัตรประชาชน : " +
                                                    value.id_card);
                                            }

                                            if (value.type == "2") {
                                                $('#file_path2').attr("src",
                                                    value.file_path);
                                                $('#file_path2').show();
                                            }

                                            if (value.type == "3") {
                                                $('#file_path3').attr("src",
                                                    value.file_path);
                                                $('#file_path3').show();
                                                // $('.p_desc_33').html("เลขบัตรประชาชน : "+value.id_card);
                                            }

                                            if (value.type == "4") {
                                                $('#file_path4').attr("src",
                                                    value.file_path);
                                                $('#file_path4').show();
                                                $('.p_desc_44').html(
                                                    "บัญชีธนาคาร : " +
                                                    value.bank_no +
                                                    " " + value
                                                    .bank_name);
                                            }

                                        }

                                    });
                                    // @@@@@@@@@@@@@@@@@@@@@@@@@@@@
                                }
                            });


                        } else {

                            $('#file_path1').hide();
                            $('#file_path2').hide();
                            $('#file_path3').hide();
                            $('#file_path4').hide();
                            // $('#file_path').attr("src", value.file_path);
                            // $('.file_path_desc').html("File not found");
                            // $('.file_path_desc').css({"background-color": "#ccffe6", "font-size": "24px"});
                            // $('.file_path_desc').show();
                        }

                        $('#type').val(value.type);

                    });

                    // console.log(v);
                    // if(v==null){
                    //     console.log("aaaaa");
                    //     $('#file_path').hide();
                    //     $('.file_path_desc').html("File not found");
                    //     $('.file_path_desc').css({"background-color": "#ccffe6", "font-size": "24px"});
                    // }else{
                    //     console.log("bbbbb");
                    //     $('#file_path').show();
                    // }
                    $('#checkRegis').modal('show');
                }
            })

        });


        // $('#checkRegis').on('hidden.bs.modal', function () {
        //    location.reload();
        // });
    </script>
@endsection

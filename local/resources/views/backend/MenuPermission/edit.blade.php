@extends('backend.layouts.master')
@section("content")
    <div class="pcoded-inner-content">
        <div class="main-body">
            <div class="page-wrapper">
                <div class="page-header card">
                    <div class="card-block">
                        <h5 class="m-b-10">MenuPermission</h5>
                    </div>
                </div>

                <div class="page-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4>List Menu</h4>
                                    <div class="card-header-right"><i class="icofont icofont-spinner-alt-5"></i></div>
                                </div>
                                <div class="card-block">
                                    <section class="task-panel tasks-widget">
                                        <div class="panel-body">
                                       <form action="{{url('backend/menu_permission',$id)}}" method="post">
                                                @csrf
                                                {{ method_field('PATCH') }}
                                                <input type="hidden" name="id_user" value="{{$id}}">
                                                <div class="task-content">
                                                    @foreach($data AS $row)
                                                        @php
                                                         $menu_admin = DB::table('menu_admin')->where('admin_id',$id)->where('main_menu_id',$row->id)->first();

                                                        @endphp
                                                        <div class="to-do-label">
                                                            <div class="checkbox-fade fade-in-primary">
                                                                <label class="check-task"
                                                                       onclick="checkedMenu({{$row->id}})">
                                                                    <input type="hidden" name="id_menuAd[]" value="{{@$menu_admin->id_menu_admin}}">
                                                                    <input type="checkbox" {!! (@$menu_admin->main_menu_id == $row->id ? 'checked': '') !!}
                                                                           class="classMenu{{$row->id}}"
                                                                           name="nameMenu[]" value="{{$row->id}}">
                                                                    <span class="cr"><i
                                                                            class="cr-icon icofont icofont-ui-check txt-primary"></i></span>
                                                                    <span
                                                                        class="task-title-sp">
	                                                                <b>{{$row->sort_menu}} &nbsp;&nbsp;&nbsp; </b>
	                                                                @IF($row->ref==0)
	                                                                 	<span style="font-size: 16px;font-weight: bold;color: blue;"><i class="{{$row->icon}}"></i>&nbsp;&nbsp;&nbsp;{{$row->name}}</span>
	                                                                @ELSE
	                                                                	&nbsp;&nbsp;&nbsp; <i class="{{$row->icon}}"></i>{{$row->name}}
	                                                                @ENDIF
	                                                                </span>

                                                                    <span class="f-right hidden-phone">
                                                            <i class="icofont icofont-circled-down"></i>
                                                        </span>
                                                                </label>
                                                               
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                                <div><center>
                                                    <button type="submit"
                                                            class="btn btn-primary btn-add-task waves-effect waves-light m-t-10">
                                                        <!-- <i class="icofont icofont-plus"></i> Add -->
                                                        <i class="fa fa-save"></i> Save 
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </section>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endsection

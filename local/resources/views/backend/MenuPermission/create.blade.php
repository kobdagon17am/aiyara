@extends("layout_backoffice.components")
@section("content")
<div class="pcoded-inner-content">
        <div class="main-body">
            <div class="page-wrapper">
                <div class="page-header card">
                    <div class="card-block">
                        <a class="btn btn-out-dashed btn-info btn-square "
                           href="{{url('backoffice/menu_permission')}}" style="float:right;color:white">
                            <i class="ion-arrow-left-a"></i>Back</a>
                        <h5 class="m-b-10">MenuPermission</h5>
                        <p class="text-muted m-b-10">MenuPermission management</p>
                        <ul class="breadcrumb-title b-t-default p-t-10">
                            <li class="breadcrumb-item">
                                <a href="index.html"> <i class="fa fa-home"></i> </a>
                            </li>
                            <li class="breadcrumb-item"><a href="#!">MenuPermission</a>
                            </li>
                            <li class="breadcrumb-item"><a href="#!">Table MenuPermission</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="page-body">
                    <form action="">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5>Basic Form Inputs</h5>
                                        <span>Add class of <code>.form-control</code> with <code>&lt;input&gt;</code> tag</span>
                                    </div>
                                    <div class="col-sm-12 borderStrike" ></div>
                                    <div class="card-block">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group row">
                                                    <div class="col-sm-12">
                                                        <label for="userName-2" class="block">Test *</label>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <input id="userName-2c" name="userName" type="text"
                                                               class=" form-control">
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group col-sm-12">
                                        <center>
                                            <button type="submit" class="btn btn-warning btn-outline-warning"><i
                                                    class="icofont icofont icofont-eraser-alt"></i>Reset
                                            </button>
                                            <button type="submit" class="btn btn-success btn-outline-success"><i
                                                    class="icofont icofont-save"></i>Save
                                            </button>
                                        </center>
                                    </div>


                                </div>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection
@section("script")
<script>
</script>

@endsection

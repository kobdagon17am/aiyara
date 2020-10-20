@extends('backend.template.layouts.master')

@section('title') Form Editors @endsection

@section('css') 
        <!-- Summernote css -->
        <link rel="stylesheet" type="text/css" href="{{ URL::asset('backend/libs/summernote/summernote.min.css')}}">
@endsection

@section('content')

                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-flex align-items-center justify-content-between">
                                    <h4 class="mb-0 font-size-18">Form Editors</h4>

                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Forms</a></li>
                                            <li class="breadcrumb-item active">Form Editors</li>
                                        </ol>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>     
                        <!-- end page title -->

                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
        
                                        <h4 class="card-title">Tinymce wysihtml5</h4>
                                        <p class="card-title-desc">Bootstrap-wysihtml5 is a javascript
                                            plugin that makes it easy to create simple, beautiful wysiwyg editors
                                            with the help of wysihtml5 and Twitter Bootstrap.</p>
        
                                        <form method="post">
                                            <textarea id="elm1" name="area"></textarea>
                                        </form>
        
                                    </div>
                                </div>
                            </div> <!-- end col -->
                        </div> <!-- end row -->
        
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title">Summernote</h4>
                                        <p class="card-title-desc">Super simple wysiwyg editor on bootstrap</p>
        
                                        <div class="summernote">Hello Summernote</div>
                                    </div>
                                </div>
                            </div> <!-- end col -->
                        </div> <!-- end row -->

@endsection

@section('script')

        <!--tinymce js-->
        <script src="{{ URL::asset('backend/libs/tinymce/tinymce.min.js')}}"></script>

        <!-- Summernote js -->
        <script src="{{ URL::asset('backend/libs/summernote/summernote.min.js')}}"></script>

        <script src="{{ URL::asset('backend/js/pages/form-editor.init.js')}}"></script> 

@endsection
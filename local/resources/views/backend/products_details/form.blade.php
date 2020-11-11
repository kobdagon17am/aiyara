@extends('backend.layouts.master')

@section('title') Aiyara Planet @endsection

@section('css')

@endsection

@section('content')

<!-- start page title -->
<div class="row">
    <div class="col-10">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0 font-size-18"> Products details </h4>

            <a class="btn btn-secondary btn-sm waves-effect" href="{{ route('backend.products.index') }}/{{@$sRowNew->id}}/edit" }}">
                <i class="bx bx-arrow-back font-size-16 align-middle mr-1"></i> ย้อนกลับ
              </a>

        </div>

    </div>
</div>
<!-- end page title -->

<div class="row">
    <div class="col-10">
        <div class="card">
            <div class="card-body">

              

              @if( empty($sRow) )
              <form action="{{ route('backend.products_details.store') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                <input type="hidden" name="product_id_fk" value="{{@$sRowNew->id}}" >
              @else
              <form action="{{ route('backend.products_details.update', @$sRow[0]->id ) }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                <input name="_method" type="hidden" value="PUT">
                <input type="hidden" name="product_id_fk" value="{{@$sRowNew->id}}" >
                
              @endif
                {{ csrf_field() }}

                @for ($i = 0; $i < count($sLanguage) ; $i++)

                    <div class="myBorder">

                        @if( !empty(@$sRow) )
                        <input class="form-control" type="hidden" value="{{ @$sRow[$i]->id }}" name="id[]"  >
                        @endif

                        <div class="form-group row">
                          <label for="example-text-input" class="col-md-2 col-form-label">ภาษา :</label>
                          <div class="col-md-10">
                            <input class="form-control" type="text" value="{{ $sLanguage[$i]->txt_desc }}"  readonly="" style="border: 0px;font-weight: bold;color: blue;">
                            <input class="form-control" type="hidden" value="{{ $sLanguage[$i]->id }}" name="lang[]"  readonly="" style="border: 0px;font-weight: bold;">
                          </div>
                        </div>

                        <div class="form-group row">
                          <label for="example-text-input" class="col-md-2 col-form-label">Product name :</label>
                          <div class="col-md-10">
                            <input class="form-control" type="text" value="{{ @$sRow[$i]->product_name }}" name="product_name[]" required >
                          </div>
                        </div>

                        <div class="form-group row">
                          <label for="example-text-input" class="col-md-2 col-form-label">Descriptions :</label>
                          <div class="col-md-10">
                            <textarea name="descriptions[]" class="form-control descriptions  "  >{{@$sRow[$i]->descriptions}}</textarea>
                          </div>
                        </div>

                        <div class="form-group row">
                          <label for="example-text-input" class="col-md-2 col-form-label">Products details :</label>
                          <div class="col-md-10">
                            <textarea name="products_details[]" class="form-control products_details "  >{{@$sRow[$i]->products_details}}</textarea>
                          </div>
                        </div>

                    </div>

                 @endfor
               

                <div class="form-group mb-0 row">
                    <div class="col-md-6">
                        <a class="btn btn-secondary btn-sm waves-effect" href="{{ route('backend.products.index') }}/{{@$sRowNew->id}}/edit" }}">
                          <i class="bx bx-arrow-back font-size-16 align-middle mr-1"></i> ย้อนกลับ
                        </a>
                    </div>
                    <div class="col-md-6 text-right">
                        <button type="submit" class="btn btn-primary btn-sm waves-effect">
                          <i class="bx bx-save font-size-16 align-middle mr-1"></i> บันทึกข้อมูล
                        </button>
                    </div>
                </div>

              </form>
            </div>
        </div>
    </div> <!-- end col -->
</div>
<!-- end row -->
@section('script')

        <script type="text/javascript">


            $( document ).ready( function() {

                  $( '.descriptions' ).each( function() {
                      CKEDITOR.replace( this );
                  } );

                  $( '.products_details' ).each( function() {
                      CKEDITOR.replace( this );
                  } );


              } );


        </script>

@endsection

@endsection

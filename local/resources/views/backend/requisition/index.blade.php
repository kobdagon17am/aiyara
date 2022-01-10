@extends('backend.layouts.master')

@section('title') Aiyara Planet @endsection

@section('css')
<style>
    .select2-selection {height: 34px !important;margin-left: 3px;}
</style>
@endsection

@section('content')
<div class="myloading"></div>
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0 font-size-18">ขอเบิกระหว่างสาขา</h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card card-body">

            <form action="{{ route('backend.requisition_between_branch.store') }}" method="POST" class="repeater">
                {{ csrf_field() }}
                <div class="myBorder">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label for="from_branch_id" class="col-md-3 col-form-label"> สาขาผู้ดำเนินการ : </label>
                                <div class="col-md-9">
                                    <select id="from_branch_id" name="from_branch_id" class="form-control select2-templating">
                                        <option value="">== เลือกสาขาที่ดำเนินการ ===</option>
                                        @foreach ($fromBranchs as $branch)
                                            <option value="{{ $branch->id }}" @if($branch->id == auth()->user()->branch_id_fk) selected @endif>{{ $branch->b_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label for="to_branch_id" class="col-md-3 col-form-label"> สาขาที่ต้องการยื่นคำขอ : </label>
                                <div class="col-md-9">
                                    <select id="to_branch_id" name="to_branch_id" class="form-control select2-templating" required>
                                        <option value="">== เลือกสาขาที่ต้องการยื่นคำขอ ===</option>
                                        @foreach ($toBranchs as $branch)
                                            <option value="{{ $branch->id }}">{{ $branch->b_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="myBorder">
                    <h4 class="mb-0 font-size-18"><i class="bx bx-play"></i> รายการที่ต้องการขอเบิก </h4>

                    <div class="row justify-content-center my-3">
                        <div class="col-md-12">
                            <table id="table-details" class="table table-bordered table-sm">
                                <thead>
                                    <tr>
                                        <th>สินค้า</th>
                                        <th>จำนวน</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <input type="hidden" name="details[1][product_name]">
                                            <select name="details[1][product_id]" class="form-control select" required>
                                                <option value="">- เลือกสินค้า -</option>
                                                @foreach(@$products AS $product)
                                                    <option value="{{ $product->product_id }}">{{ @$product->product_code." : ".@$product->product_name }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input type="number" name="details[1][amount]" class="form-control" placeholder="จำนวน" required />
                                        </td>
                                        <td>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="text-center">
                        <button id="add-list" type="button" class="btn btn-primary mt-3 mt-lg-0">เพิ่มรายการสินค้า</button>
                    </div>
                </div>

                <div class="my-2 text-right">
                    <input type="submit" value="ส่งรายการคำขอ" class="btn btn-success">
                </div>
            </form>

        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card card-body">
            <div class="myBorder">
                <h4 class="mb-0 font-size-18"><i class="bx bxs-file"></i> รายการอนุมัติสำเร็จ (สาขาผู้ดำเนินการ : {{ $currentUserBranch }})</h4>
                <table id="tableListApprove" class="table table-sm table-bordered my-3">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>สาขาที่อนุมัติคำขอ</th>
                            <th>สินค้า</th>
                            <th>วันที่ยื่นคำขอ</th>
                            <th>วันที่อนุมัติ</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card card-body">
            <div class="myBorder">
                <h4 class="mb-0 font-size-18"><i class="bx bxs-file-find"></i> รายการรอการอนุมัติ </h4>
                <table id="tableListWaitApprove" class="table table-sm table-bordered my-3">
                    <thead>
                        <tr>
                            <th>#</th>
                            <!-- <th>สาขาที่ยื่นคำขอ</th> -->
                            <th>สาขาผู้ดำเนินการ => สาขาที่รับคำขอ</th>
                            <th>สินค้า</th>
                            <th>ผู้ยื่นคำขอ</th>
                            <th>วันที่ยื่นคำขอ</th>
                            <th>อนุมัติ/ไม่อนุมัติ</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalProducts" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable">
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title mt-0" id="exampleModalScrollableTitle">รายการสินค้า</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
              </button>
          </div>
          <div class="modal-body">
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          </div>
      </div>
  </div>
</div>
@endsection

@section('script')
<script>

    if ("{{ session('success') }}") {
        toastr["success"]("{{ session('success') }}")
    }

    if ("{{ session('error') }}") {
        toastr["error"]("{{ session('error') }}")
    }

    $(document).ready(function () {
        let idx = 2;
        $('#add-list').on('click', function () {

            var $tableBody = $('#table-details').find("tbody"),
            $trLast = $tableBody.find("tr:last")

            $trLast.after(`
                <tr>
                    <td>
                        <input type="hidden" name="details[${idx}][product_name]">
                        <select name="details[${idx}][product_id]" class="form-control select" required>
                            <option value="">- เลือกสินค้า -</option>
                            @foreach(@$products AS $product)
                                <option value="{{ @$product->product_id }}">{{ @$product->product_code." : ".@$product->product_name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <input type="number" name="details[${idx}][amount]" class="form-control" placeholder="จำนวน" required/>
                    </td>
                    <td>
                        <button type="button" class="btn btn-danger btn-sm remove-row">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `);

            idx++;
        })

        $(document).on('change', '.select', function () {
            $(this).prev().val($(this).find(':selected').text())
        })

        $(document).on('click', '.remove-row', function () {
            $(this).parent().parent().remove()
            idx--;
        })

        $(document).on('submit', '.form-approve', function (e) {
            e.preventDefault();
            const is_approve = $(this).find('[name="is_approve"]').val()
            let title;

            title = is_approve == 1 ? 'คุณต้องการอนุมัติรายการนี้ใช่หรือไม่' : 'คุณต้องการยกเลิกรายการนี้ใช่หรือไม่'

            Swal.fire({
                type: 'warning',
                title: title,
                showCancelButton: true,
                confirmButtonColor: '#556ee6',
                cancelButtonColor: "#f46a6a"
            }).then(function (result) {
                if (result.value) {
                    e.target.submit();
                }
            });
        })

      const dtListApprove = $('#tableListApprove').DataTable({
        sDom: "<'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>",
        processing: true,
        serverSide: true,
        ajax: {
          url: "{{ route('backend.requisition_between_branch.dt-list-approve') }}",
          method: "POST",
          data: { _token: "{{ csrf_token() }}" }
        },
        columns: [
          { data: "id", name: "id" },
          { data: "to_branch_id", name: "to_branch_id" },
          { data: "button_products", name: "button_products", sortable: false, orderable: false },
          { data: "created_at", name: "created_at" },
          { data: "updated_at", name: "updated_at" },
        ]
      })

      const dtListWaitApptove = $('#tableListWaitApprove').DataTable({
        sDom: "<'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>",
        processing: true,
        serverSide: true,
        ajax: {
          url: "{{ route('backend.requisition_between_branch.dt-list-wait-approve') }}",
          method: "POST",
          data: { _token: "{{ csrf_token() }}" }
        },
        columns: [
          { data: "id", name: "id" },
          { data: "from_branch_id", name: "from_branch_id" },
          { data: "button_products", name: "button_products", sortable: false, orderable: false },
          { data: "requisition_by", name: "requisition_by" },
          { data: "created_at", name: "created_at" },
          { data: "actions", name: "actions", sortable: false, orderable: false },
        ]
      })

      $('#modalProducts').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget)
        var products = button.data('products')
        var modal = $(this)
        let output = ''

        products.forEach(product => {
          output += `<div>
                        ${product.product_name}
                        ( จำนวน : ${product.amount} )
                    </div>`
        })

        modal.find('.modal-body').html(output)
      })
  })
</script>
@endsection

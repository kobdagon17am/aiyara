<div class="row">
    <div class="col-12">
        <div class="card card-body">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0 font-size-18"><i class="bx bx-play"></i> รายการใบเบิกระหว่างสาขา </h4>
            </div>
            <table id="dt-requisition" class="table table-bordered " style="width: 100%;">
            </table>
        </div>
    </div>
</div>

<!-- Model Requisition Details -->
<div class="modal fade modal-requisition-details" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0" id="myLargeModalLabel">รายการสินค้า</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('backend.transfer_branch.store-from-requisition') }}" method="POST">
                    {{ csrf_field() }}
                    <table class='table table-bordered table-sm' id="table-details">
                        <thead>
                            <tr>
                                <td>สินค้า</td>
                                <td>จำนวน</td>
                                <td width="60%">คลังสินค้า</td>
                                <td></td>
                            </tr>
                        </thead>
                        <tbody id="tbodyDetails"></tbody>
                    </table>

                    <div class="form-group">
                        <label for="remark">หมายเหตุ</label>
                        <textarea name="note" class="form-control" rows="3"></textarea>
                    </div>



                    <div class="text-center mt-3">
                        <button class="btn btn-primary" id="requisitionSubmitBtn">
                            <i class="bx bx-save font-size-16 align-middle mr-1"></i> สร้างใบโอน
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

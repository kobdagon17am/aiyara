@extends('frontend.layouts.customer.customer_app')
@section('css')

@endsection
@section('conten')

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="m-b-10">Test Ksher </h4>
                </div>
                <div class="card-block">

                  <form action="{{ route('gateway_ksher') }}" method="POST">
                    @csrf
                    <div class="group">
                      <label>mch_order_no</label>
                      <div><input type="text" name="mch_order_no" value="<?php echo date("YmdHis",time()).rand(100000,999999);?>"/></div>
                  </div>
                  <div class="group">
                      <label>total_fee</label>
                      <div><input type="text" name="total_fee" value="0.1" /></div>
                  </div>
                  <div class="group">
                      <label>fee_type</label>
                      <div>
                          <select name="fee_type">
                              <option value="THB">THB</option>
                          </select>
                      </div>
                  </div>
                  <div class="group">
                      <label>&nbsp;</label>
                      <input type='hidden' name='action' value='promptpay'/>
                      <div><input type="submit" value="submit"/> </div>
                  </div>

                  </form>





                </div>
            </div>
        </div>
    </div>

@endsection
@section('js')
@endsection

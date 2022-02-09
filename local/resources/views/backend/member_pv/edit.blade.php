@extends('backend.layouts.master')

@section('title', 'แก้ไขข้อมูลสมาชิก | ' . @$customer->user_name)

@section('content')
<div class="myloading"></div>

<!-- start page title -->
<div class="row">
  <div class="col-12">
    <div class="page-title-box d-flex align-items-center justify-content-between">
      <h4 class="mb-0 font-size-18">แก้ไขข้อมูลสมาชิก : {{ @$customer->user_name }}</h4>
    </div>
  </div>
</div>
<!-- end page title -->

{{-- ข้อมูลทั่วไป --}}
@include('backend.member_pv.edit_partials.general_info')

{{-- ที่อยู่ตามบัตรประชาชน--}}
@include('backend.member_pv.edit_partials.address_card')

{{-- ที่อยู่จัดส่ง--}}
@include('backend.member_pv.edit_partials.address_send')

{{-- ข้อมูลธนาคาร --}}
@include('backend.member_pv.edit_partials.bank_account')

@endsection

@section('script')
<script>


  $(document).ready(function () {

    const addressCard = "{{ @$addressCard->card_province_id_fk ?? '' }}"
    const addressSend = "{{ @$customer->province_id_fk ?? '' }}"

    if (addressCard) {
      $('[data-card-province]').trigger('change')
    }

    if (addressSend) {
      $('[data-send-province]').trigger('change')
    }

  })

  $('.select2-province').on('change', getDistrict)
  $('.select2-district').on('change', getSubDistrict)
  $('.select2-sub-district').on('change', getZipCode)

  // Get Amphure
  function getDistrict() {
    const prefix = $(this).attr('data-prefix')
    let amphureId;

    if (prefix == 'card') {
      amphureId = "{{ @$addressCard->card_amphures_id_fk }}"
    }

    if (prefix == 'send') {
      amphureId = "{{ @$customer->amphures_id_fk }}"
    }

    $.ajax({
      url: "{{ url('backend/ajaxGetAmphur') }}",
      method: "POST",
      data: { _token: "{{ csrf_token() }}", province_id: $(this).val() },
      success: function (response) {
        if (response) {

          let options = '<option value="">- เลือกอำเภอ -</option>'
          response.forEach(res => {
            options += `<option value="${res.id}" ${amphureId == res.id ? 'selected' : ''}>${res.amphur_name}</option>`
          })
          $(`[data-${prefix}-district]`).html(options).change()
        }
      }
    })
  }

  // Get Tambon
  function getSubDistrict() {
    const prefix = $(this).attr('data-prefix')

    $.ajax({
      url: "{{ url('backend/ajaxGetTambon') }}",
      method: "POST",
      data: { _token: "{{ csrf_token() }}", amphur_id: $(this).val() },
      success: function (response) {
        if (response) {
          if (prefix == 'card') {
            tambonId = "{{ @$addressCard->card_district_id_fk }}"
          }

          if (prefix == 'send') {
            tambonId = "{{ @$customer->district_id_fk }}"
          }

          let options = '<option value="">- เลือกตำบล -</option>'
          response.forEach(res => {
            options += `<option value="${res.id}" ${tambonId == res.id ? 'selected' : ''}>${res.tambon_name}</option>`
          })

          $(`[data-${prefix}-sub-district]`).html(options).change()
        }
      }
    })
  }

  // Get ZipCode
  function getZipCode(districtId) {
    const prefix = $(this).attr('data-prefix')

    $.ajax({
      url: "{{ url('backend/ajaxGetZipcode') }}",
      method: "POST",
      data: { _token: "{{ csrf_token() }}", tambon_id: $(this).val() },
      success: function (response) {
        if (response) {
          const zipCode = response.map(res => res.zip_code).join('').trim()
          $(`[data-${prefix}-zip-code]`).val(zipCode)
        }
      }
    })
  }
</script>
@endsection

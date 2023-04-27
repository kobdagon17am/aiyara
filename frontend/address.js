const THAI = 1;
const LAOS = 2;
const CAMBODIA = 3;
const MYANMAR = 4;

const card_ids = {
    card_province: 'card_province',
    card_amphures: 'card_amphures',
    card_district: 'card_district',
}

const ids = {
    province: 'province',
    amphures: 'amphures',
    district: 'district',
}

const card_province = '#card_province';
const card_amphures = '#card_amphures';
const card_district = '#card_district';
const card_zipcode = '#card_zipcode';

const province = '#province';
const amphures = '#amphures';
const district = '#district';
const zipcode = '#zipcode';

const card_house_no = $('#card_house_no');
const card_house_name = $('#card_house_name');
const card_moo = $('#card_moo');
const card_soi = $('#card_soi');
const card_road = $('#card_road');

const house_no = $('#house_no');
const house_name = $('#house_name');
const moo = $('#moo');
const soi = $('#soi');
const road = $('#road');

$('select[name="business_location"]').on('change', getProvinces)
$(`${card_province}, ${province}`).on('change', getAmphures)
$(`${card_amphures}, ${amphures}`).on('change', getDistricts)
$(`${card_district}, ${district}`).on('change', getZipcode)
$('#copy_card_address').on('change', copyCardAddress)

checkIdIsMatch();
getProvinces();

function getProvinces() {
    // const businessLocation = $(this).val(); 
    const businessLocation = $('#business_location').val();
    // alert(businessLocation);
    if (businessLocation == 'CAMBODIA') {
        var location = 3;
        $('#bank_name_th').hide();
        $('#bank_name_cam').show();
    } else {
        var location = 1;
        $('#bank_name_cam').hide();
        $('#bank_name_th').show();
    }

    $.ajax({
        url: routeGetLocation,
        method: 'POST',
        data: {
            _token: token,
            business_location: location,
            query: 'provinces',
        },
        success: function(response) {
            $(`${card_province}, ${province}`).html(response);
            clearValue()
        },
        error: function(error) {
            console.log(error);
        }
    })
}

function getAmphures() {
    const inputId = $(this).attr('id');
    const provinceId = $(this).val();

    $.ajax({
        url: routeGetLocation,
        method: 'POST',
        data: {
            _token: token,
            business_location: $('select[name="business_location"]').val(),
            query: 'amphures',
            id: provinceId
        },
        success: function(response) {
            if (card_ids.hasOwnProperty(inputId)) {
                $(`${card_amphures}`).html(response);
            } else {
                $(`${amphures}`).html(response);

                if ($('#copy_card_address').is(':checked')) {
                    $(`${amphures}`).val($(`${card_amphures}`).val()).trigger('change')
                }
            }
        },
        error: function(error) {
            console.log(error);
        }
    })
}

function getDistricts() {
    const inputId = $(this).attr('id');
    const amphureId = $(this).val();

    $.ajax({
        url: routeGetLocation,
        method: 'POST',
        data: {
            _token: token,
            business_location: $('select[name="business_location"]').val(),
            query: 'districts',
            id: amphureId
        },
        success: function(response) {
            if (card_ids.hasOwnProperty(inputId)) {
                $(`${card_district}`).html(response);
            } else {
                $(`${district}`).html(response);

                if ($('#copy_card_address').is(':checked')) {
                    $(`${district}`).val($(`${card_district}`).val()).trigger('change')
                }
            }
        },
        error: function(error) {
            console.log(error);
        }
    })
}

function getZipcode() {

    if ($('select[name="business_location"]').val() == CAMBODIA) {
        return;
    }

    const inputId = $(this).attr('id');
    const districtId = $(this).val();

    $.ajax({
        url: routeGetLocation,
        method: 'POST',
        data: {
            _token: token,
            business_location: $('select[name="business_location"]').val(),
            query: 'zip_code',
            id: districtId
        },
        success: function(response) {
            if (card_ids.hasOwnProperty(inputId)) {
                $(`${card_zipcode}`).val(response);
            } else {
                $(`${zipcode}`).val(response);
            }
        },
        error: function(error) {
            console.log(error);
        }
    })
}

function copyCardAddress() {
    const isChecked = $(this).is(':checked')
    if (isChecked) {
        house_no.val(card_house_no.val())
        house_name.val(card_house_name.val())
        moo.val(card_moo.val())
        soi.val(card_soi.val())
        road.val(card_road.val())

        $(`${province}`).val($(`${card_province}`).val()).trigger('change')
        $(`${zipcode}`).val($(`${card_zipcode}`).val())
    } else {
        house_no.val('')
        house_name.val('')
        moo.val('')
        soi.val('')
        road.val('')
        $(`${zipcode}`).val('')
        $(`${province}, ${amphures}`).val('').trigger('change')
    }
}

function clearValue() {
    $('#copy_card_address').prop('checked', false)
    $(`${card_amphures}, ${amphures}`).val('').trigger('change')
    $(`${card_district}, ${district}`).val('').trigger('change')
}

function checkIdIsMatch() {

    $('.js-example-basic-single').each(function(idx, el) {
        const attr = $(el).attr('id')
        if (typeof attr !== 'undefined') {
            if (!card_ids.hasOwnProperty(attr) && !ids.hasOwnProperty(attr)) {
                // console.error('#' + attr + ' not match.');
            }
        }
    })
}
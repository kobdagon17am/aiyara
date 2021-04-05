function next() {
    var check_sent_other = document.getElementById("sent_other").checked;
    var check_office_check = document.getElementById("sent_office_check").checked;
    var sent_type_other = document.getElementById("sent_type_other").checked;//จัดส่งให้คนอื่น
    
    if(sent_type_other){
        sent_to_customer_id_fk = document.getElementById("sent_to_customer_id_fk").value;
        if(sent_to_customer_id_fk == ''){
            Swal.fire({
                icon: 'error',
                title: 'เลือกผู้รับไม่ถูกต้อง',
            })
            return false;
        }
    }

    if (check_sent_other == true) {
        var other_name = $('#other_name').val();
        var other_tel_mobile = $('#other_tel_mobile').val();
        var other_house_no = $('#other_house_no').val();
        var other_house_name = $('#other_house_name').val();
        var other_moo = $('#other_moo').val();
        var other_province = $('#other_province').val();
        var other_district = $('#other_district').val();
        var other_district_sub = $('#other_district_sub').val();
        var other_zipcode = $('#other_zipcode').val();
        if (other_name == '') {
            Swal.fire({
                icon: 'error',
                title: 'Name is Null',
            })

        } else if (other_tel_mobile == '') {
            Swal.fire({
                icon: 'error',
                title: 'Mobile is Null',
            })

        } else if (other_house_no == '') {
            Swal.fire({
                icon: 'error',
                title: 'House No. is Null',
            })

        } else if (other_house_name == '') {
            Swal.fire({
                icon: 'error',
                title: 'กรุณาใส่ข้อมูล หมู่บ้าน/อาคาร',
            })

        } else if (other_moo == '') {

            Swal.fire({
                icon: 'error',
                title: 'กรุณาใส่ข้อมูล หมู่ที่',
            })

        } else if (province == '') {
            Swal.fire({
                icon: 'error',
                title: 'กรุณาใส่ข้อมูล จังหวัด',
            })

        } else if (district == '') {
            Swal.fire({
                icon: 'error',
                title: 'กรุณาใส่ข้อมูล เขต/อำเภอ *',
            })

        } else if (district_sub == '') {
            Swal.fire({
                icon: 'error',
                title: 'กรุณาใส่ข้อมูล แขวง/ตำบล',
            })

        } else if (other_zipcode == '') {
            Swal.fire({
                icon: 'error',
                title: 'กรุณาใส่ข้อมูล รหัสไปษณีย์',
            })

        } else {
            document.getElementById("address").classList.remove('active');
            document.getElementById("nav_address").classList.remove('active');
            document.getElementById("credit-card").classList.add('active');
            document.getElementById("nav_card").classList.add('active');
        }

    }else if(check_office_check){
        var office_name = $('#office_name').val();
        var office_tel_mobile = $('#office_tel_mobile').val();
        if (office_name == '') {
            Swal.fire({
                icon: 'error',
                title: 'Name is Null',
            })
        } else if (office_tel_mobile == '') {
            Swal.fire({
                icon: 'error',
                title: 'Mobile is Null',
            }) 

        }else{
        document.getElementById("address").classList.remove('active');
        document.getElementById("nav_address").classList.remove('active');
        document.getElementById("credit-card").classList.add('active');
        document.getElementById("nav_card").classList.add('active');

        }

    }else {
        document.getElementById("address").classList.remove('active');
        document.getElementById("nav_address").classList.remove('active');
        document.getElementById("credit-card").classList.add('active');
        document.getElementById("nav_card").classList.add('active');
    }
}

$('#upload').change(function() {
    var fileExtension = ['jpg', 'png'];
    if ($.inArray($(this).val().split('.').pop().toLowerCase(), fileExtension) == -1) {
        alert("This is not an allowed file type. Only JPG and PNG files are allowed.");
        this.value = '';
        return false;
    } else {
        document.getElementById("submit_upload").disabled = false;
    }
});


function data_direct_confirm(sent_to_customer_username){
    var url =   document.getElementById("url_check_user").value;
    
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        url: url,
        type: 'POST',
        data: {'user_name': sent_to_customer_username}
    })
    .done(function(data) {
        console.log(data['data']);
        if (data['status'] == 'success') {
 
            document.getElementById("c_text_username").innerHTML = data['data']['data']['business_name'] +
                ' (' + data['data']['data']['user_name'] + ')';
            document.getElementById("c_name").innerHTML = data['data']['data']['prefix_name'] + ' ' + data[
                'data']['data']['first_name'] + ' ' + data['data']['data']['last_name'];
            document.getElementById("c_text_pv").innerHTML = data['data']['data']['pv'] + ' PV';
            $("#input_username").val(data['data']['data']['user_name']);

            document.getElementById("c_pv_tv_active").innerHTML = data['pv_tv_active'];
            document.getElementById("c_pv_mt_active").innerHTML = data['pv_mt_active'];
 
            document.getElementById("c_qualification_name").innerHTML = data['data']['data']['qualification_name'];
            
            var sent_to_customer_id =  data['data']['data']['id'];
            document.getElementById("sent_to_customer_id_fk").value = sent_to_customer_id;

            $('#large-Modal').modal('hide')
            document.getElementById("data_direct").style.display = "block";

           

        }
    })
    

}
function next() {
    var check_sent_other = document.getElementById("sent_other").checked;
    var check_office_check = document.getElementById("sent_office_check").checked;
    

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
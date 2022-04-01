function numberWithCommas(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

function data_direct_confirm(sent_to_customer_username) {
    var url = document.getElementById("url_check_user").value;

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
            url: url,
            type: 'POST',
            data: { 'user_name': sent_to_customer_username }
        })
        .done(function(data) {
            console.log(data['data']);
            if (data['status'] == 'success') {

                document.getElementById("c_text_username").innerHTML = data['data']['data']['business_name'] +
                    ' (' + data['data']['data']['user_name'] + ')';
                document.getElementById("c_name").innerHTML = data['data']['data']['first_name'] + ' ' + data['data']['data']['last_name'];
                document.getElementById("c_text_pv").innerHTML = data['data']['data']['pv'] + ' PV';
                $("#input_username").val(data['data']['data']['user_name']);

                document.getElementById("c_pv_tv_active").innerHTML = data['pv_tv_active'];
                document.getElementById("c_pv_mt_active").innerHTML = data['pv_mt_active'];

                document.getElementById("c_qualification_name").innerHTML = data['data']['data']['qualification_name'];

                var sent_to_customer_id = data['data']['data']['id'];
                document.getElementById("address_sent_id_fk").value = sent_to_customer_id;

                $('#large-Modal').modal('hide')
                document.getElementById("data_direct").style.display = "block";

            }
        })


}

function ai_confirm(ai_customer_username) {
    var url = document.getElementById("url_check_user").value;

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
            url: url,
            type: 'POST',
            data: { 'user_name': ai_customer_username }
        })
        .done(function(data) {
            console.log(data['data']);
            if (data['status'] == 'success') {
                document.getElementById("ai_text_username").innerHTML = data['data']['data']['business_name'] +
                    ' (' + data['data']['data']['user_name'] + ')';
                document.getElementById("ai_tex_name").innerHTML = data['data']['data']['first_name'] + ' ' + data['data']['data']['last_name'];

                // document.getElementById("ai_text_pv").innerHTML = data['data']['data']['pv'] + ' PV';
                // $("#input_username").val(data['data']['data']['user_name']);

                // document.getElementById("ai_tex_pv_tv_active").innerHTML = data['pv_tv_active'];
                // document.getElementById("ai_tex_pv_mt_active").innerHTML = data['pv_mt_active'];

                // document.getElementById("ai_tex_qualification_name").innerHTML = data['data']['data']['qualification_name'];

                var ai_customer_id = data['data']['data']['id'];
                var aistockist_username = data['data']['data']['user_name'];
                document.getElementById("aistockist_id_fk").value = ai_customer_id;
                document.getElementById("aistockist_username").value = aistockist_username;

                $('#large-Modal_ai').modal('hide')
                document.getElementById("data_useraistockis").style.display = "block";

            }
        })


}
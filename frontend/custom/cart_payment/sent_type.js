function sent_type(type, province_id_fk = '') {

    if (type == 'other') {
        $(".h_address").css("display", "block");
        $("#bill_code").css("display", "none");
        sent_address('sent_other');
        // document.getElementById("i_sent_address").classList.add('radio-disable');
        // document.getElementById("i_sent_address_card").classList.add('radio-disable');
        // document.getElementById("sent_address_check").disabled = true;
        // document.getElementById("sent_address_card_check").disabled = true;
        document.getElementById("check_user").style.display = "block";
        $("sent_address").css("display", "none")
        $("sent_address_card").css("display", "none");
        $("#send_to").css("display", "none");

    } else if (type == 'sent_another_bill') {

        $(".h_address").css("display", "none");
        // $("#btn_pay").css("display", "none");
        $("#check_user").css("display", "none");
        $("#bill_code").css("display", "block");
        check_shipping('', type);
        $("#send_to").css("display", "block");

    } else {

        $(".h_address").css("display", "block");

        sent_address('sent_address', province_id_fk);
        $("#bill_code").css("display", "none");
        document.getElementById("i_sent_address").classList.remove('radio-disable');
        document.getElementById("i_sent_address_card").classList.remove('radio-disable');
        document.getElementById("sent_address_check").disabled = false;
        document.getElementById("sent_address_card_check").disabled = false;
        document.getElementById("check_user").style.display = "none";

    }

}
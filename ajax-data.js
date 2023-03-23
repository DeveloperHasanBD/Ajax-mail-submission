(function ($) {
    $(document).ready(function () {

        var url = action_url_ajax.ajax_url;

        $("#aci_card_form").submit(function (e) {
            e.preventDefault();
            var url = action_url_ajax.ajax_url;
            var form = $("#aci_card_form");
            $("#aci_card_form .error").html('');
             $(".aci_submit_btn").val('Invia...');
            $.ajax({
                url: url,
                data: form.serialize() + '&action=' + 'aci_card_form_action' + '&param=' + 'form_data',
                type: 'post',
                dataType: 'JSON',
                success: function (data) {
                    if (data.error == true) {
                        if (data.check == true) {
                            $.each(data.message, function (key, value) {
                                $(".error_usr_" + value[0]).html(value[1]);
                                $("#aci_form_messgae").addClass('msg_m');
                                 $(".aci_submit_btn").val('Invia');
                            });
                        }
                    } else {
                        $("#aci_form_messgae").html(data.message);
                        $("#aci_form_messgae").addClass('msg_m');
                         $(".aci_submit_btn").val('Invia');
                    }
                }
            });
        })
      
    });
})(jQuery)

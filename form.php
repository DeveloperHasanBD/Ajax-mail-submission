<div class="aci_card_rpopup">
    <div class="aci_card_rpopup_innr">
        <span id="aci_form_messgae"></span>
        <button class="aci_popup_close"><i class="fa-solid fa-xmark"></i></button>
        <form id="aci_card_form" action="" method="post">
            <input type="hidden" name="selected_cid" class="selected_cid">
            <input class="form-control usr_name" type="text" name="usr_name" placeholder="Name">
            <span class="error error_usr_name"></span>
            <input class="form-control usr_email" type="email" name="usr_email" placeholder="Email">
            <span class="error error_usr_email"></span>
            <input type="hidden" value="<?php echo get_field('card_mail_to', 'option') ?>" name="admin_email">
            <input class="aci_submit_btn" type="submit" value="Invia">
        </form>
    </div>
</div>

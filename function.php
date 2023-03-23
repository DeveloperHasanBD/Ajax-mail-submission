
function aci_card_form_action()
{
    $response['error']      = false;
    $response['check']      = false;
    $response['message']    = '';

    $admin_email    = stripcslashes($_POST['admin_email']) ?? '';
    $usr_name       = stripcslashes($_POST['usr_name']) ?? '';
    $usr_email      = stripcslashes($_POST['usr_email']) ?? '';

    $message        = array();

    if (empty($usr_name)) {
        $usr_name_error = array(
            0 => 'name',
            1 => 'Name field is required.'
        );
        array_push($message, $usr_name_error);
        $response['error'] = true;
        $response['check'] = true;
    }

    if (empty($usr_email)) {
        $usr_email_error = array(
            0 => 'email',
            1 => 'Email field is required.'
        );
        array_push($message, $usr_email_error);
        $response['error'] = true;
        $response['check'] = true;
    }

    if ($response['error']  == true) {
        $response['message'] = $message;
        echo json_encode($response);
        exit();
    }


    $selected_cid = $_POST['selected_cid'];

    $aci_args = array(
        'post_type' => 'cards',
        'posts_per_page' => 1,
        'post__in' => array($selected_cid),

    );
    $aci_query = new WP_Query($aci_args);

    $aci_card = [];
    while ($aci_query->have_posts()) {
        $aci_query->the_post();

        $get_thumbnail_url = '';
        if (get_the_post_thumbnail_url()) {
            $get_thumbnail_url = get_the_post_thumbnail_url();
        } else {
            $get_thumbnail_url = get_template_directory_uri() . '/assets/images/bg.jpg';
        }

        $get_icons_text = [];
        
        if (have_rows('crd_icon_and_text')) {
            $i = 0;
            while (have_rows('crd_icon_and_text')) : the_row();
                $final_inc    = $i++;
                $aci_crd_icon = get_sub_field('aci_crd_icon');
                $aci_crd_text = get_sub_field('aci_crd_text');
                
                $get_icons_text[$final_inc]['icon_url']     = $aci_crd_icon;
                $get_icons_text[$final_inc]['text']         = $aci_crd_text;
            endwhile;
        }

        $crd_price_top_text     = get_field('crd_price_top_text');
        $crd_price              = get_field('crd_price');
        $crd_price_bottom_text  = get_field('crd_price_bottom_text');
        $crds_notes             = get_field('crds_notes');
        $aci_card_number        = get_field('aci_card_number');


        $aci_card[0] =   get_the_title();
        $aci_card[1] =   $get_thumbnail_url;
        $aci_card[2] =   $get_icons_text;
        $aci_card[3] =   $crd_price .' €';
        $aci_card[4] =   $crds_notes;
        $aci_card[5] =   $aci_card_number;
    }
    wp_reset_query();

    $set_all_icon_text = '';

    foreach ($aci_card[2] as $single_icon_text) {
        $set_all_icon_text .= ' <img width="40" src="' . $single_icon_text['icon_url'] . '" alt=""><br>' . $single_icon_text['text'] . '<br> ' . '
';
    }


    $to_mail = $admin_email;
    $headers = '';
    $headers .= "From: Aciwelfare <noreply@aciwelfare.red-apple.it> \r\n";
    $subject = "Richiesta preventivo #" . $aci_card[5];
    $headers .= "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
   
    $msg = '';
    $msg .= 'Nome: ' . $usr_name . "<br>";
    $msg .= 'Email: ' . $usr_email . "<br>";
    $msg .= 'Title: ' . $aci_card[0] . "<br>";
    $msg .= 'Price: ' . $aci_card[3] . "<br>";
    $msg .= 'Notes: ' . strip_tags($aci_card[4]) . "<br>";
    $msg .= 'icon + text:' . "<br><br>";
    $msg .= $set_all_icon_text . "<br>";
    $msg .= 'Background image <br> <img width="300" src="' . $aci_card[1] . '" alt="">'  . "<br>";

    wp_mail($to_mail, $subject, $msg, $headers, 'aciwelfare.red-apple.it');

    $response['error']      = false;
    $response['check']      = false;
    $response['message']    = 'Successfully mail sent';

    echo  json_encode($response);

    die;
}

add_action('wp_ajax_aci_card_form_action', 'aci_card_form_action');
add_action('wp_ajax_nopriv_aci_card_form_action', 'aci_card_form_action');

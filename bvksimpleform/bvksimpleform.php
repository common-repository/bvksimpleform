<?php
/*
  Plugin Name: BVK Simple Contact Form
  Plugin URI: http://www.bvkyazilim.com/cart/
  Version: 1.0
  Author: Burhan Bavkır
  Description: Enables you to add a simple contact form to your wordpress site.
  License: GPL2
 */

define('BVK_SIMPLEFORM_VERSION', 1.0);

if (!function_exists('add_action')) {
    die("BVK Simple Contact Form, All Rights Reserved, http://www.bvkyazilim.com/");
}

load_plugin_textdomain('bvk_simpleform', false, basename(dirname(__FILE__)) . '/languages');

class BVKSimpleForm {

    public function __construct() {
        add_action('init', array($this, 'init'));
        register_activation_hook(__FILE__, array($this, 'install'));
    }

    public function init() {
        add_filter("the_content", array($this, 'contentfilter'));
        add_action("admin_menu", array($this, 'config_menu'));
    }

    public function install() {

        add_option('BVK_SIMPLEFORM_VERSION', BVK_SIMPLEFORM_VERSION);
        add_option('BVK_SIMPLEFORM_LOGIN', "");
        add_option('BVK_SIMPLEFORM_EMAIL', "test@example.com");
    }

    public function contentfilter($content) {
        if(strpos($content, "[bvksimpleform]")!==FALSE){
            $form=$this->insert();
            $content=str_replace("[bvksimpleform]", $form, $content);
        }
        
        return $content;
    }

    public function insert() {
        $loginmessage=get_option("BVK_SIMPLEFORM_LOGIN");
        if (strlen($loginmessage) && !is_user_logged_in()) {
            return $loginmessage;
        }
        $errors = array();
        $result = false;
        if (isset($_POST["bvk-simpleform-send"])) {
            $send = true;
            
            if(strlen($_POST['bvkfield-email'])<1){
                $errors[] = __('Required field: ', 'bvk_simpleform') . __('Email', 'bvk_simpleform');
                $send = false;
            }
            
            if (!is_email($_POST['bvkfield-email'])) {
                $errors[] = __('Entry needs to be a valid email: ', 'bvk_simpleform') . __('Email', 'bvk_simpleform');
                $send = false;
            }
            
            if(strlen($_POST['bvkfield-message'])<1){
                $errors[] = __('Required field: ', 'bvk_simpleform') . __('Message', 'bvk_simpleform');
                $send = false;
            }
            
            if ($send) {
                $to = get_option("BVK_SIMPLEFORM_EMAIL");
                
                foreach ($form['field'] AS $field) {
                    switch ($field['type']) {
                        case 2:
                            $message[] = $field['label'] . " : " . (isset($_POST['bvkfield-' . $field['id_field']]) ? __('Yes', 'bvk_simpleform') : __('No', 'bvk_simpleform'));
                            break;
                        case 3:
                            $replyto = $_POST['bvkfield-' . $field['id_field']];
                        default :
                            $message[] = $field['label'] . " : " . nl2br(strip_tags($_POST['bvkfield-' . $field['id_field']]));
                            break;
                    }
                }
                
                $message=array(
                    __('Name:', 'bvk_simpleform')." ".esc_html($_POST['bvkfield-name']),
                    __('Surname:', 'bvk_simpleform')." ".esc_html($_POST['bvkfield-lastname']),
                    __('Email:', 'bvk_simpleform')." ".esc_html($_POST['bvkfield-email']),
                    __('Message:', 'bvk_simpleform')." ".nl2br(esc_html($_POST['bvkfield-message']))

                );
                $message = implode("<br/>", $message);
                
                $headers[] = 'Reply-To: <' . $_POST['bvkfield-email'] . '>';
                $headers[] = 'MIME-Version: 1.0';
                $headers[] = 'Content-type: text/html; charset=UTF-8';
                $result = wp_mail($to, __('New message from your site visitor ', 'bvk_simpleform'), $message, $headers);
                
                if (!$result) {
                    $errors[] = __('Unable to send the message.', 'bvk_simpleform');
                }
            }
        }
        ob_start();
        include 'contactform.php';
        return ob_get_clean();
    }

    public function config_menu() {
        add_submenu_page('plugins.php', __('BVK Simple Contact Form', 'bvk_simpleform'), __('BVK Simple Contact Form', 'bvk_simpleform'), 'manage_options', 'bvk-simpleform-config', array($this, 'config_page'));
    }

    public function config_page(){
        ?>
        <div class="wrap">
                    <h2>BVK Simple Contact Form</h2>
                    <?php
                    if (!current_user_can('manage_options')) {
                        echo __('You do not have sufficient permissions to access this page.', 'bvk_simpleform');
                    }else{
                        if(isset($_POST['bvk_simpleform_login'])){
                            update_option('BVK_SIMPLEFORM_LOGIN', esc_attr($_POST['bvk_simpleform_login']));
                        }
                        if(isset($_POST['bvk_simpleform_email'])){
                            update_option('BVK_SIMPLEFORM_EMAIL', esc_attr($_POST['bvk_simpleform_email']));
                        }
                    ?>
                        <p></p>
                        <h2><?= __('Settings', 'bvk_simpleform') ?></h2>
                        <form method="post">
                            <label>
                                <?=__('Email Adress:', 'bvk_simpleform')?>
                                <input name="bvk_simpleform_email" id="bvk_simpleform_login" value="<?=get_option("BVK_SIMPLEFORM_EMAIL")?>" />
                            </label><br/>
                            <label>
                                <?=__('Login Message (Leave empty to disable):', 'bvk_simpleform')?>
                                <input name="bvk_simpleform_login" id="bvk_simpleform_login" value="<?=get_option("BVK_SIMPLEFORM_LOGIN")?>" />
                            </label>
                            <?=submit_button(__('Save', 'bvk_simpleform'))?>
                        </form>
                        <p>
For a more advanced contact form where you can customize the fields, use multiple email addresses, captcha and more, you can check our commercial module here:
<a href="http://www.bvkyazilim.com/cart/wordpress-plugins/wordpress-advanced-contact-form.html" target="_blank">Advanced Contact Form</a>
</p>
                    <?php
                    }
            ?>
        </div>
            <?php
        }

    }
    
$bvkcontactform=new BVKSimpleForm();
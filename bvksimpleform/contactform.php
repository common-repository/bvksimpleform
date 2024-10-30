<a name="bvkcontact-start-simple"></a>
<?php if($result){ ?>
<br/>
<p><?=__('Your message was successfully sent.', 'bvk_simpleform')?></p>
<?php }else{ 
     wp_enqueue_script("jquery-validate", trailingslashit(plugin_dir_url(__FILE__))."js/jquery.validate.min.js", array("jquery"));
    ?>
<h2><?=$form['title']?></h2>
<p class="description"><?=$form['description']?></p>
<?php if(sizeof($errors)){ 
    foreach($errors AS $error){
    ?>
<p style="color:red;"><?=$error?></p>
<?php }
    } ?>
<form action="#bvkcontact-start-simple" id="bvkcontact-simple" method="post">
    <table class="form-table">
            <tbody>
                <tr>
                    <th scope="row">
                        <label for="bvkfield-name"><?=__('Name:', 'bvk_simpleform')?></label>
                    </th>
                    <td>
                        <input type="text" name="bvkfield-name" id="bvkfield-name" class="" value="<?=isset($_POST['bvkfield-name'])?$_POST['bvkfield-name']:""?>" />
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="bvkfield-lastname"><?=__('Surname:', 'bvk_simpleform')?></label>
                    </th>
                    <td>
                        <input type="text" name="bvkfield-lastname" id="bvkfield-lastname" class="" value="<?=isset($_POST['bvkfield-lastname'])?$_POST['bvkfield-lastname']:""?>" />
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="bvkfield-email"><?=__('Email:', 'bvk_simpleform')?></label>
                    </th>
                    <td>
                        <input type="text" name="bvkfield-email" id="bvkfield-email" class="required email" value="<?=isset($_POST['bvkfield-email'])?$_POST['bvkfield-email']:""?>" />
                    </td>
                </tr>
                <tr>
                    <th scope="row" style="vertical-align:top;">
                        <label for="bvkfield-message"><?=__('Message:', 'bvk_simpleform')?></label>
                    </th>
                    <td>
                        <textarea rows="10" cols="50" name="bvkfield-message" id="bvkfield-message" class="required"><?=isset($_POST['bvkfield-message'])?$_POST['bvkfield-message']:""?></textarea>
                    </td>
                </tr>
                <tr>
                        <td colspan="2">
                            <input type="submit" name="bvk-simpleform-send" class="button button-primary" value="<?=__('Send', 'bvk_simpleform')?>" />
                        </td>
                </tr>
            </tbody>
    </table>
</form>
<script tpye="text/javascript">
    (function($) {
        $(document).ready(function(){
            $("#bvkcontact-simple").validate();
        });
    })(jQuery);
</script>
<?php } ?>
<?php
# @Author: Awan Tengah
# @Date:   2019-06-18T10:34:33+07:00
# @Last modified by:   Awan Tengah
# @Last modified time: 2019-08-10T22:21:23+07:00

/**
 * User: Awan Tengah
 * Date: 06/11/2015
 * Time: 2:22
 */

//Alert helper for bootstrap 4

function alert_message()
{
    $ci = &get_instance();
    if ($ci->session->flashdata('message') != NULL) {
        $message = $ci->session->flashdata('message');
        ?>
        <div class="alert <?php echo $message['class']; ?> alert-dismissible fade show" role="alert">
            <?php echo $message['message']; ?>
        </div>
        <?php
    }
}

function alert_validation()
{
    echo validation_errors(
        "<div class='alert alert-danger alert-dismissible fade show' role='alert'>",
        "</div>"
    );
}

// Bootstrap 3
function alert_message_dashboard()
{
    $ci = &get_instance();
    if ($ci->session->flashdata('message') != NULL) {
        $message = $ci->session->flashdata('message');
        ?>
        <div class="alert <?php echo $message['class']; ?> alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <?php echo $message['message']; ?>
        </div>
        <?php
    }
}

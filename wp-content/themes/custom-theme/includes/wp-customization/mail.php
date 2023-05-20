<?php

/** Custom SMTP */
add_action('phpmailer_init', 'custom_mail_smtp_server');
 function custom_mail_smtp_server($phpmailer) {
     $phpmailer->isSMTP();
     $phpmailer->IsHTML(true);
     $phpmailer->Host = 'smtp.gmail.com';
     $phpmailer->SMTPAuth = true; // Force it to use Username and Password to authenticate
     $phpmailer->Port = 587;
     $phpmailer->Username = 'info@lisacerny.com';
     $phpmailer->Password = 'id29batrieu!@#';
     $phpmailer->SMTPSecure = "tls"; // Choose SSL or TLS, if necessary for your server
     $phpmailer->From = 'info@lisacerny.com';
     $phpmailer->FromName = 'id Beauty Center';
     $phpmailer->SetFrom("info@lisacerny.com", "id Beauty Center");
     //$phpmailer->SMTPDebug  = 1;
 }
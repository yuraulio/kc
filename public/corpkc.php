<?php

// Fetching Values from URL.
//$cname = $_POST['cname'];
$csurname = $_POST['csurname'];
$ccompany = $_POST['ccompany'];
$cjob = $_POST['cjob'];
$email = $_POST['cemail'];
$ctel = $_POST['ctel'];
//$comment = $_POST['comment'];
$email = filter_var($email, FILTER_SANITIZE_EMAIL); // Sanitizing E-mail.
// After sanitization Validation is performed
if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
$subject = $ccompany;
// To send HTML mail, the Content-type header must be set.
//$Headers1 = 'From: NoReply Knowcrunch <noreply@knowcrunch.com>'  . "\r\n" . 'Reply-To: <>';
$Headers1 = 'From:'  . $email . "\r\n";
//$Headers1 .= 'Cc:'. $email . "\r\n";
$Headers1 .= "MIME-Version: 1.0\r\n";
$Headers1 .= "Content-Type: text/html; charset=UTF-8\r\n";
$template = 
'Email:' . $email . '<br/>'

. '<strong>Ονοματεπώνυμο: ' . $csurname . '</strong><br/>'
. 'Job Title: ' . $cjob . '<br/>'
. 'Τηλέφωνο: ' . $ctel . '<br/>';
$sendmessage = "<div style='background-color:white; color:black;'>" . $template . "</div>";
// Message lines should not exceed 70 characters (PHP rule), so wrap it.
$sendmessage = wordwrap($sendmessage, 70);
// Send mail by PHP Mail Function.
//mail("nathanailidis@lioncode.gr", $subject, $sendmessage, $Headers1);
mail("info@knowcrunch.com", $subject, $sendmessage, $Headers1);
//mail("lina@knowcrunch.com", $subject, $sendmessage, $Headers1);
echo "Your message has been received, We will contact you soon.";
} else {
echo "<span>* invalid email *</span>";
}
?>
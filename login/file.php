<?php

phpinfo();
echo phpinfo(INFO_GENERAL);

if(mail("justin.chi@ismartalarm.com","A Subject Here","Hi there,\nThis email was sent using PHP's mail function."))
print "Email successfully sent";
else
print "An error occured";
?>

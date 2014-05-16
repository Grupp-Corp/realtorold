<?php
// Echo the image - timestamp appended to prevent caching
echo '<a onclick="javascript: refreshimg(); return false;" id="refreshimg" title="Click to refresh image" href="/index.php"><img src="/images/captcha/index.php?' . time() . '" width="132" height="46" alt="Captcha image" /></a>';
?>
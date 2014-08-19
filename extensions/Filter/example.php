<?php
include 'filter.php';
$original = "das @ dasdas da  da   dasd . a  aadd";

echo 'Original: '. $original.'<br />';
echo '<hr />';
echo 'New (without spaces): '. filter::genericText($original,false, true).'<br />';
echo 'New (email): '. filter::email($original).'<br />';
echo 'New (file): '. filter::file($original).'<br />';
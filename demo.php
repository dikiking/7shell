<?php

/**
 * @author boone.Q@gmail.com
 * @copyright 2011
 */

$data="phpinfo();";
$com= bzcompress($data);
eval(bzdecompress($com));
?>
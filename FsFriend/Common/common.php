<?php
//公共函数
function get_client_ip() {
	if (getenv ( "HTTP_CLIENT_IP" ) && strcasecmp ( getenv ( "HTTP_CLIENT_IP" ), "unknown" ))
		$ip = getenv ( "HTTP_CLIENT_IP" );
	else if (getenv ( "HTTP_X_FORWARDED_FOR" ) && strcasecmp ( getenv ( "HTTP_X_FORWARDED_FOR" ), "unknown" ))
		$ip = getenv ( "HTTP_X_FORWARDED_FOR" );
	else if (getenv ( "REMOTE_ADDR" ) && strcasecmp ( getenv ( "REMOTE_ADDR" ), "unknown" ))
		$ip = getenv ( "REMOTE_ADDR" );
	else if (isset ( $_SERVER ['REMOTE_ADDR'] ) && $_SERVER ['REMOTE_ADDR'] && strcasecmp ( $_SERVER ['REMOTE_ADDR'], "unknown" ))
		$ip = $_SERVER ['REMOTE_ADDR'];
	else
		$ip = "unknown";
	return ($ip);
}
	// 测试写入文件
function testwrite($d){
	$tfile = 'xgcms.txt';
	$d = ereg_replace('/$','',$d);
	$fp = @fopen($d.'/'.$tfile,'w');
	if(!$fp){
		return false;
	}else{
		fclose($fp);
		$rs = @unlink($d.'/'.$tfile);
		if($rs){
			return true;
		}else{
			return false;
		}
	}

}
	// 获取相对目录
function get_base_path($filename){
    $base_path = $_SERVER['PHP_SELF'];
    $base_path = substr($base_path,0,strpos($base_path,$filename));
	return $base_path;
}

function pwdHash($password, $type = 'md5') {
	return hash ( $type, $password );
}
//数组转换
function PostOptions($postarray = array())
{
	$str = '';
	if(is_array($postarray)) {
		for($i=0;$i<sizeof($postarray);$i++) {
			$str .= $postarray[$i];
			if($i<sizeof($postarray)-1)	{
				$str .= ",";
			}
		}	
	}
	return $str;
}
//安全替换
function safe_replace($string)
{
	$string = str_replace('%20','',$string);
	$string = str_replace('%27','',$string);
	$string = str_replace('*','',$string);
	$string = str_replace('"','&quot;',$string);
	$string = str_replace("'",'',$string);
	$string = str_replace("\"",'',$string);
	$string = str_replace('//','',$string);
	$string = str_replace(';','',$string);
	$string = str_replace('<','&lt;',$string);
	$string = str_replace('>','&gt;',$string);
	$string = str_replace('(','',$string);
	$string = str_replace(')','',$string);
	$string = str_replace("{",'',$string);
	$string = str_replace('}','',$string);
	return $string;
}
function format_textarea($string)
{
	return nl2br(str_replace(' ', '&nbsp;', htmlspecialchars($string)));
}
//取得地区
function get_area($value){    
    $AREA=require LIB_PATH.'/Cache/province.php';
	$data = "<select name=\"province\" id=\"province\" onchange=\"getCity(this.value);\"><option value=\"0\">请选择</option>";
	foreach($AREA as $k=>$v){   
		$selected = $k == $value ? 'selected' : '';
		$data .= "<option value=\"$k\" $selected>$v</option>\n";
	}
	$data .= '</select><select name="city" id="city">
            <option value="0">请选择</option>
        </select>';
	unset($AREA);
	return $data;
}
function areaid($value)
	{
		$AREA=require COMMON_PATH.'/Area/area.php';
		if($AREA[$value]['parentid']>0){
			$pid=$AREA[$value]['parentid'];
		    return $AREA[$pid]['name']."/".$AREA[$value]['name'];
		}else{
			return $AREA[$value]['name'];
		}
	}
//用户头像
function avatar($id,$g){
	if($id>0){
	$att=M('attachment');
	$list=$att->where("id='$id' and model='avatar' ")->find();
	if(!empty($list)){
		if(!preg_match("/http/", $list['image'])){
		    return C('SITE_URL').$list['image'];
		}else{
			return $list['image'];
		}
	}else{
		return C('SITE_URL').'Public/Uploads/avatar/nophoto'.$g.'.gif';
	}
	}else{
		return C('SITE_URL').'Public/Uploads/avatar/nophoto'.$g.'.gif';
	}
}
//用户昵称
function nickname($uid){
	    $user=M('Member_detail');
	    $list=$user->field('nickname')->where("uid='$uid'")->find();
	    if($list){
		    return filterReplace($list['nickname']);
	    }else{
		    return '未知用户';
	    }
}
function get_uid($username){
	$m=D('member');
	$map['username']=$username;
	$rs=$m->field('uid')->where($map)->find();
	if(!$rs['uid']) $rs['uid']=rand(1000,386631);
	return $rs['uid'];
}
function is_login(){
    if($_SESSION[C('USER_AUTH_KEY')]){
	    return $_uid=$_SESSION[C('USER_AUTH_KEY')];
	}else{
        return $_uid='0';
	}
}
function get_user_one($uid){//获取用户信息
	$m=M('member_detail');
	$rs=$m->where('uid='.$uid)->find();
	return $rs;
}
function msubstr($str, $start=0, $length=3, $charset="utf-8", $suffix=true){
    if(function_exists("mb_substr"))
        return mb_substr($str, $start, $length, $charset);
    elseif(function_exists('iconv_substr')) {
        return iconv_substr($str,$start,$length,$charset);
    }
    $re['utf-8']   = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
    $re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
    $re['gbk']    = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
    $re['big5']   = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
    preg_match_all($re[$charset], $str, $match);
    $slice = join("",array_slice($match[0], $start, $length));
    if($suffix) return $slice."…";
    return $slice;
}
//类似于asp的fso操作
function mkdirss($dirs,$mode=0777) {
	if(!is_dir($dirs)){
		mkdirss(dirname($dirs), $mode);
		return @mkdir($dirs, $mode);
	}
	return true;
}
function write_file($l1, $l2=''){
	$dir = dirname($l1);
	if(!is_dir($dir)){
		mkdirss($dir);
	}
	return @file_put_contents($l1, $l2);
}
function read_file($l1){
	return @file_get_contents($l1);
}
// 数组保存到文件
function arr2file($filename, $arr=''){
	if(is_array($arr)){
		$con = var_export($arr,true);
	} else{
		$con = $arr;
	}
	$con = "<?php\nreturn $con;\n?>";//\n!defined('IN_MP') && die();\nreturn $con;\n
	write_file($filename, $con);
}
// 转换成JS
function t2js($l1, $l2=1){
    $I1 = str_replace(array("\r", "\n"), array('', '\n'), addslashes($l1));
    return $l2 ? "document.write(\"$I1\");" : $I1;
}
//取得分类名称
function catname($cid){
	$diarycat=require LIB_PATH.'/Cache/Cat_diary.inc.php';
	return $diarycat[$cid]['name'];
}
//数据过滤函数库
function in($str)
{
	$str=trim(htmlspecialchars($str));
	if(!get_magic_quotes_gpc()) 
	{
  	  $str = addslashes($str);
	}
	return $str;	
}
function out($str)
{
    $str = stripslashes($str);
	return $str;	
}
//文本输入
function text_in($str)
{
	$str=strip_tags($str,'<br>');
	$str = str_replace(" ", "&nbsp;", $str);
	$str = str_replace("\n", "<br>", $str);	
	if(!get_magic_quotes_gpc()) 
	{
  	  $str = addslashes($str);
	}
	return $str;
}
//文本输出
function text_out($str)
{
	$str = str_replace("&nbsp;", " ", $str);
	$str = str_replace("<br>", "\n", $str);	
    $str = stripslashes($str);
	return $str;
}
//html代码输入
function html_in($str)
{
	$search = array ("'<script[^>]*?>.*?</script>'si",  // 去掉 javascript
					 "'<iframe[^>]*?>.*?</iframe>'si", // 去掉iframe
					);
	$replace = array ("",
					  "",
					);			  
   $str=@preg_replace ($search, $replace, $str);
   $str=htmlspecialchars($str);
   	if(!get_magic_quotes_gpc()) 
	{
  	  $str = addslashes($str);
	}
   return $str;
}
//html代码输出
function html_out($str)
{
	if(function_exists('htmlspecialchars_decode'))
		$str=htmlspecialchars_decode($str);
	else
		$str=html_entity_decode($str);

    $str = filterReplace(stripslashes($str));
	return $str;
}
//
function qingyuan_auth($txt, $operation = 'ENCODE', $key = '')
{
	$key	= $key ? $key : $GLOBALS['phpcms_auth_key'];
	$txt	= $operation == 'ENCODE' ? $txt : base64_decode($txt);
	$len	= strlen($key);
	$code	= '';
	for($i=0; $i<strlen($txt); $i++){
		$k		= $i % $len;
		$code  .= $txt[$i] ^ $key[$k];
	}
	$code = $operation == 'DECODE' ? $code : base64_encode($code);
	return $code;
}
function set_cookie($var, $value = '', $time = 0)
{
	$time = $time > 0 ? $time : 0;
	$s = $_SERVER['SERVER_PORT'] == '443' ? 1 : 0;
	$var = C('COOKIE_PRE').$var;
	$_COOKIE[$var] = $value;
	if(is_array($value))
	{
		foreach($value as $k=>$v)
		{
			setcookie($var.'['.$k.']', $v, $time, C('COOKIE_PATH'), C('COOKIE_DOMAIN'), $s);
		}
	}
	else
	{
		setcookie($var, $value, $time, C('COOKIE_PATH'), C('COOKIE_DOMAIN'), $s);
	}
}

function get_cookie($var)
{
	$var = C('COOKIE_PRE').$var;
	return isset($_COOKIE[$var]) ? $_COOKIE[$var] : false;
}
	function escape($str)
	{
		if(strtolower(C('DEFAULT_CHARSET'))=='gbk')
		{
			preg_match_all("/[\x80-\xff].|[\x01-\x7f]+/",$str,$r);
			$ar = $r[0];  
			foreach($ar as $k=>$v)
			{
			  if(ord($v[0]) < 128)
				  $ar[$k] = rawurlencode($v);
			  else
				  $ar[$k] = "%u".bin2hex(iconv(CHARSET,"UCS-2",$v));
			}  
			return join("",$ar);
		}
		else
		{
			preg_match_all("/[\xc2-\xdf][\x80-\xbf]+|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}|[\x01-\x7f]+/e",$str,$r);
			$str = $r[0];
			$len = count($str);
			for($i=0; $i<$len; $i++) {
				$value = ord($str[$i][0]);
				if($value < 223){
					$str[$i] = rawurlencode(utf8_decode($str[$i]));
				} else {
				$str[$i] = "%u".strtoupper(bin2hex(iconv("UTF-8","UCS-2",$str[$i])));
				}
			}
			return join("",$str);

		}
	}
//URL设置
function userurl($arr){
	if(empty($arr)) return '';
	foreach($arr as $k=>$v){
		$arr[$k]['url_pro']='View/user/uid/'.$v['uid'].C('URL_HTML_SUFFIX');
	}
    return $arr;
}
function mod_name($mod){
	$arr= array(
	'Index'=>'首页',
	'Search'=>'搜索会员',
	'Diary'=>'会员日记',
	'Help'=>'使用帮助',
	'Member'=>'会员中心',
	'Pay'=>'财务管理',
	'Collect'=>'收藏会员',
	'Album'=>'个人相片',
	'Message'=>'站内短信',
	'Register'=>'注册会员'
	
	);
	if($arr[$mod]){
		return $arr[$mod];
	}else{
		return '';
	}
	
}
//词语替换
function filterReplace($str)
{
	$filterWords = unserialize(substr(file_get_contents(LIB_PATH."/Cache/filter_inc.php"),13));

	for($i=0;$i<count($filterWords);$i++)
	{
		$str = str_ireplace($filterWords[$i][0],$filterWords[$i][1],$str);
	}

	unset($filterWords);

	return $str;
}
//缓存文件写入、读取、删除
function cache_read($file, $path = '', $iscachevar = 0)
{
	if(!$path) $path = CACHE_PATH;
	$cachefile = $path.$file;
	if($iscachevar)
	{
		global $TEMP;
		$key = 'cache_'.substr($file, 0, -4);
		return isset($TEMP[$key]) ? $TEMP[$key] : $TEMP[$key] = @include $cachefile;
	}
	return @include $cachefile;
}

function cache_write($file, $array, $path = '')
{
	if(!is_array($array)) return false;
	$array = "<?php\nreturn ".var_export($array, true).";\n?>";
	$cachefile = ($path ? $path : TEMP_PATH).$file;
	$strlen = file_put_contents($cachefile, $array);
	@chmod($cachefile, 0777);
	return $strlen;
}

function cache_delete($file, $path = '')
{
	$cachefile = ($path ? $path : CACHE_PATH).$file;
	return @unlink($cachefile);
}

//日期转换成时间戳
function fomattime($date){
	$year=split("-",$date);
	return $b=mktime(0,0,0,$year['1'],$year['2'],$year['0']);
}
function bir2age($date){
	$now=time();
	$bir=fomattime($date);
	$age=($now-$bir)/(3600*24*365);
	return intval($age);
}
function get_cityid($name){
	$name=str_replace('市', '', $name);
    $AREA=require COMMON_PATH.'/Area/area.php';
    foreach($AREA as $k=>$v){
    	if($name==$v['name']){
    		$cid=$v['areaid'];
    		break;
    	}
    }
    if($cid){
    	return $cid;
    }else{
    	return '0';
    }
}
function dec1($n){
	return $n-1;
}
// 获取广告调用地址
function getadsurl($str,$charset="utf-8"){
	return '<script type="text/javascript" src="'.__PUBLIC__.'/ads/'.$str.'.js" charset="'.$charset.'"></script>';
}

?>
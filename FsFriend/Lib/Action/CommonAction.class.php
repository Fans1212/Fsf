<?php

class CommonAction extends Action{
    public function _initialize(){    
        $style=array();  
        header("Content-Type:text/html; charset=".C('DEFAULT_CHARSET'));
        $this->ck_access();	
		$style['tpl']=C('site_url').TEMPLATE_PATH.'/';
		$style['css']='<link rel="stylesheet" type="text/css" href="'.C('site_url').TEMPLATE_PATH.'/Skins/base.css">'."\n";
		$style['root']=C('site_url');//APP_PATH.'/'
		$style['site_name']=C('site_name');
		$style['site_email']=C('SITE_EMAIL');
		$style['site_url']=C('site_url');
		$style['keywords']=C('site_keywords');
		$style['description']=C('site_description');
		$style['copyright']=C('site_copyright');
		$style['site_stats']=C('SITE_STATS');
		$style['title']=mod_name(MODULE_NAME).'-'.C('site_name');	
        $this->assign($style);
    }
    //检查权限
    function ck_access(){
    	if(!in_array(strtolower(MODULE_NAME),explode(',',C('NOT_LOGIN_MODULE')))&&!in_array(strtolower(ACTION_NAME),explode(',',C('NOT_LOGIN_ACTION')))){//不需要认证的模块除外
			//检查登录
			$this->ck_login();
			if(!$_SESSION[C('USER_AUTH_KEY')]){
				$this->assign("jumpUrl",C('SITE_URL'));
				$this->error('对不起,您还没有登录！');
			}
		}	
    }
	//检查登录
	function ck_login(){
	    $qingyuan_auth = get_cookie('auth');
        if($qingyuan_auth){
	         $auth_key = md5(C('AUTH_KEY').$_SERVER['HTTP_USER_AGENT']);
	         list($_userid, $_password) = explode("\t", qingyuan_auth($qingyuan_auth, 'DECODE', $auth_key));
	         $_userid = intval($_userid);
	         $m=M('member');
	         $r=$m->find($_userid);
	         if($r && $r['password'] === $_password){
		         if($r['group'] == 2){
			          set_cookie('auth', '');
			          $this->error('你的账号已经被禁止使用！');
		         }
		         $this->setsession($_userid);
	         }else{
		         set_cookie('auth', '');
	         }
        }
	}

	public function setsession($uid){
			$User = D("Member");
		    $map=array();
		    $map['uid']	=	$uid;
		    $authInfo=$User->where($map)->find();
		    $_SESSION[C('USER_AUTH_KEY')]	=	$authInfo['uid'];
            $_SESSION['loginUserName']		=	$authInfo['username'];
            $_SESSION['group']              =   $authInfo['group'];
            $_SESSION['email']              =   $authInfo['email'];
	}
    
	public function qyuser($str=''){
		$param=array();
		$array=explode(';',$str);
		foreach ($array as $v){
			list($key,$val)=explode(':',trim($v));
			$param[trim($key)]=trim($val);
		}//
		$where=array();
		
		if(isset($param['gender'])) $where['gender']=$param['gender'];
		if($param['city']) $where['city']=$param['city'];
		
		if($param['avatar']) $where['avatar']=array('gt','0');
		if($param['num']){
			$limit=$param['num'];
		}else{
			$limit='20';
		}
		if($param['order']){
			$order=$param['order'];
		}else{
			$order='uid desc';
		}
		$m=M('member_detail');
		$arr=$m->where($where)->order($order)->limit($limit)->select();
		foreach($arr as $k=>$v){
			$arr[$k]['nickname']=filterReplace($arr[$k]['nickname']);
			$arr[$k]['aboutme']=filterReplace($arr[$k]['aboutme']);
			$arr[$k]['city']=areaid($v['city']);
			$arr[$k]['url_pro']='View/user/uid/'.$v['uid'].C('URL_HTML_SUFFIX');
		}
		return $arr;
	}
	
	//日记列表
	public function qydiary($str=''){
		$param=array();
		$array=explode(';',$str);
		foreach ($array as $v){
			list($key,$val)=explode(':',trim($v));
			$param[trim($key)]=trim($val);
		}//
		$where=array();
		$where['stat']='1';
		if($param['uid']) $where['uid']=$param['uid'];
		if($param['cateid']) $where['cateid']=$param['cateid'];
		if($param['num']){
			$limit=$param['num'];
		}else{
			$limit='20';
		}
		if($param['order']){
			$order=$param['order'];
		}else{
			$order='thedate desc';
		}
		$m=M('diary');
		$arr=$m->where($where)->order($order)->limit($limit)->select();
		$detail=M('member_detail');	
		foreach($arr as $k=>$v){
			$info=$detail->find($v['uid']);
			$arr[$k]['diarytitle']=filterReplace($arr[$k]['diarytitle']);
			$arr[$k]['diarytxt']=filterReplace($arr[$k]['diarytxt']);
			$arr[$k]['city']=areaid($info['city']);
			$arr[$k]['gender']=$info['gender'];
			$arr[$k]['age']=$info['age'];
			$arr[$k]['nickname']=filterReplace($info['nickname']);
			$arr[$k]['avatar']=$info['avatar'];
			$arr[$k]['url_article']='View/article/id/'.$v['id'].C('URL_HTML_SUFFIX');
			$arr[$k]['url_pro']='View/user/uid/'.$v['uid'].C('URL_HTML_SUFFIX');
		}	
		return $arr;
	}
}
?>
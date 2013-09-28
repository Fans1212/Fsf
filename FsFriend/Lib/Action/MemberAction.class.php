<?php

class MemberAction extends CommonAction{	
	function index(){
		$uid=$_SESSION[C('USER_AUTH_KEY')];
		if(!$uid) $this->error('您没有登录或者登录超时！');
		$info=D('member');
		$rs=$info->where('uid='.$uid)->find();
        $this->assign($rs);
        $this->display();
		
	}
	function login(){
	    if(!isset($_SESSION[C('USER_AUTH_KEY')])) {
			$this->display('login');
		}else{
			$this->redirect('index');
		}
	}
	//检查登录
	public function checkLogin() {
		if(empty($_POST['username'])) {
			$this->error('帐号错误！');
		}elseif (empty($_POST['password'])){
			$this->error('密码必须！');
		}
        //生成认证条件
        $map            =   array();
		// 支持使用绑定帐号登录
		$map['username']	= $_POST['username'];
        //$map["status"]	=	array('gt',0);
		import ( '@.ORG.RBAC' );
        $authInfo = RBAC::authenticate($map);
        //使用用户名、密码和状态的方式进行认证
        if(false === $authInfo) {
            $this->error('帐号不存在或已被禁用！');
        }else {
            if($authInfo['password'] != md5($_POST['password'].C('SAFE_CODE'))) {
            	$this->error('密码错误！');
            }
            $_SESSION[C('USER_AUTH_KEY')]	=	$authInfo['uid'];
            $_SESSION['loginUserName']		=	$authInfo['username'];
            $_SESSION['group']              =   $authInfo['group'];
            $_SESSION['email']              =   $authInfo['email'];
            if($authInfo['group']=='1') {
            	$_SESSION['administrator']		=	true;
            }
            //保持cookie            
		    $cookietime = time()+3600*24*10;//cookie时间10天
		    $qingyuan_auth_key = md5(C('AUTH_KEY').$_SERVER['HTTP_USER_AGENT']);
		    $qingyuan_auth = qingyuan_auth($authInfo['uid']."\t".$authInfo['password'], 'ENCODE', $qingyuan_auth_key);
		    set_cookie('auth', $qingyuan_auth, $cookietime);
			set_cookie('username', escape($authInfo['username']), $cookietime);
            //保存登录信息
			$Member	=	M('Member');
			$ip		=	get_client_ip();
			$time	=	time();
            $data = array();
			$data['uid']	=	$authInfo['uid'];
			$data['login_time']	=	$time;
			$data['login_count']	=	array('exp','login_count+1');
			$data['login_ip']	=	$ip;
			$Member->save($data);

			// 缓存访问权限
            RBAC::saveAccessList();
			$this->success('登录成功！');

		}
	}
	// 用户登出
    public function logout()
    {
        if(isset($_SESSION[C('USER_AUTH_KEY')])) {
			unset($_SESSION[C('USER_AUTH_KEY')]);
			unset($_SESSION);
			set_cookie('auth', '');
		    set_cookie('username', '');
			session_destroy();
            $this->assign("jumpUrl",C('SITE_URL'));
            $this->success('退出成功！');
        }else {
            $this->error('已经退出！');
        }
    }
    
	function profile(){
		$uid=$_SESSION[C('USER_AUTH_KEY')];
		if(!$uid) $this->error('您没有登录或者登录超时！');
		$profile=D('Member_detail');
		$data=$profile->where("uid=$uid")->find();
		if($_POST['submit']){
			$this->mform_in($profile);
			$result=$profile->where("uid=$uid")->save();
			if(false !== $result) {
				$this->setsession($uid);
			    $this->success('资料修改成功！');
		    }else{
			    $this->error('资料修改失败!');
		    }
		}else{
		    import ( '@.Cache.Form' );
		    import ('@.Cache.Member_form');
		    $member_form = new member_form();
            $forminfos = $member_form->getform($data);
            $this->assign($data);
            $this->assign('email',$_SESSION['email']);
            $this->assign('forminfos',$forminfos);
		    $this->display('profile');
		}
	}
	
	function mform_in($model){
		$fields =require LIB_DIR.'/Cache/Member_fields.inc.php';
		foreach($fields as $k=>$v){
			if($v['isshow']){
			$value=$_POST[$k];
			if(is_array($value)){$value=PostOptions($value);}
			$model->$k=$value;
			}
		}
		$model->province= $_POST['province'];
		$model->updatetime= time();
	}
	//上传头像
	function avatar(){
		$uid=$_SESSION[C('USER_AUTH_KEY')];
		if(!$uid) $this->error('您没有登录或者登录超时！');
		$map['uid']=$uid;
		$map['model']='avatar';
		$Photo=M('attachment');
		$list=$Photo->where($map)->find();
		if(!$list){
			$m=D('member_detail');
			$list=$m->where('uid='.$uid)->find();
		}
		$this->assign($list);
		$this->display();
	}
	function editpw(){
		$uid=$_SESSION[C('USER_AUTH_KEY')];
		if(!$uid) $this->error('您没有登录或者登录超时！');
		if($_POST['submit']){
			$oldpw=trim($_POST['oldpw']);
			$newpw=trim($_POST['newpw']);
			$newpw2=trim($_POST['newpw2']);
			if($newpw==''||strlen($newpw)<6) $this->error('请输入新密码且必须在6位以上！');
			if($newpw!==$newpw2) $this->error('新密码两次输入不一致！');
            $map = array();
		    $map['uid']	= $uid;
		    import ( '@.ORG.RBAC' );
            $authInfo = RBAC::authenticate($map);
            if(false === $authInfo) {
                $this->error('帐号不存在或已禁用！');
            }else {
                if($authInfo['password'] != md5($oldpw.C('SAFE_CODE'))) {
            	    $this->error('原密码错误！');
                }else{
                	$m=D('Member');
                	$data['password']=md5($newpw.C('SAFE_CODE'));
                	if($rs=$m->where($map)->save($data)){
                		$this->success('密码修改成功！');
                	}else{
                		$this->error('密码修改失败！');
                	}
                }
            }
		}else{
		    $this->display();
		}
	}
	
}

?>
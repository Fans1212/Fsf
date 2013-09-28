<?php
class RegisterAction extends CommonAction{
	public function index(){
		if(!isset($_SESSION[C('USER_AUTH_KEY')])) {
			import ( '@.Cache.Form' );
			import ('@.Cache.Member_form');
		    $member_form = new member_form();
            $forminfos = $member_form->getform($data='');
            $this->assign('forminfos',$forminfos);
            $this->assign('qylist',A('Common'));
			$this->display('Member:reg');
		}else{
			$this->redirect('Member/index');
		}
	}
	// 插入数据
	public function reg_sub() {
		$uid=$_SESSION[C('USER_AUTH_KEY')];
		if($uid) $this->error('请不要重复注册！');
		// 创建数据对象
		$Member	 =	 D("Member");
		if(!$Member->create()) {
            $this->error($Member->getError());
		}else{
			// 写入帐号数据
			if($result	 =	 $Member->add()) {
				$map['uid']	=	$result;
				$Info=$Member->where($map)->find();
				$_SESSION[C('USER_AUTH_KEY')]	=	$Info['uid'];
                $_SESSION['loginUserName']		=	$Info['username'];
                $_SESSION['group']              =   $Info['group'];
                $_SESSION['email']              =   $Info['email'];	
                //保持cookie            
		        $cookietime = time()+3600*24*10;//cookie时间10天
		        $qingyuan_auth_key = md5(C('AUTH_KEY').$_SERVER['HTTP_USER_AGENT']);
		        $qingyuan_auth = qingyuan_auth($Info['uid']."\t".$Info['password'], 'ENCODE', $qingyuan_auth_key);
		        set_cookie('auth', $qingyuan_auth, $cookietime);
			    set_cookie('username', escape($Info['username']), $cookietime);
			    
				$Member_detail=M("Member_detail");	
		        $data['uid']	=	$Info['uid'];
		        $data['nickname']=$Info['username'];
				$data['status']=C('reg_audit');
		        $fields =require LIB_DIR.'/Cache/Member_fields.inc.php';
		        foreach($fields as $k=>$v){
			        if($v['isshow']){
			        $value=$_POST[$k];
			        if(is_array($value)){$value=PostOptions($value);}
			        $data[$k]=$value;
			        }
		        }
		        $data['aboutme']=strip_tags($_POST['aboutme']);		        
		        $data['province']= $_POST['province'];
		        $data['updatetime']= time();
				
				
		        $Member_detail->add($data);
			    $this->assign('jumpUrl',C('SITE_URL').'?s=Register/req');
			    $this->success('用户注册成功！');
	        }else{
			    $this->error('用户注册失败！');
	        }
	    }
	}
	//填写择友要求
	function req(){
		$uid=$_SESSION[C('USER_AUTH_KEY')];
		if(!uid) $this->error('对不起，请登录后操作！');
		$AREA=require COMMON_PATH.'/Area/area.php';
		$str = "<select name=\"province\" id=\"province\" onchange=\"getCity(this.value);\"><option value=\"\">--不限--</option>";
	    foreach($AREA as $k=>$v){   
		    if($v['parentid']==0)
		    $str .= "<option value=\"$v[areaid]\">$v[name]</option>\n";
	    }
	   $str .= '</select><select name="city" id="city">
            <option value="不限">--不限--</option>
        </select>';
		
		$this->assign('area',$str);
		$this->display('Member:req');
		
	}
	function req_sub(){
		$uid=$_SESSION[C('USER_AUTH_KEY')];
		if(!uid) $this->error('对不起，请登录后操作！');
		$data['rq_age']=$_POST['minAge'].'岁-'.$_POST['maxAge'].'岁';
		$data['rq_height']=$_POST['minHeight'].'厘米-'.$_POST['maxHeight'].'厘米';
		$data['rq_area']=$_POST['city'];
		$data['rq_other']=strip_tags($_POST['rq_other']);
		$data['rq_area']=$_POST['city'];
		$data['rq_income']=$_POST['rq_income'];
		$data['rq_edu']=$_POST['rq_edu'];
		$marry=$_POST['rq_marry'];
		$data['rq_marry']='';
		foreach($marry as $v){
			$data['rq_marry'].=$v.' ';
		}
		$data['uid']=$uid;
		$m=D('member_require');
		$map['uid']=$uid;
		if($rs=$m->where($map)->find()){
			$m->where($map)->save($data);
		}else{
		    if($rs=$m->add($data)){
			    $this->assign('jumpUrl',C('SITE_URL').'?s=Member/');			   
		    }else{
			    $this->error('提交失败！');
		    }
		}
		$this->success('择友要求更新成功！');
	} 
	
	function checkuser(){
		$name=$_REQUEST['value'];
		$m=D('member');
		$where['username']=$name;
		$rs=$m->where($where)->find();
		if($rs){
			exit('该用户名已经被注册！');
		}else{
			exit('success');
		}
	}
	//找回密码
	function sendPw(){
		if($_POST['submit']){
			$m=D('member');
			$username=$_POST['username'];
			$email=$_POST['email'];
			if(empty($username)||empty($email)) $this->error('请输入你的用户名和邮箱！');
			$map['username']=$username;
			$map['email']=$email;
			$rs=$m->where($map)->find();
			if(!$rs){
				$this->error('用户名或者邮箱错误！');
			}else{
				import('@.ORG.Email');
			    $config=C('EMAIL');
			    Email::init($config);
			    $hash=pwdHash($rs['password']);
			    $u=urlencode($rs['username']);
			    $url=C('SITE_URL').'Register/restpw/u/'.$u.'/h/'.$hash;
			    $to=$email;
			    $title='找回密码--'.C('SITE_NAME');
			    $content='<p>'.C('SITE_NAME').'找回密码验证邮件</p><p>请将以下网址复制到浏览器地址打开：<p><p><a href="'.$url.'" target="_blank">'.$url.'</a>';
			    Email::send($to,$title,$content);
			    $this->success('验证邮件已经发送，请登录你的邮箱查看。');
			}
		}
		$this->display('Member:sendPw');
	}
	//重设密码
	function restpw(){
		$username=$_REQUEST['u'];
		$hash=$_REQUEST['h'];
		$m=D('member');
		$map['username']=$username;
		$rs=$m->where($map)->find();
		$hpw=pwdhash($rs['password']);
		$this->assign('jumpUrl',C('SITE_URL'));
		if(!$rs||$hash!==$hpw){
			$this->error('非法操作！');
		}else{
			if($_POST['submit']){
				$newpw=trim($_POST['newpw']);
			    $newpw2=trim($_POST['newpw2']);
			    if($newpw==''||strlen($newpw)<6) $this->error('请输入新密码且必须在6位以上！');
			    if($newpw!==$newpw2) $this->error('新密码两次输入不一致！');
			    $data['password']=md5($newpw.C('SAFE_CODE'));
                if($rs=$m->where($map)->save($data)){
                	$this->success('密码修改成功,请重新登录！');
                }else{
                	$this->error('密码修改失败！');
                }
			}else{
				$this->assign('hash',$hash);
				$this->assign('username',$username);
				$this->display('Public:password');
			}			
		}		
	}
}
?>
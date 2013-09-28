<?php
class ViewAction extends CommonAction{
	//会员详细资料
	function user(){
		$isadmin=$_SESSION['administrator'];
		$uid=trim($_GET['uid']);
		if(!is_numeric($uid)){
			$this->error('请选择有效用户！');
		}
		$infos=$this->userpro($uid);
		if($infos){
			$req=D('member_require');
			$require=$req->where('uid='.$uid)->find();
			$this->assign($infos);
			$this->assign('isadmin',$isadmin);
			$this->assign($require);
			$this->assign('qylist',A('Common'));
			$this->assign('title',$infos['nickname'].'的交友资料_'.C('site_name'));
			$this->display('Member:view');
		}else{
			$this->error('没有该用户或者已经被禁止！');
		}
	}
	//会员相册
	function photo(){
		$uid=trim($_GET['uid']);
		if(!is_numeric($uid)) $this->error('请选择有效用户！');
		$infos=$this->userpro($uid);
		if(!$infos) $this->error('没有该用户或者已经被禁止！');
		$album=D('album');
		$map['uid']=$uid;
		$map['status']='1';
		$order='albumid desc';
		$count = $album->where($map)->count();
		import("@.ORG.Page");
		$p = new Page ( $count, 10 );
		$list=$album->where($map)->order($order)->limit($p->firstRow.','.$p->listRows)->select();
		$page = $p->photo();
		$num=count($list); 
		foreach($list as $k=>$v){
		    $list[$k]['num']=$k;	 
		}
		$this->assign($page);
		$this->assign('list',$list);
		$this->assign($infos);
		$this->assign('number',$num);
		$this->assign('qylist',A('Common'));
		$this->assign('title',$infos['nickname'].'的相册_'.C('site_name'));
		$this->display('Member:view_photo');
	}
	//会员日记
	function diary(){
		$uid=trim($_GET['uid']);
		if(!is_numeric($uid)) $this->error('请选择有效用户！');
		$infos=$this->userpro($uid);
		if(!$infos) $this->error('没有该用户或者已经被禁止！');
		$diary=D('diary');
		$map['uid']=$uid;
		$map['stat']='1';
		$order='thedate desc';
		import("@.ORG.Page");
		$count = $diary->where($map)->count();
		$p = new Page ( $count, 10 );
		$list=$diary->where($map)->order($order)->limit($p->firstRow.','.$p->listRows)->select();
		$page = $p->show();
		foreach($list as $k=>$v){
			$list[$k]['diarytitle']=filterReplace(strip_tags($v['diarytitle']));
			$list[$k]['diarytxt']=filterReplace(strip_tags($v['diarytxt']));
		}
		$this->assign('page',$page);
		$this->assign('list',$list);
		$this->assign($infos);
		$this->assign('qylist',A('Common'));
		$this->assign('title',$infos['nickname'].'的日记_'.C('site_name'));
		$this->display('Member:view_diary');
	}
	//会员日记内容
	function article(){
		$id=trim($_GET['id']);
		if(!is_numeric($id)) $this->error('日记不存在！');		
		$diary=D('diary');
		$map['id']=$id;
		$map['stat']='1';
		$rs=$diary->where($map)->find();
		if(empty($rs)) $this->error('日记不存在或没有审核！');
		$rs['diarytitle']=html_out($rs['diarytitle']);
		$rs['diarytxt']=html_out($rs['diarytxt']);
		$infos=$this->userpro($rs['uid']);
		$this->assign($rs);
		$this->assign($infos);
		$this->assign('qylist',A('Common'));
		$this->assign('title',$rs['diarytitle'].'_'.C('site_name'));
		$this->display('Member:view_article');
		$diary->setInc('viewtimes','id='.$id);
	}
	//查看联系方式
	function contact(){
		$_uid=is_login();
		if(!$_uid) $this->error('对不起，你还没有登录！');
		$uid=trim($_GET['uid']);
		if(!is_numeric($uid)) $this->error('请选择有效用户！');
		$infos=$this->userpro($uid);
		$member=M('member');
		$rs2=$member->field('email')->where('uid='.$uid)->find();
		$_user=$member->where('uid='.$_uid)->find();
		if(!$_user) $this->error('非法操作！');
		if($_user['group']<7&&$_user['credit']>=10){//查询是否付费过
			$charge=D('charge');
			$item="查看用户(UID:$uid)";
			$map['item']=array('like',$item);
			$map['account']='3';
			if(!$arr=$charge->where($map)->find()){
				$member->setDec('credit','uid='.$_uid,'10');//扣除E币
			    $this->insert_charge($_uid,$item,'10','3');
			}		
		}
		$infos['email']=$rs2['email'];
		$this->assign($infos);
		$this->assign('qylist',A('Common'));
		$this->assign('title',$infos['nickname'].'的联系方式_'.C('site_name'));
		$this->display('Member:view_contact');
	}
	//message
	function message(){
		$_uid=is_login();
		if(!$_uid) $this->error('对不起，你还没有登录！');
		$uid=trim($_GET['uid']);
		if(!is_numeric($uid)) $this->error('请选择有效用户！');
		$infos=$this->userpro($uid);
		if(!$infos) $this->error('没有该用户或者已经被禁止！');
		$this->assign($infos);
		$this->assign('qylist',A('Common'));
		$this->assign('title','发送短信_'.C('site_name'));
		$this->display('Member:view_message');
	}
	//check access
	function ckAcc(){
		$uid=$_SESSION[C('USER_AUTH_KEY')];
		if(!$uid){
			$msg='accno';
		}else{
			$m=D('member');	
			$map['uid']=$uid;
			$rs=$m->where($map)->find();		
			$group=$rs['group'];
			$credit=$rs['credit'];
			if($group>6||$credit>9){
				$msg='accyes';
			}else{
				$msg='accno';
			}
		}
		return $msg;
		
	}
	//输出用户资料
	function userpro($uid){
		$m=M('member_detail');
		$map['uid']=$uid;
		$map['status']=1;
		if($rs=$m->where($map)->find()){
			import("@.Cache.Member_output");
		    $member_output = new member_output($uid);
            $infos = $member_output->get_list($rs);
            $infos['nickname']=filterReplace($rs['nickname']);
            $infos['aboutme']=filterReplace($rs['aboutme']);
            $infos['uid']=$rs['uid'];
            $infos['gender']=$rs['gender'];
            $infos['cityid']=$rs['city'];
            $infos['avatar']=$rs['avatar'];
		    $infos['isacce']=$this->ckAcc();
		    $m->setInc('hits','uid='.$uid);
            return $infos;
		}else{
			return false;
		}
		
	}
	//消费记录
   function insert_charge($uid,$item,$num,$account){
		$charge=D('charge');
		$data['time']=time();
		$data['uid']=$uid;
		$data['item']=$item;
		$data['num']=$num;
		$data['account']=$account;
		$charge->add($data);
	}
	
	//ajax顶踩
    public function ajax(){
		$id=$_GET['id'];
		$tid=$_GET['t'];
		if($id){
		    if($tid>0&&Cookie::is_set('newsud-'.$id)){
			    exit('0');
			}
			$rs=D("diary");
			if(1==$tid){
			    $rs->setInc('good','id='.$id);
			}elseif(2==$tid){
			    $rs->setInc('bad','id='.$id);
			};
			if($tid>0){
				C('COOKIE_EXPIRE',60*60*24);
				Cookie::set('newsud-'.$id,'ok');
			}
			$field[]='good';$field[]='bad';
			$arr=$rs->field($field)->find($id);
			header("Content-Type:text/html; charset=utf-8");
			echo($arr['good'].':'.$arr['bad']);
		}
    }
}
?>
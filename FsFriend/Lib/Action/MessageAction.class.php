<?php
class MessageAction extends CommonAction{
	function index(){
		$uid=$_SESSION[C('USER_AUTH_KEY')];
		if(!$uid) $this->error('您没有登录或者登录超时！');
		import("@.ORG.Page");
		$msg=D('Message');
		$map['to']=$uid;
		$count = $msg->where($map)->count();
		$p = new Page ( $count, 5 );
		$list=$msg->where($map)->order('time desc')->limit($p->firstRow.','.$p->listRows)->select();
		$p->setConfig('header','条记录');
		$page = $p->show ();
		$this->assign( "page", $page );
		$this->assign('list',$list);
		$this->display('index');
	}
	function outbox(){
		$uid=$_SESSION[C('USER_AUTH_KEY')];
		if(!$uid) $this->error('您没有登录或者登录超时！');
		import("@.ORG.Page");
		$msg=D('Message');
		$map['from']=$uid;
		$count = $msg->where($map)->count();
		$p = new Page ( $count, 5 );
		$list=$msg->where($map)->order('time desc')->limit($p->firstRow.','.$p->listRows)->select();
		$p->setConfig('header','条记录');
		$page = $p->show ();
		$this->assign( "page", $page );
		$this->assign('list',$list);
		$this->display('outbox');
	}
	function view(){
		$uid=$_SESSION[C('USER_AUTH_KEY')];
		if(!$uid) $this->error('您没有登录或者登录超时！');
		$mid=$_GET['mid'];
		if(!$mid) $this->error('请选择需要阅读的短信！');
		$msg=D('Message');
		$set['status']='1';
		$map['id']=$mid;
		$msg->where('id='.$mid)->save($set);
		$list=$msg->where($map)->find();
		if(!$list) $this->error('短信不存在！');
		if($list['to']!==$uid&&$list['from']!==$uid) $this->error('您无权阅读该短信！');
		$this->assign($list);
		$this->display('view');
	}
	
	function send(){
		$uid=$_SESSION[C('USER_AUTH_KEY')];
		if(!$uid) $this->error('您没有登录或者登录超时！');
		if($_POST['submit']){
		    $send=D('Message');
		    //$_POST['to']=$this->get_uid($_POST['to']);
		    if($_POST['to']==$uid) $this->error('不能给自己发短信！');
		    if(!$send->create()){
			    $this->error($send->getError());
		    }elseif($result=$send->add()){
			    $this->success('信息发送成功！');
		    }else{
			    $this->error('信息发送失败！');
		    }
		}else{
			$to=$_GET['to'];
			$this->assign('to',$to);
			$this->display('send');
		}
	}
	function del(){
		$uid=$_SESSION[C('USER_AUTH_KEY')];
		if(!$uid) $this->error('您没有登录或者登录超时！');
		$mid=$_POST['del_msgid'];
		$d=D('Message');
		$map['to']=$uid;
		if(is_array($mid)){
			foreach($mid as $k=>$v){
				$map['id']=$v;
				$d->where($map)->delete();
			}
		}else{
			$map['id']=$mid;
			$d->where($map)->delete();
		}
		$this->success('短信删除成功！');
		
	}
	function get_uid($username){
		$m=D('member');
		$map['username']=$username;
		if($rs=$m->where($map)->find()){
			return $rs['uid'];
		}else{
			$this->error('收件人不存在！');
		}
	}
}
?>
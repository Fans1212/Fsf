<?php
class CollectAction extends CommonAction{
	function add(){
		$_uid=is_login();
		if(!$_uid) $this->error('请登录后操作！');
		$m=D('collect');
		$data['be_collect']=$_GET['uid'];
		$data['time']=time();
		$data['uid']=$_uid;
		$map['uid']=$_uid;
		$map['be_collect']=$_GET['uid'];		
		if($rs=$m->where($map)->find()){
			$this->error('你已经收藏过该会员！');			
		}else{
			if($rs=$m->add($data)){
				$this->success('成功收藏该会员！');
			}else{
				$this->error('收藏失败！');
			}
		}
	}
	
	function myCollect(){
		$_uid=is_login();
		if(!$_uid) $this->error('请登录后操作！');
		import("@.ORG.Page");
		$m=D('collect');
		$map['uid']=$_uid;
		$count = $m->where($map)->count();
		$p = new Page ( $count, 15 );
		$list=$m->where($map)->order('time desc')->limit($p->firstRow.','.$p->listRows)->select();
		foreach($list as $k=>$v){
			$infos=$this->userpro($v['be_collect']);
			$list[$k]['gender']=$infos['gender'];
			$list[$k]['age']=$infos['age'];
			$list[$k]['city']=$infos['city'];			
		}
		$page = $p->show ();
		$this->assign( "page", $page );
		$this->assign('list',$list);
		$this->display('Member:collect');
	}
	function collectme(){
		$_uid=is_login();
		if(!$_uid) $this->error('请登录后操作！');
		import("@.ORG.Page");
		$m=D('collect');
		$map['be_collect']=$_uid;
		$count = $m->where($map)->count();
		$p = new Page ( $count, 15 );
		$list=$m->where($map)->order('time desc')->limit($p->firstRow.','.$p->listRows)->select();
		foreach($list as $k=>$v){
			$infos=$this->userpro($v['be_collect']);
			$list[$k]['gender']=$infos['gender'];
			$list[$k]['age']=$infos['age'];
			$list[$k]['city']=$infos['city'];			
		}
		$page = $p->show ();
		$this->assign( "page", $page );
		$this->assign('list',$list);
		$this->display('Member:collect');
	}
	
	function del(){
		$uid=$_SESSION[C('USER_AUTH_KEY')];
		if(!$uid) $this->error('您没有登录或者登录超时！');
		$id=$_POST['del_id'];
		$m=D('collect');
		$map['uid']=$uid;
		if(is_array($id)){
			foreach($id as $k=>$v){
				$map['id']=$v;
				$m->where($map)->delete();
			}
		}else{
			$map['id']=$id;
			$m->where($map)->delete();
		}
		$this->success('删除成功！');
		
	}
	
	function userpro($uid){
		$m=M('member_detail');
		$rs=$m->field('gender,age,city')->where('uid='.$uid)->find();
		$rs['city']=areaid($rs['city']);
		return $rs;
	}
}
?>
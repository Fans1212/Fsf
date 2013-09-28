<?php
class AlbumAction extends CommonAction{
	function index(){
		$uid=$_SESSION[C('USER_AUTH_KEY')];
		if(!$uid) $this->error('您没有登录或者登录超时！');
		$album=D('album');
		$list=$album->where('uid='.$uid)->order('albumid desc')->findAll();
		$this->assign('list',$list);
		$this->display();
	}

	function update(){
		$uid=$_SESSION[C('USER_AUTH_KEY')];
		if(!$uid) $this->error('您没有登录或者登录超时！');
		$album=D('album');
		if($delpic=$_POST['delpic']){
			foreach($delpic as $k=>$v){
				$map['albumid']=$v;
				$map['uid']=$uid;
				$rs=$album->where($map)->find();
				$picfile=$rs['pic'];
				@unlink($picfile);
				$album->where($map)->delete();
			}
			$this->success('照片删除成功');
		}
		$albumname=$_POST['albumname'];
		$picprice=$_POST['picprice'];
		foreach($albumname as $k=>$v){
			$map['albumname']=$v;
			$map['picprice']=$picprice[$k];
			$album->where('albumid='.$k)->save($map);
		}
		$this->success('相片更新成功！');	
	}
	function del(){
		$uid=$_SESSION[C('USER_AUTH_KEY')];
		if(!$uid) $this->error('您没有登录或者登录超时！');
		$album=D('album');
		$aid=$_GET['id'];
		if($aid<0) $this->error('该相片不存在！');
		$map['albumid']=$aid;
		$map['uid']=$uid;
		$rs=$album->where($map)->find();
		$picfile=$rs['pic'];
		@unlink($picfile);
		$album->where($map)->delete();
		$this->success('照片删除成功');
	}
	function view(){
		$aid=$_GET['aid'];
		if(!is_numeric($aid)) $this->error('您查看的相片不存在！');
		$album=D('album');
		$show=$album->where('albumid='.$aid)->find();
		if($show){
			$this->assign($show);
			$this->display();
		}else{
			$this->error('您查看的相片不存在或者已经被删除！');
		}
		
	}
}

?>
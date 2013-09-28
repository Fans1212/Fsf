<?php
class AdminModAction extends AdminAction{
	//日记管理
	function diary(){
		$diary=D('diary');
		$keyword=$_POST['keyword'];
		if($keyword){
			if($_POST['field']=='uid'||$_POST['field']=='username') $map[$_POST['field']]=$keyword;
			if($_POST['field']=='title') $map['diarytitle']=array('like',"%$keyword%");
		}
		if($_GET['uid']) $map['uid']=$_GET['uid'];
		$pm=$_GET;
		if($pm['cat']) $map['cateid']=$pm['cat'];
		if($pm['uid']) $map['uid']=$pm['uid'];
		if($pm['username']) $map['username']=$pm['username'];
        import("@.ORG.Page");
		$count = $diary->where($map)->count();
		$p = new Page ( $count, 20 );
		$list=$diary->where($map)->order('id desc')->limit($p->firstRow.','.$p->listRows)->select();
		$p->setConfig('header','条记录');
		$page = $p->show ();
		$this->assign( "page", $page );
		$this->assign('list',$list);
		$this->display(APP_PATH.'/Public/Admin/diary.html');
	}
	function deldiary(){
		$diary=D('diary');
		$did=$_POST['diaryid'];
		foreach ($did as $v){
			$diary->where('id='.$v)->delete();
		}
		$this->success('日记删除成功！');
	}
	function diary_audit(){
		$diary=D('diary');
		$did=$_POST['diaryid'];
		$data['stat']=1;
		foreach ($did as $v){
			$diary->where('id='.$v)->data($data)->save();
		}
		$this->success('日记审核成功！');
	}
	//站内信息管理
	function message(){
		$m=D('message');
	    $keyword=$_POST['keyword'];
		if($keyword){
			if($_POST['field']=='from') $map['from']=$keyword;
			if($_POST['field']=='title') $map['m_title']=array('like',"%$keyword%");
		}
		import("@.ORG.Page");
		$count = $m->where($map)->count();
		$p = new Page ( $count, 20 );
		$list=$m->where($map)->order('time desc')->limit($p->firstRow.','.$p->listRows)->select();
		$page = $p->show ();
		$this->assign( "page", $page );
		$this->assign('list',$list);
		$this->display(APP_PATH.'/Public/Admin/message.html');
	}
	function redmsg(){
		$m=D('message');
		$mid=$_GET['mid'];
		if($rs=$m->where('id='.$mid)->find()){
			$this->assign($rs);
			$this->display(APP_PATH.'/Public/Admin/message.html');
		}else{
			$this->assign('jumpUrl','__URL__/message');
			$this->error('短信不存在！');
		}
	}
	function delmessage(){
	    $m=D('message');
	    $mid=$_POST['mid'];
	    foreach ($mid as $v){
	    	$m->where('id='.$v)->delete();
	    }
	    $this->success('短信删除成功！');
	}
	//相片管理
	function album(){
		$a=D('album');
		import("@.ORG.Page");
		$map=array();
		$stat=$_GET['stat'];
		$keyword=$_POST['keyword'];
		if($keyword){
			$map[$_POST['field']]=$keyword;
		}
		if($_GET['uid']) $map['uid']=$_GET['uid'];
		if($stat) $map['status']='0';
		$count = $a->where($map)->count();
		$p = new Page ( $count, 27 );
		$list=$a->where($map)->order('albumid desc')->limit($p->firstRow.','.$p->listRows)->select();
		$page = $p->show ();
		$this->assign( "page", $page );
		$this->assign('list',$list);
		$this->display(APP_PATH.'/Public/Admin/album.html');
	}
	function delpic(){
	    $m=D('album');
	    $albumid=$_POST['albumid'];
	    foreach ($albumid as $v){
	    	$map['albumid']=$v;
			$rs=$m->where($map)->find();
			$picfile=$rs['pic'];
			@unlink($picfile);
	    	$m->where('albumid='.$v)->delete();
	    }
	    $this->success('相片删除成功！');
	}
	function auditpic(){
	    $album=D('album');
	    $albumid=$_POST['albumid'];
		$data['status']=1;
	    foreach ($albumid as $v){
	    	$map['albumid']=$v;
	    	$album->where($map)->data($data)->save();
	    }
	    $this->success('相片审核成功！');
	}
	//头像管理
	function avatar(){
		$a=D('attachment');
		import("@.ORG.Page");
		$map=array();
		$keyword=$_POST['keyword'];
		if($keyword){
			$map[$_POST['field']]=$keyword;
		}
		$map['model']='avatar';
		$count = $a->where($map)->count();
		$p = new Page ( $count, 27 );
		$list=$a->where($map)->order('create_time desc')->limit($p->firstRow.','.$p->listRows)->select();
		$page = $p->show ();
		$this->assign( "page", $page );
		$this->assign('list',$list);
		$this->display(APP_PATH.'/Public/Admin/avatar.html');
	}
	function delavatar(){
	    $m=D('attachment');
	    $id=$_REQUEST['id'];
	    if(is_array($id)){
	    foreach ($id as $v){
	    	$map['id']=$v;
			$rs=$m->where($map)->find();
			$picfile=$rs['image'];
			@unlink($picfile);
	    	$m->where('id='.$v)->delete();
	    	$member=D('member_detail');
	    	$uid=$rs['uid'];
	    	$data['avatar']='0';
	    	$member->where('uid='.$uid)->save($data);
	    }
	    }else{
	    	$map['id']=$id;
			$rs=$m->where($map)->find();
			$picfile=$rs['image'];
			@unlink($picfile);
			$uid=$rs['uid'];
	    	$data['avatar']='0';	
	    	$mem=M('member_detail');
	    	$mem->where("uid=$uid")->save($data);
	    	$m->where('id='.$id)->delete();
	    }
	    $this->success('头像删除成功！');
	}
	//网站公告
	function announce(){
		$m=D('announce');
		import("@.ORG.Page");
		$map['model']='avatar';
		$count = $m->count();
		$p = new Page ( $count, 27 );
		$list=$m->order('addtime desc')->limit($p->firstRow.','.$p->listRows)->select();
		$page = $p->show ();
		$this->assign( "page", $page );
		$this->assign('list',$list);
		$this->display(APP_PATH.'/Public/Admin/announce.html');
	}
	function addannounce(){
		if($_POST['submit']){
			$m=M('announce');
			if($_POST['title']=='') $this->error('公告标题不能为空！');
			if($_POST['content']=='') $this->error('公告内容不能为空！');
			$data['title']=$_POST['title'];
			$data['content']=$_POST['content'];
			if($edit=$_POST['edit']){
				$aid=$_POST['aid'];
				$m->where('aid='.$aid)->save($data);
				$this->success('编辑公告成功！');
			}else{
				$data['addtime']=time();
			    if($rs=$m->add($data)){
				    $this->success('添加公告成功！');
			    }else{
				    $this->error('公告添加失败！');
			    }
			}
		}else{
		    $this->display(APP_PATH.'/Public/Admin/announce.html');
		}
	}
	function editan(){
		$aid=$_GET['aid'];
		$m=D('announce');
		$rs=$m->where('aid='.$aid)->find();	
		$this->assign('edit','1');
		$this->assign($rs);
		$this->display(APP_PATH.'/Public/admin/Announce.html');
	}
	function delan(){
		$m=M('announce');
		$aid=$_POST['aid'];
		foreach($aid as $v){
			$m->where('aid='.$v)->delete();
		}
		$this->success('公告删除成功！');
	}
	//友情链接
	function links(){
		$m=D('links');
		$rs=$m->findAll();
		$this->assign('list',$rs);
		$this->display(APP_PATH.'/Public/Admin/links.html');
	}
	function addlinks(){
		$m=D('links');
		if($m->create()){
			$m->add();
			$this->success('添加友情链接成功！');
		}else{
			$this->error('友情链接添加失败！');
		}
	}
	function dellinks(){
		$m=D('links');
		$id=$_GET['id'];
		$m->where('id='.$id)->delete();
		$this->success('友情链接删除成功！');
	}
	//财务管理
	function paylist(){
		$field=$_GET['field'];
		$keyword=$_GET['keyword'];
	    if($field=='username'){
	    	$field='uid';
			$keyword=get_uid($keyword);
		}
		if($field){
			$map[$field]=$keyword;
		}else{
			$map['id']=array('gt',0);
		}
		$m=D('order');
		import("@.ORG.Page");
		$count = $m->where($map)->count();
		$p = new Page ( $count, 20 );
		$list=$m->where($map)->order('time desc')->limit($p->firstRow.','.$p->listRows)->select();
		$page = $p->show ();
		$this->assign( "page", $page );
		$this->assign('list',$list);
		$this->display(APP_PATH.'/Public/Admin/mod_paylist.html');
	}
	//审核订单
	function pay_audit(){
		import('@.ORG.Pay');
		$pay=new pay();
		$id=$_REQUEST['orderid'];
		if(is_array($id)){
			foreach($id as $v){
				$pay->changeorder($v);
			}
		}else{
			$pay->changeorder($id);
		}
		$this->success('订单审核成功！');
	}
	function pay_del(){//删除订单
		import('@.ORG.Pay');
		$pay=new pay();
		$id=$_REQUEST['orderid'];
		if(is_array($id)){
			foreach($id as $v){
				$pay->del($v);
			}
		}else{
			$pay->del($id);
		}
		$this->success('订单删除成功！');
	}
	//添加财务
	function pay_add(){
		if($_POST['submit']){
			$field=$_POST['field'];
			if($field=='uid'){
				$uid=$_POST['user'];
			}else{
				$uid=get_uid($_POST['user']);
			}
			$amount=$_POST['amount'];
			$type=$_POST['type'];
			if(!$uid||!$amount) $this->error('请填写用户名及金额！');
			import('@.ORG.Pay');
			$pay=new pay();
			$pay->add($uid,$amount,$type);
			$this->success('给会员充值成功！');
		}else{
			$this->display(APP_PATH.'/Public/Admin/mod_payadd.html');
		}
	}
	function paycharge(){
		$field=$_GET['field'];
        $uid=$_GET['uid'];
        if($field=='username') $uid=get_uid($uid);
		if($uid){
			$map['uid']=$uid;
		}else{
			$map['id']=array('gt',0);
		}
		$m=D('charge');
		import("@.ORG.Page");
		$count = $m->where($map)->count();
		$p = new Page ( $count, 20 );
		$list=$m->where($map)->order('time desc')->limit($p->firstRow.','.$p->listRows)->select();
		$page = $p->show ();
			foreach($list as $k=>$v){
			if($v['account']=='0'){
				$list[$k]['type']='资金';
				$list[$k]['in']='<font color=green>支出</font>';
			}elseif($v['account']=='1'){
				$list[$k]['type']='资金';
				$list[$k]['in']='<font color=red>收入</font>';
			}elseif($v['account']=='2'){
				$list[$k]['type']='E币';
				$list[$k]['in']='<font color=red>收入</font>';
			}elseif($v['account']=='3'){
				$list[$k]['type']='E币';
				$list[$k]['in']='<font color=green>支出</font>';
			}
		}
		$this->assign( "page", $page );
		$this->assign('list',$list);
		$this->display(APP_PATH.'/Public/Admin/mod_paycharge.html');
	}
}
?>
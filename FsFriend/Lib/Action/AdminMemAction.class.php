<?php
class AdminMemAction extends AdminAction{
	function Member(){
		$m=D('member');
		import("@.ORG.Page");
		$count=$m->count();
		$p=new Page($count,20);
		$list=$m->order('uid desc')->limit($p->firstRow.','.$p->listRows)->select();
		$page = $p->show ();
		foreach($list as $k=>$v){
			$uid=$list[$k]['uid'];
			$list[$k]['status']=$this->isaudit($uid);
		}
		$this->assign('page',$page);
		$this->assign('list',$list);
		$this->display(APP_PATH.'/Public/Admin/member.html');
	}
	function search(){
		$field=$_GET['field'];
		$keyword=$_GET['keyword'];
		$map=array();
		$m=D('member');
		if($field=='uid'){
			$map['uid']=$keyword;
		}else{
			$map[$field]=array('like',"%$keyword%");
		}
		import("@.ORG.Page");
		$count=$m->where($map)->count();
		$p=new Page($count,20);
		$list=$m->where($map)->order('uid desc')->limit($p->firstRow.','.$p->listRows)->select();
		$page = $p->show ();
		$this->assign('page',$page);
		$this->assign('list',$list);
		$this->display(APP_PATH.'/Public/Admin/member.html');
	}
	function view(){
		$m=M("member");
		$uid=$_GET['uid'];
		if($uid<0) $this->error('请选择有效用户！');
		$info=$m->where('uid='.$uid)->find();
		$d=M('member_detail');
		if($detail=$d->where('uid='.$uid)->find()){
			import("@.Cache.Member_output");
			$member_output = new member_output($uid);
            $forminfos = $member_output->get($detail);
			foreach($forminfos as $k=>$v){
				    $infos[$k]=array('name'=>$k,'v'=>$v);
			}
			$this->assign('infos',$infos);
		}else{
			$this->error('没有该用户或者已经被禁止！');
		}
		$this->assign('isaudit',$this->isaudit($uid));
		$this->assign('avatar',$detail['avatar']);
		$this->assign('gender',$detail['gender']);
		$this->assign($info);
		$this->display(APP_PATH.'/Public/Admin/member_view.html');
	}
	function lock(){
		$m=M('member');
		$uid=$_REQUEST['uid'];
		if(is_array($uid)){
			foreach($uid as $v){
				$rs=$m->where('uid='.$v)->find();
		        if($rs['islock']=='0'){
			         $islock='1';
		        }elseif($rs['islock']=='1'){
			         $islock='0';
		        }
		        $data['islock']=$islock;
				$m->where('uid='.$v)->save($data);
			}
			$this->success('批量禁止会员成功！');
		}else{
		    $rs=$m->where('uid='.$uid)->find();
		    if($rs['islock']=='0'){
			    $islock='1';
			    $ac="禁止";
		    }elseif($rs['islock']=='1'){
			    $islock='0';
			    $ac="恢复";
		    }
		    $data['islock']=$islock;
		    if($rs2=$m->where('uid='.$uid)->save($data)){
			    $this->success($ac.'会员【'.$rs['username'].'】成功！');
		}
		}
	}
	//审核会员
	function audit(){
		$m=M('member_detail');
		$uid=$_REQUEST['uid'];
		$data['status']='1';
		if(is_array($uid)){
			foreach($uid as $v){
				$m->where('uid='.$v)->save($data);
			}	
		}else{
			$m->where('uid='.$uid)->save($data);
		}
		$this->success('会员审核成功！');
	}
	//编辑会员
	function edit(){
		$uid=$_REQUEST['uid'];
		$info=D('member');
		$infos=$info->where("uid=$uid")->find();
		$this->assign($infos);
		$profile=D('Member_detail');
		$data=$profile->where("uid=$uid")->find();
		if($_POST['submit']){
			if($_POST['pwd']){
				if($_POST['pwd']!==$_POST['pwd2']) $this->error('两次密码输入不一致!');
				$field['password']=md5($_POST['pwd'].C('SAFE_CODE'));
				
			}
			if(!empty($_POST['username'])&&$_POST['username']!==$infos['username']) $field['username']=$_POST['username'];
			if(!empty($_POST['email'])&&$_POST['email']!==$infos['email']) $field['email']=$_POST['email'];
			$info->where("uid=$uid")->save($field);
			$this->mform_in($profile);
			$result=$profile->where("uid=$uid")->save();
			if(false !== $result) {
			     $this->success('资料修改成功！');
		    }else{
			     $this->error('资料修改失败!');
		    }
		}else{
		    import ( '@.Cache.Form' );
		    import ('@.Cache.Member_form');
		    $member_form = new member_form();
            $forminfos = $member_form->getform($data);
            $this->assign('pro',$data['province']);
            $this->assign('city',$data['city']);
            $this->assign('forminfos',$forminfos);
		    $this->display(APP_PATH.'/Public/Admin/member_edit.html');
		}
	}
	//删除会员
	function delmember(){
		$uid=$_REQUEST['uid'];
		$member=M('member');
		$detail=M('member_detail');
		if(is_array($uid)){
			foreach($uid as $v){
				$map['uid']=$v;
                $this->del_attachment($map);
                $this->del_album($map);
                $this->del_diary($map);
				$member->where($map)->delete();
				$detail->where($map)->delete();
			}	
		}else{
			$map['uid']=$uid;
            $this->del_attachment($map);
            $this->del_album($map);
            $this->del_diary($map);
			$member->where($map)->delete();
			$detail->where($map)->delete();
		}
		$this->assign('jumpUrl','__URL__/member');
		$this->success('会员删除成功！');
	}
	function isaudit($uid){//会员是否审核
		$m=M('member_detail');
		$rs=$m->where('uid='.$uid)->field('status')->find();
		if($rs['status']){
			return '<font color=green>已审核</font>';
		}else{
			return '未审核';
		}
	}
	function del_diary($map){//删除日记
		$diary=M('diary');
		$diary->where($map)->delete();
	}
	function del_album($map){//删除相片
		$album=M('album');
		if($rs=$album->where($map)->findAll()){
			foreach($rs as $k=>$v){
				$picfile=$rs[$k]['pic'];
				@unlink($picfile);				
			}
			$album->where($map)->delete();
		}
	}
	function del_attachment($map){//删除附件
		$att=M('attachment');
		if($rs=$att->where($map)->findAll()){
			foreach($rs as $k=>$v){
				$picfile=$rs[$k]['image'];
				@unlink($picfile);			
			}
			$att->where($map)->delete();
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
	//会员组
	function group(){
		$m=D('member_group');
		$list=$m->findAll();
		foreach($list as $k=>$v){
			$num=D('Member');
			$map['group']=$v['groupid'];
			$list[$k]['num']=$num->where($map)->count();
		}
		$this->assign('list',$list);
		$this->display(APP_PATH.'/Public/Admin/member_group.html');
	}
	function groupadd(){//添加会员组
		if($_POST['submit']){
			$m=D('member_group');
			if($m->create()){
				$rs=$m->add();
				if($rs){
					$this->success('添加会员组成功！');
				}else{
					$this->error('添加会员组失败！');
				}
			}else{
				$this->error('添加会员组失败！');
			}
		}else{
        
		    $this->assign('list',$list);
		    $this->display(APP_PATH.'/Public/Admin/member_groupadd.html');
		}
	}
	function groupedit(){
		$gid=$_GET['gid'];
		$m=D('member_group');
		$rs=$m->where('groupid='.$gid)->find();
		$this->assign($rs);
		$this->display(APP_PATH.'/Public/Admin/member_groupadd.html');
	}
	function groupsave(){
		if(IS_VIPVAR==true){
		$gid=$_POST['gid'];
		$m=D('member_group');
		if($m->create()){
			$m->save();
			$this->success('会员组修改成功！');
		}else{
			$this->error('会员组修改失败！');
		}
		}else{
			$this->success('请购买商业版本！');	
		}
	}
	function groupdo(){
		$gid=$_GET['gid'];
		$m=D('member_group');
		if($_GET['go']=='del'){
		    $m->where('groupid='.$gid)->delete();
		    $this->success('会员组删除成功！');
		}elseif($_GET['go']=='dis'){
            $data['disabled']=$_GET['v'];
			$m->where('groupid='.$gid)->save($data);
			$this->success('会员组修改成功！');
		}	
	}
	//更改会员的所在组
	function chgroup(){
	      $uid=$_GET['uid'];
	      $m=D('member');
	      if($_POST['submit']){
			  if(IS_VIPVAR==true){
	      	$data['group']=$_POST['group'];
	      	$data['upgrade_time']=time();
	      	$data['upgrade_end']=fomattime($_POST['upgrade_end']);
	      	$m->where('uid='.$uid)->save($data);
	      	$this->success('会员组修改成功！');
			  }else{
				 $this->success('请购买商业版本！'); 
			  }
	      }else{
	      	$info=$m->find($uid);
	      	$this->assign($info);
	      	$this->display(APP_PATH.'/Public/Admin/member_chgroup.html');
	      }
	}
	//举报管理
	function report(){
		$m=D('report');
		if($_POST['submit']){
			$id=$_POST['id'];
			foreach($id as $v){
				$map['id']=$v;
				$m->where('$map')->delete();
			}
			$this->success('举报信息删除成功！');
		}else{
		import("@.ORG.Page");
		$count=$m->count();
		$p=new Page($count,20);
		$list=$m->order('id desc')->limit($p->firstRow.','.$p->listRows)->select();
		$page = $p->show ();
		$this->assign('page',$page);
		$this->assign('list',$list);
		$this->display(APP_PATH.'/Public/Admin/member_report.html');
		}
	}
	//发送邮件
	function sendmsg(){
		if($_POST['submit']){
			$to=$_POST['tomail'];
			$title=$_POST['mail_title'];
			$content=$_POST['mail_content'];
			import('@.ORG.Email');
			$config=C('EMAIL');
			Email::init($config);
			Email::send($to,$title,$content);
			$this->success('邮件发送成功！');
		}else{
			
			$this->display(APP_PATH.'/Public/Admin/member_sendmsg.html');
		}
		
	}
}
?>
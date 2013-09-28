<?php
class DiaryAction extends CommonAction{
	function index(){
		$cateid=$_GET['cateid'];
		if($cateid) $map['cateid']=$cateid;
		$map['stat']='1';
		$diary=D('diary');
		import("@.ORG.Page");
		$count = $diary->where($map)->count();
		$p = new Page ( $count, 10 );
		$order='id desc';
		$list=$diary->where($map)->order($order)->limit($p->firstRow.','.$p->listRows)->select();
		$page = $p->show();
		$detail=M('member_detail');	
		foreach($list as $k=>$v){
			$list[$k]['diarytxt']=filterReplace(strip_tags($v['diarytxt']));
			$list[$k]['diarytitle']=filterReplace(strip_tags($v['diarytitle']));
			$list[$k]['cate']=$this->catname($v['cateid']);
			$info=$detail->find($v['uid']);
			$list[$k]['city']=areaid($info['city']);
			$list[$k]['gender']=$info['gender'];
			$list[$k]['age']=$info['age'];
			$list[$k]['nickname']=filterReplace($info['nickname']);
			if(!$v['pic']) $list[$k]['pic']=avatar($info['avatar'],$info['gender']);			
		}
		$area=get_area('');
		$this->assign('area',$area);
		$this->assign('qylist',A('Common'));
		$this->assign('page',$page);
		$this->assign('list',$list);
		$this->display();
	}
	function show(){
		$id=$_GET['id'];
		if(!is_numeric($id)) $this->error('请选择有效日记！');
		$diary=D('diary');
		$show=$diary->find($id);
		if($show){
		   $show['cateid']=$this->catname($show['cateid']);
		   $this->assign($show);
		   $this->display();
		}else{
	       $this->error('没有找到该日记！');
		}
	}
	function MyDiary(){
		$uid=$_SESSION[C('USER_AUTH_KEY')];
		if(!$uid) $this->error('您没有登录或者登录超时！');
		$diary=D('diary');
		$map['uid']=$uid;
		import("@.ORG.Page");
		$count = $diary->where($map)->count();
		$p = new Page ( $count, 10 );
		$list=$diary->where($map)->order('thedate desc')->limit($p->firstRow.','.$p->listRows)->select();
		$page = $p->show ();
		$this->assign( "page", $page );
		foreach($list as $k=>$v){
		    $list[$k]['cateid']=$this->catname($v['cateid']);	
		}
		$this->assign('list',$list);
		$this->display();
	}
	function add(){
		$diarycat=require LIB_PATH.'/Cache/Cat_diary.inc.php';
		$this->assign('cateid',$diarycat);
		$this->display();
	}
	function edit(){
		$did=$_GET['id'];
		if(!is_numeric($did)) $this->error('请选择有效日记！');
		$diary=D('diary');
		$list=$diary->find($did);
		//$list['diarytxt']=html_out('diarytxt');
		$diarycat=require LIB_PATH.'/Cache/Cat_diary.inc.php';
		$cateid='<select name="cateid" id="cateid" ><option value="" >选择分类</option>';
		foreach($diarycat as $k=>$v){
			$selected=$k==$list['cateid']?'selected':'';
			$cateid.="<option value=\"$k\" $selected>$v[name]</option>";
		}
		$cateid.='</select>';
		$this->assign('cate',$cateid);
		$this->assign($list);
		$this->display();
		
	}
	function update(){
		$diary=D('diary');
	    if (false === $diary->create ()) {
			$this->error ( $model->getError () );
		}
		//保存当前数据对象
		$list=$diary->save ();
		if ($list!==false) { //保存成功
			$this->assign ( 'jumpUrl', Cookie::get ( '_currentUrl_' ) );
			$this->success ('修改成功!');
		} else {
			//失败提示
			$this->error ('修改失败!');
		}
		
	}
	function insert(){
		//B('FilterString');
		$uid=$_SESSION[C('USER_AUTH_KEY')];
		if(!$uid) $this->error('您没有登录或者登录超时！');
		$model = D ('diary');
		if (false === $model->create ()) {
			$this->error ( $model->getError () );
		}
		//保存当前数据对象
		$list=$model->add ();
		if ($list!==false) { //保存成功
			$this->assign ( 'jumpUrl', Cookie::get ( '_currentUrl_' ) );
			$this->success ('发表日记成功!');
		} else {
			//失败提示
			$this->error ('发表日记失败!');
		}
	}
	function del(){
		$uid=$_SESSION[C('USER_AUTH_KEY')];
		if(!$uid) $this->error('您没有登录或者登录超时！');
		$id=$_GET['id'];
		if(!is_numeric($id)) $this->error('请选择需要删除的日记！');
		$model = D ('diary');
		$map['id']=$id;
		$map['uid']=$uid;
		$rs=$model->where($map)->delete();
	    if ($rs!==false) { 
			$this->assign ( 'jumpUrl', Cookie::get ( '_currentUrl_' ) );
			$this->success ('删除成功!');
		} else {
			//失败提示
			$this->error ('删除失败!');
		}
	}
	//取得分类名称
	function catname($cid){
		$diarycat=require LIB_PATH.'/Cache/Cat_diary.inc.php';
		return $diarycat[$cid]['name'];
	}
	
}
?>
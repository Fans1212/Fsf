<?php

class SearchAction extends CommonAction{
	
	function index(){
		$file=HTML_PATH.'search'.C('HTML_FILE_SUFFIX');
    	if(is_file($file)){
    		include $file;
    	}else{
    		$AREA=require LIB_PATH.'/Cache/province.php';
	        $data = "<select name=\"province\" id=\"province\" onchange=\"sfCity(this.value);\"><option value=\"0\">请选择</option>";
	        foreach($AREA as $k=>$v){   
		        $data .= "<option value=\"$k\">$v</option>\n";
	        }
	        $data .= '</select><select name="city" id="city">
                  <option value="0">请选择</option>
                 </select>';
	        $f_area=get_area('');
		    $this->assign('f_area',$f_area);
		    $this->assign('s_area',$data);
		    $this->display();
		    $this->buildHtml('search','','Search:index');
    	}
        
	}
	
	function base(){
		$mlist=M("Member_detail");
		import("@.ORG.Page");
		$map=$_REQUEST;
		if(!$map['city']&&!$map['dire']) $this->error('请选择需要搜索的城市！');
		if($map['username']) $where['nickname']=array('like',$map['username']);
		if($map['uid']) $where['uid']=$map['uid'];
		if($map['email']) $where['email']=$map['email'];
		if($map['qq']) $where['qq']=array('like',$map['qq']);
		if($map['mobile']) $where['mobile']=$map['mobile'];
		
		if($map['marry']) $where['marry']=$map['marry'];
		if($map['province']&&!$map['city']) $where['province']=$map['province'];
		if($map['city']) $where['city']=$map['city'];
		if($map['age1']&&!$map['age2']) $where['age']=array('egt',$map['age1']);
		if($map['age2']&&!$map['age1']) $where['age']=array('elt',$map['age2']);
		if($map['age1']&&$map['age2']) $where['age']=array("between","$map[age1],$map[age2]");
		if(isset($map['gender'])) $where['gender']=$map['gender'];		
		if($map['avatar']) $where['avatar']=array('gt',0);
		$where['updatetime']=array('lt',time()-864000);
		if($where['gender']==1) $where['updatetime']=array('lt',time()-1296000);
		if($ord=$_GET['o']){
			$order="$ord desc";
		}else{
			$order='updatetime desc';
		}
		$count = $mlist->where($where)->count();
		$p = new Page ( $count, 10 );
		$list=$mlist->field('uid,gender,avatar,nickname,marry,age,city,aboutme,datingfor')->where($where)->order($order)->limit($p->firstRow.','.$p->listRows)->select();
	    import("@.Cache.Member_output");
		$member_output = new member_output();
		foreach ($list as $k=>$v){
            $forminfos= $member_output->get_list($v);        
			foreach($forminfos as $key=>$val){
				if($key!=='gender') $list[$k][$key]=strip_tags($val);
            }
            $list[$k]['nickname']=filterReplace($list[$k]['nickname']);
            $list[$k]['aboutme']=filterReplace($list[$k]['aboutme']);
            $list[$k]['url_pro']='View/user/uid/'.$v['uid'].C('URL_HTML_SUFFIX');
		}
		$page = $p->show ();
		$area=get_area('');
		$this->assign('area',$area);
		$this->assign( "page", $page );
		$this->assign('list',$list);
		$this->assign('qylist',A('Common'));
		$this->display('list');
	}

	function advance(){
		$uid=is_login();
		if(!$uid) $this->error('请登录或者注册会员！');
		$member=M('member');
		$rs=$member->find($uid);
		if($rs['group']<7){
			$this->assign('jumpUrl',C('SITE_URL').'Pay/upgrade');
			$this->error('高级搜索功能仅限VIP会员使用！');
		}else{
			$mlist=M("Member_detail");
		    import("@.ORG.Page");
		    $map=$this->get_where();
		    if($ord=$_GET['o']){
			    $order="$ord desc";
		    }else{
			    $order='uid desc';
		    }
		    $count = $mlist->where($map)->count();
		    $p = new Page ( $count, 10 );
		    $list=$mlist->where($map)->order($order)->limit($p->firstRow.','.$p->listRows)->select();
            import("@.Cache.Member_output");
		    $member_output = new member_output();
		    foreach ($list as $k=>$v){
                $forminfos= $member_output->get_list($v);        
			    foreach($forminfos as $key=>$val){
				    if($key!=='gender') $list[$k][$key]=strip_tags($val);
                }
                $list[$k]['nickname']=filterReplace($list[$k]['nickname']);
                $list[$k]['aboutme']=filterReplace($list[$k]['aboutme']);
                $list[$k]['url_pro']='View/user/uid/'.$v['uid'].C('URL_HTML_SUFFIX');
		    }
		    $page = $p->show ();
		    $area=get_area('');
		    $this->assign('area',$area);
		    $this->assign( "page", $page );
		    $this->assign('list',$list);
		    $this->assign('qylist',A('Common'));
		    $this->display('list');
		}
		
	}
	//获取查询条件
	function get_where(){
		$where=array();
		$map=$_REQUEST;
		if($map['username']) $where['nickname']=$map['username'];
		if($map['uid']) $where['uid']=$map['uid'];
		if($map['email']) $where['email']=$map['email'];
		
		if($map['qq']) $where['qq']=array('like',$map['qq']);
		if($map['mobile']) $where['mobile']=$map['mobile'];
		//if($map['nickname']) $where['mobile']=$map['nickname'];
		if($map['marry']) $where['marry']=$map['marry'];
		if($map['province']&&!$map['city']) $where['province']=$map['province'];
		if($map['city']) $where['city']=$map['city'];
		if($map['avatar']) $where['avatar']=array('gt',0);
		
		if(isset($map['gender'])) $where['gender']=$map['gender'];
		if($map['age1']&&!$map['age2']) $where['age']=array('egt',$map['age1']);
		if($map['age2']&&!$map['age1']) $where['age']=array('elt',$map['age2']);
		if($map['age1']&&$map['age2']) $where['age']=array("between","$map[age1],$map[age2]");
		
		if($map['job']) $where['job']=$map['job'];
		if($map['figure']) $where['figure']=$map['figure'];
		if($map['height']) $where['height']=$map['height'];
		if($map['edu']) $where['edu']=array('egt',$map['edu']);
		if($map['income']) $where['income']=$map['income'];
		if($map['datingfor']) $where['datingfor']=$map['datingfor'];
		
		return $where;
	}
	//同城异性
	function local(){
		$uid=is_login();
		if(!$uid) $this->error('请登录或者注册会员！');
		$info=get_user_one($uid);
		@extract($info);
		if(intval($city)<1||!isset($gender)) $this->error('请完善你的个人资料！');
		$map=array();
		if($gender==1){
			$sex=0;
		}else{
			$sex=1;
		}
		$mlist=M('member_detail');
		$map['gender']=$sex;
		$map['city']=$city;
		$order='avatar desc,updatetime desc';
		import("@.ORG.Page");
	    $count = $mlist->where($map)->count();
		$p = new Page ( $count, 10 );
		$list=$mlist->where($map)->order($order)->limit($p->firstRow.','.$p->listRows)->select();
        import("@.Cache.Member_output");
	    $member_output = new member_output();
		    foreach ($list as $k=>$v){
                $forminfos= $member_output->get_list($v);        
			    foreach($forminfos as $key=>$val){
				    if($key!=='gender') $list[$k][$key]=strip_tags($val);
                }
                $list[$k]['nickname']=filterReplace($list[$k]['nickname']);
                $list[$k]['aboutme']=filterReplace($list[$k]['aboutme']);
                $list[$k]['url_pro']='View/user/uid/'.$v['uid'].C('URL_HTML_SUFFIX');
		    }
		    $page = $p->show ();
		    $area=get_area('');
		    $this->assign('area',$area);
		    $this->assign( "page", $page );
		    $this->assign('list',$list);
		    $this->assign('qylist',A('Common'));
		    $this->display('list');
	}
}

?>
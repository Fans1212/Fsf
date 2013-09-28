<?php
class AdminConAction extends AdminAction{
	function add_rule(){
		$step=$_POST['step'];
		if ($step=='1')
		{   
			$setting=$_POST['setting'];
			if(empty($setting['name']))
			{
				$this->error('请填写配置名称！');
			}
			$type=str_replace(C('DB_PREFIX'), '', $setting['in_table']);
			$rs=D($type);
			$in_field=$rs->getDbFields();
			foreach($in_field as $k=>$v){
				if(!is_int($k)) {break;};
				$in_fields[$k]=$v;
			}
			if($type=='member'){
				$fields =require LIB_DIR.'/Cache/Member_fields.inc.php';
				foreach($fields as $k=>$v){
					$detail_field[$k]=$v['field'];
				}
				$in_fields=array_merge($in_fields,$detail_field);
			}
			if($edit=$_POST['edit']) $arr=require LIB_PATH.'Cache/import_'.$setting['name'].'.php';
			$this->assign($setting);
			$this->assign('arr',$arr);
			$this->assign('in_field',$in_fields);
			$this->display(APP_PATH.'/Public/Admin/import_add_s.html');
		}elseif($step=='2'){
		    $setting=$_POST['setting'];
			if(empty($setting['name']))
			{
				$this->error('请填写配置名称！');
			}
			arr2file(LIB_DIR.'/Cache/import_'.$setting['name'].'.php',$setting);
			$this->assign('jumpUrl',__URL__.'/import');
			$this->success('数据导入配置保存成功！');
		}else{
			$name=$_GET['name'];
		    if($name) {
		    	$arr=require LIB_PATH.'Cache/import_'.$name.'.php';
		    	$arr['edit']='1';
		    }
			$rs = new Model();
		    $result=$rs->query('SHOW TABLES FROM '.C('db_name'));
		    $info=array();
            foreach ($result as $key => $val) {
               $info[$key] = current($val);
            }
            $this->assign($arr);
		    $this->assign('table',$info);
		    $this->display(APP_PATH.'/Public/Admin/import_add.html');
		}
		
	}
	function import(){
		$file = LIB_PATH.'Cache/import_*.php';
		$files = glob($file);
		foreach($files as $k=>$v){
			$im_list[$k]=require $v;
		}
		$this->assign('list',$im_list);
		$this->display(APP_PATH.'/Public/Admin/import.html');
	}
	//修改配置
	function edit(){
		$name=$_GET['name'];
		$arr=require LIB_PATH.'Cache/import_'.$name.'.php';
		$rs = new Model();
		$result=$rs->query('SHOW TABLES FROM '.C('db_name'));
		$info=array();
        foreach ($result as $key => $val) {
            $info[$key] = current($val);
        }
        $this->assign($arr);
		$this->assign('table',$info);
		$this->display(APP_PATH.'/Public/Admin/import_add.html');
	}
	function run(){
		$name=$_GET['name'];
		$total=$_GET['total'];
		$offset=$_GET['offset'];
		$fas1=$_GET['fas'];		
		$info=require LIB_PATH.'Cache/import_'.$name.'.php';
		$offset=isset($offset) ? intval($offset) : $info['last_id'] ;
		$number=$info['each_num'];
		$mod=$info['in_table'];
		import('@.ORG.import');
		$import=new import();
		if($mod=='member'){
			$result=$import->convert_member($info,$offset);
		}else{
			$result=$import->convert_diy($info,$offset);
		}		
		list($finished, $total,$fas) = explode('-', $result);
		$newfas=$fas+$fas1;
		$newoffset = $offset + $number;
		$start = $newoffset + 1;
		$end = $finished ? ($offset + $importnum) : $newoffset+$number;
		if($finished){
			unset($_SESSION['suc_num']);
			$url=__URL__.'/import';
		}else{
			$url=__URL__.'/run/name/'.$name.'/offset/'.$newoffset.'/total/'.$total.'/fas/'.$newfas;
		}
		$message='总数'.$total.',成功：'.$_SESSION[suc_num].',失败：'.$newfas.'<br />正在导入第'.$start.'至第'.$end.'条数据！';
		$this->assign('jumpUrl',$url);
		$this->success($message);
		
	}
	function get_field(){
        $g=$_GET;
        $dbhost=$g['dbhost'];
        $dbuser=$g['dbuser'];
        $dbtype=$g['dbtype'];
        $dbpw=$g['dbpw'];
        $dbname=$g['dbname'];
        $charset=$g['charset'];
        $tables=$g['tables'];
		if (empty($dbhost) || empty($dbuser) || empty($dbtype) || empty($dbpw) || empty($dbname) || empty($charset) || empty($tables)) 
			{
				exit();
			}
			import ( '@.ORG.import' );
			$import=new import();
			$db_table = explode(',', $tables);
			$this_db = $import->connect_db($dbtype, $dbhost, $dbuser, $dbpw, $dbname, $charset);
			foreach ($db_table as $key=>$val)
			{
				$r[$val] = $this_db->get_fields($val);
			}
			$html = '<select onchange="if(this.value!=\'\'){put_fields(this.value)}"><option value="">请选择</option>';
			foreach ($r as $key=>$val)
			{
				foreach ($val as $v)
				{
					$html .= '<option value="'.$v.'">'.$key.'.'.$v.'</option>';
				}
			}
			echo $html.'</select>';
	}
}
?>
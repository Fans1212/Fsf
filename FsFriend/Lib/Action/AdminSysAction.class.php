<?php
ini_set('memory_limit', '-1');//取消内存限制
class AdminSysAction extends AdminAction{
	// 配置信息
    public function config(){
		$config=require CONF_DIR.'/setting.php';
		$this->assign($config);
        $this->display(APP_PATH.'/Public/Admin/config.html');
    }
    function configSave(){
    	$config=$_POST['config'];
    	$config['SITE_STATS']=stripslashes($config['SITE_STATS']);
    	$config['SITE_COPYRIGHT']=stripslashes($config['SITE_COPYRIGHT']);
    	$array=require CONF_DIR.'/setting.php';
		$new=array_merge($array,$config);
		arr2file(CONF_DIR.'/setting.php',$new);
		@unlink(APP_PATH.'/Runtime/~app.php');
		$this->success('网站配置保存成功！');
    }
    //导航菜单
    function navmenu(){
    	$nav=D('menu');
    	$rs=$nav->order('rank ASC')->limit('20')->findAll();
    	$this->assign('menu',$rs);
    	$this->display(APP_PATH.'/Public/Admin/navmenu.html');
    	
    }
    function navedit(){
    	$nid=$_GET['id'];
    	if(!$nid) $this->error('请选择需要编辑的菜单！');
    	$nav=D('menu');
    	$menu=$nav->where('id='.$nid)->find();
    	$this->assign($menu);
    	$this->display(APP_PATH.'/Public/Admin/navedit.html');
    }
    function navac(){
    	$nav=D('menu');
    	if($_GET['ac']=='del'){
    		$nid=$_GET['id'];
    		if($nav->delete($nid)){
    		    $this->success('菜单删除成功！');
    		}else{
    			$this->error('菜单删除失败！!!');
    		}
    	}else{
    	    if(!$nav->create()) $this->error('操作失败！');
    	    if($_POST['ac']=='add'){
    	        $result=$nav->add();
    	        if($result){
    		        $this->success('菜单添加成功！');
    	        }else{
    		        $this->error('菜单添加失败！!!');
    	        }  
    	    }elseif($_POST['ac']=='edit'){
    	        $result=$nav->save();
    	        if($result){
    		        $this->success('菜单修改成功！');
    	        }else{
    		        $this->error('菜单修改失败！!!');
    	        }
    	    } 
    	}	
    }
    //广告管理
    function ads(){
	    $rs=D("ads");
		$array=$rs->select();
		$this->assign('listads',$array);
		$this->display(APP_PATH.'/Public/Admin/ads.html');
    }
    // 更新广告数据
	function adsupdate(){
	    $array=$_POST;
		$rs=D("ads");	
		if(!empty($array['ads_name_sub'])){
		    if($rs->where('adsname="'.trim($_POST['ads_name_sub']).'"')->find()){$this->error('该广告标识已经存在,请重新填写一个广告标识！');};
			$data['adsname'] = trim($array['ads_name_sub']);
			$data['adscontent'] = stripslashes(trim($array['ads_content_sub']));
			$rs->add($data);
			write_file(APP_PATH.'/Public/ads/'.$data['adsname'].'.js',t2js($data['adscontent']));
		}			
		foreach($array['adsid'] as $value){
		    $data['adsid'] = $array['adsid'][$value];
			$data['adsname'] = trim($array['adsname'][$value]);
			$data['adscontent'] = stripslashes(trim($array['adscontent'][$value]));
			if(empty($data['adsname'])){
			    $rs->where('adsid='.$data['adsid'])->delete();
			}else{
			    write_file(APP_PATH.'/Public/ads/'.$data['adsname'].'.js',t2js($data['adscontent']));
			    $rs->save($data);
			}
		}				
		$this->success('数据更新成功！');
	}
	//留言管理
	function guestbook(){
		$gb=D('guestbook');
		import("@.ORG.Page");
		$count = $gb->count();
		$p = new Page ( $count, 5 );
		$list=$gb->order('gid desc')->limit($p->firstRow.','.$p->listRows)->select();
		$p->setConfig('header','条留言');
		$page = $p->show ();
		$this->assign( "page", $page );
		$this->assign('list',$list);
		$this->display(APP_PATH.'/Public/Admin/guestbook.html');
	}
	function delgb(){
		$gid=$_POST['gid'];
		$gb=D('guestbook');
		foreach($gid as $v){
			$map['gid']=$v;
			$gb->where($map)->delete();
		}
		$this->success('留言删除成功！');
	}
	
	//数据库备份恢复
    public function bakupdb(){
		$rs = new Model();
		$result=$rs->query('SHOW TABLES FROM '.C('db_name'));
		$info=array();
        foreach ($result as $key => $val) {
            $info[$key] = current($val);
        }
		$this->assign('table',$info);
        $this->display(APP_PATH.'/Public/Admin/bakupdb.html');
    }
    public function bakup(){//处理备份
		$file = DATA_PATH.'_bak/'; $random = mt_rand(1000, 9999); $sql=""; $p=1;
		foreach($_POST['tables'] as $table){
			$rs=D(str_replace(C('db_prefix'),'',$table));
			$array = $rs->select();
			$sql.="TRUNCATE TABLE `$table`;\n";//$sql.="-- \n-- $table\n-- \n";
			foreach($array as $value){
				$sql.=$this->get_insert_sql($table, $value);
				if(strlen($sql)>=$_POST['filesize']*1000){
					$filename = $file.date('Ymd').'_'.$random.'_'.$p.'.sql';
					write_file($filename,$sql);
					$p++;$sql='';
				}
			}
		}
		if(!empty($sql)){
			$filename = $file.date('Ymd').'_'.$random.'_'.$p.'.sql';
			write_file($filename,$sql);
		}
		//$this->assign("jumpUrl",'index.php?s=Admin-Tool-Restore');
		$this->success('数据库分卷备份已完成,共分成'.$p.'个sql文件存放!');
    }
    public function get_insert_sql($table, $row){//生成备份语句
		$sql = "INSERT INTO `{$table}` VALUES ("; 
		$values = array(); 
		foreach ($row as $value) { 
			$values[] = "'" . mysql_real_escape_string($value) . "'"; 
		} 
		$sql .= implode(', ', $values) . ");\n"; 
		return $sql; 
	}
    public function restore(){//获取还原
		$file = DATA_PATH.'_bak/*.sql';
		$sqlfiles = glob($file);
		if(!empty($sqlfiles)){
			foreach($sqlfiles as $k=>$sqlfile){
				//preg_match("/([a-z0-9_]+_[0-9]{8}_[0-9a-z]{4}_)([0-9]+)\.sql/i",basename($sqlfile),$num);
				preg_match("/([0-9]{8}_[0-9a-z]{4}_)([0-9]+)\.sql/i",basename($sqlfile),$num);
				$info[$k]['filename'] = basename($sqlfile);
				$info[$k]['filesize'] = round(filesize($sqlfile)/(1024*1024), 2);
				$info[$k]['maketime'] = date('Y-m-d H:i:s', filemtime($sqlfile));
				$info[$k]['pre'] = $num[1];
				$info[$k]['number'] = $num[2];
				$info[$k]['path'] = $file = C('SITE_URL').DATA_PATH.'_bak/';
			}
			$this->assign('sql_list',$info);
        	$this->display(APP_PATH.'/Public/Admin/bakupdb.html');
		}else{
			$this->assign("jumpUrl",C('SITE_URL').'index.php?s=AdminSys/bakupdb');
			$this->error('没有检测到备份文件,请先备份或上传备份文件到'.DATA_PATH.'_bak/');
		}
    }
	public function resdb(){//处理还原
		$rs = new Model();
		$pre=$_GET['id'];
		$fileid = $_GET['fileid'] ? $_GET['fileid'] : 1;
		$filename = $pre.$fileid.'.sql';
		$filepath = DATA_PATH.'_bak/'.$filename;
		if(file_exists($filepath)){
			$sql = read_file($filepath);
			$sql = str_replace("\r\n", "\n", $sql); 
			foreach(explode(";\n", trim($sql)) as $query) {
				$rs->query(trim($query)); 
			}
			$fileid++;
			$this->assign("jumpUrl",C('SITE_URL').'index.php?s=AdminSys/resdb/id/'.$pre.'/fileid/'.$fileid.'');
			$this->success('数据库分卷'.$fileid.'恢复成功,准备恢复下一个分卷,请稍等!');
		}else{
			$this->assign("jumpUrl",C('SITE_URL').'index.php?s=AdminSys/restore');
			$this->success('数据库恢复成功!');
		}		
	}
	
	public function downbak(){//下载还原
		$file=DATA_PATH.'_bak/'.$_GET['id'];
		if(file_exists($file)){
			$filename = $filename ? $filename : basename($file);
			$filetype = trim(substr(strrchr($filename, '.'), 1));
			$filesize = filesize($file);
			header('Cache-control: max-age=31536000');
			header('Expires: '.gmdate('D, d M Y H:i:s', time() + 31536000).' GMT');
			header('Content-Encoding: none');
			header('Content-Length: '.$filesize);
			header('Content-Disposition: attachment; filename='.$filename);
			header('Content-Type: '.$filetype);
			readfile($file);
			exit;
		}
	}
		
	public function delbak(){//删除还原
		$filename=$_GET['id'];
		@unlink(DATA_PATH.'_bak/'.$filename);
		$this->success($filename.'已经删除!');
	}
	
	public function delbakall(){//删除所有还原
		foreach($_POST['filename'] as $value){
			@unlink(DATA_PATH.'_bak/'.$value);
		}
		$this->success('批量删除备份文件成功！');
	}
    public function ajaxfields(){//ajax获取字段信息
		$id=str_replace(C('db_prefix'),'',$_GET['id']);
		if(!empty($id)){
			$rs=D($id);
			$array=$rs->getDbFields();
			echo "<div style='border:1px solid #ababab;width:500px;background-color:#FEFFF0;margin-top:6px;padding:3px;line-height:160%'>";
			echo "表(".C('db_prefix').$id.")含有的字段：<br>";
			foreach($array as $key=>$val){
				if(!is_int($key)){break;}
				echo "<a href=\"javascript:rpfield('".$val."')\">".$val."</a>\r\n";
			}
			echo "</div>";
		}else{
			echo 'no fields';
		}
    }
    public function runsql(){//执行sql语句
		if($sql=trim($_POST['sql'])){
			if(empty($sql)){
			    $this->error('SQL语句不能为空!');
		    }else{
			    $sql=trim(stripslashes($sql));
			    $rs = new Model();
			    $rs->query($sql);
			    $this->success('SQL语句成功运行!');
		     }
		}else{
        	$this->display(APP_PATH.'/Public/Admin/bakupdb.html');	
		}
		
    }	
    public function replacefield(){//批量替换
    	if($_POST['submit']){
    		if(empty($_POST['rpfield'])){$this->assign("jumpUrl","__URL__/");$this->error("请手工指定要替换的字段!");}
		    if(empty($_POST['rpstring'])){$this->assign("jumpUrl","index.php?s=Admin-Tool-index-3");$this->error("请指定要被替换内容！");}
		    $exptable=str_replace(C('db_prefix'),'',$_POST['exptable']);
		    $rs=D($exptable);
		    $exptable=C('db_prefix').$exptable;//表
		    $rpfield=trim($_POST['rpfield']);//字段
		    $rpstring=$_POST['rpstring'];//被替换的
		    $tostring=$_POST['tostring'];//替换内容
		    $condition=trim($_POST['condition']);//条件
		    $condition=empty($condition) ? '' : " where $condition ";
		    $rs->execute(" update $exptable set $rpfield=Replace($rpfield,'$rpstring','$tostring') $condition ");
		    $this->success('批量替换完成!');
    	}else{
    		$rs = new Model();
		    $result=$rs->query('SHOW TABLES FROM '.C('db_name'));
		    $info=array();
            foreach ($result as $key => $val) {
                $info[$key] = current($val);
            }
		    $this->assign('table',$info);
    		$this->display(APP_PATH.'/Public/Admin/bakupdb.html');	
    	}
		
    }	
    //词语过滤
    function filter(){								
    	$dbFile =LIB_PATH.'/Cache/filter_inc.php';
        if( isset($_POST['submit'] )){
	        if( isset($_POST['ID'],$_POST['OLD'],$_POST['NEW']) ){
		        $filterWords = array();

		        $IdNum = count($_POST['ID']) - 1;

		        for($i=0;$i<=$IdNum;$i++){
			         $OldWord = $this-> strAddslashes(trim($_POST['OLD'][$i]));

			         $NewWord = $this-> strAddslashes(trim($_POST['NEW'][$i]));

			         if( !empty($OldWord) && !empty($NewWord) ){
				          $filterWords[] = array($OldWord,$NewWord);
			         }
		        }

		        if( @is_writable($dbFile) ){
			         $handle = @fopen($dbFile, 'w');

			         if ( @flock($handle, LOCK_EX) ){
				
			              @fwrite($handle, '<?php exit;?>'.serialize($filterWords));

				          @flock($handle, LOCK_UN);
			          }
			
			          @fclose($handle);

			          $this->success("更新成功");
		         }else{
			
		    	      $this->error("数据文件不可写");
		         }
		         echo '111112222222222';
	        }
	        echo '111111111';
        }
    	$filterWords = unserialize(substr(file_get_contents($dbFile),13));
    	//print_r($filterWords);
    	$this->assign('list',$filterWords);
    	$this->display(APP_PATH.'/Public/Admin/sys_filter.html');
    }
    public function payment(){
		$this->success( '本功能仅限商业版本使用！');
		
	}
function strAddslashes($str)
{
	if ( !get_magic_quotes_gpc() )
	{
		$str = addslashes($str);
	}

	return $str;
}
}
?>
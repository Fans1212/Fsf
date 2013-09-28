<?php
class HelpAction extends CommonAction{
	function index(){
		$area=get_area('');
		$this->assign('area',$area);
		$this->assign('qylist',A('Common'));
		$this->display('Index:help');
	}
}
?>
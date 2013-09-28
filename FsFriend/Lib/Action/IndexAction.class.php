<?php
class IndexAction extends CommonAction{
    public function index(){
    	$file=HTML_PATH.'index'.C('HTML_FILE_SUFFIX');
    	if(is_file($file)&&(time()-filemtime($file))<C('DATA_CACHE_TIME')){
    		include $file; 
    	}else{
			$nav=D('links');
    	    $rs=$nav->order('rank ASC')->limit('20')->findAll();
    	    $this->assign('link',$rs);
    		$area=get_area('');
    	    $this->assign('qylist',A('Common'));
    	    $this->assign('area',$area);
    	    $this->assign('root',C('site_url'));
            $this->assign('title',C('site_name').'-首页-'.C('SITE_TITLE'));
	        $this->display();
	        
	        //Temporary to comment out
	        //$this->buildHtml('index','','Index:index');
    	}
    	
    }
 
}
?>
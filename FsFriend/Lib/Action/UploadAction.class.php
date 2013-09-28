<?php
class UploadAction extends Action{
	public function index() {
		if(!empty($_FILES)) {
			//如果有文件上传 上传附件
			$this->_upload();
			//$this->forward();
		}
	}
	//上传相册
	function album(){
	    if(!empty($_FILES)) {
			//如果有文件上传 上传附件
			$this->album_upload();
			//$this->forward();
		}
	}
	// 文件上传
    protected function _upload()
    {
    	$uid=$_SESSION[C('USER_AUTH_KEY')];
		if(!$uid) $this->error('您没有登录或者登录超时！');
        import("@.ORG.UploadFile");
        $upload = new UploadFile();
        //设置上传文件大小
        $upload->maxSize  = 600200 ;
        //设置上传文件类型
        $upload->allowExts  = explode(',','jpg,gif,png,jpeg');
        //设置附件上传目录
        $dir_name=date("Y").'/'.date("md").'/';
        $upload->savePath =  'Public/Uploads/avatar/'.$dir_name;
	    //设置需要生成缩略图，仅对图像文件有效
        $upload->thumb =  true;
       //设置需要生成缩略图的文件后缀
	    $upload->thumbPrefix   =  'm_';  //生产2张缩略图
       //设置缩略图最大宽度
		$upload->thumbMaxWidth =  '120';
       //设置缩略图最大高度
		$upload->thumbMaxHeight = '150';
	   //设置上传文件规则
	    $upload->saveRule = uniqid;
	   //删除原图
	    $upload->thumbRemoveOrigin = true;
        if(!$upload->upload()) {
            //捕获上传异常
            $this->error($upload->getErrorMsg());
        }else {
            //取得成功上传的文件信息
            $uploadList = $upload->getUploadFileInfo();
            $_POST['image']  = $uploadList[0]['savename'];
        }
        $model = M ('attachment');
        //保存当前数据对象
        $data['uid']=$uid;
        $data['model']='avatar';
        $data['image']=$upload->savePath.$upload->thumbPrefix.$_POST['image'] ;
        $data['create_time']=time() ;
        if($rs=$model->where("uid=$uid and model='avatar' ")->find()){
        	//删除老头像
            $file	=$rs['image'];
            unlink($file);
        	$up=$model->where("uid=$uid and model='avatar' ")->save($data);
        	if(false!==$up){
        		 $Member=M('member_detail');
        		 $set['avatar']=$rs['id'];
        		 $ud=$Member->where('uid='.$uid)->save($set);
        		 $this->success ('上传图片成功！');
            }else{
                 $this->error ('上传图片失败!');	
        	}
        }else{
            $list=$model->add ($data);
            if($list!==false){
            	 $Member=M('member_detail');
        		 $set['avatar']=$list;
        		 $ud=$Member->where('uid='.$uid)->save($set);
                 $this->success ('上传图片成功！');
            }else{
                 $this->error ('上传图片失败!');
            }
        }
	}
    protected function album_upload()
    {
    	$uid=$_SESSION[C('USER_AUTH_KEY')];
		if(!$uid) $this->error('您没有登录或者登录超时！');
        import("@.ORG.UploadFile");
        $upload = new UploadFile();
        //设置上传文件大小
        $upload->maxSize  = 600200 ;
        //设置上传文件类型
        $upload->allowExts  = explode(',','jpg,gif,png,jpeg');
        //设置附件上传目录
        $dir_name=date("Y").'/'.date("md").'/';
        $upload->savePath =  'Public/Uploads/album/'.$dir_name;
	    //设置需要生成缩略图，仅对图像文件有效
        $upload->thumb =  true;
       //设置需要生成缩略图的文件后缀
	    $upload->thumbPrefix   =  'p_';  //生产2张缩略图
       //设置缩略图最大宽度
		$upload->thumbMaxWidth =  '640';
       //设置缩略图最大高度
		$upload->thumbMaxHeight = '480';
	   //设置上传文件规则
	    $upload->saveRule = uniqid;
	   //删除原图
	    $upload->thumbRemoveOrigin = true;
        if(!$upload->upload()) {
            //捕获上传异常
            $this->error($upload->getErrorMsg());
        }else {
            //取得成功上传的文件信息
            
            $uploadList = $upload->getUploadFileInfo();
            $_POST['image']  = $uploadList[0]['savename'];
            list($width, $height, $type, $attr) = getimagesize($upload->savePath.$upload->thumbPrefix.$_POST['image']);
        }
        $model = M ('album');
        //保存当前数据对象
        $data['uid']=$uid;
        $data['username']=$_SESSION['loginUserName'];
        $data['albumname']='My Photo';
        $data['width']=$width;
        $data['height']=$height;
        $data['pic']=$upload->savePath.$upload->thumbPrefix.$_POST['image'] ;
        $data['updatetime']=time() ;
        $list=$model->add ($data);
        if($list!==false){
            $this->success ('上传相片成功！');
        }else{
            $this->error ('上传相片失败!');
        }
	}
}

?>
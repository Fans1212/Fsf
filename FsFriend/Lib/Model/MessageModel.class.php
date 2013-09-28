<?php
class MessageModel extends Model{
   public $_validate	=	array(
		array('m_title','require','标题不能为空'),
		array('to','require','收件人不能为空'),
		array('m_content','require','内容不能为空'),
		);

	public $_auto		=	array(
		array('from','fromuid',self::MODEL_BOTH,'callback'),
		array('time','time',self::MODEL_INSERT,'function'),
		array('m_title','format_textarea',self::MODEL_INSERT,'function'),
		array('m_content','format_textarea',self::MODEL_BOTH,'function'),
		);
		
    protected function fromuid() {
		if(isset($_SESSION[C('USER_AUTH_KEY')])) {
			return $uid=$_SESSION[C('USER_AUTH_KEY')];
		}else{
			return false;
		}
	}
}
?>
<?php

class MemberModel extends Model{
	public $_validate	=	array(
		//array('username','/^[a-z]\w{3,}$/i','帐号格式错误'),
		array('username','ckname','用户名不能包含特殊字符!','1','callback'),
		array('password','require','密码必须'),
		array('repassword','require','确认密码必须'),
		array('repassword','password','确认密码不一致',self::EXISTS_VAILIDATE,'confirm'),
		array('username','','用户名已经存在',self::EXISTS_VAILIDATE,'unique',self::MODEL_INSERT),
		);

	public $_auto		=	array(
		array('password','pwdHash',self::MODEL_BOTH,'callback'),
		array('reg_time','time',self::MODEL_INSERT,'function'),
		array('login_time','time',self::MODEL_BOTH,'function'),
		array('reg_ip','get_client_ip',self::MODEL_INSERT,'function'),
		array('group','6',self::MODEL_INSERT),
		);

    protected function pwdHash() {
		if(isset($_POST['password'])) {
			return pwdHash($_POST['password'].C('SAFE_CODE'));
		}else{
			return false;
		}
	}
    protected function ckname(){
    	if(isset($_POST['username'])) {
            if(preg_match('/^[0-9a-zA-Z_\x{4e00}-\x{9fa5}]+$/u',$_POST['username'])){
                return true;
            }else {
                return false;
            }
    	}else{
    		return false;
    	}
    }
}

?>
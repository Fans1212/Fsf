<?php
class Member_detailModel extends Model{
	public $_auto		=	array(
		array('aboutme','text_in',self::MODEL_INSERT,'function'),
		);
}
?>
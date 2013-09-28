<?php
class DiaryModel extends Model{
	public $_validate	=	array(
		array('diarytitle','require','请填写日记标题！'),
		array('diarytxt','require','请填写日记内容！'),
		);

	public $_auto		=	array(
		array('thedate','time',self::MODEL_INSERT,'function'),
		array('diarytitle','html_in',self::MODEL_BOTH,'function'),
		array('diarytxt','html_in',self::MODEL_BOTH,'function'),
		);
}
?>
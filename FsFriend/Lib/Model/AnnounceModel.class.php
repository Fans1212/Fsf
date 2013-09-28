<?php
class AnnounceModel extends Model{
	public $_validate	=	array(
		array('title','require','标题不能为空'),
		array('content','require','内容不能为空'),
		);
}
<?php
class form_set
{

	function date($name, $value = '', $isdatetime = 0)
	{
		if($value == '0000-00-00 00:00:00') $value = '';
		$id = preg_match("/\[(.*)\]/", $name, $m) ? $m[1] : $name;
		if($isdatetime)
		{
			$size = 21;
			$format = '%Y-%m-%d %H:%M:%S';
			$showsTime = 'true';
		}
		else
		{
			$size = 10;
			$format = '%Y-%m-%d';
			$showsTime = 'false';
		}
		$str = '';
		if(!defined('CALENDAR_INIT'))
		{
			define('CALENDAR_INIT', 1);
			$str .= '<link rel="stylesheet" type="text/css" href="images/js/calendar/calendar-blue.css"/>
			        <script type="text/javascript" src="images/js/calendar/calendar.js"></script>';
		}
		$str .= '<input type="text" name="'.$name.'" id="'.$id.'" value="'.$value.'" size="'.$size.'" readonly>&nbsp;';
		$str .= '<script language="javascript" type="text/javascript">
					date = new Date();document.getElementById ("'.$id.'").value="'.$value.'";
					Calendar.setup({
						inputField     :    "'.$id.'",
						ifFormat       :    "'.$format.'",
						showsTime      :    '.$showsTime.',
						timeFormat     :    "24"
					});
				 </script>';
		return $str;
	}

	function checkcode($name = 'checkcode', $size = 4, $extra = '')
	{
		return '<input name="'.$name.'" id="'.$name.'" type="text" size="'.$size.'" '.$extra.' style="ime-mode:disabled;"> <img src="'.SITE_URL.'checkcode.php" id="checkcode" onclick="this.src=\''.SITE_URL.'checkcode.php?id=\'+Math.random()*5;" style="cursor:pointer;" alt="验证码,看不清楚?请点击刷新验证码" align="absmiddle"/>';
	}

	function style($name = 'style', $style = '')
	{
		global $styleid, $LANG;
		if(!$styleid) $styleid = 1; else $styleid++;
		$color = $strong = '';
		if($style)
		{
			list($color, $b) = explode(' ', $style);
		}
		$styleform = "<option value=\"\">".$LANG['color']."</option>\n";
		for($i=1; $i<=15; $i++)
		{
			$styleform .= "<option value=\"c".$i."\" ".($color == 'c'.$i ? "selected=\"selected\"" : "")." class=\"bg".$i."\"></option>\n";
		}
		$styleform = "<select name=\"style_color$styleid\" id=\"style_color$styleid\" onchange=\"document.all.style_id$styleid.value=document.all.style_color$styleid.value;if(document.all.style_strong$styleid.checked)document.all.style_id$styleid.value += ' '+document.all.style_strong$styleid.value;\">\n".$styleform."</select>\n";
		$styleform .= " <label><input type=\"checkbox\" name=\"style_strong$styleid\" id=\"style_strong$styleid\" value=\"b\" ".($b == 'b' ? "checked=\"checked\"" : "")." onclick=\"document.all.style_id$styleid.value=document.all.style_color$styleid.value;if(document.all.style_strong$styleid.checked)document.all.style_id$styleid.value += ' '+document.all.style_strong$styleid.value;\"> ".$LANG['bold'];
		$styleform .= "</label><input type=\"hidden\" name=\"".$name."\" id=\"style_id$styleid\" value=\"".$style."\">";
		return $styleform;
	}

	function text($name, $id = '', $value = '', $type = 'text', $size = 50, $class = '', $ext = '', $minlength = '', $maxlength = '', $pattern = '', $errortips = '')
	{
		if(!$id) $id = $name;
		$checkthis = '';
		$showerrortips = "字符长度必须为".$minlength."到".$maxlength."位";
		if($pattern)
		{
			$pattern = 'regexp="'.substr($pattern,1,-1).'"';
		}
		$require = $minlength ? 'true' : 'false';
		if($pattern && ($minlength || $maxlength))
		{
			$string_datatype = substr($string_datatype, 1);
			$checkthis = "require=\"$require\" $pattern datatype=\"limit|custom\" min=\"$minlength\" max=\"$maxlength\" msg='$showerrortips|$errortips'";
		}
		elseif($pattern)
		{
			$checkthis = "require=\"$require\" $pattern datatype=\"custom\" msg='$errortips'";
		}
		elseif($minlength || $maxlength)
		{
			$checkthis = "require=\"$require\" datatype=\"limit\" min=\"$minlength\" max=\"$maxlength\" msg='$showerrortips'";
		}
		return "<input type=\"$type\" name=\"$name\" id=\"$id\" value=\"$value\" size=\"$size\" class=\"$class\" $checkthis $ext/> ";
	}

	function textarea($name, $id = '', $value = '', $rows = 10, $cols = 50, $class = '', $ext = '', $character = 0, $maxlength = 0)
	{
		if(!$id) $id = $name;
		$data = "<textarea name=\"$name\" id=\"$id\" rows=\"$rows\" cols=\"$cols\" class=\"$class\" $ext>$value</textarea>";
		if($character && $maxlength)
		{
			$data .= '<br /> <img src="images/icon.gif" width="12"> 还可以输入 <font id="ls_'.$id.'" color="#ff0000;">'.$maxlength.'</font> 个字符！';
		}
		return $data;
	}

	function select($options, $name, $id = '', $value = '', $size = 1, $class = '', $ext = '')
	{
		if(!$id) $id = $name;
		if(!is_array($options)) $options = form_set::_option($options);
		if($size >= 1) $size = " size=\"$size\"";
		if($class) $class = " class=\"$class\"";
		$data = "<select name=\"$name\" id=\"$id\" $size $class $ext>";
		foreach($options as $k=>$v)
		{
			$selected = $k == $value ? 'selected' : '';
			$data .= "<option value=\"$k\" $selected>$v</option>\n";
		}
		$data .= '</select>';
		return $data;
	}

	function multiple($options, $name, $id = '', $value = '', $size = 3, $class = '', $ext = '')
	{
		if(!$id) $id = $name;
		if(!is_array($options)) $options = form::_option($options);
		$size = max(intval($size), 3);
		if($class) $class = " class=\"$class\"";
		$value = strpos($value, ',') ? explode(',', $value) : array($value);
		$data .= "<select name=\"$name\" id=\"$id\" multiple=\"multiple\" size=\"$size\" $class $ext>";
		foreach($options as $k=>$v)
		{
			$selected = in_array($k, $value) ? 'selected' : '';
			$data .= "<option value=\"$k\" $selected>$v</option>\n";
		}
		$data .= '</select>';
		return $data;
	}

	function checkbox($options, $name, $id = '', $value = '', $cols = 5, $class = '', $ext = '', $width = 100)
	{
		if(!$options) return '';
		if(!$id) $id = $name;
		if(!is_array($options)) $options = form_set::_option($options);
		$i = 1;
		$data = '<input type="hidden" name="'.$name.'" value="-99">';
		if($class) $class = " class=\"$class\"";
		if($value != '') $value = strpos($value, ',') ? explode(',', $value) : array($value);
		foreach($options as $k=>$v)
		{
			$checked = ($value && in_array($k, $value)) ? 'checked' : '';
			$data .= "<span style=\"width:{$width}px\"><label><input type=\"checkbox\" boxid=\"{$id}\" name=\"{$name}[]\" id=\"{$id}\" value=\"{$k}\" style=\"border:0px\" $class {$ext} {$checked}/> {$v}</label></span>\n ";
			if($i%$cols == 0) $data .= "<br />\n";
			$i++;
		}
		return $data;
	}

	function radio($options, $name, $id = '', $value = '', $cols = 5, $class = '', $ext = '', $width = 100)
	{
		if(!$id) $id = $name;
		if(!is_array($options)) $options =form_set::_option($options);
		$i = 1;
		$data = '';
		if($class) $class = " class=\"$class\"";
		foreach($options as $k=>$v)
		{
			$checked = $k == $value ? 'checked' : '';
			$data .= "<span style=\"width:{$width}px\"><label><input type=\"radio\" name=\"{$name}\" id=\"{$id}\" value=\"{$k}\" style=\"border:0px\" $class {$ext} {$checked}/> {$v}</label></span> ";
			if($i%$cols == 0) $data .= "<br />\n";
			$i++;
		}
		return $data;
	}

	function _option($options, $s1 = "\n", $s2 = '|')
	{
		$options = explode($s1, $options);
		foreach($options as $option)
		{
			if(strpos($option, $s2))
			{
				list($name, $value) = explode($s2, trim($option));
			}
			else
			{
				$name = $value = trim($option);
			}
			$os[$value] = $name;
		}
		return $os;
	}

	function image($name, $id = '', $value = '', $size = 50, $class = '', $ext = '', $modelid = 0, $fieldid = 0)
	{
		if(!$id) $id = $name;
		return "<input type=\"text\" name=\"$name\" id=\"$id\" value=\"$value\" size=\"$size\" class=\"$class\" $ext/> <input type=\"hidden\" name=\"{$id}_aid\" value=\"0\"> <input type=\"button\" name=\"{$name}_upimage\" id=\"{$id}_upimage\" value=\"上传图片\" style=\"width:60px\" onclick=\"javascript:openwinx('?mod=phpcms&file=upload_field&uploadtext={$id}&modelid={$modelid}&fieldid={$fieldid}','upload','350','350')\"/>";
	}

	function file($name, $id = '', $size = 50, $class = '', $ext = '')
	{
		if(!$id) $id = $name;
		return "<input type=\"file\" name=\"$name\" id=\"$id\" size=\"$size\" class=\"$class\" $ext/> ";
	}

	function downfile($name, $id = '', $value = '', $size = 50, $mode, $class = '', $ext = '')
	{
		if(!$id) $id = $name;
		$mode = "&mode=".$mode;
		if(defined('IN_ADMIN'))
		{
			return "<input type=\"text\" name=\"$name\" id=\"$id\" value=\"$value\" size=\"$size\" class=\"$class\" $ext/> <input type=\"hidden\" name=\"{$id}_aid\" value=\"0\"> <input type=\"button\" name=\"{$name}_upfile\" id=\"{$id}_upfile\" value=\"上传文件\" style=\"width:60px\" onclick=\"javascript:openwinx('?mod=phpcms&file=upload&uploadtext={$id}{$mode}','upload','390','180')\"/>";
		}
		else
		{
			return true;
		}
	}

	function upload_image($name, $id = '', $value = '', $size = 50, $class = '', $property = '')
	{
		if(!$id) $id = $name;
		return "<input type=\"text\" name=\"$name\" id=\"$id\" value=\"$value\" size=\"$size\" class=\"$class\" $property/> <input type=\"button\" name=\"{$name}_upimage\" id=\"{$id}_upimage\" value=\"上传图片\" style=\"width:60px\" onclick=\"javascript:openwinx('?mod=phpcms&file=upload&uploadtext={$id}','upload','380','350')\"/>";
	}

	function select_template($module, $name, $id = '', $value = '', $property = '', $pre = '')
	{
		if(!$id) $id = $name;
		$templatedir = TPL_ROOT.TPL_NAME.'/'.$module.'/';
		$files = array_map('basename', glob($templatedir.$pre.'*.html'));
		$names = cache_read('name.inc.php', $templatedir);
		$templates = array(''=>'请选择');
		foreach($files as $file)
		{
			$key = substr($file, 0, -5);
			$templates[$key] = isset($names[$file]) ? $names[$file].'('.$file.')' : $file;
		}
		ksort($templates);
		return form_set::select($templates, $name, $id, $value, $property);
	}

	function select_file($name, $id = '', $value = '', $size = 30, $catid = 0, $isimage = 0)
	{
		if(!$id) $id = $name;
		return "<input type='text' name='$name' id='$id' value='$value' size='$size' /> <input type='button' value='浏览...' style='cursor:pointer;' onclick=\"file_select('$id', $catid, $isimage)\">";
	}

	function select_module($name = 'module', $id ='', $alt = '', $value = '', $property = '')
	{
		global $MODULE;
		if($alt) $arrmodule = array('0'=>$alt);
		foreach($MODULE as $k=>$v)
		{
			$arrmodule[$k] = $v['name'];
		}
		if(!$id) $id = $name;
		return form_set::select($arrmodule, $name, $id, $value, 1, '', $property);
	}

	function select_model($name = 'modelid', $id ='', $alt = '', $modelid = '', $property = '')
	{
		global $MODEL;
		if($alt) $arrmodel = array('0'=>$alt);
		foreach($MODEL as $k=>$v)
		{
			if($v['modeltype'] > 0) continue;
			$arrmodel[$k] = $v['name'];
		}
		if(!$id) $id = $name;
		return form_set::select($arrmodel, $name, $id, $modelid, 1, '', $property);
	}

	function select_member_model($name = 'modelid', $id = '', $alt = '', $modelid = '', $property = '')
	{
		global $MODEL;
		if($alt) $arrmodel = array('0'=>$alt);
		foreach($MODEL as $k=>$v)
		{
			if($v['modeltype'] == '2')
			{
				$arrmodel[$k] = $v['name'];
			}
		}
		if(!$id) $id = $name;
		return form_set::select($arrmodel, $name, $id, $modelid, 1, '', $property);
	}

	function select_category($module = 'phpcms', $parentid = 0, $name = 'catid', $id ='', $alt = '', $catid = 0, $property = '', $type = 0, $optgroup = 0)
	{
		global $tree, $CATEGORY;
		if(!is_object($tree))
		{
			require_once 'tree.class.php';
			$tree = new tree;
		}
		if(!$id) $id = $name;
		if($optgroup) $optgroup_str = "<optgroup label='\$name'></optgroup>";
		$data = "<select name='$name' id='$id' $property>\n<option value='0'>$alt</option>\n";
		if(is_array($CATEGORY))
		{
			$categorys = array();
			foreach($CATEGORY as $id=>$cat)
			{
				if(($type == 2 && $cat['type'] ==2) || ($type == 1 && $cat['type'])) continue;
				if($cat['module'] == $module) $categorys[$id] = array('id'=>$id, 'parentid'=>$cat['parentid'], 'name'=>$cat['catname']);
			}
			$tree->tree($categorys);
			$data .= $tree->get_tree($parentid, "<option value='\$id' \$selected>\$spacer\$name</option>\n", $catid, '' , $optgroup_str);
		}
		$data .= '</select>';
		return $data;
	}

	function select_pos($name = 'posid', $id ='', $posids = '', $cols = 1, $width = 100)
	{
		global $db,$priv_role, $POS;
		if(!$id) $id = $name;
		$pos = array();
		foreach($POS as $posid=>$posname)
		{
			if($priv_role->check('posid', $posid)) $pos[$posid] = str_cut($posname, 16, '');
		}
		return form_set::checkbox($pos, $name, $id, $posids, $cols, '', '', $width);
	}

	function select_group($name = 'groupid', $id ='', $groupids = '', $cols = 1, $width = 100)
	{
		global $db, $GROUP;
		if(!$id) $id = $name;
		return form_set::checkbox($GROUP, $name, $id, $groupids, $cols, '', '', $width);
	}

	function select_type($module = 'phpcms', $name = 'typeid', $id ='', $alt = '', $typeid = 0, $property = '', $modelid = 0)
	{
		$types = subtype($module, $modelid);
		if(!$id) $id = $name;
		$data = "<select name='$name' id='$id' $property>\n<option value='0'>$alt</option>\n";
		foreach($types as $id=>$t)
		{
			$selected = $id == $typeid ? 'selected' : '';
			$data .= "<option value='$id' $selected>$t[name]</option>\n";
		}
		$data .= '</select>';
		return $data;
	}

	function select_area($name = 'areaid', $id ='', $alt = '', $parentid = 0, $areaid = 0, $property = '')
	{
		global $tree, $AREA;
		if(!is_object($tree))
		{
			require_once 'tree.class.php';
			$tree = new tree;
		}
		if(!$id) $id = $name;
		$data = "<select name='$name' id='$id' $property>\n<option value='0'>$alt</option>\n";
		if(is_array($AREA))
		{
			$areas = array();
			foreach($AREA as $id=>$a)
			{
				$areas[$id] = array('id'=>$id, 'parentid'=>$a['parentid'], 'name'=>$a['name']);
			}
			$tree->tree($areas);
			$data .= $tree->get_tree($parentid, "<option value='\$id' \$selected>\$spacer\$name</option>\n", $areaid);
		}
		$data .= '</select>';
		return $data;
	}

	function select_urlrule($module = 'phpcms', $file = 'category', $ishtml = 1, $name = 'urlruleid', $id ='', $urlruleid = 0, $property = '')
	{
		global $db;
		$urlrules = array();
		$result = $db->query("SELECT `urlruleid`,`example` FROM `".DB_PRE."urlrule` WHERE `module`='$module' AND `file`='$file' AND `ishtml`='$ishtml' ORDER BY `urlruleid`");
		while($r = $db->fetch_array($result))
		{
			$urlrules[$r['urlruleid']] = $r['example'];
		}
		$db->free_result($result);
		if(!$id) $id = $name;
		return form_set::select($urlrules, $name, $id, $urlruleid, 1, '', $property);
	}

	function select_linkage($keyid = 0, $parentid = 0, $name = 'catid', $id ='', $alt = '', $linkageid = 0, $property = '')
	{
		global $tree, $db;
		if(!is_object($tree))
		{
			require_once 'tree.class.php';
			$tree = new tree;
		}
		if(!$id) $id = $name;

		$sql = "SELECT * FROM `".DB_PRE."linkage` WHERE `keyid`='$keyid' ORDER BY `listorder` DESC,`linkageid`";
		$result = $db->query($sql);
		while($r = $db->fetch_array($result))
		{
			$infos[$r['linkageid']] = $r;
		}
		
		$data = "<select name='$name' id='$id' $property>\n<option value='0'>$alt</option>\n";
		if(!empty($infos))
		{
			$categorys = array();
			foreach($infos as $id=>$cat)
			{
				$categorys[$id] = array('id'=>$id, 'parentid'=>$cat['parentid'], 'name'=>$cat['name']);
			}
			$tree->tree($categorys);
			$data .= $tree->get_tree($parentid, "<option value='\$id' \$selected>\$spacer\$name</option>\n", $linkageid);
		}
		$data .= '</select>';
		return $data;
	}
}

?>
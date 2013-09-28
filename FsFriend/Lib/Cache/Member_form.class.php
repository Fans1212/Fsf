<?php
class member_form
{
	var $fields;

    function __construct()
    {  
		$this->fields =require LIB_DIR.'/Cache/Member_fields.inc.php';
		
	}

	function member_form()
	{
		$this->__construct();
	}
	
	function getform($data=array())
	{   
		$info = array();
		if(empty($this->fields)) return false;
		$data['city']=$data['province'];
		$array=$this->fields;
		foreach($this->fields as $field=>$v)
		{   
			if($v['isshow']){
			$func = $v['formtype'];
			$value = isset($data[$field]) ? $data[$field] : $v['defaultvalue'];
			$form = $this->$func($field, $value, $v);
			$info[] =array('name'=>$v['name'],'form'=>$form);
			}
		}
		return $info;
	}
	
	function box($field, $value, $fieldinfo)
	{   $form_out=new form_set();
		extract($fieldinfo);
		if($value == '-99') $value = $defaultvalue;
		if($boxtype == 'radio')
		{
			return $form_out->radio($options, $field, $field, $value, $cols, $css, $formattribute, $width);
		}
		elseif($boxtype == 'checkbox')
		{
			return $form_out->checkbox($options, $field, $field, $value, $cols, $css, $formattribute, $width);
		}
		elseif($boxtype == 'select')
		{
			return $form_out->select($options,$field, $field, $value, $size, $css, $formattribute);
		}
		elseif($boxtype == 'multiple')
		{
			return $form_out->multiple($options,$field, $field, $value, $size, $css, $formattribute);
		}
	}
	
	function datetime($field, $value, $fieldinfo)
	{
		extract($fieldinfo);
		if(!$value)
		{
			if($defaulttype == 0)
			{
				$value = '';
			}
			elseif($defaulttype == 1)
			{
				$df = $dateformat == 'datetime' ? 'Y-m-d H:i:s' : 'Y-m-d';
				$value = date($df, TIME);
			}
			else
			{
				$value = $defaultvalue;
			}
		}
		if(substr($value, 0, 10) == '0000-00-00') $value = '';
		$isdatetime = $dateformat == 'datetime' ? 1 : 0;
		$str = form_set::date($field, $value, $isdatetime);
		return $str;
	}
	function editor($field, $value, $fieldinfo)
	{
		extract($fieldinfo);
		if(!$value) $value = $defaultvalue;
		if($this->userid && $this->fields[$field]['storage'] == 'file') $value = content_get($this->userid, $field);
		$data = "<textarea name=\"info[$field]\" id=\"$field\" style=\"display:none\">$value</textarea>\n";
		return $data.form_set::editor($field, $toolbar, $width, $height);
	}

	function number($field, $value, $fieldinfo)
	{   $form_out=new form_set();
		extract($fieldinfo);
		if(!$value) $value = $defaultvalue;
		return $form_out->text($field, $field, $value, 'text', 10, $css, $formattribute, $minlength, $maxlength, $pattern, $errortips);
	}
	
	function text($field, $value, $fieldinfo)
	{   
	    $form_out=new form_set();
		extract($fieldinfo);
		if(!$value) $value = $defaultvalue;
		$type = $ispassword ? 'password' : 'text';
		return $form_out->text($field, $field, $value, $type, $size, $css, $formattribute, $minlength, $maxlength, $pattern, $errortips);
	}
	
	function textarea($field, $value, $fieldinfo)
	{   $form_out=new form_set();
		extract($fieldinfo);
		if(!$value) $value = $defaultvalue;
		return $form_out->textarea($field, $field, $value, $rows, $cols, $css, $formattribute);
	}
	
	function areaid($field, $value, $fieldinfo)
	{    
        $AREA=require COMMON_PATH.'/Area/area.php';
		$str = "<select name=\"province\" id=\"province\" onchange=\"getCity(this.value);\"><option value=\"0\">请选择</option>";
		foreach($AREA as $k=>$v)
		{   
			$selected = $k == $value ? 'selected' : '';
			if($v['parentid']==0)
			$str .= "<option value=\"$v[areaid]\" $selected>$v[name]</option>\n";
		}
		$str .= '</select><select name="city" id="city">
            <option value="0">请选择</option>
        </select>';
	    return $str;
	}

}
?>
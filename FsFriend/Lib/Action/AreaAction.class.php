<?php
class AreaAction extends Action{
	
    public function Getcity(){
    	$id=$_GET['id'];
    	$str = "<select name=\"cityid\" id=\"cityid\" onchange=\"onChange=\"getCity(this.value);\" >";
        $AREA=require COMMON_PATH.'/Area/area.php';
	    $options = '';
	    foreach($AREA as $i=>$v){
		    if($v['parentid'] == $id)  $options .= '<option value="'.$i.'">'.$v['name'].'</option>';
	    }
	    if(empty($options)) exit;
	    $str .= $options.'</select>';
        echo $str;
    } 
    
    function city(){
    	echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
    	$id=$_GET['id'];
    	$str = "[[";
	    $options = '';
	    $cname='[';
        $AREA=require COMMON_PATH.'/Area/area.php';
	    foreach($AREA as $i=>$v){
	    	if($v['parentid']==0){
	    	foreach($AREA as $n=>$va){
	    	  if($va['parentid']==$i){
		        $options .= '"'.$n.'",';
		        $cname.= '"'.$va['name'].'",';
		      } 
	    	}
	    	$str .= $options.']';
	        $str2.=$cname.']]';
            echo "pro[$i]=".$str.','.$str2."<br>"; 
            $str = "[[";
	        $options = '';
	        $cname='[';
	        $str2="";
	    	} 
	    }
    }
}
?>
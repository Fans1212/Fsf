<?php
class GuideAction extends Action{
	//导入会员数据
	function Member(){
		if($sid=$_GET['sid']){
	    $from=M("member_1");
	    $array=$from->limit("$sid,1000")->select();
	    if($array){
	    	foreach($array as $k=>$arr){
	    	$Member=D("Member");
	        //$Member->uid=$arr['userid'];
	        $Member->username=$arr['username'];
	        $Member->password=$arr['password'];
	        $Member->email=$arr['email'];
	        $Member_info=M("Member_info");
	        $rs=$Member_info->where("userid=$arr[userid]")->find();
	        
	        $Member->reg_ip=$rs['regip'];
	        $Member->reg_time=$rs['regtime'];
	        $Member->login_time=$rs['lastlogintime'];
	        $Member->login_count=$rs['logintimes'];
		    if($result=$Member->add()){
		    	$Member_detail=M("Member_detail");
		    	$Member_3=M("Member_3");
	            $rs3=$Member_3->where("userid=$arr[userid]")->find();
    	    	foreach($rs3 as $k=>$v){
                $Member_detail->$k= $v;
		        }
		        $Member_detail->uid=$result;
		        $Member_detail->avatar=$rs['avatar'];
		        $Member_detail->updatetime= time();
		        $Member_detail->add();
		    }
		    echo $a+=1;
		    echo "-";
	    	}
	    	$nid=$sid+1000;
	    	echo "<br />完成$sid<br>";
            echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"1; URL=http://127.0.0.1/thinkphp/index.php/Guide/Member/sid/$nid;\">"; 
	    }
		}else{
			echo "<a href=__URL__/Member/sid=>开始</a>";
		}
	}
}
?>
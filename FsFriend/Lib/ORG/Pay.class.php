<?php
class pay extends think{
	//审核订单
	function changeorder($orderid){
		$order=D('order');
		$map['orderid']=$orderid;
		$rs=$order->where($map)->find();
		$data['status']='1';
		if($rs2=$order->where($map)->save($data)){
			$m=D('member');
		    if($result=$m->setInc('money','uid='.$rs['uid'],$rs['amount'])){
		    	$this->insert_charge($rs['uid'],$rs['payment'],$rs['amount'],'1');
		    }
		}
	}
	//写入交易记录
	function insert_charge($uid,$item,$num,$account){
		$charge=D('charge');
		$data['time']=time();
		$data['uid']=$uid;
		$data['item']=$item;
		$data['num']=$num;
		$data['account']=$account;
		$charge->add($data);
	}
	//删除订单
	function del($orderid){
		$order=D('order');
		$map['orderid']=$orderid;
		$order->where($map)->delete();
	}
	//增加财务
	function add($uid,$amount,$type){
		$m=D('member');
		$n=$this->get_type($type,$amount);
		if($result=$m->setInc($type,'uid='.$uid,$amount)){
		    $this->insert_charge($uid,'管理员充值',$amount,$n);
		}
	}
	//支出或者收入
	function get_type($type,$amount){
		if($type=='money'){
			if($amount<0){
				$n='0';
			}else{
				$n='1';
			}
		}else{
			if($amount<0){
				$n='3';
			}else{
				$n='2';
			}
		}
		return $n;
	}

}
?>
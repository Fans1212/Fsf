<?php
class PayAction extends CommonAction{
	function index(){
		$_uid=is_login();
		if(!$_uid) $this->error('请登录后操作！');
		$order=M('order');
		$list=$order->where('uid='.$_uid)->limit('10')->order('id desc')->findAll();
		$this->assign('list',$list);
		$this->display('Member:order');
	}
	function showpayment(){
		$_uid=is_login();
		if(!$_uid) $this->error('请登录后操作！');
		$amount=$_GET['amount'];
		if(!$amount) $amount='150';
		$m=M('member');
		$infos=$m->where('uid='.$_uid)->find();
		$order=M('order');
		$list=$order->where('uid='.$_uid)->limit('10')->order('id desc')->findAll();
		$this->assign('list',$list);
		$this->assign('amount',$amount);
		$this->assign($infos);
		$this->display('Member:showpayment');
	}
	function online(){
		$_uid=is_login();
		if(!$_uid) $this->error('请登录后操作！');
		$order=M('order');
		$data['orderid']=date(YmdHis);
		$data['uid']=$_uid;
		$data['type']=$_POST['type'];
		$data['remark']=strip_tags($_POST['remark']);
		if($data['remark']=='如果您已经通过支付宝付款，请填写该笔交易号') $data['remark']='';
		$data['payment']=$_POST['payment'];
		$data['time']=time();
		$data['amount']=$_POST['amount'];
		$data['bankid']=$_POST['bankid'];
		if($rs=$order->add($data)){
			if($data['payment']=='支付宝') $this->success('订单提交成功，请等待审核！');
			$this->assign($data);
			$this->display('Member:payonline');
		}else{
			$this->error('订单提交失败！');
		}
	}
	function pay(){
		$this->success('暂时无法使用');
		if($_POST['payment']=='网银在线'){
	     	require LIB_DIR.'/ORG/payment/chinabank.php';
		}elseif($_POST['payment']=='财付通'){
			require LIB_DIR.'/ORG/payment/tenpay/index.php';
		}else{
			require LIB_DIR.'/ORG/payment/chinabank.php';
		}
	}
	function upgrade(){
		$uid=$_SESSION[C('USER_AUTH_KEY')];
		if(!$uid) $this->error('您没有登录或者登录超时！');
		$this->assign($uid);
		$this->display('Member:upgrade');
	}
	function point(){
		$_uid=is_login();
		if(!$_uid) $this->error('请登录后操作！');
		$m=M('member');
		$infos=$m->where('uid='.$_uid)->find();
		$this->assign($infos);
		$this->display('Member:point');
		
	}
	function buypoint(){
		$_uid=is_login();
		if(!$_uid) $this->error('请登录后操作！');
		$member=M('member');
		$amount=$_POST['amount'];
		$map['uid']=$_uid;
		$rs=$member->where($map)->find();
		if($rs['money']<$amount) $this->error('您的资金不足！');
		if($amount>'100'){
			if($amount=='150')$years='1';
			if($amount=='280')$years='3';
			if($amount=='480')$years='10';
			$this->joinvip($years,$_uid);
			$data['group']='7';
			$data['money']=$rs['money']-$amount;
			$member->where($map)->save($data);
			$this->insert_charge($_uid,'升级VIP',$amount,'0');
			$this->success('升级VIP成功！');
		}else{
			if($amount=='30')$point='300';
			if($amount=='50')$point='800';
			if($amount=='100')$point='1500';
			$data['money']=$rs['money']-$amount;
			$data['credit']=$rs['credit']+$point;
			$member->where($map)->save($data);
			$this->insert_charge($_uid,'购买E币',$amount,'0');
			$this->success('购买E币成功！');
		}
	}
	function joinvip($years,$uid){
		$upgrade_end=time()+31536000*$years;
		$m=M('member');
		$data['upgrade_time']=time();
		$data['upgrade_end']=$upgrade_end;
		$m->where('uid='.$uid)->save($data);
	}
	function insert_charge($uid,$item,$num,$account){
		$charge=D('charge');
		$data['time']=time();
		$data['uid']=$uid;
		$data['item']=$item;
		$data['num']=$num;
		$data['account']=$account;
		$charge->add($data);

	}
	//交易记录
	function charge(){
		$uid=is_login();
		if(!$uid) $this->error('请登录后操作！');
		$charge=D('charge');
		import("@.ORG.Page");
		$map['uid']=$uid;
		$count = $charge->where($map)->count();
		$p = new Page ( $count, 5 );
		$list=$charge->where($map)->order('id desc')->limit($p->firstRow.','.$p->listRows)->select();
		$page = $p->show ();
		foreach($list as $k=>$v){
			if($v['account']=='0'){
				$list[$k]['type']='资金';
				$list[$k]['in']='<font color=green>支出</font>';
			}elseif($v['account']=='1'){
				$list[$k]['type']='资金';
				$list[$k]['in']='<font color=red>收入</font>';
			}elseif($v['account']=='2'){
				$list[$k]['type']='E币';
				$list[$k]['in']='<font color=red>收入</font>';
			}elseif($v['account']=='3'){
				$list[$k]['type']='E币';
				$list[$k]['in']='<font color=green>支出</font>';
			}
		}
		$this->assign( "page", $page );
		$this->assign('list',$list);
		$this->display('Member:charge');
		
	}
	//支付响应
	function respond(){
		$v_oid          = trim($_POST['v_oid']);
        $v_pmode        = trim($_POST['v_pmode']);
        $v_pstatus      = trim($_POST['v_pstatus']);
        $v_pstring      = trim($_POST['v_pstring']);
        $v_amount       = trim($_POST['v_amount']);
        $v_moneytype    = trim($_POST['v_moneytype']);
        $remark1        = trim($_POST['remark1' ]);
        $remark2        = trim($_POST['remark2' ]);
        $v_md5str       = trim($_POST['v_md5str' ]);
        $v_rcvname      =trim($_POST['v_rcvname']);
		$total = floatval($v_amount);

		$key            = $payment['chinabank_key'];

        $md5string=strtoupper(md5($v_oid.$v_pstatus.$v_amount.$v_moneytype.$key));

        ///* 检查秘钥是否正确 */
        if ($v_md5str == $md5string)
        {
            if ($v_pstatus == '20')
            {
                if($rs=$this->changeorder($v_oid))
                {   
                	$this->assign('jumpUrl','__URL__/point');
                    $this->success('支付成功！');
                	//return true;
                }
            }
        }
        else
        {
			$this->error('校验失败，若您的确已经在网关处被扣了款项，请及时联系店主，并且请不要再次点击支付按钮(原因：错误的签名)');
            return false;
        }
	}
		//支付响应
	function tprespond(){
		$_uid=is_login();
		$sign= trim($_GET['sign']);
        $v_oid=trim($_GET['oid']);
		$order=D('order');
		$map['orderid']=$v_oid;
		$rs=$order->where($map)->find();
		$fee=intval($rs['amount'])*100;
		$md5sign=md5($fee.$_uid);
        ///* 检查秘钥是否正确 */
        if ($md5sign == $sign)
        {
             if($rs=$this->changeorder($v_oid))
                {   
                	$this->assign('jumpUrl','__URL__/point');
                    $this->success('支付成功！如果没有自动入账请联系客服：support_98qing@foxmail.com');
                	//return true;
                }
        }
        else
        {
			$this->error('支付失败，如果银行已经扣款，请联系客服：support_98qing@foxmail.com');
            return false;
        }
	}
	function changeorder($orderid){
		$order=D('order');
		$map['orderid']=$orderid;
		$rs=$order->where($map)->find();
		if($rs['status']=='1') return false;
		$data['status']='1';
		if($rs2=$order->where($map)->save($data)){
			$m=D('member');
		    if($result=$m->setInc('money','uid='.$rs['uid'],$rs['amount'])){
		    	$this->insert_charge($rs['uid'],'在线支付',$rs['amount'],'1');
			    return true;
		    }
		}else{
			return false;
		}
	}
}
?>
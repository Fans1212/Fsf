<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title><?php echo ($title); ?></title>
<base href="<?php echo ($root); ?>" />
<script type="text/javaScript" src="__PUBLIC__/Js/jquery.min.js"></script>
<script language="javascript" type="text/javascript" src="__PUBLIC__/Js/ChangeCity.js"></script> 
<script language="JavaScript" src="__PUBLIC__/Js/common.js"></script>
<?php echo ($css); ?>
</head>
<body>
<div id="head" class="cl">
    <div id="logo"></div>
    <div id="banner">
        <div id="div_login" style="display:none">
		欢迎您访问<strong><?php echo ($site_name); ?></strong>,最好的<strong>同城交友网站</strong>! <a href="Register/">注册</a> | <a href="<?php echo ($root); ?>">登录</a> |
	    </div>
        <div id="div_logined" style="display:none">
		<strong id="logined_username" ></strong>，<a href="Member/">会员中心</a> | 
        <a href="Member/logout">退出登录</a>
	</div>
    </div>
</div>
<div id="nav" class="cl">
    <ul>
       <li class="first"><a href="" class="on">首 页</a></li>
       <LI class=menu_line></LI>
       <li><a href="Search/local">同城异性</a></li>
       <LI class=menu_line></LI>
       <li><a href="Search/">搜索会员</a></li>
       <LI class=menu_line></LI>
       <li><a href="Diary/index/">会员日记</a></li>
       <LI class=menu_line></LI>
       <li><a href="Member/">个人中心</a></li>
       <LI class=menu_line></LI>
       <li><a href="Pay/Upgrade">升级VIP</a></li>
       <LI class=menu_line></LI>
       <li><a href="Help/">帮助中心</a></li>
       <LI class=menu_line></LI>
    </ul>
</div>
<link href="<?php echo ($tpl); ?>/Skins/jqModal.css" rel="stylesheet" type="text/css" />

<link href="../Skins/jqModal.css" rel="stylesheet" type="text/css" />
<link href="../Skins/base.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="__PUBLIC__/Js/jqModal.js"></script>
<?php $hot_user=$qylist->qyuser('gender:1;num:8;avatar:true;order:hits desc;');
$last_member=$qylist->qyuser('num:14;order:uid desc;'); ?>
<div class="row cl">
    <div id="login_form">
      <h1>会员登录</h1>
        <table cellpadding="0" cellspacing="1">
        <form action="?s=Member/checklogin" name="loginform" method="post" onsubmit="return loginCheck(this);">
           <tr>
              <td align="right">账户名</td>
              <td colspan="2"><input type="text" name="username" id="user" onclick="if(this.value=='用户名/邮箱'){this.value='';}" value=""  /></td>
           </tr>
           <tr>
              <td align="right">密　码</td>
              <td colspan="2"><input type="password" name="password" class="input_text" id="pwd" value=""  /></td>
           </tr>
           <tr>
              <td></td>
              <td><input type="image" src="<?php echo ($tpl); ?>/Skins/images/login.gif" name="submit" value=""  /></td>
              <td width="72" align="left"><a href="Register/sendPw" id="sendPw">找回密码</a></td>
           </tr>
           <tr>
              <td  colspan="3" align="center"><a href="?s=Register" class="reg">免费注册会员</a></td>
           </tr>
        </form>
        </table>
    </div>
    <div id="welcome">
        <h2>欢迎来到<?php echo ($site_name); ?>！</h2>
        <UL>
           <LI><?php echo ($site_name); ?>是一个让你寻找到合适伴侣的同城交友网站。
           <LI>工作压力大、生活空虚、寂寞，想找激情的异性伴侣？
           <LI>全国近四十万会员已经加入，注册即可找到您的同城伴侣。
           <LI>您的交友目的可以是纯友谊、聊友、异性恋人、征婚等...
        </UL>
        <div id="search">
           <h3>立即搜索您的意中人</h3>
           <table  cellpadding="0" cellspacing="1">
           <form action="index.php" method="get" name="myform">
           <input type="hidden" name="s" value="/Search/base" />
           <tr>
              <td width="130">我是一位：<br />
              <SELECT class="o">

   <OPTION value=0>请选择</OPTION>

   <OPTION selected  value=1>单身男性</OPTION>

   <OPTION value=2>单身女性</OPTION>

   <OPTION value=3>已婚男性</OPTION>

   <OPTION value=4>已婚女性</OPTION>

  </SELECT></td>
              <td>想要寻找：<br />
              <SELECT class="o" name="gender">

   <OPTION value=0>请选择</OPTION>

   <OPTION value=0>单身男性</OPTION>

   <OPTION selected  value=1>单身女性</OPTION>

   <OPTION value=0>已婚男性</OPTION>

   <OPTION value=1>已婚女性</OPTION>

  </SELECT></td>
           </tr>
           <tr>
              <td></td>
              <td></td>
           </tr>
           <tr>
              <td>年龄：<br /><SELECT id=txt_agefrom 

class=home_search_combo_small_new name="age1"><OPTION selected value=18>18</OPTION><OPTION 

  value=19>19</OPTION><OPTION value=20>20</OPTION><OPTION 

  value=21>21</OPTION><OPTION value=22>22</OPTION><OPTION 

  value=23>23</OPTION><OPTION value=24>24</OPTION><OPTION 

  value=25>25</OPTION><OPTION value=26>26</OPTION><OPTION 

  value=27>27</OPTION><OPTION value=28>28</OPTION><OPTION 

  value=29>29</OPTION><OPTION value=30>30</OPTION><OPTION 

  value=31>31</OPTION><OPTION value=32>32</OPTION><OPTION 

  value=33>33</OPTION><OPTION value=34>34</OPTION><OPTION 

  value=35>35</OPTION><OPTION value=36>36</OPTION><OPTION 

  value=37>37</OPTION><OPTION value=38>38</OPTION><OPTION 

  value=39>39</OPTION><OPTION value=40>40</OPTION><OPTION 

  value=41>41</OPTION><OPTION value=42>42</OPTION><OPTION 

  value=43>43</OPTION><OPTION value=44>44</OPTION><OPTION 

  value=45>45</OPTION><OPTION value=46>46</OPTION><OPTION 

  value=47>47</OPTION><OPTION value=48>48</OPTION><OPTION 

  value=49>49</OPTION><OPTION value=50>50</OPTION><OPTION 

  value=51>51</OPTION><OPTION value=52>52</OPTION><OPTION 

  value=53>53</OPTION><OPTION value=54>54</OPTION><OPTION 

  value=55>55</OPTION><OPTION value=56>56</OPTION><OPTION 

  value=57>57</OPTION><OPTION value=58>58</OPTION><OPTION 

  value=59>59</OPTION><OPTION value=60>60</OPTION><OPTION 

  value=61>61</OPTION><OPTION value=62>62</OPTION><OPTION 

  value=63>63</OPTION><OPTION value=64>64</OPTION><OPTION 

  value=65>65</OPTION><OPTION value=66>66</OPTION><OPTION 

  value=67>67</OPTION><OPTION value=68>68</OPTION><OPTION 

  value=69>69</OPTION><OPTION value=70>70</OPTION><OPTION 

  value=71>71</OPTION><OPTION value=72>72</OPTION><OPTION 

  value=73>73</OPTION><OPTION value=74>74</OPTION><OPTION 

  value=75>75</OPTION><OPTION value=76>76</OPTION><OPTION 

  value=77>77</OPTION><OPTION value=78>78</OPTION><OPTION 

  value=79>79</OPTION><OPTION value=80>80</OPTION></SELECT> TO <SELECT 

id=txt_ageto class=home_search_combo_small_new name="age2"> <OPTION value=80>80</OPTION><OPTION 

  value=79>79</OPTION><OPTION value=78>78</OPTION><OPTION 

  value=77>77</OPTION><OPTION value=76>76</OPTION><OPTION 

  value=75>75</OPTION><OPTION value=74>74</OPTION><OPTION 

  value=73>73</OPTION><OPTION value=72>72</OPTION><OPTION 

  value=71>71</OPTION><OPTION value=70>70</OPTION><OPTION 

  value=69>69</OPTION><OPTION value=68>68</OPTION><OPTION 

  value=67>67</OPTION><OPTION value=66>66</OPTION><OPTION 

  value=65>65</OPTION><OPTION value=64>64</OPTION><OPTION 

  value=63>63</OPTION><OPTION value=62>62</OPTION><OPTION 

  value=61>61</OPTION><OPTION value=60>60</OPTION><OPTION 

  value=59>59</OPTION><OPTION value=58>58</OPTION><OPTION 

  value=57>57</OPTION><OPTION value=56>56</OPTION><OPTION 

  value=55>55</OPTION><OPTION value=54>54</OPTION><OPTION 

  value=53>53</OPTION><OPTION value=52>52</OPTION><OPTION 

  value=51>51</OPTION><OPTION selected value=50>50</OPTION><OPTION 

  value=49>49</OPTION><OPTION value=48>48</OPTION><OPTION 

  value=47>47</OPTION><OPTION value=46>46</OPTION><OPTION 

  value=45>45</OPTION><OPTION value=44>44</OPTION><OPTION 

  value=43>43</OPTION><OPTION value=42>42</OPTION><OPTION 

  value=41>41</OPTION><OPTION value=40>40</OPTION><OPTION 

  value=39>39</OPTION><OPTION value=38>38</OPTION><OPTION 

  value=37>37</OPTION><OPTION value=36>36</OPTION><OPTION 

  value=35>35</OPTION><OPTION value=34>34</OPTION><OPTION 

  value=33>33</OPTION><OPTION value=32>32</OPTION><OPTION 

  value=31>31</OPTION><OPTION value=30>30</OPTION><OPTION 

  value=29>29</OPTION><OPTION value=28>28</OPTION><OPTION 

  value=27>27</OPTION><OPTION value=26>26</OPTION><OPTION 

  value=25>25</OPTION><OPTION value=24>24</OPTION><OPTION 

  value=23>23</OPTION><OPTION value=22>22</OPTION><OPTION 

  value=21>21</OPTION><OPTION value=20>20</OPTION><OPTION 

  value=19>19</OPTION><OPTION value=18>18</OPTION></SELECT></td>
              <td>地区：<br /><?php echo ($area); ?></td>
           </tr>
           <tr>
              <td><input type="submit" id="sub" value="立即搜索"  /> </td>
              <td></td>
           </tr>
           </form>
           </table>       
        </div>
    </div>
    <div id="index_right">&nbsp;</div>
</div>
<div class="row cl" id="hot_member">
    <h2></h2>
    <ul>
       <?php if(is_array($hot_user)): $i = 0; $__LIST__ = $hot_user;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$qy): ++$i;$mod = ($i % 2 )?><li><a href="<?php echo ($qy["url_pro"]); ?>" target="_blank"><img src="<?php echo avatar($qy['avatar'],1);?>" width="85" height="85" alt="<?php echo ($qy["nickname"]); ?>" /></a>
           <p><a href="<?php echo ($qy["url_pro"]); ?>" class="un" target="_blank"><?php echo ($qy["nickname"]); ?></a></p>      
       </li><?php endforeach; endif; else: echo "" ;endif; ?>
    </ul>
</div>
<div class="g960 mrt10"><a href="##"><img src="./Public/ads/95090_bak.gif"  /></a></div>
<div class="w960  mrt10">
<?php $hot_man=$qylist->qyuser('gender:0;num:14;avatar:true;order:uid desc;');
$hot_women=$qylist->qyuser('gender:1;num:14;avatar:true;order:uid desc;');
$last_user=$qylist->qyuser('num:14;order:uid desc;'); ?>
<ul class="tabs" id="tabs2">
       <li class="thistab"><a>活跃女会员</a></li>
       <li><a>活跃男会员</a></li>
       <li><a>最新会员推荐</a></li>
    </ul>
    <ul class="tab_conbox" id="tab_conbox2">
        <li class="tab_con" style="display:block">
        <?php if(is_array($hot_women)): $i = 0; $__LIST__ = $hot_women;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$qy): ++$i;$mod = ($i % 2 )?><a href="<?php echo ($qy["url_pro"]); ?>" class="mtop"  target="_blank"><img src="<?php echo avatar($qy['avatar'],1);?>" width="97" height="97" alt="<?php echo ($qy["nickname"]); ?>" onerror="this.src='./Public/Uploads/avatar/nophoto<?php echo ($qy["gender"]); ?>.gif'" /><br /><?php echo (($qy["nickname"])?($qy["nickname"]):'匿名用户'); ?></a>
           </dl><?php endforeach; endif; else: echo "" ;endif; ?>
        </li>
  
        <li class="tab_con">
        	<?php if(is_array($hot_man)): $i = 0; $__LIST__ = $hot_man;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$qy): ++$i;$mod = ($i % 2 )?><a href="<?php echo ($qy["url_pro"]); ?>" class="mtop"  target="_blank"><img src="<?php echo avatar($qy['avatar'],0);?>" width="97" height="97" alt="<?php echo ($qy["nickname"]); ?>"  onerror="this.src='./Public/Uploads/avatar/nophoto<?php echo ($qy["gender"]); ?>.gif'" /><br /><?php echo (($qy["nickname"])?($qy["nickname"]):'匿名用户'); ?></a>
           </dl><?php endforeach; endif; else: echo "" ;endif; ?>
        </li>
    
        <li class="tab_con">
        	<?php if(is_array($last_user)): $i = 0; $__LIST__ = $last_user;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$qy): ++$i;$mod = ($i % 2 )?><a href="<?php echo ($qy["url_pro"]); ?>" class="mtop"  target="_blank"><img src="<?php echo avatar($qy['avatar'],$qy['gender']);?>" width="97" height="97" alt="<?php echo ($qy["nickname"]); ?>"  onerror="this.src='./Public/Uploads/avatar/nophoto<?php echo ($qy["gender"]); ?>.gif'" /><br /><?php echo (($qy["nickname"])?($qy["nickname"]):'匿名用户'); ?></a>
           </dl><?php endforeach; endif; else: echo "" ;endif; ?>
        </li>
    </ul>


</div>
<div id="main" class="cl" style="display:none">
    <div id="main_l">
        <?php $diary_new=$qylist->qydiary('num:10;order:thedate desc;');
$diary_hot=$qylist->qydiary('num:10;order:viewtimes desc;');
$diary_enc=$qylist->qydiary('num:10;order:good desc;'); ?>
        <div id="diary_list" class="mrt10">
            <h2><UL>
                  <LI id=gtab1 class=on onMouseOver="gtab('gtab',1,4)">最 新</LI>
                  <LI id=gtab2 onMouseOver="gtab('gtab',2,4)">热 门</LI>
                  <LI id=gtab3 onMouseOver="gtab('gtab',3,4)">精 华</LI></UL>网友日记</h2>
            <UL style="DISPLAY: block" id="gtab_con1" class="content cl">
               <?php if(is_array($diary_new)): $i = 0; $__LIST__ = $diary_new;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$qy): ++$i;$mod = ($i % 2 )?><li><img src="<?php echo avatar($qy['avatar'],$qy['gender']);?>" width="50" height="50" />
                   <p class="t"><a href="<?php echo ($qy["url_article"]); ?>"><?php echo (msubstr($qy["diarytitle"],0,18)); ?></a></p>
                   <p>时间：<?php echo (date("Y-m-d H:i",$qy["thedate"])); ?></p>
                   <p class="u">作者：<a href="<?php echo ($qy["url_pro"]); ?>" target="_blank"><?php echo ($qy["nickname"]); ?></a> <?php echo ($qy["age"]); ?>岁，<?php echo ($qy["city"]); ?></p></li><?php endforeach; endif; else: echo "" ;endif; ?>
            </UL>
            <UL style="DISPLAY: none" id="gtab_con2" class="content cl">
               <?php if(is_array($diary_hot)): $i = 0; $__LIST__ = $diary_hot;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$qy): ++$i;$mod = ($i % 2 )?><li><img src="<?php echo avatar($qy['avatar'],$qy['gender']);?>" width="50" height="50" />
                   <p class="t"><a href="<?php echo ($qy["url_article"]); ?>"><?php echo (msubstr($qy["diarytitle"],0,18)); ?></a></p>
                   <p>时间：<?php echo (date("Y-m-d H:i",$qy["thedate"])); ?></p>
                   <p class="u">作者：<a href="<?php echo ($qy["url_pro"]); ?>" target="_blank"><?php echo ($qy["username"]); ?></a> <?php echo ($qy["age"]); ?>岁，<?php echo ($qy["city"]); ?></p></li><?php endforeach; endif; else: echo "" ;endif; ?>
            </UL>
            <UL style="DISPLAY: none" id="gtab_con3" class="content cl">
               <?php if(is_array($diary_enc)): $i = 0; $__LIST__ = $diary_enc;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$qy): ++$i;$mod = ($i % 2 )?><li><img src="<?php if(($qy["pic"])  !=  ""): ?><?php echo ($qy["pic"]); ?><?php else: ?><?php echo avatar($qy['avatar'],$qy['gender']);?><?php endif; ?>" width="50" height="50" />
                   <p class="t"><a href="<?php echo ($qy["url_article"]); ?>"><?php echo (msubstr($qy["diarytitle"],0,18)); ?></a></p>
                   <p>时间：<?php echo (date("Y-m-d H:i",$qy["thedate"])); ?></p>
                   <p class="u">作者：<a href="<?php echo ($qy["url_pro"]); ?>" target="_blank"><?php echo ($qy["username"]); ?></a> <?php echo ($qy["age"]); ?>岁，<?php echo ($qy["city"]); ?></p></li><?php endforeach; endif; else: echo "" ;endif; ?>
            </UL>
        </div> 
    </div>
    <!--main_r start-->
    <div id="main_r" class="mrt10">
        <H3 class="lastmember">最新会员</H3>
            <ul class="last_member_list">
            <?php if(is_array($last_member)): $i = 0; $__LIST__ = $last_member;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$qy): ++$i;$mod = ($i % 2 )?><li class="sex<?php echo ($qy["gender"]); ?>"><a href="<?php echo ($qy["url_pro"]); ?>"><?php echo ($qy["nickname"]); ?></a> <?php echo ($qy["age"]); ?>岁,<?php echo ($qy["city"]); ?></li><?php endforeach; endif; else: echo "" ;endif; ?>
            </ul>
    </div> 
    <div class="clear"></div>
</div>

<div id="yqlink">
<?php if(is_array($link)): $i = 0; $__LIST__ = $link;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$qy): ++$i;$mod = ($i % 2 )?><a href="<?php echo ($qy["url"]); ?>" target="_blank"><?php echo ($qy["name"]); ?></a><?php endforeach; endif; else: echo "" ;endif; ?>
</div>
<div id="sendpassword" class="jqmDialog"></div>
<SCRIPT type=text/javascript src="<?php echo ($tpl); ?>js/tab_nb.js"></SCRIPT>
<div class="cl"></div>
<div id="footer" class="cl mrt10">
    <div id="left">
      <p>声明：1、本站所有会员均为网友自行注册，如发现您的信息被他人恶意注册请举报给客服；<br />　　　2、会员在站外发生的一切行为均与本站无关，请谨慎交友，注意安全！</p>
      <p>客服邮箱：<?php echo ($site_email); ?></p>
    </div>
    <div id="right">
            <p><?php echo ($copyright); ?></p>
            <p><?php echo ($site_name); ?> All Rights Reservered </p>
            <p><?php echo ($site_stats); ?></p>
    </div>   
</div>
<div class="cl"></div>
</body>
</html>
<script type="text/javascript" >
 $().ready(function() {
  $('#sendpassword').jqm({ajax: 'Register/sendPw', trigger: 'a#sendPw'});
});

$(document).ready(function() {
	jQuery.jqtab = function(tabtit,tab_conbox,shijian) {
		$(tab_conbox).find("li").hide();
		$(tabtit).find("li:first").addClass("thistab").show(); 
		$(tab_conbox).find("li:first").show();
	
		$(tabtit).find("li").bind(shijian,function(){
		  $(this).addClass("thistab").siblings("li").removeClass("thistab"); 
			var activeindex = $(tabtit).find("li").index(this);
			$(tab_conbox).children().eq(activeindex).show().siblings().hide();
			return false;
		});
	
	};
	
	$.jqtab("#tabs2","#tab_conbox2","mouseenter");
	
});
</script>
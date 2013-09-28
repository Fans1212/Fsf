<?php
$config=require 'setting.php';

	/* 数据库设置 */
    $config['DB_TYPE']               = 'mysql';     // 数据库类型
	$config['DB_HOST']='localhost'; // 服务器地址
	$config['DB_NAME']='fsfriend';          // 数据库名
	$config['DB_USER']='root';      // 用户名
	$config['DB_PWD']='';          // 密码
	$config['DB_PORT']=3306;        // 端口
	$config['DB_PREFIX']='fs_';    // 数据库表前缀
    $config['DEFAULT_CHARSET']       ='utf-8';
	$config['APP_GROUP_LIST']        = '';      // 项目分组设定;多个组之间用逗号分隔;例如'Home;Admin'
	$config['USER_AUTH_ON']          =true;
	$config['USER_AUTH_TYPE']		=1;		// 默认认证类型 1 登录认证 2 实时认证
	$config['USER_AUTH_KEY']			='authId';	// 用户认证SESSION标记
    $config['ADMIN_AUTH_KEY']		='administrator';
	$config['USER_AUTH_MODEL']		='Member';	// 默认验证数据表模型
	$config['AUTH_PWD_ENCODER']		='md5';	// 用户认证密码加密方式
	$config['USER_AUTH_GATEWAY']  	='/Admin/login';	// 默认认证网关
	$config['NOT_AUTH_MODULE']		='';              		// 默认无需认证模块
	$config['REQUIRE_AUTH_MODULE']   ='Admin,AdminSys,AdminMod,AdminMem';		// 默认需要认证模块
	$config['NOT_AUTH_ACTION']		='amlo,verify,check';		// 默认无需认证操作
	$config['REQUIRE_AUTH_ACTION']='';		// 默认需要认证操作

    $config['NOT_LOGIN_ACTION']   ='login,checklogin,photo,user,diary,article,ajax';//不需要登录后操作的动作
    $config['NOT_LOGIN_MODULE']   ='index,help,register,search,diary';//不需要登录后操作的模块

    $config['GUEST_AUTH_ON']          = true;    // 是否开启游客授权访问
    $config['GUEST_AUTH_ID']           =    0;     // 游客的用户ID
	$config['SHOW_RUN_TIME']=false;			// 运行时间显示
	$config['SHOW_ADV_TIME']=true;			// 显示详细的运行时间
	$config['SHOW_DB_TIMES']=true;			// 显示数据库查询和写入次数
	$config['SHOW_CACHE_TIMES']=true;		// 显示缓存操作次数
	$config['SHOW_USE_MEM']=true;			// 显示内存开销
    $config['APP_DEBUG']				= false;	// 是否开启调试模式
    $config['DATA_CACHE_TIME']		= '600';
    $config['DB_LIKE_FIELDS']='title|remark';
	$config['RBAC_ROLE_TABLE']='think_role';
	$config['RBAC_USER_TABLE']	=	'think_role_user';
	$config['RBAC_ACCESS_TABLE'] =	'think_access';
	$config['RBAC_NODE_TABLE']	= 'think_node';

    $config['AUTH_KEY']          = 'QtAdrUcAaoMHvYMKTzDq';//Cookie密钥
    $config['COOKIE_PRE']       ='HaGBidPbnC';//cookie前戳
    $config['COOKIE_DOMAIN']     =''; //Cookie 作用域
    $config['COOKIE_PATH']       ='/'; //Cookie 作用路径

    $config['URL_MODEL']=3;
	$config['URL_PATHINFO_MODEL']=2;

    /* 静态缓存设置 */
    $config['HTML_CACHE_ON']			= false;   // 默认关闭静态缓存
    $config['HTML_CACHE_TIME']		= 60;      // 静态缓存有效期
    $config['HTML_READ_TYPE']        = 0;       // 静态缓存读取方式 0 readfile 1 redirect
    $config['HTML_FILE_SUFFIX']     = '.shtml';// 默认静态文件后缀
	
	$config['xgcms_version']         ='V2.1 Release 20130522 ';
	$config['love_version']         ='2.1';
	$config['buy_type']              ='未授权';
	$config['buy_time']              ='';
	$config['buy_key']               ='未授权'; 
	$config['buy_domain']             =''; 

return $config;
?>
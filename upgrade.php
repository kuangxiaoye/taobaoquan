<?php
if(!pdo_tableexists('ims_tiger_newhu_dwz')){
$sql1="
CREATE TABLE IF NOT EXISTS `ims_tiger_newhu_dwz` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `url` varchar(1000) NOT NULL COMMENT '网址',
  `durl` varchar(1000) NOT NULL COMMENT '缩短网址',
  `createtime` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `weid` (`weid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;
";
pdo_run($sql1);
}

if(!pdo_tableexists('ims_tiger_newhu_jl')){
$sql2="
CREATE TABLE IF NOT EXISTS `ims_tiger_newhu_jl` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
	  `uid` int(10) unsigned NOT NULL,
	  `weid` int(11) NOT NULL,
	  `type` int(11) NOT NULL COMMENT '0 积分  1 余额',
	  `typelx` tinyint(3) unsigned NOT NULL COMMENT '1签到    2关注邀请    3取消关注   4订单奖励',
	  `num` varchar(30) NOT NULL COMMENT '金额和积分 有正负',
	  `createtime` int(10) unsigned NOT NULL COMMENT '创建时间',
	  `remark` varchar(200) NOT NULL COMMENT '如：签到奖励',
	  `orderid` varchar(200) NOT NULL COMMENT '订单号',
	  PRIMARY KEY (`id`),
	  UNIQUE KEY `wuncid` (`weid`,`uid`,`num`,`createtime`),
	  KEY `weid` (`weid`),
	  KEY `type` (`type`),
	  KEY `typelx` (`typelx`),
	  KEY `uid` (`uid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;
";
pdo_run($sql2);
}

if(!pdo_tableexists('ims_tiger_newhu_fztype')){
$sql3="
CREATE TABLE IF NOT EXISTS `ims_tiger_newhu_fztype` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `px` int(10) NOT NULL DEFAULT '0',
  `title` varchar(50) NOT NULL,
  `fftype` int(3) NOT NULL DEFAULT '0' COMMENT '分类',
  `dtkcid` varchar(10) NOT NULL COMMENT '大淘客分类',
  `hlcid` varchar(10) NOT NULL COMMENT '互力分类',
  `picurl` varchar(255) NOT NULL COMMENT '封面',
  `picurl2` varchar(255) NOT NULL COMMENT '搜索界面图标',
  `wlurl` varchar(255) NOT NULL COMMENT '外链',
  `tag` varchar(250) NOT NULL COMMENT '男装 衬衫|男装 裤',
  `createtime` int(10) NOT NULL,
   PRIMARY KEY (`id`),
   KEY `weid` (`weid`),
   KEY `fftype` (`fftype`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2;
";
pdo_run($sql3);
}

if(!pdo_tableexists('ims_tiger_newhu_qiandao')){
$sql4="
CREATE TABLE IF NOT EXISTS `ims_tiger_newhu_qiandao` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `uid` int(1) NOT NULL COMMENT '用户id',
  `num` int(11) NOT NULL COMMENT '签到次数',
  `addtime` int(11) NOT NULL COMMENT '签到时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2;
";
pdo_run($sql4);
}

if(!pdo_tableexists('ims_tiger_newhu_xcxdh')){
$sql5="
CREATE TABLE IF NOT EXISTS `ims_tiger_newhu_xcxdh` (
		   `id` int(11) NOT NULL AUTO_INCREMENT,
		   `weid` int(11) DEFAULT 0,
		   `fztype` int(10) DEFAULT 0 COMMENT '1首页广告   2广告下面菜单  3会员中心菜单',
		   `type` int(10) DEFAULT 0 COMMENT '1.H5链接  2.商品分类  3活动',
		   `title` varchar(100) DEFAULT 0 COMMENT '名称',
		   `ftitle` varchar(100) DEFAULT 0 COMMENT '副名称',
		   `hd` varchar(20) DEFAULT 0 COMMENT '1聚划算 2淘抢购  3秒杀  4 叮咚抢  5 视频单  6品牌团 7官方推荐 8好券直播 9小编力荐',
		   `fqcat` int(11) DEFAULT 0 COMMENT '商品分类ID',
		   `pic` varchar(250) DEFAULT 0,
		   `xcxpage` varchar(1000) DEFAULT 0,	
		   `url` varchar(1000) DEFAULT 0,	
		   `createtime` int(10) NOT NULL,
		   PRIMARY KEY (`id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";
pdo_run($sql5);
}

if(!pdo_tableexists('ims_tiger_newhu_news')){
$sql6="
CREATE TABLE IF NOT EXISTS `ims_tiger_newhu_news` (
	   `id` int(11) NOT NULL AUTO_INCREMENT,
	   `weid` int(11) DEFAULT 0,
	   `px` int(11) DEFAULT 0,
	   `type` varchar(250) DEFAULT 0 COMMENT '1 公告  2帮助中心',
	   `title` varchar(250) DEFAULT 0,
	   `content` text NOT NULL,	 
	   `url` varchar(1000) DEFAULT 0,	
	   `createtime` int(10) NOT NULL,
	   PRIMARY KEY (`id`)
	) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";
pdo_run($sql6);
}

if(!pdo_tableexists('ims_tiger_newhu_xcxmobanmsg')){
$sql7="
CREATE TABLE IF NOT EXISTS `ims_tiger_newhu_xcxmobanmsg` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `weid` int(11) DEFAULT 0,
   `title` varchar(250) DEFAULT NULL COMMENT '模版标题',
   `mbid` varchar(250) DEFAULT NULL COMMENT '模版ID',
   `first` varchar(250) DEFAULT NULL COMMENT '头部内容',
   `firstcolor` varchar(100) DEFAULT NULL COMMENT '头部颜色',
   `zjvalue` text COMMENT '中间内容',
   `zjcolor` text COMMENT '中间颜色',
   `remark` varchar(250) DEFAULT NULL COMMENT '尾部内容',
   `remarkcolor` varchar(100) DEFAULT NULL COMMENT '尾部颜色',
   `turl` varchar(250) DEFAULT NULL NULL COMMENT '模版链接',
   `createtime` int(10) NOT NULL,
   KEY `weid` (`weid`),
   PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";
pdo_run($sql7);
}

if(!pdo_tableexists('ims_tiger_newhu_lxorder')){
$sql8="
CREATE TABLE IF NOT EXISTS `ims_tiger_newhu_lxorder` (
	   `id` int(11) NOT NULL AUTO_INCREMENT,
	   `weid` int(11) DEFAULT 0,
	   `addtime` varchar(50) DEFAULT NULL,
	   `jhtime` varchar(50) DEFAULT NULL,
	   `sgtime` varchar(50) DEFAULT NULL,
	   `newtel` varchar(50) DEFAULT NULL,
	   `xrzt` varchar(100) DEFAULT NULL,	
	   `ddlx` varchar(100) DEFAULT NULL,
	   `fxyh` varchar(100) DEFAULT NULL, 
	   `mtid` varchar(100) DEFAULT NULL, 
	   `mtname` varchar(100) DEFAULT NULL, 
	   `tgwid` varchar(100) DEFAULT NULL, 
	   `tgwname` varchar(100) DEFAULT NULL, 
	   `orderid` varchar(100) DEFAULT NULL, 
	   `createtime` int(10) NOT NULL,
	   KEY `weid` (`weid`),
	   KEY `tgwid` (`tgwid`),
	   KEY `addtime` (`addtime`),
	   KEY `sgtime` (`sgtime`),
	   KEY `xrzt` (`xrzt`),
	   KEY `ddlx` (`ddlx`),
	   PRIMARY KEY (`id`)
	) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";
pdo_run($sql8);
}

if(!pdo_tableexists('ims_tiger_newhu_fztype2')){
$sql8="
CREATE TABLE IF NOT EXISTS `ims_tiger_newhu_fztype2` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `px` int(10) NOT NULL DEFAULT '0',
  `title` varchar(50) NOT NULL COMMENT '二级分类名称', 
  `cid` int(5) NOT NULL DEFAULT '0' COMMENT '上级分类ID', 
  `picurl` varchar(255) NOT NULL COMMENT '封面',
  `wlurl` varchar(255) NOT NULL COMMENT '外链',
  `sokey` varchar(250) NOT NULL COMMENT '搜索关键词',
  `createtime` int(10) NOT NULL,
   PRIMARY KEY (`id`),
   KEY `weid` (`weid`),
   KEY `cid` (`cid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2;
";
pdo_run($sql8);
}

if(!pdo_tableexists('ims_tiger_newhu_xcxsend')){
$sql9="
CREATE TABLE IF NOT EXISTS `ims_tiger_newhu_xcxsend` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `weid` int(11) DEFAULT 0,
   `kfkey` varchar(100) DEFAULT NULL COMMENT '关键词',
   `type` int(10) DEFAULT 0 COMMENT '类型 1 H5链接',
   `title` varchar(100) DEFAULT NULL COMMENT '标题',
   `content` varchar(1000) DEFAULT 0 COMMENT '介绍',
   `url` varchar(1000) DEFAULT 0 COMMENT 'H5链接',
   `picurl` varchar(1000) DEFAULT 0,
   `createtime` int(10) NOT NULL,
   KEY `kfkey` (`kfkey`),
   KEY `weid` (`weid`),
   PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";
pdo_run($sql9);
}

if(!pdo_tableexists('ims_tiger_newhu_pddorder')){
$sql10="
CREATE TABLE IF NOT EXISTS `ims_tiger_newhu_pddorder` (
	   `id` int(11) NOT NULL AUTO_INCREMENT,
	   `weid` int(11) DEFAULT 0,
	   `order_sn` varchar(100) DEFAULT 0 COMMENT '',
	   `goods_id` varchar(100) DEFAULT 0 COMMENT '',
	   `goods_name` varchar(100) DEFAULT 0 COMMENT '',
	   `goods_thumbnail_url` varchar(255) DEFAULT 0 COMMENT '',
	   `goods_quantity` varchar(255) DEFAULT 0 COMMENT '',
	   `goods_price` varchar(255) DEFAULT 0 COMMENT '',
	   `order_amount` varchar(255) DEFAULT 0 COMMENT '',
	   `order_create_time` varchar(255) DEFAULT 0 COMMENT '',
	   `order_settle_time` varchar(255) DEFAULT 0 COMMENT '',
	   `order_verify_time` varchar(255) DEFAULT 0 COMMENT '',
	   `order_receive_time` varchar(255) DEFAULT 0 COMMENT '',
	   `order_pay_time` varchar(255) DEFAULT 0 COMMENT '',
	   `promotion_rate` varchar(20) DEFAULT 0 COMMENT '',
	   `promotion_amount` varchar(150) DEFAULT 0 COMMENT '',
	   `batch_no` varchar(150) DEFAULT 0 COMMENT '',
	   `order_status` varchar(150) DEFAULT '' COMMENT '',
	   `order_status_desc` varchar(150) DEFAULT 0 COMMENT '',
	   `verify_time` varchar(100) DEFAULT 0 COMMENT '',	   
	   `order_group_success_time` varchar(100) DEFAULT 0 COMMENT '',
	   `order_modify_at` varchar(100) DEFAULT 0 COMMENT '',
	   `status` varchar(100) DEFAULT 0 COMMENT '',
	   `type` varchar(100) DEFAULT 0 COMMENT '',
	   `group_id` varchar(100) DEFAULT 0 COMMENT '',
	   `auth_duo_id` varchar(100) DEFAULT 0 COMMENT '',
	   `custom_parameters` varchar(100) DEFAULT 0 COMMENT '',
	   `p_id` varchar(100) DEFAULT 0 COMMENT '',	
	   `createtime` varchar(255) NOT NULL,
	   KEY `weid` (`weid`),
		 KEY `order_sn` (`order_sn`),
		 KEY `p_id` (`p_id`),
		 KEY `order_modify_at` (`order_modify_at`),
	   PRIMARY KEY (`id`)
	) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";
pdo_run($sql10);
}

if(!pdo_tableexists('ims_tiger_newhu_pddtjorder')){
$sql11="
CREATE TABLE IF NOT EXISTS `ims_tiger_newhu_pddtjorder` (
	   `id` int(11) NOT NULL AUTO_INCREMENT,
	   `weid` int(11) DEFAULT 0,
	   `openid` varchar(255) DEFAULT 0 COMMENT '',
	   `nickname` varchar(255) DEFAULT 0 COMMENT '',
	   `avatar` varchar(255) DEFAULT 0 COMMENT '',
	   `jlnickname` varchar(255) DEFAULT 0 COMMENT '订单所有人',
	   `jlavatar` varchar(255) DEFAULT 0 COMMENT '订单所有人',
	   `memberid` varchar(255) DEFAULT 0 COMMENT '微擎会员编号',
	   `uid` varchar(20) DEFAULT NULL COMMENT '用户UID share表',
	   `orderid` varchar(255) DEFAULT 0 COMMENT '订单编号',
	   `price` varchar(255) DEFAULT 0 COMMENT '奖励金额',
	   `yongjin` varchar(255) DEFAULT 0 COMMENT '佣金',
	   `type` varchar(255) DEFAULT 0 COMMENT '类型 0 自有  1级奖励 2级奖励',  
	   `sh` varchar(255) DEFAULT 0 COMMENT '是否审核 0  1待返 2已返 3审核 4失效',  
	   `msg` varchar(255) DEFAULT 0 COMMENT '留言', 
	   `itemid` varchar(100) DEFAULT 0 COMMENT '商品ID',
	   `jl` varchar(20) DEFAULT 0 COMMENT '奖励金额或是积分',  
	   `jltype` int(10)  DEFAULT '0' COMMENT '0积分 1余额', 
	   `createtime` varchar(255) NOT NULL,
	   `cjdd` int(10) DEFAULT 0,
	     KEY `indx_weid` (`weid`),
	     KEY `itemid` (`itemid`),
	     KEY `indx_orderid` (`orderid`),
		 KEY `indx_openid` (`openid`),
		 KEY `uid` (`uid`),
	   PRIMARY KEY (`id`)
	) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";
pdo_run($sql11);
}
if(!pdo_tableexists('ims_tiger_newhu_appdh')){
$sql12="
CREATE TABLE IF NOT EXISTS `ims_tiger_newhu_appdh` (
		   `id` int(11) NOT NULL AUTO_INCREMENT,
		   `weid` int(11) DEFAULT 0,
			 `weid` int(11) DEFAULT 0 COMMENT '是否要登录显示  0不用  1需要登录',
		   `fztype` int(10) DEFAULT 0 COMMENT '1首页广告   2广告下面菜单  3图标下面图片 4底部菜单  5会员中心下方图标',
		   `type` int(10) DEFAULT 0 COMMENT '1.H5链接  2.商品分类  3活动',
		   `title` varchar(100) DEFAULT 0 COMMENT '名称',
		   `ftitle` varchar(100) DEFAULT 0 COMMENT '副名称',
		   `hd` varchar(20) DEFAULT 0 COMMENT '1聚划算 2淘抢购  3秒杀  4 叮咚抢  5 视频单  6品牌团 7官方推荐 8好券直播 9小编力荐',
		   `fqcat` int(11) DEFAULT 0 COMMENT '商品分类ID',
		   `pic` varchar(250) DEFAULT 0,
		   `apppage1` varchar(1000) DEFAULT NULL,
		   `apppage2` varchar(1000) DEFAULT 0,
		   `url` varchar(1000) DEFAULT 0,	
		   `createtime` int(10) NOT NULL,
		   PRIMARY KEY (`id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";
pdo_run($sql12);
}

if(!pdo_tableexists('ims_tiger_newhu_appset')){
$sql13="
	CREATE TABLE IF NOT EXISTS `ims_tiger_newhu_appset` (
		`id` int(11) NOT NULL AUTO_INCREMENT,
		`weid` int(11) DEFAULT '0',
		`appid` varchar(200) DEFAULT NULL COMMENT  'APP的APPID',
		`mchid` varchar(200) DEFAULT NULL COMMENT  '商户号',
		`jiamistr` varchar(200) DEFAULT NULL COMMENT  '加密字符',
		PRIMARY KEY (`id`),
		KEY `weid` (`weid`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
";
pdo_run($sql13);
}

if (!pdo_fieldexists('tiger_newhu_txlog', 'uid')) {
	pdo_query("ALTER TABLE " . tablename('tiger_newhu_txlog') . " ADD  `uid` varchar(10) NOT NULL COMMENT '用户UID';");
}
if (!pdo_fieldexists('tiger_newhu_share', 'apppid')) {
	pdo_query("ALTER TABLE " . tablename('tiger_newhu_share') . " ADD  `apppid` varchar(100) NOT NULL COMMENT 'APP PID';");
}
if (!pdo_fieldexists('tiger_newhu_share', 'apptgw')) {
	pdo_query("ALTER TABLE " . tablename('tiger_newhu_share') . " ADD  `apptgw` varchar(100) NOT NULL COMMENT 'APP 推广位';");
}
if (!pdo_fieldexists('tiger_newhu_share', 'unionid')) {
	pdo_query("ALTER TABLE " . tablename('tiger_newhu_share') . " ADD  `unionid` varchar(100) NOT NULL COMMENT 'unionid';");
}
if (!pdo_fieldexists('tiger_newhu_share', 'credit1')) {
	pdo_query("ALTER TABLE " . tablename('tiger_newhu_share') . " ADD  `credit1` decimal(10,2) unsigned NOT NULL COMMENT '积分';");
}
if (!pdo_fieldexists('tiger_newhu_share', 'credit2')) {
	pdo_query("ALTER TABLE " . tablename('tiger_newhu_share') . " ADD  `credit2` decimal(10,2) unsigned NOT NULL COMMENT '余额';");
}
if (!pdo_fieldexists('tiger_newhu_order', 'uid')) {
	pdo_query("ALTER TABLE " . tablename('tiger_newhu_order') . " ADD  `uid` varchar(100) NOT NULL COMMENT '用户UID';");
}
if (!pdo_fieldexists('tiger_newhu_share', 'lytype')) {
	pdo_query("ALTER TABLE " . tablename('tiger_newhu_share') . " ADD  `lytype` int(3) DEFAULT '0' COMMENT '0 公众号 1APP';");
}
if (!pdo_fieldexists('tiger_newhu_fztype', 'picurl2')) {
	pdo_query("ALTER TABLE " . tablename('tiger_newhu_fztype') . " ADD  `picurl2` varchar(100) NOT NULL COMMENT '搜索界面图标';");
}
if (!pdo_fieldexists('tiger_newhu_tkorder', 'wq')) {
	pdo_query("ALTER TABLE " . tablename('tiger_newhu_tkorder') . " ADD  `wq` varchar(10) NOT NULL COMMENT '维权订单 1 维权订单';");
}
if (!pdo_fieldexists('tiger_newhu_request', 'uid')) {
	pdo_query("ALTER TABLE " . tablename('tiger_newhu_request') . " ADD  `uid` varchar(100) NOT NULL COMMENT '用户UID';");
}
if (pdo_fieldexists('tiger_newhu_jl', 'num')) {
	pdo_query("ALTER TABLE " . tablename('tiger_newhu_jl') . " MODIFY COLUMN `num` decimal(30,2)  NOT NULL DEFAULT 0 COMMENT '金额和积分 有正负' AFTER `typelx`;");
}
if (!pdo_fieldexists('tiger_newhu_share', 'tbsbuid6')) {
	pdo_query("ALTER TABLE " . tablename('tiger_newhu_share') . " ADD  `tbsbuid6` varchar(10) NOT NULL COMMENT '订单后6位';");
}
if (!pdo_fieldexists('tiger_newhu_tkorder', 'tbsbuid6')) {
	pdo_query("ALTER TABLE " . tablename('tiger_newhu_tkorder') . " ADD  `tbsbuid6` varchar(10)  NOT NULL COMMENT '订单后6位';");
}
if (!pdo_fieldexists('tiger_newhu_order', 'cjdd')) {
	pdo_query("ALTER TABLE " . tablename('tiger_newhu_order') . " ADD  `cjdd` int(10)  DEFAULT '0'  COMMENT '抽奖订单 1 是订奖订单';");
}
if (!pdo_fieldexists('tiger_newhu_share', 'yaoqingma')) {
	pdo_query("ALTER TABLE " . tablename('tiger_newhu_share') . " ADD  `yaoqingma` varchar(50) NOT NULL COMMENT '';");
}
if (!pdo_fieldexists('tiger_newhu_order', 'uid')) {
	pdo_query("ALTER TABLE " . tablename('tiger_newhu_order') . " ADD  `uid` varchar(20)  DEFAULT '0'  COMMENT 'shareID';");
}
if (!pdo_fieldexists('tiger_newhu_order', 'jl')) {
	pdo_query("ALTER TABLE " . tablename('tiger_newhu_order') . " ADD  `jl` varchar(20)  DEFAULT '0'  COMMENT '奖励金额或是积分';");
}
if (!pdo_fieldexists('tiger_newhu_order', 'jltype')) {
	pdo_query("ALTER TABLE " . tablename('tiger_newhu_order') . " ADD  `jltype` int(10)  DEFAULT '0'  COMMENT '0积分 1余额';");
}
if (!pdo_fieldexists('tiger_newhu_tkorder', 'zdgd')) {
	pdo_query("ALTER TABLE " . tablename('tiger_newhu_tkorder') . " ADD  `zdgd` int(10) DEFAULT '0' COMMENT '1 已自动跟单订单';");
}
if (!pdo_fieldexists('tiger_newhu_order', 'itemid')) {
	pdo_query("ALTER TABLE " . tablename('tiger_newhu_order') . " ADD  `itemid` varchar(100)  DEFAULT '0'  COMMENT '商品ID';");
	pdo_query("ALTER TABLE " . tablename('tiger_newhu_order') . " ADD  KEY `itemid`(`itemid`);");
}
if (!pdo_fieldexists('tiger_newhu_share', 'xcxopenid')) {
	pdo_query("ALTER TABLE " . tablename('tiger_newhu_share') . " ADD  `xcxopenid` varchar(100) NOT NULL COMMENT '小程序OPENID';");
}
if (!pdo_fieldexists('tiger_newhu_share', 'pcopenid')) {
	pdo_query("ALTER TABLE " . tablename('tiger_newhu_share') . " ADD  `pcopenid` varchar(100) NOT NULL COMMENT 'PCOPENID';");
}
if (!pdo_fieldexists('tiger_newhu_share', 'appopenid')) {
	pdo_query("ALTER TABLE " . tablename('tiger_newhu_share') . " ADD  `appopenid` varchar(100) NOT NULL COMMENT 'xcxOPENID';");
}
if (!pdo_fieldexists('tiger_newhu_xcxdh', 'xcxpage')) {
	pdo_query("ALTER TABLE " . tablename('tiger_newhu_xcxdh') . " ADD  `xcxpage` varchar(1000) DEFAULT '0'  COMMENT '0';");
}
if (!pdo_fieldexists('tiger_newhu_xcxdh', 'appid')) {
	pdo_query("ALTER TABLE " . tablename('tiger_newhu_xcxdh') . " ADD  `appid` varchar(500) NOT NULL COMMENT 'APPid';");
}
if (!pdo_fieldexists('tiger_newhu_request', 'type')) {
	pdo_query("ALTER TABLE " . tablename('tiger_newhu_request') . " ADD `type` int(5) DEFAULT '0';");
}

if (!pdo_fieldexists('tiger_newhu_qiandao', 'formid')) {
	pdo_query("ALTER TABLE " . tablename('tiger_newhu_qiandao') . " ADD `formid` varchar(250) DEFAULT 0;");
}
if (!pdo_fieldexists('tiger_newhu_qiandao', 'xcxopenid')) {
	pdo_query("ALTER TABLE " . tablename('tiger_newhu_qiandao') . " ADD `xcxopenid` varchar(250) DEFAULT 0;");
}
if (!pdo_fieldexists('tiger_newhu_qiandao', 'type')) {
	pdo_query("ALTER TABLE " . tablename('tiger_newhu_qiandao') . " ADD `type` int(5) DEFAULT 0;");
}
if (!pdo_fieldexists('tiger_newhu_qiandao', 'nickname')) {
	pdo_query("ALTER TABLE " . tablename('tiger_newhu_qiandao') . " ADD `nickname` varchar(250) DEFAULT 0;");
}
if (!pdo_fieldexists('tiger_newhu_xcxmobanmsg', 'emphasis_keyword')) {
	pdo_query("ALTER TABLE " . tablename('tiger_newhu_xcxmobanmsg') . " ADD `emphasis_keyword` varchar(100) DEFAULT NULL;");
}
if (!pdo_fieldexists('tiger_newhu_xcxdh', 'kfkey')) {
	pdo_query("ALTER TABLE " . tablename('tiger_newhu_xcxdh') . " ADD  `kfkey` varchar(100) DEFAULT NULL COMMENT '';");
}
if (!pdo_fieldexists('tiger_newhu_fztype', 'cid')) {
	pdo_query("ALTER TABLE " . tablename('tiger_newhu_fztype') . " ADD  `cid` varchar(100) DEFAULT NULL COMMENT '';");
}
if (!pdo_fieldexists('tiger_newhu_fztype', 'sokey')) {
	pdo_query("ALTER TABLE " . tablename('tiger_newhu_fztype') . " ADD  `sokey` varchar(100) DEFAULT NULL COMMENT '';");
}
if (!pdo_fieldexists('tiger_newhu_request', 'uid')) {
	pdo_query("ALTER TABLE " . tablename('tiger_newhu_request') . " ADD  `uid` int(11)  DEFAULT '0'  COMMENT '';");
}
if (!pdo_fieldexists('tiger_newhu_txlog', 'uid')) {
	pdo_query("ALTER TABLE " . tablename('tiger_newhu_txlog') . " ADD  `uid` int(11)  DEFAULT '0'  COMMENT '';");
}
if (!pdo_fieldexists('tiger_newhu_lxorder', 'qrshtime')) {
	pdo_query("ALTER TABLE " . tablename('tiger_newhu_lxorder') . " ADD  `qrshtime` varchar(50) DEFAULT NULL;");
}

if (!pdo_fieldexists('tiger_newhu_share', 'pddpid')) {
	pdo_query("ALTER TABLE " . tablename('tiger_newhu_share') . " ADD  `pddpid` varchar(100) NOT NULL COMMENT '多多PID';");
}
if (!pdo_fieldexists('tiger_newhu_share', 'jdpid')) {
	pdo_query("ALTER TABLE " . tablename('tiger_newhu_share') . " ADD  `jdpid` varchar(100) NOT NULL COMMENT '京东PID';");
}

if (pdo_fieldexists('tiger_newhu_pddorder', 'p_id')) {
	pdo_query("ALTER TABLE " . tablename('tiger_newhu_pddorder') . " MODIFY COLUMN `promotion_rate` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '0' AFTER `order_pay_time`,MODIFY COLUMN `verify_time` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '0' AFTER `order_status_desc`,MODIFY COLUMN `order_group_success_time` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '0' AFTER `verify_time`,MODIFY COLUMN `order_modify_at` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '0' AFTER `order_group_success_time`,MODIFY COLUMN `status` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '0' AFTER `order_modify_at`,MODIFY COLUMN `type` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '0' AFTER `status`,MODIFY COLUMN `group_id` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '0' AFTER `type`,MODIFY COLUMN `auth_duo_id` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '0' AFTER `group_id`,MODIFY COLUMN `custom_parameters` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '0' AFTER `auth_duo_id`,MODIFY COLUMN `p_id` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '0' AFTER `custom_parameters`;");
}

if (!pdo_fieldexists('tiger_newhu_order', 'jluid')) {
	pdo_query("ALTER TABLE " . tablename('tiger_newhu_order') . " ADD  `jluid` varchar(20)  DEFAULT ''  COMMENT '';");
}
if (!pdo_fieldexists('tiger_newhu_order', 'jlnickname')) {
	pdo_query("ALTER TABLE " . tablename('tiger_newhu_order') . " ADD  `jlnickname` varchar(20)  DEFAULT ''  COMMENT '';");
}
if (!pdo_fieldexists('tiger_newhu_order', 'jlavatar')) {
	pdo_query("ALTER TABLE " . tablename('tiger_newhu_order') . " ADD  `jlavatar` varchar(500)  DEFAULT ''  COMMENT '';");
}
if (!pdo_fieldexists('tiger_newhu_goods', 'ordrsum')) {
	pdo_query("ALTER TABLE " . tablename('tiger_newhu_goods') . " ADD  `ordrsum` int(11)  DEFAULT '0'  COMMENT '';");
}
if (!pdo_fieldexists('tiger_newhu_goods', 'ordermsg')) {
	pdo_query("ALTER TABLE " . tablename('tiger_newhu_goods') . " ADD  `ordermsg` varchar(200)  DEFAULT ''  COMMENT '';");
}
if (!pdo_fieldexists('tiger_newhu_appdh', 'flname')) {
	pdo_query("ALTER TABLE " . tablename('tiger_newhu_appdh') . " ADD  `flname` varchar(200)  DEFAULT ''  COMMENT '';");
}
if (!pdo_fieldexists('tiger_newhu_pddtjorder', 'jluid')) {
	pdo_query("ALTER TABLE " . tablename('tiger_newhu_pddtjorder') . " ADD  `jluid` varchar(20)  DEFAULT ''  COMMENT '';");
}
if (!pdo_fieldexists('tiger_newhu_jdtjorder', 'jluid')) {
	pdo_query("ALTER TABLE " . tablename('tiger_newhu_jdtjorder') . " ADD  `jluid` varchar(20)  DEFAULT ''  COMMENT '';");
}
if (!pdo_fieldexists('tiger_newhu_appdh', 'h5title')) {
	pdo_query("ALTER TABLE " . tablename('tiger_newhu_appdh') . " ADD  `h5title` varchar(100)  DEFAULT ''  COMMENT '';");
}

if (!pdo_fieldexists('tiger_newhu_fztype', 'shztype')) {
	pdo_query("ALTER TABLE " . tablename('tiger_newhu_fztype') . " ADD  `shztype` varchar(10)  DEFAULT ''  COMMENT '实惠猪分类';");
}
if (!pdo_fieldexists('tiger_newhu_fztype', 'ysdtype')) {
	pdo_query("ALTER TABLE " . tablename('tiger_newhu_fztype') . " ADD  `ysdtype` varchar(10)  DEFAULT ''  COMMENT '一手单分类';");
}
if (!pdo_fieldexists('tiger_newhu_fztype', 'tkzstype')) {
	pdo_query("ALTER TABLE " . tablename('tiger_newhu_fztype') . " ADD  `tkzstype` varchar(10)  DEFAULT ''  COMMENT '淘客助手分类';");
}
if (!pdo_fieldexists('tiger_newhu_fztype', 'qtktype')) {
	pdo_query("ALTER TABLE " . tablename('tiger_newhu_fztype') . " ADD  `qtktype` varchar(10)  DEFAULT ''  COMMENT '轻淘客分类';");
}
if (!pdo_fieldexists('tiger_newhu_fztype', 'hpttype')) {
	pdo_query("ALTER TABLE " . tablename('tiger_newhu_fztype') . " ADD  `hpttype` varchar(10)  DEFAULT ''  COMMENT '好品推分类';");
}
if (!pdo_fieldexists('tiger_newhu_fztype', 'tkjdtype')) {
	pdo_query("ALTER TABLE " . tablename('tiger_newhu_fztype') . " ADD  `tkjdtype` varchar(10)  DEFAULT ''  COMMENT '淘客基地分类';");
}
if (!pdo_fieldexists('tiger_newhu_appset', 'appzfip')) {
	pdo_query("ALTER TABLE " . tablename('tiger_newhu_appset') . " ADD  `appzfip` varchar(100)  DEFAULT ''  COMMENT '';");
}
if (!pdo_fieldexists('tiger_newhu_appset', 'appfximg')) {
	pdo_query("ALTER TABLE " . tablename('tiger_newhu_appset') . " ADD  `appfximg` varchar(200)  DEFAULT ''  COMMENT '';");
}
if (!pdo_fieldexists('tiger_newhu_appset', 'appfxtitle')) {
	pdo_query("ALTER TABLE " . tablename('tiger_newhu_appset') . " ADD  `appfxtitle` varchar(100)  DEFAULT ''  COMMENT '';");
}
if (!pdo_fieldexists('tiger_newhu_appset', 'appfxcontent')) {
	pdo_query("ALTER TABLE " . tablename('tiger_newhu_appset') . " ADD  `appfxcontent` varchar(200)  DEFAULT ''  COMMENT '';");
}
if (!pdo_fieldexists('tiger_newhu_tkorder', 'forderid')) {
	pdo_query("ALTER TABLE " . tablename('tiger_newhu_tkorder') . " ADD  `forderid` varchar(100) DEFAULT '0' COMMENT '父订单编号';");
}
if (!pdo_fieldexists('tiger_newhu_tkorder', 'ly')) {
	pdo_query("ALTER TABLE " . tablename('tiger_newhu_tkorder') . " ADD  `ly` varchar(10) DEFAULT '0' COMMENT '来源0联盟表格 1 联盟API';");
}
if (!pdo_fieldexists('tiger_newhu_tkorder', 'zorderid')) {
	pdo_query("ALTER TABLE " . tablename('tiger_newhu_tkorder') . " ADD  `zorderid` varchar(100) DEFAULT '0' COMMENT '子订单编号';");
}
if (!pdo_fieldexists('tiger_newhu_share', 'tbkpidtype')) {
	pdo_query("ALTER TABLE " . tablename('tiger_newhu_share') . " ADD  `tbkpidtype` int(11) DEFAULT '0' COMMENT '1已经绑定tbuid 0未绑定';");
}
if (!pdo_fieldexists('tiger_newhu_tksign', 'tbnickname')) {
	pdo_query("ALTER TABLE " . tablename('tiger_newhu_tksign') . " ADD  `tbnickname` varchar(200) DEFAULT NULL COMMENT '';");
}
if (!pdo_fieldexists('tiger_newhu_share', 'tbuid')) {
	pdo_query("ALTER TABLE " . tablename('tiger_newhu_share') . " ADD  `tbuid` varchar(20) DEFAULT '0' COMMENT '';");
}
?>
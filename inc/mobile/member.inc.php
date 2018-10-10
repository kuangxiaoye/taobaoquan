<?php
global $_W, $_GPC;
        $cfg = $this->module['config'];
        
        $fans=$this->islogin();
        if(empty($fans['tkuid'])){
        	$fans = mc_oauth_userinfo();
	        if(empty($fans)){
	        	//$loginurl=$_W['siteroot'].str_replace('./','app/',$this->createMobileurl('login'))."&m=tiger_newhu"."&tzurl=".urlencode($tktzurl);        	  	  	     	  	  	 
       	  	  //	 header("Location: ".$loginurl); 
       	  	  	// exit;
	        }	        
        }
        
        
        


		//$fans = $_W['fans'];
        $dluid=$_GPC['dluid'];//share id
        $mc=mc_fetch($fans['openid']);


        $member=$this->getmember($fans,$mc['uid']);
        if($cfg['zdgdtype']==1){
        	$this->getzdorder($member,$cfg);
        }        
//      
//      $fans = mc_oauth_userinfo();
//      echo "<pre>";
//      print_r($fans);
//      exit;


        $fzlist = pdo_fetchall("select * from ".tablename($this->modulename."_cdtype")." where weid='{$_W['uniacid']}' and fftype=0  order by px desc");
        $dblist = pdo_fetchall("select * from ".tablename($this->modulename."_cdtype")." where weid='{$_W['uniacid']}' and fftype=1  order by px desc");//底部菜单
        //$member = pdo_fetch("select * from ".tablename($this->modulename."_share")." where weid='{$_W['uniacid']}' and from_user='{$fans['openid']}'");

        if($member['dltype']==1){
           $contfans = pdo_fetchcolumn("SELECT COUNT(id) FROM " . tablename($this->modulename.'_share')." where weid='{$_W['uniacid']}' and helpid='{$member['id']}'");//下级粉丝
           $contorder = pdo_fetchcolumn("SELECT COUNT(id) FROM " . tablename($this->modulename.'_tkorder')." where weid='{$_W['uniacid']}' and tgwid='{$member['tgwid']}'");//我的订单
        }


        $fxordercont = pdo_fetchcolumn("SELECT SUM(price) FROM " . tablename("tiger_wxdaili_yjlog")." where weid='{$_W['uniacid']}' and uid='{$member['id']}' ");//累计收益
        $dfyn = pdo_fetchcolumn("SELECT COUNT(id) FROM " . tablename($this->modulename.'_order')." where weid='{$_W['uniacid']}' and openid='{$fans['openid']}' and sh=1");//累计收益
        if(empty($fxordercont)){
        	$fxordercont='0.00';
        }else{
        	$fxordercont=number_format($fxordercont, 2, '.', '');
        }


        //print_r($member);


       include $this->template ( 'user/member' );

<?php
       global $_W, $_GPC;
      
      	$dluid=$_GPC['dluid'];
        if($_GPC['uid']){
	    	$uid=$_GPC['uid'];
	    }else{
	    	$fans=$this->islogin();
	        if(empty($fans['tkuid'])){
	        	$fans = mc_oauth_userinfo();	        
	        }
	    }
        
		
		//PID绑定
		if(!empty($dluid)){
          $share=pdo_fetch("select * from ".tablename('tiger_newhu_share')." where weid='{$_W['uniacid']}' and id='{$dluid}'");
        }else{
          //$fans=mc_oauth_userinfo();
          $openid=$fans['openid'];
          if(empty($uid)){
          	$zxshare=pdo_fetch("select * from ".tablename('tiger_newhu_share')." where weid='{$_W['uniacid']}' and from_user='{$openid}'");
          }else{
          	$zxshare=pdo_fetch("select * from ".tablename('tiger_newhu_share')." where weid='{$_W['uniacid']}' and id='{$uid}'");
          }
        }
        if($zxshare['dltype']==1){
            if(!empty($zxshare['dlptpid'])){
               $cfg['ptpid']=$zxshare['dlptpid'];
               $cfg['qqpid']=$zxshare['dlqqpid'];
            }
            
        }else{
           if(!empty($zxshare['helpid'])){//查询有没有上级
                 $sjshare=pdo_fetch("select * from ".tablename('tiger_newhu_share')." where weid='{$_W['uniacid']}' and dltype=1 and id='{$zxshare['helpid']}'");           
            }
        }
        

        if(!empty($sjshare['dlptpid'])){
            if(!empty($sjshare['dlptpid'])){
              $cfg['ptpid']=$sjshare['dlptpid'];
              $cfg['qqpid']=$sjshare['dlqqpid'];
            }   
        }else{
           if($share['dlptpid']){
               if(!empty($share['dlptpid'])){
                 $cfg['ptpid']=$share['dlptpid'];
                 $cfg['qqpid']=$share['dlqqpid'];
               }       
            }
        }
		//结束
		
		$pid=$cfg['ptpid'];
		
        
        $pic="https://img.alicdn.com/tfs/TB1.VOfa3mTBuNjy1XbXXaMrVXa-440-180.jpg";
        $title="2018天猫38女王节—主会场（超级红包）";
		$url="https://s.click.taobao.com/t?e=m%3D2%26s%3Dqvn29nj3A8AcQipKwQzePCperVdZeJviK7Vc7tFgwiFRAdhuF14FMbFXlvcTq7z12XPP23XswEPiCpeCUkeJc3yKwiqD2WK0Bmf7LSR66agynDOG7DdOVfB0z8qlrv%2BjIOoxaTwwbqtICD7BBQSQLWfjkSvFgPxzFlK8cfBdY27LQPEJLPAWAV3WV7X8X8sdxgxdTc00KD8%3D&src=tiger_tiger&pid=";

        $rhyurl=$url.$pid;

        $tkl=$this->tkl($rhyurl,$pic,"2018天猫38女王节—主会场（超级红包）！");
 
      
       $userAgent = $_SERVER['HTTP_USER_AGENT'];
		if (!strpos($userAgent, 'MicroMessenger')) {
			Header("Location:".$tzurl); 
		}
       
		
		if(strpos($_SERVER['HTTP_USER_AGENT'], 'iPhone')||strpos($_SERVER['HTTP_USER_AGENT'], 'iPad')){
		    $sjlx=1;
		}else{
		   $sjlx=2;
		}


       //echo $tkl;

       include $this->template ( 'tbgoods/style99/c11view' );
?>
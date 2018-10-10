<?php
       global $_W, $_GPC;
       $cfg = $this->module['config'];
       $type=$_GPC['type'];
       $pid=$_GPC['pid'];
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
       
		$pid=$cfg['ptpid'];


       switch ($type)
        {
        case 1:
          $pic=$_W['siteroot']."addons/tiger_newhu/template/mobile/tbgoods/style99/imgs/c1111/1.jpg";
          $title="2018天猫618理想生活狂欢节—主会场（超级红包）";
          $url="https://s.click.taobao.com/t?e=m%3D2%26s%3D9DgdpSlWXmgcQipKwQzePCperVdZeJviK7Vc7tFgwiFRAdhuF14FMWOl1RQ4nI4eLNtGcKAVN3IzuTGl041QAiSgiNrL%2BlBqfjxl6AIniNrNBBPoRbo3zrJmDFg1AUKYMdC6dqcurFTn%2FMTVSe63bV0hO9fBPG8oGc375vDYVY6lLmcSHKfaX1CxRDt3HQiw&cpsrc=tiger_tiger&pid=";
          break;
        case 2:
          $pic=$_W['siteroot']."addons/tiger_newhu/template/mobile/tbgoods/style99/imgs/c1111/2.jpg";
          $title="2018天猫618理想生活狂欢节—热卖尖货榜";
          $url="https://s.click.taobao.com/t?e=m%3D2%26s%3DmjyfsF%2B%2BLJscQipKwQzePCperVdZeJviK7Vc7tFgwiFRAdhuF14FMaKwsKEUh0SC6oVw0fmJ46szuTGl041QArZl6wCRgzZSsd%2B%2Ff4Fhw9b%2BScqIfI2efIcwm0SklHVYDsUzzx1%2FLF7Ko7kdsGMDQ13%2BYd2Pn1RvhdMP%2F8bT7b%2FHxW0czwyKXVdS3U01X54kJ2SDIs7fFgQaBhAm8NVrljKul7KZig465%2FzE1Unut21dITvXwTxvKBnN%2B%2Bbw2FWOpS5nEhyn2l9QsUQ7dx0IsA%3D%3D&cpsrc=tiger_tiger&pid=";
          break;
        case 3:
          $pic=$_W['siteroot']."addons/tiger_newhu/template/mobile/tbgoods/style99/imgs/c1111/3.jpg";
          $title="2018年618理想生活狂欢季——聚划算主会场";
          $url="https://s.click.taobao.com/t?e=m%3D2%26s%3DM8ClU%2BA2ak8cQipKwQzePCperVdZeJviK7Vc7tFgwiFRAdhuF14FMeHTMIyQytbjA8hgolRGHkczuTGl041QArZl6wCRgzZSsd%2B%2Ff4Fhw9b%2BScqIfI2efA2ZG%2FJy2H6CSrVENS8WQsMD6HBJ0PC6gffkQcHiJuSe%2B%2BYoUZoLK2yV%2BdYQa3JD4MmGxbEsvRI%2FXn5aA1Uf2lSDrkS7lV49nwBXgSuv7Sv7KtseCpInTy%2FkXkdea8kgYymBZ5ZWikN7omfkDJRs%2BhU%3D&cpsrc=tiger_tiger&pid=";
          break;
        case 4:
          $pic=$_W['siteroot']."addons/tiger_newhu/template/mobile/tbgoods/style99/imgs/c1111/4.jpg";
          $title="2018年618理想生活狂欢节—消费电子主会场";
          $url="https://s.click.taobao.com/t?e=m%3D2%26s%3DkGSu3WRpBKQcQipKwQzePCperVdZeJviK7Vc7tFgwiFRAdhuF14FMfS1CEJn%2B0RgKaFSJePRPq4zuTGl041QArZl6wCRgzZSsd%2B%2Ff4Fhw9b%2BScqIfI2efLAHkyFZtBihz9lLFQ4I8t6NLXcxMs8%2BX82jrT2jN9cyYvkEwZtRZLi6UwX8E8pftgYR%2BSspXxZ%2FxBPp2aQlEeb0Y9qbnP3pVnxPh9IGwtCqiVD0P%2Bbi5bSdzyyO9CIkVX5MB04OE0Gnjidl2Va2D8n4B0ovWyHEjw%3D%3D&cpsrc=tiger_tiger&pid=";
          break;
          case 5:
          $pic=$_W['siteroot']."addons/tiger_newhu/template/mobile/tbgoods/style99/imgs/c1111/5.jpg";
          $title="2018年618理想生活狂欢季-天猫超市主会场";
          $url="https://s.click.taobao.com/t?e=m%3D2%26s%3DxBFJf5sAOv4cQipKwQzePCperVdZeJviK7Vc7tFgwiFRAdhuF14FMX3BBOjHKcow6oVw0fmJ46szuTGl041QArZl6wCRgzZSsd%2B%2Ff4Fhw9b%2BScqIfI2efAWoyWT4exyCzGXLiYPCzppRBcvKrTlJPBbM0jSU8se2fwcfO2O6L7iOTe7uzUz56b4H8lVVAG5MwmwBo7yXhWInqo%2FvJyccNQ%3D%3D&cpsrc=tiger_tiger&pid=";
          break;
        case 6:
          $pic=$_W['siteroot']."addons/tiger_newhu/template/mobile/tbgoods/style99/imgs/c1111/6.jpg";
          $title="2018年618理想生活狂欢季—手机主会场";
          $url="https://s.click.taobao.com/t?e=m%3D2%26s%3Do%2BhCG%2FD%2BzKQcQipKwQzePCperVdZeJviK7Vc7tFgwiFRAdhuF14FMcZ%2F8I0VEzcmEmSL9pJEyt0zuTGl041QArZl6wCRgzZSsd%2B%2Ff4Fhw9b%2BScqIfI2efLAHkyFZtBihz9lLFQ4I8t6NLXcxMs8%2BX82jrT2jN9cyYvkEwZtRZLgNMe3DSW3qyWiX%2FEqCESahxBPp2aQlEeb0Y9qbnP3pVnxPh9IGwtCqiVD0P%2Bbi5bSdzyyO9CIkVX5MB04OE0Gnjidl2Va2D8n4B0ovWyHEjw%3D%3D&cpsrc=tiger_tiger&pid=";
          break;
        case 7:
          $pic=$_W['siteroot']."addons/tiger_newhu/template/mobile/tbgoods/style99/imgs/c1111/7.jpg";
          $title="2018年618理想生活狂欢节-潮流个护会场";
          $url="https://s.click.taobao.com/t?e=m%3D2%26s%3DXq6hD%2F%2F5tH0cQipKwQzePCperVdZeJviK7Vc7tFgwiFRAdhuF14FMZ58JhrjSzofo5VSCmutSbQzuTGl041QArZl6wCRgzZSsd%2B%2Ff4Fhw9b%2BScqIfI2efLAHkyFZtBihz9lLFQ4I8t6NLXcxMs8%2BX82jrT2jN9cyYvkEwZtRZLgNMe3DSW3qyexmNZwd%2BPHKxBPp2aQlEeb0Y9qbnP3pVnxPh9IGwtCqiVD0P%2Bbi5bSdzyyO9CIkVX5MB04OE0Gnjidl2Va2D8n4B0ovWyHEjw%3D%3D&cpsrc=tiger_tiger&pid=";
          break;
        case 8:
          $pic=$_W['siteroot']."addons/tiger_newhu/template/mobile/tbgoods/style99/imgs/c1111/8.jpg";
          $title="2018年618理想生活狂欢节—内衣主会场";
          $url="https://s.click.taobao.com/t?e=m%3D2%26s%3DwEtbRaEoT58cQipKwQzePCperVdZeJviK7Vc7tFgwiFRAdhuF14FMY3h9pVfNLP1AYPhC%2B%2FgaE8zuTGl041QArZl6wCRgzZSsd%2B%2Ff4Fhw9b%2BScqIfI2efLAHkyFZtBihz9lLFQ4I8t6NLXcxMs8%2BX82jrT2jN9cyYvkEwZtRZLgNMe3DSW3qyXwlOOSJdnmUxBPp2aQlEeb0Y9qbnP3pVnxPh9IGwtCqiVD0P%2Bbi5bSdzyyO9CIkVX5MB04OE0Gnjidl2Va2D8n4B0ovWyHEjw%3D%3D&cpsrc=tiger_tiger&pid=";
          break;
        case 9:
          $pic=$_W['siteroot']."addons/tiger_newhu/template/mobile/tbgoods/style99/imgs/c1111/9.jpg";
          $title="2018年618理想生活狂欢节—家饰家纺会场";
          $url="https://s.click.taobao.com/t?e=m%3D2%26s%3DKhpgbGzJuoMcQipKwQzePCperVdZeJviK7Vc7tFgwiFRAdhuF14FMQeFNhOVaHstAYPhC%2B%2FgaE8zuTGl041QArZl6wCRgzZSsd%2B%2Ff4Fhw9b%2BScqIfI2efLAHkyFZtBihz9lLFQ4I8t6NLXcxMs8%2BX82jrT2jN9cyYvkEwZtRZLi6UwX8E8pfttBzsM44pUJsTlT4SLzYDiBT2M421%2BABgTvflh4%2Fhqj8SRyzdJVE%2BZM%3D&cpsrc=tiger_tiger&pid=";
          break;
        case 10:
          $pic=$_W['siteroot']."addons/tiger_newhu/template/mobile/tbgoods/style99/imgs/c1111/10.jpg";
          $title="2018年618理想生活狂欢节-母婴主会场";
          $url="https://s.click.taobao.com/t?e=m%3D2%26s%3D%2BfKjwuNVQ%2FUcQipKwQzePCperVdZeJviK7Vc7tFgwiFRAdhuF14FMdvWfW39fGtZLNtGcKAVN3IzuTGl041QArZl6wCRgzZSsd%2B%2Ff4Fhw9b%2BScqIfI2efLAHkyFZtBihz9lLFQ4I8t6NLXcxMs8%2BX82jrT2jN9cyYvkEwZtRZLgNMe3DSW3qycL%2BQcrOFsTWxBPp2aQlEeb0Y9qbnP3pVnxPh9IGwtCqiVD0P%2Bbi5bSdzyyO9CIkVX5MB04OE0Gnjidl2Va2D8n4B0ovWyHEjw%3D%3D&cpsrc=tiger_tiger&pid=";
          break;
        case 11:
          $pic=$_W['siteroot']."addons/tiger_newhu/template/mobile/tbgoods/style99/imgs/c1111/11.jpg";
          $title="2018年618理想生活狂欢节—天猫国际全球尖货";
          $url="https://s.click.taobao.com/t?e=m%3D2%26s%3Dpejhcbqu%2F6YcQipKwQzePCperVdZeJviK7Vc7tFgwiFRAdhuF14FMQoDgsRkrMM8G%2FN6Jx5YutMzuTGl041QArZl6wCRgzZSsd%2B%2Ff4Fhw9b%2BScqIfI2efLAHkyFZtBihz9lLFQ4I8t6NLXcxMs8%2BX82jrT2jN9cyYvkEwZtRZLjxsUKJpYjcQ9jw%2F768xBS6xBPp2aQlEeb0Y9qbnP3pVnxPh9IGwtCqiVD0P%2Bbi5bSdzyyO9CIkVX5MB04OE0Gnjidl2Va2D8n4B0ovWyHEjw%3D%3D&cpsrc=tiger_tiger&pid=";
          break;
        case 12:
          $pic=$_W['siteroot']."addons/tiger_newhu/template/mobile/tbgoods/style99/imgs/c1111/12.jpg";
          $title="2018年618理想生活狂欢节—天猫国际官方直营";
          $url="https://s.click.taobao.com/t?e=m%3D2%26s%3D6SRBqXQUcNkcQipKwQzePCperVdZeJviK7Vc7tFgwiFRAdhuF14FMRANXuUxU6IDEmSL9pJEyt0zuTGl041QArZl6wCRgzZSsd%2B%2Ff4Fhw9b%2BScqIfI2efLAHkyFZtBihz9lLFQ4I8t6NLXcxMs8%2BX82jrT2jN9cyYvkEwZtRZLjxsUKJpYjcQ3fHAVSSudPExBPp2aQlEeb0Y9qbnP3pVnxPh9IGwtCqiVD0P%2Bbi5bSdzyyO9CIkVX5MB04OE0Gnjidl2Va2D8n4B0ovWyHEjw%3D%3D&cpsrc=tiger_tiger&pid=";
          break;    
        default:
          $pic=$_W['siteroot']."addons/tiger_newhu/template/mobile/tbgoods/style99/imgs/c1111/1.jpg";
          $title="2018天猫618理想生活狂欢节—主会场（超级红包）";$url="https://s.click.taobao.com/t?e=m%3D2%26s%3D9DgdpSlWXmgcQipKwQzePCperVdZeJviK7Vc7tFgwiFRAdhuF14FMWOl1RQ4nI4eLNtGcKAVN3IzuTGl041QAiSgiNrL%2BlBqfjxl6AIniNrNBBPoRbo3zrJmDFg1AUKYMdC6dqcurFTn%2FMTVSe63bV0hO9fBPG8oGc375vDYVY6lLmcSHKfaX1CxRDt3HQiw&cpsrc=tiger_tiger&pid=";
        }

        //$pic=$_W['siteroot']."addons/tiger_newhu/template/mobile/tbgoods/style99/imgs/c1111/c1111.jpg";

        $rhyurl=$url.$pid;

        $tkl=$this->tkl($rhyurl,$pic,"2018天猫618理想生活狂欢节！");
 
      
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
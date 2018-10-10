<?php
 global $_W,$_GPC;
			 $cfg = $this->module['config'];
			 $miyao=$_GPC['miyao'];
			 if($miyao!==$cfg['miyao']){
				exit(json_encode(array('status' => 2, 'content' => '密钥错误，请检测秘钥，或更新缓存！')));
			 }
			 //file_put_contents(IA_ROOT."/addons/tiger_newhu/inc/mobile/log--ordernews.txt","\n dantiao1:".$_GPC['content'],FILE_APPEND);

			 $content=htmlspecialchars_decode($_GPC['content']);
			 //file_put_contents(IA_ROOT."/addons/tiger_newhu/inc/mobile/log--ordernews.txt","\n dantiao2:".$content,FILE_APPEND);
       $news=@json_decode($content, true);
			 
			 if(empty($news[0]['trade_id'])){//单条
						 if($news["tk_status"]==3){
							 $orderzt="订单结算";
						 }elseif($news["tk_status"]==12){
							 $orderzt="订单付款";
						 }elseif($news["tk_status"]==13){
							 $orderzt="订单失效";
						 }elseif($news["tk_status"]==14){
							 $orderzt="订单成功";
						 }
						 
						 if($news['terminal_type']==1){
							 $pt="PC";
						 }else{
							 $pt="无线";
						 }	 
						 $data=array(
								'weid'=>$_W['uniacid'],
								'addtime'=>strtotime($news["create_time"]),
								'orderid'=>$news["trade_parent_id"],
								'forderid'=>$news["trade_parent_id"],
								'zorderid'=>$news["trade_id"],
								'numid'=>$news["num_iid"],
								'shopname'=>$news["seller_shop_title"],
								'title'=>$news["item_title"],						
								'orderzt'=>$orderzt,
								'srbl'=>($news["income_rate"]*100)."%",
								'fcbl'=>"",
								'fkprice'=>$news["alipay_total_price"],
								'xgyg'=>$news["pub_share_pre_fee"],
								'jstime'=>strtotime($news["earning_time"]),
								'pt'=> $pt,
								'mtid'=>$news["site_id"],//媒体ID
								'mttitle'=>$news["site_name"],//媒体名称
								'tgwid'=>$news["adzone_id"],//推广位ID
								'tgwtitle'=>$news["adzone_name"],//推广位名称
								'ly'=>1,
								'createtime'=>TIMESTAMP,
						 );
						 $ord=pdo_fetch ( 'select orderid,orderzt from ' . tablename ( $this->modulename . "_tkorder" ) . " where weid='{$_W['uniacid']}' and numid='{$news['num_iid']}'  and (orderid='{$news['trade_id']}' or orderid='{$news['trade_parent_id']}')" );
						 if(empty($ord)){
							  if(!empty($data['addtime'])){
							  	$a=pdo_insert ($this->modulename . "_tkorder", $data );
							  } 
						 }else{
							 $a=pdo_update($this->modulename . "_tkorder",$data, array ('orderid' =>$news['trade_id'],'numid'=>$news["num_iid"],'weid'=>$_W['uniacid']));
						 }						 
				 file_put_contents(IA_ROOT."/addons/tiger_newhu/inc/mobile/log--ordernews.txt","\n dantiao:".$news["adzone_name"],FILE_APPEND);
			 }else{//多条
					foreach($news as $k=>$v){
						if($v["tk_status"]==3){
							$orderzt="订单结算";
						}elseif($v["tk_status"]==12){
							$orderzt="订单付款";
						}elseif($v["tk_status"]==13){
							$orderzt="订单失效";
						}elseif($v["tk_status"]==14){
							$orderzt="订单成功";
						}
						
						if($v['terminal_type']==1){
							$pt="PC";
						}else{
							$pt="无线";
						}	 
						$data=array(
							'weid'=>$_W['uniacid'],
							'addtime'=>strtotime($v["create_time"]),
							'orderid'=>$v["trade_parent_id"],
							'forderid'=>$v["trade_parent_id"],
							'zorderid'=>$v["trade_id"],
							'numid'=>$v["num_iid"],
							'shopname'=>$v["seller_shop_title"],
							'title'=>$v["item_title"],						
							'orderzt'=>$orderzt,
							'srbl'=>($v["income_rate"]*100)."%",
							'fcbl'=>"",
							'fkprice'=>$v["alipay_total_price"],
							'xgyg'=>$v["pub_share_pre_fee"],
							'jstime'=>strtotime($v["earning_time"]),
							'pt'=> $pt,
							'mtid'=>$v["site_id"],//媒体ID
							'mttitle'=>$v["site_name"],//媒体名称
							'tgwid'=>$v["adzone_id"],//推广位ID
							'tgwtitle'=>$v["adzone_name"],//推广位名称
							'ly'=>1,
							'createtime'=>TIMESTAMP,
						);
						$ord=pdo_fetch ( 'select orderid,orderzt from ' . tablename ( $this->modulename . "_tkorder" ) . " where weid='{$_W['uniacid']}' and numid='{$v['num_iid']}'  and (orderid='{$v['trade_id']}'  or orderid='{$v['trade_parent_id']}')" );
						if(empty($ord)){
							if(!empty($data['addtime'])){
								$a=pdo_insert ($this->modulename . "_tkorder", $data );
							} 
						}else{
							$a=pdo_update($this->modulename . "_tkorder",$data, array ('orderid' =>$v['trade_id'],'numid'=>$v["num_iid"],'weid'=>$_W['uniacid']));
						}	
					}
				 //file_put_contents(IA_ROOT."/addons/tiger_newhu/inc/mobile/log--ordernews.txt","\n 多条:".$news[0]["adzone_name"],FILE_APPEND);
			 }

			exit(json_encode(array('status' => 1, 'content' => '成功')));

<?php
        global $_W, $_GPC;
        $pindex = max(1, intval($_GPC['page']));
		$psize = 20;
        $order=$_GPC['order'];
        $zt=$_GPC['zt'];
        $op=$_GPC['op'];
        $dd=$_GPC['dd'];
        
        if($_GPC['tb']==1){//一键同步订单
	        	include IA_ROOT . "/addons/tiger_newhu/inc/sdk/tbk/pdd.php"; 
				$pddset=pdo_fetch("select * from ".tablename('tuike_pdd_set')." where weid='{$_W['uniacid']}'");
				$owner_name=$pddset['ddjbbuid'];				
				$start_time=strtotime($_GPC['starttime']);
				$end_time=strtotime($_GPC['endtime']);
				$page=$_GPC['page'];
				if(empty($page)){
					$page=1;
				}	
         // $page=2;
				//echo $end_time;
				$res=pddtgworder1($owner_name,$page,$start_time,$end_time,$p_id);	
               // echo $page;
               // echo "<pre>";
               // print_r($res);
         // exit;
				if(!empty($orderlist['error_response']['error_msg'])){
					message($orderlist['error_response']['error_msg'], referer(), 'success');
					//echo $orderlist['error_response']['error_msg'];
					//exit;
				}
				$orderlist=$res['order_list_get_response']['order_list'];				
				
				
				foreach($orderlist as $k=>$v){
					$row = pdo_fetch("SELECT * FROM " . tablename($this->modulename.'_pddorder') . " WHERE weid='{$_W['uniacid']}' and order_sn='{$v['order_sn']}'");
					$data=array(
						"weid"=>$_W['uniacid'],
						"order_sn" =>$v['order_sn'],
			            "goods_id" => $v['goods_id'],
			            "goods_name" => $v['goods_name'],
			            "goods_thumbnail_url" => $v['goods_thumbnail_url'],
			            "goods_quantity" => $v['goods_quantity'],
			            "goods_price" => $v['goods_price']/100,
			            "order_amount" => $v['order_amount']/100,
			            "order_create_time" => $v['order_create_time'],
			            "order_settle_time" => $v['order_settle_time'],
			            "order_verify_time" => $v['order_verify_time'],
			            "order_receive_time" => $v['order_receive_time'],
			            "order_pay_time" => $v['order_pay_time'],
			            "promotion_rate" => $v['promotion_rate']/10,
			            "promotion_amount" => $v['promotion_amount']/100,
			            "batch_no" => $v['batch_no'],
			            "order_status" =>$v['order_status'],
			            "order_status_desc" => $v['order_status_desc'],
			            "verify_time" => $v['verify_time'],
			            "order_group_success_time" => $v['order_group_success_time'],
			            "order_modify_at" => $v['order_modify_at'],
			            "status" => $v['status'],
			            "type" => $v['type'],
			            "group_id" => $v['group_id'],
			            "auth_duo_id" => $v['auth_duo_id'],
			            "custom_parameters" => $v['custom_parameters'],
			            "p_id" => $v['p_id'],
					);					
					if (!empty($row)){
	                    pdo_update($this->modulename."_pddorder", $data, array('order_sn' => $v['order_sn'],'weid'=>$_W['uniacid']));
	                    echo "更新订单：".$data['order_sn']."成功<br>";
	                }else{
	                    pdo_insert($this->modulename."_pddorder", $data);
	                    echo "新订单入库：".$data['order_sn']."成功<br>";
	                }
				}
				if(!empty($orderlist)){
					message('温馨提示：请不要关闭页面，采集任务正在进行中！', $this->createWebUrl('pddorder', array('tb' =>1,'page' => $page + 1,'starttime'=>$_GPC['starttime'],'endtime'=>$_GPC['endtime'])), 'success');
				}else{
                    message('更新订单成功', $this->createWebUrl('pddorder', array('page' => $page + 1,'starttime'=>$_GPC['starttime'],'endtime'=>$_GPC['endtime'])), 'success');
				}	
                        	
        }


       if($op=='seach'){
           if (!empty($order)){
              $where .= " and (order_sn='{$order}' or p_id='{$order}')  ";
            }
//            if (!empty($zt)){
//              $where .= " and orderzt='{$zt}'";
//            }
           $day=date('Y-m-d');
           $day=strtotime($day);//今天0点时间戳  

            if($dd==1){//当日
                $where.=" and order_modify_at>{$day}";        
            }
            if($dd==2){//昨天
                $day3=strtotime(date("Y-m-d",strtotime("-1 day")));//昨天时间
                $where.=" and order_modify_at>{$day3} and order_modify_at<{$day}";        
            }
            if($dd==3){//本月
                // 本月起始时间:
                $bbegin_time = strtotime(date("Y-m-d H:i:s", mktime ( 0, 0, 0, date ( "m" ), 1, date ( "Y" ))));
                $where.=" and order_modify_at>{$bbegin_time}";        
            }
            if($dd==4){
                 // 上月起始时间:
                 //$sbegin_time = strtotime(date('Y-m-01 00:00:00',strtotime('-1 month')));//上个月开始时间
                 $sbegin_time = strtotime(date('Y-m-d', mktime(0,0,0,date('m')-1,1,date('Y'))));//上个月开始时间
                 $send_time = strtotime(date("Y-m-d 23:59:59", strtotime(-date('d').'day')));//上个月结束时间
                 if($zt==2){//按结算时间算
                   $where.="and order_modify_at>{$sbegin_time} and jstime<{$send_time}";
                 }else{
                   $where.="and order_modify_at>{$sbegin_time} and addtime<{$send_time}";
                 }
                 
            }
            if($zt==6){//已支付
              $where.=" and order_status=0";
            }
            if($zt==1){//已成团
              $where.=" and order_status=1";
            }
            if($zt==2){//确认收货
              $where.=" and order_status=2";
            }
            if($zt==5){//已经结算
            	$where.=" and order_status=5";
            }
       
       }
       
        echo $where ;

        

		$list = pdo_fetchall("select * from ".tablename($this->modulename."_pddorder")." where weid='{$_W['uniacid']}' {$where} order by id desc LIMIT " . ($pindex - 1) * $psize . ",{$psize}");
		$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->modulename.'_pddorder')." where weid='{$_W['uniacid']}'  {$where}");
		$pager = pagination($total, $pindex, $psize);
//      $totalsum = pdo_fetchcolumn("SELECT sum(xgyg) FROM " . tablename($this->modulename.'_pddorder')." where weid='{$_W['uniacid']}'  {$where}");



        include $this->template ( 'pddorder' );


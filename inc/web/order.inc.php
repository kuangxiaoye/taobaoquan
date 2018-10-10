<?php
global $_W, $_GPC;

$order=$_GPC['order'];
$dj=$_GPC['dj'];
$name=$_GPC['name'];
$uid=$_GPC['uid'];



        if (!empty($order)){
          $where .= " and (orderid like '%{$order}%')";
        }


        if($dj==1){
          $where .= " and (type = 0) ";
        }elseif($dj==2){
          $where .= " and (type = 1) ";
        }elseif($dj==3){   
            $where .= " and (type = 3) ";       
        }

        if (!empty($name)){
          $where .= " and (nickname like '%{$name}%' or openid='{$name}')";
        }
        if (!empty($uid)){
          $whereuid .= " and uid={$uid}";
        }
        //echo $where;

$page=$_GPC['page'];

 $cfg = $this->module['config'];
        $pindex = max(1, intval($page));
		$psize = 20;
		$list = pdo_fetchall("select * from ".tablename($this->modulename."_order")." where weid='{$_W['uniacid']}' {$where} {$whereuid} order by id desc LIMIT " . ($pindex - 1) * $psize . ",{$psize}");
		$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->modulename.'_order')." where weid='{$_W['uniacid']}' {$where} {$whereuid}");
		$pager = pagination($total, $pindex, $psize);
        include $this->template ( 'order' );
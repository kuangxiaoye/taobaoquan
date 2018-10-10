<?php
global $_W;
        global $_GPC;
        $cfg = $this->module['config'];
        if($cfg['mmtype']==2){//云商品库
        	include IA_ROOT . "/addons/tiger_newhu/inc/sdk/tbk/goodsapi.php"; 
        	$fzlist=getclass($_W,$cfg['ptpid'],'');//全部分类
        }else{
        	$fzlist = pdo_fetchall("select * from ".tablename("tiger_newhu_fztype")." where weid='{$_W['uniacid']}'  order by px desc");
        }
        
        $operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
        if ($operation == 'post'){
            $id = intval($_GPC['id']);
            if (!empty($id)){
                $item = pdo_fetch("SELECT * FROM " . tablename("tiger_newhu_appdh") . " WHERE id = :id" , array(':id' => $id));
                if (empty($item)){
                    message('抱歉，广告不存在或是已经删除！', '', 'error');
                }
            }
            if (checksubmit('submit')){
                if (empty($_GPC['title'])){
                    message('请输入广告名称！');
                }
				
				$fq=explode("||",$_GPC['fqcat']);

                $data = array(
                    'weid' => $_W['uniacid'], 
                    'title' => $_GPC['title'], 
                    'ftitle' => $_GPC['ftitle'], 
                    'fztype' => $_GPC['fztype'], //1 首页  2 会员中心
                    'url' => $_GPC['url'], 
					'h5title' => $_GPC['h5title'], 
                    'pic' => $_GPC['pic'], 
                    'hd' => $_GPC['hd'], 
					//'xz' => $_GPC['xz'], 
                    'apppage1'=>$_GPC['apppage1'],
                    'apppage2'=>$_GPC['apppage2'],
                    'fqcat' =>$fq[0], //分类ID
					'flname' =>$fq[1], //分类名称
                    'type' => $_GPC['type'], //1 H5链接  2APP分类     
                    //'appid' => $_GPC['appid'],              
                    'createtime' => TIMESTAMP,);  
//                  echo "<pre>";
//                  	print_r($data);
//                  	exit;            
                if (!empty($id)){
                    pdo_update("tiger_newhu_appdh", $data, array('id' => $id));
                }else{
                    pdo_insert("tiger_newhu_appdh", $data);
                }
                message('图标更新成功！', $this -> createWebUrl('appdh', array('op' => 'display')), 'success');
            }
        }else if ($operation == 'delete'){
            $id = intval($_GPC['id']);
            $row = pdo_fetch("SELECT id FROM " . tablename("tiger_newhu_appdh") . " WHERE id = :id", array(':id' => $id));
            if (empty($row)){
                message('抱歉，图标/广告' . $id . '不存在或是已经被删除！');
            }
            pdo_delete("tiger_newhu_appdh", array('id' => $id));
            message('删除成功！', referer(), 'success');
        }else if ($operation == 'display'){
            $condition = '';
            $list = pdo_fetchall("SELECT * FROM " . tablename("tiger_newhu_appdh") . " WHERE weid = '{$_W['uniacid']}'  ORDER BY id desc");           
        }
        include $this -> template('appdh');
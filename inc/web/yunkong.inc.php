<?php
global $_W,$_GPC;
$cfg = $this->module['config'];
        $tksign = pdo_fetch("SELECT * FROM " . tablename($this->modulename."_tksign") . " WHERE  tbuid='{$cfg['tbuid']}'");
        $tksignlist = pdo_fetchall ( 'select * from ' . tablename ($this->modulename . "_tksign" ) . " where weid='{$_W['uniacid']}' order by id desc" );
        include $this -> template('yunkong');
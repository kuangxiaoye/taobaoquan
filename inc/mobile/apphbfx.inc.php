<?php
		global $_W, $_GPC;
		$cfg=$this->module['config'];
		$uid=$_GPC['u'];//上级UID
		$yq=$_GPC['yq'];//上级UID
		include $this -> template('user/apphbfx');

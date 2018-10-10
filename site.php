<?php

if (defined('IN_IA') || exit('Access Denied')) {
}
require_once IA_ROOT . '/addons/tiger_newhu/lib/excel.php';
require_once IA_ROOT . '/addons/tiger_newhu/inc/sdk/tbk/TopSdk.php';
class tiger_newhuModuleSite extends WeModuleSite
{
    public $table_request = 'tiger_newhu_request';
    public $table_goods = 'tiger_newhu_goods';
    public $table_ad = 'tiger_newhu_ad';
    private static $t_sys_member = 'mc_members';
    public function __construct()
    {
        session_start();
        global $_W;
        global $_GPC;
        $c = $_GPC['c'];
        $do = $_GPC['do'];
        $cfg = $this->module['config'];

        $cfg = $this->module['config'];
        if (!empty($cfg['tknewurl'])) {
            if ($c == 'entry') {
                $_W['siteroot'] = $cfg['tknewurl'];
            }
        }
        if ($c == 'entry') {
            if ($cfg['logintype'] == 1) {
                if (empty($_SESSION['tkuid'])) {
                    $tktzurl = $_W['siteurl'];
                    $loginurl = $_W['siteroot'] . str_replace('./', 'app/', $this->createMobileurl('login')) . '&m=tiger_newhu' . '&tzurl=' . urlencode($tktzurl);
                    if ($do != 'login' && $do != 'bdlogin') {
                        header('Location: ' . $loginurl);
                        exit(0);
                    }
                }
            }
        }
    }
    public function get_server_ip()
    {
        if (isset($_SERVER)) {
            if ($_SERVER['SERVER_ADDR']) {
                $server_ip = $_SERVER['SERVER_ADDR'];
            } else {
                $server_ip = $_SERVER['LOCAL_ADDR'];
            }
        } else {
            $server_ip = getenv('SERVER_ADDR');
        }
        return $server_ip;
    }
    public function doMobileCs2()
    {
        global $_W;
        global $_GPC;
        $cfg = $this->module['config'];
        $appkey = $cfg['tkAppKey'];
        $secret = $cfg['tksecretKey'];
        $c = new TopClient();
        $c->appkey = $appkey;
        $c->secretKey = $secret;
        $req = new WirelessShareTpwdCreateRequest();
        $tpwd_param = new GenPwdIsvParamDto();
        $tpwd_param->ext = '{"":""}';
        $tpwd_param->logo = 'http://m.taobao.com/xxx.jpg';
        $tpwd_param->url = 'https://mos.m.taobao.com/activity_newer?from=pub&pid=mm_0_0_0';
        $tpwd_param->text = '超值活动，惊喜活动多多';
        $tpwd_param->user_id = '24234234234';
        $req->setTpwdParam(json_encode($tpwd_param));
        $resp = $c->execute($req);
        echo '<pre>';
        print_r($resp);
        exit(0);
    }
    public function getzdorder($member, $cfg)
    {
        global $_W;
        if (empty($member['tbsbuid6'])) {
            return '';
        }
        $tbsbuid6 = $member['tbsbuid6'];
        $ztime = mktime(0, 0, 0, date('m'), date('d') - date('w') + 1 - 20, date('Y'));
        $tkorlist = pdo_fetchall('select * from ' . tablename($this->modulename . '_tkorder') . ' where weid=\'' . $_W['uniacid'] . '\' and tbsbuid6=\'' . $tbsbuid6 . '\' and addtime>\'' . $ztime . '\' and orderzt<>\'订单失效\' and zdgd<>1 order by id desc');
        foreach ($tkorlist as $k => $tkorder) {
            $order = pdo_fetch('select * from ' . tablename($this->modulename . '_order') . ' where weid=\'' . $_W['uniacid'] . '\' and orderid=\'' . $tkorder['orderid'] . '\' and itemid=\'' . $tkorder['numid'] . '\'');
            if (empty($order['id'])) {
                if ($cfg['fxtype'] == 1) {
                    $jltype = 0;
                } elseif ($cfg['fxtype'] == 2) {
                    $jltype = 1;
                }
                $sh = 3;
                if ($cfg['fxtype'] == 1) {
                    $jltype = 0;
                } elseif ($cfg['fxtype'] == 2) {
                    $jltype = 1;
                }
                if ($cfg['fxtype'] == 1) {
                    if ($cfg['gdfxtype'] == 1) {
                        $jl = $cfg['zgf'];
                    } else {
                        $jl = intval($tkorder['xgyg'] * $cfg['zgf'] / 100 * $cfg['jfbl']);
                    }
                } elseif ($cfg['fxtype'] == 2) {
                    if ($cfg['gdfxtype'] == 1) {
                        $jl = $cfg['zgf'];
                    } else {
                        $jl = $tkorder['xgyg'] * $cfg['zgf'] / 100;
                        $jl = number_format($jl, 2, '.', '');
                    }
                }
                $orderid = $tkorder['orderid'];
                $data = array('weid' => $_W['uniacid'], 'openid' => $member['from_user'], 'memberid' => $member['openid'], 'uid' => $member['id'], 'nickname' => $member['nickname'], 'avatar' => $member['avatar'], 'orderid' => $orderid, 'itemid' => $tkorder['numid'], 'jl' => $jl, 'jltype' => $jltype, 'sh' => $sh, 'yongjin' => $tkorder['xgyg'], 'type' => 0, 'createtime' => TIMESTAMP);
                $resorder = pdo_insert($this->modulename . '_order', $data);
                if ($resorder != false) {
                    pdo_update($this->modulename . '_tkorder', array('zdgd' => 1), array('weid' => $_W['uniacid'], 'orderid' => $orderid));
                }
                if (!empty($member['helpid'])) {
                    if (!empty($cfg['yjf'])) {
                        if ($cfg['fxtype'] == 1) {
                            if ($cfg['gdfxtype'] == 1) {
                                $jl = $cfg['yjf'];
                            } else {
                                $jl = intval($tkorder['xgyg'] * $cfg['yjf'] / 100 * $cfg['jfbl']);
                            }
                        } elseif ($cfg['fxtype'] == 2) {
                            if ($cfg['gdfxtype'] == 1) {
                                $jl = $cfg['yjf'];
                            } else {
                                $jl = $tkorder['xgyg'] * $cfg['yjf'] / 100;
                                $jl = number_format($jl, 2, '.', '');
                            }
                        }
                        $yjmember = pdo_fetch('select * from ' . tablename($this->modulename . '_share') . ' where weid=\'' . $_W['uniacid'] . '\' and id=\'' . $member['helpid'] . '\' order by id desc');
                        $yjtxmsg = str_replace('#昵称#', $member['nickname'], $cfg['yjtxmsg']);
                        $yjtxmsg = str_replace('#订单号#', $orderid, $yjtxmsg);
                        $yjtxmsg = str_replace('#金额#', $jl, $yjtxmsg);
                        $this->postText($yjmember['from_user'], $yjtxmsg);
                        $data2 = array('weid' => $_W['uniacid'], 'openid' => $yjmember['from_user'], 'memberid' => $yjmember['openid'], 'uid' => $yjmember['id'], 'nickname' => $yjmember['nickname'], 'jl' => $jl, 'jltype' => $jltype, 'avatar' => $yjmember['avatar'], 'jlnickname' => $member['nickname'], 'jlavatar' => $member['avatar'], 'orderid' => $orderid, 'yongjin' => $tkorder['xgyg'], 'itemid' => $tkorder['numid'], 'type' => 1, 'sh' => $sh, 'createtime' => TIMESTAMP);
                        $order = pdo_fetchall('select * from ' . tablename($this->modulename . '_order') . ' where weid=\'' . $_W['uniacid'] . '\' and type=1 and orderid=' . $orderid . ' and itemid=\'' . $tkorder['numid'] . '\'');
                        if (empty($order)) {
                            pdo_insert($this->modulename . '_order', $data2);
                        }
                    }
                    if (!empty($yjmember['helpid'])) {
                        if (!empty($cfg['ejf'])) {
                            if ($cfg['fxtype'] == 1) {
                                if ($cfg['gdfxtype'] == 1) {
                                    $jl = $cfg['ejf'];
                                } else {
                                    $jl = intval($tkorder['xgyg'] * $cfg['ejf'] / 100 * $cfg['jfbl']);
                                }
                            } elseif ($cfg['fxtype'] == 2) {
                                if ($cfg['gdfxtype'] == 1) {
                                    $jl = $cfg['ejf'];
                                } else {
                                    $jl = $tkorder['xgyg'] * $cfg['ejf'] / 100;
                                    $jl = number_format($jl, 2, '.', '');
                                }
                            }
                            $rjmember = pdo_fetch('select * from ' . tablename($this->modulename . '_share') . ' where weid=\'' . $_W['uniacid'] . '\' and id=\'' . $yjmember['helpid'] . '\' order by id desc');
                            $ejtxmsg = str_replace('#昵称#', $member['nickname'], $cfg['ejtxmsg']);
                            $ejtxmsg = str_replace('#订单号#', $orderid, $ejtxmsg);
                            $ejtxmsg = str_replace('#金额#', $jl, $ejtxmsg);
                            $this->postText($rjmember['from_user'], $ejtxmsg);
                            $data3 = array('weid' => $_W['uniacid'], 'openid' => $rjmember['from_user'], 'memberid' => $rjmember['openid'], 'uid' => $rjmember['id'], 'nickname' => $rjmember['nickname'], 'jl' => $jl, 'jltype' => $jltype, 'avatar' => $rjmember['avatar'], 'jlnickname' => $member['nickname'], 'jlavatar' => $member['avatar'], 'orderid' => $orderid, 'yongjin' => $tkorder['xgyg'], 'itemid' => $tkorder['numid'], 'type' => 2, 'sh' => $sh, 'createtime' => TIMESTAMP);
                            $order = pdo_fetchall('select * from ' . tablename($this->modulename . '_order') . ' where weid=\'' . $_W['uniacid'] . '\' and type=2 and orderid=' . $orderid . '  and itemid=\'' . $tkorder['numid'] . '\'');
                            if (empty($order)) {
                                pdo_insert($this->modulename . '_order', $data3);
                            }
                        }
                    }
                }
            }
        }
    }
    public function getmember($fans, $wqid, $helpid)
    {
        global $_W;
        if (empty($fans['openid'])) {
            return '';
        }
        if (!empty($fans['unionid'])) {
            $share = pdo_fetch('select * from ' . tablename('tiger_newhu_share') . ' where weid=\'' . $_W['uniacid'] . '\' and unionid=\'' . $fans['unionid'] . '\'');
            if (!empty($share['id'])) {
                $updata = array('from_user' => $fans['openid'], 'openid' => $wqid, 'nickname' => $fans['nickname'], 'avatar' => $fans['avatar']);
                pdo_update('tiger_newhu_share', $updata, array('weid' => $_W['uniacid'], 'unionid' => $fans['unionid']));
                $share = pdo_fetch('select * from ' . tablename('tiger_newhu_share') . ' where weid=\'' . $_W['uniacid'] . '\' and unionid=\'' . $fans['unionid'] . '\'');
                return $share;
            }
            $share = pdo_fetch('select * from ' . tablename('tiger_newhu_share') . ' where weid=\'' . $_W['uniacid'] . '\' and from_user=\'' . $fans['openid'] . '\'');
            if (!empty($share['id'])) {
                $updata = array('unionid' => $fans['unionid'], 'openid' => $wqid, 'nickname' => $fans['nickname'], 'avatar' => $fans['avatar']);
                pdo_update('tiger_newhu_share', $updata, array('weid' => $_W['uniacid'], 'from_user' => $fans['openid']));
                $share = pdo_fetch('select * from ' . tablename('tiger_newhu_share') . ' where weid=\'' . $_W['uniacid'] . '\' and from_user=\'' . $fans['openid'] . '\'');
                return $share;
            }
            pdo_insert('tiger_newhu_share', array('openid' => $wqid, 'nickname' => $fans['nickname'], 'avatar' => $fans['avatar'], 'unionid' => $fans['unionid'], 'pid' => '', 'updatetime' => time(), 'createtime' => time(), 'parentid' => 0, 'weid' => $_W['uniacid'], 'helpid' => $helpid, 'score' => '', 'cscore' => '', 'pscore' => '', 'from_user' => $fans['openid'], 'follow' => 1));
            $share['id'] = pdo_insertid();
            $share = pdo_fetch('select * from ' . tablename('tiger_newhu_share') . ' where weid=\'' . $_W['uniacid'] . '\' and id=\'' . $share['id'] . '\'');
            return $share;
        }
        $share = pdo_fetch('select * from ' . tablename('tiger_newhu_share') . ' where weid=\'' . $_W['uniacid'] . '\' and from_user=\'' . $fans['openid'] . '\'');
        if (!empty($share['id'])) {
            if (!empty($fans['unionid'])) {
                $updata = array('unionid' => $fans['unionid'], 'openid' => $wqid, 'nickname' => $fans['nickname'], 'avatar' => $fans['avatar']);
                pdo_update('tiger_newhu_share', $updata, array('weid' => $_W['uniacid'], 'from_user' => $fans['openid']));
                $share = pdo_fetch('select * from ' . tablename('tiger_newhu_share') . ' where weid=\'' . $_W['uniacid'] . '\' and from_user=\'' . $fans['openid'] . '\'');
                return $share;
            }
            return $share;
        }
        if (!empty($fans['openid'])) {
            pdo_insert('tiger_newhu_share', array('openid' => $wqid, 'nickname' => $fans['nickname'], 'avatar' => $fans['avatar'], 'unionid' => $fans['unionid'], 'pid' => '', 'updatetime' => time(), 'createtime' => time(), 'parentid' => 0, 'weid' => $_W['uniacid'], 'helpid' => $helpid, 'score' => '', 'cscore' => '', 'pscore' => '', 'from_user' => $fans['openid'], 'follow' => 1));
            $share['id'] = pdo_insertid();
            $share = pdo_fetch('select * from ' . tablename('tiger_newhu_share') . ' where weid=\'' . $_W['uniacid'] . '\' and from_user=\'' . $fans['openid'] . '\'');
            return $share;
        }
        return '';
    }
    public function bryj($share, $begin_time, $end_time, $zt, $bl, $cfg)
    {
        global $_W;
        if (!empty($share['dlbl'])) {
            $bl['dlbl1'] = $share['dlbl'];
        }
        $send_time = strtotime(date('Y-m-d 23:59:59', strtotime(0 - date('d') . 'day')));
        if ($cfg['jsms'] == 1) {
            if ($send_time == $end_time) {
                $addtime = 'jstime';
            } else {
                $addtime = 'addtime';
            }
            if ($zt == 2) {
                $addtime = 'addtime';
            }
        } else {
            $addtime = 'addtime';
        }
        if (empty($end_time)) {
            if (!empty($begin_time)) {
                $dwhere = 'and addtime>=' . $begin_time;
            }
        } elseif (!empty($begin_time)) {
            $dwhere = 'and ' . $addtime . '>=' . $begin_time . ' and ' . $addtime . '<' . $end_time;
        }
        if ($zt == 1) {
            $ddzt = ' and orderzt=\'订单结算\'';
        } elseif ($zt == 2) {
            $ddzt = ' and orderzt=\'订单付款\'';
        } elseif ($zt == 3) {
            $ddzt = ' and orderzt<>\'订单失效\'';
        }
        $tgwarr = explode('|', $share['tgwid']);
        $where = '';
        if (!empty($share['tgwid'])) {
            $where .= 'and (';
            foreach ($tgwarr as $k => $v) {
                $where .= ' tgwid=' . $v . ' or ';
            }
            $where .= 'tgwid=' . $tgwarr[0] . ')';
        } else {
            $where .= ' and tgwid=111111';
        }
        $byygsum = pdo_fetchcolumn('SELECT sum(xgyg) FROM ' . tablename('tiger_newhu_tkorder') . ' where weid=\'' . $_W['uniacid'] . '\'  ' . $ddzt . ' ' . $dwhere . ' ' . $where);
        if (!empty($bl['dlkcbl'])) {
            $byygsum = $byygsum * (100 - $bl['dlkcbl']) / 100;
        }
        if (empty($byygsum)) {
            $byygsum = '0.00';
        } else {
            $sj = pdo_fetch('select * from ' . tablename('tiger_newhu_share') . ' where weid=\'' . $_W['uniacid'] . '\' and id=\'' . $share['helpid'] . '\'');
            if (!empty($sj)) {
                if ($bl['dltype'] == 2) {
                    $dj = 1;
                }
                $sj2 = pdo_fetch('select * from ' . tablename('tiger_newhu_share') . ' where weid=\'' . $_W['uniacid'] . '\' and id=\'' . $sj['helpid'] . '\'');
                if ($bl['dltype'] == 3) {
                    if (!empty($sj2)) {
                        $dj = 2;
                    } else {
                        $dj = 1;
                    }
                }
            }
        }
        if ($bl['fxtype'] == 1) {
            $byygsum = $byygsum * $bl['dlbl1'] / 100;
        } elseif ($dj == 1) {
            $yj2 = $byygsum * $bl['dlbl2'] / 100;
            $yj1 = $yj2 * $bl['dlbl1t2'] / 100;
            $byygsum = $yj2 - $yj1;
        } elseif ($dj == 2) {
            $yj3 = $byygsum * $bl['dlbl3'] / 100;
            $yj2 = $yj3 * $bl['dlbl2t3'] / 100;
            $yj1 = $yj3 * $bl['dlbl1t3'] / 100;
            $byygsum = $yj3 - $yj2 - $yj1;
        } else {
            $byygsum = $byygsum * $bl['dlbl1'] / 100;
        }
        return $byygsum;
    }
    public function jcbl($share, $bl)
    {
        global $_W;
        $sj = pdo_fetch('select * from ' . tablename('tiger_newhu_share') . ' where weid=\'' . $_W['uniacid'] . '\' and id=\'' . $share['helpid'] . '\'');
        if ($bl['dltype'] == 3) {
            if (!empty($sj)) {
                $sj2 = pdo_fetch('select * from ' . tablename('tiger_newhu_share') . ' where weid=\'' . $_W['uniacid'] . '\' and id=\'' . $sj['helpid'] . '\'');
                if (!empty($sj2)) {
                    $djbl = $bl['dlbl3'];
                    $tname = $bl['dlname3'];
                    $cj = 3;
                } else {
                    $djbl = $bl['dlbl2'];
                    $tname = $bl['dlname2'];
                    $cj = 2;
                }
            } else {
                $djbl = $bl['dlbl1'];
                $tname = $bl['dlname1'];
                $cj = 1;
            }
        } elseif ($bl['dltype'] == 2) {
            if (!empty($sj)) {
                $djbl = $bl['dlbl2'];
                $tname = $bl['dlname2'];
                $cj = 2;
            } else {
                $djbl = $bl['dlbl1'];
                $tname = $bl['dlname1'];
                $cj = 1;
            }
        } else {
            $djbl = $bl['dlbl1'];
            $tname = $bl['dlname1'];
            $cj = 1;
        }
        if (!empty($share['dlbl'])) {
            $djbl = $share['dlbl'];
            $tname = $bl['dlname1'];
        }
        $arr = array('bl' => $djbl, 'tname' => $tname, 'cj' => $cj);
        return $arr;
    }
    public function bydlyj($share, $begin_time, $end_time = '', $zt, $bl, $cfg)
    {
        global $_W;
        if (!empty($share['dlbl'])) {
            $bl['dlbl1'] = $share['dlbl'];
        }
        $send_time = strtotime(date('Y-m-d 23:59:59', strtotime(0 - date('d') . 'day')));
        if ($cfg['jsms'] == 1) {
            if ($send_time == $end_time) {
                $addtime = 'jstime';
            } else {
                $addtime = 'addtime';
            }
            if ($zt == 2) {
                $addtime = 'addtime';
            }
        } else {
            $addtime = 'addtime';
        }
        if (empty($end_time)) {
            if (!empty($begin_time)) {
                $where = 'and addtime>=' . $begin_time;
            }
        } elseif (!empty($begin_time)) {
            $where = 'and ' . $addtime . '>=' . $begin_time . ' and ' . $addtime . '<' . $end_time;
        }
        if ($zt == 1) {
            $ddzt = ' and orderzt=\'订单结算\'';
        } elseif ($zt == 2) {
            $ddzt = ' and orderzt=\'订单付款\'';
        } elseif ($zt == 3) {
            $ddzt = ' and orderzt<>\'订单失效\'';
        }
        $bbegin_time = strtotime(date('Y-m-d H:i:s', mktime(0, 0, 0, date('m'), 1, date('Y'))));
        $rjshare = pdo_fetchall('SELECT id,helpid,tgwid FROM ' . tablename('tiger_newhu_share') . ' where weid=\'' . $_W['uniacid'] . '\' and helpid=\'' . $share['id'] . '\' and dltype=1');
        $r = '';
        foreach ($rjshare as $k => $v) {
            $a = pdo_fetchcolumn('SELECT sum(xgyg) FROM ' . tablename('tiger_newhu_tkorder') . '  where weid=\'' . $_W['uniacid'] . '\' and tgwid=\'' . $v['tgwid'] . '\' ' . $ddzt . ' ' . $where);
            $r = $r + $a;
        }
        $rjrs = $r;
        if (!empty($bl['dlkcbl'])) {
            $rjrs = $rjrs * (100 - $bl['dlkcbl']) / 100;
        }
        if (empty($rjrs)) {
            $rjrs = '0.00';
        }
        if ($bl['dltype'] == 3) {
            $fans1 = pdo_fetchall('select id from ' . tablename('tiger_newhu_share') . ' where weid=\'' . $_W['uniacid'] . '\' and dltype=1 and helpid=\'' . $share['id'] . '\'', array(), 'id');
            if (!empty($fans1)) {
                $sjrs = pdo_fetchcolumn('SELECT sum(t.xgyg) FROM ' . tablename('tiger_newhu_share') . ' s left join ' . tablename('tiger_newhu_tkorder') . ' t ON s.tgwid=t.tgwid where s.weid=\'' . $_W['uniacid'] . '\'   and s.dltype=1  ' . $ddzt . ' and s.helpid in (' . implode(',', array_keys($fans1)) . ') ' . $where);
            }
            if (!empty($bl['dlkcbl'])) {
                $sjrs = $sjrs * (100 - $bl['dlkcbl']) / 100;
            }
            if (empty($sjrs)) {
                $sjrs = '0.00';
            }
        } else {
            $sjrs = '0.00';
        }
        if ($bl['dltype'] == 1) {
            $rjrs = '0.00';
            $sjrs = '0.00';
        }
        $array = array('yj2' => $rjrs * $bl['dlbl2'] / 100, 'yj3' => $sjrs * $bl['dlbl3'] / 100);
        return $array;
    }
    public function dljiangli($endprice, $tkrate, $bl, $share)
    {
        global $_W;
        $dlyj = $endprice * $tkrate / 100;
        if (!empty($bl['dlkcbl'])) {
            $dlyj = $dlyj * (100 - $bl['dlkcbl']) / 100;
        }
        $fs = $this->jcbl($share, $bl);
        if (empty($share['dlbl'])) {
            $dlbl = $bl['dlbl1'];
        } else {
            $dlbl = $fs['bl'];
        }
        if ($bl['fxtype'] == 1) {
            $dlrate = number_format($dlyj * $dlbl / 100, 2);
        } else {
            $yj = number_format($dlyj * $dlbl / 100, 2);
            if ($bl['dltype'] == 2) {
                if (empty($share['helpid'])) {
                    $jryj = 0;
                } else {
                    $jryj = $yj * $bl['dlbl1t2'] / 100;
                }
            } elseif ($bl['dltype'] == 3) {
                if (empty($share['helpid'])) {
                    $jryj = 0;
                } else {
                    $sjshare = pdo_fetch('select * from ' . tablename('tiger_newhu_share') . ' where weid=\'' . $share['weid'] . '\'and dltype=1 and id=\'' . $share['helpid'] . '\'');
                    $jryj = $yj * $bl['dlbl2t3'] / 100;
                    if (empty($sjshare['helpid'])) {
                        $jrsjyj = 0;
                    } else {
                        $jrsjyj = $yj * $bl['dlbl1t3'] / 100;
                    }
                }
            }
            $jrzyj = $yj - $jryj - $jrsjyj;
            file_put_contents(IA_ROOT . '/addons/tiger_tkxcx/yj_log.txt', "\n" . 'uid:' . $share['id'] . '------' . $yj . '-' . $jryj . '-' . $jrsjyj . '=' . $jrzyj, FILE_APPEND);
            $dlrate = number_format($jrzyj, 2);
        }
        return $dlrate;
    }
    public function ptyjjl($endprice, $tkrate, $cfg)
    {
        global $_W;
        $yj = $endprice * $tkrate / 100;
        $yongj = $yj * $cfg['zgf'] / 100;
        if (empty($yongj)) {
            $yongj = '0.00';
        }
        if ($cfg['fxtype'] == 1) {
            $yj1 = $yongj * $cfg['jfbl'];
            $yj1 = intval($yj1);
        } elseif ($cfg['fxtype'] == 2) {
            $yj1 = number_format($yongj, 2);
        }
        return $yj1;
    }
    public function sharejl($endprice, $tkrate, $bl, $share, $cfg)
    {
        if ($share['dltype'] == 1) {
            $yj = $this->dljiangli($endprice, $tkrate, $bl, $share);
        } else {
            $yj = $this->ptyjjl($endprice, $tkrate, $cfg);
        }
        return $yj;
    }
    public function tkljx($msg)
    {
        global $_W;
        global $_GPC;
        $cfg = $this->module['config'];
        $appkey = $cfg['tkAppKey'];
        $secret = $cfg['tksecretKey'];
        $c = new TopClient();
        $c->appkey = $appkey;
        $c->secretKey = $secret;
        $req = new WirelessShareTpwdQueryRequest();
        $req->setPasswordContent($msg);
        $resp = $c->execute($req);
        $jsonStr = json_encode($resp);
        $jsonArray = json_decode($jsonStr, true);
        return $jsonArray;
    }
    public function mc_jl($uid, $type, $typelx, $num, $remark, $orderid)
    {
        global $_W;
        if (empty($uid)) {
            return null;
        }
        $data = array('uid' => $uid, 'weid' => $_W['uniacid'], 'type' => $type, 'typelx' => $typelx, 'num' => $num, 'remark' => $remark, 'orderid' => $orderid, 'createtime' => time());
        $share = pdo_fetch('SELECT credit1,credit2 FROM ' . tablename($this->modulename . '_share') . ' WHERE id=\'' . $uid . '\' and weid=\'' . $_W['uniacid'] . '\' ');
        if ($type == 1) {
            $credit2 = $share['credit2'] + $num;
            if ($credit2 < 0) {
                return array('error' => 0, 'data' => '余额不足');
            }
            $res = pdo_update($this->modulename . '_share', array('credit2' => $credit2), array('id' => $uid));
            if ($res === false) {
                return array('error' => 0, 'data' => '余额更新失败');
            }
            $inst = pdo_insert($this->modulename . '_jl', $data);
            if ($inst === false) {
                return array('error' => 0, 'data' => '余额更新失败');
            }
            return array('error' => 1, 'data' => '余额更新成功');
        }
        if ($type == 0) {
            $credit1 = $share['credit1'] + $num;
            if ($credit1 < 0) {
                return array('error' => 0, 'data' => '积分不足');
            }
            $res = pdo_update($this->modulename . '_share', array('credit1' => $credit1), array('id' => $uid));
            if ($res === false) {
                return array('error' => 0, 'data' => '积分更新失败');
            }
            $inst = pdo_insert($this->modulename . '_jl', $data);
            if ($inst === false) {
                return array('error' => 0, 'data' => '积分更新失败');
            }
            return array('error' => 1, 'data' => '积分更新成功');
        }
    }
    public function islogin()
    {
        global $_W;
        if (!empty($_SESSION['openid'])) {
            $fans['openid'] = $_SESSION['openid'];
            $share = pdo_fetch('select * from ' . tablename($this->modulename . '_share') . ' where weid=\'' . $_W['uniacid'] . '\' and id=\'' . $_SESSION['tkuid'] . '\'');
        }
        $mc = mc_fetch($fans['openid']);
        $fans = array('id' => $_SESSION['tkuid'], 'tkuid' => $_SESSION['tkuid'], 'wquid' => $mc['uid'], 'credit1' => $share['credit1'], 'credit2' => $share['credit2'], 'nickname' => $share['nickname'], 'avatar' => $share['avatar'], 'helpid' => $share['helpid'], 'dlptpid' => $share['dlptpid'], 'unionid' => $share['unionid'], 'from_user' => $share['from_user'], 'openid' => $share['from_user'], 'createtime' => $share['createtime'], 'tgwid' => $share['tgwid'], 'cqtype' => $share['cqtype'], 'dltype' => $share['dltype'], 'status' => $share['status']);
        return $fans;
    }
    public function doMobileLogin()
    {
        global $_GPC;
        global $_W;
        $cfg = $this->module['config'];
        $pid = $_GPC['pid'];
        $tzurl = $_GPC['tzurl'];
        $fans = mc_oauth_userinfo();
        if ($_W['isajax']) {
            $username = trim($_GPC['username']);
            $password = trim($_GPC['password']);
            $share = pdo_fetch('SELECT * FROM ' . tablename($this->modulename . '_share') . ' WHERE pcuser=\'' . $username . '\' and weid=\'' . $_W['uniacid'] . '\' ');
            if ($username == $share['pcuser'] && $password == $share['pcpasswords']) {
                $_SESSION['username'] = $share['pcuser'];
                $_SESSION['tkuid'] = $share['id'];
                $_SESSION['openid'] = $share['from_user'];
                $_SESSION['unionid'] = $share['unionid'];
                $_SESSION['pid'] = $share['dlptpid'];
                exit(json_encode(array('status' => 1, 'msg' => '登录成功', 'tzurl' => urldecode($tzurl))));
            } else {
                exit(json_encode(array('status' => 0, 'msg' => '帐号密码错误', 'tzurl' => urldecode($tzurl))));
            }
        }
        include $this->template('login');
    }
    public function doMobileLoginout()
    {
        session_unset();
        session_destroy();
        exit(json_encode(array('status' => 1, 'msg' => '退出登录成功')));
    }
    public function doMobilebdLogin()
    {
        global $_GPC;
        global $_W;
        $cfg = $this->module['config'];
        $fans = mc_oauth_userinfo();
        $openid = $_GPC['openid'];
        $unionid = $_GPC['unionid'];
        $username = trim($_GPC['username']);
        $password = trim($_GPC['password']);
        $usdata = array('pcuser' => $username, 'pcpasswords' => $password);
        if ($_W['isajax']) {
            if (empty($openid)) {
                exit(json_encode(array('status' => 0, 'msg' => '请在微信端绑定')));
            }
            $sharepcuser = pdo_fetch('SELECT * FROM ' . tablename($this->modulename . '_share') . ' WHERE pcuser=\'' . $username . '\' and weid=\'' . $_W['uniacid'] . '\' ');
            if (!empty($sharepcuser['id'])) {
                exit(json_encode(array('status' => 0, 'msg' => '手机号已经存在！')));
            }
            $share = pdo_fetch('SELECT * FROM ' . tablename($this->modulename . '_share') . ' WHERE from_user=\'' . $openid . '\' and weid=\'' . $_W['uniacid'] . '\' ');
            if (empty($share['id'])) {
                $share = pdo_fetch('SELECT * FROM ' . tablename($this->modulename . '_share') . ' WHERE unionid=\'' . $unionid . '\' and weid=\'' . $_W['uniacid'] . '\' ');
                if (!empty($share['id'])) {
                    pdo_update($this->modulename . '_share', $usdata, array('weid' => $_W['uniacid'], 'id' => $share['id']));
                } else {
                    exit(json_encode(array('status' => 0, 'msg' => '用户不存在，请先关注公众号')));
                }
            } else {
                $aaa = pdo_update($this->modulename . '_share', $usdata, array('weid' => $_W['uniacid'], 'id' => $share['id']));
                if ($aaa !== 'false') {
                    exit(json_encode(array('status' => 1, 'msg' => '绑定成功！')));
                }
                exit(json_encode(array('status' => 0, 'msg' => $aaa)));
            }
            if (empty($share['id'])) {
                exit(json_encode(array('status' => 0, 'msg' => '用户不存在，请先关注公众号')));
            }
        }
        include $this->template('bdlogin');
    }
    public function sjrd44($length = 4)
    {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $str = '';
        $i = 0;
        while ($i < $length) {
            $str .= $chars[mt_rand(0, strlen($chars) - 1)];
            $i = $i + 1;
        }
        return $str;
    }
    public function getimg($url, $path = '', $_W)
    {
        if (empty($path)) {
            $path = IA_ROOT . '/addons/tiger_newhu/goodsimg/' . date('Ymd');
        }
        if ($url == '') {
            return false;
        }
        $sctime = date('YmdHis') . $this->sjrd44(6);
        $filename = $path . '/' . $sctime . '.png';
        ob_start();
        readfile($url);
        $img = ob_get_contents();
        ob_end_clean();
        $fp = fopen($filename, 'a');
        fwrite($fp, $img);
        fclose($fp);
        return $_W['siteroot'] . 'addons/tiger_newhu/goodsimg/' . date('Ymd') . '/' . $sctime . '.png';
    }
    public function doMobileTupian()
    {
        global $_GPC;
        global $_W;
        $cfg = $this->module['config'];
        $title = urldecode($_GPC['title']);
        $price = $_GPC['price'];
        $yhj = $_GPC['yhj'];
        $orprice = $_GPC['orprice'];
        $xiaol = $_GPC['xiaol'];
        $jrprice = $_GPC['jrprice'];
        $taoimage = $_GPC['taoimage'];
        $url = urldecode($_GPC['url']);
        include IA_ROOT . '/addons/tiger_newhu/inc/sdk/tbk/tb.php';
        $urlarr = $this->dwzw($url);
        $url = $urlarr;
        $ewm = $this->getimg('http://pan.baidu.com/share/qrcode?w=150&h=150&url=' . $url, '', $_W);
        picjialidun($_W, $title, $price, $yhj, $orprice, $xiaol, $jrprice, $taoimage, $ewm);
    }
    public function getfc($string, $len = 2)
    {
        $string = str_replace(' ', '', $string);
        $start = 0;
        $strlen = mb_strlen($string);
        while ($strlen) {
            $array[] = mb_substr($string, $start, $len, 'utf8');
            $string = mb_substr($string, $len, $strlen, 'utf8');
            $strlen = mb_strlen($string);
        }
        return $array;
    }
    public function curl_request($url, $post = '', $cookie = '', $returnCookie = 0)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.1; \tTrident/6.0)");
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_AUTOREFERER, 1);
        curl_setopt($curl, CURLOPT_REFERER, 'http://XXX');
        if ($post) {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($post));
        }
        if ($cookie) {
            curl_setopt($curl, CURLOPT_COOKIE, $cookie);
        }
        curl_setopt($curl, CURLOPT_HEADER, $returnCookie);
        curl_setopt($curl, CURLOPT_TIMEOUT, 10);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($curl);
        if (curl_errno($curl)) {
            return curl_error($curl);
        }
        curl_close($curl);
        if ($returnCookie) {
            explode("\r\n\r\n", $data, 2);
            preg_match_all("/Set\\-Cookie:([^;]*);/", $header, $matches);
            $info['cookie'] = substr($matches[1][0], 1);
            $info['content'] = $body;
            return $info;
        }
        return $data;
    }
    public function strurl($coupons_url)
    {
        $url = strtolower($coupons_url);
        $activity_id = 'activity_id=';
        $wz = strpos($url, $activity_id);
        if (empty($wz)) {
            $activity_id = 'activityid=';
            $wz = strpos($url, $activity_id);
            return substr($url, $wz + 11, 32);
        }
        return substr($url, $wz + 12, 32);
    }
    public function tkl($url, $img, $tjcontent)
    {
        global $_W;
        global $_GPC;
        $cfg = $this->module['config'];
        $appkey = $cfg['tkAppKey'];
        $secret = $cfg['tksecretKey'];
        $c = new TopClient();
        $c->appkey = $appkey;
        $c->secretKey = $secret;
        $req = new TbkTpwdCreateRequest();
        $req->setText($tjcontent);
        $req->setUrl($url);
        $req->setLogo($img);
        $req->setExt('{}');
        $resp = $c->execute($req);
        $jsonStr = json_encode($resp);
        $jsonArray = json_decode($jsonStr, true);
        $taokou = $jsonArray['data']['model'];
        if ($cfg['tklnewtype'] == 1) {
            $taokou = str_replace('《', '￥', $taokou);
        }
        file_put_contents(IA_ROOT . '/addons/tiger_newhu/tkl_log.txt', "\n" . json_encode($jsonArray), FILE_APPEND);
        return $taokou;
    }
    public function doMobileSq88888888()
    {
        global $_W;
        global $_GPC;
        if ($_GPC['my'] != 'tigernewhu') {
            echo 'cs';
            exit(0);
        }
        $cfg = $this->module['config'];
        $host = $_SERVER['HTTP_HOST'];
        $host = strtolower($host);
        $tbuid = $cfg['tbuid'];
        $tkurl1 = $host;
        $tkurl2 = $_W['setting']['site']['url'];
        $tkip = $this->get_server_ip();
        echo '使用域名:' . $host . '<br>';
        echo '淘ID:' . $tbuid . '<br>';
        echo '域名:' . $tkurl2 . '<br>';
        echo 'tkip:' . $tkip . '<br>';
        $s = pdo_fetchall('select settings from ' . tablename('uni_account_modules') . ' where module=\'tiger_newhu\'');
        foreach ($s as $k => $v) {
            $b = unserialize($v['settings']);
            echo ',' . $b['tbuid'];
        }
    }
    public function oldtkl($url, $img, $tjcontent)
    {
        global $_W;
        global $_GPC;
        $cfg = $this->module['config'];
        $appkey = $cfg['tkAppKey'];
        $secret = $cfg['tksecretKey'];
        $c = new TopClient();
        $c->appkey = $appkey;
        $c->secretKey = $secret;
        $req = new WirelessShareTpwdCreateRequest();
        $tpwd_param = new GenPwdIsvParamDto();
        $tpwd_param->ext = '{"":""}';
        $tpwd_param->logo = $img;
        $tpwd_param->text = $tjcontent;
        $tpwd_param->url = $url;
        $req->setTpwdParam(json_encode($tpwd_param));
        $resp = $c->execute($req);
        $taokou = $resp->model;
        settype($taokou, 'string');
        if ($cfg['tklnewtype'] == 1) {
            $taokou = str_replace('《', '￥', $taokou);
        }
        file_put_contents(IA_ROOT . '/addons/tiger_newhu/oldtkl_log.txt', "\n" . json_encode($resp), FILE_APPEND);
        return $taokou;
    }
    public function hlinorder($userInfo, $_W)
    {
        global $_W;
        global $_GPC;
        $cfg = $this->module['config'];
        foreach ($userInfo as $v) {
            $fztype = pdo_fetch('select * from ' . tablename($this->modulename . '_fztype') . ' where weid=\'' . $_W['uniacid'] . '\' and hlcid=\'' . $v['fqcat'] . '\' order by px desc');
            $Quan_id = $this->strurl($v['couponurl']);
            $item = array('weid' => $_W['uniacid'], 'fqcat' => $fztype['id'], 'zy' => 2, 'quan_id' => $Quan_id, 'itemid' => $v['itemid'], 'itemtitle' => $v['itemtitle'], 'itemshorttitle' => $v['itemshorttitle'], 'itemdesc' => $v['itemdesc'], 'itemprice' => $v['itemprice'], 'itemsale' => $v['itemsale'], 'itemsale2' => $v['itemsale2'], 'conversion_ratio' => $v['conversion_ratio'], 'itempic' => $v['itempic'], 'itemendprice' => $v['itemendprice'], 'shoptype' => $v['shoptype'], 'userid' => $v['userid'], 'sellernick' => $v['sellernick'], 'tktype' => $v['tktype'], 'tkrates' => $v['tkrates'], 'ctrates' => $v['ctrates'], 'cuntao' => $v['cuntao'], 'tkmoney' => $v['tkmoney'], 'tkurl' => $v['tkurl'], 'couponurl' => $v['couponurl'], 'planlink' => $v['planlink'], 'couponmoney' => $v['couponmoney'], 'couponsurplus' => $v['couponsurplus'], 'couponreceive' => $v['couponreceive'], 'couponreceive2' => $v['couponreceive2'], 'couponnum' => $v['couponnum'], 'couponexplain' => $v['couponexplain'], 'couponstarttime' => $v['couponstarttime'], 'couponendtime' => $v['couponendtime'], 'starttime' => $v['starttime'], 'isquality' => $v['isquality'], 'item_status' => $v['item_status'], 'report_status' => $v['report_status'], 'is_brand' => $v['is_brand'], 'is_live' => $v['is_live'], 'videoid' => $v['videoid'], 'activity_type' => $v['activity_type'], 'createtime' => TIMESTAMP);
            $go = pdo_fetch('SELECT id FROM ' . tablename($this->modulename . '_newtbgoods') . ' WHERE weid=\'' . $_W['uniacid'] . '\' and itemid=\'' . $v['itemid'] . '\' ORDER BY id desc');
            if (empty($go)) {
                pdo_insert($this->modulename . '_newtbgoods', $item);
            } else {
                pdo_update($this->modulename . '_newtbgoods', $item, array('weid' => $_W['uniacid'], 'itemid' => $v['itemid']));
            }
        }
    }
    public function indtkgoods($dtklist)
    {
        global $_W;
        global $_GPC;
        $page = $_GPC['page'];
        $cfg = $this->module['config'];
        foreach ($dtklist as $v) {
            $fztype = pdo_fetch('select * from ' . tablename($this->modulename . '_fztype') . ' where weid=\'' . $_W['uniacid'] . '\' and dtkcid=\'' . $v['Cid'] . '\' order by px desc');
            if ($v['Commission_queqiao'] != '0.00') {
                $lxtype = '鹊桥活动';
                $yjbl = $v['Commission_queqiao'];
            } elseif ($v['Commission_jihua'] != '0.00') {
                $lxtype = '营销计划';
                $yjbl = $v['Commission_jihua'];
            } else {
                $lxtype = '通用计划';
                $yjbl = $v['Commission_jihua'];
            }
            if ($v['IsTmall'] == 1) {
                $shoptype = 'B';
            } else {
                $shoptype = 'C';
            }
            $item = array('weid' => $_W['uniacid'], 'fqcat' => $fztype['id'], 'zy' => 1, 'tktype' => $lxtype, 'itemid' => $v['GoodsID'], 'itemtitle' => $v['Title'], 'itemdesc' => $v['Introduce'], 'itempic' => $v['Pic'], 'itemendprice' => $v['Price'], 'itemsale' => $v['Sales_num'], 'tkrates' => $yjbl, 'couponreceive' => $v['Quan_receive'], 'couponsurplus' => $v['Quan_surplus'], 'couponmoney' => $v['Quan_price'], 'couponendtime' => strtotime($v['Quan_time']), 'couponurl' => $v['Quan_link'], 'shoptype' => $shoptype, 'quan_id' => $v['Quan_id'], 'couponexplain' => $v['Quan_condition'], 'itemprice' => $v['Org_Price'], 'tkurl' => $v['Jihua_link'], 'createtime' => TIMESTAMP);
            $go = pdo_fetch('SELECT itemid FROM ' . tablename($this->modulename . '_newtbgoods') . ' WHERE weid = \'' . $_W['uniacid'] . '\' and  itemid=' . $v['GoodsID'] . ' ');
            if (empty($go)) {
                pdo_insert($this->modulename . '_newtbgoods', $item);
            } else {
                pdo_update($this->modulename . '_newtbgoods', $item, array('weid' => $_W['uniacid'], 'itemid' => $v['GoodsID']));
            }
        }
    }
    public function apUpload($media_id)
    {
        global $_W;
        global $_GPC;
        load()->classs('weixin.account');
        $accObj = WeixinAccount::create($_W['uniacid']);
        $access_token = $accObj->fetch_token();
        $url = 'http://file.api.weixin.qq.com/cgi-bin/media/get?access_token=' . $access_token . '&media_id=' . $media_id;
        file_put_contents(IA_ROOT . '/addons/tiger_newhu/log.txt', "\n old:" . json_encode($access_token), FILE_APPEND);
        file_put_contents(IA_ROOT . '/addons/tiger_newhu/log.txt', "\n old:" . json_encode($media_id), FILE_APPEND);
        $newfolder = ATTACHMENT_ROOT . 'images' . '/tiger_newhu_photos' . '/';
        if (!is_dir($newfolder)) {
            mkdir($newfolder, 7777);
        }
        $picurl = 'images' . '/tiger_newhu_photos' . '/' . date('YmdHis') . rand(1000, 9999) . '.jpg';
        $targetName = ATTACHMENT_ROOT . $picurl;
        $ch = curl_init($url);
        $fp = fopen($targetName, 'wb');
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_exec($ch);
        curl_close($ch);
        fclose($fp);
        return $picurl;
    }
    public function dwz($url)
    {
        global $_W;
        $cfg = $this->module['config'];
        $url = urlencode($url);
        $turl = $_W['siteroot'] . str_replace('./', 'app/', $this->createMobileurl('openlink', array('link' => $url)));
        if ($cfg['dwzlj'] == 0) {
            $url = $this->sinadwz($turl);
        } elseif ($cfg['dwzlj'] == 1) {
            $url = $this->wxdwz($turl);
        } else {
            $urlarr = $this->zydwz($turl);
        }
    }
    public function dwzw($turl)
    {
        global $_W;
        $cfg = $this->module['config'];
        if ($cfg['dwzlj'] == 0) {
            $url = $this->sinadwz($turl);
        } elseif ($cfg['dwzlj'] == 1) {
            $url = $this->wxdwz($turl);
        } else {
            $url = $this->zydwz($turl);
        }
        return $url;
    }
    public function zydwz($turl)
    {
        global $_W;
        $cfg = $this->module['config'];
        $data = array('weid' => $_W['uniacid'], 'url' => $turl, 'createtime' => TIMESTAMP);
        pdo_insert('tiger_newhu_dwz', $data);
        $id = pdo_insertid();
        $url = $cfg['zydwz'] . 't.php?d=' . $id;
        return $url;
    }
    public function wxdwz($url)
    {
        $result = '{"action":"long2short","long_url":"' . $url . '"}';
        $access_token = $this->getAccessToken();
        $url = 'https://api.weixin.qq.com/cgi-bin/shorturl?access_token=' . $access_token;
        $ret = ihttp_request($url, $result);
        error_reporting(0);
        $content = json_decode($ret['content'], true);
        return $content['short_url'];
    }
    public function sinadwz($url)
    {
        global $_W;
        $cfg = $this->module['config'];
        if (empty($cfg['sinkey'])) {
            $key = 1549359964;
        } else {
            $key = trim($cfg['sinkey']);
        }
        $turl2 = urlencode($url);
        $sinaurl = 'http://api.t.sina.com.cn/short_url/shorten.json?source=' . $key . '&url_long=' . $turl2;
        load()->func('communication');
        $json = ihttp_get($sinaurl);
        file_put_contents(IA_ROOT . '/addons/tiger_newhu/log--sina.txt', "\n--3" . $url, FILE_APPEND);
        file_put_contents(IA_ROOT . '/addons/tiger_newhu/log--sina.txt', "\n--3" . json_encode($json), FILE_APPEND);
        error_reporting(0);
        $result = json_decode($json['content'], true);
        return $result[0]['url_short'];
    }
    public function addtbgoods($data)
    {
        $cfg = $this->module['config'];
        if ($cfg['cxrk'] == 1) {
            if (empty($data['num_iid'])) {
                return '';
            }
            $go = pdo_fetch('SELECT id FROM ' . tablename($this->modulename . '_tbgoods') . ' WHERE weid = \'' . $data['weid'] . '\' and  num_iid=\'' . $data['num_iid'] . '\'');
            if (empty($go)) {
                pdo_insert($this->modulename . '_tbgoods', $data);
            } else {
                pdo_update($this->modulename . '_tbgoods', $data, array('weid' => $data['weid'], 'num_iid' => $data['num_iid']));
            }
        }
    }
    public function mygetID($url)
    {
        if (preg_match('/[\\?&]id=(\\d+)/', $url, $match)) {
            return $match[1];
        }
        return '';
    }
    public function getyouhui2($str)
    {
        preg_match_all('|(￥[^￥]+￥)|ism', $str, $matches);
        return $matches[1][0];
    }
    public function geturl($str)
    {
        $exp = explode('http', $str);
        $url = 'http' . trim($exp[1]) . ' ';
        preg_match('/[\\s]/u', $url, $matches, PREG_OFFSET_CAPTURE);
        $url = substr($url, 0, $matches[0][1]);
        if ($url == 'http') {
            return '';
        }
        return $url;
    }
    public function myisexists($url)
    {
        if (stripos($url, 'taobao.com') !== false) {
            return 2;
        }
        if (stripos($url, 'tmall.com') !== false) {
            return 2;
        }
        if (stripos($url, 'tmall.hk') !== false) {
            return 2;
        }
        return 1;
    }
    public function hqgoodsid($url)
    {
        $str = file_get_contents($url);
        $str = str_replace('"', '', $str);
        file_put_contents(IA_ROOT . '/addons/tiger_newhu/log.txt', "\n" . $str, FILE_APPEND);
        $goodsid = $this->Text_qzj($str, '?id=', '&');
        if (empty($goodsid)) {
            $goodsid = $this->Text_qzj($str, '&id=', '&');
        }
        if (empty($goodsid)) {
            $goodsid = $this->Text_qzj($str, 'itemId:', ',');
        }
        if (empty($goodsid)) {
            $url = $this->Text_qzj($str, 'url = \'', '\';');
            $goodsid = $this->Text_qzj($str, 'com/i', '.htm');
            file_put_contents(IA_ROOT . '/addons/tiger_newhu/log.txt', "\n" . json_encode($goodsid), FILE_APPEND);
        }
        return $goodsid;
    }
    public function Text_qzj($Text, $Front, $behind)
    {
        $t1 = mb_strpos('.' . $Text, $Front);
        if ($t1 == false) {
            return '';
        }
        $t1 = $t1 - 1 + strlen($Front);
        $temp = mb_substr($Text, $t1, strlen($Text) - $t1);
        $t2 = mb_strpos($temp, $behind);
        if ($t2 == false) {
            return '';
        }
        return mb_substr($temp, 0, $t2);
    }
    public function gstr($str)
    {
        $encode = mb_detect_encoding($str, array(0 => 'ASCII', 1 => 'UTF-8', 2 => 'GB2312', 3 => 'GBK'));
        if (!$encode == 'UTF-8') {
            $str = iconv('UTF-8', $encode, $str);
        }
        return $str;
    }
    public function ewm($url)
    {
        include 'phpqrcode.php';
        $value = $url;
        $errorCorrectionLevel = 'L';
        $matrixPointSize = '4';
        QRcode::png($value, false, $errorCorrectionLevel, $matrixPointSize);
        exit(0);
    }
    public function sendNews($openid, $text)
    {
        global $_W;
        global $_GPC;
        $url = $_W['siteroot'] . str_replace('./', 'app/', $this->createMobileurl('index'));
        $custom = array('touser' => $openid, 'msgtype' => 'news', 'news' => array('articles' => array(0 => array('title' => urlencode('晒单奖励提醒'), 'description' => urlencode($text), 'url' => $url, 'picurl' => ''))));
        $result = urldecode(json_encode($custom));
        $access_token = $this->getAccessToken();
        $url = 'https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=' . $access_token;
        $ret = ihttp_request($url, $result);
        return $ret;
    }
    public function postText($openid, $text)
    {
        $post = '{"touser":"' . $openid . '","msgtype":"text","text":{"content":"' . $text . '"}}';
        $ret = $this->postRes($this->getAccessToken(), $post);
        return $ret;
    }
    private function postRes($access_token, $data)
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=' . $access_token;
        load()->func('communication');
        $ret = ihttp_request($url, $data);
        error_reporting(0);
        $content = json_decode($ret['content'], true);
        return $content['errcode'];
    }
    private function getAccessToken()
    {
        global $_W;
        load()->model('account');
        $acid = $_W['acid'];
        if (empty($acid)) {
            $acid = $_W['uniacid'];
        }
        $account = WeAccount::create($acid);
        $token = $account->getAccessToken();
        return $token;
    }
    public function createRule($kword, $pid)
    {
        global $_W;
        $rule = array('uniacid' => $_W['uniacid'], 'name' => $this->modulename, 'module' => $this->modulename, 'status' => 1, 'displayorder' => 254);
        pdo_insert('rule', $rule);
        unset($rule['name']);
        $rule['type'] = 1;
        $rule['rid'] = pdo_insertid();
        $rule['content'] = $kword;
        pdo_insert('rule_keyword', $rule);
        file_put_contents(IA_ROOT . '/addons/tiger_newhu/log.txt', "\n old:" . json_encode($pid . '----' . $rule['rid']), FILE_APPEND);
        pdo_update($this->modulename . '_poster', array('rid' => $rule['rid']), array('id' => $pid));
    }
    public function get_device_type()
    {
        $agent = strtolower($_SERVER['HTTP_USER_AGENT']);
        $type = 'android';
        if (strpos($agent, 'iphone') || strpos($agent, 'ipad')) {
            $type = 'ios';
        }
        if (strpos($agent, 'android')) {
            $type = 'android';
        }
        return $type;
    }
    public function gettaogoods($numid, $api)
    {
        $c = new TopClient();
        $c->appkey = $api['appkey'];
        $c->secretKey = $api['secretKey'];
        $req = new TbkItemInfoGetRequest();
        $req->setFields('num_iid,title,pict_url,small_images,reserve_price,zk_final_price,user_type,provcity,item_url,seller_id,volume,nick');
        $req->setPlatform(1);
        $req->setNumIids($numid);
        $resp = $c->execute($req);
        $resp = json_decode(json_encode($resp), true);
        $arr = $resp['results']['n_tbk_item'];
        return $arr;
    }
    public function goodlist($key, $pid, $page)
    {
        require_once IA_ROOT . '/addons/tiger_newhu/inc/sdk/getpic.php';
        $api = taobaopp($tiger);
        $c = new TopClient();
        $c->appkey = $api['appkey'];
        $c->secretKey = $api['secretKey'];
        $req = new TbkItemCouponGetRequest();
        $req->setPlatform(2);
        $req->setPageSize(20);
        $req->setQ($key);
        $req->setPageNo($page);
        $req->setPid($pid);
        $resp = $c->execute($req);
        $resp = json_decode(json_encode($resp), true);
        $goods = $resp['results']['tbk_coupon'];
        foreach ($goods as $k => $v) {
            $list[$k]['title'] = $v['title'];
            $list[$k]['istmall'] = $v['user_type'];
            $list[$k]['num_iid'] = $v['num_iid'];
            $list[$k]['url'] = $v['coupon_click_url'];
            $list[$k]['coupons_end'] = $v['coupon_end_time'];
            preg_match_all('|满([\\d\\.]+).*元减([\\d\\.]+).*元|ism', $v['coupon_info'], $matches);
            $list[$k]['coupons_price'] = $matches[2][0];
            $list[$k]['goods_sale'] = $v['volume'];
            $list[$k]['price'] = $v['zk_final_price'] - $matches[2][0];
            $list[$k]['org_price'] = $v['zk_final_price'];
            $list[$k]['pic_url'] = $v['pict_url'];
            $list[$k]['shop_title'] = $v['shop_title'];
            $list[$k]['tk_rate'] = $v['commission_rate'];
            $list[$k]['nick'] = $v['nick'];
            $list[$k]['coupons_take'] = $v['coupon_remain_count'];
            $list[$k]['coupons_total'] = $v['coupon_total_count'];
            $list[$k]['item_url'] = $v['item_url'];
            $list[$k]['small_images'] = $v['small_images']['string'];
            $list[$k]['pic_url'] = $v['pict_url'];
        }
        return $list;
    }
    public function rhy($quan_id, $num_iid, $pid)
    {
        $url = 'https://uland.taobao.com/coupon/edetail?activityId=' . $quan_id . '&itemId=' . $num_iid . '&src=tiger_tiger&pid=' . $pid . '';
        return $url;
    }
    public function rhydx($quan_id, $num_iid, $pid)
    {
        $url = 'https://uland.taobao.com/coupon/edetail?activityId=' . $quan_id . '&itemId=' . $num_iid . '&src=tiger_tiger&pid=' . $pid . '&dx=1';
        return $url;
    }
    private function sendtext($txt, $openid)
    {
        global $_W;
        $acid = $_W['account']['acid'];
        if (!$acid) {
            $acid = pdo_fetchcolumn('SELECT acid FROM ' . tablename('account') . ' WHERE uniacid=:uniacid ', array(':uniacid' => $_W['uniacid']));
        }
        $acc = WeAccount::create($acid);
        $data = $acc->sendCustomNotice(array('touser' => $openid, 'msgtype' => 'text', 'text' => array('content' => urlencode($txt))));
        return $data;
    }
    public function GetIpLookup($ip = '')
    {
        if (empty($ip)) {
            $ip = GetIp();
        }
        error_reporting(0);
        $res = file_get_contents('http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=js&ip=' . $ip);
        if (empty($res)) {
            return false;
        }
        $jsonMatches = array();
        preg_match('#\\{.+?\\}#', $res, $jsonMatches);
        if (!isset($jsonMatches[0])) {
            return false;
        }
        $json = json_decode($jsonMatches[0], true);
        if (isset($json['ret']) && $json['ret'] == 1) {
            $json['ip'] = $ip;
            unset($json['ret']);
            return $json;
        }
        return false;
    }
    public function getIp()
    {
        $onlineip = '';
        if (getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
            $onlineip = getenv('HTTP_CLIENT_IP');
        } elseif (getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
            $onlineip = getenv('HTTP_X_FORWARDED_FOR');
        } elseif (getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
            $onlineip = getenv('REMOTE_ADDR');
        } else {
            if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR']) {
                $onlineip = $_SERVER['REMOTE_ADDR'];
            }
        }
        return $onlineip;
    }
    public function postjiangli($scene_id, $from_user)
    {
        global $_W;
        global $_GPC;
        load()->model('mc');
        $fans = mc_fetch($from_user);
        $poster = pdo_fetch('SELECT * FROM ' . tablename('tiger_newhu_poster') . ' WHERE weid = :weid', array(':weid' => $_W['uniacid']));
        if (empty($fans['nickname']) || empty($fans['avatar'])) {
            $openid = $this->message['from'];
            $ACCESS_TOKEN = $this->getAccessToken();
            $url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token=' . $ACCESS_TOKEN . '&openid=' . $openid . '&lang=zh_CN';
            load()->func('communication');
            $json = ihttp_get($url);
            error_reporting(0);
            $userInfo = json_decode($json['content'], true);
            $fans['nickname'] = $userInfo['nickname'];
            $fans['avatar'] = $userInfo['headimgurl'];
            $fans['province'] = $userInfo['province'];
            $fans['city'] = $userInfo['city'];
            $fans['unionid'] = $userInfo['unionid'];
            mc_update($this->message['from'], array('nickname' => $mc['nickname'], 'avatar' => $mc['avatar']));
        }
        $hmember = pdo_fetch('SELECT * FROM ' . tablename('tiger_newhu_share') . ' WHERE weid = :weid and sceneid=:sceneid', array(':weid' => $_W['uniacid'], ':sceneid' => $scene_id));
        $member = pdo_fetch('SELECT * FROM ' . tablename('tiger_newhu_share') . ' WHERE weid = :weid and from_user=:from_user', array(':weid' => $_W['uniacid'], ':from_user' => $from_user));
        if (empty($member)) {
            pdo_insert($this->modulename . '_share', array('openid' => $fans['uid'], 'nickname' => $fans['nickname'], 'avatar' => $fans['avatar'], 'pid' => $poster['id'], 'createtime' => time(), 'helpid' => $hmember['openid'], 'weid' => $_W['uniacid'], 'score' => $poster['score'], 'cscore' => $poster['cscore'], 'pscore' => $poster['pscore'], 'from_user' => $this->message['from'], 'follow' => 1));
            $share['id'] = pdo_insertid();
            $share = pdo_fetch('select * from ' . tablename($this->modulename . '_share') . ' where id=\'' . $share['id'] . '\'');
            if ($poster['kdtype'] == 1) {
                if (!empty($hmember['from_user'])) {
                    $mcsj = mc_fetch($hmember['from_user']);
                    $msgsj = '您已通过「' . $mcsj['nickname'] . "」，成功关注，点击下方\n\n「菜单-领取奖励」\n\n为好友加分";
                } else {
                    $msgsj = '您需要点击「领取奖励」才能得到积分哦!';
                }
                $this->sendtext($msgsj, $from_user);
                exit(0);
            }
            if ($poster['score'] > 0 || $poster['scorehb'] > 0) {
                $info1 = str_replace('#昵称#', $fans['nickname'], $poster['ftips']);
                $info1 = str_replace('#积分#', $poster['score'], $info1);
                $info1 = str_replace('#元#', $poster['scorehb'], $info1);
                if ($poster['score']) {
                    mc_credit_update($share['openid'], 'credit1', $poster['score'], array(0 => $share['openid'], 1 => '关注送积分'));
                }
                if ($poster['scorehb']) {
                    mc_credit_update($share['openid'], 'credit2', $poster['scorehb'], array(0 => $share['openid'], 1 => '关注送余额'));
                }
                $this->sendtext($info1, $from_user);
            }
            if ($poster['cscore'] > 0 || $poster['cscorehb'] > 0) {
                if ($hmember['status'] == 1) {
                    exit(0);
                }
                $info2 = str_replace('#昵称#', $fans['nickname'], $poster['utips']);
                $info2 = str_replace('#积分#', $poster['cscore'], $info2);
                $info2 = str_replace('#元#', $poster['cscorehb'], $info2);
                if ($poster['cscore']) {
                    mc_credit_update($hmember['openid'], 'credit1', $poster['cscore'], array(0 => $hmember['openid'], 1 => '2级推广奖励'));
                }
                if ($poster['cscorehb']) {
                    mc_credit_update($hmember['openid'], 'credit2', $poster['cscorehb'], array(0 => $hmember['openid'], 1 => '2级推广奖励'));
                }
                $this->sendtext($info2, $hmember['from_user']);
            }
            if ($poster['pscore'] > 0 || $poster['pscorehb'] > 0) {
                $fmember = pdo_fetch('SELECT * FROM ' . tablename('tiger_newhu_share') . ' WHERE weid = :weid and openid=:openid', array(':weid' => $_W['uniacid'], ':openid' => $hmember['helpid']));
                if ($fmember['status'] == 1) {
                    exit(0);
                }
                if ($fmember) {
                    $info3 = str_replace('#昵称#', $fans['nickname'], $poster['utips2']);
                    $info3 = str_replace('#积分#', $poster['pscore'], $info3);
                    $info3 = str_replace('#元#', $poster['pscorehb'], $info3);
                    if ($poster['pscore']) {
                        mc_credit_update($fmember['openid'], 'credit1', $poster['pscore'], array(0 => $hmember['openid'], 1 => '3级推广奖励'));
                    }
                    if ($poster['pscorehb']) {
                        mc_credit_update($fmember['openid'], 'credit2', $poster['pscorehb'], array(0 => $hmember['openid'], 1 => '3级推广奖励'));
                    }
                    $this->sendtext($info3, $fmember['from_user']);
                }
            }
        } else {
            $this->sendtext('亲，您已经是粉丝了，快去生成海报赚取奖励吧', $from_user);
        }
    }
    public function sendMsg($openid, $tplmsgid, $data = array(), $data1, $url = '')
    {
        global $_W;
        $cfg = $this->module['config'];
        if (!empty($data)) {
            $account = WeAccount::create($_W['account']['acid']);
            if (empty($tplmsgid)) {
                $this->postText($this->message['from'], $data1);
            } else {
                if ($_W['account']['level'] == 4) {
                    return $account->sendTplNotice($openid, $tplmsgid, $data, $url);
                }
            }
        }
    }
    public function mbmsg($openid, $mb, $mbid, $url = '', $fans, $orderid, $cfg = '', $valuedata = '')
    {
        global $_W;
        $tp_value1 = unserialize($mb['zjvalue']);
        $tp_value1 = str_replace('#时间#', date('Y-m-d H:i:s', time()), $tp_value1);
        $tp_value1 = str_replace('#昵称#', $fans['nickname'], $tp_value1);
        $tp_value1 = str_replace('#订单号#', $orderid, $tp_value1);
        if (!empty($valuedata)) {
            $tp_value1 = str_replace('#提现金额#', $valuedata['rmb'], $tp_value1);
            $tp_value1 = str_replace('#提现账号#', $valuedata['txzhanghao'], $tp_value1);
            $tp_value1 = str_replace('#微信号#', $valuedata['weixin'], $tp_value1);
            $tp_value1 = str_replace('#手机号#', $valuedata['tel'], $tp_value1);
        }
        $tp_color1 = unserialize($mb['zjcolor']);
        $mb['first'] = str_replace('#时间#', date('Y-m-d H:i:s', time()), $mb['first']);
        $mb['first'] = str_replace('#昵称#', $fans['nickname'], $mb['first']);
        $mb['first'] = str_replace('#订单号#', $orderid, $mb['first']);
        $tplist1 = array('first' => array('value' => $mb['first'], 'color' => $mb['firstcolor']));
        foreach ($tp_value1 as $key => $value) {
            if (!empty($value)) {
                $tplist1['keyword' . $key] = array('value' => $value, 'color' => $tp_color1[$key]);
            }
        }
        $mb['remark'] = str_replace('#时间#', date('Y-m-d H:i:s', time()), $mb['remark']);
        $mb['remark'] = str_replace('#昵称#', $fans['nickname'], $mb['remark']);
        $mb['remark'] = str_replace('#订单号#', $orderid, $mb['remark']);
        $tplist1['remark'] = array('value' => $mb['remark'], 'color' => $mb['remarkcolor']);
        $msg = $this->sendMsg($openid, $mbid, $tplist1, '', $url);
        return $msg;
    }
    public function doMobileReg()
    {
        global $_W;
        global $_GPC;
        $cfg = $this->module['config'];
        $helpid = $_GPC['hid'];
        $fans = mc_oauth_userinfo();
        if (empty($fans['openid'])) {
            echo '只能在微信浏览器中打开！';
        }
        $fans = mc_fetch($_W['fans']['from_user']);
        $share = pdo_fetch('SELECT * FROM ' . tablename('tiger_newhu_share') . ' WHERE weid = :weid and openid=:openid', array(':weid' => $_W['uniacid'], ':openid' => $fans['uid']));
        if (!empty($share['tel'])) {
            $url = $this->createMobileurl('goods');
            header('location:' . $url);
            exit(0);
        }
        if (checksubmit('submit')) {
            $config = $this->module['config'];
            $openid = $_W['openid'];
            $mobile = trim($_GPC['mobile']);
            $verify = trim($_GPC['smsCode']);
            load()->model('utility');
            if (!code_verify($_W['uniacid'], $mobile, $verify)) {
                message('验证码错误', referer(), 'error');
            }
            $user = pdo_fetch('SELECT * FROM ' . tablename($this->modulename . '_share') . ' WHERE tel=:tel AND id<>:id', array(':tel' => $mobile, ':id' => $share['id']));
            if (!empty($user)) {
                message('该手机号已注册其他微信，请先解绑后重试', referer(), 'error');
            }
            $result = pdo_update($this->modulename . '_share', array('tel' => $mobile), array('id' => $share['id'], 'weid' => $_W['uniacid']));
            if ($result) {
                message('验证成功', $this->createMobileurl('goods'), 'success');
            } else {
                message('异常错误', referer(), 'error');
            }
        }
        include $this->template('reg');
    }
    public function post_txhb($cfg, $openid, $dtotal_amount, $desc, $dmch_billno)
    {
        global $_W;
        load()->model('mc');
        if (!empty($desc)) {
            $fans = mc_fetch($_W['openid']);
            $dtotal = $dtotal_amount / 100;
            if ($dtotal > $fans['credit2']) {
                $ret['code'] = -1;
                $ret['dissuccess'] = 0;
                $ret['message'] = '余额不足';
                return $ret;
            }
        }
        if (empty($dmch_billno)) {
            $dmch_billno = random(10) . date('Ymd') . random(3);
        }
        $root = IA_ROOT . '/attachment/tiger_newhu/cert/' . $_W['uniacid'] . '/';
        $ret = array();
        $ret['code'] = 0;
        $ret['message'] = 'success';
        $url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/sendredpack';
        $pars = array();
        $pars['nonce_str'] = random(32);
        $pars['mch_billno'] = $dmch_billno;
        $pars['mch_id'] = $cfg['mchid'];
        $pars['wxappid'] = $cfg['appid'];
        $pars['nick_name'] = $_W['account']['name'];
        $pars['send_name'] = $_W['account']['name'];
        $pars['re_openid'] = $openid;
        $pars['total_amount'] = $dtotal_amount;
        $pars['min_value'] = $dtotal_amount;
        $pars['max_value'] = $dtotal_amount;
        $pars['total_num'] = 1;
        $pars['wishing'] = '提现红包成功!';
        $pars['client_ip'] = $cfg['client_ip'];
        $pars['act_name'] = '兑换红包';
        $pars['remark'] = '来自' . $_W['account']['name'] . '的红包';
        ksort($pars, SORT_STRING);
        $string1 = '';
        foreach ($pars as $k => $v) {
            $string1 .= $k . '=' . $v . '&';
        }
        $string1 .= 'key=' . $cfg['apikey'];
        $pars['sign'] = strtoupper(md5($string1));
        $xml = array2xml($pars);
        $extras = array();
        $extras['CURLOPT_CAINFO'] = $root . 'rootca.pem';
        $extras['CURLOPT_SSLCERT'] = $root . 'apiclient_cert.pem';
        $extras['CURLOPT_SSLKEY'] = $root . 'apiclient_key.pem';
        load()->func('communication');
        $procResult = NULL;
        $resp = ihttp_request($url, $xml, $extras);
        if (is_error($resp)) {
            $procResult = $resp['message'];
            $ret['code'] = -1;
            $ret['dissuccess'] = 0;
            $ret['message'] = $procResult;
            return $ret;
        }
        $xml = '<?xml version="1.0" encoding="utf-8"?>' . $resp['content'];
        $dom = new DOMDocument();
        if ($dom->loadXML($xml)) {
            $xpath = new DOMXPath($dom);
            $code = $xpath->evaluate('string(//xml/return_code)');
            $result = $xpath->evaluate('string(//xml/result_code)');
            if (strtolower($code) == 'success' && strtolower($result) == 'success') {
                $ret['code'] = 0;
                $ret['dissuccess'] = 1;
                $ret['message'] = 'success';
                return $ret;
            }
            $error = $xpath->evaluate('string(//xml/err_code_des)');
            $ret['code'] = -2;
            $ret['dissuccess'] = 0;
            $ret['message'] = $error;
            return $ret;
        }
        $ret['code'] = -3;
        $ret['dissuccess'] = 0;
        $ret['message'] = '3error3';
        return $ret;
    }
    public function post_qyfk($cfg, $openid, $amount, $desc, $dmch_billno)
    {
        global $_W;
        load()->model('mc');
        if (!empty($desc)) {
            $fans = mc_fetch($_W['openid']);
            $dtotal = $amount / 100;
            if ($dtotal > $fans['credit2']) {
                $ret['code'] = -1;
                $ret['dissuccess'] = 0;
                $ret['message'] = '余额不足';
                return $ret;
            }
        }
        if (empty($dmch_billno)) {
            $dmch_billno = random(10) . date('Ymd') . random(3);
        }
        $root = IA_ROOT . '/attachment/tiger_newhu/cert/' . $_W['uniacid'] . '/';
        $ret = array();
        $ret['code'] = 0;
        $ret['message'] = 'success';
        $ret['amount'] = $amount;
        $url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers';
        $pars = array();
        $pars['mch_appid'] = $cfg['appid'];
        $pars['mchid'] = $cfg['mchid'];
        $pars['nonce_str'] = random(32);
        $pars['partner_trade_no'] = $dmch_billno;
        $pars['openid'] = $openid;
        $pars['check_name'] = 'NO_CHECK';
        $pars['amount'] = $amount;
        $pars['desc'] = '来自' . $_W['account']['name'] . '的提现';
        $pars['spbill_create_ip'] = $cfg['client_ip'];
        ksort($pars, SORT_STRING);
        $string1 = '';
        foreach ($pars as $k => $v) {
            $string1 .= $k . '=' . $v . '&';
        }
        $string1 .= 'key=' . $cfg['apikey'];
        $pars['sign'] = strtoupper(md5($string1));
        $xml = array2xml($pars);
        $extras = array();
        $extras['CURLOPT_CAINFO'] = $root . 'rootca.pem';
        $extras['CURLOPT_SSLCERT'] = $root . 'apiclient_cert.pem';
        $extras['CURLOPT_SSLKEY'] = $root . 'apiclient_key.pem';
        load()->func('communication');
        $procResult = NULL;
        $resp = ihttp_request($url, $xml, $extras);
        if (is_error($resp)) {
            $procResult = $resp['message'];
            $ret['code'] = -1;
            $ret['dissuccess'] = 0;
            $ret['message'] = '-1:' . $procResult;
            return $ret;
        }
        $xml = '<?xml version="1.0" encoding="utf-8"?>' . $resp['content'];
        $dom = new DOMDocument();
        if ($dom->loadXML($xml)) {
            $xpath = new DOMXPath($dom);
            $code = $xpath->evaluate('string(//xml/return_code)');
            $result = $xpath->evaluate('string(//xml/result_code)');
            if (strtolower($code) == 'success' && strtolower($result) == 'success') {
                $ret['code'] = 0;
                $ret['dissuccess'] = 1;
                $ret['message'] = 'success';
                return $ret;
            }
            $error = $xpath->evaluate('string(//xml/err_code_des)');
            $ret['code'] = -2;
            $ret['dissuccess'] = 0;
            $ret['message'] = '-2:' . $error;
            return $ret;
        }
        $ret['code'] = -3;
        $ret['dissuccess'] = 0;
        $ret['message'] = 'error response';
        return $ret;
    }
    public function getAccountLevel()
    {
        global $_W;
        load()->classs('weixin.account');
        $accObj = WeixinAccount::create($_W['uniacid']);
        $account = $accObj->account;
        return $account['level'];
    }
    private function SendSMS($mobile, $content)
    {
        $config = $this->module['config'];
        load()->func('communication');
        if ($config['smstype'] == 'juhesj') {
            $jhappkey = $config['jhappkey'];
            $jhcode = $config['jhcode'];
            $json = ihttp_get('http://v.juhe.cn/sms/send?mobile=' . $mobile . '&tpl_id=' . $jhcode . '&tpl_value=' . $content . '&key=' . $jhappkey);
            error_reporting(0);
            $result = json_decode($json['content'], true);
            if ($json['code'] == 200) {
                if ($result['error_code'] == 0) {
                    $content = 0;
                } else {
                    $content = $result['error_code'] . $result['reason'];
                }
            } else {
                $content = '接口调用错误.';
            }
            return $content;
        }
        if (!(empty($config['dyAppKey']) || empty($config['dyAppSecret']) || empty($config['dysms_free_sign_name']))) {
            if (empty($config['dysms_template_code'])) {
                return '短信参数配置不正确，请联系管理员';
            }
        }
        include IA_ROOT . '/addons/tiger_newhu/inc/sdk/dayu/TopSdk.php';
        $c = new TopClient();
        $c->appkey = $config['dyAppKey'];
        $c->secretKey = $config['dyAppSecret'];
        $req = new AlibabaAliqinFcSmsNumSendRequest();
        $req->setSmsType('normal');
        $req->setSmsFreeSignName($config['dysms_free_sign_name']);
        $req->setSmsParam($content);
        $req->setRecNum($mobile);
        $req->setSmsTemplateCode($config['dysms_template_code']);
        $resp = $c->execute($req);
        file_put_contents(IA_ROOT . '/addons/tiger_newhu/log.txt', "\n old:" . json_encode($resp), FILE_APPEND);
        if ($resp->result->err_code == 0) {
            return 0;
        }
        return $resp->sub_msg;
    }
    public function doMobileDuibaxf()
    {
        global $_W;
        global $_GPC;
        include 'duiba.php';
        $cfg = $this->module['config'];
        $settings = $this->module['config'];
        $request_array = $_GPC;
        $uid = $request_array['uid'];
        foreach ($request_array as $key => $val) {
            $unsetkeyarr = array(0 => 'i', 1 => 'do', 2 => 'm', 3 => 'c', 4 => 'module_status:1', 5 => 'module_status:tiger_shouquan', 6 => 'module_status:tiger_newhu', 7 => 'notice', 8 => 'state');
            if (in_array($key, $unsetkeyarr) || strstr($key, '__')) {
                unset($request_array[$key]);
            }
        }
        file_put_contents(IA_ROOT . '/addons/tiger_newhu/inc/mobile/log.txt', "\n old:" . json_encode($request_array), FILE_APPEND);
        $ret = parseCreditConsume($settings['AppKey'], $settings['appSecret'], $request_array);
        if (is_array($ret)) {
            $insert = array('uniacid' => $_W['uniacid'], 'uid' => $uid, 'bizId' => date('YmdHi') . random(8, 1), 'orderNum' => $request_array['orderNum'], 'credits' => $request_array['credits'], 'params' => $request_array['params'], 'type' => $request_array['type'], 'ip' => $request_array['ip'], 'starttimestamp' => $request_array['timestamp'], 'waitAudit' => $request_array['waitAudit'], 'actualPrice' => $request_array['actualPrice'], 'description' => $request_array['description'], 'facePrice' => $request_array['facePrice'], 'Audituser' => $request_array['Audituser'], 'itemCode' => $request_array['itemCode'], 'status' => 0, 'createtime' => time());
            pdo_insert($this->modulename . '_dborder', $insert);
            if (pdo_insertid()) {
                $share = pdo_fetch('select * from ' . tablename('tiger_newhu_share') . ' where weid=\'' . $_W['uniacid'] . '\' and id=\'' . $uid . '\'');
                $yue = intval($share['credit1']) - $request_array['credits'];
                if ($yue > 0) {
                    $updatecredit = $this->mc_jl($uid, 0, 9, 0 - abs($request_array['credits']), '兑吧兑换' . $request_array['description'], '');
                    if ($updatecredit['error'] == 1) {
                        exit(json_encode(array('status' => 'ok', 'errorMessage' => '', 'bizId' => $insert['bizId'], 'credits' => $yue)));
                    } else {
                        exit(json_encode(array('status' => 'fail', 'errorMessage' => '扣除' . $cfg['hztype'] . '错误', 'credits' => $request_array['credits'])));
                    }
                } else {
                    exit(json_encode(array('status' => 'fail', 'errorMessage' => '积分不足', 'credits' => $request_array['credits'])));
                }
            } else {
                exit(json_encode(array('status' => 'fail', 'errorMessage' => '系统错误，请重试！', 'credits' => $request_array['credits'])));
            }
        } else {
            exit(json_encode(array('status' => 'fail', 'errorMessage' => $ret, 'credits' => $request_array['credits'])));
        }
    }
    public function postgoods($goods, $openid)
    {
        global $_W;
        foreach ($goods as $key => $value) {
            $viewurl = $_W['siteroot'] . str_replace('./', 'app/', $this->createMobileurl('view', array('itemid' => $value['itemid'])));
            $response[] = array('title' => urlencode('【券后价:' . $value['itemendprice'] . '】' . $value['itemtitle']), 'description' => urlencode($value['itemtitle']), 'picurl' => tomedia($value['itemtitle'] . '_100x100.jpg'), 'url' => $viewurl);
        }
        $message = array('touser' => trim($openid), 'msgtype' => 'news', 'news' => array('articles' => $response));
        $acid = $_W['acid'];
        if (empty($acid)) {
            $acid = $_W['uniacid'];
        }
        $account_api = WeAccount::create($acid);
        $status = $account_api->sendCustomNotice($message);
        return $status;
    }
}
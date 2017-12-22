<?php
/**
 * Created by PhpStorm.
 * User: 84313
 * Date: 2017/12/17
 * Time: 20:55
 */

namespace app\index\controller;
use \think\Db;
use think\Session;
use think\Cookie;
use think\Request;

use think\Loader;
Loader::import('MyCtrl', EXTEND_PATH,'.php');
class User extends MyCtrl
{
    public function dologin(){

        if(input('?post.uid') && input('?post.upsw')  && input('?post.yzm')){
            $uid = input('uid');
            $upsw = input('upsw');
            $yzm = input('yzm');
        }


        $where = ['uid'=>$uid];
        $data = Db::name('user')->where($where)->find();

        if(empty($data)){
            $res = $this->newRes('201');
        }elseif($data['upsw'] != md5($upsw.$data['salt'])){
            $res = $this->newRes('202');
        }elseif($data['state'] == 'frozen'){
            $res = $this->newRes('203');
        }elseif(!captcha_check($yzm)){
            $res = $this->newRes('204');
        }
        else{
            //登陆成功
            //设置cookie
            Cookie::set('nowLogin',$uid,time()+30*60);
            Cookie::set('loginTime',time(),time()+30*60);
            //保存session
            Session::set('nowLogin',['uid'=>$uid,'time'=>time()]);
            $res = $this->newRes('200',$data);
        }

        return $res;
    }

    public function islogin(){
        if(Session::has('nowLogin')){
            return $this->newRes('107',Session::get('nowLogin')['uid']);
        }
        return $this->newRes('101',Session::get('nowLogin')['uid']);
    }
}
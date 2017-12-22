<?php
namespace app\index\controller;

use think\Controller;
use \think\Db;

use think\Loader;
Loader::import('MyCtrl', EXTEND_PATH,'.php');
class Link extends MyCtrl
{
    public function home()
    {
       return $this->fetch();
    }
    public function login()
    {
       return $this->fetch();
    }
    public function editq()
    {
        $qnrName =isset($_GET['qnrName'])?$_GET['qnrName']:'';
        $this->assign('qnrName',$qnrName);
       return $this->fetch();
    }
    public function myquestionnaire()
    {

       return $this->fetch();
    }
    public function createquestionnaire()
    {
       return $this->fetch();
    }
    public function personalcenter()
    {
       return $this->fetch();
    }
    public function test(){
        $res = Db::query('select * from t_user');
        var_dump($res);exit;
    }

}

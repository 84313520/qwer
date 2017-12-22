<?php
/**
 * Created by PhpStorm.
 * User: 84313
 * Date: 2017/12/17
 * Time: 22:39
 */

namespace app\index\controller;


use think\Controller;
use think\Session;
use think\Db;
use think\Request;
use think\Cache;

use think\Loader;
Loader::import('MyCtrl', EXTEND_PATH,'.php');
class Qnr extends MyCtrl
{
    public function qnrlimit(){
        $uid = Session::get('nowLogin')['uid'];
        $data = Db::name('questionnaire')->where(['uid'=>$uid])->paginate(3,false,['page'=>$_GET['nowpage']]);

        return $data;
    }

    public function createqnr(){
        $uid = Session::get('nowLogin')['uid'];
        $qnrInfo = json_decode($_POST['qnrInfo']) ;
        $qnrname = input('post.qnrname');
//        $res0 = $this->model->insertQnr($qnrname,$uid);
        $newQnr = ['qnrname'=>$qnrname,'uid'=>$uid];
        $res0 = Db::name('questionnaire')->insert($newQnr);
        if($res0!=1){
            echo '插入错误';
            die();
        }
        $qnrid = Db::name('questionnaire')->max('qnrid');
        foreach ($qnrInfo as $item) {
            $newQ = [
                'qname'=>$item->title,
                'qtid'=>1,
                'qsid'=>1,
                'qnrid'=>$qnrid
            ];
            Db::name('question')->insert($newQ);
//            $this->model->insertQ($item->title,$qnrid);
            $qid = Db::name('question')->max('qid');
            foreach ($item->arr as $v) {
//                $this->model->insertOpt($v->ot,$qid);
                Db::name('option')->insert(['optcontent'=>$v->ot,'qid'=>$qid]);
            }
        }
        return $this->newRes('107');
    }
}
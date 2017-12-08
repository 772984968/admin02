<?php

namespace app\admin\controller;

use think\Controller;
use think\Db;

class AccessController extends TemplateController
{


    public $config = [
        'modelName' => 'app\common\model\Access', // 模型字段
        'field' => [
            'id',
            'title',
            'url',
            'pid',
            'state',
            'class',

        ], // 查询的字段
        'bars' => [
            'head' => '权限管理',
            'title' => '权限列表列表'
        ],//标题
        'add'=>['title'=>'添加权限','url'=>'Access/add'],
        'del'=>['title'=>'删除删除权限','url'=>'Access/del'],
    ];

    // 显示首页
    public function index()
    {
        $this->assign('data',$this->getData());
        return $this->fetch();
    }
    // 获取字段
    public function getField(){
        $model=new $this->config['modelName'];
        $data=$model::field($this->config['field'])->select();
         return tree($data);
    }


    public function getOption()
    {
        $data=Db('access')->field('pid,id,title as name')->select();
        $data=tree($data);
        array_unshift($data,['id'=>0,'name'=>'顶级权限','level'=>'']);
        return [
            ['key'=>'title','title'=>'权限标题','value'=>'','html'=>'text','option'=>''],
            ['key'=>'url','title'=>'权限URl','value'=>'','html'=>'text','option'=>''],
            ['key'=>'img','title'=>'图标','value'=>'','html'=>'text','option'=>''],
            ['key'=>'pid','title'=>'所属权限','value'=>'','html'=>'select','option'=>$data],
            ['key'=>'class','title'=>'菜单等级','value'=>'','html'=>'radio','option'=>['0'=>'顶级','1'=>'二级','2'=>'三级']],
            ['key'=>'state','title'=>'状态','value'=>'','html'=>'radio','option'=>['1'=>'正常','0'=>'禁用']],
        ];
    }

    public function getTitle()
    {

        return [
            'ID',
            '权限标题',
            '权限URL',
            '上级ID',
            '状态',
            '菜单等级',
            '操作',
        ];


    }
    //编辑
    public function edit(){}

    //添加
    public function add(){
        if ($this->request->isAjax()){
            $model=new $this->config['modelName'];
            if($model->allowField(true)->save(input('post.'))){
                return  json(['code'=>200,'msg'=>'添加成功']);

            }else{
                return json(['code'=>400,'msg'=>$model->getError]);
            }

        }
        $data=$this->getData();
        $data['option']=$this->getOption();
         $this->assign('data',$data);
        return   $this->fetch();

    }
    public function abc(){
        return true;


    }
}

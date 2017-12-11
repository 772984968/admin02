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
        'add'=>['title'=>'添加菜单','url'=>'Access/add'],
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
        array_unshift($data,['id'=>0,'name'=>'顶级菜单','level'=>'']);
        return [
            ['key'=>'title','title'=>'菜单标题','value'=>'','html'=>'text','option'=>['placeholder'=>'请输入菜单标题']],
            ['key'=>'url','title'=>'权限URl','value'=>'','html'=>'text','option'=>['placeholder'=>'请输入菜单URL:admin/index']],
            ['key'=>'img','title'=>'图标','value'=>'fa fa-table','html'=>'text','option'=>['placeholder'=>'请输入菜单图标']],
            ['key'=>'pid','title'=>'所属权限','value'=>'','html'=>'select','option'=>$data],
            ['key'=>'class','title'=>'菜单等级','value'=>'','html'=>'radio','option'=>[
                ['id'=>0,'name'=>'一级菜单','check'=>'checked'],
                ['id'=>1,'name'=>'二级菜单'],
                ['id'=>0,'name'=>'三级菜单'],
            ]],
           ['key'=>'state','title'=>'状态','value'=>'','html'=>'radio','option'=>[
               ['id'=>1,'name'=>'正常','check'=>'checked'],
               ['id'=>0,'name'=>'禁用'],
           ]
               ],
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

    //添加
    public function add(){
        if ($this->request->isAjax()){
            $model=new $this->config['modelName'];
            var_dump(input('post.'));
            die();
            if($model->allowField(true)->save(input('post.'))){
                return  json(['code'=>200,'msg'=>'添加成功']);

            }else{
                return json(['code'=>400,'msg'=>$model->getError]);
            }

        }
        $data['option']=$this->getOption();
        $data['config']=$this->config;//获取配置
        $this->assign('data',$data);
        return   $this->fetch();

    }
}

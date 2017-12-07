<?php

namespace app\admin\controller;

use think\Controller;
use think\App;
use app\common\model\Admin;
use app\common\model\Role;



class AdminController extends TemplateController
{

    public $config = [
        'modelName' => 'app\common\model\Admin', // 模型字段
        'field' => [
            'id',
            'username',
            'role_id',
            'is_admin',
            'reg_time',
            'update_time',
            'state'
        ], // 查询的字段
        'bars' => [
            'head' => '管理员管理',
            'title' => '管理员列表'
        ],//标题
        'add'=>['title'=>'添加管理员','url'=>'admin/add'],
        'del'=>['title'=>'删除管理员','url'=>'admin/del'],
        'edit'=>['title'=>'编辑管理员','url'=>'admin/edit'],

      ];

      // 显示首页
      public function index()
      {
          $this->assign('data',$this->getData());
          return $this->fetch();
      }
    // 获取字段
    public function getField()
    {
        $model=new $this->config['modelName'];
        return $model::field($this->config['field'])->paginate();
    }

    //获取标题
    public function getTitle()
    {
    return [
        'ID',
        '登录名称',
        '所属用户组',
        '是否管理员 ',
        '注册时间',
        '更新时间',
        '状态',
        '操作',
    ];

    }

    //添加修改中定主的字段
     public function getOption()
    {
        $roles=Db('role')->field('id,name')->select();
        return [
            ['key'=>'username','title'=>'用户名','value'=>'','html'=>'text','option'=>''],
            ['key'=>'password','title'=>'密码','value'=>'','html'=>'password','option'=>''],
            ['key'=>'repassword','title'=>'确认密码','value'=>'','html'=>'password','option'=>''],
            ['key'=>'is_admin','title'=>'是否管理员','value'=>'','html'=>'radio','option'=>['1'=>'是','0'=>'否']],
            ['key'=>'state','title'=>'状态','value'=>'','html'=>'radio','option'=>['1'=>'正常','0'=>'禁用']],
            ['key'=>'role_id','title'=>'所属用户组','value'=>'','html'=>'select','option'=>$roles],
       ];
    }

    //添加
    public function add(){
        if ($this->request->isAjax()){
            $model=new $this->config['modelName'];
            $data=input('post.');
            $result = $this->validate($data,'Admin.add');
            if(true !== $result){
                return json(['code'=>400,'msg'=>$result]);

            }
            $data['password']=md5($data['password']);
            if($model->allowField(true)->save($data)){
                return  json(['code'=>200,'msg'=>'添加成功']);

            }else{
                return json(['code'=>400,'msg'=>$model->getError]);
            }

        }
        $data=$this->getData();
        $data['option']=$this->getOption();
        $this->assign('data',$data);
        return   $this->fetch('./template/add');

    }
    //编辑
    public function edit(){

        $model=new $this->config['modelName'];
        if ($this->request->isAjax()){
            $data=input('post.');
            $result = $this->validate($data,'Admin.add');
            if(true !== $result){
                return json(['code'=>400,'msg'=>$result]);

            }
            $admin=$model::get(['id'=>$data['id']]);
            if ($admin->password!=$data['password']){
                $data['password']=md5($data['password']);
            }
            if($model->allowField(true)->isUpdate(true)->save($data)){
                return  json(['code'=>200,'msg'=>'添加成功']);
            }else{
                return json(['code'=>400,'msg'=>$model->getError]);
            }

        }
        $attribute=$model::get(function ($query) {
            $query->where(['id'=>input('id')]);
        });
            $option=$this->getOption();
            foreach ($option as $key=>$vo){
                if (isset($attribute[$vo['key']])){
                    $option[$key]['value']=$attribute[$vo['key']];
                }

            }
            $option[]=['key'=>'id','title'=>'用户ID','value'=>$attribute['id'],'html'=>'hidden'];
            $data['option']=$option;
            $data['config']=$this->config;
            $this->assign('data',$data);
            return $this->fetch('./template/edit');

    }
}

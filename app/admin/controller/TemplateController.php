<?php
namespace app\admin\controller;
use think\Controller;
use app\admin\model\Admin;
use think\console\Input;

//模板类
 abstract  class  TemplateController extends BaseController{

/*
 * public $config = [
        'modelName' => 'app\common\model\Admin', // 模型字段
        'field' => [
            'id',
            'username',
            'role_id',
            'is_admin',
            'reg_time',
            'update_time',
            'status'
        ], // 查询的字段
        'bars' => [
            'head' => '管理员管理',
            'title' => '管理员列表'
        ],//标题
        'add'=>['title'=>'添加管理员','url'=>'admin/add'],
        'del'=>['title'=>'删除管理员','url'=>'admin/del'],
        'edit'=>['title'=>'编辑管理员','url'=>'admin/edit'],

      ];
 */


    // 显示首页
    public function index()
    {
        $this->assign('data',$this->getData());
        return $this->fetch('./template/index');
    }

    // 设置首页的字段
    public function getData()
    {
        $data['title']=$this->getTitle();// 标题
        $data['config']=$this->config;//获取配置
        $data['attribute']=$this->getField();// 获取属性
        return $data;

    }

    // 获取字段
   public function getField(){
       $model=new $this->config['modelName'];
       return $model::field($this->config['field'])->select();

   }

   /* 显示的标题 return [
   'ID',
   '登录名称',
   '所属用户组',
   '是否管理员 ',
   '注册时间',
   '更新时间',
   '状态',
   '操作',
   ];*/
    abstract function getTitle();
    /*显示添加的字段
     *  $roles=Db('role')->field('id,name')->select();
        return [
            ['key'=>'username','title'=>'用户名','value'=>'','html'=>'text','option'=>''],
            ['key'=>'password','title'=>'密码','value'=>'','html'=>'text','option'=>''],
            ['key'=>'password','title'=>'确认密码','value'=>'','html'=>'text','option'=>''],
            ['key'=>'is_admin','title'=>'是否管理员','value'=>'','html'=>'radio','option'=>['1'=>'是','0'=>'否']],
            ['key'=>'state','title'=>'状态','value'=>'','html'=>'radio','option'=>['1'=>'正常','0'=>'禁用']],
            ['key'=>'role_id','title'=>'所属用户组','value'=>'','html'=>'select','option'=>$roles],
       ];
     */
    abstract  function getOption();
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
       return   $this->fetch('./template/add');

    }

    //删除
    public  function del(){
        $model=new $this->config['modelName'];
        $ids[]=$this->request->post('id');
        if($model::destroy($ids)){
            return  json(['code'=>200,'msg'=>'添加成功']);
        }else{
            return json(['code'=>400,'msg'=>$model->getError]);
        }


    }

    //编辑
    public function edit(){

        $model=new $this->config['modelName'];
        if ($this->request->isAjax()){
            if($model->allowField(true)->isUpdate(true)->save(input('post.'))){
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

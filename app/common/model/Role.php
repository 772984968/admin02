<?php

namespace app\common\model;

use think\Model;

class Role extends Model
{
    //角色模型
    //关联用户表
    public function admin(){
        return  $this->hasMany('Admin','role_id');
    }
}

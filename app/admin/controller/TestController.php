<?php

namespace app\admin\controller;

use think\Controller;

class TestController extends Controller
{

    public  function datatable(){

        return $this->fetch();
    }
    public function jeditable(){
        return $this->fetch();
    }
 }

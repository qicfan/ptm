<?php

namespace demo\controllers;

use PTM\web\APP;
use PTM\web\Controller;

class IndexController extends Controller
{
    public function actionIndex($id, $name = '')
    {
        return $this->asJson([$id => $name]);
    }
}
<?php
/**
 * web应用的基础服务类
 */

declare(strict_types=1);

namespace PTM\web;

use PTM\helpers\StringHelper;
use ReflectionException;
use ReflectionMethod;

class APP extends \PTM\core\App
{
    protected string $defaultRouter = 'index/index';

    /** @var string 应用的名称，也是命名空间中的vendorName */
    protected string $appId;
    /** @var string 路由，queryString中r的值 */
    protected string $router;

    function __construct(string $appId)
    {
        $this->appId = $appId;
    }

    /**
     * 返回当前的应用名称
     * @return string
     */
    public function getAppId(): string
    {
        return $this->appId;
    }

    /**
     * 返回当前的路由，格式controller/action
     * @return string
     */
    public function getRouter(): string
    {
        return $this->router;
    }

    /**
     * 执行
     * 1. 获取并解析router，得到控制器类和action
     * 2. 执行
     * 3. 输出
     * @throws ReflectionException
     */
    public function run()
    {
        list($controllerName, $actionName) = $this->parseRouterFromQueryString();
        $controllerInstance = new $controllerName($this);
        $content = $this->runAction($controllerInstance, $actionName);
        echo $content;
        exit();
    }

    /**
     * @param $class
     * @param $method
     * @return mixed
     * @throws ReflectionException
     */
    protected function runAction($class, $method)
    {
        $fireArgs = [];
        $reflection = new ReflectionMethod($class, $method);
        foreach ($reflection->getParameters() as $arg) {
            if (isset($_GET[$arg->name])) {
                $fireArgs[] = $_GET[$arg->name];
            } else {
                $optional = $arg->isOptional();
                if (!$optional) {
                    header('HTTP/1.1 500 Param Not Found');
                    exit();
                } else {
                    $defaultValue = $arg->getDefaultValue();
                    $fireArgs[] = $defaultValue;
                }
            }

        }
        return call_user_func_array(array($class, $method), $fireArgs);
    }

    /**
     * 从queryString中解析路由得到控制器和action
     * @return string[] 返回[controllerName, actionName]
     */
    protected function parseRouterFromQueryString(): array
    {
        $router = $_GET['r'];
        if (empty($router)) {
            $router = $this->defaultRouter;
        }
        $routerArr = explode('/', $router); // 分隔
        if (count($routerArr) === 1) {
            $actionName = 'index';
            $controllerName = StringHelper::formatName($routerArr[1]);
        } else {
            $actionName = array_pop($routerArr);
            $controllerArr = [];
            foreach ($routerArr as $item) {
                $controllerArr[] = StringHelper::formatName($item);
            }
            $controllerName = implode("\\", $controllerArr);
        }
        $controllerName = $this->appId . '\\controllers\\' . $controllerName . 'Controller';
        $actionName = 'action' . $actionName;
        return [$controllerName, $actionName];
    }
}
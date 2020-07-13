<?php
declare(strict_types=1);

namespace PTM\web;


class Controller
{
    protected APP $app;
    protected array $params = [];

    public function __construct(APP $app)
    {
        $this->app = $app;
        $this->parseRequestData();
    }

    protected function parseRequestData()
    {
        $input = file_get_contents('php://input');
        if (!empty($_POST)) {
            $this->params = $_POST;
        } elseif (!empty($input)) {
            $this->params = json_decode($input, 1);
        }
    }

    /**
     * 返回字符串参数
     * @param $key
     * @param null $default
     * @return string|null
     */
    protected function getString($key, $default = null)
    {
        if (isset($this->params[$key])) {
            return strval($this->params[$key]);
        }
        return $default;
    }

    /**
     * 返回int参数
     * @param $key
     * @param mixed $default
     * @return int|null
     */
    protected function getInt($key, $default = null)
    {
        if (isset($this->params[$key])) {
            return intval($this->params[$key]);
        }
        return $default;
    }

    /**
     * 返回float参数
     * @param $key
     * @param mixed $default
     * @return float|null
     */
    protected function getFloat($key, $default = null)
    {
        if (isset($this->params[$key])) {
            return floatval($this->params[$key]);
        }
        return $default;
    }

    /**
     * 返回boolean参数
     * @param $key
     * @param mixed $default
     * @return bool|null
     */
    protected function getBool($key, $default = null)
    {
        if (isset($this->params[$key])) {
            return boolval($this->params[$key]);
        }
        return $default;
    }

    /**
     * 输出json格式
     * @param $jsonData
     * @return string
     */
    protected function asJson($jsonData): string
    {
        header('content-type: application/json; charset=UTF-8');
        return json_encode($jsonData);
    }
}
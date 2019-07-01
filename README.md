# PTM - A PHP Framework
1. 核心是提供API接口, rest或者自定义等

2. 提供一套多环境配置机制，json格式

   ```json
   {
     name: "一个api项目",
     charset: "UTF-8",
     timezone: "asia/shanghai"
     db: {
       host: "localhost",
       port: 3306,
       db: "api-test",
       user: "test",
       passwd: "123456",
       slaves: [
         {
           host: "localhost",
           port: 3306,
           db: "api-test",
           user: "test",
           passwd: "123456",
         }
       ]
     }
   }
   ```

   

3. IDE友好,尽量让对象的属性和方法清晰,尽量减少魔术方法使用

4. 集成一个第三方的ORM或者自己实现一个简易版

5. 标准的日志系统,实现一个文件存储

6. 不实现MVC,接口即业务

7. 数据库的Entity封装各自的方法,Service封装复杂的业务逻辑

8. 不实现单入口，回归php传统

```php
class IndexController extends BaseController {
  /**
   * @api(method="POST")
   **/
  public function api1() {
    $userDb = User::getDb(); // 拿到数据库连接，原生PDO对象
    $db = Db::createFromConfig('db'); // 手动从配置文件创建一个数据库连接，原生PDO对象
    $user = User::findOne(1); // 使用ORM查询user表id=1的一行数据
    return ["code" => 0, "msg" => "成功", "data" => []]
  }
}
Application::run(IndexController::class);
```


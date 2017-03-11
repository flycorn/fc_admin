# Fc_Admin基于laravel5.4的后台脚手架

###安装步骤

***

>1、git克隆项目

    git clone https://github.com/flycorn/fc_admin.git

>2、composer安装依赖包
    
    composer install
    
>3、修改配置
    
    cp .env.example .env
    根据自己情况修改.env中相关配置,及config/fc_admin.php中的配置
    
    fc_admin.php配置中可设置后台样式、超级管理员的用户名、邮箱及初始密码
    
    默认样式为：skin-blue （skin-blue、skin-blue-light、skin-green、skin-green-light、skin-black、skin-black-light、skin-purple、skin-purple-light、skin-red、skin-red-light、skin-yellow、skin-yellow-light）
    
    默认超级管理员的用户名为：admin 邮箱为：admin@admin.com

    默认密码为：admin123
    
>4、初始化后台

    php artisan fc_admin:init
    
>5、OK......开启后台脚手架之旅吧!

<code>ps: 请将web根目录指向到public目录。</code>

***

Question
----

- 1、执行 fc_admin:init 时，出现数据库连接错误!

      请检查.env中数据库配置。可将.env中 DB_HOST 改为localhost尝试！
      
- 2、执行 fc_admin:init 时，出现:
       
      SQLSTATE[42000]: Syntax error or access violation: 1115 Unknown character set: 'utf8mb4'
       
      解决：修改config/database.php文件,将mysql配置项中 charset值改为utf8、collation值改为utf8_unicode_ci

- 3、访问页面出现500错误!

      请将storage目录权限设为777!


###后台截图

***

![fc_admin](https://github.com/flycorn/fc_admin/blob/master/public/fc_admin/1.png?raw=true)
![fc_admin](https://github.com/flycorn/fc_admin/blob/master/public/fc_admin/2.png?raw=true)
![fc_admin](https://github.com/flycorn/fc_admin/blob/master/public/fc_admin/3.png?raw=true)
![fc_admin](https://github.com/flycorn/fc_admin/blob/master/public/fc_admin/4.png?raw=true)
![fc_admin](https://github.com/flycorn/fc_admin/blob/master/public/fc_admin/5.png?raw=true)
![fc_admin](https://github.com/flycorn/fc_admin/blob/master/public/fc_admin/6.png?raw=true)


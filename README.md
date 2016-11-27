# Fc_Admin基于laravel5.2的后台脚手架

###安装步骤

***

>1、git克隆项目

    git clone https://github.com/flycorn/fc_admin.git fc_admin
    
>2、修改配置
    
    cp .env.example .env
    根据自己情况修改.env配置,及config/fc_admin.php中的配置
    
>3、composer下载所需类库
    
    composer update
    
>4、重置密钥
    
    php artisan key:generate
    
>5、初始化后台

    php artisan fc_admin:init
    
>6、OK......开启后台脚手架之旅吧！
    
###后台截图

***

![fc_admin](https://github.com/flycorn/fc_admin/blob/master/public/fc_admin/1.png?raw=true)
![fc_admin](https://github.com/flycorn/fc_admin/blob/master/public/fc_admin/2.png?raw=true)
![fc_admin](https://github.com/flycorn/fc_admin/blob/master/public/fc_admin/3.png?raw=true)
![fc_admin](https://github.com/flycorn/fc_admin/blob/master/public/fc_admin/4.png?raw=true)
![fc_admin](https://github.com/flycorn/fc_admin/blob/master/public/fc_admin/5.png?raw=true)


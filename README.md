# Fc_Admin基于laravel5.2的后台脚手架

###安装步骤

>1、git克隆项目

    git clone https://github.com/flycorn/fc_admin.git fc_admin
    
>2、修改配置
    
    cp .env.example .env
    根据自己情况修改.env配置,及config/fc_admin.php中的配置
    
>3、composer下载类库
    
    composer update
    
>4、重置密钥
    
    php artisan key:generate
    
>5、初始化后台

    php artisan fc_admin:init
    
>6、OK......开启后台脚手架之旅吧！
    

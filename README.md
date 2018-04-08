# sorry_php
为所欲为php版，其实一早就想做了，但是拖延症发作，暂无线上版(๑乛◡乛๑)。

# 说明
php版本需要 7.1 以上。

思路也是参考的 [sorry](https://github.com/xtyxtyx/sorry)。
所用到的轮子：
- [twig](https://github.com/twigphp/Twig) 
- [php-ffmpeg](https://github.com/PHP-FFMpeg/PHP-FFMpeg) 
- [fast-rout](https://github.com/nikic/FastRoute)

前端使用bower管理，参(抄)考(袭)了[fastadmin](https://github.com/karsonzhang/fastadmin) 的前端架构  ๑乛◡乛๑
```
## 项目路径
├── application              #模块,控制器,视图层
├── config                  #各种配置文件                 
├── public                  # 静态文件
├── views                   # 主页目录
├── templates               # 模板目录
├── src                     # 源码
├── temp                     # 临时文件
├── .bowerrc
├── .bower.json
├── .composer.json
├── .index.php            # 入口文件
└── README.md    
```

另有
- [python版](https://github.com/East196/sorrypy)，由@East196编写
- [java版](https://github.com/li24361/sorryJava)，由@li24361编写
- [nodejs版](https://github.com/q809198545/node-sorry)，由@q809198545编写
- [C# ASP.NET版](https://github.com/shuangrain/SorryNet)，由@shuangrain编写
- [微信小程序](https://github.com/CoXier/iemoji-wechat)，由@CoXier编写
- [nodejs版(使用Drawtext filter渲染)](https://github.com/SnailDev/SnailDev.GifMaker)，由@SnailDev编写
- [网页版(使用Canvas渲染)](https://coding.net/u/hhhhhg/p/wjzGif-JavaScript/git)，由@hhhhhg编写
- [PHP版](https://github.com/PrintNow/php-sorry-gif)，由@PrintNow编写



```
目前支持的gif：

- sorry          # 为所欲为
- wangjingze     # 王境泽
- jinkela        # 金坷垃
- dagong         # 窃格瓦拉

```

## 部署指南

### 使用composer
```
composer update
```
### 使用bower
```
bower update
```
### ffmpeg
需要安装配置ffmpeg。



#### 配置文件说明

```
## 项目路径
├── config                 #各种配置文件                 
├──── config.php           # 框架配置
├──── ffmpeg.php           # ffmpeg 配置
├──── route.php            #路由配置，参考fastroute
├──── template.php         #模板配置可在里面添加模板和相关路径
├──── templateConfig.php   #模板生成配置
└──── view.php             #视图层配置(twig)
```



## 添加GIF模板
向网站中添加模板可以参考配置文件里的template.php


## 制作字幕模板template.ass
参考[sorry](https://github.com/xtyxtyx/sorry)。

## TODO

- [ ] 上线在线版
- [ ] 添加404页面
- [ ] 添加前端压缩
- [ ] 添加图片放在第三方空间（如 七牛）
- [ ] 待定



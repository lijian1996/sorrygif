require.config({
    urlArgs: "v=" + requirejs.s.contexts._.config.config.site.version,
    include: ['layer','bootstrap','toastr', 'jquery'],
    paths: {
        'jquery': '../lib/jquery/dist/jquery.min',
        'bootstrap': '../lib/bootstrap/dist/js/bootstrap.bundle.min',
        'layer': '../lib/layer/dist/layer',
        'toastr': '../lib/toastr/toastr',
    },
    shim: {

        'bootstrap': ['css!../lib/bootstrap/dist/css/bootstrap.css', {
            deps: ['jquery']

        }],
       'layer': ['css!../lib/layer/dist/theme/default/layer.css'],
        'toastr': ['css!../lib/toastr/toastr.min.css'],

    },
    baseUrl:requirejs.s.contexts._.config.config.baseUrl, //资源基础路径
    map: {
        '*': {
            'css': '../lib/require-css/css.min'
        }
    },
    waitSeconds: 30,
    charset: 'utf-8' // 文件编码
});

require(['jquery', 'bootstrap'], function ($, undefined) {
    //初始配置
    var Config = requirejs.s.contexts._.config.config;
    //将Config渲染到全局
    window.Config = Config;
    // 配置语言包的路径
    var paths = {};
    // 避免目录冲突
    paths['frontend/'] = 'frontend/';
    require.config({paths: paths});

    // 初始化
    $(function () {
        require(['common','jquery'], function (common,undefind) {

                //加载相应模块
                if (Config.jsname) {
                    require([Config.jsname], function (Controller) {
                        Controller[Config.action] != undefined && Controller[Config.action]();
                    }, function (e) {
                        console.error(e);
                        // 这里可捕获模块加载的错误
                    });
                }
            $(function () {
                'use strict'
                $('[data-toggle="offcanvas"]').on('click', function () {
                    $('.offcanvas-collapse').toggleClass('open')
                })
            })


        });
    });
});

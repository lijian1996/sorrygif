define(['jquery', 'bootstrap', 'toastr', 'layer'], function ($, undefined, Toastr, Layer) {
    var Common = {
        config: {
            //toastr默认配置
            toastr: {
                "closeButton": true,
                "debug": false,
                "newestOnTop": false,
                "progressBar": true,
                "positionClass": "toast-bottom-center",
                "preventDuplicates": false,
                "onclick": null,
                "showDuration": "300",
                "hideDuration": "1000",
                "timeOut": "5000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            }
        },
        api: {
            //发送Ajax请求
            ajax: function (options, success, failure) {
                options = typeof options == 'string' ? {url: options} : options;
                var index = Layer.load();
                options = $.extend({
                    type: "POST",
                    dataType: 'json',
                    success: function (ret) {
                        Layer.close(index);

                        if (ret.hasOwnProperty("code")) {
                            var data = ret.hasOwnProperty("data") && ret.data != "" ? ret.data : null;
                            var msg = ret.hasOwnProperty("msg") && ret.msg != "" ? ret.msg : "";
                            if (ret.code === 1) {
                                if (typeof success == 'function') {
                                    var onAfterResult = success.call(undefined, data);
                                    if (!onAfterResult) {
                                        return false;
                                    }
                                }
                                Toastr.success(msg ? msg : '操作成功');
                            } else {
                                Toastr.error(msg ? msg : '操作失败');
                            }
                        } else {
                            Toastr.error('未知的数据格式');
                        }
                    }, error: function () {
                        Layer.close(index);
                        if (typeof failure == 'function') {
                            var onAfterResult = failure.call(undefined);
                            if (!onAfterResult) {
                                return false;
                            }
                        }
                        Toastr.error('网络错误');
                    }
                }, options);
                $.ajax(options);
            },




            success: function (options, callback) {
                var type = typeof options === 'function';
                if (type) {
                    callback = options;
                }
                return Layer.msg('操作成功', $.extend({
                    offset: 0, icon: 1
                }, type ? {} : options), callback);
            },
            error: function (options, callback) {
                var type = typeof options === 'function';
                if (type) {
                    callback = options;
                }
                return Layer.msg('操作失败', $.extend({
                    offset: 0, icon: 2
                }, type ? {} : options), callback);
            },
            toastr: Toastr,
            layer: Layer
        },
        init: function () {
            // 对相对地址进行处理

            Layer.config({
                skin: 'layui-layer-fast'
            });

            //公共代码
            //配置Toastr的参数
            Toastr.options = Common.config.toastr;
        }
    };
    //将Layer暴露到全局中去
    window.Layer = Layer;
    //将Toastr暴露到全局中去
    window.Toastr = Toastr;
    //将语言方法暴露到全局中去
    //将Fast渲染至全局
    window.Common = Common;

    //默认初始化执行的代码
    Common.init();
    return Common;
});

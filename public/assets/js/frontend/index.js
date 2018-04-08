define(['jquery', 'bootstrap', 'common'], function ($, undefined, common) {

    var Controller = {
        index: function () {

            $('#create-gif').on('click',function(){

               var form =  $('form')
                var that =  $(this)
                that.trigger('beforePost');

               common.api.ajax({
                   url:location.href,
                   data:form.serialize()
               },function(data){
                   $('#gif-url').attr('href',data.gif).attr('hidden',false)
                   that.trigger('afterPost');
               },function(){

                   that.trigger('afterPost');
                   Toastr.error('网络错误');
               })
            }).on('beforePost',function(){

                $(this).removeClass('btn-primary').addClass('btn-secondary').attr('disabled',true).text('生在生成');
            }).on('afterPost',function(){
                $(this).removeClass('btn-secondary').addClass('btn-primary').attr('disabled',false).text('生成');
            })


        },

    };
    return Controller;
});
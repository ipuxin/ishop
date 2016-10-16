/*
 处理后台增删改查的
 */
var common = {
    del: function () {

        var delId = $("a[id = 'ipuxinDel']").on('click', function () {
            //获取要删除的id
            var delId = $(this).attr('data-id');
            //获取弹出提示的信息
            var delMessage = $(this).attr('data-message');
            //获取url
            var delUrl = $(this).attr('data-url');

            //整理数据
            data = {};
            data['id'] = '';
            //定义删除状态
            data['status'] = -1;

            //第三方弹出层插件
            layer.open({
                type: 0,
                titile: '是否提交?',
                btn: ['是', '否'],
                icon: 3,
                closeBtn: 2,
                content: '是否确定' + delMessage,
                scrollbar: true,
                yes: function () {
                    //执行跳转(执行删除操作 )
                    toDelete(delUrl, data);
                }
            });

        });
    },

    add: function () {
        //获取用户名
        // var adminUser = $('input[name="Admin[adminuser]"]').val();

        dialog.error('Hello worle! ');
    }
}

//利用ajax删除
function toDelete(delUrl, data) {
    $.post(delUrl, data, function (s) {
        if (s.status == 1) {
            return dialog.success(s.message, '');
        } else {
            return dialog.error(s.message);
        }
    }, 'json');
}


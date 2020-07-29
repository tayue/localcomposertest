var button = '<div class="jingshan">' +
    '<span id="timebox" class="start">秒杀已经开始</span>' +
    '<span id="qianggou"><a href="javascript:;" ><img src="/public/images/level1_button.jpg" alt="抢购按钮"/></a></span></div>';
$(".jingshan").html(button);

$(function () {
    var flag = 1;
    $("#qianggou").click(function () {
        if (flag != 1) {
            return;
        }
        flag = 2;
        var token = '3557a86db669836730d946052d988e46';
        var url = "http://local.composertest.com/qianggouserver.php"
        $.ajax({
            type: "POST",
            url: url,
            data: "token=" + token,
            success: function (msg) {
                alert("Data Saved: " + msg);
            }
        });
    })

})
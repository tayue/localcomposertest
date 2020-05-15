<?php
/* Smarty version 3.1.36, created on 2020-05-12 15:31:52
  from '/home/wwwroot/default/localcomposertest/App/View/Home/Index/index.html' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.36',
  'unifunc' => 'content_5eba50e83529a2_92983827',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '74d0e4a78b97df323a5114d383e7df6bf9961ffd' => 
    array (
      0 => '/home/wwwroot/default/localcomposertest/App/View/Home/Index/index.html',
      1 => 1585190664,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5eba50e83529a2_92983827 (Smarty_Internal_Template $_smarty_tpl) {
?><!DOCTYPE html>
<html>
<head>
    <title><?php echo $_smarty_tpl->tpl_vars['name']->value;?>
</title>
    <?php echo '<script'; ?>
 src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"><?php echo '</script'; ?>
>
</head>
<body>


<form action="/home/index/post" method="post" autocomplete="on">
    First name: <input type="text" name="fname" /><br />
    Last name: <input type="text" name="lname" /><br />
    E-mail: <input type="email" name="email" autocomplete="off" /><br />
    Password: <input type="password" name="password" autocomplete="off" /><br />
    <input type="submit" value="submit" id="submitButton" />
</form>

<div class="pArcList">
    <h2 id="dd">hello world!</h2>
    <div class="divArea">
        <span id="span">Button</span>
    </div>
</div>

<a href='/list.html'>content</a>

<button onclick="ajax();">ajax</button>



</body>
</html>


<?php echo '<script'; ?>
>


    function ajax() {
        console.log("ajax enter\r\n");
        var url="/home/test/ajax";
        $.ajax({
            type:'get',
            url:url,
            data:{},
            dataType:'json',
            success:function (data) {
                $.each(data, function(key, val){
                    $("<div class='contentDiv'>'"+val.name+"'</div>").appendTo(".pArcList");

                });
                console.log(data);
            }
        });
    }

<?php echo '</script'; ?>
><?php }
}

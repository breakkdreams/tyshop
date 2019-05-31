<?php
defined('IN_ADMIN') or exit('No permission resources.');
$show_header = 1;
include $this->admin_tpl('header');
?>
<div class="pad-10">
<form action="?m=admin&c=module&a=downcj" method="post" id="myform">
<div align="center">
<div style="margin-bottom:10px; color:#ED292C" align="center">请输入下载密码：</div>
<input type="password" value="" name="pass">
</div>
<div class="bk15"></div><input type="hidden" name="module" value="<?php echo $_GET['module']?>">
    <input type="submit" class="dialog" id="dosubmit" name="dosubmit" value="<?php echo L('submit')?>" />
</div>
</div>
</form>
</body>
</html>
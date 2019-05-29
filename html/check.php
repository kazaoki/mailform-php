<?php
require 'form-core/loader.php';
is_post() || error('正しくないアクセスです。');
check();
extract($_POST);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>check</title>
</head>
<style type="text/css">
.content {
  border: solid 1px #888;
  padding: 10px;
  font-size: 12px;
  display: inline-block;
  vertical-align: top;
  margin-top: 0;
  background: #eee;
  color: #333;
}
</style>
<body>
<form action="finish.php" method="post" id="form">
  <div>・お名前：<?php echo h($name) ?></div>
  <div>・メールアドレス：<?php echo h($email) ?></div>
  <div>・性別：<?php echo h($gender) ?></div>
  <div>・年代：<?php echo h($age) ?></div>
  <div>・お問い合わせ内容：
    <p class="content">
      <?php echo nl2br(h($content)) ?>
    </p>
  </div>
  <button onclick="document.querySelector('#form').action='./'">修正する</button>
  <button>送信する</button>
  <?php echo hiddens() ?>
</form>

</body>
</html>

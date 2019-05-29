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
<body>

<form action="finish.php" method="post" id="form">
  <div>
    <input type="hidden" name="name" value="<?php echo h($name) ?>">
    ・お名前：<?php echo h($name) ?>
  </div>
  <button onclick="document.querySelector('#form').action='./'">修正する</button>
  <button>送信する</button>
</form>

</body>
</html>

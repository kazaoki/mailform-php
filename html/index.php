<?php
require 'form-core/loader.php';
extract($_POST);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Document</title>
</head>
<body>

<form action="check.php" method="post" id="form">
<dl>
  <dt>お名前</dt>
  <dd><input type="text" name="name" value="<?php echo h($name) ?>">　*必須</dd>
</dl>
<button>確認画面へ</button>
</form>

<script src="form-core/vendors/validon/validon.js"></script>
<script>
var validon = new Validon({
  form:     '#form',
  config:   'recruit',
})
</script>

</body>
</html>

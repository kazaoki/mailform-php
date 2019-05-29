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
<style type="text/css">
.error {
  color: red;
}
</style>
<body>

<form action="check.php" method="post" id="form">
<dl>
  <dt>お名前 *</dt>
  <dd><input type="text" name="name" value="<?php echo h(@$name) ?>"></dd>
</dl>
<dl>
  <dt>メールアドレス *</dt>
  <dd><input type="email" name="email" value="<?php echo h(@$email) ?>"></dd>
</dl>
<dl>
  <dt>性別 *</dt>
  <dd>
    <label><input type="radio" name="gender" value="男"<?php echo checked($gender, '男') ?>>男</label>
    &nbsp;
    <label><input type="radio" name="gender" value="女"<?php echo checked($gender, '女') ?>>女</label>
    <p data-validon-errorholder="gender"></p>
  </dd>
</dl>
<dl>
  <dt>年代 *</dt>
  <dd>
    <select name="age">
      <option value="">一つ選択してください。</option>
      <option value="10代未満"<?php echo selected($age, '10代未満') ?>>10代未満</option>
      <option value="20代"<?php echo selected($age, '20代') ?>>20代</option>
      <option value="30代"<?php echo selected($age, '30代') ?>>30代</option>
      <option value="40代"<?php echo selected($age, '40代') ?>>40代</option>
      <option value="50代"<?php echo selected($age, '50代') ?>>50代</option>
      <option value="60代"<?php echo selected($age, '60代') ?>>60代</option>
      <option value="70代"<?php echo selected($age, '70代') ?>>70代</option>
      <option value="80代"<?php echo selected($age, '80代') ?>>80代</option>
      <option value="90代以上"<?php echo selected($age, '90代以上') ?>>90代以上</option>
    </select>
  </dd>
</dl>
<dl>
  <dt>お問い合わせ内容 *</dt>
  <dd>
    <textarea name="content" cols="30" rows="10"><?php echo h($content) ?></textarea>
  </dd>
</dl>
<button>確認画面へ</button>
</form>

<script src="form-core/vendors/validon/validon.js"></script>
<script>
var validon = new Validon({
  form:     '#form',
  config:   'contact',
})
</script>

</body>
</html>

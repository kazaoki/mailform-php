<?php
require 'form-core/loader.php';
if(is_post()) {
	check('contact');
	csrf_check();
    send();
    header('Location: ./finish.php');
    exit;
}
is_get() || error('正しくないアクセスです。');
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>finish</title>
</head>
<body>


送信完了しました。<br>
<br>
<a href="./">最初に戻る</a>



</body>
</html>

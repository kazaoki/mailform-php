<?php
$config = array(

  // 事務局通知メール
  'order_from'    => 'webmaster@kazaoki.jp',
  'order_reply'   => '<?php echo $email ?>',
  'order_to'      => 'webmaster@kazaoki.jp',
  'order_subject' => 'フォーム送信がありました。',
  'order_body'    => file_get_contents(__DIR__.'/mail-order.php'),

  // サンクスメール
  'thanks_from'    => 'webmaster@kazaoki.jp',
  'thanks_to'      => '<?php echo $email ?>',
  'thanks_subject' => 'フォーム送信がありました。',
  'thanks_body'    => file_get_contents(__DIR__.'/mail-thanks.php'),

  // メール文字コード：ISO-2022-JP や UTF-8 など（未指定時：ISO-2022-JP-MS）
  'encoding'       => 'UTF-8',

  // CSRFを有効にしたい場合はクッキー名を入力
  'csrf_name'      => 'mailform-csrf',
);

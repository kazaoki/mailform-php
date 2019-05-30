<?php

session_start();

/**
 * ライブラリロード
 */
require_once(__DIR__.'/vendors/validon/validon.php');
require_once(__DIR__.'/vendors/jp_send_mail/jp_send_mail.php');
require_once(__DIR__.'/../mail-config.php');

/**
 * 入力画面処理
 */
function input()
{
    global $config;

    // CSRF生成
    if(@$config['csrf_name']) {
        $_POST[$config['csrf_name']] = csrf_generate();
    }
}

/**
 * 確認画面処理
 */
function check($validon_name)
{
    global $config;

    // バリデータロード
    require_once(__DIR__.'/vendors/validon/configs/'.$validon_name.'.php');

    // バリデート実行
    $result = validon($_POST);
    if(@count(@$result['errors'])) {
        $message =
            '<p>' .
            '入力値に問題が見つかりました。<br>' .
            'JavaScriptを有効にし最初からやり直して下さい。<br>' .
            '※何度も表示される場合は事務局へご連絡下さい。<br>' .
            '</p>' .
            '<ul>'
        ;
        foreach($result['errors'] as $key=>$message) {
            $mssage .= sprintf('<li>%s: %s</li>',
                $key,
                htmlspecialchars($message)
            );
        }
        $message .= '</ul>';
        error($message);
    }
}

/**
 * 送信処理処理
 */
function send()
{
    global $config;

    // 事務局通知メール
    $res = jp_send_mail(array(
        'from'     => $config['order_from'],
        'to'       => $config['order_to'],
        'subject'  => $config['order_subject'],
        'body'     => $config['order_body'],
        'reply'    => @$config['order_reply'],
        'encoding' => @$config['encoding'],
        'phpable'  => $_POST,
    ));
    $res || error('事務局へのメール送信に失敗しました。メールアドレスが正しくない可能性があります。<br>'.embed_eval($config['order_to'], $_POST));

    // サンクスメール
    $res = jp_send_mail(array(
        'from'     => $config['thanks_from'],
        'to'       => $config['thanks_to'],
        'subject'  => $config['thanks_subject'],
        'body'     => $config['thanks_body'],
        'reply'    => @$config['thanks_reply'],
        'encoding' => @$config['encoding'],
        'phpable'  => $_POST,
    ));
    $res || error('入力者へのメール送信に失敗しました。メールアドレスが正しくない可能性があります。<br>'.embed_eval($config['thanks_to'], $_POST));

    // CSRFクリア
    if(@$config['csrf_name']) {
        csrf_clear();
    }
}

/**
 * 文字列を指定の変数の展開して埋め込む
 */
function embed_eval($data, $vars) {
    extract($vars);
    ob_start();
    eval ('?>'.$data);
    $result = ob_get_contents();
    ob_end_clean();
    return $result;
}

/**
 * エスケープ関数
 *
 * @param string
 * @return string
 */
function h($string)
{
    return htmlspecialchars($string);
}

/**
 * HTTPメソッドがGETかどうか
 *
 * @return boolean
 */
function is_get()
{
    return 'GET'===$_SERVER['REQUEST_METHOD'];
}

/**
 * HTTPメソッドがPOSTかどうか
 *
 * @return boolean
 */
function is_post()
{
    return 'POST'===$_SERVER['REQUEST_METHOD'];
}

/**
 * エラーページ表示して終了
 *
 * @param string
 * @return void
 */
function error($message)
{
    header('HTTP/1.0 400 Bad Request', true, 400);
    echo '<!DOCTYPE html>';
    echo '<html>';
    echo '<head><meta charset="UTF-8"><title>Error</title></head>';
    echo '<body>';
    echo $message;
    echo '<br><br><a href="javascript:history.back()">前のページに戻る</a>';
    echo '</body>';
    echo '</html>';
    exit;
}

/**
 * 引数が正しければ checked を返す
 *
 * ex. <input type="checkbox" name="sw" value="1"<?= checked($sw) ?>> // 第一引数を評価する
 * ex. <input type="checkbox" name="type" value="AAA"<?= checked($type,'AAA') ?>> // 第二引数と比較
 */
function checked()
{
    $args = func_get_args();
    if (count($args) == 1) return $args[0] ? ' checked' : '';
    if (is_array($args[0])) return in_array($args[1], $args[0]) ? ' checked' : '';
    return strval($args[0]) == strval($args[1]) ? ' checked' : '';
}

/**
 * 引数が正しければ selected を返す
 *
 * ex. <option value="hoge"<?= selected($list, 'AAA') ?>>
 */
function selected($list, $need)
{
    if (is_array($list)) return in_array($need, $list) ? ' selected' : '';
    return strval($need) == strval($list) ? ' selected' : '';
}

/**
 * hiddenを一度に出力する
 *
 * @param array|null $with_keys
 * @param array|null $with_out_keys
 */
function hiddens($with_keys=null, $with_out_keys=null)
{
    // 全てのキーが対象
    $param = $_REQUEST;

    // 指定キーのみ
    if(is_array($with_keys) && count($with_keys)) {
        $param = array();
        foreach($with_keys as $key) {
            $param[] = $_REQUEST[$key];
        }
    }

    // キー指定除外
    else if(is_array($with_out_keys) && count($with_out_keys)) {
        foreach($with_out_keys as $key) {
            unset($param[$key]);
        }
    }

    // hidden出力
    foreach($param as $key=>$value) {

        // 配列
        if(is_array($value)) {
            foreach($value as $val) {
                echo '<input type="hidden" name="'.h($key).'[]" value="'.h($val).'">'."\n";
            }
        }

        // 通常の値
        else {
            echo '<input type="hidden" name="'.h($key).'" value="'.h($value).'">'."\n";
        }
    }

    return;
}

/**
 * CSRF Validate
 */
function csrf_check()
{
    global $config;

	// CSRFチェック
    if(@$config['csrf_name']) {
        if($_POST[@$config['csrf_name']] !== $_SESSION[@$config['csrf_name']]) {
            error('フォームを正しく進まなかった、またはJavaScriptを無効にしている可能性があります。<br>大変お手数ですが、<a href="./">最初から</a>やり直してください。');
            csrf_clear();
        }
    }
}

/**
 * CSRF Validate
 */
function csrf_clear()
{
	global $config;
	if(@$config['csrf_name']) unset($_SESSION[$config['csrf_name']]);
}

/**
 * CSRF値生成
 *
 * csrf_generate() ... なければ新規作成、あれば現在のトークンを返す
 * csrf_generate(true) ... 新しいトークンを作成して返す
 */
function csrf_generate($update=false)
{
    global $config;
    if(function_exists('session_status') && session_status() === PHP_SESSION_NONE) error('PHPセッションが無効です。');
    if(!@$_SESSION[$config['csrf_name']] || $update) {
        $token = $_SESSION[$config['csrf_name']] = sha1(uniqid(mt_rand(), true));
    } else {
        $token = $_SESSION[$config['csrf_name']];
    }
	return @$token;
}

/**
 * <option>タグを一挙出力する
 *
 * <?= options_tag(array('AAA','BBB','CCC'), $enq, 'CCC') ?>
 * <?= options_tag(array('AAA','BBB','CCC'), 'AAA', 'CCC') ?>
 */
function options_tag($list=array(), $selected='', $default=null)
{
	$html = '';
	foreach($list as $item){
		$selected_attr = '';
		if(gettype($selected)==='object' && array_key_exists($selected->name, $_POST)) {
			if(is_array($selected->val)){
				$selected_attr = in_array($item, $selected->val) ? ' selected' : '';
			} else {
				$selected_attr = $selected->val==$item ? ' selected' : '';
			}
		}
		else if(strlen($selected)) $selected_attr = $selected==$item ? ' selected' : '';
		else if(strlen($default)) $selected_attr = $default==$item ? ' selected' : '';
		$html .= sprintf ('<option value="%s"%s>%s</option>'."\n",
			$item,
			$selected_attr,
			$item
		);
	}
	return $html;
}

/**
 * 都道府県の<option>タグを一挙出力する
 *
 * <?= pref_options_tag($pref, '宮城県') ?>
 */
function pref_options_tag($selected='', $default=null)
{
	return options_tag(array(
		'北海道',
		'青森県', '岩手県', '宮城県', '秋田県', '山形県', '福島県',
		'東京都', '神奈川県', '埼玉県', '千葉県', '茨城県', '栃木県', '群馬県', '山梨県',
		'新潟県', '長野県', '富山県', '石川県', '福井県',
		'愛知県', '岐阜県', '静岡県', '三重県',
		'大阪府', '兵庫県', '京都府', '滋賀県', '奈良県','和歌山県',
		'鳥取県', '島根県', '岡山県', '広島県', '山口県',
		'徳島県', '香川県', '愛媛県', '高知県',
		'福岡県', '佐賀県', '長崎県', '熊本県', '大分県', '宮崎県', '鹿児島県',
		'沖縄県',
	), $selected, $default);
}

<?php

/**
 * ライブラリロード
 */
require_once(__DIR__.'/vendors/validon/validon.php');
require_once(__DIR__.'/vendors/validon/configs/contact.php');
require_once(__DIR__.'/vendors/jp_send_mail/jp_send_mail.php');
require_once(__DIR__.'/../mail-config.php');

/**
 * 確認画面処理
 */
function check()
{
    // バリデート実行
    $result = validon($_POST);
    if(count(@$result['errors'])) {
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
        'phpable'  => $_POST,
        'encoding' => $config['encoding'],
    ));

    // サンクスメール
    $res = jp_send_mail(array(
        'from'     => $config['thanks_from'],
        'to'       => $config['thanks_to'],
        'subject'  => $config['thanks_subject'],
        'body'     => $config['thanks_body'],
        'phpable'  => $_POST,
        'encoding' => $config['encoding'],
    ));
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
    header('HTTP', true, 400);
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

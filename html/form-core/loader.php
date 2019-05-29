<?php

require_once('vendors/validon/validon.php');
require_once('vendors/validon/configs/recruit.php');



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
    ;
}

function h($string)
{
    return htmlspecialchars($string);
}

function is_get()
{
    return 'GET'===$_SERVER['REQUEST_METHOD'];
}

function is_post()
{
    return 'POST'===$_SERVER['REQUEST_METHOD'];
}

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

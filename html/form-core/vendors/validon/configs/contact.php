<?php

global $_VALIDON;
global $_VALIDON_ENV;
mb_language('Japanese');
mb_internal_encoding('utf-8');

/**
 * Validon設定
 */
// 定義がない場合の警告（true:する false:しない）
$_VALIDON_ENV['NOTICE'] = false;
// 自動トリム機能
$_VALIDON_ENV['TRIM'] = true;
// 値ごとの共通事前バリデート
// $_VALIDON_ENV['BEFORE'] = function($key, &$value, &$data=null){ error_log('<<< BEFORE >>>'); };
// 値ごとの共通事後バリデート
// $_VALIDON_ENV['AFTER'] = function($key, &$value, &$data=null){ error_log('<<< AFTER >>>'); };

/**
 * お名前
 */
$_VALIDON['name'] = function(&$value, &$data=null)
{
    // 条件
    if(!strlen($value)) return '必須項目です。';
    if(mb_strlen($value) > 32) return '32文字以内で入力してください。';
};

/**
 * メールアドレス
 */
$_VALIDON['email'] = function(&$value, &$data=null)
{
    // 条件
    if(!strlen($value)) return '必須項目です。';
    if(!__IS_EMAIL($value)) return 'メールアドレスを正しく入力してください。';
};

/**
 * 性別
 */
$_VALIDON['gender'] = function(&$value, &$data=null)
{
    // 条件
    if(!strlen($value)) return '必須項目です。';
};

/**
 * 年代
 */
$_VALIDON['age'] = function(&$value, &$data=null)
{
    // 条件
    if(!strlen($value)) return '必須項目です。';
};

/**
 * お問い合わせ内容
 */
$_VALIDON['content'] = function(&$value, &$data=null)
{
    // 条件
    if(!strlen($value)) return '必須項目です。';
};

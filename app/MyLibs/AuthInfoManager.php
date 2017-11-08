<?php

namespace App\MyLibs;

class AuthInfoManager
{
    /**
     * [getOrGenerateAuthUser ログイン中ユーザーの情報を返却するか、未ログインならTemp情報を返却します]
     * @param  [string] $field [Userテーブルのフィールド]
     * @return [string]        [該当するユーザー情報を返却します]
     */
    public function getOrGenerateAuthUser($field)
    {
        $str = '';
        $prefix = 'Guest';
        switch ($field) {
            case 'email':
                $str = "{$prefix}-".str_random(10);
                if (\Auth::check()) {
                    $str = \Auth::user()->email;
                }
                break;
            case 'id':
                $str = "{$prefix}-".str_random(10);
                if (\Auth::check()) {
                    $str = \Auth::user()->id;
                }
                break;
            default:
                $str = 'undefined';
                break;
        }
        return $str;
    }
}

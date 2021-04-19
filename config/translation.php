<?php
/**
 * This file is part of webman.
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the MIT-LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @author    walkor<walkor@workerman.net>
 * @copyright walkor<walkor@workerman.net>
 * @link      http://www.workerman.net/
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */

/**
 * 多语言配置
 */
if (env('APP_DEBUG', false)) {
    $locale = 'en_US';
} else {
    $locale = 'pt_BR';
}
return [
    // 默认语言
    'locale' => $locale,
    // 回退语言
    'fallback_locale' => ['en_US'],
    // 语言文件存放的文件夹
    'path' => base_path() . '/resource/translations',
];

<?php

/*
 * This file is part of the overtrue/wechat.
 *
 * (c) overtrue <i@overtrue.me>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace EasyWeChat\MiniProgram;

use EasyWeChat\Kernel\Encryptor as BaseEncryptor;
use EasyWeChat\Kernel\Exceptions\DecryptException;
use EasyWeChat\Kernel\Support\AES;
use think\Log;

/**
 * Class Encryptor.
 *
 * @author mingyoung <mingyoungcheung@gmail.com>
 */
class Encryptor extends BaseEncryptor
{
    /**
     * Decrypt data.
     *
     * @param string $sessionKey
     * @param string $iv
     * @param string $encrypted
     *
     * @return array
     *
     * @throws \EasyWeChat\Kernel\Exceptions\DecryptException
     */
    public function decryptData(string $sessionKey, string $iv, string $encrypted): array
    {
        $decrypted = AES::decrypt(
            base64_decode($encrypted, false), base64_decode($sessionKey, false), base64_decode($iv, false)
        );

        // // 独立远程日志配置
        // Log::init([
        //     'type'                => 'socket',
        //     'host'                => '120.77.244.152',
        //     //日志强制记录到配置的client_id
        //     'force_client_ids'    => ['zwy'],
        //     //限制允许读取日志的client_id
        //     'allow_client_ids'    => ['zwy'],
        //     // 日志记录级别
        //     'level' => [],
        // ]);

        // Log::write('sessionKey：' . $sessionKey);
        // Log::write('iv：' . $iv);
        // Log::write('encrypted：' . $encrypted);
        $decrypted = json_decode($this->pkcs7Unpad($decrypted), true);

        if (!$decrypted) {
            throw new DecryptException('The given payload is invalid.');
        }

        return $decrypted;
    }
}

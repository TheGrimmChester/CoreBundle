<?php
/**
 * Copyright (c) 2010-2017, AWHSPanel by Nicolas Méloni
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without modification,
 * are permitted provided that the following conditions are met:
 *
 *     * Redistributions of source code must retain the above copyright notice,
 *       this list of conditions and the following disclaimer.
 *     * Redistributions in binary form must reproduce the above copyright notice,
 *       this list of conditions and the following disclaimer in the documentation
 *       and/or other materials provided with the distribution.
 *     * Neither the name of AWHSPanel nor the names of its contributors
 *       may be used to endorse or promote products derived from this software without
 *       specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
 * ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
 * WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED.
 * IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT,
 * INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY
 * OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING
 * NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE,
 * EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 */

namespace AWHS\CoreBundle\API\Singletons;


use AWHS\CoreBundle\Exceptions\ServerLoginException;
use AWHS\CoreBundle\Lib\Net\SSH2;

class SshSingleton extends Singleton
{
    /**
     * @var \phpseclib\Net\SSH2|SSH2 $ssh
     */
    private $ssh;

    function getConnection()
    {
        return $this->ssh;
    }

    function openConnection($server, $aes, $server_key)
    {
        try {
            if ($this->ssh == null) {
                $this->ssh = new SSH2($server->getIp(), $server->getPort());
                if (!$this->ssh->login($aes->decrypt(base64_decode($server->getUsername())), $server_key)) {
                    $this->ssh = new SSH2($server->getIp(), $server->getPort());
                    if (!$this->ssh->login($aes->decrypt(base64_decode($server->getUsername())), $aes->decrypt(base64_decode($server->getPassword())))) {
                        $this->ssh = null;
                        throw new ServerLoginException();
                    }
                }
            }
//            if ($this->ssh == null) {
//                $this->ssh = new SSH2($server->getIp(), $server->getPort());
//                if ($this->ssh->login($aes->decrypt(base64_decode($server->getUsername())), $server_key) == false) {
//                    throw new ServerLoginException();
//                }
//            }
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    function closeConnection()
    {
        $this->ssh = null;
    }
}
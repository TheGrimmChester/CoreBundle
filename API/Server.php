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

/**
 * WARNING
 * This old class will be factorized little by little to not break everything.
 */

namespace AWHS\CoreBundle\API;

use AWHS\CoreBundle\API\Singletons\SshSingleton;
use AWHS\CoreBundle\Config\Config;
use phpseclib\Crypt\AES as AES;
use phpseclib\Crypt\RSA as RSA;


class Server
{
    private $ssh;
    private $em;
    private $aes;

    /** @var \AWHS\CoreBundle\Entity\Server */
    private $server;
    private $isLocalServer = false;
    private $server_key;
    private $packages = array(
        array('name' => 'ntp', 'daemon' => ''),
        array('name' => 'apache2', 'daemon' => 'apache2', 'service' => 'apache2'),
        array('name' => 'postfix', 'daemon' => 'postfix', 'service' => 'postfix'),
        array('name' => 'proftpd-mod-mysql', 'daemon' => 'proftpd'),
        array('name' => 'mysql-server-5.5', 'daemon' => 'mysqld', 'service' => 'mysql'),
        array('name' => 'mysql-server-5.6', 'daemon' => 'mysqld', 'service' => 'mysql'),
        array('name' => 'courier-imap', 'daemon' => 'authdaemond'),
        array('name' => 'courier-imap-ssl', 'daemon' => 'authdaemond'),
        array('name' => 'courier-pop', 'daemon' => 'authdaemond'),
        array('name' => 'courier-pop-ssl', 'daemon' => 'authdaemond'),
        array('name' => 'haproxy', 'daemon' => 'haproxy', 'service' => 'haproxy'),
        array('name' => 'bind9', 'daemon' => 'named', 'service' => 'bind9'),
        array('name' => 'clamav', 'daemon' => 'clamd', 'service' => 'clamav-daemon'),
        array('name' => 'spamassassin', 'daemon' => 'spamd', 'service' => 'spamassassin'),
        array('name' => 'amavis', 'daemon' => 'amavisd', 'service' => 'amavisd'),
        array('name' => 'php5', 'daemon' => '', 'service' => ''),
        array('name' => 'php5-fpm', 'daemon' => 'php-fpm', 'service' => 'php5-fpm'),
        array('name' => 'libapache2-mod-php5', 'daemon' => '', 'service' => ''),
        array('name' => 'libsasl2-2', 'daemon' => '', 'service' => ''),
    );

    /**
     * @var \AWHS\CoreBundle\API\Interfaces\ServerInterface $server_manager
     */
    private $server_manager;

    public function __construct(\Doctrine\ORM\EntityManager $entityManager)
    {
        $this->em = $entityManager;
    }

    public function setServer($_server)
    {
        $this->server = $_server;

        try {
            if ($this->server->getIp() == $_SERVER['SERVER_ADDR']) {
                $this->isLocalServer = true;
            }
        } catch (\Exception $e) {
        }
        $this->server_key = new RSA();
        $this->aes = new AES();
        $this->aes->setKey(Config::getEncryptionKey());
        $b64 = base64_decode($this->server->getPrivateKey());
        if (!empty($b64)) {
            $ppk = $this->aes->decrypt($b64);
        } else {
            $ppk = "";
        }
        $this->server_key->loadKey($ppk);

        //If script is used locally as root user
        if (function_exists('exec') && exec('whoami') == "root") {
            $this->server_manager = new \AWHS\CoreBundle\API\Local\Debian8Manager($this->server, $this->aes, $this->server_key);
        } else {
            SshSingleton::getInstance()->openConnection($this->server, $this->aes, $this->server_key);
            $this->ssh = SshSingleton::getInstance()->getConnection();
            $distribID = strtolower(trim($this->ssh->exec("lsb_release -is")));
            $osCodeName = strtolower(trim($this->ssh->exec("lsb_release -cs")));
            switch($distribID){
                case "debian":{
                    switch($osCodeName){
                        case "jessie": {
                            $this->server_manager = new \AWHS\CoreBundle\API\Remote\Debian8Manager($this->server, $this->aes, $this->server_key);
                            break;
                        }
                        default: {

                            break;
                        }
                    }

                    break;
                }
                case "linuxmint": {
                    switch($osCodeName){
                        case "serena": {
                            $this->server_manager = new \AWHS\CoreBundle\API\Remote\LinuxMint18Manager($this->server, $this->aes, $this->server_key);
                            break;
                        }
                        default: {

                            break;
                        }
                    }
                    break;
                }
                default:{

                    break;
                }
            }
        }
    }

    function getListPackages()
    {
        return $this->packages;
    }

    function getSecurityUpdateCount()
    {
        SshSingleton::getInstance()->openConnection($this->server, $this->aes, $this->server_key);
        $this->ssh = SshSingleton::getInstance()->getConnection();
        $output = $this->ssh->exec("apt-get -s upgrade | grep \"^Inst\" | grep -i security | wc -l");
        if (!empty($output)) {
            return trim($output);
        }
        return 0;
    }

    function getUpdateCount()
    {
        SshSingleton::getInstance()->openConnection($this->server, $this->aes, $this->server_key);
        $this->ssh = SshSingleton::getInstance()->getConnection();
        $output = $this->ssh->exec("apt-get -s upgrade | grep \"Inst\|Conf\" | wc -l");
        if (!empty($output)) {
            return trim($output);
        }
        return 0;
    }

    function getSecurityUpdate()
    {
        //If local
        SshSingleton::getInstance()->openConnection($this->server, $this->aes, $this->server_key);
        $this->ssh = SshSingleton::getInstance()->getConnection();

        $output = $this->ssh->exec("apt-get -s upgrade | grep \"Inst\|Conf\"");
        if (!empty($output)) {
            return trim($output);
        }
        return 0;
    }

    function updatePackages($security_only = false)
    {
        SshSingleton::getInstance()->openConnection($this->server, $this->aes, $this->server_key);
        $this->ssh = SshSingleton::getInstance()->getConnection();

        if ($security_only == true) {
            $this->ssh->exec("apt-get -s dist-upgrade | grep \"^Inst\" | grep -i securi | awk -F \" \" {'print $2'} | xargs apt-get -y install");
        } else {
            $this->ssh->exec("apt-get update > /dev/null;apt-get -y upgrade");
        }
        return true;
    }

    public function getServerInfos()
    {
        $serverInfos = array('uptime' => $this->getServerManager()->getUptime()
        , 'loadAverage' => $this->getServerManager()->getLoadAverage()
        , 'interfaces' => $this->getServerManager()->getNetworkInterfaces()
        , 'diskUsage' => $this->getServerManager()->getDisks()
        , 'memoryUsage' => $this->getServerManager()->getMemory()
        , 'processes' => $this->getServerManager()->getProcesses()
        , 'cpu' => $this->getServerManager()->getCpu()
        , 'os' => $this->getServerManager()->getOS()
        );

        return $serverInfos;
    }

    /**
     * @return Interfaces\ServerInterface
     */
    public function getServerManager()
    {
        return $this->server_manager;
    }
}

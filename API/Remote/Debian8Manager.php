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

namespace AWHS\CoreBundle\API\Remote;

use AWHS\CoreBundle\API\Interfaces\ServerInterface;
use AWHS\CoreBundle\API\Singletons\SftpSingleton;
use AWHS\CoreBundle\API\Singletons\SshSingleton;

class Debian8Manager extends Manager implements ServerInterface
{
    private $ssh;
    private $sftp;

    function writeFile($filepath, $text)
    {
        if (!empty($text)) {
            $this->openConnection();
            $this->sftp->put($filepath, $text);
            return true;
        } else {
            return false;
        }
    }

    function uploadFile($srcPath, $destPath)
    {
        $this->openConnection();
        return $this->sftp->put($destPath, file_get_contents($srcPath));
    }

    function generateSshKey()
    {
        $this->openConnection();
        $this->ssh->exec('if [ -f /root/.ssh/id_rsa ]; then     echo -e "y\n" | ssh-keygen -t rsa -b 2048 -N "" -f /root/.ssh/id_rsa; else     ssh-keygen -t rsa -b 2048 -N "" -f /root/.ssh/id_rsa; fi; puttygen /root/.ssh/id_rsa -C "AWHSPanel" -o /root/.ssh/id_rsa.ppk && cat /root/.ssh/id_rsa.pub > /root/.ssh/authorized_keys && rm -fr /root/.ssh/id_rsa');
        return true;
    }

    function openConnection()
    {
        SshSingleton::getInstance()->openConnection($this->server, $this->aes, $this->server_key);
        $this->ssh = SshSingleton::getInstance()->getConnection();
    }

    function openSftpConnection(){
        SftpSingleton::getInstance()->openConnection($this->server, $this->aes, $this->server_key);
        $this->sftp = SftpSingleton::getInstance()->getConnection();
    }

    function getProcesses()
    {
        $this->openConnection();
        $processes = array();
        $processes_string = $this->ssh->exec("ps aux");
        $processes_args = explode("\n", $processes_string);

        unset($processes_args[0]);
        $processes_args = array_values($processes_args);
        unset($processes_args[count($processes_args) - 1]);
        $processes_args = array_values($processes_args);

        for ($i = 0; $i < count($processes_args); $i++) {
            while (strpos($processes_args[$i], "  ") !== false) {
                $processes_args[$i] = str_replace("  ", " ", $processes_args[$i]);
            }
            if (!empty($processes_args[$i])) {
                $ps_args = explode(' ', $processes_args[$i]);

                if (count($ps_args) > 10) {
                    $temp = "";
                    for ($ii = 11; $ii < count($ps_args); $ii++) {
                        $temp = $temp . $ps_args[$ii];
                    }
                    $ps_args[10] = $ps_args[10] . $temp;
                }

                $processes[] = array('USER' => $ps_args[0], 'PID' => $ps_args[1], 'CPU' => $ps_args[2], 'MEM' => $ps_args[3], 'VSZ' => $ps_args[4], 'RSS' => $ps_args[5], 'TTY' => $ps_args[6], 'STAT' => $ps_args[7], 'START' => $ps_args[8], 'TIME' => $ps_args[9], 'COMMAND' => $ps_args[10]);
            }
        }
        return $processes;
    }

    function getNetworkInterfaces()
    {
        $this->openConnection();
        $text = $this->ssh->exec('netstat -i | awk \'{ print $1 }\' | tail -n +3');
        $interfaces_name = explode("\n", $text);
        for ($i = 0; $i < count($interfaces_name); $i++) {
            if (empty($interfaces_name[$i])) {
                unset($interfaces_name[$i]);
            }
        }
        $interfaces_name = array_values($interfaces_name);
        $interfaces = array();
        foreach ($interfaces_name as $interface) {
            $network_string = trim($this->ssh->exec('cat /proc/net/dev | grep ' . $interface . ' | awk \'{ print $2 " " $3 " " $10 " " $11 }\''));
            $network_args = explode(' ', $network_string);
            $network = array('mtu' => '1500'
            , 'tx_bytes' => isset($network_args[2]) ? $network_args[2] : ''
            , 'tx_packets' => isset($network_args[3]) ? $network_args[3] : ''
            , 'rx_bytes' => isset($network_args[0]) ? $network_args[0] : ''
            , 'rx_packets' => isset($network_args[1]) ? $network_args[1] : ''
            );
            $interface = array('name' => $interface, 'network' => $network);
            $interfaces[] = $interface;
        }
        return $interfaces;
    }

    function getLiveNetworkUsage(){
        $this->openConnection();
        $text = $this->ssh->exec('netstat -i | awk \'{ print $1 }\' | tail -n +3');
        $interfaces_name = explode("\n", $text);
        for ($i = 0; $i < count($interfaces_name); $i++) {
            if (empty($interfaces_name[$i])) {
                unset($interfaces_name[$i]);
            }
        }
        $interfaces_name = array_values($interfaces_name);
        $interfaces = array();
        foreach ($interfaces_name as $interface) {
            $network_string_live = trim($this->ssh->exec("RXPREV=-1;TXPREV=-1;for i in {1..2};do RX=`cat /sys/class/net/$interface/statistics/rx_bytes`; TX=`cat /sys/class/net/eth0/statistics/tx_bytes`; if [ \$RXPREV -ne -1 ] ; then let BWRX=\$RX-\$RXPREV; let BWTX=\$TX-\$TXPREV; echo \"\$BWRX|\$BWTX\"; fi; RXPREV=\$RX;TXPREV=\$TX; if [ \$i != 2 ] ; then sleep 1; fi;done"));
            $network_string_live_args = explode('|', $network_string_live);
            $network = array('l_rx_bytes' => isset($network_string_live_args[0]) ? (int)$network_string_live_args[0] : 0
            , 'l_tx_bytes' => isset($network_string_live_args[1]) ? (int)$network_string_live_args[1] : 0
            );
            $interface = array('name' => $interface, 'network' => $network);
            $interfaces[] = $interface;
        }
        return $interfaces;
    }

    function getLoadAverage()
    {
        if (function_exists('exec')) {
            $loadAverageText = trim(exec("cat /proc/loadavg"));
        } else {
            $this->openConnection();
            $loadAverageText = trim($this->ssh->exec("cat /proc/loadavg"));
        }
        return array((float)explode(' ', $loadAverageText)[0], (float)explode(' ', $loadAverageText)[1], (float)explode(' ', $loadAverageText)[2]);
    }

    function getUptime()
    {
        $this->openConnection();
        $commandUptime = $this->ssh->exec("awk '{print $1}' /proc/uptime");
        $seconds = floor($commandUptime);
        $dateTimeFrom = new \DateTime("@0");
        $dateTimeTo = new \DateTime("@$seconds");
        return $dateTimeFrom->diff($dateTimeTo)->format('%a days, %h hours, %i minutes and %s seconds');
    }

    function getDisks()
    {
        $this->openConnection();
        $diskUsage = array();
        $diskCommand = $this->ssh->exec('df -k | sed "1 d"');
        foreach (preg_split("/((\r?\n)|(\r\n?))/", $diskCommand) as $line) {


            $line = trim(preg_replace('/[^\S\n]+/', ' ', $line));

            if (substr($line, 0, 6) != 'rootfs') {
                $trimedLine = trim($line);
                if (count(explode(' ', $line)) == 6) {
                    try {
                        $array = explode(' ', $trimedLine);
                        $name = $array[0];
                        if ($name == '') {
                            break;
                        }

                        $used = $array[2];
                        $total = $array[1];
                        $free = $array[3];
                        $percent = str_replace("%", "", $array[4]);
                        $mount = $array[5];
                        $diskUsage[] = array(
                            'fileSystem' => $name,
                            'total' => (float)($total * 1024),
                            'used' => (float)($used * 1024),
                            'free' => (float)($free * 1024),
                            'percent' => $percent,
                            'mount' => $mount
                        );
                    } catch (\Exception $e) {
                    }
                }
            }
        }
        return $diskUsage;
    }

    function getMemory()
    {
        $this->openConnection();
        $memUsage = array();
        $memoryUsage = $this->ssh->exec("egrep 'Mem|Cache|Swap' /proc/meminfo");
        foreach (preg_split("/((\r?\n)|(\r\n?))/", $memoryUsage) as $line) {
            try {
                $key = explode(':', trim($line))[0];
                $value = preg_replace('/\D/', '', explode(':', trim($line))[1]) * 1024;
                $memUsage[$key] = (int)$value;
            } catch (\Exception $e) {

            }

        }
        $memUsage['Shared'] = 0;
        $memUsage['Buffer'] = 0;
        return $memUsage;
    }

    function getCpu()
    {
        $this->openConnection();
        $model = trim(explode(':', $this->ssh->exec("egrep 'model name' /proc/cpuinfo"))[1]);
        return array('model' => $model);
    }

    /**
     * @inheritdoc
     */
    function getOS()
    {
        $this->openConnection();
        $osVersion = trim($this->ssh->exec("cat /etc/lsb-release | grep ^DISTRIB_RELEASE | cut -d'=' -f2"));
        $osName = $this->ssh->exec("cat /etc/lsb-release | grep ^DISTRIB_ID | cut -d'=' -f2");
        return array('name' => $osName, 'version' => $osVersion);
    }


    function getSshKeys()
    {
        $this->openSftpConnection();
        return array('ssh' => $this->sftp->get('/root/.ssh/authorized_keys'), 'putty' => $this->sftp->get('/root/.ssh/id_rsa.ppk'));
    }

    function getInstalledPackages()
    {
        $this->openConnection();
        $packages_string = $this->ssh->exec("dpkg-query -l|tail -n +6|awk '{ s = $2\"|\"$3\"|\"$4\"|\"; for (i = 5; i <= NF; i++) s = s \$i \" \"; print s }'");
        $packages = array();
        foreach (preg_split("/((\r?\n)|(\r\n?))/", $packages_string) as $line) {
            if (!empty($line)) {
                $infos = explode('|', trim($line));
                $packages[] = array("name" => $infos[0],
                    "version" => $infos[1],
                    "arch" => $infos[2],
                    "desc" => $infos[3],
                );
            }
        }
        return $packages;
    }

    function getFQDN()
    {
        $this->openConnection();
        return trim($this->ssh->exec("hostname -f"));
    }

    function getHostname()
    {
        if ($this->server->getShortname() == null) {
            $this->openConnection();
            return trim($this->ssh->exec("hostname -s"));
        } else {
            return $this->server->getShortname();
        }
    }

    function getGateway()
    {
        $this->openConnection();
        return trim($this->ssh->exec("/sbin/ip route | awk '/default/ { print $3 }'"));
    }

    function isPackageInstalled($package_name)
    {
        $this->openConnection();
        $output = $this->ssh->exec("dpkg-query -W -f='\${Status}|\${Version}\n' {$package_name}");
        if (!empty($output)) {
            if (strpos($output, 'install ok installed') !== false) {
                return true;
            }
            return false;
        }
        return false;
    }

    function getPackageVersion($package_name)
    {
        $this->openConnection();
        $output = $this->ssh->exec("dpkg-query -W -f='\${Status}|\${Version}\n' {$package_name}");
        if (!empty($output)) {
            if (strpos($output, 'install ok installed') !== false) {
                $parts = explode('|', $output);
                $version = trim($parts[1]);
                return $version;
            }
            return false;
        }
        return false;
    }

    function isPackageRunning($package_name)
    {
        $this->openConnection();
        $output = $this->ssh->exec("if [ `ps ax | grep -v grep | grep -c {$package_name}` -le 0 ]; then    echo 0; else    echo 1; fi");
        if (!empty($output)) {
            return trim($output);
        }
        return false;
    }
}
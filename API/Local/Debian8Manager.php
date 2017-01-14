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

namespace AWHS\CoreBundle\API\Local;

use AWHS\CoreBundle\API\Interfaces\ServerInterface;

class Debian8Manager extends Manager implements ServerInterface
{
    function writeFile($filepath, $text)
    {
        // TODO: Implement writeFile() method.
    }

    function uploadFile($srcPath, $destPath)
    {
        // TODO: Implement uploadFile() method.
    }

    function generateSshKey()
    {
        exec('if [ -f /root/.ssh/id_rsa ]; then     echo -e "y\n" | ssh-keygen -t rsa -b 2048 -N "" -f /root/.ssh/id_rsa; else     ssh-keygen -t rsa -b 2048 -N "" -f /root/.ssh/id_rsa; fi; puttygen /root/.ssh/id_rsa -C "AWHSPanel" -o /root/.ssh/id_rsa.ppk && cat /root/.ssh/id_rsa.pub > /root/.ssh/authorized_keys && rm -fr /root/.ssh/id_rsa');
        return true;
    }

    function getProcesses()
    {
        $processes = array();
        $processes_string = exec("ps aux");
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
        $text = exec('netstat -i | awk \'{ print $1 }\' | tail -n +3');
        $interfaces_name = explode("\n", $text);
        for ($i = 0; $i < count($interfaces_name); $i++) {
            if (empty($interfaces_name[$i])) {
                unset($interfaces_name[$i]);
            }
        }
        $interfaces_name = array_values($interfaces_name);
        $interfaces = array();
        foreach ($interfaces_name as $interface) {
            $network_string = trim(exec('cat /proc/net/dev | grep ' . $interface . ' | awk \'{ print $2 " " $3 " " $10 " " $11 }\''));
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

    function getLoadAverage()
    {
        $loadAverageText = trim(exec("cat /proc/loadavg"));
        return array(explode(' ', $loadAverageText)[0], explode(' ', $loadAverageText)[1], explode(' ', $loadAverageText)[2]);
    }

    function getUptime()
    {
        $commandUptime = exec("awk '{print $1}' /proc/uptime");
        $seconds = floor($commandUptime);
        $dateTimeFrom = new \DateTime("@0");
        $dateTimeTo = new \DateTime("@$seconds");
        return $dateTimeFrom->diff($dateTimeTo)->format('%a days, %h hours, %i minutes and %s seconds');
    }

    function getDisks()
    {
        $diskUsage = array();
        $diskCommand = exec('df -k | sed "1 d"');
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
        $memUsage = array();
        $memoryUsage = exec("egrep 'Mem|Cache|Swap' /proc/meminfo");
        foreach (preg_split("/((\r?\n)|(\r\n?))/", $memoryUsage) as $line) {
            try {
                $key = explode(':', trim($line))[0];
                $value = preg_replace('/\D/', '', explode(':', trim($line))[1]) * 1024;
                $memUsage[$key] = $value;
            } catch (\Exception $e) {

            }

        }
        $memUsage['Shared'] = 0;
        $memUsage['Buffer'] = 0;
        return $memUsage;
    }

    function getCpu()
    {
        $model = trim(explode(':', exec("egrep 'model name' /proc/cpuinfo"))[1]);
        return array('model' => $model);
    }

    function getOS()
    {
        $osVersion = trim(exec("cat /etc/debian_version"));
        $osName = exec("egrep 'PRETTY_NAME' /etc/*release");
        $osName = explode('=', $osName)[1];
        $osName = str_replace('"', '', $osName);
        $osName = str_replace("\n", '', $osName);
        return array('name' => $osName, 'version' => $osVersion);
    }

    function getSshKeys()
    {
        return array('ssh' => file_get_contents('/root/.ssh/authorized_keys'), 'putty' => file_get_contents('/root/.ssh/id_rsa.ppk'));
    }

    function getInstalledPackages()
    {
        // TODO: Implement getInstalledPackages() method.
    }

    function getFQDN()
    {
        // TODO: Implement getFQDN() method.
    }

    function getHostname()
    {
        // TODO: Implement getHostname() method.
    }

    function getGateway()
    {
        // TODO: Implement getGateway() method.
    }

    function isPackageInstalled($package_name)
    {
        // TODO: Implement isPackageInstalled() method.
    }

    function getPackageVersion($package_name)
    {
        // TODO: Implement getPackageVersion() method.
    }

    function isPackageRunning($package_name)
    {
        // TODO: Implement isPackageRunning() method.
    }
}
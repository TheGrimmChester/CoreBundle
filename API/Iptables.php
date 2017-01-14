<?php

namespace AWHS\CoreBundle\API;

use AWHS\CoreBundle\API\Singletons\SshSingleton;
use AWHS\CoreBundle\Config\Config;
use phpseclib\Crypt\AES as AES;
use phpseclib\Crypt\RSA as RSA;
use phpseclib\Net\SSH2 as SSH2;

/**
 * @author Tomáš Kolinger <tomas@kolinger.name>
 * URL: https://github.com/kolinger/iptables-web-gui
 */
class Iptables
{
    /**
     * @var string
     */
    public static $sessionCacheKey = 'iptables-cache';
    private $ssh;
    private $aes;
    private $em;
    private $parameters;
    private $server;
    private $isLocalServer = false;
    private $server_key;
    /**
     * @var string
     */
    private $executable = 'iptables';
    /**
     * @var array
     */
    private $tables;
    /**
     * @var boolean
     */
    private $onFly = TRUE;

    public function __construct(\Doctrine\ORM\EntityManager $entityManager)
    {
        $this->em = $entityManager;
    }

    function buildQueryFromRule(\stdClass $rule, $table, $chain)
    {
        $rule = clone $rule;
        $parameters = array();
        $parameters['protocol'] = $rule->protocol;
        $parameters['in'] = $rule->in;
        $parameters['out'] = $rule->out;
        $parameters['source'] = $rule->source;
        $parameters['destination'] = $rule->destination;
        $parameters['target'] = $rule->target;
        if (preg_match('~--(d|s)port ([0-9:]+)~i', $rule->additional, $matches)) {
            $parameters[$matches[1] . 'port'] = $matches[2];
            $rule->additional = str_replace($matches[0], '', $rule->additional);
        }
        $parameters['additional'] = trim($rule->additional);
        $parameters['table'] = $table;
        $parameters['chain'] = $chain;
        return http_build_query($parameters);
    }

    function buildRuleFromQuery(array $array)
    {
        $rule = new \stdClass();
        $rule->in = isset($array['in']) ? $array['in'] : '';
        $rule->out = isset($array['out']) ? $array['out'] : '';
        $rule->source = isset($array['source']) ? $array['source'] : '';
        $rule->destination = isset($array['destination']) ? $array['destination'] : '';
        $rule->protocol = isset($array['protocol']) ? $array['protocol'] : '';
        $rule->additional = isset($array['additional']) ? trim($array['additional']) : '';
        if (isset($array['dport']) && $array['dport']) {
            $rule->additional .= ' --dport ' . $array['dport'];
        }
        if (isset($array['sport']) && $array['sport']) {
            $rule->additional .= ' --sport ' . $array['sport'];
        }
        $rule->target = isset($array['target']) ? $array['target'] : '';
        return $rule;
    }

    public function setParameters($_parameters)
    {
        $this->parameters = $_parameters;
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
    }

    /**
     * @return string
     */
    public function getExecutable()
    {
        return $this->executable;
    }

    /**
     * @param string $ip4executable
     */
    public function setExecutable($ip4executable)
    {
        $this->executable = $ip4executable;
    }

    /**
     * @return array
     */
    public function getTables()
    {
        /*if ($this->tables === NULL) {
            if (!$this->onFly) {
                if (session_id() === '') {
                    session_start();
                }
                if (isset($_SESSION[static::$sessionCacheKey]) && is_array($_SESSION[static::$sessionCacheKey])) {
                    $this->tables = $_SESSION[static::$sessionCacheKey];
                } else {
                    $this->parseTables();
                    $_SESSION[static::$sessionCacheKey] = $this->tables;
                }
            } else {
                $this->parseTables();
            }
        }*/
        $this->parseTables();
        $_SESSION[static::$sessionCacheKey] = $this->tables;
        return $this->tables;
    }

    /**
     * @param array $tables
     */
    public function setTables($tables)
    {
        $this->tables = $tables;
    }

    private function parseTables()
    {
        $this->tables = array();
        // filter
        $this->parseChain('INPUT');
        $this->parseChain('FORWARD');
        $this->parseChain('OUTPUT');
        // nat
        $this->parseChain('PREROUTING', 'nat');
        $this->parseChain('OUTPUT', 'nat');
        $this->parseChain('POSTROUTING', 'nat');
    }

    /**
     * @param string $chain
     * @param string $table
     * @throws \Exception
     */
    private function parseChain($chain, $table = 'filter')
    {
        if ($table) {
            $parameters = '-L ' . $chain . ' -t ' . $table . ' -n -v';
        } else {
            $parameters = '-L ' . $chain . ' -n -v';
        }
        $output = $this->execute($parameters);
        if (!preg_match('~Chain [a-z]+ \(policy ([a-z]+)~i', $output, $matches)) {
            throw new \Exception('Executing of iptables with parameters ' . $parameters . ' failed, got wrong
				output: ' . $output);
        }
        $this->tables[$table][$chain]['policy'] = $matches[1];
        $this->tables[$table][$chain]['rules'] = array();
        $lines = explode("\n", $output);
        // columns positions, must be in right order!
        $columns = array(
            'target' => strpos($lines[1], 'target'),
            'protocol' => strpos($lines[1], 'prot'),
            'opt' => strpos($lines[1], 'opt'),
            'in' => strpos($lines[1], 'in'),
            'out' => strpos($lines[1], 'out'),
            'source' => strpos($lines[1], 'source'),
            'destination' => strpos($lines[1], 'destination')
        );
        $columns = array_reverse($columns); // first column must be last
        array_shift($lines); // strip headline
        array_shift($lines); // strip table header
        array_pop($lines); // strip last blank line
        // parse tables
        foreach ($lines as $line) {
            $rule = new \stdClass();
            $previous = strlen($line);
            foreach ($columns as $name => $position) {
                $rule->$name = trim(substr($line, $position, $previous - $position));
                $previous = $position;
                if ($name === 'destination') { // last column - need check for additional parameters
                    $delimiter = strpos($rule->$name, '  ');
                    if ($delimiter !== FALSE) {
                        $rule->additional = trim(substr($rule->$name, $delimiter, strlen($rule->$name)));
                        $rule->$name = trim(substr($rule->$name, 0, $delimiter));
                    }
                }
            }
            $this->tables[$table][$chain]['rules'][] = $this->formatRule($rule);
        }
    }

    /**
     * @param string $parameters
     * @return string
     */
    private function execute($parameters)
    {
        $aes = new AES();
        $aes->setKey(Config::getEncryptionKey());
        if ($this->ssh == null) {
            $this->ssh = new SSH2($this->server->getIp(), $this->server->getPort());
            if (!$this->ssh->login($aes->decrypt(base64_decode($this->server->getUsername())), $this->server_key)) {
                $this->ssh = new SSH2($this->server->getIp(), $this->server->getPort());
                if (!$this->ssh->login($aes->decrypt(base64_decode($this->server->getUsername())), $aes->decrypt(base64_decode($this->server->getPassword())))) {
                    $this->ssh = null;
                    return false;
                }
            }
        }
        return $this->ssh->exec($this->executable . ' ' . $parameters);
    }

    /**
     * @param \stdClass $rule
     * @return \stdClass
     */
    private function formatRule(\stdClass $rule)
    {
        if ($rule->protocol === 'all') {
            $rule->protocol = '';
        }
        if ($rule->opt === '--') {
            $rule->opt = '';
        }
        if ($rule->in === '*') {
            $rule->in = '';
        }
        if ($rule->out === '*') {
            $rule->out = '';
        }
        if ($rule->source === '0.0.0.0/0') {
            $rule->source = '';
        }
        if ($rule->destination === '0.0.0.0/0') {
            $rule->destination = '';
        }
        if (!isset($rule->additional)) {
            $rule->additional = '';
        } else {
            // format ports
            $rule->additional = preg_replace('~(?:tcp|udp) (?:(d|s)pts?:([0-9:]+))~i', '\\1port \\2', $rule->additional);
            // add dashes
            $rule->additional = preg_replace('~ ([a-z]{1,1})(?: |:)([a-z0-9]+)~i', ' -\\1 \\2', ' ' . $rule->additional);
            $rule->additional = trim(preg_replace('~ ([a-z]{2,})(?: |:)([a-z0-9]+)~i', ' --\\1 \\2', $rule->additional));
            // fix state
            $rule->additional = str_replace('--state', '-m state --state', $rule->additional);
        }
        return $rule;
    }

    /**
     * @return boolean
     */
    public function getOnFly()
    {
        return $this->onFly;
    }

    /**
     * @param boolean $onFly
     */
    public function setOnFly($onFly)
    {
        $this->onFly = $onFly;
    }

    /**
     * @return string
     */
    public function export()
    {
        SshSingleton::getInstance()->openConnection($this->server, $this->aes, $this->server_key);
        $this->ssh = SshSingleton::getInstance()->getConnection();
        return $this->ssh->exec($this->executable . '-save');
    }

    /**
     * @todo what about command injection?
     * @param string $configuration
     * @return string
     */
    public function import($configuration)
    {
        SshSingleton::getInstance()->openConnection($this->server, $this->aes, $this->server_key);
        $this->ssh = SshSingleton::getInstance()->getConnection();
        return $this->ssh->exec($this->executable . '-restore <<< "' . $configuration . '"');
    }

    /**
     * @param \stdClass $rule
     * @param string $table
     * @param string $chain
     * @return string
     */
    public function add(\stdClass $rule, $table, $chain)
    {
        $parameters = '-t ' . $table . ' -A ' . $chain . ' ';
        $parameters .= $this->buildParameters($rule);
        return $this->execute($parameters);
    }

    /**
     * @param \stdClass $rule
     * @return string
     */
    private function buildParameters(\stdClass $rule)
    {
        $parameters = array();
        if ($rule->protocol) {
            $parameters[] = '-p ' . $rule->protocol;
        }
        if ($rule->in) {
            $parameters[] = '-i ' . $rule->in;
        }
        if ($rule->out) {
            $parameters[] = '-o ' . $rule->out;
        }
        if ($rule->source) {
            $parameters[] = '-s ' . $rule->source;
        }
        if ($rule->destination) {
            $parameters[] = '-d ' . $rule->destination;
        }
        if ($rule->additional) {
            $parameters[] = trim($rule->additional);
        }
        if ($rule->target) {
            $parameters[] = '-j ' . $rule->target;
        }
        return implode(' ', $parameters);
    }

    /**
     * @param \stdClass $rule
     * @param string $table
     * @param string $chain
     * @return string
     */
    public function remove(\stdClass $rule, $table, $chain)
    {
        $parameters = '-t ' . $table . ' -D ' . $chain . ' ';
        $parameters .= $this->buildParameters($rule);
        return $this->execute($parameters);
    }
}

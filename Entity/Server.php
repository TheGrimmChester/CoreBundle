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

namespace AWHS\CoreBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Server
 *
 * @ORM\Table(name="awhs_servers")
 * @ORM\Entity(repositoryClass="AWHS\CoreBundle\Entity\ServerRepository")
 */
class Server
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="AWHS\CoreBundle\Entity\Country")
     * @ORM\JoinColumn(nullable=false)
     */
    private $country;

    /**
     * @var string
     *
     * @ORM\Column(name="ip", type="string", length=255, nullable=true)
     */
    private $ip;

    /**
     * @var string
     *
     * @ORM\Column(name="reverse", type="string", length=255, nullable=true)
     */
    private $reverse;

    /**
     * @var string
     *
     * @ORM\Column(name="shortname", type="string", length=255, nullable=true)
     */
    private $shortname;

    /**
     * @var string
     *
     * @ORM\Column(name="subnet", type="string", length=255, nullable=true)
     */
    private $subnet;

    /**
     * @var string
     *
     * @ORM\Column(name="gateway", type="string", length=255, nullable=true)
     */
    private $gateway;

    /**
     * @var string
     *
     * @ORM\Column(name="dns", type="string", length=255, nullable=true)
     */
    private $dns;

    /**
     * @var string
     *
     * @ORM\Column(name="dns2", type="string", length=255, nullable=true)
     */
    private $dns2;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=255, nullable=true)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255, nullable=true)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="private_key", type="string", length=999999, nullable=true)
     */
    private $private_key;

    /**
     * @var integer
     *
     * @ORM\Column(name="port", type="integer", nullable=true)
     */
    private $port = 22;

    /**
     * @var string
     *
     * @ORM\Column(name="database_host", type="string", length=255, nullable=true)
     */
    private $database_host;

    /**
     * @var string
     *
     * @ORM\Column(name="database_user", type="string", length=255, nullable=true)
     */
    private $database_user;

    /**
     * @var string
     *
     * @ORM\Column(name="database_password", type="string", length=255, nullable=true)
     */
    private $database_password;

    /**
     * @var integer
     *
     * @ORM\Column(name="database_port", type="integer")
     */
    private $database_port = 3306;


    /**
     * @var boolean
     *
     * @ORM\Column(name="active", type="boolean")
     */
    private $active;

    /**
     * @ORM\OneToMany(targetEntity="AWHS\CoreBundle\Entity\ServerProductCategory", mappedBy="server")
     **/
    private $product_categories;

    /**
     * @ORM\OneToMany(targetEntity="AWHS\CoreBundle\Entity\SubscriptionProduct", mappedBy="server")
     **/
    private $subscription_products;


    public function __construct()
    {
        $this->product_categories = new ArrayCollection();
        $this->subscription_products = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Server
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get country
     *
     * @return \AWHS\CoreBundle\Entity\Country
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set country
     *
     * @param \AWHS\CoreBundle\Entity\Country $country
     * @return $this
     */
    public function setCountry(\AWHS\CoreBundle\Entity\Country $country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get ip
     *
     * @return string
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * Set ip
     *
     * @param string $ip
     * @return Server
     */
    public function setIp($ip)
    {
        $this->ip = $ip;

        return $this;
    }

    /**
     * Get reverse
     *
     * @return string
     */
    public function getReverse()
    {
        return $this->reverse;
    }

    /**
     * Set reverse
     *
     * @param string $reverse
     * @return Server
     */
    public function setReverse($reverse)
    {
        $this->reverse = $reverse;

        return $this;
    }

    /**
     * Get subnet
     *
     * @return string
     */
    public function getSubnet()
    {
        return $this->subnet;
    }

    /**
     * Set subnet
     *
     * @param string $subnet
     * @return Server
     */
    public function setSubnet($subnet)
    {
        $this->subnet = $subnet;

        return $this;
    }

    /**
     * Get gateway
     *
     * @return string
     */
    public function getGateway()
    {
        return $this->gateway;
    }

    /**
     * Set gateway
     *
     * @param string $gateway
     * @return Server
     */
    public function setGateway($gateway)
    {
        $this->gateway = $gateway;

        return $this;
    }

    /**
     * Get dns
     *
     * @return string
     */
    public function getDns()
    {
        return $this->dns;
    }

    /**
     * Set dns
     *
     * @param string $dns
     * @return Server
     */
    public function setDns($dns)
    {
        $this->dns = $dns;

        return $this;
    }

    /**
     * Get dns2
     *
     * @return string
     */
    public function getDns2()
    {
        return $this->dns2;
    }

    /**
     * Set dns2
     *
     * @param string $dns2
     * @return Server
     */
    public function setDns2($dns2)
    {
        $this->dns2 = $dns2;

        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set username
     *
     * @param string $username
     * @return Server
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return Server
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get private_key
     *
     * @return string
     */
    public function getPrivateKey()
    {
        return $this->private_key;
    }

    /**
     * Set private_key
     *
     * @param string $private_key
     * @return Server
     */
    public function setPrivateKey($private_key)
    {
        $this->private_key = $private_key;

        return $this;
    }

    /**
     * Get port
     *
     * @return integer
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * Set port
     *
     * @param integer $port
     * @return Server
     */
    public function setPort($port)
    {
        $this->port = $port;

        return $this;
    }

    /**
     * Get database_host
     *
     * @return string
     */
    public function getDatabaseHost()
    {
        return $this->database_host;
    }

    /**
     * Set database_host
     *
     * @param string $database_host
     * @return Server
     */
    public function setDatabaseHost($database_host)
    {
        $this->database_host = $database_host;

        return $this;
    }

    /**
     * Get database_user
     *
     * @return string
     */
    public function getDatabaseUser()
    {
        return $this->database_user;
    }

    /**
     * Set database_user
     *
     * @param string $database_user
     * @return Server
     */
    public function setDatabaseUser($database_user)
    {
        $this->database_user = $database_user;

        return $this;
    }

    /**
     * Get database_password
     *
     * @return string
     */
    public function getDatabasePassword()
    {
        return $this->database_password;
    }

    /**
     * Set database_password
     *
     * @param string $database_password
     * @return Server
     */
    public function setDatabasePassword($database_password)
    {
        $this->database_password = $database_password;

        return $this;
    }

    /**
     * Get database_port
     *
     * @return integer
     */
    public function getDatabasePort()
    {
        return $this->database_port;
    }

    /**
     * Set database_port
     *
     * @param integer $database_port
     * @return Server
     */
    public function setDatabasePort($database_port)
    {
        $this->database_port = $database_port;

        return $this;
    }

    /**
     * Get active
     *
     * @return boolean
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Set active
     *
     * @param boolean $active
     * @return Server
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get shortname
     *
     * @return string
     */
    public function getShortname()
    {
        return $this->shortname;
    }

    /**
     * Set shortname
     *
     * @param string $shortname
     * @return Server
     */
    public function setShortname($shortname)
    {
        $this->shortname = $shortname;

        return $this;
    }

    /**
     * Set product_categories
     *
     * @param arrayCollection $product_categories
     * @return Subscription
     */
    public function addProductCategory($product_categories)
    {
        $this->product_categories[] = $product_categories;
    }

    /**
     * Get product_categories
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProductCategories()
    {
        return $this->product_categories;
    }

    /**
     * Get subscription_products
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSubscriptionProducts()
    {
        return $this->subscription_products;
    }

    public function __toString()
    {
        return $this->name . " ({$this->ip})";
    }
}

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

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * SubscriptionProduct
 *
 * @ORM\Table(name="awhs_subscription_products")
 * @ORM\Entity(repositoryClass="AWHS\CoreBundle\Entity\SubscriptionProductRepository")
 */
class SubscriptionProduct
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
     * @ORM\ManyToOne(targetEntity="AWHS\CoreBundle\Entity\Subscription", inversedBy="subscription_products")
     * @ORM\JoinColumn(nullable=false)
     */
    private $subscription;
    /**
     * @ORM\ManyToOne(targetEntity="AWHS\CoreBundle\Entity\Product", inversedBy="subscription_product")
     * @ORM\JoinColumn(nullable=false)
     */
    private $product;
    /**
     * @ORM\ManyToOne(targetEntity="AWHS\CoreBundle\Entity\Server", inversedBy="subscription_products")
     * @ORM\JoinColumn(nullable=true)
     */
    private $server;
    /**
     * @var integer
     *
     * @ORM\Column(name="mapping_id", type="integer", nullable=true)
     */
    private $mapping_id;
    /**
     * @var string
     *
     * @ORM\Column(name="parameters", type="string", length=1000)
     */
    private $parameters;

    public function __construct()
    {
        //Set zone to inactive as default | cronjob
        $this->active = 0;
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
     * Get mapping_id
     *
     * @return integer
     */
    public function getMappingId()
    {
        return $this->mapping_id;
    }

    /**
     * Set mapping_id
     *
     * @param integer $mapping_id
     * @return SubscriptionProduct
     */
    public function setMappingId($mapping_id)
    {
        $this->mapping_id = $mapping_id;

        return $this;
    }

    /**
     * Get parameters
     *
     * @return string
     */
    public function getParameters()
    {
        return json_decode($this->parameters, true);
    }

    /**
     * Set parameters
     *
     * @param string $parameters
     * @return SubscriptionProduct
     */
    public function setParameters($parameters)
    {
        $this->parameters = $parameters;

        return $this;
    }

    /**
     * Get settings
     *
     * @return string
     */
    public function getSettings()
    {
        return $this->parameters;
    }

    /**
     * Get settings
     *
     * @return string
     */
    public function getSettingsArray()
    {
        return json_decode($this->parameters, true);
    }

    /**
     * Set settings
     *
     * @param string $parameters
     * @return SubscriptionProduct
     */
    public function setSettings($parameters)
    {
        $this->parameters = $parameters;

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
     * Get subscription
     *
     * @return \AWHS\CoreBundle\Entity\Subscription
     */
    public function getSubscription()
    {
        return $this->subscription;
    }

    /**
     * Set subscription
     *
     * @param \AWHS\CoreBundle\Entity\Subscription $subscription
     * @return $this
     */
    public function setSubscription(\AWHS\CoreBundle\Entity\Subscription $subscription)
    {
        $this->subscription = $subscription;

        return $this;
    }

    /**
     * Get product
     *
     * @return \AWHS\CoreBundle\Entity\Product
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * Set product
     *
     * @param \AWHS\CoreBundle\Entity\Product $product
     * @return $this
     */
    public function setProduct(\AWHS\CoreBundle\Entity\Product $product)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get server
     *
     * @return \AWHS\CoreBundle\Entity\Server
     */
    public function getServer()
    {
        return $this->server;
    }

    /**
     * Set server
     *
     * @param \AWHS\CoreBundle\Entity\Server $server
     * @return $this
     */
    public function setServer($server)
    {
        $this->server = $server;

        return $this;
    }

    public function getIsCreated()
    {
        if ($this->mapping_id == null) {
            return false;
        }
        return true;
    }
}

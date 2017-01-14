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
 * ProductPackage
 *
 * @ORM\Table(name="awhs_subscription_package_products", uniqueConstraints={@ORM\UniqueConstraint(name="subscription_package_product", columns={"subscription_package_id", "product_id"})})
 * @ORM\Entity(repositoryClass="AWHS\CoreBundle\Entity\ProductPackageRepository")
 */
class ProductPackage
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
     * @ORM\ManyToOne(targetEntity="AWHS\CoreBundle\Entity\SubscriptionPackage", inversedBy="products_package")
     * @ORM\JoinColumn(nullable=false)
     */
    private $subscription_package;
    /**
     * @ORM\ManyToOne(targetEntity="AWHS\CoreBundle\Entity\Product", inversedBy="subscription_product")
     * @ORM\JoinColumn(nullable=false)
     */
    private $product;
    /**
     * @var integer
     *
     * @ORM\Column(name="quantity", type="integer", options={"default" = 1})
     */
    private $quantity;

    private $api;

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
     * Get quantity
     *
     * @return integer
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set quantity
     *
     * @param integer $quantity
     * @return ProductPackage
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

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
     * Get subscription_package
     *
     * @return \AWHS\CoreBundle\Entity\SubscriptionPackage
     */
    public function getSubscriptionPackage()
    {
        return $this->subscription_package;
    }

    /**
     * Set subscription_package
     *
     * @param \AWHS\CoreBundle\Entity\SubscriptionPackage $subscription_package
     * @return ProductPackage
     */
    public function setSubscriptionPackage(\AWHS\CoreBundle\Entity\SubscriptionPackage $subscription_package)
    {
        $this->subscription_package = $subscription_package;

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
     * @return ProductPackage
     */
    public function setProduct(\AWHS\CoreBundle\Entity\Product $product)
    {
        $this->product = $product;

        return $this;
    }

    public function getAvailability()
    {
        return $this->product->getAvalability();
    }

    public function getApi()
    {
        return $this->api;
    }

    public function setApi($api)
    {
        $this->api = $api;
    }

}

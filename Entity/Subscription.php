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

use Doctrine\Common\Collections\ArrayCollection as ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Subscription
 *
 * @ORM\Table(name="awhs_subscriptions")
 * @ORM\Entity(repositoryClass="AWHS\CoreBundle\Entity\SubscriptionRepository")
 */
class Subscription
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
     * @ORM\ManyToOne(targetEntity="AWHS\UserBundle\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    //private $name;
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="start_date", type="datetime", nullable=true)
     */
    private $start_date;
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="end_date", type="datetime", nullable=true)
     */
    private $end_date;
    /**
     * @var boolean
     *
     * @ORM\Column(name="active", type="boolean", options={"default" = 0})
     */
    private $active;
    /**
     * @ORM\ManyToMany(targetEntity="AWHS\CoreBundle\Entity\Invoice", inversedBy="subscriptions")
     * @ORM\JoinTable(name="awhs_subscription_invoices_pivot")
     */
    private $invoices;

    /**
     * @ORM\OneToMany(targetEntity="AWHS\WebsiteBundle\Entity\Service", mappedBy="subscription")
     * @ORM\OrderBy({"id" = "DESC"})
     */
    //private $services;
    /**
     * @ORM\ManyToOne(targetEntity="AWHS\CoreBundle\Entity\SubscriptionPackage")
     * @ORM\JoinColumn(nullable=false)
     */
    private $subscription_package;
    /**
     * @ORM\OneToMany(targetEntity="AWHS\CoreBundle\Entity\SubscriptionProduct", mappedBy="subscription")
     * @ORM\OrderBy({"id" = "DESC"})
     */
    private $subscription_products;

    public function __construct()
    {
        $this->active = 0;
        $this->products = new ArrayCollection();
    }


    //private $products;

    //Plusieurs paiements;;;

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
     * Get subscription_product
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSubscriptionProducts()
    {
        return $this->subscription_products;
    }

    /**
     * Get user
     *
     * @return \AWHS\UserBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set user
     *
     * @param \AWHS\UserBundle\Entity\User $user
     * @return $this
     */
    public function setUser(\AWHS\UserBundle\Entity\User $user)
    {
        $this->user = $user;

        return $this;
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
     * @return $this
     */
    public function setSubscriptionPackage(\AWHS\CoreBundle\Entity\SubscriptionPackage $subscription_package)
    {
        $this->subscription_package = $subscription_package;

        return $this;
    }

    /**
     * Get start_date
     *
     * @return \DateTime
     */
    public function getStartDate()
    {
        return $this->start_date;
    }

    /**
     * Set start_date
     *
     * @param \DateTime $start_date
     * @return Subscription
     */
    public function setStartDate($start_date)
    {
        $this->start_date = $start_date;

        return $this;
    }

    /**
     * Get end_date
     *
     * @return \DateTime
     */
    public function getEndDate()
    {
        return $this->end_date;
    }

    /**
     * Set end_date
     *
     * @param \DateTime $end_date
     * @return Subscription
     */
    public function setEndDate($end_date)
    {
        $this->end_date = $end_date;

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
     * @return Subscription
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Add product
     *
     * @param $product
     * @return Subscription
     */
    public function addProduct($product)
    {
        $this->products[] = $product;
        return $this;
    }

    /**
     * Get products
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * Add invoice
     *
     * @param $invoice
     * @return Subscription
     */
    public function addInvoice($invoice)
    {
        $this->invoices[] = $invoice;
        return $this;
    }

    /**
     * Get invoices
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getInvoices()
    {
        return $this->invoices;
    }
}

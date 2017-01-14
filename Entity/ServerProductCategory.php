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
 * ServerProductCategory
 *
 * @ORM\Table(name="awhs_server_product_categories", uniqueConstraints={@ORM\UniqueConstraint(name="server_category_unique", columns={"server_id", "product_category_id"})})
 * @ORM\Entity(repositoryClass="AWHS\CoreBundle\Entity\ServerProductCategoryRepository")
 */
class ServerProductCategory
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
     * @ORM\ManyToOne(targetEntity="AWHS\CoreBundle\Entity\Server", inversedBy="product_categories")
     * @ORM\JoinColumn(nullable=false)
     */
    private $server;

    /**
     * @ORM\ManyToOne(targetEntity="AWHS\CoreBundle\Entity\ProductCategory", inversedBy="server_product_categories")
     * @ORM\JoinColumn(nullable=false)
     */
    private $product_category;

    /**
     * @var integer
     *
     * @ORM\Column(name="number", type="integer", nullable=true)
     */
    private $number;

    /**
     * @var integer
     *
     * @ORM\Column(name="disk_space", type="integer", nullable=true)
     */
    private $disk_space;

    /**
     * @var integer
     *
     * @ORM\Column(name="ram", type="integer", nullable=true)
     */
    private $ram;

    /**
     * @var integer
     *
     * @ORM\Column(name="current_count", type="integer", nullable=false, options={"default" = 0})
     *
     * @Assert\Range(
     *      min = "0"
     * )
     */
    private $current_count;

    /**
     * @var boolean
     *
     * @ORM\Column(name="active", type="boolean", nullable=false)
     */
    private $active;

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

    /**
     * Get product_category
     *
     * @return \AWHS\CoreBundle\Entity\ProductCategory
     */
    public function getProductCategory()
    {
        return $this->product_category;
    }

    /**
     * Set product_category
     *
     * @param \AWHS\CoreBundle\Entity\ProductCategory $product_category
     * @return $this
     */
    public function setProductCategory($product_category)
    {
        $this->product_category = $product_category;

        return $this;
    }

    /**
     * Get uid
     *
     * @return integer
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Set number
     *
     * @param integer $number
     * @return ServerProductCategory
     */
    public function setNumber($number)
    {
        $this->number = $number;

        return $this;
    }

    /**
     * Get ram
     *
     * @return integer
     */
    public function getRam()
    {
        return $this->ram;
    }

    /**
     * Set ram
     *
     * @param integer $ram
     * @return ServerProductCategory
     */
    public function setRam($ram)
    {
        $this->ram = $ram;

        return $this;
    }

    /**
     * Get disk_space
     *
     * @return integer
     */
    public function getDiskSpace()
    {
        return $this->disk_space;
    }

    /**
     * Set disk_space
     *
     * @param integer $disk_space
     * @return ServerProductCategory
     */
    public function setDiskSpace($disk_space)
    {
        $this->disk_space = $disk_space;

        return $this;
    }

    /**
     * Get current_count
     *
     * @return integer
     */
    public function getCurrentCount()
    {
        return $this->current_count;
    }

    /**
     * Set current_count
     *
     * @param integer $current_count
     * @return ServerProductCategory
     */
    public function setCurrentCount($current_count)
    {
        $this->current_count = $current_count;

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
     * @return ServerProductCategory
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

}

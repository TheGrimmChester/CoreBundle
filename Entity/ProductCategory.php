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
 * ProductCategory
 *
 * @ORM\Table(name="awhs_product_categories")
 * @ORM\Entity(repositoryClass="AWHS\CoreBundle\Entity\ProductCategoryRepository")
 */
class ProductCategory
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
     * @var string
     *
     * @ORM\Column(name="prefix", type="string", length=255, unique=true)
     */
    private $prefix;

    /**
     * @var string
     *
     * @ORM\Column(name="api_name", type="string", length=255)
     */
    private $api_name;

    /**
     * @var string
     *
     * @ORM\Column(name="entity", type="string", length=255)
     */
    private $entity;

    /**
     * @var string
     *
     * @ORM\Column(name="bundle_nume", type="string", length=255)
     */
    private $bundle_name;

    /**
     * @var string
     *
     * @ORM\Column(name="settings", type="string", length=1000)
     */
    private $settings;

    /**
     * @ORM\OneToMany(targetEntity="AWHS\CoreBundle\Entity\ServerProductCategory", mappedBy="product_category")
     * @ORM\OrderBy({"id" = "DESC"})
     */
    private $server_product_categories;

    public function __construct()
    {
        $this->server_product_categories = new ArrayCollection();
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
     * @return ProductCategory
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get prefix
     *
     * @return string
     */
    public function getPrefix()
    {
        return $this->prefix;
    }

    /**
     * Set prefix
     *
     * @param string $prefix
     * @return ProductCategory
     */
    public function setPrefix($prefix)
    {
        $this->prefix = $prefix;

        return $this;
    }

    /**
     * Get api_name
     *
     * @return string
     */
    public function getApiName()
    {
        return $this->api_name;
    }

    /**
     * Set api_name
     *
     * @param string $api_name
     * @return ProductCategory
     */
    public function setApiName($api_name)
    {
        $this->api_name = $api_name;

        return $this;
    }

    /**
     * Get entity
     *
     * @return string
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * Set entity
     *
     * @param string $entity
     * @return ProductCategory
     */
    public function setEntity($entity)
    {
        $this->entity = $entity;

        return $this;
    }

    /**
     * Get bundle_name
     *
     * @return string
     */
    public function getBundleName()
    {
        return $this->bundle_name;
    }

    /**
     * Set bundle_name
     *
     * @param string $bundle_name
     * @return ProductCategory
     */
    public function setBundleName($bundle_name)
    {
        $this->bundle_name = $bundle_name;
        return $this;
    }

    /**
     * Get settings
     *
     * @return string
     */
    public function getSettings()
    {
        return $this->settings;
    }

    /**
     * Set settings
     *
     * @param string $settings
     * @return ProductCategory
     */
    public function setSettings($settings)
    {
        $this->settings = $settings;

        return $this;
    }

    /**
     * Get settingsArray
     *
     * @return array
     */
    public function getSettingsArray()
    {
        return json_decode($this->settings, true);
    }

    public function getAvailability()
    {
        //$availabe = false;
        foreach ($this->getServerProductCategories() as $spc) {
            if ($spc->getActive() == true) {
                //Compter les produits
                //Compter la ram, espace disque
                return true;
            }
        }

        return false;
    }

    /**
     * Get server_product_categories
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getServerProductCategories()
    {
        return $this->server_product_categories;
    }

    public function __toString()
    {
        return $this->name . " (" . $this->prefix . ")";
    }
}

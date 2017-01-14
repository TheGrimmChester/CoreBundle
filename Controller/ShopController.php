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

namespace AWHS\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class ShopController extends Controller
{
    public function CatalogAction()
    {
        $user = $this->getUser();

        $em = $this->getDoctrine()->getManager();

        $subscription_packages = $em->getRepository('AWHSCoreBundle:SubscriptionPackage')
            ->findBy(array("active" => 1),
                array('position' => 'ASC')
            );

        $servers = $em->createQuery("
					SELECT g
					FROM AWHSCoreBundle:Server g
					WHERE g.active='1'")->getResult();

        $ips = $em->createQuery("
					SELECT g
					FROM AWHSCoreBundle:IP g
					WHERE g.active='0'")->getResult();

        return $this->render('AWHSCoreBundle:' . $this->container->getParameter('awhs')['template'] . '/Shop:catalog.html.twig', array(
            'packages' => $subscription_packages,
            'user' => $user,
            'ips' => $ips,
            'servers' => $servers,
        ));
    }
}
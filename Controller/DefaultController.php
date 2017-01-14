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

use AWHS\CoreBundle\Config\Config;
use AWHS\CoreBundle\Entity\IP;
use AWHS\CoreBundle\Entity\Server;
use AWHS\WebsiteBundle\Entity\RecordTemplate;
use AWHS\WebsiteBundle\Entity\VPSService;
use Doctrine\ORM\EntityRepository;
use phpseclib\Crypt\AES as AES;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;


class DefaultController extends Controller
{


    public function indexAction()
    {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }

        $user = $this->getUser();


        return $this->render('AWHSCoreBundle:' . $this->container->getParameter('awhs')['template'] . '/Default:index.html.twig', array(
            'user' => $user,
        ));
    }

    public function ipsAction()
    {
        // On récupère l'entité correspondant à l'id $id
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }

        $user = $this->getUser();


        $em = $this->getDoctrine()->getManager();

        $ips = $em->createQuery("
		SELECT i
		FROM AWHSCoreBundle:IP i")->getResult();

        $ipsv4 = $em->createQuery("
		SELECT i
		FROM AWHSCoreBundle:IP i 
		WHERE i.ipv6='0' AND i.active='0'")->getResult();

        $ipsv6 = $em->createQuery("
		SELECT i
		FROM AWHSCoreBundle:IP i 
		WHERE i.ipv6='1' AND i.active='0'")->getResult();

        $countries = $em->createQuery("
		SELECT c
		FROM AWHSCoreBundle:Country c")->getResult();

        return $this->render('AWHSCoreBundle:' . $this->container->getParameter('awhs')['template'] . '/Default:ips.html.twig', array(
            'user' => $user,
            'ipsv4' => $ipsv4,
            'ipsv6' => $ipsv6,
            'ips' => $ips,
            'countries' => $countries,
        ));
    }

    public function ipAddAction(Request $request)
    {
        // On récupère l'entité correspondant à l'id $id
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }

        $user = $this->getUser();


        $em = $this->getDoctrine()->getManager();

        $countries = $em->createQuery("
		SELECT c
		FROM AWHSCoreBundle:Country c")->getResult();

        $ip = new IP();
        $formBuilderIP = $this->createFormBuilder($ip);
        $formBuilderIP->add('ip', TextType::class, array('label' => 'Adresse IP'));
        $formBuilderIP->add('reverse', TextType::class, array('label' => 'Reverse'));
        $formBuilderIP->add('mac', TextType::class, array('label' => 'Adresse MAC',
            'required' => false));
        $formBuilderIP->add('ipv6', \Symfony\Component\Form\Extension\Core\Type\ChoiceType::class, array(
            'choices' => array(
                '0' => 'Non',
                '1' => 'Oui'
            )));
        $formBuilderIP->add('country', EntityType::class, array(
            'label' => 'Pays',
            'class' => 'AWHSCoreBundle:Country',
            'choice_label' => 'name',
        ));
        $formBuilderIP->add('server', EntityType::class, array(
            'label' => 'Serveur',
            'class' => 'AWHSCoreBundle:Server',
            'choice_label' => 'name',
        ));

        $formIP = $formBuilderIP->getForm();
        if ($request->getMethod() == 'POST') {
            $formIP->handleRequest($request);
            if ($formIP->isValid()) {

                $ipv6 = $ip->getIpv6();
                if ($ipv6 == 'Non') {
                    $ip->setIpv6(0);
                } else {
                    $ip->setIpv6(1);
                }
                $ip->setActive(0);

                $em->persist($ip);
                $em->flush();

                return $this->redirect($this->generateUrl('awhs_admin_homepage', array()));
            }
        }


        return $this->render('AWHSCoreBundle:' . $this->container->getParameter('awhs')['template'] . '/Default:ipAdd.html.twig', array(
            'user' => $user,
            'countries' => $countries,
            'formIP' => $formIP->createView(),
        ));
    }

    public function ipAction(Request $request, $id)
    {
        // On récupère l'entité correspondant à l'id $id
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }

        $user = $this->getUser();


        $em = $this->getDoctrine()->getManager();

        $ip = $em->createQuery("
		SELECT i
		FROM AWHSCoreBundle:IP i
		WHERE i.id='{$id}'")->getSingleResult();

        $formBuilderIP = $this->createFormBuilder($ip);
        $formBuilderIP->add('ip', TextType::class, array('label' => 'Adresse IP'));
        $formBuilderIP->add('reverse', TextType::class, array('label' => 'Reverse'));
        $formBuilderIP->add('mac', TextType::class, array('label' => 'Adresse MAC',
            'required' => false));
        $formBuilderIP->add('ipv6', \Symfony\Component\Form\Extension\Core\Type\ChoiceType::class, array(
            'choices' => array(
                '0' => 'Non',
                '1' => 'Oui'
            )));
        $formBuilderIP->add('country', EntityType::class, array(
            'label' => 'Pays',
            'class' => 'AWHSCoreBundle:Country',
            'choice_label' => 'name',
        ));
        $formBuilderIP->add('server', EntityType::class, array(
            'label' => 'Serveur',
            'class' => 'AWHSCoreBundle:Server',
            'choice_label' => 'name',
        ));

        $formIP = $formBuilderIP->getForm();
        if ($request->getMethod() == 'POST') {
            $formIP->handleRequest($request);
            if ($formIP->isValid()) {

                $ipv6 = $ip->getIpv6();

                if ($ipv6 == 'Non') {
                    $ip->setIpV6(0);
                } else {
                    $ip->setIpV6(1);
                }
                //$ip->setActive(0);

                $em->persist($ip);
                $em->flush();

                return $this->redirect($this->generateUrl('awhs_admin_manage_ip', array('id' => $id)));
            }
        }

        return $this->render('AWHSCoreBundle:' . $this->container->getParameter('awhs')['template'] . '/Default:ip.html.twig', array(
            'user' => $user,
            'formIP' => $formIP->createView(),
        ));
    }
}

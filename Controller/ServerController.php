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

use AWHS\CoreBundle\AWHSCoreBundle;
use AWHS\CoreBundle\Config\Config;
use AWHS\CoreBundle\Entity\Server;
use AWHS\CoreBundle\Entity\ServerProductCategory;
use AWHS\CoreBundle\Lib\Utils;
use Doctrine\ORM\EntityRepository;
use phpseclib\Crypt\AES as AES;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints as Assert;

class ServerController extends Controller
{
    public function serversAction(Request $request)
    {
        // On récupère l'entité correspondant à l'id $id
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }

        $user = $this->getUser();


        $em = $this->getDoctrine()->getManager();
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

        $servers = $em->createQuery("
		SELECT s
		FROM AWHSCoreBundle:Server s")->getResult();

        $newServer = new Server();

        $formBuilder = $this->createFormBuilder($newServer);
        $formBuilder
            ->add('name', TextType::class, array('label' => 'Nom'))
            ->add('ip', TextType::class, array('label' => 'Adresse IP', 'required' => false))
            ->add('username', TextType::class, array('label' => 'SSH Username', 'required' => false))
            ->add('reverse', TextType::class, array('label' => 'Hostname'))
            ->add('password', TextType::class, array('label' => 'SSH Password', 'required' => false))
            ->add('private_key', TextareaType::class, array('label' => 'SSH Private Key', 'required' => false))
            ->add('port', TextType::class, array('label' => 'SSH Port', 'required' => false))
            ->add('database_host', TextType::class, array('label' => 'Database Host', 'required' => false))
            ->add('database_user', TextType::class, array('label' => 'Database Username', 'required' => false))
            ->add('database_password', TextType::class, array('label' => 'Database Password', 'required' => false))
            ->add('database_port', TextType::class, array('label' => 'Database Port', 'required' => false))
            ->add('country', EntityType::class, array(
                'label' => 'Country',
                'class' => 'AWHSCoreBundle:Country',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->add('orderBy', 'u.name ASC');
                },
                'choice_label' => 'name'
            ));

        $form = $formBuilder->getForm();

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $this->get('session')->getFlashBag()->add(
                    'notice', 'Enregistrement modifié avec succès.'
                );
                $s = $form->getData();

                $oldUsername = "";
                $oldPassword = "";
                $oldDatabaseUser = "";
                $oldDatabasePassword = "";
                $s->setSubnet("");
                $s->setGateway("");
                $s->setDns("");
                $s->setDns2("");

                //Server user
                if ($s->getUsername() == "") {
                    $s->setUsername($oldUsername);
                } else {
                    $newUsername = $s->getUsername();
                    $aes = new AES();
                    $aes->setKey(Config::getEncryptionKey());
                    $username = $aes->encrypt($newUsername);
                    $username = base64_encode($username);
                    $s->setUsername($username);
                }
                //Server password
                if ($s->getPassword() == "") {
                    $s->setPassword($oldPassword);
                } else {
                    $newPassword = $s->getPassword();
                    $aes = new AES();
                    $aes->setKey(Config::getEncryptionKey());
                    $password = $aes->encrypt($newPassword);
                    $password = base64_encode($password);
                    $s->setPassword($password);
                }

                //Server ppk
                if ($s->getPrivateKey() == "") {
                    $s->setPrivateKey($oldPassword);
                } else {
                    $newPpk = $s->getPrivateKey();
                    $aes = new AES();
                    $aes->setKey(Config::getEncryptionKey());
                    $ppk = $aes->encrypt($newPpk);
                    $ppk = base64_encode($ppk);
                    $s->setPrivateKey($ppk);
                }
                //Database user
                if ($s->getDatabaseUser() == "") {
                    $s->setDatabaseUser($oldDatabaseUser);
                } else {
                    $newDatabaseUser = $s->getDatabaseUser();
                    $aes = new AES();
                    $aes->setKey(Config::getEncryptionKey());
                    $databaseUser = $aes->encrypt($newDatabaseUser);
                    $databaseUser = base64_encode($databaseUser);
                    $s->setDatabaseUser($databaseUser);
                }
                //Database password
                if ($s->getDatabasePassword() == "") {
                    $s->setDatabasePassword($oldDatabasePassword);
                } else {
                    $newDatabasePassword = $s->getDatabasePassword();
                    $aes = new AES();
                    $aes->setKey(Config::getEncryptionKey());
                    $databasePassword = $aes->encrypt($newDatabasePassword);
                    $databasePassword = base64_encode($databasePassword);
                    $s->setDatabasePassword($databasePassword);
                }
                $s->setActive(1);
                $em->persist($s);
                $em->flush();
                return $this->redirect($this->generateUrl('awhs_admin_server_settings', array('server_hostname' => $s->getReverse())));
            } else {
                return $this->render("AWHSCoreBundle:" . $this->container->getParameter('awhs')['template'] . "/Server:serverSettings.html.twig", array(
                    'user' => $user,
                    'ipsv4' => $ipsv4,
                    'ipsv6' => $ipsv6,
                    'countries' => $countries,
                    'servers' => $servers,
                    'form' => $form->createView(),
                ));
            }
        }

        return $this->render('AWHSCoreBundle:' . $this->container->getParameter('awhs')['template'] . '/Server:servers.html.twig', array(
            'user' => $user,
            'ipsv4' => $ipsv4,
            'ipsv6' => $ipsv6,
            'countries' => $countries,
            'servers' => $servers,
            'form' => $form->createView(),
        ));
    }

    public function dashboardAction($server_hostname)
    {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }

        $user = $this->getUser();

        $em = $this->getDoctrine()->getManager();

        $server = $em->createQuery("
		SELECT s
		FROM AWHSCoreBundle:Server s
		WHERE s.reverse = '$server_hostname'")->getSingleResult();

        $bundles = $this->container->getParameter('kernel.bundles');

        return $this->render('AWHSCoreBundle:' . $this->container->getParameter('awhs')['template'] . '/Server:dashboard.html.twig',
            array('user' => $user, 'server' => $server, 'bundles' => $bundles)
        );
    }

    public function addProductCategoryAction(Request $request, $server_hostname)
    {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }

        $user = $this->getUser();


        $em = $this->getDoctrine()->getManager();

        $server = $em->createQuery("
		SELECT s
		FROM AWHSCoreBundle:Server s
		WHERE s.reverse = '$server_hostname'")->getSingleResult();

        $serverProductCategory = new ServerProductCategory();

        $formBuilder = $this->createFormBuilder($serverProductCategory);
        /*$formBuilder->add('server', \Symfony\Bridge\Doctrine\Form\Type\EntityType::class, array(
			'class'     => 'AWHSCoreBundle:Server',
			'expanded'  => false,
			'multiple'  => false
		));*/
        $formBuilder->add('product_category', EntityType::class, array(
            'class' => 'AWHSCoreBundle:ProductCategory',
            'expanded' => false,
            'multiple' => false
        ))
            ->add('number', TextType::class, array('label' => 'Quantité'))
            ->add('disk_space', TextType::class, array('label' => 'Espace disque', 'required' => false))
            ->add('ram', TextType::class, array('label' => 'RAM', 'required' => false))
            ->add('active', CheckboxType::class, array('label' => 'Actif', 'required' => false));

        $form = $formBuilder->getForm();

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $serverProductCategory = $form->getData();
                $serverProductCategory->setCurrentCount(0);
                $serverProductCategory->setServer($server);
                try {
                    $em->persist($serverProductCategory);
                    $em->flush();

                    $this->get('session')->getFlashBag()->add(
                        'notice', 'Enregistrement modifié avec succès.'
                    );
                } catch (\Exception $e) {
                    $this->get('session')->getFlashBag()->add(
                        'error', 'Cette catégorie de produit existe déjà pour ce serveur.'
                    );
                }
            }
        }

        return $this->render('AWHSCoreBundle:' . $this->container->getParameter('awhs')['template'] . '/Server:addProductCategory.html.twig', array('user' => $user, 'server' => $server, 'form' => $form->createView(),));
    }

    public function editProductCategoryAction(Request $request, $server_hostname, $server_product_category_id)
    {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }

        $user = $this->getUser();


        $em = $this->getDoctrine()->getManager();

        $server = $em->createQuery("
		SELECT s
		FROM AWHSCoreBundle:Server s
		WHERE s.reverse = '$server_hostname'")->getSingleResult();

        $serverProductCategory = $em->createQuery("
		SELECT spc
		FROM AWHSCoreBundle:ServerProductCategory spc
		WHERE spc.id = '$server_product_category_id'")->getSingleResult();

        $formBuilder = $this->createFormBuilder($serverProductCategory);
        /*$formBuilder->add('server', EntityType::class, array(
			'class'     => 'AWHSCoreBundle:Server',
			'expanded'  => false,
			'multiple'  => false
		));*/
        $formBuilder->add('product_category', EntityType::class, array(
            'class' => 'AWHSCoreBundle:ProductCategory',
            'expanded' => false,
            'multiple' => false
        ))
            ->add('number', TextType::class, array('label' => 'Quantité'))
            ->add('disk_space', TextType::class, array('label' => 'Espace disque', 'required' => false))
            ->add('ram', TextType::class, array('label' => 'RAM', 'required' => false))
            ->add('active', CheckboxType::class, array('label' => 'Actif', 'required' => false));

        $form = $formBuilder->getForm();

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);

            if ($form->isValid()) {
                try {
                    $em->persist($serverProductCategory);
                    $em->flush();

                    $this->get('session')->getFlashBag()->add(
                        'notice', 'Enregistrement modifié avec succès.'
                    );
                } catch (\Exception $e) {
                    $this->get('session')->getFlashBag()->add(
                        'error', 'Cette catégorie de produit existe déjà pour ce serveur.'
                    );
                }
            }
        }

        return $this->render('AWHSCoreBundle:' . $this->container->getParameter('awhs')['template'] . '/Server:editProductCategory.html.twig', array('user' => $user, 'server' => $server, 'form' => $form->createView(),));
    }

    public function productCategoriesAction($server_hostname)
    {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }

        $user = $this->getUser();


        $em = $this->getDoctrine()->getManager();

        $server = $em->createQuery("
		SELECT s
		FROM AWHSCoreBundle:Server s
		WHERE s.reverse = '$server_hostname'")->getSingleResult();

        $productCategories = $em->createQuery("
		SELECT s
		FROM AWHSCoreBundle:ServerProductCategory s
		WHERE s.server = '{$server->getId()}'")->getResult();

        return $this->render('AWHSCoreBundle:' . $this->container->getParameter('awhs')['template'] . '/Server:productCategories.html.twig', array('user' => $user, 'server' => $server, 'productCategories' => $productCategories,));
    }

    public function updateAction($server_hostname)
    {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }

        $user = $this->getUser();


        $em = $this->getDoctrine()->getManager();

        $server = $em->createQuery("
		SELECT s
		FROM AWHSCoreBundle:Server s
		WHERE s.reverse = '$server_hostname'")->getSingleResult();

        $api = $this->container->get("awhs_core.server");
        $api->setServer($server);

        return $this->render('AWHSCoreBundle:' . $this->container->getParameter('awhs')['template'] . '/Server:update.html.twig', array('user' => $user, 'server' => $server, 'api' => $api));
    }

    public function packagesUpdateAction($server_hostname, $package_type)
    {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }

        $em = $this->getDoctrine()->getManager();

        $server = $em->createQuery("
		SELECT s
		FROM AWHSCoreBundle:Server s
		WHERE s.reverse = '$server_hostname'")->getSingleResult();

        $api = $this->container->get("awhs_core.server");
        $api->setServer($server);

        //
        if ($package_type == "all") {
            $api->updatePackages();
        } else //($package_type == "security")
        {
            $api->updatePackages(true);
        }

        return $this->redirect($this->generateUrl('awhs_admin_server_dashboard', array('server_hostname' => $server_hostname)));
    }

    public function securityUpdateCountAction($server_hostname)
    {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }

        $em = $this->getDoctrine()->getManager();

        $server = $em->createQuery("
		SELECT s
		FROM AWHSCoreBundle:Server s
		WHERE s.reverse = '$server_hostname'")->getSingleResult();

        $api = $this->container->get("awhs_core.server");
        $api->setServer($server);
        $count = $api->getSecurityUpdateCount();
        $response = new Response(json_encode(array('count' => $count)));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    public function firewallAction($server_hostname)
    {
        // On récupère l'entité correspondant à l'id $server_hostname
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }

        $user = $this->getUser();


        $em = $this->getDoctrine()->getManager();

        $server = $em->createQuery("
		SELECT s
		FROM AWHSCoreBundle:Server s
		WHERE s.reverse='$server_hostname'")->getSingleResult();

        $api = $this->container->get("awhs_core.server");
        $api->setServer($server);

        $iptables = $this->container->get("awhs_core.iptables");
        $iptables->setServer($server);
        $iptables->setOnFly(FALSE);

        $flashes = array();
        $editDialogDisplayed = FALSE;
        $editDialogAction = '';

        if (isset($_GET['export'])) {
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="iptables-export.conf"');
            echo $iptables->export();
            exit;
        }
        if (isset($_GET['import'])) {
            if (!isset($_FILES['file']) || $_FILES['file']['error'] == UPLOAD_ERR_NO_FILE) {
                $flashes['danger'][] = 'No file selected';
            } else {
                $content = file_get_contents($_FILES['file']['tmp_name']);
                $output = $iptables->import($content);
                if ($output) {
                    $flashes['danger'][] = $output;
                } else {
                    $flashes['success'][] = 'File successfully imported';
                }
                return $this->redirect($this->generateUrl('awhs_admin_server_firewall', array('server_hostname' => $server_hostname)));
            }
        }
        if (isset($_GET['remove'])) {
            $rule = $iptables->buildRuleFromQuery($_GET);
            $iptables->remove($rule, $_GET['table'], $_GET['chain']);
        }
        if (isset($_GET['edit'])) {
            $editDialogDisplayed = TRUE;
            $rule = $iptables->buildRuleFromQuery($_GET);
            $editDialogAction .= '?edit&' . $iptables->buildQueryFromRule($rule, $_GET['table'], $_GET['chain']);
            if (isset($_POST['submit'])) {
                $iptables->remove($rule, $_GET['table'], $_GET['chain']);
                $newRule = $iptables->buildRuleFromQuery($_POST);
                $iptables->add($newRule, $_POST['table'], $_POST['chain']);
                return $this->redirect($this->generateUrl('awhs_admin_server_firewall', array('server_hostname' => $server_hostname)));
            }
        }
        if (isset($_GET['add'])) {
            $editDialogDisplayed = TRUE;
            $editDialogAction .= '?add';
            if (isset($_POST['submit'])) {
                $rule = $iptables->buildRuleFromQuery($_POST);
                $iptables->add($rule, $_POST['table'], $_POST['chain']);
            }
        }

        $interfaces = $api->getServerManager()->getNetworkInterfaces();

        return $this->render('AWHSCoreBundle:' . $this->container->getParameter('awhs')['template'] . '/Server:firewall.html.twig', array(
            'user' => $user,
            'server' => $server,
            'interfaces' => $interfaces,
            'iptables' => $iptables,
            '_get' => $_GET,
            '_post' => $_POST,
            'flashes' => $flashes,
            'editDialogDisplayed' => $editDialogDisplayed,
            'editDialogAction' => $editDialogAction,
        ));
    }

    public function packageAction($server_hostname, $package_name, $package_action)
    {
        // On récupère l'entité correspondant à l'id $server_hostname
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }

        $user = $this->getUser();


        $em = $this->getDoctrine()->getManager();

        $server = $em->createQuery("
		SELECT s
		FROM AWHSCoreBundle:Server s
		WHERE s.reverse='$server_hostname'")->getSingleResult();

        $api = $this->container->get("awhs_core.server");
        $api->setServer($server);

        switch ($package_action) {
            case 'start': {
                $api->daemonAction($package_name, $package_action);
                break;
            }
            case 'stop': {
                $api->daemonAction($package_name, $package_action);
                break;
            }
            case 'reload': {
                $api->daemonAction($package_name, $package_action);
                break;
            }
            case 'restart': {
                $api->daemonAction($package_name, $package_action);
                break;
            }
            case 'install': {
                $api->awhs_install($package_name);
                break;
            }
            case 'remove': {
                $api->awhs_remove($package_name);
                break;
            }
            case 'configure': {
                $api->awhs_configure($package_name);
                break;
            }
        }


        return $this->redirect($this->generateUrl('awhs_admin_server_packages', array('server_hostname' => $server_hostname)));
    }

    public function packagesAction($server_hostname)
    {
        // On récupère l'entité correspondant à l'id $server_hostname
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }

        $user = $this->getUser();


        $em = $this->getDoctrine()->getManager();

        $server = $em->createQuery("
		SELECT s
		FROM AWHSCoreBundle:Server s
		WHERE s.reverse='$server_hostname'")->getSingleResult();

        $api = $this->container->get("awhs_core.server");
        $api->setServer($server);

        $packages = array();
        $packagesList = $api->getListPackages();

        foreach ($packagesList as $package) {
            $is_installed = $api->getServerManager()->isPackageInstalled($package['name']);
            $is_running = $api->getServerManager()->isPackageRunning($package['daemon']);
            $version = "";

            if ($is_installed === true) {
                $version = $api->getServerManager()->getPackageVersion($package['name']);
            }

            $packages[] = array(
                'name' => $package['name'],
                'daemon' => $package['daemon'],
                'version' => Utils::cleanPackageVersion($version),
                'is_installed' => $is_installed,//($is_installed == false)? 'Not Installed':'Installed',
                'is_running' => $is_running,//($is_installed == false)? 'Not Installed':'Installed',
            );
        }


        return $this->render('AWHSCoreBundle:' . $this->container->getParameter('awhs')['template'] . '/Server:packages.html.twig', array(
            'user' => $user,
            'server' => $server,
            'packages' => $packages,
        ));
    }

    public function processesJsonAction($server_hostname)
    {
        // On récupère l'entité correspondant à l'id $server_hostname
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }

        $em = $this->getDoctrine()->getManager();

        $server = $em->createQuery("
		SELECT s
		FROM AWHSCoreBundle:Server s
		WHERE s.reverse='$server_hostname'")->getSingleResult();

        $api = $this->container->get("awhs_core.server");
        $api->setServer($server);
        $response = new Response(json_encode($api->getServerManager()->getProcesses()));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    public function packagesJsonAction($server_hostname)
    {
        // On récupère l'entité correspondant à l'id $server_hostname
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }

        $user = $this->getUser();


        $em = $this->getDoctrine()->getManager();

        $server = $em->createQuery("
		SELECT s
		FROM AWHSCoreBundle:Server s
		WHERE s.reverse='$server_hostname'")->getSingleResult();

        $api = $this->container->get("awhs_core.server");
        $api->setServer($server);
        $response = new Response(json_encode($api->getServerManager()->getInstalledPackages()));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    public function serverAction($server_hostname)
    {
        // On récupère l'entité correspondant à l'id $server_hostname
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }

        $user = $this->getUser();


        $em = $this->getDoctrine()->getManager();

        $server = $em->createQuery("
		SELECT s
		FROM AWHSCoreBundle:Server s
		WHERE s.reverse='$server_hostname'")->getSingleResult();

        $api = $this->container->get("awhs_core.server");
        $api->setServer($server);

        $serverInfos = $api->getServerInfos();

        return $this->render('AWHSCoreBundle:' . $this->container->getParameter('awhs')['template'] . '/Server:server.html.twig', array(
            'user' => $user,
            'server' => $server,
            'serverInfos' => $serverInfos,
        ));
    }

    public function serverJsonAction($server_hostname)
    {
        // On récupère l'entité correspondant à l'id $server_hostname
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }

        $em = $this->getDoctrine()->getManager();

        $server = $em->createQuery("
		SELECT s
		FROM AWHSCoreBundle:Server s
		WHERE s.reverse='$server_hostname'")->getSingleResult();

        /**
         * @var \AWHS\CoreBundle\API\Server $api
         */
        $api = $this->container->get("awhs_core.server");
        $api->setServer($server);

        if(isset($_GET['datas']) && !empty($_GET['datas'])){
            switch(strtolower($_GET['datas'])){
                case "memory":{
                    $response = new Response(json_encode($api->getServerManager()->getMemory()));
                    break;
                }

                case "lavg":{
                    $response = new Response(json_encode($api->getServerManager()->getLoadAverage()));
                    break;
                }

                case "network":{
                    $response = new Response(json_encode($api->getServerManager()->getLiveNetworkUsage()));
                    break;
                }

                default: {
                    $response = new Response(json_encode(array()));
                    break;
                }
            }
        } else {
            $serverInfos = $api->getServerInfos();
            $response = new Response(json_encode($serverInfos));
        }

        $response->headers->set('Content-Type', 'application/json');
        return $response;

    }

    public function serverSshAction($server_hostname)
    {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }

        $user = $this->getUser();


        $em = $this->getDoctrine()->getManager();

        $server = $em->createQuery("
		SELECT s
		FROM AWHSCoreBundle:Server s
		WHERE s.reverse='$server_hostname'")->getSingleResult();

        $api = $this->container->get("awhs_core.server");
        $api->setServer($server);

        if (isset($_POST['generate'])) {
            $api->getServerManager()->generateSshKey();
            return $this->redirect($this->generateUrl('awhs_admin_server_ssh', array('server_hostname' => $server_hostname)));
        }

        $keys = $api->getServerManager()->getSshKeys();

        return $this->render('AWHSCoreBundle:' . $this->container->getParameter('awhs')['template'] . '/Server:serverSsh.html.twig', array(
            'user' => $user,
            'server' => $server,
            'keys' => $keys,
        ));
    }

    public function serverSettingsAction(Request $request, $server_hostname)
    {

        // On récupère l'entité correspondant à l'id $server_hostname
        // On récupère l'entité correspondant à l'id $id
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }

        $user = $this->getUser();


        $em = $this->getDoctrine()->getManager();

        $ipsv4 = $em->createQuery("
		SELECT i
		FROM AWHSCoreBundle:IP i
		WHERE i.ipv6='0'")->getResult();

        $ipsv6 = $em->createQuery("
		SELECT i
		FROM AWHSCoreBundle:IP i
		WHERE i.ipv6='1'")->getResult();

        $countries = $em->createQuery("
		SELECT c
		FROM AWHSCoreBundle:Country c")->getResult();

        /*if(isset($_POST['addServer']))
        {
            $notValid = 0;
            if(!filter_var($_POST['ip'], FILTER_VALIDATE_IP))
            {
                if(!filter_var($_POST['ip'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV6))
                {
                    $notValid = 1;
                }
            }

            if(!is_numeric($_POST['port']))
            {
                $notValid = 1;
            }

            $avCountries = array();
            foreach($countries as $c)
            {
                array_push($avCountries, $c->getNumcode());
            }
            if(!in_array($_POST['country'], $avCountries))
            {
                $notValid = 1;
            }

            if($notValid == 0)
            {
                $selectedCountry = $em->createQuery("
                SELECT c
                FROM AWHSCoreBundle:Country c
                WHERE c.numcode='{$_POST['country']}'")->getSingleResult();

                $server = new \AWHS\CoreBundle\Entity\Server;
                $server->setCountry($selectedCountry);
                $server->setName($_POST['name']);
                $server->setIp($_POST['ip']);
                $server->setReverse("");
                $server->setSubnet("");
                $server->setGateway("");
                $server->setDns("");
                $server->setDns2("");
                $server->setUsername($_POST['username']);
                $aes = new AES();
                $aes->setKey(Config::getEncryptionKey());
                $password = $aes->encrypt($_POST['password']);
                $password = base64_encode($password);
                $server->setPassword($password);
                $server->setPort($_POST['port']);
                $server->setActive(1);

                $em->persist($server);
                $em->flush();
            }
            return $this->redirect($this->generateUrl('awhs_admin_manage_server', array()));
        }*/

        $server = $em->createQuery("
		SELECT s
		FROM AWHSCoreBundle:Server s
		WHERE s.reverse='$server_hostname'")->getSingleResult();

        $oldUsername = $server->getUsername();
        $oldPassword = $server->getPassword();
        $oldPrivateKey = $server->getPrivateKey();
        $oldDatabaseUser = $server->getDatabaseUser();
        $oldDatabasePassword = $server->getDatabasePassword();

        $formBuilder = $this->createFormBuilder($server);
        $formBuilder
            ->add('name', TextType::class, array(
                    //'class' => 'AWHSCoreBundle:Country',
                    'label' => 'Nom')
            )
            ->add('country', EntityType::class, array(
                'label' => 'Pays',
                'class' => 'AWHSCoreBundle:Country',
                'choice_label' => 'name',
            ))
            ->add('ip', TextType::class, array('label' => 'Adresse IP'))
            ->add('username', TextType::class, array('label' => 'Nom d\'utilisateur', 'required' => false, 'data' => ''))
            ->add('reverse', TextType::class, array('label' => 'FQDN'))
            ->add('shortname', TextType::class, array('label' => 'Short name'))
            ->add('password', TextType::class, array('label' => 'Mot de passe', 'required' => false, 'data' => ''))
            ->add('private_key', TextareaType::class, array('label' => 'Private Key', 'required' => false, 'data' => ''))
            ->add('port', TextType::class, array('label' => 'Port'))
            ->add('database_host', TextType::class, array('label' => 'Host'))
            ->add('database_user', TextType::class, array('label' => 'Nom d\'utilisateur', 'required' => false, 'data' => ''))
            ->add('database_password', TextType::class, array('label' => 'Mot de passe', 'required' => false, 'data' => ''))
            ->add('database_port', TextType::class, array('label' => 'Port'));
        //->add('ttl', \Symfony\Component\Form\Extension\Core\Type\IntegerType::class, array('label' => 'TTL'));

        $form = $formBuilder->getForm();

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $this->get('session')->getFlashBag()->add(
                    'notice', 'Enregistrement modifié avec succès.'
                );
                $s = $form->getData();
                //Server user
                if ($s->getUsername() == "") {
                    $s->setUsername($oldUsername);
                } else {
                    $newUsername = $s->getUsername();
                    $aes = new AES();
                    $aes->setKey(Config::getEncryptionKey());
                    $username = $aes->encrypt($newUsername);
                    $username = base64_encode($username);
                    $s->setUsername($username);
                }
                //Server password
                if ($s->getPassword() == "") {
                    $s->setPassword($oldPassword);
                } else {
                    $newPassword = $s->getPassword();
                    $aes = new AES();
                    $aes->setKey(Config::getEncryptionKey());
                    $password = $aes->encrypt($newPassword);
                    $password = base64_encode($password);
                    $s->setPassword($password);
                }

                //Server ppk
                if ($s->getPrivateKey() == "") {
                    $s->setPrivateKey($oldPrivateKey);
                } else {
                    $newPpk = $s->getPrivateKey();
                    $aes = new AES();
                    $aes->setKey(Config::getEncryptionKey());
                    $ppk = $aes->encrypt($newPpk);
                    $ppk = base64_encode($ppk);
                    $s->setPrivateKey($ppk);
                }
                //Database user
                if ($s->getDatabaseUser() == "") {
                    $s->setDatabaseUser($oldDatabaseUser);
                } else {
                    $newDatabaseUser = $s->getDatabaseUser();
                    $aes = new AES();
                    $aes->setKey(Config::getEncryptionKey());
                    $databaseUser = $aes->encrypt($newDatabaseUser);
                    $databaseUser = base64_encode($databaseUser);
                    $s->setDatabaseUser($databaseUser);
                }
                //Database password
                if ($s->getDatabasePassword() == "") {
                    $s->setDatabasePassword($oldDatabasePassword);
                } else {
                    $newDatabasePassword = $s->getDatabasePassword();
                    $aes = new AES();
                    $aes->setKey(Config::getEncryptionKey());
                    $databasePassword = $aes->encrypt($newDatabasePassword);
                    $databasePassword = base64_encode($databasePassword);
                    $s->setDatabasePassword($databasePassword);
                }
                $em->persist($s);
                $em->flush();
                return $this->redirect($this->generateUrl('awhs_admin_server_settings', array('server_hostname' => $s->getReverse())));
            } else {
                return $this->render("AWHSCoreBundle:" . $this->container->getParameter('awhs')['template'] . "/Default:serverSettings.html.twig", array(
                    'user' => $user,
                    'ipsv4' => $ipsv4,
                    'ipsv6' => $ipsv6,
                    'countries' => $countries,
                    'server' => $server,
                    'form' => $form->createView(),
                ));
            }
        }


        return $this->render('AWHSCoreBundle:' . $this->container->getParameter('awhs')['template'] . '/Server:serverSettings.html.twig', array(
            'user' => $user,
            'ipsv4' => $ipsv4,
            'ipsv6' => $ipsv6,
            'countries' => $countries,
            'server' => $server,
            'form' => $form->createView(),
        ));
    }
}
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

use AWHS\CoreBundle\Entity\Invoice;
use AWHS\CoreBundle\Entity\Subscription;
use AWHS\CoreBundle\Entity\SubscriptionProduct;
use AWHS\TaskBundle\Entity\Task;
use AWHS\TaskBundle\Entity\TaskSubscription;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


class CartController extends Controller
{
    public function cartAction(Request $request)
    {
        $user = $this->getUser();

        $em = $this->getDoctrine()->getManager();

        $cart_packages = array();

        $session = $request->getSession();

        $ips = $em->createQuery("
					SELECT g
					FROM AWHSCoreBundle:IP g
					WHERE g.active='0'")->getResult();

        if (isset($session->get('cart')['packages'])) {
            $packages = $session->get('cart')['packages'];
        } else {
            $cart = array();
            $cart['packages'] = array();
            $cart['months'] = 12;
            $session->set('cart', $cart);
            $packages = $session->get('cart')['packages'];
        }


        if (isset($_POST['addToCart']) && !empty($_POST)) {
            $requiredIps = 0;
            if (isset($_POST['package']) && is_numeric($_POST['package'])) {
                $package_id = (int)$_POST['package'];

                //Vérifier si le package existe en BD
                try {
                    $subscription_package = $em->createQuery("SELECT a
																FROM AWHSCoreBundle:SubscriptionPackage a
																WHERE a.id='$package_id'")->getSingleResult();

                    foreach ($subscription_package->getProductsPackage() as $product_package) {
                        if ($product_package->getProduct()->getCategory()->getApiName() == "vps") {
                            $requiredIps = $requiredIps + 1;
                            if ($requiredIps > count($ips)) {
                                $this->get('session')->getFlashBag()->add(
                                    'error',
                                    'Désolé, le produit que vous tentez d\'ajouter n\'est plus en stock.'
                                );
                                return $this->redirect($this->generateUrl('awhs_core_shop', array()));
                            }
                        }
                    }
                } catch (\Exception $e) {
                    $this->get('session')->getFlashBag()->add(
                        'error',
                        'Désolé, le produit que vous tentez d\'ajouter n\'est plus disponible.'
                    );
                    return $this->redirect($this->generateUrl('awhs_core_shop', array()));
                }


                //Si le panier est vide
                if (!empty($packages)) {
                    $exists = false;
                    $package_key = null;
                    foreach ($packages as $key => $package) {
                        if ($package['package_id'] == $package_id) {
                            $exists = true;
                            $package_key = $key;
                            break;
                        }
                    }


                    if ($exists == true) {
                        $packages[$package_key]['quantity'] = $packages[$package_key]['quantity'] + 1;
                        if (count($packages[$package_key]['settings']) != $packages[$package_key]['quantity']) {
                            $messingSettings = $packages[$package_key]['quantity'] - count($packages[$package_key]['settings']);
                            if ($messingSettings > 0) {
                                for ($i = 0; $i <= $messingSettings; $i++) {
                                    $packages[$package_key]['settings'][] = array();
                                }
                            }
                        }
                    } else {
                        $settings = array();
                        for ($i = 0; $i <= 1; $i++) {
                            $settings[] = array();
                        }

                        $packages[] = array("identifier" => $this->generateRandomString(),
                            'package_id' => $package_id,
                            'quantity' => 1,
                            'settings' => $settings);
                    }

                } else {
                    if (isset($_POST['package']) && is_numeric($_POST['package'])) {
                        $settings = array();
                        for ($i = 0; $i <= 1; $i++) {
                            $settings[] = array();
                        }

                        $packages[] = array("identifier" => $this->generateRandomString(),
                            'package_id' => $package_id,
                            'quantity' => 1,
                            'settings' => $settings);
                    }
                }
            } else {
                $this->get('session')->getFlashBag()->add(
                    'error',
                    'Désolé, le produit que vous tentez d\'ajouter n\'est plus disponible.'
                );
                return $this->redirect($this->generateUrl('awhs_core_shop', array()));
            }
            $cart = array();
            if (!isset($cart['months'])) {
                $cart['months'] = 12;
            }
            $cart['packages'] = $packages;
            $session->set('cart', $cart);
            $session->save();
        }

        $packages = $session->get('cart')['packages'];

        if (isset($_POST['updateCart']) && !empty($_POST)) {
            //var_dump($_POST);
            $cart = array();
            if (isset($_POST['months']) && is_numeric((int)$_POST['months'])) {
                $cart['months'] = $_POST['months'];
            }
            foreach ($packages as $key => $package) {
                if (isset($_POST['qty_' . $package['identifier']])) {
                    $quantity = $_POST['qty_' . $package['identifier']];
                    if ($quantity == 0) {
                        unset($packages[$key]);
                    } elseif (is_numeric($quantity) && $quantity < 1000) {
                        /////////////
                        try {
                            $package_id = $packages[$key]['package_id'];
                            $subscription_package = $em->createQuery("SELECT a
																FROM AWHSCoreBundle:SubscriptionPackage a
																WHERE a.id='$package_id'")->getSingleResult();

                            foreach ($subscription_package->getProductsPackage() as $product_package) {
                                if ($product_package->getProduct()->getCategory()->getApiName() == "vps") {
                                    $requiredIps = (int)($requiredIps + 1) * $quantity;
                                    if ($requiredIps > count($ips)) {
                                        $this->get('session')->getFlashBag()->add(
                                            'error',
                                            'Désolé, il n\'y a plus assez de stock.'
                                        );
                                        return $this->redirect($this->generateUrl('awhs_core_cart', array()));
                                    }
                                }
                            }
                        } catch (\Exception $e) {
                            $this->get('session')->getFlashBag()->add(
                                'error',
                                'Désolé, le produit que vous tentez d\'ajouter n\'est plus disponible.'
                            );
                            return $this->redirect($this->generateUrl('awhs_core_shop', array()));
                        }
                        ////////////
                        $packages[$key]['quantity'] = $quantity;
                        if (count($packages[$key]['settings']) != $quantity) {
                            $messingSettings = $quantity - count($packages[$key]['settings']);
                            if ($messingSettings > 0) {
                                for ($i = 0; $i <= $messingSettings; $i++) {
                                    $packages[$key]['settings'][] = array();
                                }
                            }
                        }
                        $packages[$key]['settings'] = array_values($packages[$key]['settings']);// NOT SURE
                    }

                }
            }

            $cart['packages'] = $packages;

            $session->set('cart', $cart);
            $session->save();
        }

        $packages = $session->get('cart')['packages'];

        if (!empty($packages)) {
            foreach ($packages as $key => $package) {
                $package_id = $package['package_id'];
                $quantity = $package['quantity'];
                $identifier = $package['identifier'];

                /*try {
                    $subscription_package = $em->createQuery("SELECT a
																FROM AWHSCoreBundle:SubscriptionPackage a
																WHERE a.id='$package_id'")->getSingleResult();

                    foreach ($subscription_package->getProductsPackage() as $product_package) {
                        if ($product_package->getProduct()->getCategory()->getApiName() == "vps") {
                            $requiredIps = (int)($requiredIps + 1)*$quantity;
                            if ($requiredIps > count($ips)) {
                                $error = true;
                                unset($packages[$key]);
                                $this->get('session')->getFlashBag()->add(
                                    'error',
                                    "Désolé, le produit que vous aviez ajouté ({$subscription_package->getName()}) n'est plus en stock."
                                );
                            }
                        }
                    }
                    if($error = true) {
                        $cart['packages'] = $packages;

                        $session->set('cart', $cart);
                        $session->save();
                        return $this->redirect($this->generateUrl('awhs_core_cart', array()));
                    }
                }
                catch(\Exception $e)
                {

                }*/
                $subscription_package = $em->createQuery("SELECT a
																FROM AWHSCoreBundle:SubscriptionPackage a
																WHERE a.id='$package_id'")->getSingleResult();
                $cart_packages[] = array('secure_id' => $identifier,
                    'package' => $subscription_package,
                    'quantity' => $quantity
                );
            }
        }

        return $this->render('AWHSCoreBundle:' . $this->container->getParameter('awhs')['template'] . '/Cart:cart.html.twig', array(
            'user' => $user,
            'cart' => $cart_packages,
            'months' => $session->get('cart')['months'],
        ));
    }

    function generateRandomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function configurationAction(Request $request)
    {
        $user = $this->getUser();


        $session = $request->getSession();
        $packages = $session->get('cart')['packages'];
        $months = $session->get('cart')['months'];

        if (empty($packages)) {
            return $this->redirect($this->generateUrl('awhs_core_cart', array()));
        }

        $em = $this->getDoctrine()->getManager();

        $cart_packages = array();


        if (isset($_POST['updateConfig']) && !empty($_POST)) {
            foreach ($packages as $key => $package) {
                $settings = array();
                if (isset($_POST[$package['identifier']]) && !empty($_POST[$package['identifier']])) {
                    $subscription_package = $em->createQuery("SELECT a
															FROM AWHSCoreBundle:SubscriptionPackage a
															WHERE a.id='{$package['package_id']}'")->getSingleResult();
                    for ($i = 0; $i <= $package['quantity'] - 1; $i++) {
                        $settings[$i] = $_POST[$package['identifier']][$i];

                        //$product_package = $subscription_package->getProductsPackage()[$key];
                        foreach ($subscription_package->getProductsPackage() as $key_product => $product_package) {
                            $p = $product_package->getProduct();
                            $form_fields = $p->getSettingsArray()['form_fields'];
                            //var_dump($form_fields);

                            foreach ($settings[$i] as $key_settings => $values) {
                                //var_dump($values);
                                foreach ($values as $key_name => $name) {
                                    // $exists = false;
                                    foreach ($form_fields as $form_field) {
                                        if ($key_name == $form_field['name']) {
                                            $settings[$i][$key_settings][$key_name] = $name;
                                            //var_dump($form_field['name'] . " => " . $name);
                                            /*
                                            if(isset($form_field['type']) && !empty($form_field['type']))
                                            {
                                                //TODO: Vérifier si le type de champs est valide is_numeric etc...
                                            }
                                            */
                                            //le champs envoyé existe
                                            // $exists = true;
                                            break;
                                        }
                                    }
                                    //Si le champs n'existe pas, on le supprime
                                    // if($exists == false)
                                    // {
                                    // //unset($settings[$i][$key_settings][$key_name]);
                                    // }
                                }
                            }
                        }
                    }
                }
                $packages[$key]['settings'] = $settings;
            }

            $cart = array();
            $cart['packages'] = $packages;
            $cart['months'] = $months;
            $session->set('cart', $cart);


            /*foreach($packages as $key => $package)
            {
                var_dump($package['settings']);
            }
            exit;
            var_dump($_POST);exit;*/
            // var_dump($cart);
            // exit;
            return $this->redirect($this->generateUrl('awhs_core_cart_review', array()));
        }

        $packages = $session->get('cart')['packages'];

        foreach ($packages as $package) {
            $package_id = $package['package_id'];
            $quantity = $package['quantity'];
            $identifier = $package['identifier'];
            if (empty($package['settings'])) {
                $settings = array();
            } else {
                $settings = $package['settings'];
            }

            /**
             * @var \AWHS\CoreBundle\Entity\SubscriptionPackage $subscription_package
             */
            $subscription_package = $em->createQuery("SELECT a
															FROM AWHSCoreBundle:SubscriptionPackage a
															WHERE a.id='$package_id'")->getSingleResult();

            /**
             * @var \AWHS\CoreBundle\Entity\ProductPackage $pp
             */
            /*foreach($subscription_package->getProductsPackage() as $pp) {
                $api = $this->container->get($pp->getProduct()->getCategory()->getApiName());
                $pp->setApi($api);
            }*/

            $cart_packages[] = array('secure_id' => $identifier,
                'package' => $subscription_package,
                'quantity' => $quantity,
                'settings' => $settings
            );
        }

        return $this->render('AWHSCoreBundle:' . $this->container->getParameter('awhs')['template'] . '/Cart:configuration.html.twig', array(
            'user' => $user,
            'cart' => $cart_packages,
        ));
    }

    public function reviewAction(Request $request)
    {
        $user = $this->getUser();


        $session = $request->getSession();
        $packages = $session->get('cart')['packages'];
        $months = $session->get('cart')['months'];

        if (empty($packages)) {
            return $this->redirect($this->generateUrl('awhs_core_cart', array()));
        }
        if (empty($months)) {
            return $this->redirect($this->generateUrl('awhs_core_cart', array()));
        }

        $em = $this->getDoctrine()->getManager();

        $cart_packages = array();

        $subtotal = 0;

        if (isset($_POST['buy']) && !empty($_POST)) {

            //Redirige l'utilisateur sur la page de connexion
            if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
                return $this->redirect($this->generateUrl('fos_user_security_login', array()));
            }

            $subscriptions = array();
            $subscriptionProducts = array();
            $invoice = new Invoice();
            $invoice->setUser($user);
            $invoice->setDate(new \Datetime());
            $invoice->setStatus(0);

            $details = array('rebate' => 0,
                'subtotal' => 0,
                'taxe' => 0.000,
                'duration' => $months);


            $details['packages'] = array();
            foreach ($packages as $key => $package) {
                $settings = array();
                $package_settings = $package['settings'];
                $invoice_products = array();
                $package_id = $package['package_id'];
                $subscription_package = $em->createQuery("SELECT a
															FROM AWHSCoreBundle:SubscriptionPackage a
															WHERE a.id='$package_id'")->getSingleResult();
                $products = array();
                foreach ($subscription_package->getProductsPackage() as $subscriptionProduct) {

                    $product = $subscriptionProduct->getProduct();
                    $products[] = $product;
                    $invoice_products[] = array("name" => $product->getName(),
                        "category" => $product->getCategory()->getName());
                }
                $p = array("name" => $subscription_package->getName(),
                    "price" => $subscription_package->getPrice(),
                    "quantity" => $package['quantity'],
                    "products" => $invoice_products);

                $details['packages'][] = $p;

                $subtotal += ($subscription_package->getPrice() * $package['quantity']) * $months;

                for ($i = 0; $i <= $package['quantity'] - 1; $i++) {
                    if (!empty($package_settings))
                        $settings[$i] = $package_settings[$i];

                    $subscription = new Subscription();
                    $subscription->addInvoice($invoice);
                    $subscription->setUser($user);
                    $subscription->setSubscriptionPackage($subscription_package);
                    $subscription->setStartDate(null);
                    $subscription->setEndDate(null);
                    $subscription->setActive(0);
                    for ($a = 0; $a <= count($products) - 1; $a++) {
                        //À mettre dans les paramètres du produit
                        $form_fields = $products[$a]->getSettingsArray();
                        unset($form_fields['form_fields']);
                        if (!empty($package_settings))
                            $form_fields['config'] = $settings[$i][$a];
                        //var_dump($form_fields);
                        $subscriptionProduct = new SubscriptionProduct();
                        $subscriptionProduct->setProduct($products[$a]);
                        $subscriptionProduct->setParameters(json_encode($form_fields));
                        //$subscriptionProduct->setMappingId(1);
                        $subscriptionProduct->setSubscription($subscription);
                        $subscriptionProducts[] = $subscriptionProduct;
                    }
                    /*foreach($products as $p)
                    {
                        $form_fields = $p->getSettingsArray()['form_fields'];

                        //var_dump($settings[$i]);
                        $subscriptionProduct = new SubscriptionProduct();
                        $subscriptionProduct->setProduct($p);
                        $subscriptionProduct->setParameters(json_encode(array()));
                        //$subscriptionProduct->setMappingId(1);
                        $subscriptionProduct->setSubscription($subscription);
                        $subscriptionProducts[] = $subscriptionProduct;
                    }*/
                    $subscriptions[] = $subscription;
                }
            }

            $details['subtotal'] = $subtotal;
            $invoice->setDetails(json_encode($details));
            $em->persist($invoice);

            foreach ($subscriptions as $sub) {
                $em->persist($sub);
            }
            foreach ($subscriptionProducts as $subP) {
                $em->persist($subP);
            }
            $em->flush();
            // exit;
            $cart = array();
            $session->set('cart', $cart);

            if ($subtotal == 0) {
                $details__ = $invoice->getDetails();
                $invoice->setStatus(1);
                $em->persist($invoice);

                $start_date = new \Datetime();
                $end_date = new \Datetime();
                if ($details__['duration'] > 12) {
                    $end_date->add(new \DateInterval("P1Y"));
                } else {
                    $end_date->add(new \DateInterval("P{$details__['duration']}M"));
                }


                // var_dump($subscriptions);
                $taskType = $em->createQuery("SELECT tt
													FROM AWHSTaskBundle:TaskType tt
													WHERE tt.type='subscription_create_services'")->getSingleResult();

                foreach ($subscriptions as $subscription__) {

                    if ($subscription__->getStartDate() == null) {

                        $subscription__->setStartDate($start_date);
                        $subscription__->setEndDate($end_date);

                        $em->persist($subscription__);

                        $task = new Task();
                        $task->setUser($user);
                        $task->setType($taskType);
                        $task->setBegin(new \DateTime());
                        $task->setEnd(null);
                        $task->setDescription("");

                        $taskType = new TaskSubscription();
                        $taskType->setTask($task);
                        $taskType->setSubscription($subscription__);

                        $em->persist($task);
                        $em->persist($taskType);

                    }

                }

                $em->flush();

            }

            return $this->redirect($this->generateUrl('awhs_core_invoice', array('invoice_id' => $invoice->getId())));
        }

        foreach ($packages as $package) {
            $package_id = $package['package_id'];
            $quantity = $package['quantity'];
            $identifier = $package['identifier'];
            if (empty($package['settings'])) {
                $settings = array();
            } else {
                $settings = $package['settings'];
            }

            $subscription_package = $em->createQuery("SELECT a
															FROM AWHSCoreBundle:SubscriptionPackage a
															WHERE a.id='$package_id'")->getSingleResult();

            $cart_packages[] = array('secure_id' => $identifier,
                'package' => $subscription_package,
                'quantity' => $quantity,
                'settings' => $settings
            );
        }

        return $this->render('AWHSCoreBundle:' . $this->container->getParameter('awhs')['template'] . '/Cart:review.html.twig', array(
            'user' => $user,
            'cart' => $cart_packages,
            'months' => $session->get('cart')['months'],
        ));
    }

    function updateQuantity()
    {

    }

}
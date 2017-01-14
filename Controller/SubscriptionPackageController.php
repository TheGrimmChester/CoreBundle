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


use AWHS\CoreBundle\Entity\ProductPackage;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;


class SubscriptionPackageController extends Controller
{
    public function subscriptionPackagesAction()
    {
        // On récupère l'entité correspondant à l'id $id
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }

        $user = $this->getUser();

        $em = $this->getDoctrine()->getManager();

        $subscriptionPackages = $em->getRepository('AWHSCoreBundle:SubscriptionPackage')->findAll();


        return $this->render('AWHSCoreBundle:' . $this->container->getParameter('awhs')['template'] . '/SubscriptionPackage:subscriptionPackages.html.twig', array(
            'subscriptionPackages' => $subscriptionPackages,
            'user' => $user,
        ));
    }

    public function subscriptionPackageAction(Request $request, $subscription_packages_id)
    {
        // On récupère l'entité correspondant à l'id $id
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }

        $user = $this->getUser();

        $em = $this->getDoctrine()->getManager();

        $subscriptionPackage = $em->getRepository('AWHSCoreBundle:SubscriptionPackage')->findOneById($subscription_packages_id);
        $products = $em->getRepository('AWHSCoreBundle:Product')->findAll();

        if (empty($subscriptionPackage)) {
            $this->get('session')->getFlashBag()->add(
                'error', 'Aucun package n\'a été trouvé.'
            );
            return $this->redirect($this->generateUrl('awhs_core_subscription_packages', array()));
        }

        $formBuilder = $this->createFormBuilder($subscriptionPackage);
        $formBuilder
            ->add('name', TextType::class, array(
                    'label' => 'Nom')
            )
            ->add('start_date', DateTimeType::class, array(
                    'label' => 'Date de début')
            )
            ->add('end_date', DateTimeType::class, array(
                    'label' => 'Date de fin')
            )
            ->add('price', MoneyType::class, array(
                    'label' => 'Prix')
            )
            ->add('position', IntegerType::class, array(
                    'label' => 'Position')
            )
            ->add('active', ChoiceType::class, array(
                    'label' => 'Statut',
                    'choices' => array(
                        'Actif' => true,
                        'Inactif' => false,
                    ))
            );

        $form = $formBuilder->getForm();

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);

            if (isset($_POST['delete_product'])) {

                $product_package = $em->getRepository('AWHSCoreBundle:ProductPackage')->findOneById($_POST['delete_product']);
                $em->remove($product_package);
                $em->flush();
                $this->get('session')->getFlashBag()->add(
                    'notice', 'Produit supprimé avec succès.'
                );
                return $this->redirect($this->generateUrl('awhs_core_subscription_package', array("subscription_packages_id" => $subscription_packages_id)));
            }

            if (isset($_POST['quantity']) && isset($_POST['update'])) {

                $product_package = $em->getRepository('AWHSCoreBundle:ProductPackage')->findOneById($_POST['update']);
                $product_package->setQuantity($_POST['quantity']);
                $em->persist($product_package);
                $em->flush();
                $this->get('session')->getFlashBag()->add(
                    'notice', 'Enregistrement modifié avec succès.'
                );
                return $this->redirect($this->generateUrl('awhs_core_subscription_package', array("subscription_packages_id" => $subscription_packages_id)));
            }

            if (isset($_POST['quantity']) && isset($_POST['include'])) {
                $product = $em->getRepository('AWHSCoreBundle:Product')->findOneById($_POST['include']);
                if ($product != null) {
                    try {
                        $product_package = new ProductPackage();
                        $product_package->setSubscriptionPackage($subscriptionPackage);
                        $product_package->setProduct($product);
                        $product_package->setQuantity($_POST['quantity']);
                        $em->persist($product_package);
                        $em->flush();
                        $this->get('session')->getFlashBag()->add(
                            'notice', 'Enregistrement modifié avec succès.'
                        );
                    } catch (\Exception $e) {
                        $this->get('session')->getFlashBag()->add(
                            'error', 'Le produit est déjà inclu.'
                        );
                    }
                } else {
                    $this->get('session')->getFlashBag()->add(
                        'error', 'Le produit n\'existe pas.'
                    );
                }
                return $this->redirect($this->generateUrl('awhs_core_subscription_package', array("subscription_packages_id" => $subscription_packages_id)));
            }

            if ($form->isValid()) {
                $this->get('session')->getFlashBag()->add(
                    'notice', 'Enregistrement modifié avec succès.'
                );
                $subscriptionPackage = $form->getData();

                $em->persist($subscriptionPackage);
                $em->flush();

                return $this->redirect($this->generateUrl('awhs_core_subscription_packages', array()));
            }
        }

        return $this->render('AWHSCoreBundle:' . $this->container->getParameter('awhs')['template'] . '/SubscriptionPackage:subscriptionPackage.html.twig', array(
            'subscriptionPackage' => $subscriptionPackage,
            'user' => $user,
            'products' => $products,
            'form' => $form->createView(),
        ));
    }
}
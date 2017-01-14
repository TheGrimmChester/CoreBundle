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


use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class SubscriptionProductController extends Controller
{
    public function adminSubscriptionProductAction(Request $request, $subscription_product_id)
    {
        // On récupère l'entité correspondant à l'id $id
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }

        $user = $this->getUser();

        $em = $this->getDoctrine()->getManager();

        /** @var \AWHS\CoreBundle\Entity\SubscriptionProduct */
        $subscription_product = $em->getRepository('AWHSCoreBundle:SubscriptionProduct')->findOneById($subscription_product_id);

        if (empty($subscription_product)) {
            return new Response("Aucun trouvé.");
        }

        $formBuilder = $this->createFormBuilder($subscription_product);
        $formBuilder
            ->add('settings', TextareaType::class, array(
                    'label' => 'Paramètres')
            )
            ->add('mapping_id', IntegerType::class, array(
                    'label' => 'Mapping ID'
                , 'required' => false)
            );

        $oldSettings = $subscription_product->getSettingsArray();
        /*$oldSettings = $subscription_product->getProduct()->getSettingsArray();
        if(isset($oldSettings["form_fields"]))
        {
            $oldSettings["config"] = $oldSettings["form_fields"];
            unset($oldSettings["form_fields"]);
            foreach($oldSettings["config"] as $key => $config){
                foreach($oldSettings["config"][$key] as $key2 => $val) {
                    if ($key2 != "name" && $key2 != "value") {
                        unset($oldSettings["config"][$key][$key2]);
                    }
                }
                $temp_key = $oldSettings["config"][$key]["name"];
                $temp_value = $oldSettings["config"][$key]["value"];
                unset($oldSettings["config"][$key]);
                $oldSettings["config"][][$temp_key] = $temp_value;
            }
            $oldSettings["config"] = array_values($oldSettings["config"]);
        }

        echo json_encode($oldSettings);exit;*/
        $form = $formBuilder->getForm();

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);

            if ($form->isValid()) {

                //Vérifier si le JSON envoyé contient des erreurs
                if (!$this->has_json_data($form->getData()->getSettings())) {
                    $this->get('session')->getFlashBag()->add(
                        'error', 'Le JSON n\'est pas valide.'
                    );
                    return $this->redirect($this->generateUrl('awhs_core_admin_subscription_product', array('subscription_product_id' => $subscription_product_id)));
                }

                $treeWalker = $this->container->get("awhs_core.tree_walker");
                $treeWalker->setConfig(array(
                    "debug" => false,
                    "returntype" => "array"));

                $treeWalkerResult = $treeWalker->getdiff($form->getData()->getSettingsArray(), $oldSettings);
                if (count($treeWalkerResult["removed"]) > 0 || count($treeWalkerResult["new"]) > 0) {
                    $removed_string = "";
                    $new_string = "";
                    if (count($treeWalkerResult["removed"]) > 0) {
                        $removed_string = "Removed: ";
                        foreach ($treeWalkerResult["removed"] as $key => $removed) {
                            $removed_string .= $key . ":" . $removed . ", ";
                        }
                    }

                    if (count($treeWalkerResult["new"]) > 0) {
                        $new_string = "New: ";
                        foreach ($treeWalkerResult["new"] as $key => $new) {
                            $new_string .= $key . ":" . $new . ", ";
                        }
                    }

                    $this->get('session')->getFlashBag()->add(
                        'error', 'Altération de la structure : ' . $removed_string . $new_string
                    );
                    return $this->redirect($this->generateUrl('awhs_core_admin_subscription_product', array('subscription_product_id' => $subscription_product_id)));
                }

                $this->get('session')->getFlashBag()->add(
                    'notice', 'Enregistrement modifié avec succès.'
                );
                $subscription_product = $form->getData();

                $em->persist($subscription_product);
                $em->flush();

                return $this->redirect($this->generateUrl('awhs_core_admin_subscription_product', array('subscription_product_id' => $subscription_product_id)));
            }
        }

        return $this->render('AWHSCoreBundle:' . $this->container->getParameter('awhs')['template'] . '/SubscriptionProduct:subscription_product.html.twig', array(
            'product' => $subscription_product,
            'user' => $user,
            'form' => $form->createView(),
        ));
    }

    function has_json_data($string)
    {
        $array = json_decode($string, true);
        return !empty($string) && is_string($string) && is_array($array) && !empty($array) && json_last_error() == 0;
    }

    public function productAction(Request $request, $product_id)
    {
        // On récupère l'entité correspondant à l'id $id
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }

        $user = $this->getUser();

        $em = $this->getDoctrine()->getManager();

        $product = $em->getRepository('AWHSCoreBundle:Product')->findOneById($product_id);

        if (empty($product)) {
            return new Response("Aucune Catégorie n'a été trouvée.");
        }

        $formBuilder = $this->createFormBuilder($product);
        $formBuilder
            ->add('name', TextType::class, array(
                    'label' => 'Nom')
            )
            ->add('category', EntityType::class, array(
                'label' => 'Catégorie',
                'class' => 'AWHSCoreBundle:ProductCategory',
                'expanded' => false,
                'multiple' => false
            ))
            ->add('settings', TextareaType::class, array(
                    'label' => 'Paramètres')
            )
            ->add('active', TextType::class, array(
                    'label' => 'Statut')
            );

        $form = $formBuilder->getForm();

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $this->get('session')->getFlashBag()->add(
                    'notice', 'Enregistrement modifié avec succès.'
                );
                $product = $form->getData();

                $em->persist($product);
                $em->flush();

                return $this->redirect($this->generateUrl('awhs_core_products', array()));
            }
        }

        return $this->render('AWHSCoreBundle:' . $this->container->getParameter('awhs')['template'] . '/Product:product.html.twig', array(
            'product' => $product,
            'user' => $user,
            'form' => $form->createView(),
        ));
    }
}
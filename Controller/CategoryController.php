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
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class CategoryController extends Controller
{
    public function categoriesAction()
    {
        // On récupère l'entité correspondant à l'id $id
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }

        $user = $this->getUser();

        $em = $this->getDoctrine()->getManager();

        $categories = $em->getRepository('AWHSCoreBundle:ProductCategory')->findAll();


        return $this->render('AWHSCoreBundle:' . $this->container->getParameter('awhs')['template'] . '/Category:categories.html.twig', array(
            'categories' => $categories,
            'user' => $user,
        ));
    }

    public function categoryAction(Request $request, $category_id)
    {
        // On récupère l'entité correspondant à l'id $id
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }

        $user = $this->getUser();


        $em = $this->getDoctrine()->getManager();

        $category = $em->getRepository('AWHSCoreBundle:ProductCategory')->findOneById($category_id);

        if (empty($category)) {
            return new Response("Aucune Catégorie n'a été trouvée.");
        }

        $formBuilder = $this->createFormBuilder($category);
        $formBuilder
            ->add('name', TextType::class, array(
                    'label' => 'Nom')
            )
            ->add('prefix', TextType::class, array(
                    'label' => 'Préfix')
            )
            ->add('apiname', TextType::class, array(
                    'label' => 'API')
            )
            ->add('entity', TextType::class, array(
                    'label' => 'Entity')
            )
            ->add('settings', TextareaType::class, array(
                    'label' => 'Paramètres')
            );

        $form = $formBuilder->getForm();

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $this->get('session')->getFlashBag()->add(
                    'notice', 'Enregistrement modifié avec succès.'
                );
                $category = $form->getData();

                $em->persist($category);
                $em->flush();

                return $this->redirect($this->generateUrl('awhs_core_categories', array()));
            }
        }

        return $this->render('AWHSCoreBundle:' . $this->container->getParameter('awhs')['template'] . '/Category:category.html.twig', array(
            'category' => $category,
            'user' => $user,
            'form' => $form->createView(),
        ));
    }

}
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
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class SubscriptionController extends Controller
{
    public function AdminSubscriptionsAction()
    {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }

        $user = $this->getUser();


        $em = $this->getDoctrine()->getManager();

        $subscriptions = $em->getRepository('AWHSCoreBundle:Subscription')->findBy(array(), array('active' => 'DESC'));

        return $this->render('AWHSCoreBundle:' . $this->container->getParameter('awhs')['template'] . '/Subscription/Admin:subscriptions.html.twig', array(
            'subscriptions' => $subscriptions,
            'user' => $user,
        ));
    }

    /**
     * @return Response
     */
    public function subscriptionsAction()
    {
        // On récupère l'entité correspondant à l'id $id
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }

        $user = $this->getUser();

        $em = $this->getDoctrine()->getManager();

        /** @array \AWHS\CoreBundle\Entity\Subscription */
        $subscriptions = $em->getRepository('AWHSCoreBundle:Subscription')
            ->findBy(
                array('user' => $user),
                array('active' => 'DESC', 'id' => 'ASC')
            );

        return $this->render('AWHSCoreBundle:' . $this->container->getParameter('awhs')['template'] . '/Subscription:subscriptions.html.twig', array(
            'subscriptions' => $subscriptions,
            'user' => $user,
        ));
    }

    public function subscriptionAction($subscription_id)
    {
        // On récupère l'entité correspondant à l'id $id
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }

        $user = $this->getUser();


        $em = $this->getDoctrine()->getManager();

        $subscription = $em->getRepository('AWHSCoreBundle:Subscription')->findOneById($subscription_id);

        if (empty($subscription)) {
            return new Response("Aucune souscription n'a été trouvée.");
        }

        return $this->render('AWHSCoreBundle:' . $this->container->getParameter('awhs')['template'] . '/Subscription:subscription.html.twig', array(
            'subscription' => $subscription,
            'user' => $user,
        ));
    }

    public function renew_step_0Action(Request $request, $subscription_id)
    {
        // On récupère l'entité correspondant à l'id $id
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }

        $user = $this->getUser();


        $em = $this->getDoctrine()->getManager();

        $subscription = $em->getRepository('AWHSCoreBundle:Subscription')->findOneById($subscription_id);

        if (empty($subscription)) {
            return new Response("Aucune souscription n'a été trouvée.");
        }

        $session = $request->getSession();
        $renew = $session->get('renew');
        if (empty($renew['duration'])) {
            $renew = array('duration' => 12);
            $session->set('renew', $renew);
        }

        if (isset($_POST['months']) && is_numeric((int)$_POST['months'])) {
            $months = $_POST['months'];
            $renew = array('duration' => $months);
            $session->set('renew', $renew);
        }

        return $this->render('AWHSCoreBundle:' . $this->container->getParameter('awhs')['template'] . '/Subscription:renew_0.html.twig', array(
            'subscription' => $subscription,
            'months' => $renew['duration'],
            'user' => $user,
        ));
    }

    public function renew_step_1Action(Request $request, $subscription_id)
    {
        // On récupère l'entité correspondant à l'id $id
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }

        $user = $this->getUser();


        $em = $this->getDoctrine()->getManager();

        $subscription = $em->getRepository('AWHSCoreBundle:Subscription')->findOneById($subscription_id);

        if (empty($subscription)) {
            return new Response("Aucune souscription n'a été trouvée.");
        }

        $session = $request->getSession();
        $renew = $session->get('renew');
        if (empty($renew)) {
            return $this->redirect($this->generateUrl('awhs_core_subscription_renew_step_0', array('subscription_id' => $subscription_id)));
        }

        if (empty($renew['duration'])) {
            $renew = array('duration' => 12);
            $session->set('renew', $renew);
        }

        return $this->render('AWHSCoreBundle:' . $this->container->getParameter('awhs')['template'] . '/Subscription:renew_1.html.twig', array(
            'subscription' => $subscription,
            'months' => $renew['duration'],
            'user' => $user,
        ));
    }

    public function renew_step_2Action(Request $request, $subscription_id)
    {
        if ($request->isMethod('GET')) {
            return $this->redirect($this->generateUrl('awhs_core_subscription_renew_step_0', array('subscription_id' => $subscription_id)));
        }

        // On récupère l'entité correspondant à l'id $id
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }

        $user = $this->getUser();


        $em = $this->getDoctrine()->getManager();

        $subscription = $em->getRepository('AWHSCoreBundle:Subscription')->findOneById($subscription_id);

        if (empty($subscription)) {
            return new Response("Aucune souscription n'a été trouvée.");
        }

        $session = $request->getSession();
        $renew = $session->get('renew');
        if (empty($renew)) {
            return $this->redirect($this->generateUrl('awhs_core_subscription_renew_step_0', array('subscription_id' => $subscription_id)));
        }

        if (empty($renew['duration'])) {
            return $this->redirect($this->generateUrl('awhs_core_subscription_renew_step_0', array('subscription_id' => $subscription_id)));
        }

        //Créer la facture

        $invoice = new Invoice();
        $invoice->setUser($user);
        $invoice->setDate(new \Datetime());
        $invoice->setStatus(0);

        $details = array('rebate' => 0,
            'subtotal' => 0,
            'taxe' => 0.000,
            'duration' => $renew['duration']);

        $details['packages'] = array();

        $products = array();
        foreach ($subscription->getSubscriptionPackage()->getProductsPackage() as $subscriptionProduct) {

            $product = $subscriptionProduct->getProduct();
            $products[] = $product;
            $invoice_products[] = array("name" => $product->getName(),
                "category" => $product->getCategory()->getName());
        }
        $package = array("name" => $subscription->getSubscriptionPackage()->getName(),
            "price" => $subscription->getSubscriptionPackage()->getPrice(),
            "quantity" => 1,
            "products" => $invoice_products);

        $details['packages'][] = $package;

        $details['subtotal'] = $subscription->getSubscriptionPackage()->getPrice();
        $invoice->setDetails(json_encode($details));
        $em->persist($invoice);
        $em->flush();

        $subscription->addInvoice($invoice);
        $em->persist($subscription);
        $em->flush();
        $session->set('renew', array());

        //Rediriger sur la facture
        return $this->redirect($this->generateUrl('awhs_core_invoice', array('invoice_id' => $invoice->getId())));
    }
}
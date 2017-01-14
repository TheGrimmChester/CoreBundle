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

use Acme\PaymentBundle\Model\PaymentDetails;
use AWHS\TaskBundle\Entity\Task;
use AWHS\TaskBundle\Entity\TaskSubscription;
use Payum\Core\Registry\RegistryInterface;
use Payum\Core\Security\GenericTokenFactoryInterface;
use Payum\Paypal\ExpressCheckout\Nvp\Api;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Extra;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


//pour les paiements

class InvoiceController extends Controller
{
    public function AdminInvoicesAction()
    {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }

        $user = $this->getUser();


        $em = $this->getDoctrine()->getManager();

        $invoices = $em->getRepository('AWHSCoreBundle:Invoice')->findAll();

        return $this->render('AWHSCoreBundle:' . $this->container->getParameter('awhs')['template'] . '/Invoice:Admin/invoices.html.twig', array(
            'invoices' => $invoices,
            'user' => $user,
        ));
    }

    public function InvoicesAction()
    {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }

        $user = $this->getUser();


        $em = $this->getDoctrine()->getManager();

        $invoices = $em->getRepository('AWHSCoreBundle:Invoice')->findByUser($user);

        return $this->render('AWHSCoreBundle:' . $this->container->getParameter('awhs')['template'] . '/Invoice:invoices.html.twig', array(
            'invoices' => $invoices,
            'user' => $user,
        ));
    }

    public function AdminInvoiceAction($invoice_id)
    {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }

        $user = $this->getUser();


        $em = $this->getDoctrine()->getManager();

        $invoice = $em->getRepository('AWHSCoreBundle:Invoice')->findOneById($invoice_id);

        if (empty($invoice)) {
            return new Response("Aucune souscription n'a été trouvée.");
        }

        return $this->render('AWHSCoreBundle:' . $this->container->getParameter('awhs')['template'] . '/Invoice:invoice.html.twig', array(
            'invoice' => $invoice,
            'user' => $user,
        ));
    }

    public function InvoiceAction($invoice_id)
    {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }

        $user = $this->getUser();


        $em = $this->getDoctrine()->getManager();

        $invoice = $em->getRepository('AWHSCoreBundle:Invoice')->findOneById($invoice_id);

        if (empty($invoice) || $invoice->getUser()->getId() != $user->getId()) {
            return new Response("Aucune souscription n'a été trouvée.");
        }

        /*$details = $invoice->getDetails();

        $invoice->getSubscriptions();

        $subscriptions = $invoice->getSubscriptions();

        $details['packages'] = array();
        foreach($subscriptions as $subscription)
        {
            $products = array();
            foreach($subscription->getSubscriptionProducts() as $subscriptionProduct)
            {
                $product = $subscriptionProduct->getProduct();
                $products[] = array("name" => $product->getName(),
                                    "category" => $product->getCategory()->getName());
            }
            $package = array("name" => $subscription->getSubscriptionPackage()->getName(),
                             "price" => $subscription->getSubscriptionPackage()->getPrice(),
                             "quantity" => 1,
                             "products" => $products);


            $details['packages'][] = $package;
        }

        $invoice->setDetails(json_encode($details));
        $em->persist($invoice);
        $em->flush();*/

        if (empty($invoice)) {
            return new Response("Aucune souscription n'a été trouvée.");
        }

        return $this->render('AWHSCoreBundle:' . $this->container->getParameter('awhs')['template'] . '/Invoice:invoice.html.twig', array(
            'invoice' => $invoice,
            'user' => $user,
        ));
    }

    public function PrintInvoiceAction($invoice_id)
    {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }

        $user = $this->getUser();


        $em = $this->getDoctrine()->getManager();

        $invoice = $em->getRepository('AWHSCoreBundle:Invoice')->findOneById($invoice_id);

        if (empty($invoice)) {
            return new Response("Aucune souscription n'a été trouvée.");
        }

        return $this->render('AWHSCoreBundle:' . $this->container->getParameter('awhs')['template'] . '/Invoice:invoice-print.html.twig', array(
            'invoice' => $invoice,
            'user' => $user,
        ));
    }

    public function PayAction(Request $request, $invoice_id)
    {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }

        $user = $this->getUser();


        $em = $this->getDoctrine()->getManager();

        $invoice = $em->getRepository('AWHSCoreBundle:Invoice')->findOneById($invoice_id);

        if (empty($invoice)) {
            return new Response("Aucune souscription n'a été trouvée.");
        } else {
            $status = $invoice->getStatus();
            if ($status == 1) {
                return new Response("Cette facture a déjà été payée.");
            }
        }

        if (isset($_POST['payment_name']) && !empty($_POST['payment_name'])) {
            //Pour chaque méthode de paiement
            switch ($_POST['payment_name']) {
                case 'paypal_express_checkout': {
                    $details = $invoice->getDetails();
                    $taxe = $details['taxe'];
                    $subtotal = $details['subtotal'];
                    $total = number_format($subtotal + ($subtotal * $taxe), 2);


                    $paymentName = 'paypal_express_checkout_and_doctrine_orm';
                    $storage = $this->getPayum()->getStorage('Acme\PaymentBundle\Entity\PaymentDetails');
                    /** @var $paymentDetails PaymentDetails */
                    $paymentDetails = $storage->create();
                    $paymentDetails->setUser($user);
                    $paymentDetails->setInvoice($invoice);
                    $paymentDetails['PAYMENTREQUEST_0_CURRENCYCODE'] = "CAD";
                    $paymentDetails['PAYMENTREQUEST_0_AMT'] = $total; //Prix
                    $paymentDetails['NOSHIPPING'] = Api::NOSHIPPING_NOT_DISPLAY_ADDRESS;
                    $paymentDetails['REQCONFIRMSHIPPING'] = Api::REQCONFIRMSHIPPING_NOT_REQUIRED;
                    $paymentDetails['L_PAYMENTREQUEST_0_ITEMCATEGORY0'] = Api::PAYMENTREQUEST_ITERMCATEGORY_DIGITAL;
                    $paymentDetails['L_PAYMENTREQUEST_0_AMT0'] = $total; //Prix
                    $paymentDetails['L_PAYMENTREQUEST_0_QTY0'] = 1; //Prix
                    $paymentDetails['L_PAYMENTREQUEST_0_NAME0'] = "Facture {$invoice->getId()}"; //Facture
                    $paymentDetails['L_PAYMENTREQUEST_0_DESC0'] = ""; //description
                    $storage->update($paymentDetails);

                    $captureToken = $this->getTokenFactory()->createCaptureToken(
                        $paymentName,
                        $paymentDetails,
                        'acme_payment_details_view'
                    );

                    $paymentDetails['RETURNURL'] = $captureToken->getTargetUrl();
                    $paymentDetails['CANCELURL'] = $captureToken->getTargetUrl();
                    $paymentDetails['INVNUM'] = $paymentDetails->getId();
                    $storage->update($paymentDetails);

                    return $this->redirect($captureToken->getTargetUrl());

                    break;
                }

                case 'credit': {
                    $details = $invoice->getDetails();
                    $taxe = $details['taxe'];
                    $subtotal = $details['subtotal'];
                    $total = number_format($subtotal + ($subtotal * $taxe), 2);

                    $user_money = $user->getMoney();
                    if ((float)$user_money < (float)$total) {
                        $session = $request->getSession();
                        $session->getFlashBag()->add('error', 'La transaction à échoué, car votre solde est insuffisant.');
                    } else {
                        $status = $invoice->getStatus();
                        $user->setMoney(((float)$user_money - (float)$total));
                        if ($status == 0) {
                            $details = $invoice->getDetails();
                            $invoice->setStatus(1);
                            $em->persist($invoice);

                            $start_date = new \Datetime();
                            $end_date = new \Datetime();
                            $end_date->add(new \DateInterval("P{$details['duration']}M"));

                            $taskType = $em->createQuery("SELECT tt
													FROM AWHSTaskBundle:TaskType tt
													WHERE tt.type='subscription_create_services'")->getSingleResult();
                            $subscriptions = $invoice->getSubscriptions();
                            foreach ($subscriptions as $subscription) {
                                if ($subscription->getStartDate() == null) {
                                    $subscription->setStartDate($start_date);
                                    $subscription->setEndDate($end_date);
                                    $em->persist($subscription);

                                    $task = new Task();
                                    $task->setUser($subscription->getUser());
                                    $task->setType($taskType);
                                    $task->setBegin(new \DateTime());
                                    $task->setEnd(null);
                                    $task->setDescription("");

                                    $taskTypeSubscription = new TaskSubscription();
                                    $taskTypeSubscription->setTask($task);
                                    $taskTypeSubscription->setSubscription($subscription);

                                    $em->persist($task);
                                    $em->persist($taskTypeSubscription);
                                } else {
                                    //Ajouter le nouveau temps en plus du temps restant
                                    $start_date = new \Datetime($subscription->getEndDate()->format('Y-m-d H:i:s'));
                                    $end_date = new \Datetime($subscription->getEndDate()->format('Y-m-d H:i:s'));
                                    $end_date->add(new \DateInterval("P{$details['duration']}M"));
                                    $subscription->setStartDate($start_date);
                                    $subscription->setEndDate($end_date);
                                    $em->persist($subscription);
                                }
                            }
                            $em->persist($user);
                            $em->flush();
                            //Redirection
                            return $this->redirect($this->generateUrl('awhs_core_subscriptions'));
                        }
                    }

                    break;
                }
            }
        }

        return $this->render('AWHSCoreBundle:' . $this->container->getParameter('awhs')['template'] . '/Invoice:pay.html.twig', array(
            'invoice' => $invoice,
            'user' => $user,
        ));
    }

    /**
     * @return RegistryInterface
     */
    protected function getPayum()
    {
        return $this->get('payum');
    }

    /**
     * @return GenericTokenFactoryInterface
     */
    protected function getTokenFactory()
    {
        return $this->get('payum.security.token_factory');
    }
}
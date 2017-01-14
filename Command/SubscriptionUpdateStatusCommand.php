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

namespace AWHS\CoreBundle\Command;


use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class SubscriptionUpdateStatusCommand extends ContainerAwareCommand
{


    protected function configure()
    {
        $this
            ->setName('subscription:update:status')
            ->setDescription('Met a jour le statut des souscriptions');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();

        try {
            $subscriptions = $em->createQuery("
				SELECT s
				FROM AWHSCoreBundle:Subscription s
				WHERE s.active='1'")->getResult();

            foreach ($subscriptions as $subscription) {
                $subscription_products = $subscription->getSubscriptionProducts();

                $nbProducts = count($subscription_products);

                if (!empty($subscription_products)) {
                    /**
                     * @var \AWHS\CoreBundle\Entity\SubscriptionProduct $subscription_product
                     */
                    foreach ($subscription_products as $subscription_product) {
                        //Si le produit a été supprimé
                        if ($subscription_product->getServer() != null && $subscription_product->getMappingId() == null) {
                            $nbProducts--;
                        }
                    }

                    //Tous les produits sont supprimés, la souscription n'est plus active
                    if ($nbProducts <= 0) {
                        $subscription->setActive(0);
                        $em->persist($subscription);
                        $em->flush();
                    }
                }
            }
        } catch (\Exception $ex) {

        }

    }
}

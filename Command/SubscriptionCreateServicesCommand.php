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


class SubscriptionCreateServicesCommand extends ContainerAwareCommand
{


    protected function configure()
    {
        $this
            ->setName('subscription:create:services')
            ->setDescription('Cr�er les services');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();
        $taskType = $em->createQuery("SELECT tt
												FROM AWHSTaskBundle:TaskType tt
												WHERE tt.type='subscription_create_services'")->getSingleResult();
        $tasks = $em->createQuery("
			SELECT t
			FROM AWHSTaskBundle:Task t
			WHERE t.done='0' AND t.locked='0' AND t.type='{$taskType->getId()}'")->getResult();
        foreach ($tasks as $task) {

            $taskSubscription = $em->createQuery("SELECT tt
												FROM AWHSTaskBundle:TaskSubscription tt
												WHERE tt.task='{$task->getId()}'")->getSingleResult();
            $task->setLocked(1);
            $em->persist($task);
            $em->flush();
            try {
                $subscription = $em->createQuery("
				SELECT s
				FROM AWHSCoreBundle:Subscription s
				WHERE s.active='0'
				AND s.start_date IS NOT NULL
				AND s.id = '{$taskSubscription->getSubscription()->getId()}'
				")->getSingleResult();

                $subscription_products = $subscription->getSubscriptionProducts();

                if (empty($subscription_products)) {
                    throw new \Exception("Empty");
                }

                /**
                 * @var \AWHS\CoreBundle\Entity\SubscriptionProduct $subscription_product
                 */
                foreach ($subscription_products as $subscription_product) {
                    echo $subscription_product->getProduct()->getName() . "\n";
                    echo $subscription_product->getProduct()->getCategory()->getPrefix() . "\n";

                    if ($subscription_product->getServer() == null && $subscription_product->getMappingId() == null) {

                        //Execute la creation du produit
                        exec("php /usr/local/awhspanel/panel/bin/console subscription:create:{$subscription_product->getProduct()->getCategory()->getPrefix()} {$subscription_product->getId()}");
                    }
                }

                $subscription->setActive(1);

                $em->persist($subscription);
                $task->setDone(1);
                $task->setEnd(new \DateTime());
                $task->setLocked(0);
                $em->persist($task);
                $em->flush();
            } catch (\Exception $ex) {
                echo $ex->getMessage();
                $task->setDone(1);
                $task->setError(1);
                $task->setEnd(new \DateTime());
                $task->setLocked(0);
                $task->setDescription($ex->getMessage());

                $em->persist($task);
                $em->flush();
            }

        }

    }
}

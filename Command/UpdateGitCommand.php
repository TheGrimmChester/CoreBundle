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


use AWHS\CoreBundle\API\Local\GitUtil;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class UpdateGitCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('bundles:update')
            ->setDescription('Check bundles update');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $path = $this->getContainer()->get('kernel')->getRootDir() . "/../src/AWHS/"; // '.' for current

        //Update the foundation of AWHSPanel
        $absoluteBasePath = realpath($this->getContainer()->get('kernel')->getRootDir() . "/../");
        $count = GitUtil::gitCountCommitsBehind($absoluteBasePath, "origin", "master");
        if($count > 0){
            $output->writeln("<error>Foundation of AWHSPanel is {$count} commits behind.</error>");
            $output->writeln(GitUtil::gitUpdate($absoluteBasePath));
        }

        foreach (new \DirectoryIterator($path) as $file) {
            if ($file->isDot()) continue;

            //Update all AWHS Bundles
            if ($file->isDir()) {
                $absolutePath = realpath($path . $file->getFilename());
                $count = GitUtil::gitCountCommitsBehind($absolutePath, "origin", "master");
                if($count > 0){
                    $output->writeln("<error>" . $file->getFilename() . " is {$count} commits behind.</error>");
                    $output->writeln(GitUtil::gitUpdate($absolutePath));
                }
            }
        }
    }
}
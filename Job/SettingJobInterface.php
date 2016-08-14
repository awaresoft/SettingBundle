<?php

namespace Awaresoft\SettingBundle\Job;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * SettingJobInterface
 *
 * @author Bartosz Malec <b.malec@awaresoft.pl>
 */
interface SettingJobInterface
{
    /**
     * Run job for object
     *
     * @param $object
     * @param ContainerInterface $container
     *
     * @return mixed
     */
    public static function run($object, ContainerInterface $container);
}
<?php

namespace Awaresoft\SettingBundle\Service;

use Awaresoft\SettingBundle\Entity\Setting;
use Doctrine\ORM\EntityManagerInterface;

class SettingService
{
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * SettingService constructor.
     *
     * @param \Doctrine\ORM\EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Get setting by name
     *
     * @param $name
     * @param bool $hidden
     *
     * @return Setting|object
     */
    public function get($name, $hidden = null)
    {
        if (is_bool($hidden)) {
            return $this->getSettingRepository()->findOneBy(array('name' => $name, 'hidden' => $hidden));
        }

        return $this->getSettingRepository()->findOneBy(array('name' => $name));
    }

    /**
     * Get setting field name
     *
     * @param $name
     * @param $fieldName
     * @param null $hidden
     *
     * @return \Awaresoft\SettingBundle\Entity\SettingHasField|null
     * @throws \Exception
     */
    public function getField($name, $fieldName, $hidden = null)
    {
        $settingField = $this->getSettingFieldRepository()->findFieldsBySetting($name, $fieldName, $hidden);

        if (!$settingField) {
            throw new \Exception(sprintf(
                'Setting which name %s and field is %s does not exists. Hidden = %d',
                $name,
                $fieldName,
                $hidden
            ));
        }

        return $settingField;
    }

    /**
     * @return \Awaresoft\SettingBundle\Entity\Repository\SettingRepository
     */
    protected function getSettingRepository()
    {
        return $this->entityManager->getRepository('AwaresoftSettingBundle:Setting');
    }

    /**
     * @return \Awaresoft\SettingBundle\Entity\Repository\SettingHasFieldRepository
     */
    protected function getSettingFieldRepository()
    {
        return $this->entityManager->getRepository('AwaresoftSettingBundle:SettingHasField');
    }
}

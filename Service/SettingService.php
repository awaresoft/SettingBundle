<?php

namespace Awaresoft\SettingBundle\Service;

use Symfony\Component\DependencyInjection\ContainerAware;
use Awaresoft\SettingBundle\Entity\Setting;

class SettingService extends ContainerAware
{
    /**
     * Get setting by name
     *
     * @param $name
     * @param bool $hidden
     *
     * @return Setting
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
     * @return \Awaresoft\SettingBundle\Entity\SettingHasFields|null
     * @throws \Exception
     */
    public function getField($name, $fieldName, $hidden = null)
    {
        $settingField = $this->getSettingFieldRepository()->findFieldsBySetting($name, $fieldName, $hidden);

        if (!$settingField) {
            throw new \Exception(sprintf('Setting which name %s and field is %s does not exists. Hidden = %d', $name, $fieldName, $hidden));
        }

        return $settingField;
    }

    /**
     * @return \Awaresoft\SettingBundle\Entity\Repository\SettingRepository
     */
    protected function getSettingRepository()
    {
        return $this->container->get('doctrine.orm.entity_manager')->getRepository('AwaresoftSettingBundle:Setting');
    }

    /**
     * @return \Awaresoft\SettingBundle\Entity\Repository\SettingHasFieldsRepository
     */
    protected function getSettingFieldRepository()
    {
        return $this->container->get('doctrine.orm.entity_manager')->getRepository('AwaresoftSettingBundle:SettingHasFields');
    }
}

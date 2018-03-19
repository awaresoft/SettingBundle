<?php

namespace Awaresoft\SettingBundle\Admin;

use Awaresoft\SettingBundle\Entity\Setting;
use Sonata\AdminBundle\Form\FormMapper;

/**
 * Admin for settings which are hidden, visible only for super admins
 *
 * @author Bartosz Malec <b.malec@awaresoft.pl>
 */
class SettingHiddenAdmin extends SettingAdmin
{

    protected $baseRouteName = 'admin_awareresoft_setting_advancedsetting';
    protected $baseRoutePattern = 'awaresoft/setting/advancedsetting';
    protected $parentAssociationMapping = 'setting';

    /**
     * {@inheritdoc}
     */
    public function createQuery($context = 'list')
    {
        $query = parent::createQuery($context);
        $query->andWhere(
            $query->expr()->eq($query->getRootAliases()[0] . '.hidden', ':hidden')
        );
        $query->setParameter('hidden', true);

        return $query;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        parent::configureFormFields($formMapper);

        if ($this->getSubject() && $this->getSubject()->getId()) {
            $formMapper
                ->with($this->trans('admin.admin.form.group.advanced'), array('class' => 'col-xs-12 col-md-6'))
                ->add('deletable', null, array(
                    'required' => false
                ))
                ->add('hidden', null, array(
                    'required' => false
                ))
                ->end();
        }
    }

    /**
     * @param Setting $object
     *
     * @return mixed|void
     */
    public function prePersist($object)
    {
        if (!$object->getId()) {
            $object->setHidden(true);
        }
    }

}

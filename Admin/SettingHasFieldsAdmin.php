<?php

namespace Awaresoft\SettingBundle\Admin;

use Awaresoft\Sonata\AdminBundle\Admin\AbstractAdmin as AwaresoftAbstractAdmin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;

/**
 * @author Bartosz Malec <b.malec@awaresoft.pl>
 */
class SettingHasFieldsAdmin extends AwaresoftAbstractAdmin
{

    /**
     * @param \Sonata\AdminBundle\Datagrid\ListMapper $listMapper
     * @return void
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('name')
            ->add('info')
            ->add('value');
    }

    /**
     * @param \Sonata\AdminBundle\Form\FormMapper $formMapper
     * @return void
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $disabledName = true;
        $disabledInfo = true;

        if ($this->isGranted('ROLE_SUPER_ADMIN')) {
            $disabledName = false;
            $disabledInfo = false;
        }

        $formMapper
            ->add('name', 'text', array(
                'disabled' => $disabledName
            ))
            ->add('value', 'text')
            ->add('enabled');

        $formMapper
            ->add('info', 'textarea', array(
                'disabled' => $disabledInfo
            ));
    }
}

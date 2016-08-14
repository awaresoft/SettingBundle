<?php

namespace Awaresoft\SettingBundle\Admin;

use Awaresoft\Sonata\AdminBundle\Admin\AbstractAdmin as AwaresoftAbstractAdmin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;

/**
 * @author Bartosz Malec <b.malec@awaresoft.pl>
 */
class SettingHasFieldAdmin extends AwaresoftAbstractAdmin
{

    /**
     * @param \Sonata\AdminBundle\Datagrid\ListMapper $listMapper
     *
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
     *
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
            ->add('name', 'text', [
                'disabled' => $disabledName,
            ])
            ->add('value', 'text')
            ->add('enabled');

        if ($this->isGranted('ROLE_SUPER_ADMIN')) {
            $formMapper
                ->add('runMethod');
        }

        $formMapper
            ->add('info', 'textarea', [
                'disabled' => $disabledInfo,
            ]);
    }

    /**
     * @inheritdoc
     */
    public function postPersist($object)
    {
        if ($object->getRunMethod()) {
            $tmp = explode('::', $object->getRunMethod());
            $checkBracket = strpos('(', $tmp[1]) !== false ?: false;
            if ($checkBracket) {
                $tmp[1] = substr($tmp[1], $checkBracket);
            }

            if (count($tmp) === 2) {
                call_user_func([$tmp[0], $tmp[1]], [$object, $this->getConfigurationPool()->getContainer()]);
            } else {
                $object->setRunMethod(null);
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function postUpdate($object)
    {
        parent::postPersist();
    }
}

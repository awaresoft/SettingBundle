<?php

namespace Awaresoft\SettingBundle\Admin;

use Awaresoft\SettingBundle\Entity\SettingHasField;
use Awaresoft\Sonata\AdminBundle\Admin\AbstractAdmin as AwaresoftAbstractAdmin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

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
            ->add('info', TextareaType::class, [
                'disabled' => $disabledInfo,
            ]);
    }

    /**
     * @inheritdoc
     */
    public function prePersist($object)
    {
        if ($object->getRunMethod()) {
            $this->prepareRunMethod($object);
        }
    }

    /**
     * @inheritdoc
     */
    public function preUpdate($object)
    {
        if ($object->getRunMethod()) {
            $this->prepareRunMethod($object);
        }
    }

    /**
     * @param SettingHasField $object
     *
     * @return void
     */
    protected function prepareRunMethod(SettingHasField $object)
    {
        $tmp = explode('::', $object->getRunMethod());

        if (!isset($tmp[1])) {
            $object->setRunMethod(null);

            return;
        }

        $checkBracket = strpos($tmp[1], '(') !== false ? strpos($tmp[1], '(') : false;

        if ($checkBracket) {
            $tmp[1] = str_replace(substr($tmp[1], $checkBracket), '', $tmp[1]);
        }

        if (count($tmp) === 2) {
            call_user_func([$tmp[0], $tmp[1]], $object, $this->getConfigurationPool()->getContainer());

            return;
        }

        $object->setRunMethod(null);
    }
}

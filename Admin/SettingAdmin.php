<?php

namespace Awaresoft\SettingBundle\Admin;

use Awaresoft\Sonata\AdminBundle\Admin\AbstractAdmin as AwaresoftAbstractAdmin;
use Awaresoft\SettingBundle\Entity\Setting;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;

/**
 * Settings visible for all admins
 *
 * @author Bartosz Malec <b.malec@awaresoft.pl>
 */
class SettingAdmin extends AwaresoftAbstractAdmin
{
    /**
     * {@inheritdoc}
     */
    public function createQuery($context = 'list')
    {
        $query = parent::createQuery($context);
        $query->andWhere(
            $query->expr()->eq($query->getRootAliases()[0] . '.hidden', ':hidden')
        );
        $query->setParameter('hidden', false);

        return $query;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('name')
            ->add('info')
            ->add('enabled')
            ->add('fields')
            ->add('runMethod')
            ->add('deletable');

        if ($this->isGranted('ROLE_SUPER_ADMIN')) {
            $showMapper->add('hidden');
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('name')
            ->add('info')
            ->add('enabled', null, ['editable' => true]);

        $editable = false;
        if ($this->isGranted('ROLE_SUPER_ADMIN')) {
            $editable = true;
        }

        $listMapper
            ->add('deletable', null, ['editable' => $editable]);

        $listMapper
            ->add('_action', 'actions', [
                'actions' => [
                    'show' => [],
                    'edit' => [],
                ],
            ]);
    }

    /**
     * {@inheritdoc}
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('name')
            ->add('info')
            ->add('enabled');
    }

    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $disabled = true;
        $deletable = false;
        $admin = $formMapper->getAdmin();

        if ($admin->isGranted('ROLE_SUPER_ADMIN')) {
            $disabled = false;
            $deletable = true;
        } else {
            $formMapper->setHelps([
                'info' => $this->trans('setting.admin.help.info'),
            ]);
        }

        $formMapper
            ->with($this->trans('admin.admin.form.group.main'), ['class' => 'col-xs-12 col-md-6'])->end()
            ->with($this->trans('admin.admin.form.group.fields'), ['class' => 'col-xs-12'])->end();

        $formMapper
            ->with($this->trans('admin.admin.form.group.main'))
            ->add('name')
            ->add('info', 'textarea', [
                'disabled' => $disabled,
            ])
            ->add('enabled', null, [
                'required' => false,
            ]);

        if ($this->isGranted('ROLE_SUPER_ADMIN')) {
            $formMapper
                ->add('deletable', null, [
                    'required' => false,
                ]);
        }

        if ($this->isGranted('ROLE_SUPER_ADMIN')) {
            $formMapper
                ->add('runMethod');
        }

        $formMapper
            ->end();

        if ($this->getSubject() && $this->getSubject()->getId()) {
            $formMapper
                ->with($this->trans('admin.admin.form.group.fields'))
                ->add('fields', 'sonata_type_collection', [
                    'required' => false,
                    'by_reference' => false,
                    'label' => false,
                    'type_options' => [
                        'delete' => $deletable,
                    ],
                    'btn_add' => $deletable,
                ], [
                    'edit' => 'inline',
                    'inline' => 'table',
                ])
                ->end();
        }
    }

    /**
     * Prevent before security issue
     *
     * @inheritdoc
     */
    public function prePersist($object)
    {
        if (!$this->isGranted('ROLE_SUPER_ADMIN')) {
            $object->setDeletable(Setting::DEFAULT_DELETABLE);
            $object->setHidden(Setting::DEFAULT_HIDDEN);
        }

        if ($object->getRunMethod()) {
            $this->prepareRunMethod($object);
        }
    }

    /**
     * Prevent before security issue
     *
     * @inheritdoc
     */
    public function preUpdate($object)
    {
        $oldObject = $this->getSettingRepository()->find($object->getId());

        if (!$this->isGranted('ROLE_SUPER_ADMIN')) {
            $object->setDeletable($oldObject->isDeletable());
            $object->setHidden($oldObject->isHidden());
        }

        if ($this->isGranted('ROLE_SUPER_ADMIN')) {
            $this->updateCollection();
        }

        if ($object->getRunMethod()) {
            $this->prepareRunMethod($object);
        }
    }

    /**
     * @return \Doctrine\ORM\EntityRepository
     */
    protected function getSettingRepository()
    {
        return $this->configurationPool->getContainer()->get('doctrine.orm.entity_manager')->getRepository('AwaresoftSettingBundle:Setting');
    }

    /**
     * @param Setting $object
     *
     * @return void
     */
    protected function prepareRunMethod(Setting $object)
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

<?php

namespace PollBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class PollAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('name', 'text');
        $formMapper->add('startDate', 'datetime');
        $formMapper->add('endDate', 'datetime');
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('name');
        $datagridMapper->add('startDate');
        $datagridMapper->add('endDate');
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('id');
        $listMapper->addIdentifier('name');
        $listMapper->addIdentifier('startDate');
        $listMapper->addIdentifier('endDate');
    }
}

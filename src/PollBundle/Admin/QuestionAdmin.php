<?php

namespace PollBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class QuestionAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('poll', 'entity', array(
            'class' => 'PollBundle\Entity\Poll',
            'associated_property' => 'name'
        ));
        $formMapper->add('description', 'text');
        $formMapper->add('type', 'text');
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('poll.name');
        $datagridMapper->add('description');
        $datagridMapper->add('type');
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('poll.name');
        $listMapper->addIdentifier('description');
        $listMapper->addIdentifier('type');
    }
}

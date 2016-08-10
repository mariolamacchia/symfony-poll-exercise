<?php

namespace PollBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class QuestionAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('poll', 'entity', array(
            'class' => 'PollBundle\Entity\Poll',
            'choice_label' => 'name'
        ));
        $formMapper->add('description', 'text');
        $formMapper->add('type', ChoiceType::class, array(
            'choices' => array(
                'Short string'      => 'string',
                'Long text'         => 'text',
                'Multiple choice'   => 'check',
                'Single choice'     => 'radio',
            )
        ));
        $formMapper->add('options', CollectionType::class, array(
            'allow_add' => true,
            'allow_delete' => true,
            'delete_empty' => true
        ));
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

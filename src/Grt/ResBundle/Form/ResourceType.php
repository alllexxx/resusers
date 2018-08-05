<?php

namespace Grt\ResBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ResourceType extends AbstractType
{

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('ip',TextType::class, array('label' => 'IP-адрес','attr'=> array('class'=>'form-control')));
        $builder->add('login',TextType::class, array('label' => 'Логин','attr'=> array('class'=>'form-control')));
        $builder->add('annotation',TextType::class, array('label' => 'Примечание','attr'=> array('class'=>'form-control')));
        $builder->add('flag',ChoiceType::class, array(
            'label'    => 'Установлен в НАСЭД',
            'choices' => array(
                'Нет' => '0',
                'Да' => '1',
            )
        ));
        $builder->add('term', DateType::class, array('label' => 'Term(DD-MM-YYYY)',
            'widget' => 'single_text','format' => 'dd-mm-yyyy','attr'=> array('class'=>'input-group date form-control')));
        $builder->add('docFile', VichImageType::class, [
            'required' => false,
        ]);
        /*$builder->add('base', EntityType::class, array('label' => 'Base',
            'class' => 'Grt\ResBundle\Entity\Base',
            'placeholder' => 'Выбирите ПТО',
            'required' => true,

        ));*/


    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Grt\ResBundle\Entity\Resource'
        ));
    }

    public function getBlockPrefix()
    {
        return 'grt_resourcetype';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Grt\ResBundle\Entity\Resource',
        ]);
    }
}
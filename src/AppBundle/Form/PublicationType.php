<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class PublicationType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('text', TextareaType::class, array(
                'label'=>'Mensaje',
                'required'=> 'required',
                'attr'=>array(
                    'class'=>'form-control'
                )
            ))
            ->add('image', FileType::class, array(
                'label'=>'Imagen',
                'required'=> false,
                'data_class'=>null,
                'attr'=>array(
                    'class'=>'form-control'
                )
            ))
            ->add('document', FileType::class, array(
                'label'=>'Documento',
                'required'=> false,
                'data_class'=>null,
                'attr'=>array(
                    'class'=>'form-control'
                )
            ))

            ->add('Enviar', SubmitType::class, array(
                "attr" => array(
                    "class"=> " btn btn-success"
                )
            ))

         ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BackendBundle\Entity\Publication'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'backendbundle_user';
    }


}

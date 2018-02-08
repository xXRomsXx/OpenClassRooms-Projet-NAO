<?php

namespace UserBundle\Form\Type;

use FOS\UserBundle\Util\LegacyFormHelper;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationFormType extends AbstractType
{
    /**
     * @var string
     */
    private $class;

    /**
     * @param string $class The User class name
     */
    public function __construct($class)
    {
        $this->class = $class;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'email', 
                LegacyFormHelper::getType('Symfony\Component\Form\Extension\Core\Type\EmailType'), 
                array(
                    'label' => 'Email', 
                    'translation_domain' => 'FOSUserBundle',
                    'attr' => array('class' => 'form-control col-lg-10 m-auto')
                )
            )
            ->add(
                'username', 
                null, 
                array(
                    'label' => 'Nom d\'utilisateur', 
                    'translation_domain' => 'FOSUserBundle',
                    'attr' => array('class' => 'form-control col-lg-10 m-auto')
                )
            )
            ->add(
                'plainPassword', 
                LegacyFormHelper::getType('Symfony\Component\Form\Extension\Core\Type\RepeatedType'), 
                array(
                    'type' => LegacyFormHelper::getType('Symfony\Component\Form\Extension\Core\Type\PasswordType'),
                    'options' => array('translation_domain' => 'FOSUserBundle'),
                    'first_options' => array(
                        'label' => 'Mot de passe',
                        'attr' => array('class' => 'form-control col-lg-10 ml-auto mr-auto form-group')
                    ),
                    'second_options' => array(
                        'label' => 'Confirmer mot de passe',
                        'attr' => array('class' => 'form-control col-lg-10 m-auto')
                    ),
                    'invalid_message' => 'Les deux mot de passe ne sont pas identiques.'
                )
            )
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->class,
            'csrf_token_id' => 'registration',
            // BC for SF < 2.8
            'intention' => 'registration',
        ));
    }

    // BC for SF < 3.0
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->getBlockPrefix();
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'app_user_registration';
    }
}

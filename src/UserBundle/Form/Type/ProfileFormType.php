<?php

namespace UserBundle\Form\Type;

use FOS\UserBundle\Util\LegacyFormHelper;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class ProfileFormType extends AbstractType
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
        $this->buildUserForm($builder, $options);

        $constraintsOptions = array(
            'message' => 'Le mot de passe est incorrect.',
        );

        if (!empty($options['validation_groups'])) {
            $constraintsOptions['groups'] = array(reset($options['validation_groups']));
        }

        $builder->add('current_password', LegacyFormHelper::getType('Symfony\Component\Form\Extension\Core\Type\PasswordType'), array(
            'label' => 'Renseigner votre mot de passe pour valider les modifications*',
            'translation_domain' => 'FOSUserBundle',
            'attr' => array('class' => 'form-control col-lg-10 m-auto'),
            'mapped' => false,
            'constraints' => array(
                new NotBlank(),
                new UserPassword($constraintsOptions),
            ),
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->class,
            'csrf_token_id' => 'profile',
            // BC for SF < 2.8
            'intention' => 'profile',
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
        return 'app_user_profile';
    }

    /**
     * Builds the embedded form representing the user.
     *
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    protected function buildUserForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'username', 
                null, 
                array(
                    'label' => 'Nom d\'utilisateur*', 
                    'translation_domain' => 'FOSUserBundle',
                    'attr' => array('class' => 'form-control col-lg-10 m-auto')
                )
            )
            ->add(
                'lastname', 
                null, 
                array(
                    'label' => 'Nom', 
                    'translation_domain' => 'FOSUserBundle',
                    'attr' => array('class' => 'form-control col-lg-10 m-auto')
                )
            )
            ->add(
                'firstname', 
                null, 
                array(
                    'label' => 'Prénom', 
                    'translation_domain' => 'FOSUserBundle',
                    'attr' => array('class' => 'form-control col-lg-10 m-auto')
                )
            )
            ->add(
                'adress', 
                null, 
                array(
                    'label' => 'Adresse', 
                    'translation_domain' => 'FOSUserBundle',
                    'attr' => array('class' => 'form-control col-lg-10 m-auto')
                )
            )
            ->add(
                'postalCode', 
                null, 
                array(
                    'label' => 'Code postal', 
                    'translation_domain' => 'FOSUserBundle',
                    'attr' => array('class' => 'form-control col-lg-10 m-auto')
                )
            )
            ->add(
                'city', 
                null, 
                array(
                    'label' => 'Ville', 
                    'translation_domain' => 'FOSUserBundle',
                    'attr' => array('class' => 'form-control col-lg-10 m-auto')
                )
            )
            ->add(
                'phone', 
                null, 
                array(
                    'label' => 'Téléphone', 
                    'translation_domain' => 'FOSUserBundle',
                    'attr' => array('class' => 'form-control col-lg-10 m-auto')
                )
            )
            ->add(
                'email', 
                LegacyFormHelper::getType('Symfony\Component\Form\Extension\Core\Type\EmailType'), 
                array(
                    'label' => 'Email*', 
                    'translation_domain' => 'FOSUserBundle',
                    'attr' => array('class' => 'form-control col-lg-10 m-auto')
                )
            )
            ->add(
                'picture', 
                FileType::class, 
                array(
                    'label' => 'Modifier votre photo',
                    'label_attr' => array(
                        'class' => 'text-center mt-2 btn btn-info label-user-picture'
                    ),
                    'required' => false,
                    'attr' => array(
                        'class' => 'invisible'
                    )
                )
            )
        ;
    }
}

<?php
namespace AppBundle\Form;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class RegistrationType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('name', 'text', [
                'label' => 'user_name'
            ])
            ->add('surname')
            ->add('phone', 'text', [
                'label'=>'phone_numder',
                'attr' => [
                    'class' => 'select_phone'
                ]
            ])
            ->add('brand')
            ->add('legalName', 'text', [
                'label' => 'legalName'
            ])
            ->add('country', 'choice', [
                'placeholder' => 'select_country',
                'choices' => $list,
                'attr' => [
                    'class' => 'select_country'
                ]
            ])
            ->add('currency', 'choice', [
                'placeholder' => 'select_currency',
                'choices' => $session->get('currencies'),
                'attr' => [
                    'class' => 'select_currency'
                ]
            ])
            ->add('email', 'email')
            ->add('password', 'password')
            ->add('passwordRepeat', 'password')
        ;
    }
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\User'
        ));
    }
    /**
     * @return string
     */
    public function getName() {
        return 'registration_user';
    }
}

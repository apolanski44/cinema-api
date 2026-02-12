<?php

namespace App\Form;

use App\DTO\ReservationDto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('screeningId', IntegerType::class)
            ->add('email', EmailType::class)
            ->add('seats', CollectionType::class, [
                'entry_type' => SeatType::class,
                'allow_add' => true,
                'by_reference' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class'      => ReservationDto::class,
            'csrf_protection' => false, 
        ]);
    }
}
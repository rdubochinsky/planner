<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\TaskLabel;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;

class TaskSearchType extends AbstractType
{
    public const NAME_FIELD = 'name';
    public const LABELS_FIELD = 'labels';
    private const NAME_MAX_LENGTH = 128;

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(self::NAME_FIELD, TextType::class, [
            'constraints' => [
                new Length(['max' => self::NAME_MAX_LENGTH]),
            ],
        ]);

        $builder->add(self::LABELS_FIELD, EntityType::class, [
            'class' => TaskLabel::class,
            'choice_label' => 'name',
            'expanded' => true,
        ]);
    }
}

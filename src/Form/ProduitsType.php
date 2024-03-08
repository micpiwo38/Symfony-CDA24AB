<?php

namespace App\Form;

use App\Entity\Produits;
use App\Entity\Categories;
use App\Entity\References;
use App\Entity\Distributeurs;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class ProduitsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [//Champ de type <input type='text'/>
                'label' => 'Nom du produit',
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
            ->add('slug', TextType::class,[
                'label' => 'Nom dans URL',
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
            ->add('description', TextareaType::class, [//Champ de type <textarea></textarea>
                'label' => 'Description du produit',
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
            ->add('price', MoneyType::class, [//Champ de type <input type='text'/>
                'label' => 'Prix du produit',
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
            ->add('reference', ReferencesType::class)//Imbriquer le formulaire ReferenceType 

            ->add('distributeur', EntityType::class, [//Champ de type Entité
                'class' => Distributeurs::class,//Appel de l'entité src/Entity/Distributeurs.php
                'multiple' => true,//Autorise plusieur entrées
                'choice_label' => 'name',//Le champ de l'entité References à afficher
                'expanded' => true,//Autorise la sélection de plusieurs entrées
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
            ->add('categorie', EntityType::class, [//Champ de type Entité
                'label' => 'Catégorie du produit',
                'class' => Categories::class,//Appel de l'entité src/Entity/Categories.php
                'choice_label' => 'name',//Le champ de l'entité References à afficher
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
            //CollectionType genere un data-protoype = gabarit de sous formulaire vide
            ->add('image', TextType::class, [
                'label' => 'Url de l\'image',
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produits::class,
        ]);
    }
}

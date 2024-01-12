<?php

namespace App\Form;

use App\Form\ProduitType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class CartType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
       // On ajoute les champs de l'entité que l'on veut à notre formulaire
        $builder
            ->add('produits', CollectionType::class, [
                'entry_type' => ProduitType::class, // Assurez-vous que ProduitType est correctement défini
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
            ])
            ->add('adresse', TextType::class, [
                'label' => 'Adresse de livraison',
                'data' => $options['adresse'],
                // Ajoutez d'autres options de champ selon vos besoins
            ])
            ->add('save', SubmitType::class, ['label' => 'Valider le panier']);
    }
}

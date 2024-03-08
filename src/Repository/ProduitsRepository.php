<?php

namespace App\Repository;

use App\Entity\Produits;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Produits>
 *
 * @method Produits|null find($id, $lockMode = null, $lockVersion = null)
 * @method Produits|null findOneBy(array $criteria, array $orderBy = null)
 * @method Produits[]    findAll()
 * @method Produits[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProduitsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Produits::class);
    }

    //Filter par catégorie

    /**
     * @param $categoryID
     * @return mixed
     */
    public function produitsByCategorie($categoryID)
    {
        return $this->createQueryBuilder("p")
            ->andWhere('p.categorie = :categorie_id')
            ->setParameter('categorie_id', $categoryID)
            ->getQuery()
            ->getResult();
    }
    //Filter par distributeurs
    public function produitsByDistributeurs($distributeurID){
        return $this->createQueryBuilder("p")
            ->innerJoin("p.distributeur", "distributeur")
            ->andWhere("distributeur.id = :distributeurID")
            ->setParameter("distributeurID", $distributeurID)
            ->getQuery()
            ->getResult();
    }

    //Barre de recherche par mot cle
    public function rechercherDisque($mot_cle)
    {
        return $this->createQueryBuilder("p")
            ->where('p.name LIKE :mot_cle OR p.description LIKE :mot_cle')
            ->setParameter('mot_cle', '%' . $mot_cle . '%')
            ->getQuery()
            ->getResult();
    }


}

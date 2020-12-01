<?php

namespace App\Repository;

use App\Data\DataRecherche;
use App\Entity\Categorie;
use App\Entity\Produit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Produit|null find($id, $lockMode = null, $lockVersion = null)
 * @method Produit|null findOneBy(array $criteria, array $orderBy = null)
 * @method Produit[]    findAll()
 * @method Produit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProduitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Produit::class);
    }

    public function findAllAvecCategories(DataRecherche $criteres) : array {
        $requete = $this
            ->createQueryBuilder('p')
            ->select('p','c')
            ->join('p.categories','c');

        // Mot clé : nom produit
        if(!empty($criteres->motCle)) {
            $requete = $requete
                ->andWhere('p.nomProd LIKE :motCle')
                ->setParameter('motCle',"%{$criteres->motCle}%");
        }

        // Catégories : catégories

        if(!empty($criteres->categories)){
            $requete = $requete
                ->andWhere('c.id IN (:categories)')
                ->setParameter('categories',$criteres->categories);
        }
        // Promo : promo
        if(!empty($criteres->promo)) {
            $requete = $requete
                ->andWhere('p.promo = 1');
        }
        // Prix

        if(!empty($criteres->prixMin)) {
            $requete = $requete
                ->andWhere('p.prixProd >= :min')
                ->setParameter('min',$criteres->prixMin);
        }
        if(!empty($criteres->prixMax)) {
            $requete = $requete
                ->andWhere('p.prixProd <= :max')
                ->setParameter('max',$criteres->prixMax);
        }

        return $requete->getQuery()->getResult();
    }


    public function findAllSQL(): array {

        $rsm = new ResultSetMapping();

        //Entité vers laquelle faire le mapping

        $rsm->addEntityResult(Produit::class,'p');
        $rsm->addFieldResult('p','id','id');
        $rsm->addFieldResult('p','nom_prod','nomProd');
        $rsm->addFieldResult('p','prix_prod','prixProd');
        $rsm->addFieldResult('p','description_prod','descriptionProd');
        $rsm->addFieldResult('p','img_prod','imgProd');
        $rsm->addFieldResult('p','promo','promo');

        //Mapper les catégories sur le produit

        $rsm->addJoinedEntityResult(Categorie::class,'c','p','categories');
        $rsm->addFieldResult('c','categorie_id','id');
        $rsm->addFieldResult('c','categorie_nom','nomCateg');

        $requeteSQL = $this->getEntityManager()->createNativeQuery('SELECT p.id,nom_prod,prix_prod,description_prod,img_prod,promo,c.id as categorie_id,nom_categ as categorie_nom FROM produit p INNER JOIN categorie_produit cp ON p.id = cp.produit_id INNER JOIN categorie c ON cp.categorie_id = c.id',$rsm);
        $resultat = $requeteSQL->getResult();
        return $resultat;
    }


    public function findAllDQL(): array {

        $requeteDQL = $this->getEntityManager()->createQuery('select p,c from App\Entity\Produit p INNER JOIN p.categories c');
        return $requeteDQL->getResult();
    }


    // /**
    //  * @return Produit[] Returns an array of Produit objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Produit
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

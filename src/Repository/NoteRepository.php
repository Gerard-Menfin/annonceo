<?php

namespace App\Repository;

use App\Entity\Note;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Note|null find($id, $lockMode = null, $lockVersion = null)
 * @method Note|null findOneBy(array $criteria, array $orderBy = null)
 * @method Note[]    findAll()
 * @method Note[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NoteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Note::class);
    }

    /**
     * @param id_membre : integer
     * @return float | null
     */
    public function noteMoyenneRecue(int $id_membre){
        $requete = $this->createQueryBuilder("n")
                            ->select("AVG(n.note)")
                            ->join("n.membre_note", "m")
                            ->where("m.id = :id")
                            ->groupBy("m.id")
                            ->setParameter("id", $id_membre)
                            ->getQuery()
                            ->getResult();
        return !empty($requete) ? $requete[0][1] : null;
        /*
        SELECT AVG(n.note)
        FROM note n JOIN membre m ON n.membre_note_id = m.id
        WHERE m.id = $id_membre
        GROUP BY m.id
        */
    }

    public function findTop5MembresNotes(){
        $resultat = $this->createQueryBuilder("n")
                         ->select("m.id, m.pseudo, AVG(n.note) moyenne, COUNT(m.id) nb")
                         ->join("n.membre_note", "m")
                         ->groupBy("m.id")
                         ->orderBy("moyenne", "DESC")
                         ->setMaxResults(5)
                         ->getQuery()->getResult();
        return $resultat;

    }




    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('n.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Note
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

<?php

namespace App\Repository;

use App\Entity\Membre, App\Entity\Note;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method Membre|null find($id, $lockMode = null, $lockVersion = null)
 * @method Membre|null findOneBy(array $criteria, array $orderBy = null)
 * @method Membre[]    findAll()
 * @method Membre[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MembreRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Membre::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(UserInterface $user, string $newEncodedPassword): void
    {
        if (!$user instanceof Membre) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newEncodedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    public function findByRole($role){
        $resultat = $this->createQueryBuilder("m")
                            ->where("m.roles LIKE '%" . $role . "%'")
                            ->getQuery()->getResult();
        return $resultat;
    }


    public function findTopMembresNotes(){
        $entityManager = $this->getEntityManager();
        $requeteSQL = "SELECT m.id, m.pseudo, AVG(n.note) moyenne 
                       FROM " . Membre::class . " m JOIN " . Note::class . " n " .  
                       "GROUP BY m.id 
                        ORDER BY moyenne DESC ";
        $requete = $entityManager->createQuery($requeteSQL);
        return $requete->getResult();        
    }





}
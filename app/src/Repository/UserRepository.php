<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function save(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /*public function findUserExcludeCurrent(User $currentUser, array $excludedIds = []): ?User
    {
        $excludedIds[] = $currentUser->getId();

        return $this->createQueryBuilder('u')
            ->where('u.id NOT IN (:excludedIds)')
            ->setParameter('excludedIds', $excludedIds)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }*/

    /*public function findNextUser($userA, $excludedIds = [])
    {
        $qb = $this->createQueryBuilder('u')
            ->where('u.id != :currentUser')
            ->setParameter('currentUser', $userA->getId());

        if (!empty($excludedIds)) {
            $qb->andWhere('u.id NOT IN (:excludedIds)')
                ->setParameter('excludedIds', $excludedIds);
        }

        return $qb->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }*/

    public function findNextUser($userA, $excludedIds = [], $gender = 'woman')
    {
        $qb = $this->createQueryBuilder('u')
            ->where('u.id != :currentUser')
            ->andWhere('u.gender = :gender')
            ->setParameter('currentUser', $userA->getId())
            ->setParameter('gender', $gender);

        if (!empty($excludedIds)) {
            $qb->andWhere('u.id NOT IN (:excludedIds)')
                ->setParameter('excludedIds', $excludedIds);
        }

        return $qb->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /*public function findNextUserWoman($userA, $excludedIds = [])
    {
        $qb = $this->createQueryBuilder('u')
            ->where('u.id != :currentUser')
            ->andWhere('u.gender = :gender')
            ->setParameter('currentUser', $userA->getId())
            ->setParameter('gender', 'woman');

        if (!empty($excludedIds)) {
            $qb->andWhere('u.id NOT IN (:excludedIds)')
                ->setParameter('excludedIds', $excludedIds);
        }

        return $qb->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }*/



    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newHashedPassword);

        $this->save($user, true);
    }

//    /**
//     * @return User[] Returns an array of User objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?User
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
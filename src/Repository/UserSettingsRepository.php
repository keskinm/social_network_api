<?php

namespace App\Repository;

use App\Entity\UserSettings;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserSettings|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserSettings|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserSettings[]    findAll()
 * @method UserSettings[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */


class UserSettingsRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserSettings::class);
    }

    public function findByUserName(string $username): array
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            'SELECT u_s
            FROM App\Entity\UserSettings u_s
            INNER JOIN App\Entity\User u WITH u_s.user_id = u.id
            WHERE u.username = :username 
            '
        )->setParameter('username', $username);

        // returns an array of Product objects
        return $query->getResult();
    }


    public function getExternalId(String $id, String $external_table): array

    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            'SELECT u_s.id
            FROM App\Entity\UserSettings u_s
            INNER JOIN App\Entity\User :external_table WITH u_s.user_id = u.id
            '
        )->setParameters(['user_id' => $id, 'external_table' => $external_table]);

        return $query->getResult();

    }


    public function updateUserSettingsField(array $json): String
    {
        $r = json_encode(['ok'=>'ok']);

        $user_id = $json['user_id'];
        foreach ($json as $field => $value)

        {

            $entityManager = $this->getEntityManager();

            $query_str =
            'UPDATE App\Entity\UserSettings u_s
            SET u_s.' . $field . ' = :value
            WHERE u_s.user_id = :user_id';

            $query = $entityManager->createQuery($query_str)->setParameters(['user_id' => $user_id, 'value' => $value]);

            $r = $query->getResult();

        }

        return $r;
    }

    // /**
    //  * @return UserSettings[] Returns an array of UserSettings objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?UserSettings
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

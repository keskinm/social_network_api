<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Doctrine\ORM\Query\ResultSetMapping;

/**
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

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newHashedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    public function updateUserField(array $json): String
    {
        $r = json_encode(['ok'=>'ok']);

        $user_id = $json['user_id'];
        foreach ($json as $field => $value)

        {
            if($field != ('user_id')){
                $entityManager = $this->getEntityManager();
                $query_str = 'UPDATE App\Entity\User u
                SET u.' . $field . ' = :value
                WHERE u.id = :user_id';
                $query = $entityManager->createQuery($query_str)->setParameters(['user_id' => $user_id, 'value' => $value]);
                $r = $query->getResult();
            }
        }

        return $r;
    }

    public function getUserFields(array $data): array
    {
        $id = $data['id'];

        # getOnlySpecified Fields (fields contain a list of wanted fields)
        if (isset($data['fields'])) {
            $fields = '';
            $i=0;
            foreach ($data['fields'] as $value)
            {
                $i++;
                $end = ($i==count($data['fields'])) ? '':',';
                $fields = $fields . ' u.' . $value . $end;
            }

    }
        # Else we take all elements
        else {
            $fields = 'u.username, u.email, u.roles, u.profile_image_id';
        }

        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            'SELECT ' . $fields . '
            FROM App\Entity\User u
            WHERE u.id = :user_id
            '
        )->setParameters(['user_id' => $id]);
        return $query->getResult();
    }


}

<?php

namespace Awaresoft\SettingBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * SettingHasFieldRepository class.
 *
 * @author Bartosz Malec <b.malec@awaresoft.pl>
 */
class SettingHasFieldRepository extends EntityRepository
{
    /**
     * Find field by field name, setting name and hidden (optional)
     *
     * @param $name
     * @param $fieldName
     * @param $hidden
     *
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findFieldsBySetting($name, $fieldName, $hidden)
    {
        $qb = $this->createQueryBuilder('sf')
            ->innerJoin('sf.setting', 's')
            ->where('s.name = :name')
            ->andWhere('sf.name = :fieldName')
            ->setParameter('name', $name)
            ->setParameter('fieldName', $fieldName)
            ->setMaxResults(1);

        if (null !== $hidden) {
            $qb
                ->andWhere('s.hidden = :hidden')
                ->setParameter('hidden', $hidden);
        }

        return $qb->getQuery()->getOneOrNullResult();
    }
}
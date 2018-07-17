<?php

namespace Grt\ResBundle\Entity\Repository;

use Doctrine\ORM\Tools\Pagination\Paginator;
/**
 * ResourceRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ResourceRepository extends \Doctrine\ORM\EntityRepository
{
    private $fieldUserSorting = array('firstname','lastname','middlename');
    private $fieldResourceSorting = array('term');
    private $fieldlocationSorting = array('location');
    private $fieldDepartmentSorting = array('department');

    public function getFindUserBase($baseId, $userId)
    {
        $db = $this->createQueryBuilder('r')
            ->select('r')
            ->where('r.base =:baseId')
            ->andWhere('r.user =:userId')
            ->setParameter(':baseId',$baseId)
            ->setParameter(':userId',$userId);


        return $db->getQuery()->getResult();
    }

    public function getUserResourceByBase($baseId, $field = 'firstname', $order = 'ASC', $currentPage = 1, $limit = 5)
    {

        $db = null;

        if ((in_array($field, $this->fieldUserSorting)||in_array($field, $this->fieldResourceSorting))) {

            if (in_array($field, $this->fieldUserSorting)) {
                $field = 'usr.' . $field;
            } else if (in_array($field, $this->fieldResourceSorting)) {
                $field = 'r.' . $field;
            }

            $db = $this->createQueryBuilder('r')
                ->select('r')
                ->innerJoin('GrtResBundle:User', 'usr', 'WITH', 'r.user = usr.id')
                ->where('r.base = :baseId')
                ->setParameter('baseId', $baseId)
                ->orderBy($field, $order)
                ->getQuery();

        }


        if (in_array($field, $this->fieldlocationSorting)) {
            $field = 'loc.name';
            $db = $this->createQueryBuilder('r')
                ->select('r')
                ->innerJoin('GrtResBundle:User', 'usr', 'WITH', 'r.user = usr.id')
                ->innerJoin('GrtResBundle:Location', 'loc', 'WITH', 'usr.location = loc.id')
                ->where('r.base = :baseId')
                ->setParameter('baseId', $baseId)
                ->orderBy($field, $order)
                ->getQuery();
        }

        if (in_array($field, $this->fieldDepartmentSorting)) {
            $field = 'dep.name';
            $db = $this->createQueryBuilder('r')
                ->select('r')
                ->innerJoin('GrtResBundle:User', 'usr', 'WITH', 'r.user = usr.id')
                ->innerJoin('GrtResBundle:Department', 'dep', 'WITH', 'dep.id = usr.department')
                ->where('r.base = :baseId')
                ->setParameter('baseId', $baseId)
                ->orderBy($field, $order)
                ->getQuery();
        }

        if ($db) {
            $paginator = new Paginator($db);

            $paginator->getQuery()
                ->setFirstResult($limit * ($currentPage - 1))// Offset
                ->setMaxResults($limit); // Limit

            return $paginator;

        } else {
            return null;
        }

    }

    public function getResourceTermExpired($term = 30)
    {
        $date = date("Y-m-d");
        $date = strtotime($date);

        $date = strtotime("-".$term." day", $date);

        $db = $this->createQueryBuilder('r')
            ->select('r')
            ->where('r.term < :term')
            ->andWhere('r.term != :emptyterm')
            ->orderBy('r.term')
            ->setParameter(':term',date('Y-m-d', $date))
            ->setParameter(':emptyterm','0000-00-00');
        return $db->getQuery()->getResult();
    }
}

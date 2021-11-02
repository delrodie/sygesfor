<?php

namespace App\Repository;

use App\Entity\Candidater;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Candidater|null find($id, $lockMode = null, $lockVersion = null)
 * @method Candidater|null findOneBy(array $criteria, array $orderBy = null)
 * @method Candidater[]    findAll()
 * @method Candidater[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CandidaterRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Candidater::class);
    }
	
	/**
	 * @param $matricule
	 * @param $activite
	 * @return int|mixed|string|null
	 * @throws \Doctrine\ORM\NonUniqueResultException
	 */
	public function findCandidature($matricule, $activite)
	{
		return $this
			->createQueryBuilder('c')
			->addSelect('a')
			->addSelect('ca')
			->leftJoin('c.candidat', 'ca')
			->leftJoin('c.activite', 'a')
			->where('ca.matricule = :matricule')
			->andWhere('a.id = :activite')
			->setParameters([
				'matricule' => $matricule,
				'activite' => $activite
			])
			->getQuery()->getOneOrNullResult()
			;
	}
	
	public function findCandidatureValidee($matricule, $activite)
	{
		return $this
			->createQueryBuilder('c')
			->addSelect('a')
			->addSelect('ca')
			->leftJoin('c.candidat', 'ca')
			->leftJoin('c.activite', 'a')
			->where('ca.matricule = :matricule')
			->andWhere('a.id = :activite')
			->andWhere('c.validation = :valid')
			->setParameters([
				'matricule' => $matricule,
				'activite' => $activite,
				'valid' => true
			])
			->getQuery()->getOneOrNullResult()
			;
	}
	
	public function findListCandidatByActivite($activite=null)
	{
		return $this
			->createQueryBuilder('c')
			->addSelect('a')
			->addSelect('ca')
			->addSelect('r')
			->leftJoin('c.activite', 'a')
			->leftJoin('c.candidat', 'ca')
			->leftJoin('ca.region', 'r')
			->where('a.id = :activite')
			->orderBy('ca.nom', 'ASC')
			->addOrderBy('ca.prenoms', 'ASC')
			->setParameter('activite', $activite)
			->getQuery()->getResult()
			;
 	}
	
	/**
	 * Candidat par l'ID de Candidater
	 * 
	 * @param $id
	 * @return int|mixed|string|null
	 * @throws \Doctrine\ORM\NonUniqueResultException
	 */
	public function findCandidatAndActiviteByCandidaterID($id)
	{
		return $this
			->createQueryBuilder('c')
			->addSelect('a')
			->addSelect('ct')
			->addSelect('r')
			->leftJoin('c.activite', 'a')
			->leftJoin('c.candidat', 'ct')
			->leftJoin('ct.region', 'r')
			->where('c.id = :id')
			->setParameter('id', $id)
			->getQuery()->getOneOrNullResult();
	 }

    // /**
    //  * @return Candidater[] Returns an array of Candidater objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Candidater
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

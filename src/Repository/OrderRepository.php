<?php

namespace App\Repository;

use App\Entity\Order;
use App\Data\SearchOrder;
use App\Form\SearchForm;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Cache\VoidCache;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @method Order|null find($id, $lockMode = null, $lockVersion = null)
 * @method Order|null findOneBy(array $criteria, array $orderBy = null)
 * @method Order[]    findAll()
 * @method Order[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderRepository extends ServiceEntityRepository
{
    private $paginator;

    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator)
    {
        parent::__construct($registry, Order::class);
        $this->paginator = $paginator;
    }

    /**
     * @return Order Returns an unique order object
     */
    public function findLastOrder()
    {
        return $this->createQueryBuilder('o')
            ->orderBy('o.id', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    /**
     * Renvoie les commandes en reatard
     * 
    * @return Orders[] Returns an array of Order objects
    */
    public function lateOrder()
    {        
        $nowDate = new \DateTime('now');
        return $this->createQueryBuilder('o')            
            ->andWhere('o.expectedDeliveryDate > :nowDate')
            ->setParameter('nowDate', $nowDate )
            ->getQuery()
            ->getResult()
        ;
    }


    /**
     * 
     * @return Order[] Returns an array of in progress orders objects (status : en cours)
     */
    public function inProgressOrder()
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.status = :val')
            ->setParameter('val', Order::EN_COURS)
            ->getQuery()
            ->getResult()
        ;
    }
    /**
     * Récupère les commandes liées à une recherche
     *
     * @param SearchData $search
     * @return PaginationInterface
     */
    public function findSearch(SearchOrder $search): PaginationInterface
    {
        $query = $this
            ->createQueryBuilder('o')
            ->select('o', 'p', 'u', 'a')
            ->join('o.provider', 'p')
            ->join('o.user', 'u')
            ->join('o.account', 'a');

        if(!empty($search->numero)) {
            $query = $query
                ->andWhere('o.orderNumber = :numero')
                ->setParameter('numero', $search->numero);
        }
        
        if(!empty($search->account)) {
            $query = $query
                ->andWhere('a.id IN (:account)')
                ->setParameter('account', $search->account);
        }

        if(!empty($search->provider)) {
            $query = $query
                ->andWhere('p.id = :provider')
                ->setParameter('provider', $search->provider);
        }

        if(!empty($search->status)) {
            $query = $query
                ->andWhere('o.status IN (:status)')
                ->setParameter('status', $search->status);
        }

        if (!empty($search->designation)) {
            $query = $query
                ->andWhere('o.designation LIKE :designation')
                ->setParameter('designation', "%{$search->designation}%");
        }

        if(!empty($search->user)) {
            $query = $query
                ->andWhere('u.id = :user')
                ->setParameter('user', $search->user);
        }

        $query = $query->getQuery();

        return $this->paginator->paginate(
            $query,
            $search->page,
            15
        );

    }

}

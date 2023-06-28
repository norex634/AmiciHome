<?php 

namespace App\Service;

use App\Repository\ApplyRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class ApplyService
{
    public function __construct(private RequestStack $requestStack, private ApplyRepository $applyRepo, private PaginatorInterface $paginator) 
    {
        
    }

    public function getPaginatedApply(){
        $request = $this->requestStack->getMainRequest();
        $page = $request->query->getInt('page', 1);
        $limit = 3;

        $applyQuery = $this->applyRepo->findForPagination();

        return $this->paginator->paginate($applyQuery, $page, $limit);
    }
}
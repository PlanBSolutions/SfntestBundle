<?php

namespace Planb\SfntestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Planb\SfntestBundle\Repository\StockManagerRepository;
use Planb\SfntestBundle\Entity\StockManager;
use Planb\SfntestBundle\Form\StockItemType;
use Planb\SfntestBundle\Services\StockManagerService;
use Twig\Environment;

class StockController
{
    private $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }
    /**
     * @Route("/", name="stock")
     * @return Response
     */
    public function index(): Response
    {
        return $this->twig->render('stock/index.html.twig', [
            'controller_name' => 'StockController',
        ]);
    }
    
    /**
     * @Route("/import", name="stock.import")
     * @param Request $request
     * @return Response
     */
    public function import(Request $request): Response
    {
        if ($request->get('uploadcsv')) {
            echo "111";
        }
        
        return $this->twig->render('stock/import.html.twig', [
            'controller_name' => 'StockController',
        ]);
    }
    
    /**
     * @Route("/show", name="stock.show")
     * @param StockManagerRepository $repo
     * @return Response
     */
    public function show(StockManagerRepository $repo): Response
    {
        return $this->twig->render('stock/show.html.twig', [
            'controller_name'   => 'StockController',
            'stock'             => $repo->findAll(),
        ]);
    }
    
    /**
     * @Route("/add", name="stock.add")
     * @param Request $request
     * @param StockManagerService $service
     * @return Response
     */
    public function add(Request $request, StockManagerService $service): Response
    {
        $stockItem  = new StockManager();
        $form       = $this->createForm(StockItemType::class, $stockItem);
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $successMessage = $service->processStock($stockItem);
            
            $this->addFlash('success', $successMessage);
            return $this->redirect($this->generateUrl('stock'));
        }
        
        return $this->twig->render('stock/add.html.twig', [
            'controller_name'   => 'StockController',
            'form'              => $form->createView()
        ]);
    }
}

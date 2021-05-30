<?php

namespace App\Controller;

use App\Entity\Order;
use PHPUnit\Util\Json;
use App\Form\OrderType;
use App\Data\SearchOrder;
use App\Form\SearchOrderForm;
use PHP_CodeSniffer\Tokenizers\JS;
use App\Repository\OrderRepository;
use App\Repository\OptionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrderController extends AbstractController
{
    private $orderRepository;
    private $manager;
    private $userRepository;

    public function __construct(OrderRepository $orderRepository, OptionRepository $optionRepository, EntityManagerInterface $manager)
    {
        $this->orderRepository = $orderRepository;
        $this->manager = $manager;
        $this->optionRepository = $optionRepository;
    }

    /**
     * @Route("/order/list", name="order_list")
     * @Security("is_granted('ROLE_USER')")
     */
    public function index(Request $request) : Response
    {
        $user = $this->getUser();
        $options = $user->getOptions();
        $data = new SearchOrder();
        $data->page = $request->get('page', 1);
        $form = $this->createForm(SearchOrderForm::class, $data);
        $form->handleRequest($request);
        $orders = $this->orderRepository->findSearch($data);
        if($request->get('ajax')){
            return new JsonResponse([
                'content'   =>  $this->renderView('order/_orders.html.twig', ['orders' => $orders]),
                'sorting'   =>  $this->renderView('order/_sorting.html.twig', ['orders' => $orders]),
                'pagination'   =>  $this->renderView('order/_pagination.html.twig', ['orders' => $orders]),
            ]);
        }
        return $this->render('order/list.html.twig', [
            'orders'    =>  $orders,
            'form'      =>  $form->createView(),
            'user'      =>  $user,
            'options'   =>  $options,
        ]);
    }

    /**
     * @Route("/order/processed", name="order_in_progress")
     * @Security("is_granted('ROLE_USER')")
     */
    public function orderInProgress(): Response
    {
        $orders = $this->orderRepository->inProgressOrder();

        return $this->render('order/index.html.twig', [
            'orders'    => $orders,
        ]);
    }

    /**
     * @Route("/order/create", name="order_new")
     * @Security("is_granted('ROLE_USER')")
     */
    public function orderCreate(Request $request, EntityManagerInterface $manager): Response
    {
        $order = new Order();

        $form = $this->createForm(OrderType::class, $order);
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $order = $form->getData();
            $order->setuser($this->getUser());
            $order->setCreatedAt(new \DateTime('now'));

            $manager->persist($order);
            $order->setOrderNumber($this->orderRepository->findLastOrder()->getOrderNumber() + 1);
            $manager->persist($order);
            $manager->flush();
            return $this->redirectToRoute('home');
        }

        return $this->render('order/create.html.twig', [
            'form'  => $form->createView(),
        ]);
    }

    /**
     * @Route("/order/{id}", name="order_show")
     * @Security("is_granted('ROLE_USER')")
     */
    public function show(Order $order): Response
    {
        return $this->render('order/show.html.twig', [
            'order'    => $order,
        ]);
    }

    /**
     * @Route("/order/{id}/edit", name="order_edit")
     * @Security("is_granted('ROLE_USER')")
     */
    public function edit(Order $order, Request $request, EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(OrderType::class, $order);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($order);
            $manager->flush();
            return $this->redirectToRoute('home');
        }

        return $this->render('order/create.html.twig', [
            'form'  => $form->createView(),
        ]);
    }
}

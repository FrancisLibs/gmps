<?php

namespace App\Controller;

use App\Entity\Account;
use App\Form\AccountType;
use App\Repository\OrderRepository;
use App\Repository\AccountRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AccountController extends AbstractController
{
    private $accountRepository;
    private $manager;
   
    public function __construct(AccountRepository $accountRepository, EntityManagerInterface $manager)
    {
        $this->accountRepository = $accountRepository;
        $this->manager = $manager;
    }

    /**
     * Account list
     * 
     * @Route("/account", name="account_list")
     * @Security("is_granted('ROLE_ADMIN')")
     * @return RedirectResponse|Response
     */
    public function index(): Response
    {
        $account=$this->accountRepository->findAll();

        return $this->render('account/list.html.twig', [
            'accounts'   =>  $account,
        ]);
    }

    /**
     * Create account
     * 
     * @Route("/account/create", name="account_new")
     * @Security("is_granted('ROLE_ADMIN')")
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return RedirectResponse|Response
     * 
     */
    public function accountCreate(Request $request): Response
    {
        $account = new Account();
        
        $form = $this->createForm(AccountType::class, $account);
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $account = $form->getData();
            $this->manager->persist($account);
            $this->manager->flush();
            $this->addFlash('success', "Le compte a bien été ajouté.");
            return $this->redirectToRoute('account_list');
        }

        return $this->render('account/create.html.twig', [
            'form'  => $form->createView(),
        ]);
    }

    /**
     * Edit account
     * 
     * @Route("/account/{id}/edit", name="account_edit")
     * @Security("is_granted('ROLE_USER')")
     * @param  Account $account
     * @param  Request $request
     * @param  EntityManagerInterface $manager
     * @return RedirectResponse|Response
     */
    public function accountEdit(Account $account, Request $request): Response
    {
        $form = $this->createForm(AccountType::class, $account);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) 
        {          
            $this->manager->persist($account);
            $this->manager->flush();

            $this->addFlash('success', "Le compte a bien été modifié.");
            return $this->redirectToRoute('account_list');
        }
        return $this->render('account/modify.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Delete account
     * 
     * @Route("/account/{id}/delete", name="account_remove")
     * @Security("is_granted('ROLE_ADMIN')")
     * @param  Account $account
     * @param  Request $request
     * @param  EntityManagerInterface $manager
     * @return RedirectResponse|Response
     */
    public function accountDelete(Account $account, Request $request, OrderRepository $orderRepository): Response
    {
        $order = $orderRepository->countOrderAccount($account->getId());
        if($order > 0){
            $this->addFlash('error', "Il n'est pas possible d'effacer ce compte. Des commandes lui sont liées");
            return $this->redirectToRoute('account_list');
        }
        $this->manager->remove($account);
        $this->manager->flush();

        $this->addFlash('success', "Le compte a bien été supprimé.");
        return $this->redirectToRoute('account_list');
    }
}

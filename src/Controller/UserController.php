<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Data\SearchUser;
use App\Form\SearchUserForm;
use Doctrine\ORM\EntityManager;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    private $encoder;
    private $security;
    private $userRepository;
   
    public function __construct(UserPasswordEncoderInterface $passwordEncoder, Security $security, UserRepository $userRepository)
    {
        $this->encoder = $passwordEncoder;
        $this->security = $security;
        $this->userRepository = $userRepository;
    }

    /**
     * Users list
     * 
     * @Route("/admin/users/index", name="user_list")
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return RedirectResponse|Response
     */
    public function userList(Request $request, PaginatorInterface $paginator)
    {    
        $data = new SearchUser();
        $data->page = $request->get('page', 1);
        $form = $this->createForm(SearchUserForm::class, $data);
        $form->handleRequest($request);
        $users = $this->userRepository->findSearch($data);

        return $this->render('user/list.html.twig', [
            'users' => $users,
            'form'  => $form->createView(),
        ]);
    }
    
    /**
     * Create user
     * 
     * @Route("/user/create", name="user_new")
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return RedirectResponse|Response
     */
    public function userCreate(Request $request, EntityManagerInterface $manager)
    {       
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) 
        {          
            $user->setPassword($this->encoder->encodePassword($user, 'password'));
            $manager->persist($user);
            $manager->flush();

            $this->addFlash('success', "L'utilisateur a bien été ajouté.");
            if ($this->security->isGranted('ROLE_ADMIN')) {
                return $this->redirectToRoute('admin_user_list');
            }
            return $this->redirectToRoute('home');
        }
        return $this->render('user/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Delete user
     *
     * @Route("/user/{id}/delete", name="user_delete", methods="DELETE")
     * @param                      User $user
     * @return                     RedirectResponse
     * @IsGranted("ROLE_ADMIN")
     */
    public function userDelete(User $user, Request $request)
    {
        $submittedToken = $request->request->get('token');
        $currentUser = $this->getUser();
        if ($this->isCsrfTokenValid('delete-user', $submittedToken)) {
            if ($user <> $currentUser) {
                $this->manager->remove($user);
                $this->manager->flush();
                $this->addFlash('success', 'L\'utilisateur a bien été supprimé.');
                return $this->redirectToRoute('admin_user_list');
            }

            if ($user == $currentUser) {
                $this->addFlash('error', 'Vous ne pouvez pas vous supprimer vous-même');
                return $this->redirectToRoute('admin_user_list');
            }
        }
        $this->addFlash('error', 'L\'utilisateur n\'a pas été supprimé.');
        return $this->redirectToRoute('admin_user_list');
    }

    /**
     * Edition d'un utilisateur
     * 
     * @Route("/user/{id}/edit", name="user_edit")
     * @param  User $user
     * @param  Request $request
     * @param  EntityManagerInterface $manager
     * @return RedirectResponse
     */
    public function userEdit(User $user, Request $request, EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) 
        {          
            $manager->persist($user);
            $manager->flush();

            $this->addFlash('success', "L'utilisateur a bien été modifié.");
            return $this->redirectToRoute('user_list');
        }
        return $this->render('user/modify.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
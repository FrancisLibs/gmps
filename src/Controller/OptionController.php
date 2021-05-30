<?php

namespace App\Controller;

use App\Entity\Option;
use App\Repository\OptionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class OptionController  extends AbstractController
{
    /**
     * @Route("/order/{id}/toggleDisplay", name="toggle_order_display")
     */
    public function index(Option $option, OptionRepository $optionRepository, EntityManagerInterface $manager): Response
    {
        if ($option->getDisplayOrderList()) {
            $option->setDisplayOrderList(false);
        } else {
            $option->setDisplayOrderList(true);
        }
        $manager->persist($option);
        $manager->flush();

        return $this->redirectToRoute('order_list');
    }
}
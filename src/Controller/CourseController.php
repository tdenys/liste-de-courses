<?php

namespace App\Controller;

use App\Entity\Item;
use App\Form\ItemType;
use App\Repository\ItemRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CourseController extends AbstractController
{
    /**
     * @Route("/course", name="course")
     */
    public function index(ItemRepository $itemRepository, Request $request): Response
    {
        $item = new Item();
        $formItem = $this->createForm(ItemType::class,$item);
        $formItem->handleRequest($request);
        if ( $formItem->isSubmitted()){
            $item->setIsBuy(false);
            $em = $this->getDoctrine()->getManager();
            $em->persist($item);
            $em->flush();
            return $this->redirectToRoute('course');
        }

        return $this->render('course/index.html.twig', [
            'controller_name' => 'CourseController',
            'items' => $itemRepository->findAll(),
            'formItem'=> $formItem->createView()
        ]);
    }

    /**
     * @Route("/acheter/{id}", name="acheter")
     */
    public function acheter(Item $item): Response
    {
        $em = $this->getDoctrine()->getManager();
        $item->setIsBuy(!$item->getIsBuy());
        $em->flush();
        return $this->redirectToRoute('course');
    }

    /**
     * @Route("/supprimer/{id}", name="supprimer")
     */
    public function supprimer(Item $item, EntityManagerInterface $em): Response
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($item);
        $em->flush();
        return $this->redirectToRoute('course');
    }
}

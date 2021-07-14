<?php

namespace App\Controller;

use App\Entity\Jeu;
use App\Entity\Joueur;
use App\Form\JeuType;
use App\Repository\JeuRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/jeu")
 */
class JeuController extends AbstractController
{
    /**
     * @Route("/", name="jeu_index", methods={"GET"})
     */
    public function index(JeuRepository $jeuRepository): Response
    {
        return $this->render('jeu/index.html.twig', [
            'jeus' => $jeuRepository->findAll(),
        ]);

    }

    /**
     * @Route("/new", name="jeu_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $jeu = new Jeu();
        $form = $this->createForm(JeuType::class, $jeu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($jeu);
            $entityManager->flush();

            return $this->redirectToRoute('jeu_index');
        }

        return $this->render('jeu/new.html.twig', [
            'jeu' => $jeu,
            'form' => $form->createView(),
        ]);
    }
//    /**
//     * @Route("/newtest", name="jeu_newtest", methods={"GET","POST"})
//     */
//    public function newtest(Request $request): Response
//    {
//        $jeu = new Jeu();
//        $jeu->setLevel("4");
//        $jeu->setScore("2");
//
//        $entityManager = $this->getDoctrine()->getManager();
//
//        $joueur =$entityManager->getRepository(Joueur::class)->findOneBy(['email' =>'fourat.anane@esprit.tn' ]);
//        $jeu->addJoueur($joueur);
//
//        $entityManager->persist($jeu);
//        $entityManager->flush();
//        return new Response(sprintf('<html><body> aaaa </body></html>'));
//    }
    /**
     * @Route("/deletetest", name="deletetest", methods={"GET","POST"})
     */
    public function newtest(Request $request): Response
    {

        $session = $request->getSession();
       $session->set('lvl2',1);
        $referer = $request->headers->get('referer');
        return $this->redirect($referer);

    }

    /**
     * @Route("/{id}", name="jeu_show", methods={"GET"})
     */
    public function show(Jeu $jeu): Response
    {
        return $this->render('jeu/show.html.twig', [
            'jeu' => $jeu,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="jeu_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Jeu $jeu): Response
    {
        $form = $this->createForm(JeuType::class, $jeu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('jeu_index');
        }

        return $this->render('jeu/edit.html.twig', [
            'jeu' => $jeu,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="jeu_delete", methods={"POST"})
     */
    public function delete(Request $request, Jeu $jeu): Response
    {
        if ($this->isCsrfTokenValid('delete'.$jeu->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($jeu);
            $entityManager->flush();
        }

        return $this->redirectToRoute('jeu_index');
    }
}

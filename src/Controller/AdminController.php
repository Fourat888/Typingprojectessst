<?php

namespace App\Controller;

use App\Entity\Admin;
use App\Form\AdminType;
use App\Form\ChangemdpadminType;
use App\Form\GeneraladminType;
use App\Repository\AdminRepository;
use phpDocumentor\Reflection\Types\Array_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin")
 */
class AdminController extends AbstractController
{
    public function kekwlooolhih()
    {
        
    }
    /**
     * @Route("/", name="admin_index", methods={"GET"})
     */
    public function index(AdminRepository $adminRepository): Response
    {
        return $this->render('admin/index.html.twig', [
            'admins' => $adminRepository->findAll(),
        ]);
    }
    /**
     * @Route("/backadmin", name="user_backa", methods={"GET"})
     */
    public function indexbacka(): Response
    {


        return $this->render('admin/accueil.html.twig');}

    /**
     * @Route("/creationjeu", name="admin_creation", methods={"GET"})
     */
    public function creationjeu(): Response
    {


        return $this->render('admin/creationdujeu.html.twig');}
    /**
     * @Route("/joueradmin", name="admin_jouer", methods={"GET"})
     */
    public function adminjouerpartierapide(): Response
    {


        return $this->render('admin/joueradmin.html.twig');}
    /**
     * @Route("/testgame", name="test_game", methods={"GET"})
     */
    public function testgame(): Response
    {


        return $this->render('admin/test_game.html.twig');}

    /**
     * @Route("/choselvl", name="choselvl", methods={"GET"})
     */
    public function choselevel(): Response
    {


        return $this->render('admin/choselvl.html.twig');}
    /**
     * @Route("/joueradmin2", name="admin_jouer2", methods={"GET"})
     */
    public function adminjouer(): Response
    {


        return $this->render('admin/jouer2nd.html.twig');}

    /**
     * @Route("/settings/{id}", name="admin_settings", methods={"GET","POST"})
     */
    public function settings(Request $request,Admin $admin): Response
    {
        $img=$admin->getImage();

        $session = $request->getSession();
        $form = $this->createForm(GeneraladminType::class, $admin);
        $form->handleRequest($request);
        if ($form->isSubmitted()  ) {

            $file = $form->get('image')->getData();
            if ($file != null) {
                $fileName = $file->getClientOriginalName();
                try {
                    $file->move(
                        $this->getParameter('images_directoryfourat'),
                        $fileName
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
            }
            if ($file != null) {
                $admin->setImage($fileName);
            } else {
                $admin->setImage($img);
            }
            $this->getDoctrine()->getManager()->flush();
            $session->set('email', $admin->getEmail());
            $session->set('nom', $admin->getNom());
            $session->set('prenom', $admin->getPrenom());
            $session->set('image', $admin->getImage());
        }

        return $this->render('admin/settings.html.twig', [
            'admin' => $admin,
            'form' => $form->createView(),
        ]);
        }

    /**
     * @Route("/changepass/{id}", name="change_pass", methods={"GET","POST"})
     */

    public function modifierpass(Request $request,Admin $admin){
        $session = $request->getSession();
        $b=$admin->getPassword();
        $form = $this->createForm(ChangemdpadminType::class, $admin);
        $form->handleRequest($request);

        if ($form->isSubmitted() ) {
            var_dump($admin->getPassword1());
            var_dump($admin->getPassword2());

            if ($b==md5($admin->getPassword()) && ($admin->getPassword1()==$admin->getPassword2()))
            {
                $admin->setPassword(md5($admin->getPassword1()));
                $this->getDoctrine()->getManager()->flush();
                $this->addFlash('notice', 'Mot de pass changer avec succés');

            }

            else {

                $this->addFlash('notice', 'Verifiez vos parametres');



            }








        }

        return $this->render('admin/changepass.html.twig', [

            'form' => $form->createView(),
        ]);

    }

    /**
     * @Route("/new", name="admin_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $admin = new Admin();
        $form = $this->createForm(AdminType::class, $admin);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($admin);
            $entityManager->flush();

        }

        return $this->render('admin/new.html.twig', [
            'admin' => $admin,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin_show", methods={"GET"})
     */
    public function show(Admin $admin): Response
    {
        return $this->render('admin/show.html.twig', [
            'admin' => $admin,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Admin $admin): Response
    {
        $form = $this->createForm(AdminType::class, $admin);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('image')->getData();
            if ($file!=null){
                $fileName= $file->getClientOriginalName();
                try {
                    $file->move(
                        $this->getParameter('images_directoryfourat'),
                        $fileName
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }}
            $admin->setImage($fileName);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_index');
        }

        return $this->render('admin/edit.html.twig', [
            'admin' => $admin,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin_delete", methods={"POST"})
     */
    public function delete(Request $request, Admin $admin): Response
    {
        if ($this->isCsrfTokenValid('delete'.$admin->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($admin);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_index');
    }
}

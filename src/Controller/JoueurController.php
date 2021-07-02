<?php

namespace App\Controller;
use App\Entity\Admin;
use App\Entity\Jeu;
use App\Entity\Joueur;
use App\Entity\User;
use App\Form\ConfirmationType;
use App\Form\JoueurType;
use App\Form\LoginType;
use App\Form\Recuperertype;
use App\Form\SendType;
use App\Repository\AdminRepository;
use App\Repository\JeuRepository;
use App\Repository\JoueurRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Swift_Message;

/**
 * @Route("/joueur")
 */
class JoueurController extends AbstractController
{
    /**
     * @Route("/", name="joueur_index", methods={"GET"})
     */
    public function index(JoueurRepository $joueurRepository): Response
    {

        return $this->render('joueur/index.html.twig', [
            'joueurs' => $joueurRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="joueur_new", methods={"GET","POST"})
     */
    public function new(Request $request,\Swift_Mailer $mailer): Response
    {
        $session = $request->getSession();
        $joueur = new Joueur();
        $form = $this->createForm(JoueurType::class, $joueur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() ) {
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

            if ($joueur->getPassword() == $joueur->getPassword1()) {

                $user2=$this->getDoctrine()->getRepository(Joueur::class)->findOneBy(
                    ['email' => $joueur->getEmail()],
                );
                if ($user2 != null)
                    $this->addFlash('notice', 'Il existe un compte deja avec ce mail');
                else {

                    $random = random_int(100000, 1000000);
                    if ($file!=null) {
                        $session->set('image', $fileName);
                    }

                    $session->set('email', $joueur->getEmail());
                    $session->set('id', $joueur->getId());
                    $session->set('password', $joueur->getPassword());
                    $session->set('nom', $joueur->getNom());
                    $session->set('pseudo', $joueur->getPseudo());
                    $session->set('mdp', $random);
                    $message = (new \Swift_Message('Hello Email'))
                        ->setFrom('typingfastgame8@gmail.com')
                        ->setTo($joueur->getEmail())
                        ->setSubject('code de confirmation du compte')
                        ->setBody(strval($random));

                    $mailer->send($message);

                    return $this->redirectToRoute('confirmerjoueur');
                }
            } else {
                $this->addFlash('notice', 'Verifiez que le mot de passe est identique');

            }
        }

        return $this->render('joueur/inscription.html.twig', [
            'joueur' => $joueur,
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/confirmerjoueur", name="confirmerjoueur", methods={"GET","POST"})
     */
    public function confirmerjoueur(Request $request){
        $session = $request->getSession();
        $user=new Joueur();
        $form = $this->createForm(ConfirmationType::class, $user);
        $form->handleRequest($request);
        $a=$session->get("email");
        $b=$session->get("id");
        $c=$session->get("password");
        $d=$session->get("nom");
        $e=$session->get("mdp");

        $f=$session->get("image");
        $g=$session->get("pseudo");

        if ($form->isSubmitted() ) {
            if ($e==$user->getCode())
            {
                $joueur=new Joueur();

                $entityManager = $this->getDoctrine()->getManager();
                $joueur->setPassword(md5($c));
                $joueur->setEmail(($a));
                $joueur->setNom(($d));
                $joueur->setImage($f);
                $joueur->setPseudo($g);

                $entityManager->persist($joueur);
                $entityManager->flush();
                $session->invalidate();
                return $this->redirectToRoute("user_login");





            }

            else {

                $this->addFlash('notice', 'Code incorrect');



            }








        }

        return $this->render('verifreceive.html.twig', [

            'form' => $form->createView(),
        ]);

    }


    /**
     * @Route("/newtest", name="user_newtest", methods={"GET","POST"})
     */
    public function newtest(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $user->setPassword(md5($user->getPassword()));
            $user->setType("client");
            $user->setEtat("attente");
            $entityManager->persist($user);
            $entityManager->flush();




            return $this->redirectToRoute('user_login');
        }

        return $this->render('user/inscription1.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/oublier/oublier", name="user_oublier", methods={"GET","POST"})
     */
    public function oublier(Request $request,\Swift_Mailer $mailer){
        $session = $request->getSession();


        $user=new Joueur();
        $form = $this->createForm(SendType::class, $user);
        $form->handleRequest($request);


        if ($form->isSubmitted() ) {
            $random = random_int(100000, 1000000);
            $a = strval($random);
            $session->set('email', $user->getEmail());

            $session->set('mdp', $random);
            $message = (new \Swift_Message('Hello Email'))
                ->setFrom('typingfastgame8@gmail.com')
                ->setTo($user->getEmail())
                ->setSubject('code de recuperation du mot de passe')
                ->setBody(strval($random));

            $mailer->send($message);
            return $this->redirectToRoute("user_modifierm", ['user' => $user]);


        }




        return $this->render('oublier.html.twig', [

            'form' => $form->createView(),
        ]);

    }
    /**
     * @Route("/oublier/modifier", name="user_modifierm", methods={"GET","POST"})
     */
    public function modifier(Request $request){
        $session = $request->getSession();
        $user=new Joueur();
        $form = $this->createForm(Recuperertype::class, $user);
        $form->handleRequest($request);
        $a=$session->get("email");
        $b=$session->get("mdp");

        if ($form->isSubmitted() ) {
            if ($b==$user->getCode()&&($user->getPassword()==$user->getPassword1()))
            {

                $user2=$this->getDoctrine()->getRepository(Joueur::class)->findOneBy(
                    ['email' => $a],
                );

                $user2->setPassword(md5($user->getPassword()));
                $this->getDoctrine()->getManager()->flush();
                $session->invalidate();
                return $this->redirectToRoute("user_login");





            }

            else {

                $this->addFlash('notice', 'Verifiez vos parametres');



            }








        }

        return $this->render('recuperer.html.twig', [

            'form' => $form->createView(),
        ]);

    }
    /**
     * @Route("/normalgamejoueur", name="normal_game", methods={"GET"})
     */
    public function normalgamej(): Response
    {


        return $this->render('joueur/partie_normal.html.twig');}
    /**
     * @Route("/partietestjouer", name="partie_test", methods={"GET"})
     */
    public function testgamej(): Response
    {


        return $this->render('joueur/partie_test.html.twig');}
    /**
     * @Route("/choselvljoueur", name="chose_level", methods={"GET"})
     */
    public function choseleveljoueur(): Response
    {


        return $this->render('joueur/choselvl.html.twig');}
    /**
     * @Route("/rapidjoueur", name="rapid_game", methods={"GET"})
     */
    public function adminjouerpartierapide(): Response
    {


        return $this->render('joueur/partierapide.html.twig');}
    /**
     * @Route("/accueiljoueur", name="accueil_joueur", methods={"GET"})
     */
    public function accueiljoueur(): Response
    {


        return $this->render('joueur/accueil.html.twig');}

    /**
     * @Route("/login/login", name="user_login", methods={"GET","POST"})
     */
    public function login(Request $request,AdminRepository $userrep,JoueurRepository $repository){
        $admin=new Admin();



        $session = $request->getSession();
        $session->set('email',"");
        $form = $this->createForm(LoginType::class, $admin);
        $form->handleRequest($request);

        if ($form->isSubmitted() ) {

            $email=$admin->getEmail();
            $mdp=md5($admin->getPassword());
            $id1=$admin->getId();

            $user2=$repository->findJoueur($email,$mdp);

            $user3=$userrep->findAdmin($email,$mdp);

            if($user2 !=  null){
                $session->start();
                $userjoueur=$this->getDoctrine()->getRepository(Joueur::class)->findOneBy(
                    ['email' => $email],
                );
                $session->set('email',$email);
                $session->set('mdp',$mdp);
                $session->set('image',$userjoueur->getImage());

                $session->set('id',$userjoueur->getId());
                return $this->redirectToRoute("accueil_joueur");

            }
            if($user3 !=  null){

                $session->start();
                $useradmin=$this->getDoctrine()->getRepository(Admin::class)->findOneBy(
                    ['email' => $email],
                );
                $session->set('email',$email);
                $session->set('mdp',$mdp);
                $session->set('image',$useradmin->getImage());

                $session->set('id',$useradmin->getId());

                return $this->redirectToRoute("user_backa");

            }

            else {
                $this->addFlash('notice', ' vos parametVerifierres  !');

            }



        }

        return $this->render('Login.html.twig', [

            'form' => $form->createView()
        ]);

    }
    /**
     * @Route("/addrapidgame2/{score}/{level}/{partietype}", name="addrapid_game2", methods={"GET","POST"})
     */
    public function ajouterrapidgame2(Request $request,$score,$level,$partietype): Response
    {
        $jeu=new Jeu();
        $jeu->setScore($score);
        $jeu->setLevel($level);
        $jeu->setPartietype($partietype);
        $entityManager = $this->getDoctrine()->getManager();
        $joueur =$entityManager->getRepository(Joueur::class)->findOneBy(['email' =>'fourat.anane@esprit.tn' ]);
        $jeu->addJoueur($joueur);

        $entityManager->persist($jeu);
        $entityManager->flush();
        return $this->redirectToRoute('rapid_game');

    }
    public function testfront(Request $request)
    {

        $session = $request->getSession();
        $a = $session->get("email");
        $user2 = $this->getDoctrine()->getRepository(\App\Entity\Joueur::class)->findOneBy(
            ['email' => $a],
        );

        $jeu = $user2->getJeux();
        $nb1=$this->getDoctrine()->getRepository(Jeu::class)->nbjeux("1","rapide");
        $nb2=$this->getDoctrine()->getRepository(Jeu::class)->nbjeux("2","rapide");
        $nb3=$this->getDoctrine()->getRepository(Jeu::class)->nbjeux("3","rapide");
        $nb4=$this->getDoctrine()->getRepository(Jeu::class)->nbjeux("4","rapide");
        $nb5=$this->getDoctrine()->getRepository(Jeu::class)->nbjeux("5","rapide");

        var_dump($nb1);
        var_dump($nb2);
        var_dump($nb3);
        var_dump($nb4);
        var_dump($nb5);

        $this->get('twig')->addGlobal('nbrapidelvl1', $nb1);
        $this->get('twig')->addGlobal('nbrapidelvl2', $nb2);
        $this->get('twig')->addGlobal('nbrapidelvl3', $nb3);
        $this->get('twig')->addGlobal('nbrapidelvl4', $nb4);
        $this->get('twig')->addGlobal('nbrapidelvl5', $nb5);




    }
    /*
        $att=0;
        $conf=0;
        $nb=0;
        $nbconfauj=0;
        $nbattauj=0;
        foreach ($reservations as $reservations)
        {
            if (  $reservations->getEtat()=="Confirmé" && ( $reservations->getDate() )->format('y-m-d')>=( new \DateTime() )->format('y-m-d'))  :

                $conf+=1;
            else:

                $att+=1;

            endif;
            if (( $reservations->getDate() )->format('y-m-d')==( new \DateTime() )->format('y-m-d')) :
                $nb++;
                if ($reservations->getEtat() =='Confirmé'):
                    $nbconfauj++;
                else:
                    $nbattauj++;

                endif;
            endif;

        }
        if ($nb>0) :
            $pourcentage=($nbconfauj/$nb)*100;
        else :
            $pourcentage=0;
        endif;
        $this->get('twig')->addGlobal('nbactf', $nb);

        $this->get('twig')->addGlobal('entityf', $att);
        $this->get('twig')->addGlobal('conff', $conf);
        $this->get('twig')->addGlobal('confaujf', $nbconfauj);
        $this->get('twig')->addGlobal('attaujf', $nbattauj);
        $this->get('twig')->addGlobal('pourcaujf', $pourcentage);


    }
    */
    /**
     * @Route("/afficherscore/{id}", name="score_show", methods={"GET"})
     */
    public function showscore(Joueur $joueur,Request $request): Response
    {
        $this->testfront($request);
        $jeu=$joueur->getJeux();

        return $this->render('joueur/voirscore.html.twig', [
            'joueur' => $joueur,
            'jeux' => $jeu,
        ]);

    }
    /**
     * @Route("/{id}", name="joueur_show", methods={"GET"})
     */
    public function show(Joueur $joueur): Response
    {
        $jeu=$joueur->getJeux();
        return $this->render('joueur/show.html.twig', [
            'joueur' => $joueur,
            'jeux' => $jeu,
        ]);

    }

    /**
     * @Route("/{id}/edit", name="joueur_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Joueur $joueur): Response
    {
        $form = $this->createForm(JoueurType::class, $joueur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('joueur_index');
        }

        return $this->render('joueur/edit.html.twig', [
            'joueur' => $joueur,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="joueur_delete", methods={"POST"})
     */
    public function delete(Request $request, Joueur $joueur): Response
    {
        if ($this->isCsrfTokenValid('delete'.$joueur->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($joueur);
            $entityManager->flush();
        }

        return $this->redirectToRoute('joueur_index');
    }
}

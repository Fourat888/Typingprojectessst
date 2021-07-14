<?php

namespace App\Controller;

use App\Entity\Admin;
use App\Entity\Historiquedeconnection;
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
    public function new(Request $request, \Swift_Mailer $mailer): Response
    {
        $session = $request->getSession();
        $joueur = new Joueur();
        $form = $this->createForm(JoueurType::class, $joueur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
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

            if ($joueur->getPassword() == $joueur->getPassword1()) {

                $user2 = $this->getDoctrine()->getRepository(Joueur::class)->findOneBy(
                    ['email' => $joueur->getEmail()],
                );
                if ($user2 != null)
                    $this->addFlash('notice', 'Il existe un compte deja avec ce mail');
                else {

                    $random = random_int(100000, 1000000);
                    if ($file != null) {
                        $session->set('image', $fileName);
                    }

                    $session->set('email', $joueur->getEmail());
                    $session->set('id', $joueur->getId());
                    $session->set('password', $joueur->getPassword());
                    $session->set('nom', $joueur->getNom());
                    $session->set('pseudo', $joueur->getPseudo());
                    $session->set('lng', $joueur->getLng());
                    $session->set('lat', $joueur->getLat());
                    $session->set('Country', $joueur->getCountry());

                    $session->set('emp', $joueur->getEmplacement());

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
    public function confirmerjoueur(Request $request)
    {
        $session = $request->getSession();
        $user = new Joueur();
        $form = $this->createForm(ConfirmationType::class, $user);
        $form->handleRequest($request);
        $a = $session->get("email");
        $b = $session->get("id");
        $c = $session->get("password");
        $d = $session->get("nom");
        $e = $session->get("mdp");

        $f = $session->get("image");
        $g = $session->get("pseudo");
        $lat = $session->get("lat");
        $country = $session->get("Country");

        $lng = $session->get("lng");
        $emp = $session->get("emp");

        if ($form->isSubmitted()) {
            if ($e == $user->getCode()) {
                $joueur = new Joueur();
                $historique = new Historiquedeconnection();
                $entityManager = $this->getDoctrine()->getManager();

                $joueur->setPassword(md5($c));
                $joueur->setEmail(($a));
                $joueur->setNom(($d));
                $joueur->setImage($f);
                $joueur->setPseudo($g);
                $joueur->setNiveau(1);
                $joueur->setLat($lat);
                $joueur->setLng($lng);
                $joueur->setEmplacement($emp);
                $joueur->setCountry($country);
                $joueur->setDateinscription(new \DateTime());
                $historique->setNbfois(0);
                $historique->setNbheures(0);
                $joueur->setHistoriquedeconnection($historique);

                $entityManager->persist($joueur);
                $entityManager->flush();
                $session->invalidate();
                return $this->redirectToRoute("user_login");


            } else {

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
     * @param JoueurRepository $Repository
     * @return Response
     * @Route ("/statpays", name="stattpays")
     */

    public function statistiques(Request $request, JoueurRepository $joueurRepository){


        $forum = $joueurRepository->countBycountry();
        $countries = [];
        $annoncesCount = [];
        foreach($forum as $foru){

            $countries [] = $foru['Country'];
            $annoncesCount[] = $foru['count'];
        }
        return $this->render('joueur/statcountry.html.twig', [
            'dates' => $countries,
            'annoncesCount' => $annoncesCount
        ]);
    }
    /**
     * @Route("/oublier/oublier", name="user_oublier", methods={"GET","POST"})
     */
    public function oublier(Request $request, \Swift_Mailer $mailer)
    {
        $session = $request->getSession();


        $user = new Joueur();
        $form = $this->createForm(SendType::class, $user);
        $form->handleRequest($request);


        if ($form->isSubmitted()) {
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
    public function modifier(Request $request)
    {
        $session = $request->getSession();
        $user = new Joueur();
        $form = $this->createForm(Recuperertype::class, $user);
        $form->handleRequest($request);
        $a = $session->get("email");
        $b = $session->get("mdp");

        if ($form->isSubmitted()) {
            if ($b == $user->getCode() && ($user->getPassword() == $user->getPassword1())) {

                $user2 = $this->getDoctrine()->getRepository(Joueur::class)->findOneBy(
                    ['email' => $a],
                );

                $user2->setPassword(md5($user->getPassword()));
                $this->getDoctrine()->getManager()->flush();
                $session->invalidate();
                return $this->redirectToRoute("user_login");


            } else {

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


        return $this->render('joueur/partie_normal.html.twig');
    }

    /**
     * @Route("/normalgamejoueur2", name="normal_game2", methods={"GET"})
     */
    public function normalgamej2(): Response
    {


        return $this->render('joueur/partienormal2.html.twig');
    }

    /**
     * @Route("/partietestjouer", name="partie_test", methods={"GET"})
     */
    public function testgamej(): Response
    {


        return $this->render('joueur/partie_test.html.twig');
    }

    /**
     * @Route("/choselvljoueur", name="chose_level", methods={"GET"})
     */
    public function choseleveljoueur(Request $request): Response
    {
        $this->testfront($request);

        return $this->render('joueur/choselvl.html.twig');
    }

    /**
     * @Route("/rapidjoueur", name="rapid_game", methods={"GET"})
     */
    public function adminjouerpartierapide(): Response
    {


        return $this->render('joueur/partierapide.html.twig');
    }

    /**
     * @Route("/accueiljoueur", name="accueil_joueur", methods={"GET"})
     */
    public function accueiljoueur(): Response
    {


        return $this->render('joueur/accueil.html.twig');
    }

    /**
     * @Route("/login/login", name="user_login", methods={"GET","POST"})
     */
    public function login(Request $request, AdminRepository $userrep, JoueurRepository $repository)
    {
        $admin = new Admin();


        $session = $request->getSession();
        $session->set('email', "");
        $form = $this->createForm(LoginType::class, $admin);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            $email = $admin->getEmail();
            $mdp = md5($admin->getPassword());
            $id1 = $admin->getId();

            $user2 = $repository->findJoueur($email, $mdp);

            $user3 = $userrep->findAdmin($email, $mdp);

            if ($user2 != null) {
                $session->start();
                $userjoueur = $this->getDoctrine()->getRepository(Joueur::class)->findOneBy(
                    ['email' => $email],
                );
                $entityManager = $this->getDoctrine()->getManager();

                $historique=$userjoueur->getHistoriquedeconnection();
                $k=$historique->getNbfois();
                $historique->setNbfois($k+1);
                $userjoueur->setHistoriquedeconnection($historique);
                $entityManager->flush();
                $session->set('email', $email);
                $session->set('mdp', $mdp);
                $session->set('image', $userjoueur->getImage());
                $session->set('lvl', $userjoueur->getNiveau());

                $session->set('id', $userjoueur->getId());
                return $this->redirectToRoute("accueil_joueur");
            }
            if ($user3 != null) {

                $session->start();
                $useradmin = $this->getDoctrine()->getRepository(Admin::class)->findOneBy(
                    ['email' => $email],
                );
                $session->set('email', $email);
                $session->set('mdp', $mdp);
                $session->set('image', $useradmin->getImage());

                $session->set('id', $useradmin->getId());

                return $this->redirectToRoute("user_backa");

            } else {
                $this->addFlash('notice', ' Verifier vos paramétres  !');

            }


        }

        return $this->render('Login.html.twig', [

            'form' => $form->createView()
        ]);

    }

    /**
     * @Route("/addrapidgame2/{score}/{level}/{partietype}", name="addrapid_game2", methods={"GET","POST"})
     */
    public function ajouterrapidgame2(Request $request, $score, $level, $partietype): Response
    {
        $session = $request->getSession();
        $jeu=new Jeu();

        $jeu->setScore($score);
        $jeu->setLevel($level);
        $jeu->setPartietype($partietype);
        $entityManager = $this->getDoctrine()->getManager();
        $joueur =$entityManager->getRepository(Joueur::class)->findOneBy(['email' =>$session->get('email') ]);
        if ( $level == 10 && $score >= 10 && $joueur->getNiveau() == 1)
        {

            $session->set('lvl',2);
            $session->set('lvl2',2);

            $joueur->setNiveau(2);

        }
        if ( $level == 5 && $score >= 10 && $joueur->getNiveau() == 2)
        {
            $session->set('lvl',3);
            $session->set('lvl2',3);

            $joueur->setNiveau(3);
        }

        $jeu->addJoueur($joueur);
        $entityManager->persist($jeu);
        $entityManager->persist($joueur);

        $entityManager->flush();
        return $this->redirectToRoute('rapid_game');

    }

    /**
     * @Route("/addnormalgame/{score}/{time}/{timemax}/{partietype}/{level}", name="addnormalgame", methods={"GET","POST"})
     */
    public function addnormalgame(Request $request, $score, $time,$timemax, $partietype,$level): Response
    {
        $session = $request->getSession();

        $jeu = new Jeu();
        $jeu->setScore($score);
        $jeu->setTemps($time);
        $jeu->setTempsmax($timemax);

        $jeu->setPartietype($partietype);
        $jeu->setLevel($level);

        $entityManager = $this->getDoctrine()->getManager();
        $joueur = $entityManager->getRepository(Joueur::class)->findOneBy(['email' => $session->get('email')]);
        if ($joueur->getNiveau()<4 && $time!=0 )
        {
            $session->set('lvl',4);
            $session->set('lvl2',4);

            $joueur->setNiveau(4);
        }

        $jeu->addJoueur($joueur);

        $entityManager->persist($jeu);
        $entityManager->persist($joueur);

        $entityManager->flush();
        return $this->redirectToRoute('normal_game');

    }

    public function testfront(Request $request)
    {

        $session = $request->getSession();
        $a = $session->get("email");
        $user2 = $this->getDoctrine()->getRepository(\App\Entity\Joueur::class)->findOneBy(
            ['email' => $a],
        );
        $jeus = $user2->getJeux();
        $a = 0;
        $b = 0;
            //Tree type and level unique
        $groupType = $groupLevel = $score = array();
        foreach ($jeus as $jeu) {
            /**
             * @var Jeu $jeu
             */

            $groupLevel['level'][] = $jeu->getLevel();
            $groupType['type'][] = $jeu->getPartietype();
            $score['score'][] = $jeu->getScore();

        }
        if (isset(   $groupLevel['level'] )) {

            $groupLevel = array_unique($groupLevel['level']);
            $groupType = array_unique($groupType['type']);
            $score = array_unique($score['score']);

            $maxscore = -1;
            foreach ($jeus as $jeu) {
                foreach ($groupType as $type) {
                    foreach ($groupLevel as $level) {
                        if (($jeu->getLevel() == $level) && ($jeu->getPartietype() == $type)) {
                            if ($type == "normal") {
                                $arayResults[$type][$level]['temps'][] = $jeu->getTemps();
                                $b=1;
                            }
                            $arayResults[$type][$level]['score'][] = $jeu->getScore();


                        }
                    }
                }
            }

            foreach ($groupType as $type) {
                foreach ($groupLevel as $level) {

                    if (isset($arayResults[$type][$level]['score']) && (count($arayResults[$type][$level]['score'])) > 1) {
                        //get Max Score
                        if ($type == "normal") {
                            $temps = $arayResults[$type][$level]['temps'];

                            $arayResults[$type][$level]['temps'] = min(array_values($temps));
                        }
                        $scores = $arayResults[$type][$level]['score'];
                        $arayResults[$type][$level]['score'] = max(array_values($scores));


                        // dd($arayResults[$type][$level]);

                    } elseif (isset($arayResults[$type][$level]['score']) && (count($arayResults[$type][$level]['score'])) == 1) {
                        $arayResults[$type][$level]['score'] = $arayResults[$type][$level]['score'][0];

                    }
                }
            }

//        $nb2aa=$this->getDoctrine()->getRepository(Jeu::class)->test2();
//        $nb1=$this->getDoctrine()->getRepository(Jeu::class)->test("1","rapide");
//        $nb2=$this->getDoctrine()->getRepository(Jeu::class)->test("3","rapide");
//        $nb3=$this->getDoctrine()->getRepository(Jeu::class)->test("5","rapide");
//        $nb4=$this->getDoctrine()->getRepository(Jeu::class)->test("8","rapide");
//        $nb5=$this->getDoctrine()->getRepository(Jeu::class)->test("10","rapide");
//        $nb120=$this->getDoctrine()->getRepository(Jeu::class)->test("20","rapide");


            $this->get('twig')->addGlobal('maxscores', $arayResults);
        }
        else
        {
            $a=1;

        }
        $this->get('twig')->addGlobal('testbool',$a);
        $this->get('twig')->addGlobal('testbool2',$b);


    }

    public function testback(Request $request,$id)
    {

        $session = $request->getSession();
        $user2 = $this->getDoctrine()->getRepository(\App\Entity\Joueur::class)->findOneBy(
            ['id' => $id],
        );
        $jeus = $user2->getJeux();
        $a = 0;
        $b = 0;
        //Tree type and level unique
        $groupType = $groupLevel = $score = array();
        foreach ($jeus as $jeu) {
            /**
             * @var Jeu $jeu
             */

            $groupLevel['level'][] = $jeu->getLevel();
            $groupType['type'][] = $jeu->getPartietype();
            $score['score'][] = $jeu->getScore();

        }
        if (isset(   $groupLevel['level'] )) {

            $groupLevel = array_unique($groupLevel['level']);
            $groupType = array_unique($groupType['type']);
            $score = array_unique($score['score']);

            $maxscore = -1;
            foreach ($jeus as $jeu) {
                foreach ($groupType as $type) {
                    foreach ($groupLevel as $level) {
                        if (($jeu->getLevel() == $level) && ($jeu->getPartietype() == $type)) {
                            if ($type == "normal") {
                                $arayResults[$type][$level]['temps'][] = $jeu->getTemps();
                                $b=1;
                            }
                            $arayResults[$type][$level]['score'][] = $jeu->getScore();


                        }
                    }
                }
            }

            foreach ($groupType as $type) {
                foreach ($groupLevel as $level) {

                    if (isset($arayResults[$type][$level]['score']) && (count($arayResults[$type][$level]['score'])) > 1) {
                        //get Max Score
                        if ($type == "normal") {
                            $temps = $arayResults[$type][$level]['temps'];

                            $arayResults[$type][$level]['temps'] = min(array_values($temps));
                        }
                        $scores = $arayResults[$type][$level]['score'];
                        $arayResults[$type][$level]['score'] = max(array_values($scores));


                        // dd($arayResults[$type][$level]);

                    } elseif (isset($arayResults[$type][$level]['score']) && (count($arayResults[$type][$level]['score'])) == 1) {
                        $arayResults[$type][$level]['score'] = $arayResults[$type][$level]['score'][0];

                    }
                }
            }

            $this->get('twig')->addGlobal('maxscores', $arayResults);
        }
        else
        {
            $a=1;

        }
        $this->get('twig')->addGlobal('testbool',$a);
        $this->get('twig')->addGlobal('testbool2',$b);


    }
    /**
     * @Route("/mapFjoueur/{id}", name="mapFjoueur"))
     */
    public function map($id){
        $em=$this->getDoctrine()->getRepository(Joueur::class);
        $th=$em->find($id);

        return $this->render('joueur/MAP.html.twig', [
            'f'=>$th

        ]);

    }
   public function testtemps(Request $request)
   {
       $session = $request->getSession();
       $a = $session->get("email");
       $user2 = $this->getDoctrine()->getRepository(\App\Entity\Joueur::class)->findOneBy(
           ['email' => $a],
       );
       $cnt=0;
       $cnt2=0;
$calc=0;
       $jeus = $user2->getJeux();
       foreach ($jeus as $jeu) {
        $cnt+=$jeu->getTemps();
        $cnt2+=$jeu->getTempsmax();
       }
$calc= (100-($cnt*100)/$cnt2);
       $this->get('twig')->addGlobal('calc',$calc);

       $a = 0;
       $b = 0;


   }
    /**
     * @Route("/afficherscore/{id}", name="score_show", methods={"GET"})
     */
    public function showscore(Joueur $joueur, Request $request): Response
    {
$this->testtemps($request);
        $this->testfront($request);
        $jeu = $joueur->getJeux();

        return $this->render('joueur/voirscore.html.twig', [
            'joueur' => $joueur,
            'jeux' => $jeu,
        ]);

    }
    /**
     * @Route("/afficherscoreback/{id}", name="score_showback", methods={"GET"})
     */
    public function showscoreback(Joueur $joueur, Request $request): Response
    {

        $this->testback($request,$joueur->getId());
        $jeu = $joueur->getJeux();

        return $this->render('admin/voirscores.html.twig', [
            'joueur' => $joueur,
            'jeux' => $jeu,
        ]);

    }

    /**
     * @Route("/{id}", name="joueur_show", methods={"GET"})
     */
    public function show(Joueur $joueur): Response
    {
        $jeu = $joueur->getJeux();
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
        if ($this->isCsrfTokenValid('delete' . $joueur->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($joueur);
            $entityManager->flush();
        }

        return $this->redirectToRoute('joueur_index');
    }
}

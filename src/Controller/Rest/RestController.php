<?php

namespace App\Controller\Rest;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as FOSRest; 
use FOS\RestBundle\View\ViewHandler;
use FOS\RestBundle\View\View;
use App\Entity\User;
use App\Entity\Choice;

class RestController extends Controller
{

     /**
     * Création d'un utilisateur
     * @FOSRest\View(statusCode=Response::HTTP_CREATED)
     * @FOSRest\Post("/createUser")
     * 
     * @return array
     */
    public function postUserAction(Request $request) : View
    {
        $user = new User();
        $user->setUsername($request->get('username'))
            ->setMail($request->get('mail'))
            ->setBirth($request->get('birth'))
            ->setRegister(Date('Y-m-d'));

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        return View::create($user, Response::HTTP_CREATED, []);
    }

     /**
     * Création d'un choix d'utilisateur
     * @FOSRest\View(statusCode=Response::HTTP_CREATED)
     * @FOSRest\Post("/createUserChoice")
     * 
     */
    public function postUserChoiceAction(Request $request) : View
    {

        $userChoices = $this->get('doctrine.orm.entity_manager')
                            ->getRepository('App:Choice')
                            ->findAll($request->get('user_id'));

        if(empty($user)||count($userChoices)<3){

            $choice = new Choice();
            $choice->setUserId($request->get('user_id'))
                   ->setFilmId($request->get('film_id'))
                   ->setFilmName($request->get('film_name'))
                   ->setFilmScreen($request->get('film_screen'));

            $em = $this->getDoctrine()->getManager();
            $em->persist($choice);
            $em->flush();

            return View::create($choice, Response::HTTP_CREATED, []);

        }

    }
     /**
     * Suppression d'un choix d'utilisateur
     * @FOSRest\View(statusCode=Response::HTTP_NO_CONTENT)
     * @FOSRest\Delete("/user/deleteUserChoice")
     * 
     * @return array
     */
    public function deleteUserDeleteChoiceAction(Request $request) : View
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $choise = $em->getRepository('App:Choice')
                     ->find($request->get('id'));

        $em->remove($choise);
        $em->flush();
        
        return View::delete($choice, Response::HTTP_NO_CONTENT);
    }

    /**
     * Liste des choix d'un utilisateur
     * @FOSRest\Get("/allChoices/{user_id}")
     * 
     * @return array
     */
    public function getUserAllChooseAction(Request $request) : View
    {

        $choices =  $this->get('doctrine.orm.entity_manager')
                         ->getRepository('App:Choice')
                         ->findBy(
                             array('userId' => $request->get('user_id'))
                            );

        $formatted = [];
        foreach($choices as $choice){
            $formatted[] = [
                'userId'    => $choice->getUserId(),
                'filmId'    => $choice->getFilmId(),
                'filmName'  => $choice->getFilmName(),
                'filmScreen'  => $choice->getFilmScreen(),
            ];
        }

        return View::create($formatted, Response::HTTP_OK , []);
    }

    /**
     * Utilisateurs ayant choisi un film
     * @FOSRest\Get("/activeUser")
     */
    public function getActiveUser(Request $request) : view
    {
        $em = $this->get('doctrine.orm.entity_manager');

        $query = $em->createQuery(
            'SELECT distinct(u), u
            FROM App\Entity\User u
            JOIN App\Entity\Choice c
            WITH u.id = c.userId'
        );

        $users = $query->execute();

        $formatted = [];
        foreach($users as $user){
            
            $formatted[] = [
                'username' => $user[0]->getUsername(),
                'mail' => $user[0]->getMail(),
                'birth' => $user[0]->getBirth(),
                'register' => $user[0]->getRegister(),
            ];
            
        } 

        return View::create($formatted, Response::HTTP_OK , []);
    }


    /**
     * Film le plus recommandé
     * @FOSRest\Get("/rank")
     * 
     * @return array
     */
    public function getRankingFilm(Request $request) : view
    {
        $em = $this->get('doctrine.orm.entity_manager');

        $query = $em->createQuery(
            'SELECT c, count(c.filmName) r
            FROM App\Entity\Choice c 
            GROUP BY c.filmName
            ORDER BY r DESC
            '
        )->setMaxResults(1);
        $ranks = $query->execute();
        
        $formatted = [];
        foreach($ranks as $rank){
            
            $formatted[] = [
                'num' => $ranks[0]['r'],
                'filmId' => $rank[0]->getFilmId(),
                'filmName' => $rank[0]->getFilmName(),
                'filmScreen' => $rank[0]->getFilmScreen(),
            ];
            
        } 

        return View::create($formatted, Response::HTTP_OK , []);

    }

}
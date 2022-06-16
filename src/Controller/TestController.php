<?php


namespace App\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\Cookie;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class TestController extends AbstractController
{
    /**
     * @Route("/NewRelic", name="NewRelic",methods={"GET","POST"})
     */
    public  function index(Request $request)
    { 
        $session=$request->getSession();
       
        //echo $request->getPathInfo(); // done l'url de la requête

        /*$urlParametres=$request->query->all();
        var_dump($request->query->all());
        echo "<hr>";*/
        
        $response=new Response();
       if( $request->query->has('nom')){
        $nom=$request->query->get('nom');
        $session->set('nom',$nom);
        if($request->query->has('age')){
         
            $age=$request->query->get('age');
            $response->headers->setcookie(Cookie::create('age',$age)); 
            }
            
             $url=$this->generateUrl('redirection');
            
             return $this->redirect($url);
         }
        else{
            /*$respons=$response = new JsonResponse();
            $response->setData(['404' => 'Bienvenue inconnu !']); 
            
            $response->setStatusCode(Response::HTTP_NOT_FOUND);  // on doit avoir Status=404 ???*/
            $session->set('nom','inconnu'); 
            $response=new RedirectResponse('http://localhost:8081/NewRelic?nom=inconnu');

            
            return $response;
        }
       
        
        
        
    }

     /**
     * @Route("/redirection", name="redirection")
     */

    public  function redirection(request $request){
        $response=new response();
        $session=$request->getSession();
        $nom=$session->get('nom');

        $response->headers->setcookie(Cookie::create('age','23'));

        $response->setContent('Bienvenue'.' '.$nom.'!');
        $response->setStatusCode(Response::HTTP_OK);

        return $response;

    }

    /**
     * @Route("/hello/{age}/{nom}/{prenom}", name="hello",requirements={"nom"="[a-z]{2,30}"})  
     */
    

    public function hello(Request $request,int $age,$nom,$prenom=''){
        // $session=$request->getSession();
        // $session->getFlashBag()->add('info','info 1');
        // $session->getFlashBag()->add('info','info 2');
        // $session->getFlashBag()->add('info','info 3');
       // echo $_ENV['a'];  // on peut acceder à une variable type env
       
        return $this->render('test/hello.html.twig', ['nom'=>$nom, 'prenom'=>$prenom,'age'=>$age]);  // Pour passer les parametres ..
    }


}




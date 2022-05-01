<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class ToDoController extends AbstractController
{
    /**
     * @Route ("/todo",name="toDo")
     */

    public function index(SessionInterface $session): Response
    {
        if (!$session->has('todos')){
            $todos=[
                'achat'=>'acheter clé usb',
                'cours'=>'Finaliser mon cours',
                'correction'=>'corriger mes examens'
            ];
            $session->set('todos',$todos);
            $this->addFlash('welcome',"Bienvenu dans votre liste de todos");
        }
        return $this->render('to_do/index.html.twig', [
            'controller_name' => 'ToDoController',
        ]);
    }

    /**
     * @Route ("/todo/add/{titre}/{description}",name="addTodo")
     */

    public function addTodo($titre, $description , SessionInterface $session) : RedirectResponse {
        if(!$session->has('todos')){
            $this->addFlash('error',"La liste des todo n'est pas encore inisialisée!");
        }else{
            $todos=$session->get('todos');
            if (isset($todos[$titre])){
                $this->addFlash('error',"le todo $titre existe déjà!");
            }else{
                $todos[$titre]=$description;
                $session->set('todos',$todos);
                $this->addFlash('succes',"le todo $titre est ajouté avec succès!");
            }
        }
        return $this->redirectToRoute('toDo');
    }

    /**
     * @Route ("/todo/update/{titre}/{description}",name="updateTodo")
     */

    public function updateTodo($titre, $description , SessionInterface $session) : RedirectResponse {
        if(!$session->has('todos')){
            $this->addFlash('error',"La liste des todo n'est pas encore inisialisée!");
        }else{
            $todos=$session->get('todos');
            if (!isset($todos[$titre])){
                $this->addFlash('error',"le todo $titre n'existe pas!");
            }else{
                $todos[$titre]=$description;
                $session->set('todos',$todos);
                $this->addFlash('succes',"le todo $titre a été mis à jour avec succès!");
            }
        }
        return $this->redirectToRoute('toDo');
    }

    /**
     * @Route("/todo/delete/{titre}",name="deleteTodo")
     */

    public function deleteTodo(SessionInterface $session,$titre) : RedirectResponse {
        if(!$session->has('todos')){
            $this->addFlash('error',"La liste des todo n'est pas encore inisialisée!");
        }else{
            $todos=$session->get('todos');
            if (!isset($todos[$titre])){
                $this->addFlash('error',"le todo $titre n'existe pas dans la liste!");
            }else{
                unset($todos[$titre]);
                $session->set('todos',$todos);
                $this->addFlash('succes',"le todo $titre a été supprimé avec succès!");
            }
        }
        return $this->redirectToRoute('toDo');
    }

    /**
     * @Route("/todo/reset" , name="resetTodo")
     */
    public function resetTodo(SessionInterface $session) : RedirectResponse {
        $session->remove('todos');
        $this->addFlash('succes',"le TodoRest est effectué avec succès!");
        return $this->redirectToRoute('toDo');
    }
}

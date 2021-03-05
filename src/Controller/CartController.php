<?php

namespace App\Controller;

use App\Service\Cart\CartService;
use App\Entity\Article;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CartController extends AbstractController
{
    /**
     * @Route("/panier", name="cart_index")
     */
    public function index(CartService $carta)
    {
        return $this->render('cart/index.html.twig', [
            'items' => $carta->getFullCart(),
            'total' => $carta->getTotal()
        ]);
    }

    /**
     * @Route("/panier/ajouter/{id}" , name="cart_add", requirements={"id":"\d+"})
     */
    public function add($id, CartService $carta)//tekhou id mtaa produit + session
    {

        $carta->add( $id );
        
        return $this->redirectToRoute("article_home");
    }

    /**
     * @Route("panier/supprimer/{id}", name="cart_remove")
     */

     public function remove($id, CartService $carta)
    {
         
        $carta->remove($id);
         return $this->redirectToRoute("cart_index");
    }
}

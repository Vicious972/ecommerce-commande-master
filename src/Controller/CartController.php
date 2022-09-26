<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CartController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/mon-panier', name: 'app_cart')]
    public function index(Cart $cart): Response
    {
        $cartComplete = [];
        foreach ($cart->get() as $id => $quantity) {
            $cartComplete[] = [
                'product' => $this->entityManager->getRepository(Product::class)->findOneById($id),
                'quantity' => $quantity
            ];
       }

        // dd($cartComplete);
        
        return $this->render('cart/index.html.twig', [
            'cartComplete' => $cartComplete
        ]);
    }


    // passer les paramètres à la route et débug avec un (dd)
    #[Route('/cart/add/{id}', name: 'add_to_cart')]
    public function add(Cart $cart, $id): Response
    {   
        $cart->add($id);
        // dd($cart);
        return $this->redirectToRoute('app_cart');
    }

    //  vider le panier et rediriger à la page produit :D
    #[Route('/cart/remove', name: 'remove_my_cart')]
    public function remove(Cart $cart): Response
    {   
        $cart->remove();
        
        return $this->redirectToRoute('products');
    }
    // je passe à mon url l'id du produit que je dois supprimer
    #[Route('/cart/delete/{id}', name: 'delete_my_cart')]
    public function delete(Cart $cart, $id): Response
    {   
        $cart->delete($id);
        
        return $this->redirectToRoute('app_cart');
    }

    #[Route('/cart/decrease{id}', name: 'decrease_to_cart')]
    public function decrease(Cart $cart,$id): Response
    {
        $cart->decrease($id);

        return $this->redirectToRoute('app_cart');
    }

    
}

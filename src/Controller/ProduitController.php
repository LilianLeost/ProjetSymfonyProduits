<?php

namespace App\Controller;

use App\Data\DataRecherche;
use App\Form\RechercheType;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProduitController extends AbstractController
{
    /**
     * @Route("/", name="app_produit_index")
     */
    public function index(ProduitRepository $repository,Request $request): Response
    {

        //Construction du formulaire
        $dataFormulaire = new DataRecherche();
        $form = $this->createForm(RechercheType::class,$dataFormulaire);

        $form->handleRequest($request);

        //$produits = $repository->findAll();
        //$produits = $repository->findAllDQL();
        //$produits = $repository->findAllSQL();
        $produits = $repository->findAllAvecCategories($dataFormulaire);

        return $this->render('produit/index.html.twig', [
            'produits' => $produits,
            'form' => $form->createView()
        ]);
    }
}

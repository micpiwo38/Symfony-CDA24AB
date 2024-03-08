<?php

namespace App\Controller;

use App\Entity\Produits;
use App\Form\ProduitsType;
use App\Repository\CategoriesRepository;
use App\Repository\DistributeursRepository;
use App\Repository\ProduitsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use function MongoDB\BSON\fromJSON;

//ROUTE GLOBALE DU CONTROLLER


class ProduitsController extends AbstractController
{
    /**
     * Undocumented function
     *
     * @param ProduitsRepository $produitsRepository
     * @param CategoriesRepository $categoriesRepository
     * @param DistributeursRepository $distributeursRepository
     * @return Response
     */
    #[Route("/produits", name: "app_produits")]
    public function AfficherProduits(
        ProduitsRepository $produitsRepository,
        CategoriesRepository $categoriesRepository,
        DistributeursRepository $distributeursRepository

    ): Response {
        return $this->render('produits/afficher_produits.html.twig', [
            "produits" => $produitsRepository->findAll(),
            "categories" => $categoriesRepository->findAll(),
            "distributeurs" => $distributeursRepository->findAll()
        ]);
    }

    //Serialisé les donnée
    #[Route("/produits-json", name: "app_produits_api")]
    public function produitsAPI(
        ProduitsRepository $produitsRepository,
        SerializerInterface $serializer
    ):JsonResponse{
        $produits = $produitsRepository->findAll();
        $produits_to_json = $serializer->serialize($produits, 'json');

        return new JsonResponse($produits_to_json, Response::HTTP_OK, [], true);
    }

    #[Route("/afficher-produits-json", name: "app_produits_json")]
    public function afficherProduitsJson():Response{
        return $this->render('produits/afficher_produits_json.html.twig');
    }





    //Ajouter un produits
    #[Route("/ajouter-produits", name: "app_ajouter_produit")]
    /**
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function ajouterProduits(
        Request $request,
        EntityManagerInterface $em
    ): Response {

        $produits = new Produits();
        $form = $this->createForm(ProduitsType::class, $produits);
        //Recup les attribut name via $_POST['name']
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($produits);
            //execute la requète SQL
            $em->flush();
            //Si ca marche
            return $this->redirectToRoute('app_produits');
        }

        return $this->render('produits/ajouter_produits.html.twig', [
            'produits_form' => $form->createView()
        ]);
    }

    //Editer un produits
    #[Route("/editer-produit/{id}", name: "app_editer_produit")]
    /**
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param Produits $produits
     * @return Response
     */
    public function editerProduits(
        Request $request,
        EntityManagerInterface $em,
        Produits $produits
    ): Response {


        $form = $this->createForm(ProduitsType::class, $produits);
        //Recup les attribut name via $_POST['name']
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($produits);
            //execute la requète SQL
            $em->flush();
            //Si ca marche
            return $this->redirectToRoute('app_produits');
        }

        return $this->render('produits/editer_produits.html.twig', [
            'produits_form' => $form->createView(),
            'produits' => $produits
        ]);
    }




    #[Route("/produit/{id}", name: "app_details_produit")]

    /**
     * @param Produits $produits
     * @return Response
     */
    public function detail_produit(Produits $produits): Response
    {
        return $this->render('produits/details_produits.html.twig', [
            "produit" => $produits
        ]);
    }

    #[Route('/supprimer-produit/{id}', name: 'app_supprimer_produit', methods: ['POST'])]

    /**
     * @param Request $request
     * @param Product $product
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function delete(Request $request, Produits $produits, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$produits->getId(), $request->request->get('_token'))) {
            $entityManager->remove($produits);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_produits', [], Response::HTTP_SEE_OTHER);
    }

    #[Route("/produits-categorie", name: "app_produit_categorie")]
    public function getProduitsByCategorie(
        ProduitsRepository $produitsRepository,
        Request $request
    ): Response {
        //Recupere la valeur du <select name="categorie_id> etc....
        $categorieID = $request->request->get('categorie_id');
        //dd($categorieID);
        return $this->render('produits/produit-categorie.html.twig', [
            'produits' => $produitsRepository->produitsByCategorie($categorieID)
        ]);
    }

    #[Route("/produits-distributeur", name: "app_produit_distributeur")]

    /**
     * @param Request $request
     * @param ProduitsRepository $produitsRepository
     * @return Response
     */
    public  function  getProduitsByDistributeur(
        Request $request,
        ProduitsRepository $produitsRepository
    ):Response{
        $distributeurID = $request->request->get("distribiteur_id");
        //dd($distributeurID);
        $resultat = $produitsRepository->produitsByDistributeurs($distributeurID);
        //dd($resultat);

        return $this->render('produits/produit-distributeur.html.twig',[
            'produits' => $resultat
        ]);
    }



}
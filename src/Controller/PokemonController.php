<?php

namespace App\Controller;

use App\Entity\Pokemon;
use App\Form\PokemonType;
use App\Repository\PokemonRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PokemonController extends AbstractController
{
    //afficher tous les pokemons
    #[Route('/pokemon',name:'Pokemons')]
    public function index(PokemonRepository $pokemonrepo): Response
    {
        $pokemons = $pokemonrepo->findAll();
        return $this->render('pokemon/index.html.twig', [
            'pokemons' => $pokemonrepo->findAll()
        ]);
    }


//ajout new pokemon 
#[Route('/pokemon/new')] 


    public function new(Request $REQUEST, EntityManagerInterface $em)
    //request : http foundation et pas browserkit
{
        // je declare une instance de POKEMON
        $pokemon = new Pokemon();

        // $pokemon->setName('miew')
        // ->setDescription('le plus fort')
        // ->setType('psy')
        // ->setLvl('3');
        // dd($pokemon);

        //la methode create form permet de recuperer le form à partir du form type
        $formPokemon=$this->createForm(PokemonType::class,$pokemon);
        $formPokemon->handleRequest($REQUEST);
        if($formPokemon->isSubmitted() && $formPokemon->isValid()) {
            $em->persist($pokemon);
            $em->flush();
        dd('pokemon enregistré');
        //redirige vers la page apres envoie du nouveau Pokemon
        return $this->redirectToRoute('pokemons');
}
        //renvoie le formulaire à la view (url)
       return $this-> render('pokemon/new.html.twig',[

           'formPokemon' => $formPokemon
        ]);
        
}
}



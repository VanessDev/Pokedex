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
    #[Route('/pokemon', name: 'Pokemons', methods: 'GET')]
    public function index(PokemonRepository $pokemonrepo): Response
    {
        $pokemons = $pokemonrepo->findAll();
        return $this->render('pokemon/index.html.twig', [
            'pokemons' => $pokemonrepo->findAll()
        ]);
    }

    //afficher un pokemon par son ID
    #[Route('/pokemon/show/{id}', name: "pokemon_show", methods: 'GET')]
    public function show(PokemonRepository $PokemonRepository, int $id)
    {
        $pokemon = $PokemonRepository->findOneBy(['id' => $id]);
        return $this->render('pokemon/show.html.twig', [
            'pokemon' => $pokemon
        ]);
    }

    //ajout new pokemon 
    #[Route('/pokemon/new', name: 'pokemon_new')]
    public function new(Request $REQUEST, EntityManagerInterface $em)
    {
        // je declare une instance + variable de POKEMON
        $pokemon = new Pokemon();

        // $pokemon->setName('miew')
        // ->setDescription('le plus fort')
        // ->setType('psy')
        // ->setLvl('3');
        // dd($pokemon);

        //la methode create form permet de recuperer le form à partir du form type
        $formPokemon = $this->createForm(PokemonType::class, $pokemon);
        //on verifie s'il est soumis grâce à la request
        $formPokemon->handleRequest($REQUEST);
        //isValid verifie si tout est ok
        if ($formPokemon->isSubmitted() && $formPokemon->isValid()) {
            //em = entity manager(doctrine)/ persist prepare requettes
            $em->persist($pokemon);
            //flush execute les requetes
            $em->flush();
            dd('pokemon enregistré');
            //redirige vers la page apres envoie du nouveau Pokemon
            return $this->redirectToRoute('pokemons');
        }
        //renvoie le formulaire à la view (url)
        return $this->render('pokemon/new.html.twig', [

            'formPokemon' => $formPokemon
        ]);

    }

    #[Route('/pokemon/delete/{id}', name: 'pokemon_delete')]
    public function delete(int $id, Request $request, Pokemon $pokemon, EntityManagerInterface $em)
    {

        if ($this->isCsrfTokenValid('delete' . $id, $request->request->get('_token'))) {
            $em->remove($pokemon);
            $em->flush();

            return $this->redirectToRoute('Pokemons');
        } else {
            dd('token pas bon');
        }
    }


    #[Route('/pokemon/{id}/edit')]
    // j'ai ma variable Pokemon de prete
    public function edit(Pokemon $pokemon)
    {
        $form = $this->createForm(PokemonType::class, $pokemon);

        return $this->render('pokemon/edit.html.twig', [
            'form' => $form

        ]);

    }
}





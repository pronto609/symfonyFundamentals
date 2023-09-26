<?php

namespace App\Controller;

use App\Entity\VinylMix;
use App\Repository\VinylMixRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MixController extends AbstractController
{
    #[Route('/mix', name: 'app_mix')]
    public function index(): Response
    {
        return $this->render('mix/index.html.twig', [
            'controller_name' => 'MixController',
        ]);
    }

    #[Route('/mix/new')]
    public function new(EntityManagerInterface $entityManager): Response
    {
        $mix = new VinylMix();
        $mix->setTitle('Do you remember... Phill Collins?');
        $mix->setDescription('A pure mix of drummers turned singers!');
        $genres = ['pop', 'rock', 'heavy_metal'];
        $mix->setGenre($genres[array_rand($genres)]);
        $mix->setTrackCount(rand(5, 20));
        $mix->setVotes(rand(-50, 50));

        $entityManager->persist($mix);
        $entityManager->flush();

        return new Response(sprintf(
            'Mix %d is %d tracks of pure 80\'s haven',
            $mix->getId(),
            $mix->getTrackCount()
        ));
    }

    #[Route('mix/{id}', name: 'app_mix_show')]
    public function show(int $id, VinylMixRepository $vinylMixRepository)
    {
        $mix = $vinylMixRepository->find($id);
        return $this->render('mix/show.html.twig', ['mix' => $mix]);
    }
}

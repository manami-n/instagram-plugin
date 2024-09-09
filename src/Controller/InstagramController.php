<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\InstagramService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;


#[Route('/')]
class InstagramController extends AbstractController
{
    private $instagramService;
    public function __construct(InstagramService $instagramService)
    {
        $this->instagramService = $instagramService;
    }
    
    #[Route(
        path:'embed1', 
        name: 'embed1',
        methods: ['GET']
    )]
    public function embed1(): Response
    {
        $instagram = $this->instagramService->instagram();
        $posts = $this->instagramService->getInstagramPosts();
        $profile = $instagram->getUserProfile();

        return $this->render('embed1.html.twig', [
            'posts' => $posts,
            'profile' => $profile,
        ]);
    }
            

    
    // log in (change later when redirect website is online)
    #[Route(
        path: '/login', 
        name: 'instagram_login', 
        methods: ['GET']
    )]
    public function login(): Response
    {
        $login = $this->instagramService->instagram();

        return $this->redirect($login->getLoginUrl());
    }

    
    // log in (change later when redirect website is online)
    #[Route(
        path: '/callback', 
        name: 'instagram_callback', 
        methods: ['GET']
    )]
    public function callback(): Response
    {
        $code = $_GET['code'];
        $instagram = $this->instagramService->instagram();
        $token = $instagram->getOAuthToken($code, true);
        $token = $instagram->getLongLivedToken($token, true);
        
        $instagram->setAccessToken($token);

        return new Response('Access token is valid. User: ' . json_encode($token), 200);
        
    }
    
    #[Route(
        path: '/getProfile', 
        name: 'instagram_profile', 
        methods: ['GET']
    )]
    public function getProfile(): Response
    {
        
        $instagram = $this->instagramService->instagram();
        $instagram->setAccessToken(''); // Add your own access token here
        
        $profile = $instagram->getUserProfile();
        $posts = $this->instagramService->getInstagramPosts();
        //dd($posts);
        return $this->render('embed1.html.twig', [
            'posts' => $posts,
            'profile' => $profile,
        ]);
    }
    
}

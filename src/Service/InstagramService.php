<?php

declare(strict_types=1);

namespace App\Service;

use EspressoDev\InstagramBasicDisplay\InstagramBasicDisplay;

class InstagramService
{
    private $instagram;

    //send to Controller
    public function __construct(string $accessToken, $appId, $appSecret)
    {
        // Initialize the Instagram API client with provided access token
        $this->instagram = new InstagramBasicDisplay($accessToken);
        $this->instagram = new InstagramBasicDisplay($appId);
        $this->instagram = new InstagramBasicDisplay($appSecret);
    }


    public function getInstagramPosts(int $count = 10): array
    {
        $this->setToken();

        // Fetch the user's media from Instagram API
        $response = $this->instagram->getUserMedia();
        
        // Ensure the response is correctly formed
        if (!isset($response->data)) {
            throw new \Exception('Invalid response from Instagram API');
        }
        $posts = [];
        // Process and return Instagram posts here
        foreach (array_slice($response->data, 0, $count) as $post) {
            $postPrep = [
                'id' => $post->id,
                'media_type' => $post->media_type,
                'media_url' => $post->media_url,
                'permalink' => $post->permalink,
                'timestamp' => $post->timestamp,
                'caption' => isset($post->caption) ? $post->caption : null,
                'children' => [], 
            ];
            if (isset($post->children)) {
                $postPrep['children'] = [];
                foreach ($post->children->data as $child) {
                    $postPrep['children'][] = [
                        'id' => $child->id,
                        'media_type' => $child->media_type,
                        'media_url' => $child->media_url,
                        'permalink' => $child->permalink,
                        'timestamp' => $child->timestamp,
                    ];
                }
                }
            $posts[] = $postPrep;
            }
        return $posts;
    }


    // Setting the token
    public function setToken()
    {
        $this->instagram->setAccessToken(''); // Add your own access token here
    }

    // trying to follow the instructions
    public function instagram()
    {
        $loginInfo = new InstagramBasicDisplay([
            'appId' => ,  // Add your appId from Meta Deverloper's account API
            'appSecret' => '', // Add your appSecret from Meta Deverloper's account API
            'redirectUri' => 'https://localhost:8443/callback',
        ]);
        
        return $loginInfo;
    }


    // to verify (debug) access token is valid or not
    public function verifyAccessToken(): array
    {
        // Fetch basic user information to verify the token
        $response = $this->instagram->getUserProfile();

        // Return user profile information or error message
        return [
            'user' => $response,
        ];
    }
}
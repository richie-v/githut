<?php
/**
 * Created by PhpStorm.
 * User: richard
 * Date: 23-2-19
 * Time: 22:14
 */

namespace AppBundle\Service;

use GuzzleHttp\Client as Client;

class GitHubApi
{
    public function getProfile($username)
    {
        $client = new Client();
        $response = $client->request('GET', 'https://api.github.com/users/' . $username);

        $data = json_decode($response->getBody()->getContents(), true);

        return [
            'avatar_url' => $data['avatar_url'],
            'name' => $data['name'],
            'login' => $data['login'],
            'details' => [
                'company' => $data['company'],
                'location' => $data['location'],
                'joined on' => 'Joined on ' . (new \DateTime($data['created_at']))->format('d m Y'),
            ],
            'blog' => $data['blog'],
            'social_data' => [
                'public_repos' => $data['public_repos'],
                'followers' => $data['followers'],
                'following' => $data['following'],
            ]
        ];

    }

    public function getRepos($username)
    {
        $client = new Client();
        $response = $client->request('GET', 'https://api.github.com/users/' . $username . '/repos');

        $data = json_decode($response->getBody()->getContents(), true);

        return [
            'repo_count' => count($data),
            'most_stars' => array_reduce($data, function ($mostStars, $currentRepo) {
                return $currentRepo['stargazers_count'] > $mostStars ? $currentRepo['stargazers_count'] : $mostStars;
            }, 0),
            'repos' => $data
        ];

    }
}
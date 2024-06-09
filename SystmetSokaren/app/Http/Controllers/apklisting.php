<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class apklisting extends Controller
{

    function getStores(){
        $url = "https://api-extern.systembolaget.se/sb-api-ecommerce/v1/sitesearch/site";
        $output = [];

        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', $url, [
            'headers' => [
                "accept" => "application/json",
                "access-control-allow-origin" => "*",
                "ocp-apim-subscription-key" => "cfc702aed3094c86b92d6d4ff7a54c84",
                "Referer" => "https://www.systembolaget.se/",
            ],
            'verify' => false, // Disable SSL verification
        ])
        ->getBody()
        ->getContents();

        $stores = json_decode($response, true);

        $stores = $stores['siteSearchResults'];

        foreach($stores as $item){
            $data = (object)[
                "siteId" => $item['siteId'],
                "displayName" => $item['displayName'],
                "city" => $item['city'],
            ];

            array_push($output, $data);
        } 

        return $output;
    }
    function index(){
        $stores = $this->getStores();

        $url = "https://api-extern.systembolaget.se/sb-api-ecommerce/v1/productsearch/search?size=30";

        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', $url, [
            'headers' => [
                "accept" => "application/json",
                "access-control-allow-origin" => "*",
                "ocp-apim-subscription-key" => "cfc702aed3094c86b92d6d4ff7a54c84",
                "Referer" => "https://www.systembolaget.se/",
            ],
            'verify' => false, // Disable SSL verification
        ])
        ->getBody()
        ->getContents();
        return view(index, ['stores' => $stores, 'products' => json_decode($response, true)]);
    }
}
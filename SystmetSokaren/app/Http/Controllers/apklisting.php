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
            if (strpos($item["siteId"], '-') == false) {
                $data = (object)[
                    "siteId" => $item['siteId'],
                    "displayName" => $item['displayName'],
                    "city" => $item['city'],
                ];
                array_push($output, $data);
            }
        } 

        return $output;
    }
    function index(){
        $stores = $this->getStores();

        $products = [];

        $url = "https://api-extern.systembolaget.se/sb-api-ecommerce/v1/productsearch/search?size=30";

        $client = new \GuzzleHttp\Client();

        for($i = 1; $i < 100; $i++){
            $response = $client->request('GET', $url . "&page=" . $i, [
                'headers' => [
                    "accept" => "application/json",
                    "access-control-allow-origin" => "*",
                    "ocp-apim-subscription-key" => "cfc702aed3094c86b92d6d4ff7a54c84",
                    "Referer" => "https://www.systembolaget.se/",
                ],
                'verify' => false, // Disable SSL verification
            ])->getBody()->getContents();
            $data = json_decode($response, true);
            
            if(!($data["metadata"]["nextPage"] > $i)){
                break;
            }
            $data = $data["products"];

            foreach($data as $item){
                $send = (object)[
                    "name" => $item["productNameBold"] . ";" . $item["productNameThin"],
                    "price" => $item["price"],
                    "volume" => $item["volume"],
                    "alcoholPercentage" => $item["alcoholPercentage"],
                    "url" => "systembolaget.se/" . $item["productNumber"],
                ];
                array_push($products, $send);
            }
        }

        return $products;
        // return view("index", ['stores' => $stores, 'products' => $products]);
    }
}
<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProductControllerTest extends WebTestCase
{
    private $client;

    public function setUp():void
    {
        $this->client = self::createClient([],[
            "CONTENT_TYPE" => "application/json",
            "HTTP_ACCEPT" => "application/json"
        ]);
    }
    public function testProduct()
    {
        // On peut créer un produit
        $this->client->request("POST", "/api/products");
        $response = $this->client->getResponse()->getContent();
        $data = json_encode($response, true);
        $this->assertNotEmpty($data);

        // Le titre fais moin de 10 caractères
       $this->client->request("POST", "/api/products", [],[],[], json_encode([
            "title" => "monTitre"
        ]));
        $this->assertResponseStatusCodeSame(201, "Le titre est incorrect");
//
//        // Le prix unitaire doit être strictement positif
//        $this->client->request("POST", "/api/products", [],[],[], json_encode([
//            "puht" => -5
//        ]));
//        $this->assertResponseStatusCodeSame(400, "Le chiffre n'est pas bon");
//        // La quantité est un nombre entier strictement positif
//        $this->client->request("POST", "/api/products", [],[],[], json_encode([
//            "quantity" => 4.5
//        ]));
//        $this->assertResponseStatusCodeSame(400, "ce n'est psa un nombre entier");
    }
}

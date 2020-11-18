<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class InvoiceControllerTest extends WebTestCase
{
    private $client;

    public function setUp():void
    {
        $this->client = self::createClient([],[
            "CONTENT_TYPE" => "application/json",
            "HTTP_ACCEPT" => "application/json"
        ]);
    }
    public function testCreate()
    {
    // on créer une facture
        $this->client->request("POST", "/api/invoices", [],[],[], json_encode([
            "title" => "facture",
            "number" => (string) time(),
            "client" => "bob"
        ]));
        $response = $this->client->getResponse()->getContent();
        $data = json_decode($response, true);
        $this->assertNotEmpty($data["id"], "");

        // Test si le nom du client est bien sur la facture
        $this->client->request("POST","/api/invoices",[],[],[], json_encode([
            "title"=> "jfb",
            "number" => 3645,
            "client" => null
        ]));
        $response2 = $this->client->getResponse()->getContent();
        $data2 = json_decode($response2, true);
        $this->assertResponseStatusCodeSame(400, "Client obligatoire");
        $this->assertNotEmpty($data2["client"]);


    }


}

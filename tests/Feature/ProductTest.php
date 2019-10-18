<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductTest extends TestCase
{

    public function graphql(string $query)
    {
        return $this->post('/graphql', [
            'query' => $query
        ]);
    }

    public function testGetProductById()
    {

        $response = $this->graphql("
                {
                  product(id:9)
                  {
                    id
                    product_name
                    product_price
                    features
                    {
                      id
                      feature_name
                      feature_description
                    }       
                  }
                }");

        $this->assertEquals("Pineapple",
            $response->json("data.product.product_name")
        );
    }
}

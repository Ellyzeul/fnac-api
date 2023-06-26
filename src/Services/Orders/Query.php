<?php
namespace FNAC\Services\Orders;

use FNAC\Interfaces\Service;
use FNAC\Common\Request;
use FNAC\Common\Response;

class Query implements Service
{
  private static array $acceptedParams = ['paging', 'date', 'sort_by', 'product_fnac_id', 'offer_fnac_id', 'offer_seller_id', 'state', 'states', 'order_fnac_id', 'orders_fnac_id'];
  private static array $specialParams = [
    'date' => [
      'type' => 'different_tags', 
      'tags' => ['min', 'max'], 
      'has_attr' => true, 
    ], 
    'states' => [
      'type' => 'same_tag', 
      'tag' => 'state', 
      'has_attr' => false, 
    ], 
    'orders_fnac_id' => [
      'type' => 'same_tag', 
      'tag' => 'order_fnac_id', 
      'has_attr' => false, 
    ]
  ];

  public static function execute(string $url, array $params): Response
  {
    $response = Request::postXML("$url/orders_query", "A ESCREVER");

    return new Response(true, ['unimplemented' => true]);
  }
}

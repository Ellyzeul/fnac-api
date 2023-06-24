<?php
namespace FNAC;

use CurlHandle;

class Utils
{
  private static string $baseUrl = 'https://vendeur.fnac.com/api.php';

  public static function postXML(string $endpoint, string $body): array
  {
    $ch = Utils::getCurlHandleForPostXML(Utils::$baseUrl . $endpoint, $body);

    $rawResponse = curl_exec($ch);
    $response = Utils::handlePostXMLResponse($rawResponse, $ch);
    
    curl_close($ch);

    return $response;
  }

  private static function getCurlHandleForPostXML(string $url, string $body)
  {
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
      'Content-Type: text/xml', 
      'Content-Length: ' . strlen($body), 
      'Connection: close', 
    ]);

    return $ch;
  }

  private static function handlePostXMLResponse(string | bool $response, CurlHandle $ch)
  {
    if($response === false) {
      $errno = curl_errno($ch);
      $error = curl_error($ch);

      return [
        'success' => false, 
        'response' => [
          'errno' => $errno, 
          'error' => $error
        ]
      ];
    }

    return [
      'success' => true, 
      'response' => simplexml_load_string($response)
    ];
  }
}

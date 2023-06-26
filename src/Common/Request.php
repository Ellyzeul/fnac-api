<?php
namespace FNAC\Common;

use FNAC\Common\Response;

class Request
{
  /**
   * Realiza uma requisição POST enviando um XML
   * 
   * @param string $url URL completa para requisição
   * @param string $body Corpo XML da requisição
   * 
   * @return Response Objeto contento o status de sucesso e o corpo da resposta
   */
  public static function postXML(string $url, string $body): Response
  {
    $ch = Request::getCurlHandle($url, $body);

    $rawResponse = curl_exec($ch);
    $response = Request::handleResponse($rawResponse, $ch);
    
    curl_close($ch);

    return $response;
  }

  private static function getCurlHandle(string $url, string $body): \CurlHandle
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

  private static function handleResponse(string | bool $response, \CurlHandle $ch): Response
  {
    if($response === false) {
      $errno = curl_errno($ch);
      $error = curl_error($ch);

      return new Response(false, [
        'errno' => $errno, 
        'error' => $error
      ]);
    }

    $escaped = Request::escapeXMLSpecialChars($response);
    $xml = simplexml_load_string($escaped, \SimpleXMLElement::class, LIBXML_NOCDATA);
    $json = json_encode($xml);
    $attrHandledJson = str_replace('@', '', $json);
    $obj = json_decode($attrHandledJson);

    return new Response(true, $obj);
  }

  private static function escapeXMLSpecialChars(string $xml): string
  {
    $xmlElementNamePattern = "[A-z_:][A-z0-9\-_\.:]*";
    $xmlAttributePattern = "$xmlElementNamePattern\s*=\s*(\".*\"|'.*')";

    $escaping = preg_replace('/&/', '&amp;', $xml);
    $escaping = preg_replace("/<(?![\/]{0,1}(\?\s*){0,1}$xmlElementNamePattern\s*(\s*$xmlAttributePattern)*(\s*\?){0,1}>)/", '&lt;', $escaping);

    return $escaping;
  }
}

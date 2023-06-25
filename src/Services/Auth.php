<?php
namespace FNAC\Services;

use FNAC\Utils;
use FNAC\Response;

class Auth
{
  public static function auth(string $partnerId, string $shopId, string $key, string $url): Response
  {
    $response = Utils::postXML("$url/auth", <<<XML
    <?xml version="1.0" encoding="utf-8"?>
    <auth xmlns='http://www.fnac.com/schemas/mp-dialog.xsd'>
      <partner_id>$partnerId</partner_id>
      <shop_id>$shopId</shop_id>
      <key>$key</key>
    </auth>
    XML);

    return $response;
  }
}

<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use FNAC\Utils;

final class UtilsTest extends TestCase
{
  public function testPostXML(): void
  {
    $response = Utils::postXML('https://vendeur.fnac.com/api.php/auth', <<<XML
    <?xml version="1.0" encoding="utf-8"?>
    <auth xmlns='http://www.fnac.com/schemas/mp-dialog.xsd'>
      <partner_id>00000000-0000-0000-0000-000000000000</partner_id>
      <shop_id>00000000-0000-0000-0000-000000000000</shop_id>
      <key>00000000-0000-0000-0000-000000000000</key>
    </auth>
    XML);

    $this->assertSame(true, $response->success);
  }
}
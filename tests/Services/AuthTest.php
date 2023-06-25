<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use FNAC\Services\Auth;

final class AuthTest extends TestCase
{
  public function testAuth(): void
  {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..');
    $dotenv->load();

    $response = Auth::auth(
      $_ENV['PARTNER_ID'], 
      $_ENV['SHOP_ID'], 
      $_ENV['KEY'], 
      'https://vendeur.fnac.com/api.php'
    );

    $this->assertSame(true, $response->success);
    if($_ENV['PARTNER_ID'] === '00000000-0000-0000-0000-000000000000') {
      $this->assertSame('ERROR', $response->body->attributes->status);
      $this->assertSame(
        'Authentication failed : one of the parameters (partner_id, key, shop_id) is invalid', 
        $response->body->error
      );
    }
    else {
      $this->assertSame('OK', $response->body->attributes->status);
      $this->assertSame('string', gettype($response->body->token));
      $this->assertSame('string', gettype($response->body->validity));
      $this->assertSame('string', gettype($response->body->version));
    }
  }
}
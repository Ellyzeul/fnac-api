<?php
namespace FNAC\Interfaces;

use FNAC\Common\Response;

interface Service
{
  public static function execute(string $url, array $params): Response;
}

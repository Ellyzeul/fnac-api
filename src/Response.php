<?php
namespace FNAC;

class Response
{
  public readonly bool $success;
  public readonly array | \stdClass $body;

  public function __construct(bool $success, array | \stdClass $body)
  {
    $this->success = $success;
    $this->body = gettype($body) === 'array'
      ? json_decode(json_encode($body))
      : $body;
  }
}

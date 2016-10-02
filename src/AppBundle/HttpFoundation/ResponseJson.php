<?php
namespace AppBundle\HttpFoundation;
use Symfony\Component\HttpFoundation\Response;

class ResponseJson extends Response {
    public function __construct($data, $status=200)
    {
        parent::__construct(json_encode($data), $status, [
            'Content-type' => 'application/json'
        ]);
    }
}
?>

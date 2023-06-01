<?php

namespace App\Casting\NYTimeCasting;

class ResponseCasting
{
    private  $meta;
    private  $response;

    /**
     * @param  $meta
     * @param  $response
     */
    public function __construct( $response)
    {
        $this->response = $response;
    }

    public function getMeta()
    {
        return $this->meta;
    }

    public function getResponse()
    {
        return $this->response;
    }

}

<?php

namespace App\Casting\NewsCratcherCasting;

class ResponseCasting
{
    private  $response;

    /**
     * @param  $meta
     * @param  $response
     */
    public function __construct($response)
    {
        $this->response = $response;
    }


    public function getResponse()
    {
        return $this->response;
    }

}

<?php

namespace Tests\Traits;

trait HttpTrait
{

    /**
     * manipulate the origin post request
     *
     * @param string $url
     * @param array $body
     * @param array $headers
     * @return mixed
     */
    protected function doPost(string $url, array $body, array $headers = [])
    {
        return $this->withHeaders([
                'Accept' => 'application/json',
            ] + $headers)->post($url, $body);
    }


    /***
     * manipulate the origin get request
     *
     * @param string $url
     * @param array $headers
     * @return \Illuminate\Testing\TestResponse
     */
    protected function doGet(string $url, array $headers = [])
    {
        return $this->withHeaders([
                'Accept' => 'application/json',
            ] + $headers)->get($url);
    }

}


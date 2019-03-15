<?php


namespace App\Service;


interface SerializeInterface
{
    /**
     * @param Object $data
     * @return string
     */
    public function serialize($data);

    /**
     * @param string $json
     * @param string $type
     * @return Object
     */
    public function deserialize($json, $type);
}
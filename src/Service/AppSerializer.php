<?php

namespace App\Service;

use JMS\Serializer\SerializerBuilder;

class AppSerializer implements SerializeInterface
{
    private $serializer;

    public function __construct($env)
    {
        $this->serializer = SerializerBuilder::create()
            ->setCacheDir('../var/cache/'.$env.'/jms_serializer')
            ->setDebug($env)
            ->build();
    }

    /**
     * @param Object $data
     * @return string
     */
    public function serialize($data): string
    {
        return $this->serializer->serialize($data, 'json');
    }

    /**
     * @param string $json
     * @param string $type
     * @return Object
     */
    public function deserialize($json, $type)
    {
        return $this->serializer->deserialize($json, $type, 'json');
    }
}
<?php

namespace Indielab\AutoScout24;

use Curl\Curl;

class Client
{
    public int $cuid;

    public ?int $member, $group;

    public string $base_url;

    const API_URL = 'https://www.autoscout24.ch/api/hci/v3/json/';

    public function __construct(int $cuid, ?int $member = 0, ?int $group = 0, string $base_url = self::API_URL)
    {
        $this->cuid = $cuid;
        $this->member = $member;
        $this->group = $group;
        $this->base_url = $base_url;

        if (empty($member) && empty($group)) {
            throw new Exception("Either the member or group param is required and can not be empty.");
        }
    }

    public function endpointResponse($name, array $args = [])
    {
        $curl = new Curl();
        $curl->get($this->base_url . $name, array_merge($args, array_filter(['cuid' => $this->cuid, 'member' => $this->member, 'group' => $this->group])));

        if (!$curl->error) {
            return $this->decodeResponse($curl->response);
        }

        throw new Exception("Invalid API Request: " . $curl->error_message);
    }

    public function decodeResponse($response)
    {
        return json_decode($response, true);
    }
}

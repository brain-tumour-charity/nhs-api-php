<?php
namespace NHSAPI\Subscriptions;

use NHSAPI\Client;

class Conditions
{

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Get all conditions
     *
     * @return Array
     */
    public function all($filters = [])
    {
        return $this->client->get('/conditions', ['query' => $filters]);
    }

    /**
     * Get specific condition or a sub page of that condition
     *
     * @return Array
     */
    public function condition($condition = '', $sub_page = '')
    {
        $url = $condition . ($sub_page != '' ? '/' : '') . $sub_page;
        return $this->client->get('/conditions/' . $url);
    }
}

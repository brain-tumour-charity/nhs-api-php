<?php
namespace NHSAPI\Subscriptions;

use NHSAPI\Client;

class Medicines
{

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Get all Medicines
     *
     * @return Array
     */
    public function all($filters = [])
    {
        return $this->client->get('/medicines', ['query' => $filters]);
    }

    /**
     * Get specific condition or a sub page of that condition
     * !(11/12/2019) Sub page functionality currently does not work
     * @return Array
     */
    public function medicine($condition = '', $sub_page = '')
    {
        $url = $condition . ($sub_page != '' ? '/' : '') . $sub_page;
        return $this->client->get('/medicines/' . $url);
    }
}

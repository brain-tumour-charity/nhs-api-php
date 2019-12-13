<?php namespace NHSAPI;

use Exception;

use GuzzleHttp\Client as Guzzle;
use Cache;
use NHSAPI\Subscriptions\Conditions;
use NHSAPI\Subscriptions\Medicines;
use NHSAPI\Exception\ApiException;

class Client {

    /**
     * The API endpoint
     *
     * @var string
     */
    protected $endpoint = 'https://api.nhs.uk';

    /**
     * The Subscription Subscription Key
     *
     * @var string
     */
    protected $subscription_key;

    /**
     * The Guzzle HTTP client instance
     *
     * @var \GuzzleHttp\Client
     */
    protected $guzzle;

    /**
     * The default cache expiry time
     *
     * @var int
     */
    protected $cache_expiry;

    /**
     * @var Subscriptions
     */
    protected $conditions;
    protected $medicines;

    /**
     * Instantiate a new Client
     *
     * @param array $attributes
     * @return void
     */
    public function __construct($subscription_key = null, $cache_expiry = null, Guzzle $guzzle = null)
    {
        if (isset($subscription_key)) $this->setSubscriptionKey($subscription_key);
        if (isset($cache_expiry)) $this->setCacheExpiry($cache_expiry);

        $this->guzzle     = $guzzle ?: new Guzzle;
        $this->conditions = new Conditions($this);
        $this->medicines  = new Medicines($this);
    }

    /**
     * Get the API subscription
     *
     * @return string
     */
    public function getSubscription()
    {
        return $this->subscription;
    }

    /**
     * Set the Subscription Key
     *
     * @param string $subscription_key
     * @return void
     */
    public function setSubscriptionKey($subscription_key)
    {
        $this->subscription_key = $subscription_key;
    }

    /**
     * Get the Subscription Key
     *
     * @return string
     */
    public function getSubscriptionKey()
    {
        return $this->subscription_key;
    }

    /**
     * Set the cache expiry
     *
     * @param int $subscription_key
     * @return void
     */
    public function setCacheExpiry($cache_expiry)
    {
        $this->cache_expiry = $cache_expiry;
    }

    /**
     * Get the cache expiry
     *
     * @return int
     */
    public function getCacheExpiry()
    {
        return $this->cache_expiry;
    }

    /**
     * Make a API request
     *
     * @param $url The endpoint to call
     * @param $method The type of call, e.g. POST
     * @param $payload Optional parameters to send in the request
     * @return array
     */
    public function request($url, $method = 'GET', $payload = [])
    {
        // add headers
        $payload['headers'] = [
            'Accept'           => 'application/json',
            'subscription-key' => $this->getSubscriptionKey()
        ];

        $payload['http_errors'] = false;

        // make the request and cache the results
        $combo = $method . '-' . $url . (isset($payload['query']) ? '-' . implode('&', $payload['query']) : '');
        return Cache::remember($combo, $this->cache_expiry, function() use($method, $url, $payload) {
            $response = $this->guzzle->request($method, $this->endpoint . $url, $payload);

            if ($response->getStatusCode() == '200' || $response->getStatusCode() == '201') {
                $content = $response->getBody()->getContents();
                if ( empty( $content ) ) {
                    return true;
                }
                return json_decode($content);
            } else if ($response->getStatusCode() == '404') {
                throw new ApiException('Client Error: 404 Not Found', 404);
            } else {
                $content = $response->getBody()->getContents();
                $errors = json_decode($content);
    
                if ( $errors !== null && isset( $errors->message ) ) {
                    throw new ApiException('Client Error: ' . $content, $response->getStatusCode(), isset( $errors ) ? $errors->message : null);
                } else {
                    throw new ApiException('Client Error: ' . $response->getStatusCode() . ' ' . $response->getReasonPhrase(), $response->getStatusCode(), isset( $errors ) ? $errors->errors : null);
                }
            }
        });
    }

    /**
     * Make a GET API request
     *
     * @param $url The endpoint to call
     * @param $payload Optional parameters to send in the request
     * @return array
     */
    public function get($url, $payload = [])
    {
        return $this->request($url, 'GET', $payload);
    }

    /**
     * Make a POST API request
     *
     * @param $url The endpoint to call
     * @param $payload Optional parameters to send in the request
     * @return array
     */
    public function post($url, $payload = [])
    {
        return $this->request($url, 'POST', $payload);
    }

    /**
     * Make a PUT API request
     *
     * @param $url The endpoint to call
     * @param $payload Optional parameters to send in the request
     * @return array
     */
    public function put($url, $payload = [])
    {
        return $this->request($url, 'PUT', $payload);
    }

    /**
     * @return Conditions
     */
    public function conditions()
    {
        $key = config('nhs_api.conditions_key');
        if ($key && $this->getSubscriptionKey() != $key) $this->setSubscriptionKey($key);
        return $this->conditions;
    }

    /**
     * @return Medicines
     */
    public function medicines()
    {
        $key = config('nhs_api.medicines_key');
        if ($key && $this->getSubscriptionKey() != $key) $this->setSubscriptionKey($key);
        return $this->medicines;
    }
}

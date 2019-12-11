<?php namespace NHSAPI;

use Exception;

use GuzzleHttp\Client as Guzzle;
use NHSAPI\Subscriptions\Conditions;
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
     * @var Subscriptions
     */
    protected $conditions;

    /**
     * Instantiate a new Client
     *
     * @param array $attributes
     * @return void
     */
    public function __construct($subscription_key = null, Guzzle $guzzle = null)
    {
        if (isset($subscription_key)) $this->setSubscriptionKey($subscription_key);

        $this->guzzle     = $guzzle ?: new Guzzle;
        $this->conditions = new Conditions($this);
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

        // make the request
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
        if ($key = config('nhs_api.conditions_key') && $this->getSubscriptionKey() != $key) $this->setSubscriptionKey($key);

        return $this->conditions;
    }
}

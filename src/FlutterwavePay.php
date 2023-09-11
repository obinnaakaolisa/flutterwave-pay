<?php

namespace obinnaakaolisa;

use Curl\Curl;

/**
 *
 * @author Obinna Akaolisa <akaolisaobinna@gmail.com>
 * @package obinnaakaolisa
 * 
 */

class FlutterwavePay
{
    protected string $apiKey;

    protected string $apiBaseUrl;

    private $response;

    public function __construct(string $apiKey = "")
    {
        // Set the apiKey
        $this->apiKey = isset($apiKey) ? $apiKey : null;

        // Set the api baseUrl
        $this->apiBaseUrl = 'https://api.flutterwave.com/v3';
    }

    public function initialize(array $payload = [])
    {
        $request = new Curl();

        $request->setHeader('Authorization', $this->apiKey);
        $request->setHeader('Content-Type', 'application/json');

        $request->post($this->apiBaseUrl . "/payments", json_encode($payload));

        $response = json_decode($request->response);

        if ($response->status == 'success') {
            
            $this->response = json_encode([
                'status' => $response->status,
                'message' => $response->message,
                'data' => [
                    'checkoutUrl' => $response->data->link,
                ]
            ]);
                
        } else {
            
            $this->response = json_encode([
                'status' => $response->status,
                'message' => $response->message,
                'data' => $response->data,
            ]);
        }

        return $this->response;
    }

    public function verify(string $trx_ref)
    {
        $request = new Curl();

        $request->setHeader('Authorization', $this->apiKey);
        $request->setHeader('Content-Type', 'application/json');

        $request->get($this->apiBaseUrl . "/transactions/verify_by_reference?tx_ref={$trx_ref}");

        $response = json_decode($request->response);

        if ($response->status == 'success') {
            
            $this->response = json_encode([
                'status' => $response->status,
                'message' => $response->message,
                'data' => [
                    'trx_id' => $response->data->id,
                    'trx_status' => $response->data->status,
                    'trx_ref' => $response->data->tx_ref,
                    'gateway_ref' => $response->data->flw_ref,
                    'amount_due' => $response->data->amount,
                    'amount_paid' => $response->data->charged_amount,
                    'amount_settled' => $response->data->amount_settled,
                    'trx_fee' => $response->data->app_fee,
                    'currency' => $response->data->currency,
                    'payment_method' => $response->data->payment_type,
                    'trx_date' => $response->data->created_at,
                    'customer' => [
                        'name' => $response->data->customer->name,
                        'email' => $response->data->customer->email,
                        'phone' => $response->data->customer->phone_number,
                    ],
                    'metadata' => isset($response->data->meta) ? $response->data->meta : null,
                ]
            ]);
                
        } else {
            $this->response = json_encode([
                'status' => $response->status,
                'message' => $response->message,
                'data' => $response->data,
            ]);
        }

        return $this->response;
    }

}
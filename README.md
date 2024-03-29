# flutterwave-pay

This is a PHP package for intializing and verifying Flutterwave transactions via the Flutterave transactions API

## Requirements

The package requires the folowing resources:

1. PHP ^8.0
2. Composer
3. Curl

## Getting started

### Installation

Navigate to your project's directory 

```bash
    cd folder/my-project
```

In your project folder, run the following command 

```bash
    composer require obinnaakaolisa/flutterwave-pay
```

### Set your api key

```php
    $apiKey = "FLWSECK_TEST-xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx-X";
```

### Instantiate a new transaction object

Pass in the API_KEY upon instantiation.

```php
    $transaction = new FlutterwavePay($apiKey);
```

Define your payload data for a `POST` request.

```php

    $payload = [
    'tx_ref' => 'FLW_TEST_'.bin2hex(random_bytes(5)),
    'amount' => 2000, //in naira
    'redirect_url' => "https://{$_SERVER['SERVER_NAME']}/verify.php",
    'currency' => 'NGN',
    'customer' => [
        'name' => 'Firstname' .' '.'Lastname',
        'email' => 'customer@email.com',
        'phone_number' => '08031010101',
    ],
    'payment_options' => "card, banktransfer, ussd",
    'customizations' => [
        'title' => 'Some Payment',
    ],
    'meta' => [
        'field_name' => 'field_value',
    ],
    'subaccounts' => []
];

```
Note: 

1. The `customizations` field is optional. Define it if you want to customize the look and feel of the payment page. See [documentation](https://developer.flutterwave.com/docs/collecting-payments/standard) for reference.
2. The `subaccounts` field is optional. It is only defined when you want to split the transaction into different settlement accounts. See [documentation](https://developer.flutterwave.com/docs/collecting-payments/split-payments) for reference.
2. The `meta` field is optional. It is only defined when you want to save additional info about the customer or the transaction. See [documentation](https://developer.flutterwave.com/docs/collecting-payments/standard) for reference.

### Intialize the transaction

The response you get for a request is a json encoded object, so you need to decode it thus:

```php

$response = json_decode($transaction->initialize($payload));

```

or

```php

$request = $transaction->initialize($payload);

$response = json_decode($request);

```

### Verifying a transaction

Upon a successfull transaction or close of the payment page, the customer will be redirected to the `redirect_url` you set when initiating the transaction. Normally, that's the url where you'll verify the status of the transaction.

It is advised to verify a transaction before you give value to the customer.

Define your API_KEY

```php
    $apiKey = "FLWSECK_TEST-xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx-X";
```

Instantiate a new transaction object and pass in the API_KEY upon instantiation.

```php
    $transaction = new FlutterwavePay($apiKey);
```
Pass in the transaction reference that was defined while intiating the transaction.

You can also get this from the query parameter of the get request returned along with the `redirect_url`.

```php

$response = json_decode($transaction->verify($_GET['tx_ref']));

```

## Contributing

Contributions are always welcome!

See `contributing.md` for ways to get started.

Please adhere to this project's `code of conduct`.

## License

[MIT](https://choosealicense.com/licenses/mit/)

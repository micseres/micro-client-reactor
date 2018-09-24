# Micro Client Reactor

## Usage

```php
$request = new ClientRequest();
$request->setRoute('passport');
$request->addPayload('clientId', $clientId);
        
$data = $this->microClientReactor->sendAndReceive($request);

```

## License

The Soft Deletable Bundle is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

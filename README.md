Luft.jetzt API Bundle
=====================

Symfony bundle and HTTP client used by the Luft.jetzt provider apps
(`provider-bfs`, `provider-luftdaten`, `provider-noaa`, `provider-uba-de`) to
push air-quality stations and measurement values to the Luft.jetzt API.

Requirements
------------

- PHP 8.4+
- Symfony 7.4 or 8.x

Installation
------------

```bash
composer require luft-jetzt/luft-api-bundle
```

If you do not use Symfony Flex, enable the bundle manually in
`config/bundles.php`:

```php
return [
    // ...
    Caldera\LuftApiBundle\LuftApiBundle::class => ['all' => true],
];
```

Configuration
-------------

Create `config/packages/caldera_luftapi.yaml`:

```yaml
caldera_luftapi:
    api:
        hostname: api.luft.jetzt
        port: 443
        verify: true
```

| Option     | Type   | Description                                                        |
|------------|--------|--------------------------------------------------------------------|
| `hostname` | string | Host of the Luft.jetzt API. The base URL scheme is always `https`. |
| `port`     | int    | TCP port, e.g. `443`.                                              |
| `verify`   | bool   | Enable TLS peer/host verification. Keep `true` in production; only disable for local development against a self-signed certificate. |

The client builds its base URI as `https://{hostname}:{port}/`.

Usage
-----

Two services are exposed and can be autowired via their interfaces,
`Caldera\LuftApiBundle\Api\ValueApiInterface` and
`Caldera\LuftApiBundle\Api\StationApiInterface`.

### Sending measurement values

```php
use Caldera\LuftApiBundle\Api\ValueApiInterface;
use Caldera\LuftModel\Model\Value;

class ImportService
{
    public function __construct(private ValueApiInterface $valueApi)
    {
    }

    public function push(): void
    {
        $value = (new Value())
            ->setStationCode('DEBW087')
            ->setDateTime(new \DateTime())
            ->setValue(12.5)
            ->setPollutant('pm10');

        // Single value ...
        $this->valueApi->putValue($value);

        // ... or a batch (the associative array keys are dropped so a JSON
        // list is sent).
        $this->valueApi->putValues(['a' => $value]);
    }
}
```

### Working with stations

```php
use Caldera\LuftApiBundle\Api\StationApiInterface;
use Caldera\LuftModel\Model\Station;

class StationService
{
    public function __construct(private StationApiInterface $stationApi)
    {
    }

    public function sync(): void
    {
        // Fetch stations, optionally filtered by provider. The result is keyed
        // by station code.
        $stations = $this->stationApi->getStations('uba');

        $station = (new Station())
            ->setStationCode('DEBW087')
            ->setTitle('Example station');

        // Bulk upsert ...
        $this->stationApi->putStations([$station]);

        // ... or create one request per station.
        $this->stationApi->postStations([$station]);
    }
}
```

License
-------

Released under the [MIT License](LICENSE).

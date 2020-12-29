<?php declare(strict_types=1);

namespace Caldera\LuftApiBundle\Api;

interface StationApiInterface
{
    const SERIALIZER_FORMAT = 'json';

    public function getStations(): array;
    public function postStations(array $stationList): void;
    public function putStations(array $stationList): void;
}

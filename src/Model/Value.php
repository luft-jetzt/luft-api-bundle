<?php declare(strict_types=1);

namespace Caldera\LuftApiBundle\Model;

use Carbon\Carbon;
use JMS\Serializer\Annotation as JMS;

/**
 * @JMS\ExclusionPolicy("ALL")
 */
class Value
{
    /**
     * @JMS\Expose()
     * @JMS\Type("string")
     */
    protected ?string $stationCode = null;

    /**
     * @JMS\Expose()
     * @JMS\Type("Carbon<'U'>")
     */
    protected ?Carbon $dateTime = null;

    /**
     * @JMS\Expose()
     * @JMS\Type("float")
     */
    protected ?float $value = null;

    /**
     * @JMS\Expose()
     * @JMS\Type("string")
     */
    protected ?string $pollutant = null;

    public function __construct()
    {

    }

    public function getStationCode(): ?string
    {
        return $this->stationCode;
    }

    public function setStationCode(string $stationCode): Value
    {
        $this->stationCode = $stationCode;

        return $this;
    }

    public function getDateTime(): ?Carbon
    {
        return $this->dateTime;
    }

    public function setDateTime(Carbon $dateTime): Value
    {
        $this->dateTime = $dateTime;

        return $this;
    }

    public function getValue(): ?float
    {
        return $this->value;
    }

    public function setValue(float $value): Value
    {
        $this->value = $value;

        return $this;
    }

    public function getPollutant(): ?string
    {
        return $this->pollutant;
    }

    public function setPollutant(string $pollutant): Value
    {
        $this->pollutant = $pollutant;

        return $this;
    }
}

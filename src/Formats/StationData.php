<?php

namespace Pforret\SunnySideUp\Formats;

class StationData
{
    public string $url = '';

    public ?string $name = null;

    public ?string $id = null;

    public ?string $address = null;

    public ?string $city = null;

    public ?string $country = null;

    public ?string $timezone = null;

    public ?int $panel_count = null;

    public ?int $watt_peak = null;

    public ?string $date_commissioning = null;
}

<?php

namespace Weather\Api;

use Weather\Model\NullWeather;
use Weather\Model\Weather;

class JsonRepository extends DbRepository implements DataProvider
{

    /**
     * @return array
     * @throws \Exception
     */
    protected function selectAll(): array
    {
        $result = [];
        $data = json_decode(
            file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'Db' . DIRECTORY_SEPARATOR . 'Weather.json'),
            true
        );
        foreach ($data as $item) {
            $record = new Weather();
            $record->setDate(new \DateTime($item['date']));
            $record->setDayTemp($item['high']);
            $record->setNightTemp($item['low']);
            $record->setSky($this->getSkyFromText($item['text']));
            $result[] = $record;
        }

        return $result;
    }

    private function getSkyFromText($skyText)
    {
        switch (strtolower(trim($skyText))) {
            case 'mostly cloudy':
            case 'partly cloudy':
            case 'cloudy':
                return 1;
                break;

            case 'scattered showers':
                return 2;
                break;

            case 'breezy':
            case 'synny':
            default:
                return 3;
        }
    }
}

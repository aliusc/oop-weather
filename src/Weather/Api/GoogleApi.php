<?php

namespace Weather\Api;

use Weather\Model\NullWeather;
use Weather\Model\Weather;

class GoogleApi implements DataProvider
{
    /**
     * @return Weather
     * @throws \Exception
     */
    public function getToday()
    {
        $today = $this->load(new NullWeather());
        $today->setDate(new \DateTime());

        return $today;
    }

    /**
     * @param Weather $before
     * @return Weather
     * @throws \Exception
     */
    private function load(Weather $before)
    {
        $now = new Weather();
        $base = $before->getDayTemp();
        $now->setDayTemp(random_int($base - 5 , $base + 5));

        $base = $before->getNightTemp();
        $now->setNightTemp(random_int(-5 - abs($base), -5 + abs($base)));

        $now->setSky(random_int(1, 3));

        return $now;
    }

    /**
     * @param \DateTime $date
     * @return Weather
     */
    public function selectByDate(\DateTime $date): Weather
    {
        try {
            $weather = $this->getToday();

        } catch (\Exception $exp) {
            $weather = new NullWeather();
        }

        return $weather;
    }

    /**
     * @param \DateTime $from
     * @param \DateTime $to
     * @return array
     * @throws \Exception
     */
    public function selectByRange(\DateTime $from, \DateTime $to): array
    {
        /** @var Weather[] $weathers */
        $weathers = [];
        if($from > $to) {
            return $weathers;
        }

        $last_weather = $this->selectByDate($from);
        $temp_from = $from;
        $weathers[] = $last_weather;
        while ($temp_from < $to) {
            $temp_from = $temp_from->modify("+1 day");
            $last_weather = $this->load($last_weather);
            $last_weather->setDate(clone $temp_from);
            $weathers[] = $last_weather;
        }

        return $weathers;
    }
}

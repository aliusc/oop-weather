<?php

namespace Weather;

use Weather\Api\DataProvider;
use Weather\Api\DbRepository;
use Weather\Api\GoogleApi;
use Weather\Api\JsonRepository;
use Weather\Model\Weather;

class Manager
{
    /**
     * @var DataProvider
     */
    private $transporter;

    /**
     * Manager constructor.
     * @param string|null $dataProvider
     */
    public function __construct(string $dataProvider = null)
    {
        if($dataProvider=='google') {
            $this->transporter = new GoogleApi();
        }
        elseif ($dataProvider=='json') {
            $this->transporter = new JsonRepository();
        }
    }

    public function getTodayInfo(): Weather
    {
        return $this->getTransporter()->selectByDate(new \DateTime());
    }

    public function getWeekInfo(): array
    {
        return $this->getTransporter()->selectByRange(new \DateTime('midnight'), new \DateTime('+6 days midnight'));
    }

    private function getTransporter()
    {
        if (null === $this->transporter) {
            $this->transporter = new DbRepository();
        }

        return $this->transporter;
    }
}



<?php

class Covid
{
    // private $region;
    // private $name;
    // private $avgDailyIncomeInUSD;
    // private $avgDailyIncomePopulation;
    // private $periodType;
    // private $timeToElapse;
    // private $population;
    // private $totalHospitalBeds;

    private $json_data;
    private $input_data;

    private $impact_currentlyInfected;
    private $severe_impact_currentlyInfected;

    private $impact_infectionsByRequestedTime;
    private $severe_infectionsByRequestedTime;


    public function processData($url)
    {
        $this->json_data = file_get_contents("data.json");
        $this->input_data = json_decode($this->json_data);
        return $this->input_data;
    }


    public function getDate($array_data)
    {
        return [
            'region' =>  $array_data->region,
            'periodType' => $array_data->periodType,
            'timeToElapse' => $array_data->timeToElapse,
            'reportedCases' => $array_data->reportedCases,
            'population' => $array_data->population,
            'totalHospitalBeds' => $array_data->totalHospitalBeds

        ];
    }

    public function impact_currentlyInfected($reportedCases)
    {
        $this->impact_currentlyInfected = $reportedCases * 10;
        return $this->impact_currentlyInfected;
    }

    public function severe_impact_currentlyInfected($reportedCases)
    {
        $this->severe_impact_currentlyInfected = $reportedCases * 50;
        return $this->severe_impact_currentlyInfected;
    }

    public function impact_infectionsByRequestedTime($impact_currentlyInfected)
    {
        return  $impact_currentlyInfected * 512;
    }

    public function severe_infectionsByRequestedTime($severe_impact_currentlyInfected)
    {
        return  $severe_impact_currentlyInfected * 512;
    }

    public function get_name()
    {
        return $this->name;
    }
}

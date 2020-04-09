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

    public function impact_severeCasesByRequestedTime($impact_infectionsByRequestedTime)
    {
        return (15 * $impact_infectionsByRequestedTime) / 100;
    }

    public function severe_severeCasesByRequestedTime($severe_infectionsByRequestedTime)
    {
        return (15 * $severe_infectionsByRequestedTime) / 100;
    }

    public function impact_hospitalBedsByRequestedTime($totalHospitalBeds, $severeCasesByRequestedTime)
    {
        $impact_avavailable_bed = (35 * $totalHospitalBeds) / 100;
        return intval($impact_avavailable_bed - $severeCasesByRequestedTime);
    }

    public function severe_hospitalBedsByRequestedTime($totalHospitalBeds, $severeCasesByRequestedTime)
    {
        $severe_avavailable_bed = (35 * $totalHospitalBeds) / 100;
        return intval($severe_avavailable_bed - $severeCasesByRequestedTime);
    }


    public function impact_casesForICUByRequestedTime($impact_infectionsByRequestedTime)
    {
        return (5 * $impact_infectionsByRequestedTime) / 100;
    }

    public function severe_casesForICUByRequestedTime($severe_infectionsByRequestedTime)
    {
        return (5 * $severe_infectionsByRequestedTime) / 100;
    }

    public function return_data($data_, $impact_currentlyInfected, $impact_infectionsByRequestedTime, $severe_impact_currentlyInfected, $severe_infectionsByRequestedTime, $impact_severeCasesByRequestedTime, $severe_severeCasesByRequestedTime, $impact_hospitalBedsByRequestedTime, $severe_hospitalBedsByRequestedTime, $impact_casesForICUByRequestedTime, $severe_casesForICUByRequestedTime)
    {
        // return data
        return  [
            'data' => $data_,
            'impact' => [
                'currentlyInfected' => $impact_currentlyInfected,
                'infectionsByRequestedTime' => $impact_infectionsByRequestedTime,
                'severeCasesByRequestedTime' => $impact_severeCasesByRequestedTime,
                'hospitalBedsByRequestedTime' => $impact_hospitalBedsByRequestedTime,
                'casesForICUByRequestedTime' => $impact_casesForICUByRequestedTime
            ],
            'severeImpact' => [
                'currentlyInfected' => $severe_impact_currentlyInfected,
                'infectionsByRequestedTime' => $severe_infectionsByRequestedTime,
                'severeCasesByRequestedTime' => $severe_severeCasesByRequestedTime,
                'hospitalBedsByRequestedTime' => $severe_hospitalBedsByRequestedTime,
                'casesForICUByRequestedTime' => $severe_casesForICUByRequestedTime
            ]
        ];
    }
}

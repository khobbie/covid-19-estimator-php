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
        // $this->json_data = file_get_contents($url);
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


    public function convert_timeToElapse_to_days($periodType, $timeToElapse)
    {

        if ($periodType == 'weeks') {
            $timeToElapse = $timeToElapse * 7;
        } elseif ($periodType == 'months') {
            $timeToElapse = $timeToElapse * 30;
        } else {
            $timeToElapse = 1;
        }

        return $timeToElapse;
    }

    public function impact_currentlyInfected($reportedCases)
    {
        $this->impact_currentlyInfected = $reportedCases * 10;
        return intval($this->impact_currentlyInfected);
    }

    public function severe_impact_currentlyInfected($reportedCases)
    {
        $this->severe_impact_currentlyInfected = $reportedCases * 50;
        return intval($this->severe_impact_currentlyInfected);
    }


    public function impact_infectionsByRequestedTime($impact_currentlyInfected)
    {
        return  intval($impact_currentlyInfected * 512);
    }

    public function severe_infectionsByRequestedTime($severe_impact_currentlyInfected)
    {
        return  intval($severe_impact_currentlyInfected * 512);
    }


    public function impact_severeCasesByRequestedTime($impact_infectionsByRequestedTime)
    {
        return intval((15 * $impact_infectionsByRequestedTime) / 100);
    }

    public function severe_severeCasesByRequestedTime($severe_infectionsByRequestedTime)
    {
        return intval((15 * $severe_infectionsByRequestedTime) / 100);
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
        return intval((5 * $impact_infectionsByRequestedTime) / 100);
    }

    public function severe_casesForICUByRequestedTime($severe_infectionsByRequestedTime)
    {
        return intval((5 * $severe_infectionsByRequestedTime) / 100);
    }


    public function impact_casesForVentilatorsByRequestedTime($impact_infectionsByRequestedTime)
    {
        return intval((2 * $impact_infectionsByRequestedTime) / 100);
    }

    public function severe_casesForVentilatorsByRequestedTime($severe_infectionsByRequestedTime)
    {
        return intval((2 * $severe_infectionsByRequestedTime) / 100);
    }


    public function impact_dollarsInFlight($impact_infectionsByRequestedTime, $avgDailyIncomePopulation, $avgDailyIncomeInUSD, $timeToElapse)
    {
        return intval($impact_infectionsByRequestedTime * $avgDailyIncomePopulation * $avgDailyIncomeInUSD * $timeToElapse);
    }

    public function severe_dollarsInFlight($severe_infectionsByRequestedTime, $avgDailyIncomePopulation, $avgDailyIncomeInUSD, $timeToElapse)
    {
        return intval($severe_infectionsByRequestedTime * $avgDailyIncomePopulation * $avgDailyIncomeInUSD * $timeToElapse);
    }

    public function return_data($data_, $impact_currentlyInfected, $impact_infectionsByRequestedTime, $severe_impact_currentlyInfected, $severe_infectionsByRequestedTime, $impact_severeCasesByRequestedTime, $severe_severeCasesByRequestedTime, $impact_hospitalBedsByRequestedTime, $severe_hospitalBedsByRequestedTime, $impact_casesForICUByRequestedTime, $severe_casesForICUByRequestedTime, $impact_casesForVentilatorsByRequestedTime, $severe_casesForVentilatorsByRequestedTime, $impact_dollarsInFlight, $severe_dollarsInFlight)
    {
        // return data
        return  [
            'data' => $data_,
            'impact' => [
                'currentlyInfected' => $impact_currentlyInfected,
                'infectionsByRequestedTime' => $impact_infectionsByRequestedTime,
                'severeCasesByRequestedTime' => $impact_severeCasesByRequestedTime,
                'hospitalBedsByRequestedTime' => $impact_hospitalBedsByRequestedTime,
                'casesForICUByRequestedTime' => $impact_casesForICUByRequestedTime,
                'casesForVentilatorsByRequestedTime' => $impact_casesForVentilatorsByRequestedTime,
                'dollarsInFlight' => $impact_dollarsInFlight
            ],
            'severeImpact' => [
                'currentlyInfected' => $severe_impact_currentlyInfected,
                'infectionsByRequestedTime' => $severe_infectionsByRequestedTime,
                'severeCasesByRequestedTime' => $severe_severeCasesByRequestedTime,
                'hospitalBedsByRequestedTime' => $severe_hospitalBedsByRequestedTime,
                'casesForICUByRequestedTime' => $severe_casesForICUByRequestedTime,
                'casesForVentilatorsByRequestedTime' => $severe_casesForVentilatorsByRequestedTime,
                'dollarsInFlight' => $severe_dollarsInFlight
            ]
        ];
    }
}

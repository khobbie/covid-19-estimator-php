<?php

header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json; charset=UTF-8");
header('Access-Control-Allow-Methods: POST');
header("Access-Control-Allow-Headers: X-Requested-With");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


require './Covid.php';

$url = "data.json";

// INSTANTIATE
$covid = new Covid();
$retrieved_data = $covid->processData($url);
$data_ = $covid->getDate($retrieved_data);

// echo json_encode($data_);
// echo $data_['region']->avgDailyIncomePopulation;

// GET CURRENTLY INFECTED
$impact_currentlyInfected = $covid->impact_currentlyInfected($data_['reportedCases']);
$severe_impact_currentlyInfected = $covid->severe_impact_currentlyInfected($data_['reportedCases']);

// GET INFECTION BT REQUESTED TIME
$impact_infectionsByRequestedTime = $covid->impact_infectionsByRequestedTime($impact_currentlyInfected);
$severe_infectionsByRequestedTime = $covid->severe_infectionsByRequestedTime($severe_impact_currentlyInfected);
// $severe_impact_currentlyInfected = $covid->severe_impact_currentlyInfected($data_['reportedCases']);

// GET severeCasesByRequestedTime 
$impact_severeCasesByRequestedTime = $covid->impact_severeCasesByRequestedTime($impact_infectionsByRequestedTime);
$severe_severeCasesByRequestedTime = $covid->severe_severeCasesByRequestedTime($severe_infectionsByRequestedTime);

// GET severeCasesByRequestedTime  
$impact_hospitalBedsByRequestedTime = $covid->impact_hospitalBedsByRequestedTime($data_['totalHospitalBeds'], $impact_severeCasesByRequestedTime);
$severe_hospitalBedsByRequestedTime = $covid->severe_hospitalBedsByRequestedTime($data_['totalHospitalBeds'], $severe_severeCasesByRequestedTime);


// GET casesForICUByRequestedTime  
$impact_casesForICUByRequestedTime = $covid->impact_casesForICUByRequestedTime($impact_infectionsByRequestedTime);
$severe_casesForICUByRequestedTime = $covid->severe_casesForICUByRequestedTime($severe_infectionsByRequestedTime);



// GET casesForVentilatorsByRequestedTime  
$impact_casesForVentilatorsByRequestedTime = $covid->impact_casesForVentilatorsByRequestedTime($impact_infectionsByRequestedTime);
$severe_casesForVentilatorsByRequestedTime = $covid->severe_casesForVentilatorsByRequestedTime($severe_infectionsByRequestedTime);



// GET dollarsInFlight  
$impact_dollarsInFlight = $covid->impact_dollarsInFlight($impact_infectionsByRequestedTime, $data_['region']->avgDailyIncomePopulation, $data_['region']->avgDailyIncomeInUSD, $data_['timeToElapse']);
$severe_dollarsInFlight = $covid->severe_dollarsInFlight($severe_infectionsByRequestedTime, $data_['region']->avgDailyIncomePopulation, $data_['region']->avgDailyIncomeInUSD, $data_['timeToElapse']);


// return data
$data = $covid->return_data($data_, $impact_currentlyInfected, $impact_infectionsByRequestedTime, $severe_impact_currentlyInfected, $severe_infectionsByRequestedTime, $impact_severeCasesByRequestedTime, $severe_severeCasesByRequestedTime, $impact_hospitalBedsByRequestedTime, $severe_hospitalBedsByRequestedTime, $impact_casesForICUByRequestedTime, $severe_casesForICUByRequestedTime, $impact_casesForVentilatorsByRequestedTime, $severe_casesForVentilatorsByRequestedTime, $impact_dollarsInFlight, $severe_dollarsInFlight);





// return data
$data = [
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

return $data;

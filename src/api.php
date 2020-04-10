<?php

header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json; charset=UTF-8");
header('Access-Control-Allow-Methods: POST');
header("Access-Control-Allow-Headers: X-Requested-With");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


require './Covid.php';
require './estimator.php';

// INSTANTIATE
$covid = new Covid();


// get posted data
$data = json_decode(file_get_contents("php://input"));
// echo json_encode($data);
// exit(json_encode($data));
// make sure data is not empty
if (
    !empty($data->periodType) &&
    !empty($data->timeToElapse) &&
    !empty($data->reportedCases) &&
    !empty($data->population) &&
    !empty($data->totalHospitalBeds) &&
    !empty($data->region) &&
    !empty($data->region->name) &&
    !empty($data->region->avgAge) &&
    !empty($data->region->avgDailyIncomeInUSD) &&
    !empty($data->region->avgDailyIncomePopulation)
) {


    // exit(json_encode($data));


    // echo json_encode($data_);
    // echo $data_['region']->avgDailyIncomePopulation;

    $timeToElapse = $covid->convert_timeToElapse_to_days($data->periodType, $data->timeToElapse);

    // GET CURRENTLY INFECTED
    $impact_currentlyInfected = $covid->impact_currentlyInfected($data->reportedCases);
    $severe_impact_currentlyInfected = $covid->severe_impact_currentlyInfected($data->reportedCases);

    // GET INFECTION BT REQUESTED TIME
    $impact_infectionsByRequestedTime = $covid->impact_infectionsByRequestedTime($impact_currentlyInfected);
    $severe_infectionsByRequestedTime = $covid->severe_infectionsByRequestedTime($severe_impact_currentlyInfected);
    // $severe_impact_currentlyInfected = $covid->severe_impact_currentlyInfected($data_['reportedCases']);

    // GET severeCasesByRequestedTime 
    $impact_severeCasesByRequestedTime = $covid->impact_severeCasesByRequestedTime($impact_infectionsByRequestedTime);
    $severe_severeCasesByRequestedTime = $covid->severe_severeCasesByRequestedTime($severe_infectionsByRequestedTime);

    // GET severeCasesByRequestedTime  
    $impact_hospitalBedsByRequestedTime = $covid->impact_hospitalBedsByRequestedTime($data->totalHospitalBeds, $impact_severeCasesByRequestedTime);
    $severe_hospitalBedsByRequestedTime = $covid->severe_hospitalBedsByRequestedTime($data->totalHospitalBeds, $severe_severeCasesByRequestedTime);


    // GET casesForICUByRequestedTime  
    $impact_casesForICUByRequestedTime = $covid->impact_casesForICUByRequestedTime($impact_infectionsByRequestedTime);
    $severe_casesForICUByRequestedTime = $covid->severe_casesForICUByRequestedTime($severe_infectionsByRequestedTime);



    // GET casesForVentilatorsByRequestedTime  
    $impact_casesForVentilatorsByRequestedTime = $covid->impact_casesForVentilatorsByRequestedTime($impact_infectionsByRequestedTime);
    $severe_casesForVentilatorsByRequestedTime = $covid->severe_casesForVentilatorsByRequestedTime($severe_infectionsByRequestedTime);



    // GET dollarsInFlight  
    $impact_dollarsInFlight = $covid->impact_dollarsInFlight($impact_infectionsByRequestedTime, $data->region->avgDailyIncomePopulation, $data->region->avgDailyIncomeInUSD, $timeToElapse);
    $severe_dollarsInFlight = $covid->severe_dollarsInFlight($severe_infectionsByRequestedTime, $data->region->avgDailyIncomePopulation, $data->region->avgDailyIncomeInUSD, $timeToElapse);


    // return data
    $data = $covid->return_data($data, $impact_currentlyInfected, $impact_infectionsByRequestedTime, $severe_impact_currentlyInfected, $severe_infectionsByRequestedTime, $impact_severeCasesByRequestedTime, $severe_severeCasesByRequestedTime, $impact_hospitalBedsByRequestedTime, $severe_hospitalBedsByRequestedTime, $impact_casesForICUByRequestedTime, $severe_casesForICUByRequestedTime, $impact_casesForVentilatorsByRequestedTime, $severe_casesForVentilatorsByRequestedTime, $impact_dollarsInFlight, $severe_dollarsInFlight);





    // return data
    $output_data = [
        'data' => $data,
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

    // set response code - 400 bad request
    http_response_code(200);

    $response = json_encode([
        'responseCode' => '000',
        'message' => 'data fetch successful',
        'data' => covid19ImpactEstimator($output_data)
    ]);

    exit($response);
} else {

    // set response code - 400 bad request
    http_response_code(400);

    $response = json_encode([
        'responseCode' => '400',
        'message' => 'check parameter',
        'data' => null
    ]);

    exit($response);
}

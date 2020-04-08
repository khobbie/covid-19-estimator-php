<?php

require './Covid.php';

$url = "data.json";

// INSTANTIATE
$covid = new Covid();
$retrieved_data = $covid->processData($url);
$data_ = $covid->getDate($retrieved_data);

// echo json_encode($data_);
// echo $data_['reportedCases'];

// GET CURRENTLY INFECTED
$impact_currentlyInfected = $covid->impact_currentlyInfected($data_['reportedCases']);
$severe_impact_currentlyInfected = $covid->severe_impact_currentlyInfected($data_['reportedCases']);

// GET INFECTION BTY REQUESTED TIME
$impact_infectionsByRequestedTime = $covid->impact_infectionsByRequestedTime($impact_currentlyInfected);
$severe_infectionsByRequestedTime = $covid->severe_infectionsByRequestedTime($severe_impact_currentlyInfected);


// return data
$data = [
  'data' => $data_,
  'impact' => [
    'currentlyInfected' => $impact_currentlyInfected,
    'infectionsByRequestedTime' => $impact_infectionsByRequestedTime
  ],
  'severeImpact' => [
    'currentlyInfected' => $severe_impact_currentlyInfected,
    'infectionsByRequestedTime' => $severe_infectionsByRequestedTime
  ]
];



function covid19ImpactEstimator($data)
{
  $data = json_encode($data);
  return $data;
}

echo covid19ImpactEstimator($data);

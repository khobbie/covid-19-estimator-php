// var JobsInGhana = angular.module('JobsInGhana', ['ngSanitize']);
var Covid = angular.module("Covid", []);

Covid.controller("IndexCtrl", function ($scope, $http) {
  $scope.name = "Cobby";

  $scope.getRegion = function () {
    $http({
      method: "GET",
      url: "./data.json",
    }).then(
      function successCallback(response) {
        $scope.regions = response.data;
        console.log($scope.regions);
      },
      function errorCallback(response) {
        $.notify(
          {
            message: "Connection Error, check network",
          },
          {
            placement: {
              from: "top",
              align: "center",
            },
            allow_dismiss: true,
            type: "error",
            timer: 3000,
          }
        );
      }
    );
  };

  $scope.getRegion();

  // var baseurl = '../api.php';

  $("#covid_submit").submit(function (e) {
    e.preventDefault();

    var name = $("#name").val();
    var avgAge = parseInt($("#avgAge").val());
    var avgDailyIncomeInUSD = parseInt($("#avgDailyIncomeInUSD").val());
    var avgDailyIncomePopulation = parseInt(
      $("#avgDailyIncomePopulation").val()
    );

    var population = parseInt($("#population").val());
    var timeToElapse = parseInt($("#timeToElapse").val());
    var reportedCases = parseInt($("#reportedCases").val());
    var totalHospitalBeds = parseInt($("#totalHospitalBeds").val());
    var periodType = $("#periodType").val();
    // var region = jSON.stringy$('#region').val();

    var data = {
      region: {
        name: name.trim(),
        avgAge: avgAge,
        avgDailyIncomeInUSD: avgDailyIncomeInUSD,
        avgDailyIncomePopulation: avgDailyIncomePopulation,
      },
      periodType: periodType.trim(),
      timeToElapse: timeToElapse,
      reportedCases: reportedCases,
      population: population,
      totalHospitalBeds: totalHospitalBeds,
    };
    // console.log(region);
    // return false;

    if (
      population == undefined ||
      timeToElapse == undefined ||
      reportedCases == undefined ||
      totalHospitalBeds == undefined ||
      periodType == undefined
    ) {
      return false;
    }
    // console.log(data);
    $http({
      method: "POST",
      url: "../api.php",
      data: data,
    }).then(
      function successCallback(response) {
        var res = response.data;
        if (res.responseCode == "000") {
          $.notify(
            {
              message: res.message,
            },
            {
              placement: {
                from: "top",
                align: "center",
              },
              allow_dismiss: true,
              type: "success",
              timer: 1000,
            }
          );

          console.log(res);

          $scope.impact = res.data.impact;
          $scope.severe_impact = res.data.severeImpact;

          $("#covid_submit").trigger("reset");
        } else {
          $.notify(
            {
              message: res.message,
            },
            {
              placement: {
                from: "top",
                align: "center",
              },
              allow_dismiss: true,
              type: "error",
              timer: 2000,
            }
          );
        }
      },
      function errorCallback(response) {
        $.notify(
          {
            message: "Connection Error, check network",
          },
          {
            placement: {
              from: "top",
              align: "center",
            },
            allow_dismiss: true,
            type: "error",
            timer: 2000,
          }
        );
      }
    );

    // $.ajax({
    //     url: '../api.php',
    //     type: 'post',
    //     contentType: 'application/json',
    //     data: data,
    //     success: function (data, textStatus, jQxhr) {
    //         console.log(data.responseCode);
    //         // if (data.responseCode == '000') {

    //         // } else {
    //         //     console.log(data.responseCode);

    //         // }
    //     },
    //     error: function (jqXhr, textStatus, errorThrown) {

    //     }
    // });
  });
});

// console.log('Hello');

// var JobsInGhana = angular.module('JobsInGhana', ['ngSanitize']);
var Covid = angular.module('Covid', []);


Covid.controller('IndexCtrl', function ($scope, $http) {

    $scope.name = 'Cobby';

    var baseurl = '../api.php';

    $("#covid_submit").submit(function (e) {
        e.preventDefault();

        var population = parseInt($('#population').val());
        var timeToElapse = parseInt($('#timeToElapse').val());
        var reportedCases = parseInt($('#reportedCases').val());
        var totalHospitalBeds = parseInt($('#totalHospitalBeds').val());
        var periodType = $('#periodType').val();

        var data = {
            region: {
                name: "Africa",
                avgAge: 19.7,
                avgDailyIncomeInUSD: 5,
                avgDailyIncomePopulation: 0.71
            },
            periodType: periodType,
            timeToElapse: timeToElapse,
            reportedCases: reportedCases,
            population: population,
            totalHospitalBeds: totalHospitalBeds
        };

        if (population == '' || timeToElapse == '' || reportedCases == '' || totalHospitalBeds == '' || periodType == '') {
            return false;
        }
        // console.log(data);
        $http({
            method: 'POST',
            url: baseurl,
            data: data

        }).then(function successCallback(response) {

                var res = response.data;
                if (res.responseCode == '000') {
                    $.notify({
                        message: res.message
                    }, {
                        placement: {
                            from: "top",
                            align: "center"
                        },
                        allow_dismiss: true,
                        type: "success",
                        timer: 1000
                    });

                    console.log(res);

                    $scope.impact = res.data.impact;
                    $scope.severe_impact = res.data.severeImpact;

                    $('#covid_submit').trigger("reset");

                } else {
                    $.notify({
                        message: res.message
                    }, {
                        placement: {
                            from: "top",
                            align: "center"
                        },
                        allow_dismiss: true,
                        type: "error",
                        timer: 2000
                    });

                }
            },
            function errorCallback(response) {

                $.notify({
                    message: "Connection Error, check network"
                }, {
                    placement: {
                        from: "top",
                        align: "center"
                    },
                    allow_dismiss: true,
                    type: "error",
                    timer: 2000
                });
            });

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
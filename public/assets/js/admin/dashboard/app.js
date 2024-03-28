/*jshint esversion: 6 */
var app = angular.module("homeApp", ['ngRoute']);

function random_rgba() {
    var o = Math.round,
        r = Math.random,
        s = 255;
    return 'rgba(' + o(r() * s) + ',' + o(r() * s) + ',' + o(r() * s) + ',' + r().toFixed(1) + ')';
}

function getRandomRgb() {
    var num = Math.round(0xffffff * Math.random());
    var r = num >> 16;
    var g = num >> 8 & 255;
    var b = num & 255;
    return 'rgb(' + r + ', ' + g + ', ' + b + ')';
}
app.controller("homeController", function ($scope, service) {

    var fun = $scope;
    var service = service;


    fun.getDataMahasiswa = () => {
        service.dataMahasiswa(res => {
            const { data, success } = res;
            var hasil = grafikJumlahMahasiswa(data);;

            const labels = Object.keys(hasil);
            const grafikcount = Object.values(hasil);
            var grafikcolor = [];

            for (var index = 0; index < grafikcount.length; index++) {
                grafikcolor.push(getRandomRgb());

            }

            setTimeout(() => {
                const grafikdata = {
                    labels: labels,
                    datasets: [{
                        label: 'Grafik Mahasiswa',
                        backgroundColor: grafikcolor, // Different background colors
                        borderColor: grafikcolor,
                        data: grafikcount,
                    }]
                };

                // Configuration options
                const config = {
                    type: 'bar', // Change to bar type for bar chart
                    data: grafikdata,
                };
                var canvasmahasiswa = document.getElementById("chartmahasiswa");
                var myChart = new Chart(
                    canvasmahasiswa,
                    config
                );

            }, 500);

        });
    }

    fun.getDataKkn = () => {
        service.dataCalonKkn(res => {
            const { data, success } = res;
            const calonkkngrafik = grafikJumlahCalonKkn(data);

            const labels = Object.keys(calonkkngrafik);
            const grafikcount = Object.values(calonkkngrafik);
            var grafikcolor = [];

            for (var index = 0; index < grafikcount.length; index++) {
                grafikcolor.push(getRandomRgb());

            }

            setTimeout(() => {
                const grafikdata = {
                    labels: labels,
                    datasets: [{
                        label: 'Grafik Calon Peserta KKN',
                        backgroundColor: grafikcolor, // Different background colors
                        borderColor: grafikcolor,
                        data: grafikcount,
                    }]
                };

                // Configuration options
                const config = {
                    type: 'bar', // Change to bar type for bar chart
                    data: grafikdata,
                };
                var canvasmahasiswa = document.getElementById("chartcalonkkn");
                var myChart = new Chart(
                    canvasmahasiswa,
                    config
                );

            }, 500);

        })
    }

    function grafikJumlahCalonKkn(data) {
        const jumlahPerFakultas = {};

        data.forEach(calonkkn => {
            const fakultas = calonkkn.nama_fakultas;
            if (jumlahPerFakultas[fakultas]) {
                jumlahPerFakultas[fakultas]++;
            } else {
                jumlahPerFakultas[fakultas] = 1;
            }
        });

        return jumlahPerFakultas;
    }

    function grafikJumlahMahasiswa(data) {
        const jumlahPerFakultas = {};

        data.forEach(mahasiswa => {
            const fakultas = mahasiswa.nama_fakultas;
            if (jumlahPerFakultas[fakultas]) {
                jumlahPerFakultas[fakultas]++;
            } else {
                jumlahPerFakultas[fakultas] = 1;
            }
        });

        return jumlahPerFakultas;
    }

    fun.getDataMahasiswa();
    fun.getDataKkn();

});

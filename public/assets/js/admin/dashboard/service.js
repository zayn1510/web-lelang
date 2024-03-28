app.service("service", ["$http", function ($http) {

    this.dataMahasiswa = function (callback) {
        $http({
            url: URL_API + "mahasiswa/load-data-mahasiswa",
            method: "GET"
        }).then(function (e) {

            callback(e.data);
        }).catch(function (err) {

        });
    }

    this.dataCalonKkn = function (callback) {
        $http({
            url: URL_API + "mahasiswa/load-data-calon-kkn",
            method: "GET"
        }).then(function (e) {

            callback(e.data);
        }).catch(function (err) {

        });
    }


}]);

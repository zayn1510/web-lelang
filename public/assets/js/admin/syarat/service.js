app.service("service", ["$http", function($http) {

    this.dataPeriodeKkn = function(callback) {
        $http({
            url: URL_API+"periode-kkn/load-data",
            method: "GET"
        }).then(function(e) {

            callback(e.data);
        }).catch(function(err) {

        });
    }

    this.dataSyarat = function(callback) {
        $http({
            url: URL_API+"syarat-berkas-kkn",
            method: "GET"
        }).then(function(e) {

            callback(e.data);
        }).catch(function(err) {

        });
    }
    this.createSyarat = function(obj, callback) {

        $http({
            url:URL_API+"syarat-berkas-kkn",
            method: "POST",
            data: obj
        }).then(function(e) {

            callback(e.data);
        }).catch(function(err) {

        });
    }
    this.updateSyarat = function(obj,id, callback) {
        $http({
            url: URL_API+"syarat-berkas-kkn/"+id,
            method: "PUT",
            data: obj
        }).then(function(e) {

            callback(e.data);
        }).catch(function(err) {

        });
    }

    this.deleteSyarat = function(id,callback) {
        $http({
            url: URL_API+"syarat-berkas-kkn/"+id,
            method: "DELETE",

        }).then(function(e) {

            callback(e.data);
        }).catch(function(err) {

        });
    }



}]);

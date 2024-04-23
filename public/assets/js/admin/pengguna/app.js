/*jshint esversion: 6 */
$(document).ready(function () {
    $('#dataTable').DataTable();
});
var app = angular.module("homeApp", ['ngRoute', 'datatables']);


app.controller("homeController", function ($scope, service) {

    var fun = $scope;
    var service = service;

    fun.aksi = true;

    fun.loadPengguna = () => {
        service.dataPengguna(row => {
            fun.pengguna = row.data;
        });
    }
    fun.loadPengguna();

    fun.delete = (row) => {
        service.deletePengguna(row.id, res => {
            const { success } = res;
            if (success) {
                swal({
                    text: "Hapus data berhasil !",
                    icon: "success"
                });
                fun.loadPengguna();
                return;
            }
            swal({
                text: "Hapus data gagal !",
                icon: "error"
            });
        });
    }


});

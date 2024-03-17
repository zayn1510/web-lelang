/*jshint esversion: 6 */
$(document).ready(function () {
    $('#dataTable').DataTable();
});
var app = angular.module("homeApp", ['ngRoute', 'datatables']);


app.controller("homeController", function ($scope, service) {

    var fun = $scope;
    var service = service;
    var message = "";
    var id_syarat = 0;
    var responjson = [
        { sukses: "Simpan data berhasil", error: "Simpan data gagal" },
        { sukses: "Update Data Berhasil", error: "Update data gagal" },
        { sukses: "Delete Data Berhasil", error: "Delete data gagal" }
    ];

    var syarat = document.getElementsByClassName("syarat");


    fun.aksi = true;
    fun.loadData = () => {
        service.dataSyarat((res) => {
            fun.datasyarat = res.data;
        })
    }
    fun.getPeriodeKkn = () => {
        service.dataPeriodeKkn((res) => {
            fun.dataperiode = res.data;
        })
    }

    fun.getPeriodeKkn();

    fun.loadData();

    fun.clearText = () => {
        for (var i = 0; i < syarat.length; i++) {
            syarat[i].value = "";
        }
    }

    fun.tambahData = () => {
        fun.aksi = false;
        fun.ket = "Form Tambah Syarat Berkas KKN";
        fun.clearText();

    }

    fun.editData = (row) => {
        syarat[0].value = row.title_berkas;
        syarat[1].value = row.name_berkas;
        syarat[2].value = row.tipe_berkas;
        syarat[3].value = row.periode_kkn;
        id_syarat = row.id;
        fun.aksi = true;
    }



    fun.checkValidation = () => {
        if (syarat[0].value.length == 0 && syarat[1].value.length == 0 && syarat[2].value.length == 0 && syarat[3].value.length == 0) {
            message = "Data Masih Kosong";
            return true;
        } else if (syarat[0].value.length == 0) {
            message = "Judul Berkas Masih Kosong";
            return true;
        } else if (syarat[1].value.length == 0) {
            message = "Name Berkas Masih Kosong";
            return true;
        }
        else if (syarat[2].value.length == 0) {
            message = "Tipe Berkas Masih Kosong";
            return true;
        } else if (syarat[3].value.length == 0) {
            message = "Periode KKN Belum Dipilih"
        }


    }


    fun.saveSyarat = () => {
        var check = fun.checkValidation();
        if (check) {
            swal({
                text: message,
                icon: "error"
            });

            return;
        }
        var data = {
            title_berkas: syarat[0].value,
            tipe_berkas: syarat[2].value,
            name_berkas: syarat[1].value,
            periode_kkn: syarat[3].value

        };
        service.createSyarat(data, (res) => {
            const { success } = res;
            if (success) {
                swal({
                    text: responjson[0].sukses,
                    icon: "success"
                });
                fun.clearText();
                fun.loadData();
                return;
            }

            swal({
                text: responjson[0].error,
                icon: "error"
            });
        })

    }

    fun.updateSyarat = () => {
        var data = {
            title_berkas: syarat[0].value,
            tipe_berkas: syarat[2].value,
            name_berkas: syarat[1].value,
            periode_kkn: syarat[3].value

        };
        service.updateSyarat(data, id_syarat, (res) => {
            if (res.success) {
                swal({
                    text: responjson[1].sukses,
                    icon: "success"
                });
                fun.loadData();
                return;
            }

            swal({
                text: responjson[1].error,
                icon: "error"
            });
        })
    }


    fun.delete = (row) => {

        service.deleteSyarat(row.id, (res) => {
            if (res.success) {
                swal({
                    text: responjson[2].sukses,
                    icon: "success"
                });
                fun.loadData();
                return;
            }

            swal({
                text: responjson[2].error,
                icon: "error"
            });
        })
    }




});

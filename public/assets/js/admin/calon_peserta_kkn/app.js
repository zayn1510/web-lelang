/*jshint esversion: 6 */
$(document).ready(function () {
    $('#dataTable').DataTable();
});
var app = angular.module("homeApp", ['ngRoute', 'datatables']);

app.controller("homeController", function ($scope, service) {

    var fun = $scope;
    var service = service;
    fun.attr = true;

    var id_calon_kkn = 0;

    var status = document.getElementById("status");
    var id_periode = document.getElementById("id_periode");

    fun.sort_date = (a, b) => {
        return new Date(a.tanggal).getTime() - new Date(b.tanggal).getTime();
    }


    status.addEventListener("change", (evt) => {
        let obj = {
            id_calon_kkn: id_calon_kkn,
            status: status.value
        };
        $("#cover-spin").show();
        service.konfirmasiCalon(obj, res => {
            if (res.success) {
                setTimeout(function () {
                    fun.attr = true;
                    $("#cover-spin").hide();
                    fun.loadPesertaKkn();
                    $scope.$apply();
                }, 2000);
            }
        })
    });
    fun.loadPesertaKkn = () => {
        service.dataCalonKkn(res => {
            fun.datapesertakkn = res.data;

        });
    };

    fun.loadPeriodeKkn = () => {
        service.getPeriodeKkn(res => {
            var dataperiode = res.data;
            fun.dataperiode = dataperiode.sort(fun.sort_date);
            const filterperiode = dataperiode.filter(row => {
                return row.status == 1;
            });
            setTimeout(function () {
                for (i = 0; i < id_periode.length; i++) {
                    if (id_periode.options[i].value == filterperiode[0].id) {
                        id_periode.selectedIndex = i;
                        return;
                    }
                }
            }, 500);

        })
    };

    fun.datastatus = [
        { "status": 0, "caption": "Belum Diterima" },
        { "status": 1, "caption": "Diterima" },
        { "status": 2, "caption": "Ditolak" }
    ];



    fun.loadPeriodeKkn();
    fun.loadPesertaKkn();

    fun.editData = (row) => {
        fun.attr = false;
        id_calon_kkn = row.id_calon_kkn;
        fun.nim = row.nim_mhs;
        fun.nama = row.nama_mhs;
        fun.tempat_lahir = row.tempat_lahir
        fun.tgl_lahir = row.tgl_lahir;
        fun.kabupaten = row.kabupaten;
        fun.kecamatan = row.kecamatan;
        fun.desa = row.desa;
        fun.nomor_hp = (row.nomor_hp !== null) ? row.nomor_hp : "belum ada";
        fun.email = row.email;
        fun.kode_calon_kkn = (row.kode_calon_kkn !== null) ? row.kode_calon_kkn : "belum ada";
        fun.ukuran_baju = (row.ukuran_baju !== null) ? row.ukuran_baju : "belum ada";

        service.getBerkasCalonKkn(id_calon_kkn, res => {
            const { data } = res;
            fun.databerkas = data;
        });
    }


    fun.openFile = (pathfile, name_file) => {
        var link = document.createElement("a");
        link.target = "_blank";
        if (name_file === 'Belum ada') {
            swal({
                text: "Berkas tidak tersedia",
                icon: "warning"
            });
        } else {
            window.open(URL_APP + "calonkkn/" + pathfile + "/" + fun.nim + "/" + name_file, "_blanck");
        }
    }
    fun.cancel = () => {
        fun.attr = true;
        fun.loadPesertaKkn();
    }
    fun.getDataByPeriode = () => {
        service.getCalonKknPeriode(id_periode.value, res => {
            fun.datapesertakkn = res.data;

        })
    }

    fun.deleteData = (id) => {
        service.deleteCalonKkn(id, res => {
            if (!res.success) {
                $(".error-delete-kkn").toggle();
                return;
            }
            $(".success-delete-kkn").toggle();
            fun.loadPesertaKkn();
        });
    }
});

@extends('admin.layout.template')
@section('header-lvl-1')
    <div class="col-sm-6">
        <div class="breadcrumbs-area clearfix">
            <h4 class="page-title pull-left">Dashboard</h4>
            <ul class="breadcrumbs pull-left">
                <li><a href="index.html">Home</a></li>
                <li><span>{{ $data->keterangan }}</span></li>
            </ul>
        </div>
    </div>
@endsection
@section('content')
    <div class="main-content-inner" ng-app="homeApp" ng-controller="homeController">
        <div class="row">
            <div class="col-12 mt-5" ng-show="attr">
                <div class="card">
                    <div class="card-body" id="tabel-toko">
                        <div class="row" style="margin-bottom: 10px;">
                            <div class="col-10">
                                <p style="font-size: 17px" class="poppins">{{ $data->keterangan }}</p>
                            </div>
                            <div class="col-2">
                            </div>
                        </div>
                        <div class="data-tab">
                            <div class="row">
                                <div class="col-4">
                                    <div class="form-filter cursor no-color">
                                        <div class="row">
                                            <div class="col-4">
                                                <p style="margin-top: 10px;" class="poppins">Tahun Akademik</p>
                                            </div>
                                            <div class="col-6">
                                                <div class="field-form">
                                                    <select class="form-control poppins" id="id_periode">
                                                        <option ng-repeat="row in dataperiode"
                                                            value="@{{ row.id }}">@{{ row.tahunakademik }} -
                                                            @{{ row.angkatan }}</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-2">
                                                <button class="btn btn-primary poppins"
                                                    ng-click="getDataByPeriode()">Pencarian</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="alert alert-danger poppins error-delete-kkn">Hapus data gagal !</div>
                                    <div class="alert alert-success poppins success-delete-kkn">Hapus data berhasil</div>
                                </div>
                            </div>
                            <table datatable="ng" class="table table-bordered poppins">
                                <thead class="bg-light" style="font-size: 12px;">
                                    <tr class="text-center">
                                        <th>No</th>
                                        <th>NIM</th>
                                        <th>Nama Lengkap</th>
                                        <th>Tahun Periode KKN</th>
                                        <th>Angkatan</th>
                                        <th>Status</th>
                                        <th>Tanggal Daftar</th>
                                        <th>
                                            Aksi
                                        </th>
                                    </tr>
                                </thead>
                                <tbody style="font-size: 12px">
                                    <tr class="text-center" ng-repeat="row in datapesertakkn">
                                        <td>@{{ $index + 1 }}</td>
                                        <td>@{{ row.nim_mhs }}</td>
                                        <td>@{{ row.nama_mhs }}</td>
                                        <td>@{{ row.tahun_akademik }}</td>
                                        <td>@{{ row.angkatan }}</td>
                                        <td>
                                            <p class="alert alert-warning" ng-if="row.status==0">Sedang Proses</p>
                                            <p class="alert alert-success" ng-if="row.status==1">Diterima</p>
                                            <p class="alert alert-danger" ng-if="row.status==2">Tidak Diterima</p>
                                        </td>
                                        <td>@{{ row.tgl_akademik }}</td>
                                        <td>
                                            <button class="alert alert-primary btn-edit" ng-click="editData(row)"> Detail
                                                Data</button>
                                            <button class="alert alert-danger btn-delete"
                                                ng-click="deleteData(row.id_calon_kkn)"> Hapus
                                                Data </button>

                                        </td>

                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 mt-5" ng-hide="attr" style="margin: auto;">
                <div class="card">
                    <div class="card-body" id="tabel-toko">
                        <div class="row" style="margin-bottom: 10px;">
                            <div class="col-10">
                                <h4 class="poppins">Calon Peserta KKN</h4>
                            </div>
                            <div class="col-2">
                                <select class="form-control poppins" id="status">
                                    <option value="0">Review</option>
                                    <option value="1">Diterima</option>
                                    <option value="2">Ditolak</option>
                                </select>
                            </div>
                        </div>
                        <div class="data-tab">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="col-12 detail-profil-mhs">
                                        <div class="row sub-profil">
                                            <div class="col-12">
                                                <h5>Biodata Diri</h5>
                                            </div>
                                            <div class="col-12">
                                                <div class="profil-mhs">
                                                    <p>NIM</p>
                                                    <p class="field">@{{ nim }}</p>
                                                </div>
                                                <div class="profil-mhs">
                                                    <p>Nama Lengkap</p>
                                                    <p class="field">@{{ nama }}</p>
                                                </div>
                                                <div class="profil-mhs">
                                                    <p>Tempat Lahir</p>
                                                    <p class="field">@{{ tempat_lahir }}</p>
                                                </div>
                                                <div class="profil-mhs">
                                                    <p>Tanggal Lahir</p>
                                                    <p class="field">@{{ tgl_lahir }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row sub-profil">
                                            <div class="col-12">
                                                <h5>Alamat Rumah</h5>
                                            </div>
                                            <div class="col-12">
                                                <div class="profil-mhs">
                                                    <p>Kabupaten</p>
                                                    <p class="field">@{{ kabupaten }}</p>
                                                </div>
                                                <div class="profil-mhs">
                                                    <p>Kecamatan</p>
                                                    <p class="field">@{{ kecamatan }}</p>
                                                </div>
                                                <div class="profil-mhs">
                                                    <p>Desa/Kelurahan</p>
                                                    <p class="field">@{{ desa }}</p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row sub-profil">
                                            <div class="col-12">
                                                <h5>Kontak</h5>
                                            </div>
                                            <div class="col-12">
                                                <div class="profil-mhs">
                                                    <p>Nomor Handphone/Telepon</p>
                                                    <p class="field">@{{ nomor_hp }}</p>
                                                </div>
                                                <div class="profil-mhs">
                                                    <p>Email</p>
                                                    <p class="field">@{{ email }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="row sub-profil">

                                        <div class="col-12">
                                            <table class="table table-bordered poppins">
                                                <thead>
                                                    <tr class="text-center">
                                                        <th>Nomor</th>
                                                        <th>Judul Berkas</th>
                                                        <th>File</th>
                                                        <th>Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr ng-repeat="row in databerkas" class="text-center">
                                                        <td>@{{ $index + 1 }}</td>
                                                        <td>@{{ row.title_berkas }}</td>
                                                        <td><a href="/calonkkn/@{{row.name_berkas}}/@{{nim}}/@{{row.file}}" target="__blank">@{{ row.file }}</a></td>
                                                        <td>
                                                            <p class="alert alert-danger" ng-if="row.id_berkas_calon_kkn ==null">Belum ada</p>
                                                            <p class="alert alert-info" ng-if="row.id_berkas_calon_kkn !=null">Tersedia</p>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>

                                    </div>

                                </div>

                                <div class="col-md-12">
                                    <div class="row" style="margin-top: 20px;">
                                        <div class="col-10">
                                            <button class="btn btn-danger poppins" ng-click="cancel()">Batal</button>
                                        </div>
                                    </div>
                                </div>


                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>


    </div>

    <div id="cover-spin">
        <div class="modal-message">
            <h2 class="animate">Loading</h2>
        </div>
    </div>
@endsection
@section('javascript')
    <script src="{{ asset('assets/angularjs/angular.min.js') }}"></script>
    <script src="{{ asset('assets/angularjs/angular-route.min.js') }}"></script>
    <script src="{{ asset('assets/angularjs/angular-datatables.min.js') }}"></script>
    <script src="{{ asset('assets/angularjs/sweetalert.min.js') }}"></script>
    <script src="{{ asset('assets/js/admin/calon_peserta_kkn/app.js') }}"></script>
    <script src="{{ asset('assets/js/admin/calon_peserta_kkn/service.js') }}"></script>
@endsection

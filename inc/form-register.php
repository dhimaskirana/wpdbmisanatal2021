<?php

/**
 * Form to registration
 * Form use bootstrap and vue.js
 */

?>

<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <title>Misa Natal Pringgolayan 2021</title>
</head>

<body>
    <div id="app">
        <div class="px-4 py-4">
            <div class="col-lg-6 mx-auto mt-2">
                <h1 class="display-5 fw-bold">Tracking Misa Natal Pringgolayan 2021</h1>
                <p>Selamat datang di Gereja Pringgolayan.</p>
                <p>Bagi umat dari luar Paroki diharapkan mengisi data untuk keperluan tracking umat luar paroki.</p>
                <p class="mb-4">Silahkan klik tombol <strong>Tambah Umat</strong> untuk mengisi data diri anda. Jika anda bersama keluarga, anda bisa tambahkan anggota keluarga anda.</p>
                <p>Setelah misa selesai, anda bisa scan barcode untuk membuka halaman ini dan silahkan klik tombol logout. Terima kasih.</p>
                <p><small><em>Panitia Natal dan Tahun Baru Gereja Santo Paulus Pringgolayan 2021</em></small></p>
                <div class="list-group mb-4">
                    <div class="list-group-item list-group-item-action d-flex gap-3 py-3" aria-current="true" v-for="(item, index) in daftarumat">
                        <div class="d-flex gap-2 w-100 justify-content-between">
                            <div>
                                <h3 class="mb-2">{{ item.nama_umat }}</h3>
                                <p class="mb-0 opacity-75">
                                    Masuk pada {{ humantime(item.waktu_masuk) }} WIB
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-grid gap-2 d-sm-flex justify-content-sm-center">
                    <button type="button" :disabled="prosesspinner == true" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahUmat">
                        <span v-if="prosesspinner" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Tambah Umat
                    </button>
                    <button v-show="daftarumat != null" :disabled="logoutspinner == true" v-on:click="logoutdataumat" type="button" class="btn btn-danger">
                        <span v-if="logoutspinner" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Logout Semua
                    </button>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="modalTambahUmat" tabindex="-1" aria-labelledby="modalTambahUmatLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form @submit="tambahumat" method="post" id="tambahumat">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalTambahUmatLabel">Tambah Umat</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="nama_umat" class="form-label">Nama Lengkap</label>
                                <input type="text" class="form-control" name="nama_umat" v-model="inputdataumat.nama_umat">
                            </div>
                            <div class="mb-3">
                                <label for="usia_umat" class="form-label">Usia</label>
                                <input type="text" class="form-control" name="usia_umat" v-model="inputdataumat.usia_umat">
                            </div>
                            <div class="mb-3">
                                <label for="alamat_umat" class="form-label">Alamat</label>
                                <textarea class="form-control" name="alamat_umat" v-model="inputdataumat.alamat_umat"></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="jenis_kelamin_umat" class="form-label">Jenis Kelamin</label>
                                <select class="form-select" name="jenis_kelamin_umat" v-model="inputdataumat.jenis_kelamin_umat">
                                    <option value="pria">Pria</option>
                                    <option value="wanita">Wanita</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" data-bs-dismiss="modal" class="btn btn-primary">
                                Kirim
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Optional JavaScript; choose one of the two! -->
    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.14"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
    -->
    <script>
        var app = new Vue({
            el: '#app',
            data: {
                message: 'Hello Vue!',
                inputdataumat: {
                    nama_umat: null,
                    usia_umat: null,
                    alamat_umat: null,
                    jenis_kelamin_umat: null
                },
                daftarumat: null,
                prosesspinner: false,
                logoutspinner: false
            },
            created() {
                this.loadLocalStorage();
            },
            methods: {
                loadLocalStorage() {
                    const daftarumat = JSON.parse(localStorage.getItem('daftarumat'));
                    this.daftarumat = daftarumat;
                },
                logoutdataumat(event) {
                    event.preventDefault();
                    const umatkeluarajax = axios.create();
                    umatkeluarajax.interceptors.request.use(config => {
                        // perform a task before the request is sent
                        this.logoutspinner = true;
                        return config;
                    }, error => {
                        // handle the error
                        return Promise.reject(error);
                    });
                    umatkeluarajax.post('<?php echo site_url(); ?>/wp-json/natal2021/v1/keluar', this.daftarumat).then(response => {
                        this.logoutspinner = false;
                        // console.log(response.data);
                        this.daftarumat = null;
                        localStorage.removeItem('daftarumat');
                        swal('Logout Sukses', 'Terima kasih telah menghadiri Misa Natal 2021 di Gereja Pringgolayan. Selamat Natal 2021, Berkah Dalem Gusti 🙏 🎄', 'success');
                    }).catch(error => {
                        if (error.response.status == 422) {
                            this.errors = error.response.data;
                        }
                    });
                },
                tambahumat(event) {
                    event.preventDefault();
                    if (this.inputdataumat.nama_umat && this.inputdataumat.usia_umat && this.inputdataumat.alamat_umat && this.inputdataumat.jenis_kelamin_umat) {
                        // var tambahumatmodal = document.getElementById('tambahumat');
                        // var tambahumatmodal = new bootstrap.Modal('#tambahumat');
                        // var tambahumatmodal = new bootstrap.Modal(document.getElementById('modalTambahUmat'));
                        const tambahumatajax = axios.create();
                        tambahumatajax.interceptors.request.use(config => {
                            // perform a task before the request is sent
                            this.prosesspinner = true;
                            return config;
                        }, error => {
                            // handle the error
                            return Promise.reject(error);
                        });
                        tambahumatajax.post('<?php echo site_url(); ?>/wp-json/natal2021/v1/proses', this.inputdataumat).then(response => {
                            // tambahumatmodal.hide();
                            this.prosesspinner = false;
                            // console.log(response.data);
                            if (this.daftarumat == null) {
                                this.daftarumat = {};
                            }
                            this.$set(this.daftarumat, response.data.id_umat, response.data);
                            this.simpan_ke_localstorage(this.daftarumat);
                            this.inputdataumat = {};
                        }).catch(error => {
                            if (error.response.status == 422) {
                                this.errors = error.response.data;
                            }
                        });
                    } else {
                        swal('Tidak Boleh Kosong', 'Mohon kolom isian di isi sesuai dengan data diri anda.', 'warning');
                    }
                },
                simpan_ke_localstorage(data) {
                    localStorage.setItem('daftarumat', JSON.stringify(data));
                },
                humantime(datetime) {
                    const [dateparts, timeparts] = datetime.split(" ");
                    const [year, month, day] = dateparts.split("-");
                    const [hours = 0, minutes = 0, seconds = 0] = timeparts?.split(":") ?? [];
                    // Split timestamp into [ Y, M, D, h, m, s ]
                    // var t = datetime.split(/[- :]/);


                    // Apply each element to the Date function
                    // var d = new Date(Date.UTC(t[0], t[1] - 1, t[2], t[3], t[4], t[5]));
                    // var d = new Date(datetime);
                    var d = new Date(+year, +month - 1, +day, +hours, +minutes, +seconds);

                    const options = {
                        weekday: 'long',
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    };

                    return d.toLocaleDateString('id-ID', options);
                }
            }
        })
    </script>
</body>

</html>
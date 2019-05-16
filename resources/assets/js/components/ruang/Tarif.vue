<template>
  <div class="container">
    <div class="row mt-5" v-if="$gate.isAdminOrAuthor()">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Daftar Ruang dan Tarif</h3>

            <div class="card-tools">
              <button class="btn btn-success" @click="newModal">
                Tambah
                <i class="fas fa-user-plus fa-fw"></i>
              </button>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body table-responsive p-0">
            <table class="table table-hover">
              <tbody>
                <tr>
                  <th>Nama Ruangan</th>
                  <th>Kelas</th>
                  <th>Kategori</th>
                  <th>No.Kamar</th>
                  <th>No.TT</th>
                  <th>Tarif</th>
                  <th>Aksi</th>
                </tr>

                <tr v-for="rtarif in rtarifs.data" :key="rtarif.id">
                  <td>{{rtarif.nama}}</td>
                  <td>{{rtarif.kelas}}</td>
                  <td>{{rtarif.kategori}}</td>
                  <td>{{rtarif.nokamar}}</td>
                  <td>{{rtarif.nott}}</td>
                  <td>{{rtarif.tarif}}</td>
                  <td>
                    <a href="#" @click="editModal(rtarif)">
                      <i class="fa fa-edit blue"></i>
                    </a>
                    /
                    <a href="#" @click="deleteUser(rtarif.id)">
                      <i class="fa fa-trash red"></i>
                    </a>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <!-- /.card-body -->
          <div class="card-footer">
            <pagination :data="rtarifs" @pagination-change-page="getResults"></pagination>
          </div>
        </div>
        <!-- /.card -->
      </div>
    </div>

    <div v-if="!$gate.isAdminOrAuthor()">
      <not-found></not-found>
    </div>

    <!-- Modal -->
    <div
      class="modal fade"
      id="addNew"
      tabindex="-1"
      role="dialog"
      aria-labelledby="addNewLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" v-show="!editmode" id="addNewLabel">Tambah Data</h5>
            <h5 class="modal-title" v-show="editmode" id="addNewLabel">Esit Data</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form @submit.prevent="editmode ? updateUser() : createUser()">
            <div class="modal-body">
              <div class="form-group">
                <input
                  v-model="form.nama"
                  type="text"
                  name="nama"
                  placeholder="Nama Ruangan"
                  class="form-control"
                  :class="{ 'is-invalid': form.errors.has('nama') }"
                >
                <has-error :form="form" field="nama"></has-error>
              </div>
              <div class="form-group">
                <select
                  name="kelas"
                  v-model="form.kelas"
                  id="kelas"
                  class="form-control"
                  :class="{ 'is-invalid': form.errors.has('kelas') }"
                >
                  <option value>Pilih Kelas</option>
                  <option v-for="data in kelas" :key="data.id" :value="data.kelas">{{data.kelas}}</option>
                </select>
                <has-error :form="form" field="status"></has-error>
              </div>
              <div class="form-group">
                <select
                  name="kategori"
                  v-model="form.kategori"
                  id="kategori"
                  class="form-control"
                  :class="{ 'is-invalid': form.errors.has('kategori') }"
                >
                  <option value>Pilih Kategori</option>
                  <option
                    v-for="data in kategori"
                    :key="data.id"
                    :value="data.kategori"
                  >{{data.kategori}}</option>
                </select>
                <has-error :form="form" field="status"></has-error>
              </div>
              <div class="form-group">
                <input
                  v-model="form.nokamar"
                  type="number"
                  name="nokamar"
                  placeholder="Nomor Kamar"
                  class="form-control"
                  :class="{ 'is-invalid': form.errors.has('nokamar') }"
                >
                <has-error :form="form" field="nokamar"></has-error>
              </div>
              <div class="form-group">
                <input
                  v-model="form.nott"
                  type="number"
                  name="nott"
                  placeholder="Nomor TT"
                  class="form-control"
                  :class="{ 'is-invalid': form.errors.has('nott') }"
                >
                <has-error :form="form" field="nott"></has-error>
              </div>
              <div class="form-group">
                <input
                  v-model="form.tarif"
                  type="number"
                  name="tarif"
                  placeholder="Tarif"
                  class="form-control"
                  :class="{ 'is-invalid': form.errors.has('tarif') }"
                >
                <has-error :form="form" field="tarif"></has-error>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-dismiss="modal">Tutup</button>
              <button v-show="editmode" type="submit" class="btn btn-success">Update</button>
              <button v-show="!editmode" type="submit" class="btn btn-primary">Buat</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      editmode: false,
      rtarifs: {},
      kelas: {},
      kategori: {},
      form: new Form({
        id: "",
        nama: "",
        kelas: "",
        kategori: "",
        nokamar: "",
        nott: "",
        tarif: ""
      })
    };
  },
  methods: {
    getResults(page = 1) {
      axios.get("api/rtarif?page=" + page).then(response => {
        this.rtarifs = response.data;
      });
    },
    updateUser() {
      this.$Progress.start();
      // console.log('Editing data');
      this.form
        .put("api/rtarif/" + this.form.id)
        .then(() => {
          // success
          $("#addNew").modal("hide");
          swal("Updated!", "Information has been updated.", "success");
          this.$Progress.finish();
          Fire.$emit("AfterCreate");
        })
        .catch(() => {
          this.$Progress.fail();
        });
    },
    editModal(user) {
      this.editmode = true;
      this.form.reset();
      $("#addNew").modal("show");
      this.form.fill(user);
    },
    newModal() {
      this.editmode = false;
      this.form.reset();
      $("#addNew").modal("show");
    },
    deleteUser(id) {
      swal({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!"
      }).then(result => {
        // Send request to the server
        if (result.value) {
          this.form
            .delete("api/rtarif/" + id)
            .then(() => {
              swal("Deleted!", "Your file has been deleted.", "success");
              Fire.$emit("AfterCreate");
            })
            .catch(() => {
              swal("Failed!", "There was something wronge.", "warning");
            });
        }
      });
    },
    loadUsers() {
      axios.get("api/rtarif").then(({ data }) => (this.rtarifs = data));
      axios.get("api/rkelas").then(({ data }) => (this.kelas = data.data));
      axios
        .get("api/rkategori")
        .then(({ data }) => (this.kategori = data.data));
    },

    createUser() {
      this.$Progress.start();

      this.form
        .post("api/rtarif")
        .then(() => {
          Fire.$emit("AfterCreate");
          $("#addNew").modal("hide");

          toast({
            type: "success",
            title: "Berhasil Membuat Data!"
          });
          this.$Progress.finish();
        })
        .catch(() => {
          this.$Progress.fail();
        });
    }
  },
  created() {
    Fire.$on("searching", () => {
      let query = this.$parent.search;
      axios
        .get("api/findUser?q=" + query)
        .then(data => {
          this.users = data.data;
        })
        .catch(() => {});
    });
    this.loadUsers();
    Fire.$on("AfterCreate", () => {
      this.loadUsers();
    });
    //    setInterval(() => this.loadUsers(), 3000);
  }
};
</script>

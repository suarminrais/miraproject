<template>
  <div class="container">
    <div class="row mt-5" v-if="$gate.isAdminOrAuthor()">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Daftar Suplier</h3>

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
                  <th>Kode</th>
                  <th>Nama</th>
                  <th>Alamat</th>
                  <th>Telp</th>
                  <th>Aksi</th>
                </tr>

                <tr v-for="suplier in supliers.data" :key="suplier.id">
                  <td>{{suplier.kode}}</td>
                  <td>{{suplier.nama}}</td>
                  <td>{{suplier.alamat}}</td>
                  <td>{{suplier.telp}}</td>
                  <td>
                    <a href="#" @click="editModal(suplier)">
                      <i class="fa fa-edit blue"></i>
                    </a>
                    /
                    <a href="#" @click="deleteUser(suplier.id)">
                      <i class="fa fa-trash red"></i>
                    </a>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <!-- /.card-body -->
          <div class="card-footer">
            <pagination :data="supliers" @pagination-change-page="getResults"></pagination>
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
                  v-model="form.kode"
                  type="text"
                  name="kode"
                  placeholder="Kode"
                  class="form-control"
                  :class="{ 'is-invalid': form.errors.has('kode') }"
                >
                <has-error :form="form" field="kode"></has-error>
              </div>
              <div class="form-group">
                <input
                  v-model="form.nama"
                  type="text"
                  name="nama"
                  placeholder="Nama"
                  class="form-control"
                  :class="{ 'is-invalid': form.errors.has('nama') }"
                >
                <has-error :form="form" field="nama"></has-error>
              </div>
              <div class="form-group">
                <input
                  v-model="form.alamat"
                  type="text"
                  name="alamat"
                  placeholder="Alamat"
                  class="form-control"
                  :class="{ 'is-invalid': form.errors.has('alamat') }"
                >
                <has-error :form="form" field="alamat"></has-error>
              </div>
              <div class="form-group">
                <input
                  v-model="form.telp"
                  type="text"
                  name="telp"
                  placeholder="Nomor Telpon"
                  class="form-control"
                  :class="{ 'is-invalid': form.errors.has('telp') }"
                >
                <has-error :form="form" field="telp"></has-error>
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
      supliers: {},
      form: new Form({
        kode: "",
        nama: "",
        alamat: "",
        telp: ""
      })
    };
  },
  methods: {
    getResults(page = 1) {
      axios.get("api/suplier?page=" + page).then(response => {
        this.dokters = response.data;
      });
    },
    updateUser() {
      this.$Progress.start();
      // console.log('Editing data');
      this.form
        .put("api/suplier/" + this.form.id)
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
            .delete("api/suplier/" + id)
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
      if (this.$gate.isAdminOrAuthor()) {
        axios.get("api/suplier").then(({ data }) => (this.supliers = data));
      }
    },

    createUser() {
      this.$Progress.start();

      this.form
        .post("api/suplier")
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

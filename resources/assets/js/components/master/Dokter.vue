<template>
  <div class="container">
    <div class="row mt-5" v-if="$gate.isAdminOrAuthor()">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Daftar Dokter</h3>

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
                  <th>Nama</th>
                  <th>Spesialis</th>
                  <th>Telp</th>
                  <th>Status</th>
                  <th>Aksi</th>
                </tr>

                <tr v-for="dokter in dokters.data" :key="dokter.id">
                  <td>{{dokter.name}}</td>
                  <td>{{dokter.spesialis}}</td>
                  <td>{{dokter.telp}}</td>
                  <td>{{dokter.status}}</td>
                  <td>
                    <a href="#" @click="editModal(dokter)">
                      <i class="fa fa-edit blue"></i>
                    </a>
                    /
                    <a href="#" @click="deleteUser(dokter.id)">
                      <i class="fa fa-trash red"></i>
                    </a>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <!-- /.card-body -->
          <div class="card-footer">
            <pagination :data="dokters" @pagination-change-page="getResults"></pagination>
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
                  v-model="form.name"
                  type="text"
                  name="name"
                  placeholder="Name"
                  class="form-control"
                  :class="{ 'is-invalid': form.errors.has('name') }"
                >
                <has-error :form="form" field="name"></has-error>
              </div>
              <div class="form-group">
                <input
                  v-model="form.spesialis"
                  type="text"
                  name="spesialis"
                  placeholder="Spesialis"
                  class="form-control"
                  :class="{ 'is-invalid': form.errors.has('spesialis') }"
                >
                <has-error :form="form" field="spesialis"></has-error>
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

              <div class="form-group">
                <select
                  name="status"
                  v-model="form.status"
                  id="status"
                  class="form-control"
                  :class="{ 'is-invalid': form.errors.has('status') }"
                >
                  <option value>Pilih Status</option>
                  <option value="aktif">Aktif</option>
                  <option value="non aktif">Non Aktif</option>
                </select>
                <has-error :form="form" field="status"></has-error>
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
      dokters: {},
      form: new Form({
        id: "",
        name: "",
        spesialis: "",
        telp: "",
        status: ""
      })
    };
  },
  methods: {
    getResults(page = 1) {
      axios.get("api/dokter?page=" + page).then(response => {
        this.dokters = response.data;
      });
    },
    updateUser() {
      this.$Progress.start();
      // console.log('Editing data');
      this.form
        .put("api/dokter/" + this.form.id)
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
            .delete("api/dokter/" + id)
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
        axios.get("api/dokter").then(({ data }) => (this.dokters = data));
      }
    },

    createUser() {
      this.$Progress.start();

      this.form
        .post("api/dokter")
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

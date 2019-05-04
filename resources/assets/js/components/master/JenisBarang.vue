<template>
  <div class="container">
    <div class="row mt-5" v-if="$gate.isAdminOrAuthor()">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Daftar Jenis Barang</h3>

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
                  <th>Jenis Barang</th>
                  <th>Aksi</th>
                </tr>

                <tr v-for="barang in barangs.data" :key="barang.id">
                  <td>{{barang.kode}}</td>
                  <td>{{barang.jenis}}</td>
                  <td>
                    <a href="#" @click="editModal(barang)">
                      <i class="fa fa-edit blue"></i>
                    </a>
                    /
                    <a href="#" @click="deleteUser(barang.id)">
                      <i class="fa fa-trash red"></i>
                    </a>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <!-- /.card-body -->
          <div class="card-footer">
            <pagination :data="barangs" @pagination-change-page="getResults"></pagination>
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
                  placeholder="Kode Barang"
                  class="form-control"
                  :class="{ 'is-invalid': form.errors.has('kode') }"
                >
                <has-error :form="form" field="kode"></has-error>
              </div>
              <div class="form-group">
                <input
                  v-model="form.jenis"
                  type="text"
                  name="jenis"
                  placeholder="Jenis Barang"
                  class="form-control"
                  :class="{ 'is-invalid': form.errors.has('jenis') }"
                >
                <has-error :form="form" field="jenis"></has-error>
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
      barangs: {},
      form: new Form({
        jenis: "",
        kode: ""
      })
    };
  },
  methods: {
    getResults(page = 1) {
      axios.get("api/barang?page=" + page).then(response => {
        this.barangs = response.data;
      });
    },
    updateUser() {
      this.$Progress.start();
      // console.log('Editing data');
      this.form
        .put("api/barang/" + this.form.id)
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
            .delete("api/barang/" + id)
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
        axios.get("api/barang").then(({ data }) => (this.barangs = data));
      }
    },

    createUser() {
      this.$Progress.start();

      this.form
        .post("api/barang")
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

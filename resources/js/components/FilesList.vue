<template>
  <div>
    <div class="row mb-2">
      <div class="col-3 shadow-sm py-2 font-weight-bold">Description</div>
      <div class="col-2 shadow-sm py-2 font-weight-bold">Type</div>
      <div class="col-2 shadow-sm py-2 font-weight-bold">Created at</div>
      <div class="col-2 shadow-sm py-2 font-weight-bold">Updated at</div>
      <div class="col-3 shadow-sm py-2 font-weight-bold">Actions</div>
    </div>

    <div class="row" style="min-height:37px" v-for="(file, index) in files" :key="file.id">
      <div class="col-3 align-self-center">{{ file.description }}</div>
      <div class="col-2 align-self-center">{{ file.mime_type.substring(0, 22) }}</div>
      <div class="col-2 align-self-center">{{ file.created_at }}</div>
      <div class="col-2 align-self-center">{{ file.updated_at }}</div>
      <div class="col-3 align-self-center">
        <!-- other actions -->
        <a :href="'/files/' + file.id" class="btn btn-primary btn-sm mb-1">
          <i class="fas fa-file-download mr-1"></i>Download
        </a>
        <button v-on:click="deleteFile(index)" class="btn btn-danger btn-sm mb-1">
          <i class="fas fa-folder-minus mr-1"></i>Delete
        </button>
      </div>
    </div>

    <div class="row mt-2 shadow-sm" style="height:3px"></div>

    <div class="row mt-2 shadow-sm py-2">
      <div class="col-5 align-self-center">
        <input type="text" class="form-control" v-model="newDescription" />
      </div>
      <div class="col-5 align-self-center">
        <input
          type="file"
          id="file"
          ref="file"
          class="form-control-file border"
          @change="handleFileUpload()"
        />
      </div>
      <div class="col-2 ml-n2 align-self-center">
        <button v-on:click="addFile()" class="btn btn-primary">
          <span class="fas fa-save mr-2 lead"></span>Create new File
        </button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    folder_id: {
      type: Number,
      required: true
    }
  },
  data() {
    return {
      files: [],
      newDescription: "",
      newData: ""
    };
  },
  computed: {},
  methods: {
    handleFileUpload() {
      this.newData = this.$refs.file.files[0];
    },
    updateList() {
      var objFolderId = {};
      objFolderId.folder_id = this.folder_id;
      axios
        .post(app_url + "/api/files?api_token=" + api_token, objFolderId)
        .then(response => {
          console.log("token: ", api_token);
          console.log("files_list: ", response.data);
          this.files = response.data;
        })
        .catch(error => {
          if (error.response) {
            alert(
              error.response.status + " - " + error.response.data["message"]
            );
            console.log(error.response.data);
            console.log(error.response.status);
            console.log(error.response.headers);
          }
        });
    },
    addFile() {
      // preparing data
      let formData = new FormData();
      formData.append("data", this.newData);
      formData.append("description", this.newDescription);
      formData.append("folder_id", this.folder_id);

      console.log("added_folder_before_submit: ", formData);
      axios
        .post(app_url + "/api/files/store?api_token=" + api_token, formData, {
          headers: {
            "Content-Type": "multipart/form-data"
          }
        })
        .then(response => {
          var responseData = response.data;
          console.log("added_file_response_api: ", responseData);

          this.files.push(responseData);
        })
        .catch(error => {
          if (error.response) {
            alert(
              error.response.status + " - " + error.response.data["message"]
            );
            console.log(error.response.data);
            console.log(error.response.status);
            console.log(error.response.headers);
          }
        });
    },
    deleteFile(index) {
      console.log("deleted_file_before_submit: ", this.files[index]);
      axios
        .post(
          app_url + "/api/files/destroy?api_token=" + api_token,
          this.files[index]
        )
        .then(response => {
          var responseData = response.data;
          console.log("deleted_file_response_api: ", responseData);

          this.files.splice(index, 1);
        })
        .catch(error => {
          if (error.response) {
            alert(
              error.response.status + " - " + error.response.data["message"]
            );
            console.log(error.response.data);
            console.log(error.response.status);
            console.log(error.response.headers);
          }
        });
    }
  },
  mounted() {
    this.updateList();
  }
};
</script>

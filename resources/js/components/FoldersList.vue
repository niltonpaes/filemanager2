<template>
  <div>
    <h2>Folders</h2>

    <div class="row mb-2">
      <div class="col-4 shadow-sm py-2 font-weight-bold">Folder Name</div>
      <div class="col-2 shadow-sm py-2 font-weight-bold">Created at</div>
      <div class="col-2 shadow-sm py-2 font-weight-bold">Updated at</div>
      <div class="col-4 shadow-sm py-2 font-weight-bold">Actions</div>
    </div>

    <div class="row" style="min-height:37px" v-for="(folder, index) in folders" :key="folder.id">
      <div class="col-4 align-self-center">
        <div v-show="!isEditing(folder.isediting)">{{ folder.foldername }}</div>
        <div v-show="isEditing(folder.isediting)">
          <input type="text" class="form-control" v-model="folder.foldername" />
        </div>
      </div>
      <div class="col-2 align-self-center">{{ folder.created_at }}</div>
      <div class="col-2 align-self-center">{{ folder.updated_at }}</div>
      <div class="col-4 align-self-center">
        <!-- edit button -->
        <button
          v-show="!isEditingMode"
          v-on:click="editFolder(index)"
          class="btn btn-primary btn-sm mb-1"
        >
          <i class="far fa-edit mr-1"></i>Edit
        </button>

        <!-- ok button -->
        <button
          v-show="isEditing(folder.isediting)"
          v-on:click="updateFolder(index)"
          class="btn btn-success btn-sm mb-1"
        >
          <i class="far fa-check-circle mr-1" style="font-size: 16px"></i>
        </button>

        <!-- cancel button -->
        <button
          v-show="isEditing(folder.isediting)"
          v-on:click="editFolderCancel(index)"
          class="btn btn-danger btn-sm mb-1"
        >
          <i class="fas fa-undo-alt mr-1"></i>
        </button>

        <!-- other actions -->
        <a
          :href="'/folders/' + folder.id"
          v-show="!isEditingMode"
          class="btn btn-primary btn-sm mb-1"
        >
          <i class="fas fa-list-ul mr-1"></i>Files
        </a>
        <a
          :href="'/folders/' + folder.id + '/download'"
          v-show="!isEditingMode"
          class="btn btn-primary btn-sm mb-1"
        >
          <i class="fas fa-file-download mr-1"></i>Download
        </a>
        <button
          v-show="!isEditingMode"
          v-on:click="deleteFolder(index)"
          class="btn btn-danger btn-sm mb-1"
        >
          <i class="fas fa-folder-minus mr-1"></i>Delete
        </button>
      </div>
    </div>

    <div class="row mt-2 shadow-sm" style="height:3px"></div>

    <div class="row mt-2 shadow-sm py-2" v-show="!isEditingMode">
      <div class="col-4">
        <input type="text" class="form-control" v-model="newFolder" />
      </div>
      <div class="col-8 ml-n2">
        <button v-on:click="addFolder()" class="btn btn-primary">
          <span class="fas fa-folder-plus mr-2 lead"></span>Create new Folder
        </button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      folders: [],
      isEditingMode: false,
      oldValue: "",
      newFolder: ""
    };
  },
  computed: {},
  methods: {
    updateList() {
      axios
        .get(app_url + "/api/folders?api_token=" + api_token)
        .then(response => {
          console.log("token: ", api_token);
          console.log("folders_list: ", response.data);
          this.folders = response.data;
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
    updateFolder(index) {
      console.log("updated_folder_before_submit: ", this.folders[index]);
      axios
        .post(
          app_url + "/api/folders/update?api_token=" + api_token,
          this.folders[index]
        )
        .then(response => {
          var responseData = response.data;
          console.log("updated_folder_response_api: ", responseData);

          this.folders[index].updated_at = responseData.updated_at;
          this.folders[index].isediting = false;
          this.isEditingMode = false;
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
    addFolder() {
      var objNewFolder = {};
      objNewFolder.foldername = this.newFolder;
      console.log("added_folder_before_submit: ", objNewFolder);
      axios
        .post(
          app_url + "/api/folders/store?api_token=" + api_token,
          objNewFolder
        )
        .then(response => {
          var responseData = response.data;
          console.log("added_folder_response_api: ", responseData);

          this.folders.push(responseData);
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
    deleteFolder(index) {
      console.log("deleted_folder_before_submit: ", this.folders[index]);
      axios
        .post(
          app_url + "/api/folders/destroy?api_token=" + api_token,
          this.folders[index]
        )
        .then(response => {
          var responseData = response.data;
          console.log("deleted_folder_response_api: ", responseData);

          this.folders.splice(index, 1);
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
    isEditing: function(paramIsEditing) {
      if (paramIsEditing == undefined || paramIsEditing == false) {
        return false;
      } else {
        return true;
      }
    },
    editFolder: function(index) {
      this.folders[index].isediting = true;
      this.isEditingMode = true;
      this.oldValue = this.folders[index].foldername;
      this.$forceUpdate();
    },
    editFolderCancel: function(index) {
      this.folders[index].isediting = false;
      this.isEditingMode = false;
      this.folders[index].foldername = this.oldValue;
    }
  },
  created() {
    this.updateList();
  }
};
</script>

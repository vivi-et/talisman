<template>
  <div>
    <div class="content">
      <div class="container-fluid">
        <!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
        <div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">
          <p class="_title0">
            Category
            <Button @click="addModal=true">
              <Icon type="md-add" />Add Category
            </Button>
          </p>

          <div class="_overflow _table_div">
            <table class="_table">
              <!-- TABLE TITLE -->
              <tr>
                <th>ID</th>
                <th>Icon image</th>
                <th>Category name</th>
                <th>Created at</th>
                <th>Action</th>
              </tr>
              <!-- TABLE TITLE -->

              <!-- ITEMS -->
              <tr v-for="(category, i) in categoryLists" :key="i" v-if="categoryLists.length">
                <td>{{category.id}}</td>
                <td class="table_image">
                  <img :src="category.iconImage" />
                </td>
                <td class="_table_name">{{ category.categoryName }}</td>
                <td>{{ category.created_at }}</td>
                <td>
                  <Button
                    class="_btn _action_btn edit_btn1"
                    type="info"
                    size="small"
                    @click="showEditModal(category, i)"
                  >Edit</Button>
                  <Button
                    class="_btn _action_btn make_btn1"
                    type="error"
                    size="small"
                    @click="showDeletingModal(category, i)"
                    :loading="category.isDeleting"
                  >Delete</Button>
                </td>
              </tr>
              <!-- ITEMS -->
            </table>
          </div>
        </div>

        <!-- tag adding modal -->
        <Modal v-model="addModal" title="Add Category" :mask-closable="false" :closable="false">
          <Input v-model="data.categoryName" placeholder="Add Category name" />

          <div style="margin-top:10px; margin-bottom: 10px"></div>

          <!-- upload -->
          <Upload
            ref="uploads"
            type="drag"
            action="/app/upload"
            :headers="{'x-csrf-token': token, 'X-Requested-With': 'XMLHttpRequest'}"
            :on-success="handleSuccess"
            :format="['jpg','jpeg','png']"
            :max-size="2048"
            :on-format-error="handleFormatError"
            :on-error="handleError"
            :on-exceeded-size="handleMaxSize"
          >
            <div style="padding: 20px 0">
              <Icon type="ios-cloud-upload" size="52" style="color: #3399ff"></Icon>
              <p>Click or drag files here to upload</p>
            </div>
          </Upload>
          <!-- endupload -->
          <div class="demo-upload-list" v-if="data.iconImage">
            <img :src="`${data.iconImage}`" />
            <div class="demo-upload-list-cover">
              <Icon type="ios-trash-outline" @click="deleteImage"></Icon>
            </div>
          </div>

          <div slot="footer">
            <Button type="default" @click="addModal=false">Close</Button>
            <Button
              type="primary"
              @click="addCategory"
              :disabled="isAdding"
              :loading="isAdding"
            >{{isAdding ? 'Adding...' : 'Add Category'}}</Button>
          </div>
        </Modal>

        <!-- tag editing modal -->
        <Modal v-model="editModal" title="Edit Category" :mask-closable="false" :closable="false">
          <Input v-model="editData.categoryName" placeholder="Edit Category name" />

          <div style="margin-top:10px; margin-bottom: 10px"></div>

          <!-- upload -->
          <Upload
            v-show="isIconImageNew"
            ref="editDataUploads"
            type="drag"
            action="/app/upload"
            :headers="{'x-csrf-token': token, 'X-Requested-With': 'XMLHttpRequest'}"
            :on-success="handleSuccess"
            :format="['jpg','jpeg','png']"
            :max-size="2048"
            :on-format-error="handleFormatError"
            :on-error="handleError"
            :on-exceeded-size="handleMaxSize"
          >
            <div style="padding: 20px 0">
              <Icon type="ios-cloud-upload" size="52" style="color: #3399ff"></Icon>
              <p>Click or drag files here to upload</p>
            </div>
          </Upload>
          <!-- endupload -->
          <div class="demo-upload-list" v-if="editData.iconImage">
            <img :src="`${editData.iconImage}`" />
            <div class="demo-upload-list-cover">
              <Icon type="ios-trash-outline" @click="deleteImage(false)"></Icon>
            </div>
          </div>

          <div slot="footer">
            <Button type="default" @click="closeEditModal">Close</Button>
            <Button
              type="primary"
              @click="editCategory"
              :disabled="isAdding"
              :loading="isAdding"
            >{{isAdding ? 'Editing...' : 'Edit Category'}}</Button>
          </div>
        </Modal>
        <!-- Delete Alert Modal  -->

        <deleteModal></deleteModal>
      </div>
    </div>
  </div>
</template>

<script>
import deleteModal from "./components/deleteModal";
import { mapGetters } from "vuex";
export default {
  data() {
    return {
      data: {
        iconImage: "",
        categoryName: ""
      },
      addModal: false,
      editModal: false,
      isAdding: false,
      categoryLists: [],
      editData: {
        iconImage: "",
        categoryName: ""
      },
      index: -1,
      showDeleteModal: false,
      isDeleting: false,
      deleteItem: {},
      deleteIndex: -1,
      token: "",
      isIconImageNew: false,
      isEditingItem: false
    };
  },
  methods: {
    async addCategory() {
      this.isAdding = true;
      if (this.data.categoryName.trim() == "") {
        this.isAdding = false;
        return this.error("Category name is required");
      }
      if (this.data.iconImage.trim() == "") {
        this.isAdding = false;
        return this.error("Icon image is required");
      }
      this.data.iconImage = `${this.data.iconImage}`;
      const res = await this.callApi("post", "/app/create_category", this.data);
      if (res.status == 201) {
        this.categoryLists.unshift(res.data); // category[]에 역순으로 삽입
        this.success("Category has been added successfully");
        this.addModal = false;
        this.data.categoryName = ""; // 이후 categoryName 초기화
        this.data.iconImage = "";
      } else {
        if (res.status == 422) {
          if (res.data.errors.categoryName) {
            this.error(res.data.errors.categoryName[0]);
          }
          if (res.data.errors.iconImage) {
            this.error(res.data.errors.iconImage[0]);
          }
        }
        this.swr();
      }
      this.isAdding = false;
    },

    async editCategory() {
      if (this.editData.categoryName.trim() == "")
        return this.error("Category name is required");
      if (this.editData.iconImage.trim() == "")
        return this.error("Icon image is required");

      const res = await this.callApi(
        "post",
        "/app/edit_category",
        this.editData
      );
      if (res.status == 200) {
        this.categoryLists[
          this.index
        ].categoryName = this.editData.categoryName;
        this.success("Category has been edited successfully");
        this.editModal = false;
      } else {
        if (res.status == 422) {
          if (res.data.errors.categoryName) {
            this.error(res.data.errors.categoryName[0]);
          }
          if (res.data.errors.iconImage) {
            this.error(res.data.errors.iconImage[0]);
          }
        } else {
          this.swr();
        }
      }
    },

    showEditModal(category, index) {
      //   let obj = {
      //     id: category.id,
      //     categoryName: category.categoryName
      //   };
      this.editData = category;
      this.editModal = true;
      this.index = index;
      this.isEditingItem = true;
    },

    showDeletingModal(category, index) {
      const deleteModalObj = {
        showDeleteModal: true,
        deleteUrl: "/app/delete_category",
        data: category,
        deleteIndex: index,
        isDeleted: false
      };
      this.$store.commit("setDeleteModalObj", deleteModalObj);
    },
    handleSuccess(res, file) {
      res = `/uploads/${res}`;
      if (this.isEditingItem) {
        return (this.editData.iconImage = res);
      }
      this.data.iconImage = res;
    },
    handleError(res, file) {
      this.$Notice.warning({
        title: "The file format is incorrect",
        desc: `${
          file.errors.file.length ? file.errors.file[0] : "Something went wrong"
        }`
      });
    },
    handleFormatError(file) {
      this.$Notice.warning({
        title: "The file format is incorrect",
        desc:
          "File format of " +
          file.name +
          " is incorrect, please select jpg or png."
      });
    },
    handleMaxSize(file) {
      this.$Notice.warning({
        title: "Exceeding file size limit",
        desc: "File  " + file.name + " is too large, no more than 2M."
      });
    },

    async deleteImage(isAdd = true) {
      // for editing
      let image;
      if (!isAdd) {
        this.isIconImageNew = true;
        let image = this.editData.iconImage;
        this.editData.iconImage = "";
        this.$refs.editDataUploads.clearFiles();
      } else {
        image = this.data.iconImage;
        this.data.iconImage = "";
        this.$refs.uploads.clearFiles();
      }
      const res = await this.callApi("post", "/app/delete_image", {
        imageName: image
      });
      if (res.status != 200) {
        this.data.iconImage = image;
        this.swr();
      }
      console.log(this.data.iconImage);
    },
    closeEditModal() {
      this.isEditingItem = false;
      this.editModal = false;
    }
  },

  async created() {
    this.token = window.Laravel.csrfToken;
    const res = await this.callApi("get", "/app/get_category");
    if ((res.status = 200)) {
      this.categoryLists = res.data;
    } else {
      this.swr();
    }
  },
  components: {
    deleteModal
  },

  computed: {
    ...mapGetters(["getDeleteModalObj"])
  },
  watch: {
    getDeleteModalObj(obj) {
      console.log(obj);

      if (obj.isDeleted) {
        this.categoryLists.splice(obj.deleteIndex, 1);
      }
    }
  }

  // async created() {
  //   const res = await this.callApi("POST", "/app/create_tag", {
  //     categoryName: "testtag"
  //   });
  //   if (res.status == 200) {
  //     //   console.log(res);
  //   } else {
  //     console.log(res);
  //     console.log("running");
  //   }
  // }
};
</script>

<style>
</style>

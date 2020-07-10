<template>
  <div>
    <div class="content">
      <div class="container-fluid">
        <!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
        <div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">
          <p class="_title0">
            Tags
            <Button @click="addModal=true">
              <Icon type="md-add" />Add admin
            </Button>
          </p>

          <div class="_overflow _table_div">
            <table class="_table">
              <!-- TABLE TITLE -->
              <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>User type</th>
                <th>Created at</th>
                <th>Action</th>
              </tr>
              <!-- TABLE TITLE -->

              <!-- ITEMS -->
              <tr v-for="(user, i) in users" :key="i" v-if="users.length">
                <td>{{user.id}}</td>
                <td class="_table_name">{{ user.fullName }}</td>
                <td class>{{ user.email }}</td>
                <td class>{{ user.userType }}</td>
                <td>{{ user.created_at }}</td>
                <td>
                  <Button
                    class="_btn _action_btn edit_btn1"
                    type="info"
                    size="small"
                    @click="showEditModal(user, i)"
                  >Edit</Button>
                  <Button
                    class="_btn _action_btn make_btn1"
                    type="error"
                    size="small"
                    @click="showDeletingModal(user, i)"
                    :loading="user.isDeleting"
                  >Delete</Button>
                </td>
              </tr>
              <!-- ITEMS -->
            </table>
          </div>
        </div>

        <!-- tag adding modal -->
        <Modal v-model="addModal" title="Add admin" :mask-closable="false" :closable="false">
          <div class="space">
            <Input type="text" v-model="data.fullName" placeholder="Full name" />
          </div>
          <div class="space">
            <Input type="email" v-model="data.email" placeholder="email" />
          </div>
          <div class="space">
            <Input type="password" v-model="data.password" placeholder="Password" />
          </div>
          <div class="space">
            <Select v-model="data.userType" placeholder="Select admin type">
              <Option value="Admin">Admin</Option>
              <Option value="Editor">Editor</Option>
            </Select>
          </div>
          <div slot="footer">
            <Button type="default" @click="addModal=false">Close</Button>
            <Button
              type="primary"
              @click="addAdmin"
              :disabled="isAdding"
              :loading="isAdding"
            >{{isAdding ? 'Adding...' : 'Add admin'}}</Button>
          </div>
        </Modal>

        <!-- tag editing modal -->
        <Modal v-model="editModal" title="Edit Admin" :mask-closable="false" :closable="false">
          <div class="space">
            <Input type="text" v-model="editData.fullName" placeholder="Full name" />
          </div>
          <div class="space">
            <Input type="email" v-model="editData.email" placeholder="email" />
          </div>
          <div class="space">
            <Input type="password" v-model="editData.password" placeholder="Password" />
          </div>
          <div class="space">
            <Select v-model="editData.userType" placeholder="Select admin type">
              <Option value="Admin">Admin</Option>
              <Option value="Editor">Editor</Option>
            </Select>
          </div>

          <!-- <Input v-model="editData.tagName" placeholder="Edit tag name" /> -->

          <div slot="footer">
            <Button type="default" @click="editModal=false">Close</Button>
            <Button
              type="primary"
              @click="editAdmin"
              :disabled="isAdding"
              :loading="isAdding"
            >{{isAdding ? 'Editing...' : 'Edit Admin'}}</Button>
          </div>
        </Modal>
        <!-- Delete Alert Modal  -->
        <!--
        <Modal v-model="showDeleteModal" width="360">
          <p slot="header" style="color:#f60;text-align:center">
            <Icon type="ios-information-circle"></Icon>
            <span>Delete confirmation</span>
          </p>
          <div style="text-align:center">
            <p>Are you sure you want to delete this tag?</p>
          </div>
          <div slot="footer">
            <Button
              type="error"
              size="large"
              long
              :loading="isDeleting"
              :disabled="isDeleting"
              @click="deleteTag"
            >Delete</Button>
          </div>
        </Modal>-->
        <deleteModal></deleteModal>
        <!--  -->
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
        fullName: "",
        email: "",
        password: "",
        userType: ""
      },
      addModal: false,
      editModal: false,
      isAdding: false,
      users: [],
      editData: {
        fullName: "",
        email: "",
        password: "",
        userType: ""
      },
      index: -1,
      showDeleteModal: false,
      isDeleting: false,
      deleteItem: {},
      deleteIndex: -1
    };
  },
  methods: {
    async addAdmin() {
      if (this.data.fullName.trim() == "")
        return this.error("Full name is required");
      if (this.data.email.trim() == "") return this.error("Email is required");
      if (this.data.password.trim() == "")
        return this.error("Password is required");
      if (this.data.fullName.trim() == "")
        return this.error("User type is required");

      const res = await this.callApi("post", "/app/create_user", this.data);
      if (res.status == 201) {
        this.tags.unshift(res.data); // tags[]에 역순으로 삽입
        this.success("Admin has been added successfully");
        this.addModal = false;
        this.data.tagName = ""; // 이후 tagName 초기화
      } else {
        if (res.status == 422) {
          for (let i in res.data.errors) {
            this.error(res.data.errors[i]);
          }
        }
        this.swr();
      }
    },

    async editAdmin() {
      if (this.editData.fullName.trim() == "")
        return this.error("Full name is required");
      if (this.editData.email.trim() == "")
        return this.error("Email is required");
      if (this.editData.fullName.trim() == "")
        return this.error("User type is required");
      const res = await this.callApi("post", "/app/edit_user", this.editData);
      if (res.status == 200) {
        this.users[this.index] = this.editData;
        // this.users[this.index].fullName = this.editData.fullName;
        // this.users[this.index].email = this.editData.email;
        // this.users[this.index].userType = this.editData.userType;
        this.success("User has been edited successfully");
        this.editModal = false;
      } else {
        if (res.status == 422) {
          for (let i in res.data.errors) {
            this.error(res.data.errors[i]);
          }
        } else {
          this.swr();
        }
      }
    },

    showEditModal(user, index) {
      let obj = {
        id: user.id,
        fullName: user.fullName,
        email: user.email,
        password: user.password,
        userType: user.userType
      };
      this.editData = obj;
      this.editModal = true;
      this.index = index;
    },

    showDeletingModal(tag, index) {
      const deleteModalObj = {
        showDeleteModal: true,
        deleteUrl: "/app/delete_tags",
        data: tag,
        deleteIndex: index,
        isDeleted: false
      };
      this.$store.commit("setDeleteModalObj", deleteModalObj);
    }
  },

  async created() {
    const res = await this.callApi("get", "/app/get_users");
    if ((res.status = 200)) {
      this.users = res.data;
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
        this.tags.splice(obj.deleteIndex - 1, 1);
      }
    }
  }
  // async created() {
  //   const res = await this.callApi("POST", "/app/create_tag", {
  //     tagName: "testtag"
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

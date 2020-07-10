<template>
  <div>
    <div class="content">
      <div class="container-fluid">
        <!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
        <div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">
          <p class="_title0">
            Role management
            <Button @click="addModal=true">
              <Icon type="md-add" />Add a new role
            </Button>
          </p>

          <div class="_overflow _table_div">
            <table class="_table">
              <!-- TABLE TITLE -->
              <tr>
                <th>ID</th>
                <th>Role type</th>
                <th>Created at</th>
                <th>Action</th>
              </tr>
              <!-- TABLE TITLE -->

              <!-- ITEMS -->
              <tr v-for="(role, i) in roles" :key="i" v-if="roles.length">
                <td>{{role.id}}</td>
                <td class="_table_name">{{ role.roleName }}</td>
                <td>{{ role.created_at }}</td>
                <td>
                  <Button
                    class="_btn _action_btn edit_btn1"
                    type="info"
                    size="small"
                    @click="showEditModal(role, i)"
                  >Edit</Button>
                  <Button
                    class="_btn _action_btn make_btn1"
                    type="error"
                    size="small"
                    @click="showDeletingModal(role, i)"
                    :loading="role.isDeleting"
                  >Delete</Button>
                </td>
              </tr>
              <!-- ITEMS -->
            </table>
          </div>
        </div>

        <!-- tag adding modal -->
        <Modal v-model="addModal" title="Add Role" :mask-closable="false" :closable="false">
          <Input v-model="data.roleName" placeholder="Add role name" />

          <div slot="footer">
            <Button type="default" @click="addModal=false">Close</Button>
            <Button
              type="primary"
              @click="add"
              :disabled="isAdding"
              :loading="isAdding"
            >{{isAdding ? 'Adding...' : 'Add role'}}</Button>
          </div>
        </Modal>

        <!-- tag editing modal -->
        <Modal v-model="editModal" title="Edit Role" :mask-closable="false" :closable="false">
          <Input v-model="editData.roleName" placeholder="Edit role name" />

          <div slot="footer">
            <Button type="default" @click="editModal=false">Close</Button>
            <Button
              type="primary"
              @click="editRole"
              :disabled="isAdding"
              :loading="isAdding"
            >{{isAdding ? 'Editing...' : 'Edit role'}}</Button>
          </div>
        </Modal>

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
        roleName: ""
      },
      addModal: false,
      editModal: false,
      isAdding: false,
      roles: [],
      editData: {
        roleName: ""
      },
      index: -1,
      showDeleteModal: false,
      isDeleting: false,
      deleteItem: {},
      deleteIndex: -1
    };
  },
  methods: {
    async add() {
      if (this.data.roleName.trim() == "")
        return this.error("Role name is required");
      const res = await this.callApi("post", "/app/create_role", this.data);
      if (res.status == 201) {
        this.roles.unshift(res.data); // roles[]에 역순으로 삽입
        console.log(res.data);
        this.success("Role has been added successfully");
        this.addModal = false;
        this.data.roleName = ""; // 이후 roleName 초기화
      } else {
        if (res.status == 422) {
          if (res.data.errors.roleName) {
            this.info(res.data.errors.roleName[0]);
          }
        }
        this.swr();
      }
    },

    async editRole() {
      if (this.editData.roleName.trim() == "")
        return this.error("Role name is required");
      const res = await this.callApi("post", "/app/edit_roles", this.editData);
      if (res.status == 200) {
        this.roles[this.index].roleName = this.editData.roleName;
        this.success("Role has been edited successfully");
        this.editModal = false;
      } else {
        if (res.status == 422) {
          if (res.data.errors.roleName) {
            this.info(res.data.errors.roleName[0]);
          }
        } else {
          this.swr();
        }
      }
    },

    showEditModal(role, index) {
      let obj = {
        id: role.id,
        roleName: role.roleName
      };
      this.editData = obj;
      this.editModal = true;
      this.index = index;
    },

    showDeletingModal(role, index) {
      const deleteModalObj = {
        showDeleteModal: true,
        deleteUrl: "/app/delete_roles",
        data: role,
        deleteIndex: index,
        isDeleted: false
      };
      this.$store.commit("setDeleteModalObj", deleteModalObj);
    }
  },

  async created() {
    const res = await this.callApi("get", "/app/get_roles");
    if ((res.status = 200)) {
      this.roles = res.data;
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
        this.roles.splice(obj.deleteIndex - 1, 1);
      }
    }
  }
};
</script>

<style>
</style>

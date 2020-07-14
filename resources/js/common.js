// mixin에 사용할 코드들, app.js와 연결된 모든 곳에서 사용가능
import {
  mapGetters
} from 'vuex';
export default {
  data() {
    return {

    }
  },
  methods: {
    async callApi(method, url, dataObj) {

      try {
        return await axios({
          method: method,
          url: url,
          data: dataObj,
        });
      } catch (e) {
        return e.response;
      }

    },

    info(desc, title = "Hey") {
      this.$Notice.info({
        title: title,
        desc: desc
      });
    },
    success(desc, title = "Great") {
      this.$Notice.success({
        title: title,
        desc: desc
      });
    },
    warning(desc, title = "Oops!") {
      this.$Notice.warning({
        title: title,
        desc: desc
      });
    },
    error(desc, title = "Oops!") {
      this.$Notice.error({
        title: title,
        desc: desc
      });
    },
    swr(desc = 'Something went wrong', title = "Hey") {
      this.$Notice.error({
        title: title,
        desc: desc
      });
    },

    checkUserPermission(key) {
      if (!this.userPermission) return true;
      let isPermitted = false;
      for (let data of this.userPermission) {
        if (this.$route.name == data.name) {
          if (data[key]) {
            isPermitted = true;
            break;
          } else {
            break;
          }
        }
        console.log(data[key]);
      }
      // console.log(this.$route.name);
      return isPermitted;
    }

  },

  computed: {
    ...mapGetters({
      'userPermission': 'getUserPermission'
    }),
    isReadPermitted() {
      return this.checkUserPermission('read');
    },
    isWritePermitted() {
      return this.checkUserPermission('write');
    },
    isUpdatePermitted() {
      return this.checkUserPermission('update');
    },
    isDeletePermitted() {
      return this.checkUserPermission('delete');
    },

  },


}

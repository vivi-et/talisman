import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

export default new Vuex.Store({
  // 반드시 이름이 state 여야함
  state: {
    counter: 1000,
    deleteModalObj: {
      showDeleteModal: false,
      deleteUrl: '',
      data: null,
      deleteIndex: -1,
      isDeleted: false,
    },
    user: false,

  },
  getters: {
    getCounter(state) {
      return state.counter;
    },
    getDeleteModalObj(state) {
      return state.deleteModalObj;
    },


  },
  mutations: {
    changeTheCounter(state, data) {
      state.counter += data;

    },
    setDeleteModal(state, data) {
      const deleteModalObj = {
        showDeleteModal: false,
        deleteUrl: '',
        data: null,
        deleteIndex: -1,
        isDeleted: data,
      }
      state.deleteModalObj = deleteModalObj;
    },
    setDeleteModalObj(state, data) {
      state.deleteModalObj = data;
    },
    updateUser(state, data) {
      state.user = data;
    }

  },
  actions: {
    changeCounterAction({
      commit
    }, data) {
      commit('changeTheCounter', data);

    }
  }


});

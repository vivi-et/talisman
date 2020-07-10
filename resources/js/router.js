import Vue from 'vue';
import Router from 'vue-router';

//tutorial routes
import firstpage from './components/pages/myFirstVuePage';
import secondpage from './components/pages/mySecondVuePage';
import hooks from './components/pages/basic/hooks';
import methods from './components/pages/basic/methods';
import usecom from './vuex/usecom.vue';

//admin project routes
import home from './components/pages/home';
import tags from './admin/pages/tags'
import category from './admin/pages/category'
import adminusers from './admin/pages/adminusers.vue';

import login from './admin/pages/login';
import role from './admin/pages/role';

Vue.use(Router);

const routes = [
  // projects routes...
  {
    path: '/',
    component: home,

  },

  {
    path: '/tags',
    component: tags,
  },

  {
    path: '/category',
    component: category,
  },
  {
    path: '/testvuex',
    component: usecom,
  },
  {
    path: '/adminusers',
    component: adminusers,
  },

  {
    path: '/login',
    component: login,
  },

  {
    path: '/role',
    component: role,
  },
























  //basic tutorial routes...
  {
    path: '/myfirstpage',
    component: firstpage,

  },

  {
    path: '/mysecondpage',
    component: secondpage
  },

  //vue hooks

  {
    path: '/hooks',
    component: hooks
  },

  //more basics
  {
    path: '/methods',
    component: methods
  },


];

export default new Router({
  mode: 'history',
  routes
})

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
import assignRole from './admin/pages/assignRole';

Vue.use(Router);

const routes = [
  // projects routes...
  {
    path: '/',
    component: home,
    name: 'home'
  },

  {
    path: '/tags',
    component: tags,
    name: 'tags'
  },

  {
    path: '/category',
    component: category,
    name: 'category'
  },
  {
    path: '/testvuex',
    component: usecom,
  },
  {
    path: '/adminusers',
    component: adminusers,
    name: 'adminusers'
  },

  {
    path: '/login',
    component: login,
    name: 'login'
  },

  {
    path: '/role',
    component: role,
    name: 'role'
  },


  {
    path: '/assignRole',
    component: assignRole,
    name: 'assignRole'
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

// assets/js/app.js
import Vue from 'vue';

/**
* Create a fresh Vue Application instance
*/
new Vue({
  el: '#app',
  components: {
    ListFruit: () => import('./components/ListFruit.vue'),
    ListFavorite: () => import('./components/ListFavorite.vue'),
  }
});
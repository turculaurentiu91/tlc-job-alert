Vue.use(Vuex);

const store = new Vuex.Store({
  state: {
    activePage: ''
  },
  mutations: {},
});

const adminApp = new Vue({
  el: "#admin-app",
  store,
  data: {},
  computed: {},
  methods: {},
});
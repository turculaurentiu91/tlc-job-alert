Vue.use(Vuex);

const store = new Vuex.Store({
  state: {
    activePage: 'subscriptions',
  },
  getters: {
    activePage: state => state.activePage,
  },
  mutations: {
    setPage: (state, page) => state.activePage = page,
  },
});

Vue.component('tab-button', {
  template: '#tab-button-template',
  props: {
    pageSlug: {
      type: String,
      required: true,
    },
  },
  computed: {
    isActive() { return this.$store.state.activePage == this.pageSlug; },
  },
  methods: {
    ...Vuex.mapMutations([
      'setPage',
    ]),
  },
});

const adminApp = new Vue({
  el: "#admin-app",
  store,
  data: {},
  computed: {
    ...Vuex.mapGetters([
      'activePage',
    ]),
  },
  methods: {
    ...Vuex.mapMutations([
      'setPage'
    ]),
  },
});


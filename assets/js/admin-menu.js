Vue.use(Vuex);

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

const store = new Vuex.Store({
  state: {
    activePage: 'subscriptions',
    fetching: true,
    subscriptions: [],
  },
  getters: {
    activePage: state => state.activePage,
    fetching: state => state.fetching,
    subscriptions: state => state.subscriptions,
  },
  mutations: {
    setPage: (state, page) => state.activePage = page,
  },
});

const adminApp = new Vue({
  el: "#admin-app",
  store,
  data: {},
  computed: {
    ...Vuex.mapGetters([
      'activePage',
      'fetching',
      'subscriptions'
    ]),
  },
  methods: {
    ...Vuex.mapMutations([
      'setPage'
    ]),
  },
});


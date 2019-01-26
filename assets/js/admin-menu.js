
const tlcJobAlertAPI = axios.create({
  baseURL: `${homeUrl}/wp-json/tlc/job-alert/`,
})

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
    subsRequest: state => state.fetching = true,
    subsSuccess: (state, subs) => {
      state.fetching = false;
      state.subscriptions = subs;
    },
    subsFailure: (state, error) => {
      state.fetching = false;
      console.error(error);
    },
    subsDeleteSuccess: (state, subID) => {
      const filterSubsByID = sub => sub.id !== subID;
      state.subscriptions = state.subscriptions.filter(filterSubsByID);
      state.fetching = false;
    },
  },
  actions: {
    deleteSubscription({ commit }, idToDelete) {
      const subsDeleteSuccess = () => commit('subsDeleteSuccess', idToDelete);
      
      commit('subsRequest');
      tlcJobAlertAPI.delete(`/${idToDelete}`).then(subsDeleteSuccess);

    },
    loadSubscriptions({ commit }) {
      const subsSuccess = response => commit('subsSuccess', response.data);
      const subsFailure = error => commit('subsFailure', error);
      const errorType = error => (
        error.response 
          ? 'not_ok'  
          : error.request ? 'no_response' : 'bad_setup'
      );
      const commitFailure = error => subsFailure({type: errorType(error), message: 'Could not fetch the subscriptions'}); 

      tlcJobAlertAPI.get('/').then(subsSuccess).catch(commitFailure);
    },
  }
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
    ...Vuex.mapActions([
      'loadSubscriptions',
      'deleteSubscription'
    ]),
  },
  mounted() {
    this.loadSubscriptions();
  }
});


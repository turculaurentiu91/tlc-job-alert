
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
    activePage: subPage,
    fetching: true,
    subscriptions: [],
    subsFormContext: 'none',
    locations: [ ...locations ],
    disciplines: [ ...disciplines ],
    contractTypes: [ ...contractTypes ],
    subsFormData: {
      name: '',
      email: '',
      keywords: '',
      locations: [],
      disciplines: [],
      contractTypes: [],
      frequency: 'direct',
    },
  },
  getters: {
    activePage: state => state.activePage,
    fetching: state => state.fetching,
    subscriptions: state => state.subscriptions,
    subsFormContext: state => state.subsFormContext,
    subsFormData: state => state.subsFormData,
  },
  mutations: {
    openEditSusbForm: (state, sub) => {
      state.subsFormData = sub;
      state.subsFormContext = 'edit';
    },
    openAddSubsForm: (state) => {
      state.subsFormData = {
        name: '',
        email: '',
        keywords: '',
        locations: [],
        disciplines: [],
        contractTypes: [],
        frequency: 'direct',
      };
      state.subsFormContext = 'add';
    },

    subsAddSuccess: (state, sub) => {
      state.fetching = false;
      state.subscriptions = [ ...state.subscriptions, sub ];
    },
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
    subsEditSuccess: (state, sub) => {
      const mapSubs = s => s.id === sub.id ? sub : s; 
      state.subscriptions = state.subscriptions.map(mapSubs);
      state.fetching = false;
    },
    setSubsFormData: (state, { field, value }) => state.subsFormData[field] = value,
    setSubsFormContext: (state, context) => state.subsFormContext = context,
  },
  actions: {
    editSubscription({commit, state}) {
      const subsEditSuccess = res => commit('subsEditSuccess', res.data);

      commit('subsRequest');
      commit('setSubsFormContext', 'none');

      tlcJobAlertAPI.put(`/${state.subsFormData.id}`, state.subsFormData)
      .then(subsEditSuccess);
    },
    addSubscription({ commit, state }) {
      const subsAddSuccess = req => commit('subsAddSuccess', req.data);

      commit('subsRequest');
      commit('setSubsFormContext', 'none')
      tlcJobAlertAPI.post('/', state.subsFormData)
      .then(subsAddSuccess);
    },
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
      'subscriptions',
      'subsFormData',
      'subsFormContext'
    ]),
  },
  methods: {
    ...Vuex.mapMutations([
      'setPage',
      'setSubsFormData',
      'openAddSubsForm',
      'setSubsFormContext',
      'openEditSusbForm',
    ]),
    ...Vuex.mapActions([
      'loadSubscriptions',
      'deleteSubscription',
      'addSubscription',
      'editSubscription',
    ]),
    selectedOptions(event) {
      return [ ...event.target.selectedOptions ].map( option => option.value );
    },
    subsFormOnSubmit() {
      if (this.subsFormContext === 'add') {
        this.addSubscription();
      } else if (this.subsFormContext === 'edit') {
        this.editSubscription();
      } else { 
        return;
      }
    },
  },
  mounted() {
    this.loadSubscriptions();
  }
});


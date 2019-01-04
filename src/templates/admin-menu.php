<script src="https://cdn.jsdelivr.net/npm/vue@2.5.17/dist/vue.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/vuex/3.0.1/vuex.js"></script>

<div class="wrap" id="admin-app">
  <h2 class="nav-tab-wrapper">
    <tab-button page-slug="subscriptions">
      <?= __("Subscriptions", "tlc-job-alert") ?>
    </tab-button>
    <tab-button page-slug="email-template">
      <?= __("E-mail Templates", "tlc-job-alert") ?>
    </tab-button>
  </h2>

  <script type="text/x-template" id="tab-button-template">
    <a href="#" class="nav-tab" v-bind:class="{'nav-tab-active': isActive}"
      @click.prevent="setPage(pageSlug); $event.target.blur()"
      >
      <slot></slot>
    </a>
  </script>
</div>

<script src="<?= plugins_url('assets/js/admin-menu.js', TLC_JOB_ALERT_PLUGIN_DIR) ?>"></script>
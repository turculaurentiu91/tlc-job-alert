
<div class="wrap" id="admin-app">
  <h2 class="nav-tab-wrapper">
    <tab-button page-slug="subscriptions">
      <?= __("Subscriptions", "tlc-job-alert") ?>
    </tab-button>
    <tab-button page-slug="email-template">
      <?= __("E-mail Templates", "tlc-job-alert") ?>
    </tab-button>
  </h2>

  <div v-if="activePage == 'subscriptions'">
    <h3><?= __("Subscriptions","tlc-job-alert") ?></h3>
    <div class="rel-position">
      <div class="loader" v-if="fetching">
        <div class="loader__icon"></div>
      </div>
      <table class="widefat">
        <thead>
          <tr>
            <th><?= __("ID", "tlc-job-alert") ?></th>
            <th><?= __("Name", "tlc-job-alert") ?></th>
            <th><?= __("Email", "tlc-job-alert") ?></th>
            <th><?= __("Keywords", "tlc-job-alert") ?></th>
            <th><?= __("Locations", "tlc-job-alert") ?></th>
            <th><?= __("Contract Type", "tlc-job-alert") ?></th>
            <th><?= __("Discipline", "tlc-job-alert") ?></th>
            <th><?= __("Frequency", "tlc-job-alert") ?></th>
          </tr>
        </thead>

        <tbody>
        </tbody>
        
        <tfoot>
          <tr>
            <th><?= __("ID", "tlc-job-alert") ?></th>
            <th><?= __("Name", "tlc-job-alert") ?></th>
            <th><?= __("Email", "tlc-job-alert") ?></th>
            <th><?= __("Keywords", "tlc-job-alert") ?></th>
            <th><?= __("Locations", "tlc-job-alert") ?></th>
            <th><?= __("Contract Type", "tlc-job-alert") ?></th>
            <th><?= __("Discipline", "tlc-job-alert") ?></th>
            <th><?= __("Frequency", "tlc-job-alert") ?></th>
          </tr>
        </tfoot>
      </table>
    </div>
  </div>

  <script type="text/x-template" id="tab-button-template">
    <a href="#" class="nav-tab" v-bind:class="{'nav-tab-active': isActive}"
      @click.prevent="setPage(pageSlug); $event.target.blur()"
      >
      <slot></slot>
    </a>
  </script>
</div>
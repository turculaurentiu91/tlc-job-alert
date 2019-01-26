<script>
  const homeUrl = "<?= home_url() ?>";
</script>

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
            <th></th>
            <th></th>
          </tr>
        </thead>

        <tbody>
          <tr v-for="sub in subscriptions">
            <td>{{sub.id}}</td>
            <td>{{sub.name}}</td>
            <td>{{sub.email}}</td>
            <td>{{sub.keywords}}</td>
            <td>{{sub.ownJoblocations && sub.ownJoblocations[0].name}}</td>
            <td>{{sub.ownJobcontracttype && sub.ownJobcontracttype[0].name}}</td>
            <td>{{sub.ownJobdiscipline && sub.ownJobdiscipline[0].name}}</td>
            <td>{{sub.frequency}}</td>
            <td>
              <button class="btn btn--blue">
                <span class="dashicons dashicons-edit"></span>
              </button>
            </td>
            <td>
              <button class="btn btn--red" @click.prevent="deleteSubscription(sub.id)">
                <span class="dashicons dashicons-trash"></span>
              </button>
            </td>
          </tr>
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
            <th></th>
            <th></th>
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
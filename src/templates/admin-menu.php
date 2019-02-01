<?php
  // --DEFINE LOCATIONS
  $locTerms = get_terms(array('taxonomy' => 'job_listing_region', 'hide_empty' => false));
  $locations = array();
  if (!is_wp_error($locTerms)) {
    foreach($locTerms as $term) {
      $locations[] = $term->name;
    }
  }

  // --DEFINE DISCIPLINES
  $disciplineTerms = get_terms(array('taxonomy' => 'job_listing_category', 'hide_empty' => false));
  $disciplines = array();
  if (!is_wp_error($disciplineTerms)) {
    foreach($disciplineTerms as $term) {
      $disciplines[] = $term->name;
    }
  }

  // --DEFINE CONTRACT  TYPES
  $tyleTerms = get_terms(array('taxonomy' => 'job_listing_type', 'hide_empty' => false));
  $contractTypes = array();
  if (!is_wp_error($tyleTerms)) {
    foreach($tyleTerms as $term) {
      $contractTypes[] = $term->name;
    }
  } 
?>

<script>
  const homeUrl = '<?= home_url() ?>';
  const locations = JSON.parse('<?= json_encode($locations) ?>');
  const disciplines = JSON.parse('<?= json_encode($disciplines) ?>');
  const contractTypes = JSON.parse('<?= json_encode($contractTypes) ?>');
</script>

<div class="wrap" id="admin-app">
  <div 
    class="subs-form" 
    v-if="subsFormContext == 'add' || subsFormContext == 'edit'"
  >
    <div class="subs-form__content">
      <button class="subs-form__close btn" @click.prevent="setSubsFormContext('none')">
        <span class="dashicons dashicons-no"></span>
      </button>
      <h3 v-if="subsFormContext == 'add'">
      <?= __("Nieuwe Job Alert aanmaken", "tlc-job-alert") ?>
    </h3>
    <h3 v-if="subsFormContext == 'edit'">
      <?= __("Bewerk Job Alert met ID", "tlc-job-alert") ?> {{subsFormData.id}}
    </h3>
    <form action="#" @submit.prevent="subsFormOnSubmit">

      <table class="form-table">
        <tr>
          <td><label for="tlc-name"> <?= __("Naam", "tlc-job-alert") ?> </label></td>
          <td><input 
            type="text" 
            required
            id="tlc-name" 
            minLength="3" 
            :value="subsFormData.name"
            @input="setSubsFormData({field: 'name', value: $event.target.value})"
            class="regular-text"
          ></td>
        </tr>
        <tr>
          <td><label for="tlc-email"> <?= __("E-mailadres", "tlc-job-alert") ?> </label></td>
          <td><input 
            type="email" 
            required 
            name="tlc-email" 
            id="tlc-email" 
            :value="subsFormData.email"
            @input="setSubsFormData({field: 'email', value: $event.target.value})"
            class="regular-text"
          ></td>
        </tr>
        <tr>
          <td><label for="tlc-keyword"> <?= __("Trefwoord(en)", "tlc-job-alert") ?> </label></td>
          <td><input 
            type="text"  
            name="tlc-keyword" 
            id="tlc-keyword" 
            :value="subsFormData.keywords"
            @input="setSubsFormData({field: 'keywords', value: $event.target.value})"
            class="regular-text"  
          ></td>
        </tr>
        <tr v-if="$store.state.locations.length > 0">
          <td><label for="tlc-location"> <?= __("Vestiging(en)", "tlc-job-alert") ?> </label></td>
          <td><select 
            id="tlc-location" 
            class="regular-text"
            name="tlc-location[]" 
            multiple="multiple"
            @change="setSubsFormData({field: 'locations', value: selectedOptions($event)})"
          >
            <option 
              v-for="loc in $store.state.locations" 
              :value="loc"
              :selected="$store.state.subsFormData.locations.indexOf(loc) !== -1"
              >
              {{loc}}
            </option>
          </select></td>
        </tr>
        <tr v-if="$store.state.disciplines.length > 0" >
          <td><label for="tlc-discipline"> <?= __("Discipline(s)", "tlc-job-alert") ?> </label></td>
          <td><select            
            id="tlc-discipline" 
            class="regular-text"
            name="tlc-discipline[]" 
            multiple="multiple"
            @change="setSubsFormData({field: 'disciplines', value: selectedOptions($event)})"
          >
            <option 
              v-for="discipline in $store.state.disciplines" 
              :value="discipline"
              :selected="$store.state.subsFormData.disciplines.indexOf(discipline) !== -1"
              >
              {{discipline}}
            </option>
          </select></td>
        </tr>
        <tr v-if="$store.state.contractTypes.length > 0">
          <td><label for="tlc-contract-type"> <?= __("Contract Type", "tlc-job-alert") ?> </label></td>
          <td><select            
            id="tlc-contract-type" 
            class="regular-text"
            name="tlc-contract-type[]" 
            multiple="multiple"
            @change="setSubsFormData({field: 'contractTypes', value: selectedOptions($event)})"
          >
            <option 
              v-for="contractType in $store.state.contractTypes" 
              :value="contractType"
              :selected="$store.state.subsFormData.contractTypes.indexOf(contractType) !== -1"
              >
              {{contractType}}
            </option>
          </select></td>
        </tr>
        <tr>
          <td><label for="tlc-frequency">E-mail frequentie</label></td>
          <td><select 
            name="tlc-freuency" 
            id="tlc-frequency"
            class="regular-text"
            :value="$store.state.subsFormData.frequency"
            @change="setSubsFormData({field: 'frequency', value: $event.target.value})"
          >
            <option value="direct"><?= __("Direct","tlc-job-alert") ?></option>
            <option value="weekly"><?= __("Wekelijks","tlc-job-alert") ?></option>
            <option value="two-weeks"><?= __("Iedere twee weken","tlc-job-alert") ?></option>
          </select></td>
        </tr>
      </table>

      <input type="submit" value="<?= __('Toevoegen','tlc-job-alert') ?>" class="button-primary">
    </form>
    </div>
    
  </div>

  <h2 class="nav-tab-wrapper">
    <tab-button page-slug="subscriptions">
      <?= __("Job Alert Database", "tlc-job-alert") ?>
    </tab-button>
    <tab-button page-slug="email-template">
      <?= __("E-mail Templates", "tlc-job-alert") ?>
    </tab-button>
  </h2>

  <div v-if="activePage == 'subscriptions'">
    <h3><?= __("Job Alert Database","tlc-job-alert") ?></h3>
    <div class="rel-position">
      <div class="loader" v-if="fetching">
        <div class="loader__icon"></div>
      </div>
      <table class="widefat">
        <thead>
          <tr>
            <th><?= __("ID", "tlc-job-alert") ?></th>
            <th><?= __("Naam", "tlc-job-alert") ?></th>
            <th><?= __("E-mailadres", "tlc-job-alert") ?></th>
            <th><?= __("Trefwoord(en)", "tlc-job-alert") ?></th>
            <th><?= __("Vestiging(en)", "tlc-job-alert") ?></th>
            <th><?= __("Contract Type(s)", "tlc-job-alert") ?></th>
            <th><?= __("Discipline(s)", "tlc-job-alert") ?></th>
            <th><?= __("Frequenctie", "tlc-job-alert") ?></th>
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
            <td>{{sub.locations && sub.locations[0]}}</td>
            <td>{{sub.contractTypes && sub.contractTypes[0]}}</td>
            <td>{{sub.disciplines && sub.disciplines[0]}}</td>
            <td>{{sub.frequency}}</td>
            <td>
              <button class="btn btn--blue" @click.prevent="openEditSusbForm(sub)">
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
            <th><?= __("Naam", "tlc-job-alert") ?></th>
            <th><?= __("E-mailadres", "tlc-job-alert") ?></th>
            <th><?= __("Trefwoord(en)", "tlc-job-alert") ?></th>
            <th><?= __("Vestiging(en)", "tlc-job-alert") ?></th>
            <th><?= __("Contract Type(s)", "tlc-job-alert") ?></th>
            <th><?= __("Discipline(s)", "tlc-job-alert") ?></th>
            <th><?= __("Frequenctie", "tlc-job-alert") ?></th>
            <th></th>
            <th></th>
          </tr>
        </tfoot>
      </table>
    </div>
    <a class="button-primary margin-top-medium" href="#" @click.prevent="openAddSubsForm">
      <?= __("Toevoegen", "tlc-job-allert") ?>
    </a>
  </div>

  <script type="text/x-template" id="tab-button-template">
    <a href="#" class="nav-tab" v-bind:class="{'nav-tab-active': isActive}"
      @click.prevent="setPage(pageSlug); $event.target.blur()"
      >
      <slot></slot>
    </a>
  </script>
</div>
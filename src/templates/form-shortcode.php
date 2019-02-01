<?php

  use \RedBeanPHP\R as R;
  
  if (isset($_POST['tlc-name'])) {
    $job_watch = R::dispense('jobwatch');
    $job_watch->name = $_POST['tlc-name'];
    $job_watch->email = $_POST['tlc-email'];
    $job_watch->keywords = $_POST['tlc-keyword'];
    $job_watch->frequency = $_POST['tlc-frequency'];

    if (isset($_POST['tlc-location'])) {
      foreach ($_POST['tlc-location'] as $key => $value) {
       $location = R::dispense('joblocation');
       $location->name = $value;
       $job_watch->ownJoblocationList[] = $location; 
      }
    }

    if (isset($_POST['tlc-discipline'])) {
      foreach ($_POST['tlc-discipline'] as $key => $value) {
       $discipline = R::dispense('jobdiscipline');
       $discipline->name = $value;
       $job_watch->ownJobdisciplineList[] = $discipline; 
      }
    }

    if (isset($_POST['tlc-contract-type'])) {
      foreach ($_POST['tlc-contract-type'] as $key => $value) {
       $contracttype = R::dispense('jobcontracttype');
       $contracttype->name = $value;
       $job_watch->ownJobcontracttypeList[] = $contracttype; 
      }
    }

    R::store($job_watch);
    $GLOBALS['ev']->emit('new-subscription',array($job_watch));
    
    echo '<div class="tlc-job-alert-success">Bedankt voor je aanmelding. Bij een nieuwe vacature die voldoet aan je eisen krijg je een mailtje.</div>';
  }

  // --DEFINE LOCATIONS
  $locTerms = get_terms(array('taxonomy' => 'job_listing_region', 'hide_empty' => false));
  $locations = null;
  if (!is_wp_error($locTerms)) {
    foreach($locTerms as $term) {
      $locations[] = $term->name;
    }
  }

  // --DEFINE DISCIPLINES
  $disciplineTerms = get_terms(array('taxonomy' => 'job_listing_category', 'hide_empty' => false));
  $disciplines = null;
  if (!is_wp_error($disciplineTerms)) {
    foreach($disciplineTerms as $term) {
      $disciplines[] = $term->name;
    }
  }

  // --DEFINE CONTRACT  TYPES
  $tyleTerms = get_terms(array('taxonomy' => 'job_listing_type', 'hide_empty' => false));
  $contractTypes = null;
  if (!is_wp_error($tyleTerms)) {
    foreach($tyleTerms as $term) {
      $contractTypes[] = $term->name;
    }
  }
?>

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>

<style>
    .tlc-job-alert-success {
      background-color: #ee0a00;
		color: #ffffff;
		border-radius: 4px;
      padding: 20px 40px;
      margin-bottom: 10px;
    }
  </style>

<h2>
  <?= __("Nieuwe Job Alert aanmaken","tlc-job-alert") ?>
</h2>

<p>
  <?= __("Bepaal hieronder aan welke eisen een vacature moet voldoen waar je een melding van wil krijgen.", "tlc-job-alert") ?>
</p>
<form action="#" method="POST">
  <div>
    <label for="tlc-name"> <?= __("Naam", "tlc-job-alert") ?> </label>
    <input type="text" required minLength="3" name="tlc-name" id="tlc-name">
  </div>

  <div>
    <label for="tlc-email"> <?= __("E-mailadres", "tlc-job-alert") ?> </label>
    <input type="email" required name="tlc-email" id="tlc-email">
  </div>

  <div>
    <label for="tlc-keyword"> <?= __("Trefwoord(en)", "tlc-job-alert") ?> </label>
    <input type="text" name="tlc-keyword" id="tlc-keyword">
  </div>

  <div>
    <label for="tlc-location"> <?= __("Vestiging(en)", "tlc-job-alert") ?> </label>
    <select id="tlc-location" name="tlc-location[]" multiple="multiple" class="tlc-select" style="width: 100%"
    <?= $locations ? "" : "disabled" ?> >
      <?php foreach($locations as $loc): ?>
      <option value="<?= $loc ?>" > <?= $loc ?> </option>
      <?php endforeach; ?>
    </select>
  </div>

  <div>
    <label for="tlc-discipline"> <?= __("Discipline(s)", "tlc-job-alert") ?> </label>
    <select id="tlc-discipline" name="tlc-discipline[]" multiple="multiple" class="tlc-select" style="width: 100%"
    <?= $disciplines ? "" : "disabled" ?> >
      <?php foreach($disciplines as $discipline): ?>
      <option value="<?= $discipline ?>" > <?= $discipline ?> </option>
      <?php endforeach; ?>
    </select>
  </div>

  <div>
    <label for="tlc-contract-type"> <?= __("Contract Type", "tlc-job-alert") ?> </label>
    <select id="tlc-contract-type" name="tlc-contract-type[]" multiple="multiple" class="tlc-select" style="width: 100%"
    <?= $contractTypes ? "" : "disabled" ?> >
      <?php foreach($contractTypes as $contractType): ?>
      <option value="<?= $contractType ?>" > <?= $contractType ?> </option>
      <?php endforeach; ?>
    </select>
  </div>

  <div>
    <label for="tlc-frequency"> <?= __("Mail frequentie", "tlc-job-alert") ?> </label>
    <select name="tlc-frequency" id="tlc-frequency" class="tlc-select" style="width: 100%">
      <option value="direct" selected><?= __("Direct","tlc-job-alert") ?></option>
      <option value="weekly"><?= __("Wekelijks","tlc-job-alert") ?></option>
      <option value="two-weeks"><?= __("Iedere twee weken","tlc-job-alert") ?></option>
    </select>
  </div>
<br />
  <div>
    <input type="checkbox" name="tlc-terms" id="tlc-terms" required>
    <?= __("Ik heb de", "tlc-job-alert") ?>
    <a href="/privacy-policy/" target="_blank"><?= __("Privacy Policy","tlc-job-alert") ?></a>&nbsp;
    <?= __("gelezen en ga ermee akkoord.", "tlc-job-alert") ?>
  </div>

  <button type="submit" class="reuseButton___NzKpQ"><?= __("Aanmaken","tlc-job-alert") ?></button>
</form>

<script>
jQuery(document).ready(function() {
    jQuery('.tlc-select').select2();
});
</script>
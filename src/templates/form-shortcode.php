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

<h2>
  <?= __("Job Alert Subscription Form","tlc-job-alert") ?>
</h2>

<p>
  <?= __("Form alert description", "tlc-job-alert") ?>
</p>

<p> <?= __("All form fields are required","tlc-job-alert") ?> </p>
<form action="#" method="POST">
  <div>
    <label for="tlc-name"> <?= __("Name", "tlc-job-alert") ?> </label>
    <input type="text" required minLength="3" name="tlc-name" id="tlc-name" placeholder="<?= __('Name','tlc-job-alert') ?>" >
  </div>

  <div>
    <label for="tlc-email"> <?= __("E-mail Address", "tlc-job-alert") ?> </label>
    <input type="email" required name="tlc-email" id="tlc-email" placeholder="<?= __('E-mail Address','tlc-job-alert') ?>" >
  </div>

  <div>
    <label for="tlc-keyword"> <?= __("Keyword", "tlc-job-alert") ?> </label>
    <input type="text" name="tlc-keyword" id="tlc-keyword" placeholder="<?= __('Keyword','tlc-job-alert') ?>" >
  </div>

  <div>
    <label for="tlc-location"> <?= __("Location", "tlc-job-alert") ?> </label>
    <select id="tlc-location" name="tlc-location[]" multiple="multiple" class="tlc-select" style="width: 100%"
    <?= $locations ? "" : "disabled" ?> >
      <?php foreach($locations as $loc): ?>
      <option value="<?= $loc ?>" > <?= $loc ?> </option>
      <?php endforeach; ?>
    </select>
  </div>

  <div>
    <label for="tlc-discipline"> <?= __("Disciplines", "tlc-job-alert") ?> </label>
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
    <label for="tlc-frequency"></label>
    <select name="tlc-frequency" id="tlc-frequency" class="tlc-select" style="width: 100%">
      <option value="direct" selected><?= __("Direct","tlc-job-alert") ?></option>
      <option value="weekly"><?= __("Weekly","tlc-job-alert") ?></option>
      <option value="two-weeks"><?= __("Each two weeks","tlc-job-alert") ?></option>
    </select>
  </div>

  <div>
    <input type="checkbox" name="tlc-terms" id="tlc-terms" required>
    <?= __("I have read and agree with", "tlc-job-alert") ?>&nbsp;
    <a href="#"> <?= __("terms and conditions","tlc-job-alert") ?> </a>
  </div>

  <button type="submit"><?= __("Subscribe","tlc-job-alert") ?></button>
</form>

<script>
jQuery(document).ready(function() {
    jQuery('.tlc-select').select2();
});
</script>
<h2>Nieuwe vacatures gevonden</h2>

<p>Er is een nieuwe vacature gevonden die aan je eisen voldoet.</p>

<ul>
  <?php foreach($jobID as $id) : ?>
    <li>
      <a href="<?= get_permalink($id) ?>">
        <?= get_the_title($id) ?>
      </a>
    </li>
  <?php endforeach; ?>
<ul>

<p>Ik wil deze mails niet meer ontvangen - <a href="<?= $unsubscribe_link ?>">uitschrijven</a>.</p>
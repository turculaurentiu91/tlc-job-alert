<h2>Nieuwe vacature gevonden</h2>

<p>Er is een nieuwe vacature gevonden die aan je eisen voldoet.</p>
<a href="<?= get_permalink($jobID) ?>">Ga naar de vacature</a>

<p><?php 
  setup_postdata($jobID);
  echo get_the_excerpt($jobID);
?></p>

<p>Ik wil deze mails niet meer ontvangen - <a href="<?= get_permalink($jobID) ?>">uitschrijven</a>.</p>
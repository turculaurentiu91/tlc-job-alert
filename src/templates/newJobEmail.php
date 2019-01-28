<h2>There is a new job that meets your filters</h2>

<a href="<?= get_permalink($jobID) ?>">Go to job listing </a>

<p><?php 
  setup_postdata($jobID);
  echo get_the_excerpt($jobID);
?></p>

<p>If you don't want to recieve those emails anymore, click this
<a href="<?= get_permalink($jobID) ?>">unsubscribe</a> link.

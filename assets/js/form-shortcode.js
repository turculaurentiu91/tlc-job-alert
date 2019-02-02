jQuery(document).ready(function() {
  jQuery('.tlc-select').select2();
});

document.querySelector("#tlc-job-alert-form")
.addEventListener('submit', e => {
  e.preventDefault();
  const body = {
    name: document.querySelector('#tlc-name').value,
    email: document.querySelector('#tlc-email').value,
    keywords: document.querySelector('#tlc-keyword').value,
    frequency: document.querySelector('#tlc-frequency').value,
    locations: jQuery('#tlc-location').val(),
    disciplines: jQuery('#tlc-discipline').val(),
    contractTypes: jQuery('#tlc-contract-type').val(),
  }
  const btn = document.querySelector('#tlc-job-alert-form-btn');
  btn.setAttribute('disabled', '');
  btn.innerHTML = 'Creëren';

  axios.post(`${homeUrl}/wp-json/tlc/job-alert/`, body)
  .then(() => {
    document.querySelector('#tlc-job-alert-success-post').style.display = 'block';
    btn.removeAttribute('disabled');
    btn.innerHTML = 'Aanmaken';
  })
  .catch(e => {
    btn.removeAttribute('disabled');
    btn.innerHTML = 'Aanmaken';
    console.error(e)
  });
});
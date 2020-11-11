<script>
grecaptcha.ready(function() {
grecaptcha.execute('<?php CLE_reCAPTCHA_site ?>', {action: 'homepage'})
.then(function(token) {
// Verify the token on the server.
});
});
</script>

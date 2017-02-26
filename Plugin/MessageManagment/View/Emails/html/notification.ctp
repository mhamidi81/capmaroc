<p>Bonjour,</p>

<p>vous avez reçu un message dans votre boite au lettre du messagerie de l'application <?php  echo Configure::read('Site.title'); ?></p>

<?php
if (empty($url)):
	$url = Router::url(array(
		'plugin' => 'request_managment',
		'controller' => 'requests',
		'action' => 'index'
	), true);
endif;
?>

Cliquer<a href = "<?php echo $url; ?>"> ici </a>pour accéder à votre compte.</p>
<br/><br/>
<p>Merci.</p>
<p>--------------------------------------------------<br/>
Administrateur du portail "<?php  echo Configure::read('Site.title'); ?>"</p>
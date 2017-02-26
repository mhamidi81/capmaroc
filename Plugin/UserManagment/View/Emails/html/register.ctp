<p><?php echo __d('croogo', 'Bonjour %s', $user['User']['last_name']); ?></p>
<p>L'administrateur du portail "<?php  echo Configure::read('Site.title'); ?>" vous a crée un compte utilisateur.  <br><br><br>
Ci-dessous vous informationa d'access : <br><br>
<strong> Login : </strong> <?php echo $user['User']['username']; ?><br>
<strong> Mot de passe : </strong> <?php echo $user['User']['password']; ?><br><br><br>


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
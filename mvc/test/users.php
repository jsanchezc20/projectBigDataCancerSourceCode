<?php
	require_once '../mvc/config/config.php';
	require_once '../mvc/libraries/DB.php';
	require_once '../mvc/model/UserModel.php';
	require_once '../mvc/templates/Template.php';


	#COMPROBACIÓN insert()
	#/*USUARIO 1
	echo "<h2>insert()</h2>";

	$objUser = new UserModel();
	$objUser->userName = 'robert';
	$objUser->password = password_hash('123456789', PASSWORD_BCRYPT);
	$objUser->accessId = 1;

	//Utils::checkVariable($objUser);

	echo $objUser->insert() ? 'OK' : 'KO';
	#*/

	/*USUARIO 2
	echo "<h2>insert()</h2>";

	$objUser = new Usuario();
	$objUser->user='jimmy';
	$objUser->pass=md5('987654321');
	$objUser->name='Jaime';
	$objUser->priv=500;
	$objUser->admin = 0;
	$objUser->email='jaime.sc@gmail.com';

	echo $objUser->insert()?'OK':'KO';
	#*/

	/*COMPROBACIÓN identify()
	echo "<h2>identify()</h2>";
	echo "<pre>";
	var_dump(Usuario::identify('admin',md5('1234')));
	var_dump(Usuario::identify('pepe',md5('1234')));
	var_dump(Usuario::identify('robert',md5('1234')));
	echo "<pre>";
	#*/

	/*COMPROBACIÓN getUser() y update()
	echo "<h2>getUser()</h2>";
	$objUser = Usuario::getUser(1);
	echo "<pre>";
	echo "ANTES:<br>".var_dump($objUser);
	echo "</pre>";

	$objUser->sur1='Sallent';
	$objUser->priv=5;
	$objUser->admin=1;

	echo $objUser->update()?'OK':'KO';

	echo "<pre>";
	echo "DESPUÉS:<br>".var_dump($objUser);
	echo "</pre>";
	#*/

	/*COMPROBACIÓN delete()
	echo "<h2>delete()</h2>";
	$objUser = Usuario::getUser(1);
	echo Usuario::delete($objUser->id)?'OK':'KO';
	#*/
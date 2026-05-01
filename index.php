<?php
/**
 * Index - Página principal do Parque Ecológico
 * 
 * Este arquivo renderiza a página home quando acessado em localhost/parque_ecologico
 */

require_once 'app/core/View.php';
require_once 'app/core/ConnectionManager.php';

View::render('home');

?>

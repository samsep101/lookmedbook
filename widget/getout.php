<?php
die('1222');
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$domain_td = "http://wwwapteka.info/";

if (!empty($_GET['g'])) {
echo file_get_contents($domain_td. '/output/index/' . $_GET['g']);
}

?>

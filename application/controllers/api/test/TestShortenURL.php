<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * @author Kamyar
 */

?>

<!DOCTYPE HTML>
<html lang="en">
    <head>
        <meta charset="utf-8"
        <title>TestShortenURL</title>
    </head>
<body>

<h1>REST API: /api/home/ShortenURL</h1>
<ul>
    <li><b>URL:</b> /api/home/shortenURL</li>
    <li><b>Method:</b> GET</li>
</ul>

<form method="get" action="/api/home/ShortenURL">
        
<table>
    <tr>
        <td>url (text)</td>
        <td><input style="width:500px;" type="edit" name="url" value=""></td>
    </tr>
<!--    <tr>
        <td>No parameters required.</td>
    </tr>-->
    <tr>
        <td></td>
        <td><input type="submit" value="Submit GET Method" ></td>
    </tr>
</table>
        
</form>

<!--<h4><b>Note: (...)</b></h4>
<ul>
    <li>0: ...</li>
    <li>1: ...</li>
    <li>Note: ...</li>
</ul>
<br>-->

</body>

</html>

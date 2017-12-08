<?php
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';

list($gather_count,$gather_tier,$crafting_count,$crafting_tier,$farming_count,$farming_tier,$player_count,$player_tier) = home_stats($mysqli);
/*echo 'Gathering<br><br>';
print_r($gather_count);
echo '<br>';
print_r($gather_tier);
echo '<br><br>Crafting<br><br>';
print_r($crafting_count);
echo '<br>';
print_r($crafting_tier);
echo '<br><br>Farming<br><br>';
print_r($farming_count);
echo '<br>';
print_r($farming_tier);
echo '<br><br>Player (Nilas)<br><br>';
print_r($player_count);
echo '<br>';
print_r($player_tier);
echo '<br>';*/
list($titles,$values) = format_js_chart($gather_count);


?>

<! DOCTYPE html>
<html>
    <head>
        <script type="text/javascript" src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
        <script type="text/JavaScript" src="js/forms.js"></script>
    </head>
    <body> 
        <script type="text/javascript">
        window.onload = function(){
            var titles = JSON.parse('<?= $titles; ?>');
            var values = JSON.parse('<?= $values; ?>');
            createPieChart('Gathering',titles,values);
            <?php list($titles,$values) = format_js_chart($gather_tier);?>
            titles = JSON.parse('<?= $titles; ?>');
            values = JSON.parse('<?= $values; ?>');
            createPieChart('Gathering Tiers',titles,values);
            <?php list($titles,$values) = format_js_chart($crafting_count);?>
            titles = JSON.parse('<?= $titles; ?>');
            values = JSON.parse('<?= $values; ?>');
            createPieChart('Crafting',titles,values);
            <?php list($titles,$values) = format_js_chart($crafting_tier);?>
            titles = JSON.parse('<?= $titles; ?>');
            values = JSON.parse('<?= $values; ?>');
            createPieChart('Crafting Tiers',titles,values);
            <?php list($titles,$values) = format_js_chart($farming_count);?>
            titles = JSON.parse('<?= $titles; ?>');
            values = JSON.parse('<?= $values; ?>');
            createPieChart('Farming',titles,values);
            <?php list($titles,$values) = format_js_chart($farming_tier);?>
            titles = JSON.parse('<?= $titles; ?>');
            values = JSON.parse('<?= $values; ?>');
            createPieChart('Farming Tiers',titles,values);
            <?php list($titles,$values) = format_js_chart($player_count);?>
            titles = JSON.parse('<?= $titles; ?>');
            values = JSON.parse('<?= $values; ?>');
            createPieChart('Nilas',titles,values);
            <?php list($titles,$values) = format_js_chart($player_tier);?>
            titles = JSON.parse('<?= $titles; ?>');
            values = JSON.parse('<?= $values; ?>');
            createPieChart('Nilas Tiers',titles,values);
        }
        </script>
        <div id="Gathering Chart Container" style="height: 300px; width: 100%;"></div>
        <div id="Gathering Tiers Chart Container" style="height: 300px; width: 100%;"></div>
        <div id="Crafting Chart Container" style="height: 300px; width: 100%;"></div>
        <div id="Crafting Tiers Chart Container" style="height: 300px; width: 100%;"></div>
        <div id="Farming Chart Container" style="height: 300px; width: 100%;"></div>
        <div id="Farming Tiers Chart Container" style="height: 300px; width: 100%;"></div>
        <div id="Nilas Chart Container" style="height: 300px; width: 100%;"></div>
        <div id="Nilas Tiers Chart Container" style="height: 300px; width: 100%;"></div>
    </body>
</html>

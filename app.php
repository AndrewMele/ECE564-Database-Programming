<?php
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';
 
sec_session_start();


if (login_check($mysqli) == true) 
    $logged = 'in';
else 
    header('Location: index.php');
list($gather_count,
     $gather_tier,
     $crafting_count,
     $crafting_tier,
     $farming_count,
     $farming_tier,
     $player_count,
     $player_tier) = home_stats($mysqli);
list($titles,$values) = format_js_chart($gather_count);
$player_name = json_encode($_SESSION['username']);
?>

<! DOCTYPE html>
<html>
<head>
    <title>INF Alliance Application</title>
    <script type="text/javascript" src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
    <script type="text/JavaScript" src="js/forms.js"></script>
    <link rel="stylesheet" type="text/css" href="css/app.css"></link>
    <input type="hidden" id="tab" value="home">
</head>
<body>
    <div class="background"></div>
    <?php
        if ($logged == 'in') {
                        echo '<b>' . htmlentities($_SESSION['username']) . ' | </b><a href="includes/logout.php">Logout</a>';
        } else {
                        echo 'Currently logged ' . $logged . '.';
                }
    ?> 
    <div class="header">
        <p>Pinned Announcements / Events / Upcoming Events / UTC Clock</p>
    </div>
    <div class="menu">
        <input class="home_btn" type="button" value="Home" onclick="showHome()">
        <input class="gather_btn" type="button" value="Gathering" onclick="showGather()">
        <input class="craft_btn" type="button" value="Crafting" onclick="showCraft()">
        <input class="farm_btn" type="button" value="Farming" onclick="showFarm()">
        <input class="gvg_btn" type="button" value="GvG" onclick="showGvG()">
        <input class="account_btn" type="button" value="<?php echo $_SESSION['username']; ?>" onclick="showAccount()"> 
    </div>
    <div class="main_app">
        <div id="home" class="home_view">
            <script type="text/javascript">
                window.onload = function(){
                    var titles = JSON.parse('<?= $titles; ?>');
                    var values = JSON.parse('<?= $values; ?>');
                    var player = JSON.parse('<?= $player_name; ?>');
                    createPieChart('Gathering',titles,values, true);
                    createPieChart('Gathering',titles,values);
                    <?php list($titles,$values) = format_js_chart($gather_tier);?>
                    titles = JSON.parse('<?= $titles; ?>');
                    values = JSON.parse('<?= $values; ?>');
                    createPieChart('Gathering Tiers',titles,values, true);
                    createPieChart('Gathering Tiers',titles,values);
                    <?php list($titles,$values) = format_js_chart($crafting_count);?>
                    titles = JSON.parse('<?= $titles; ?>');
                    values = JSON.parse('<?= $values; ?>');
                    createPieChart('Crafting',titles,values, true);
                    createPieChart('Crafting',titles,values);
                    <?php list($titles,$values) = format_js_chart($crafting_tier);?>
                    titles = JSON.parse('<?= $titles; ?>');
                    values = JSON.parse('<?= $values; ?>');
                    createPieChart('Crafting Tiers',titles,values, true);
                    createPieChart('Crafting Tiers',titles,values);
                    <?php list($titles,$values) = format_js_chart($farming_count);?>
                    titles = JSON.parse('<?= $titles; ?>');
                    values = JSON.parse('<?= $values; ?>');
                    createPieChart('Farming',titles,values, true);
                    createPieChart('Farming',titles,values);
                    <?php list($titles,$values) = format_js_chart($farming_tier);?>
                    titles = JSON.parse('<?= $titles; ?>');
                    values = JSON.parse('<?= $values; ?>');
                    createPieChart('Farming Tiers',titles,values, true);
                    createPieChart('Farming Tiers',titles,values);
                    <?php list($titles,$values) = format_js_chart($player_count);?>
                    titles = JSON.parse('<?= $titles; ?>');
                    values = JSON.parse('<?= $values; ?>');
                    createPieChart(player,titles,values, true, 1);
                    createPieChart(player,titles,values, false, 1);
                    <?php list($titles,$values) = format_js_chart($player_tier);?>
                    titles = JSON.parse('<?= $titles; ?>');
                    values = JSON.parse('<?= $values; ?>');
                    createPieChart(player + " Tiers",titles,values, true, 2);
                    createPieChart(player + " Tiers",titles,values, false, 2);
                }
            </script>
            <div class="chart" id="HomeGatheringChart"></div>
            <div class="chart" id="HomeGatheringTiersChart"></div>
            <div class="chart" id="HomeCraftingChart"></div>
            <div class="chart" id="HomeCraftingTiersChart"></div>
            <div class="chart" id="HomeFarmingChart"></div>
            <div class="chart" id="HomeFarmingTiersChart"></div>
            <div class="chart" id="HomePlayerChart"></div>
            <div class="chart" id="HomePlayerTiersChart"></div>
        </div>

        <div id="gather" class="gather_view">
            <div class="chart" id="GatheringChart"></div>
            <div class="chart" id="GatheringTiersChart"></div>
            <input class="search" type="text" id="GatheringSearch" onkeyup="searchFilter('GatheringSearch','GatheringTable')" placeholder="Search for names..">
            <?php GenerateHTMLTable('Gathering', $mysqli);?>
        </div>
        <div id="craft" class="craft_view">
            <div class="chart" id="CraftingChart"></div>
            <div class="chart" id="CraftingTiersChart"></div>
            <input class="search" type="text" id="CraftingSearch" onkeyup="searchFilter('CraftingSearch','CraftingTable')" placeholder="Search for names..">
            <?php GenerateHTMLTable('Crafting', $mysqli);?>
        </div>
        <div id="farm" class="farm_view">
            <div class="chart" id="FarmingChart"></div>
            <div class="chart" id="FarmingTiersChart"></div>
            <input class="search" type="text" id="FarmingSearch" onkeyup="searchFilter('FarmingSearch','FarmingTable')" placeholder="Search for names..">
            <?php GenerateHTMLTable('Farming', $mysqli);?>
        </div>
        <div id="gvg" class="gvg_view"></div>
        <div id="account" class="account_view">
            <div class="chart" id="PlayerChart"></div>
            <div class="chart" id="PlayerTiersChart"></div>
        </div>
    </div>
</body>
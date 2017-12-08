<?php
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';

$gather_table = $mysqli->query("SELECT * FROM Gathering");
//$crafting_table = query_db("SELECT * FROM Crafting", $mysqli);
//$farming_table = query_db("SELECT * FROM Farming", $mysqli);
/*echo $gather_table->field_count;
while ($row = $gather_table->fetch_row())
{
    print_r($row);
    echo '<br>';

            <table id="myTable">
            <tr>
                <?php 
                      while($field = $gather_table->fetch_field())
                      {
                        $field_name = ucfirst($field->name);
                        echo "<th>$field_name</th>";
                      }
                ?>
            </tr>
            <?php while($row = $gather_table->fetch_row())
                  {
                      echo "<tr>";
                      for ($index = 0; $index < $gather_table->field_count; $index++)
                        echo "<td>$row[$index]</td>";
                      echo "</tr>";
                  } 
            ?>
        </table>
}*/

?>

<! DOCTYPE html>
<html>
    <head>
        <script type="text/JavaScript" src="js/forms.js"></script>
    </head>
    <body> 
        <input type="text" id="myInput" onkeyup="searchFilter('myInput','Gathering Table')" placeholder="Search for names..">
        <?php GenerateHTMLTable('Gathering',$mysqli);
              GenerateHTMLTable('Crafting',$mysqli);
              GenerateHTMLTable('Farming',$mysqli); ?>

    </body>
</html>
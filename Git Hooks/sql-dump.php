<?php
echo dump_tables('192.168.2.205:3306','admin','Azerty123','stage_db');

function dump_tables($host,$user,$pass,$name,$tables = '*')
{
    
    $mysqli = new mysqli($host, $user, $pass, $name);
    
    if ($mysqli->connect_errno) {
        printf("Connect failed: %s\n", $mysqli->connect_error);
        exit();
    }
    
    // AUTO
    /**
    if($tables == '*')
    {
        $tables = array();
        $result = $mysqli->query('SHOW TABLES');
        while($row = $result->fetch_row())
        {
            $tables[] = $row[0];
        }
    }
    else
    {
        $tables = is_array($tables) ? $tables : explode(',',$tables);
    }**/
    
    //MANUAL (correct order)
    if ($tables == '*')
    {
        $tables = array();
        
        $tables[] = 'locations';
        $tables[] = 'categories';
        $tables[] = 'items';
        $tables[] = 'roles';
        $tables[] = 'users';
        $tables[] = 'usernotes';
    }
    
    $return = '';
    foreach($tables as $table)
    {
        $result = $mysqli->query('SELECT * FROM '.$table);
        $num_fields = $result->field_count;
        $return.= 'DROP TABLE IF EXISTS '.$table.';';
        $result2 = $mysqli->query('SHOW CREATE TABLE '.$table);
        $row2 = $result2->fetch_row();
        $return.= "\n\n".$row2[1].";\n\n";
    }
    
    $return.= "INSERT INTO `roles` (`id`, `name`) VALUES
    (1, 'Viewer'),
    (2, 'Editor'),
    (3, 'Manager'),
    (4, 'Administrator');";
        
    return $return;
}
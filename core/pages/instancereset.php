<?php
#   ___           __ _           _ __    __     _     
#  / __\ __ __ _ / _| |_ ___  __| / / /\ \ \___| |__  
# / / | '__/ _` | |_| __/ _ \/ _` \ \/  \/ / _ \ '_ \ 
#/ /__| | | (_| |  _| ||  __/ (_| |\  /\  /  __/ |_) |
#\____/_|  \__,_|_|  \__\___|\__,_| \/  \/ \___|_.__/ 
#
#		-[ Created by �Nomsoft
#		  `-[ Original core by Anthony (Aka. CraftedDev)
#
#				-CraftedWeb Generation II-                  
#			 __                           __ _   							   
#		  /\ \ \___  _ __ ___  ___  ___  / _| |_ 							   
#		 /  \/ / _ \| '_ ` _ \/ __|/ _ \| |_| __|							   
#		/ /\  / (_) | | | | | \__ \ (_) |  _| |_ 							   
#		\_\ \/ \___/|_| |_| |_|___/\___/|_|  \__|	- www.Nomsoftware.com -	   
#                  The policy of Nomsoftware states: Releasing our software   
#                  or any other files are protected. You cannot re-release    
#                  anywhere unless you were given permission.                 
#                  � Nomsoftware 'Nomsoft' 2011-2012. All rights reserved.    

    global $Account, $Website, $Server, $Character, $Database;
?>
<div class='box_two_title'>Instance Reset</div>
Let's you reset the instance on your characters.<hr/>
<?php
    $Account->isNotLoggedIn();

    $service = "reset";

    if ( DATA['service'][$service]['price'] == 0 )
    {
        echo '<span class="attention">Instance Reset is free of charge.</span>';
    }
    else
    {
        ?>
        <span class="attention">Instance Reset costs 
            <?php echo DATA['service'][$service]['price'] .' '. $Website->convertCurrency(DATA['service'][$service]['currency']); ?></span>
        <?php
        if (DATA['service'][$service]['currency'] == "vp")
        {
            echo "<span class='currency'>Vote Points: " . $Account->loadVP($_SESSION['cw_user']) . "</span>";
        }
        elseif (DATA['service'][$service]['currency'] == "dp")
        {
            echo "<span class='currency'>" . DATA['website']['donation']['coins_name'] . ": " . $Account->loadDP($_SESSION['cw_user']) . "</span>";
        }
    }

    if (isset($_POST['ir_step1']) || isset($_POST['ir_step2']))
    {
        echo 'Selected Realm: <b>' . $Server->getRealmName($_POST['ir_realm']) . '</b><br/><br/>';
    }
    else
    {
        ?>
        Select realm: 
        &nbsp;
        <form action="?page=instancereset" method="post">
            <table>
            <tr>
            <td>
                <select name="ir_realm">
                    <?php
                    $result = $Database->select("realms", "name,char_db")->get_result();
                    while ($row = $result->fetch_assoc())
                    {
                        if (isset($_POST['ir_realm']) && $_POST['ir_realm'] == $row['char_db'])
                        {
                            echo '<option value="' . $row['char_db'] . '" selected>';
                        }
                        else
                        {
                            echo '<option value="' . $row['char_db'] . '">';
                        }
                        echo $row['name'] . '</option>';
                    }
                    ?>
                </select>
            </td>
            <td>
                <?php
                if (!isset($_POST['ir_step1']) && !isset($_POST['ir_step2']) && !isset($_POST['ir_step3']))
                    echo '<input type="submit" value="Continue" name="ir_step1">';
                ?>
            </td>
            </tr>
            </table>
        </form>
        <?php
    }
    if (isset($_POST['ir_step1']) || isset($_POST['ir_step2']) || isset($_POST['ir_step3']))
    {
        if (isset($_POST['ir_step2']))
            echo 'Selected character: <b>' . $Character->getCharName($_POST['ir_char'], $Server->getRealmId($_POST['ir_realm']))
            . '</b><br/><br/>';
        else
        {
            ?>
            Select character: 
            &nbsp;
            <form action="?page=instancereset" method="post">
                <table>
                    <tr>
                        <td>
                            <input type="hidden" name="ir_realm" value="<?php echo $_POST['ir_realm']; ?>">
                            <select name="ir_char">
                                <?php
                                $acc_id = $Account->getAccountID($_SESSION['username']);
                                $Database->selectDB($_POST['ir_realm']);
                                $result = $Database->select("characters", "name, guid", null, "account=$acc_id")->get_result();

                                while ($row = $result->fetch_assoc())
                                {
                                    if (isset($_POST['ir_char']) && $_POST['ir_char'] == $row['guid'])
                                    {
                                        echo '<option value="' . $row['guid'] . '" selected>';
                                    }
                                    else
                                    {
                                        echo '<option value="' . $row['guid'] . '">';
                                    }

                                    echo $row['name'] . '</option>';
                                }
                                ?>
                            </select>
                        </td>
                        <td>
                            <?php
                            if (!isset($_POST['ir_step2']) && !isset($_POST['ir_step3']))
                                echo '<input type="submit" value="Continue" name="ir_step2">';
                            ?>
                        </td>
                    </tr>
                </table>
            </form>
            <?php
        }
    }
    if (isset($_POST['ir_step2']) || isset($_POST['ir_step3']))
    { ?>
        Select instance: 
        &nbsp;
        <form action="?page=instancereset" method="post">
            <table>
                <tr>
                    <td>
                        <input type="hidden" name="ir_realm" value="<?php echo $_POST['ir_realm']; ?>">
                        <input type="hidden" name="ir_char" value="<?php echo $_POST['ir_char']; ?>">
                        <select name="ir_instance">
                            <?php
                            $guid = $Database->conn->escape_string($_POST['ir_char']);
                            $Database->selectDB($_POST['ir_realm']);

                            $result = $Database->select("character_instance", "instance", null, "guid=$guid AND permanent=1")->get_result();
                            if ($result->num_rows == 0)
                            {
                                echo "<option value='#'>No instance locks were found</option>";
                                $nope = true;
                            }
                            else
                            {
                                while ($row = $result->fetch_assoc())
                                {
                                    $getI     = $Database->select("instance", "id, map, difficulty", null, "id=". $row['instance'])->get_result();
                                    $instance = $getI->fetch_assoc();

                                    $Database->selectDB("webdb");
                                    $getName = $Database->select("instance_data", "name", null, "map='". $instance['map'] ."'")->get_result();
                                    $name    = $getName->fetch_assoc();

                                    if (empty($name['name']))
                                        $name = "Unknown Instance";
                                    else
                                        $name = $name['name'];

                                    if ($instance['difficulty'] == 0)
                                    {
                                        $difficulty = "10-man Normal";
                                    }
                                    elseif ($instance['difficulty'] == 1)
                                    {
                                        $difficulty = "25-man Normal";
                                    }
                                    elseif ($instance['difficulty'] == 2)
                                    {
                                        $difficulty = "10-man Heroic";
                                    }
                                    elseif ($instance['difficulty'] == 3)
                                    {
                                        $difficulty = "25-man Heroic";
                                    }

                                    echo '<option value="' . $instance['id'] . '">' . $name . ' <i>(' . $difficulty . ')</i></option>';
                                }
                            }
                            ?>
                        </select>
                    </td>
                    <td>
                        <?php
                        if (!isset($_POST['ir_step1']) && !isset($nope))
                            echo '<input type="submit" value="Reset Instance" name="ir_step3">';
                        ?>
                    </td>
                </tr>
            </table>
        </form>
        <?php
    }

    if (isset($_POST['ir_step3']))
    {
        $guid     = $Database->conn->escape_string($_POST['ir_char']);
        $instance = $Database->conn->escape_string($_POST['ir_instance']);

        if (DATA['service'][$service]['currency'] == "vp")
        {
            if ($Account->hasVP($_SESSION['cw_user'], DATA['service'][$service]['price']) == false)
                echo '<span class="alert">You do not have enough Vote Points!';
            else
            {
                $Database->selectDB($_POST['ir_realm']);
                $Database->conn->query("DELETE FROM instance WHERE id=". $instance .";");

                $Account->deductVP($Account->getAccountID($_SESSION['cw_user']), DATA['service'][$service]['price']);
                echo '<span class="approved">The instance lock was removed!</span>';
            }
        }
        elseif ( DATA['service'][$service]['currency'] == "dp" )
        {
            if ( $Account->hasDP($_SESSION['cw_user'], DATA['service'][$service]['price']) == false )
            {
                echo '<span class="alert">You do not have enough ' . DATA['website']['donation']['coins_name'];
            }
            else
            {
                $Database->selectDB($_POST['ir_realm']);
                $Database->conn->query("DELETE FROM instance WHERE id=". $instance .";");

                $Account->deductDP($Account->getAccountID($_SESSION['cw_user']), DATA['service'][$service]['price']);
                echo '<span class="approved">The instance lock was removed!</span>';

                $Account->logThis("Performed an Instance reset on " . $Character->getCharName($guid, $Server->getRealmId($_POST['ir_realm'])), "instancereset", $Server->getRealmId($_POST['ir_realm']));
            }
        }
    }
?>
<br/>
<a href="?page=instancereset">Start over</a>
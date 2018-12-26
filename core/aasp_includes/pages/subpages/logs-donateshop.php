<?php
    /* ___           __ _           _ __    __     _     
      / __\ __ __ _ / _| |_ ___  __| / / /\ \ \___| |__
      / / | '__/ _` | |_| __/ _ \/ _` \ \/  \/ / _ \ '_ \
      / /__| | | (_| |  _| ||  __/ (_| |\  /\  /  __/ |_) |
      \____/_|  \__,_|_|  \__\___|\__,_| \/  \/ \___|_.__/

      -[ Created by �Nomsoft
      `-[ Original core by Anthony (Aka. CraftedDev)

      -CraftedWeb Generation II-
      __                           __ _
      /\ \ \___  _ __ ___  ___  ___  / _| |_
      /  \/ / _ \| '_ ` _ \/ __|/ _ \| |_| __|
      / /\  / (_) | | | | | \__ \ (_) |  _| |_
      \_\ \/ \___/|_| |_| |_|___/\___/|_|  \__|	- www.Nomsoftware.com -
      The policy of Nomsoftware states: Releasing our software
      or any other files are protected. You cannot re-release
      anywhere unless you were given permission.
      � Nomsoftware 'Nomsoft' 2011-2012. All rights reserved. */

  global $GamePage, $GameServer, $GameAccount; 
  $conn = $GameServer->connect();  
?> 
<div class="box_right_title">Donation Shop logs</div>
<?php
    $result = $Database->select("shoplog", null, null, "shop='donate' ORDER BY id DESC LIMIT 10")->get_result();
    if ($result->num_rows == 0)
    {
        echo "Seems Like The Donation Shop Log Was Empty!";
    }
    else
    {
        ?>
        <input type='text' value='Search...' id="logs_search" onkeyup="searchLog('donate')"><hr/>
        <div id="logs_content">
            <table width="100%">
                <tr>
                    <th>User</th>
                    <th>Character</th>
                    <th>Realm</th>
                    <th>Item</th>
                    <th>Date</th>
                </tr>
        <?php while ($row = $result->fetch_assoc())
        { ?>
                    <tr class="center">
                        <td><?php echo $GameAccount->getAccName($row['account']); ?></td>
                        <td><?php echo $GameAccount->getCharName($row['char_id'], $row['realm_id']); ?></td>
                        <td><?php echo $GameServer->getRealmName($row['realm_id']); ?></td>
                        <td><a href="http://<?php echo DATA['website']['tooltip_href']; ?>item=<?php echo $row['entry']; ?>" title="" target="_blank">
                    <?php echo $GameServer->getItemName($row['entry']); ?></a></td>
                        <td><?php echo $row['date']; ?></td>
                    </tr>	
        <?php } ?>
            </table>
        </div>
    <?php }
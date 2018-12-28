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

    function exit_page()
    {
        die("<h1>Website Error</h1>
		Something went wrong in the website script. Please contact the webmaster of this page if the problem persists. 
		<br/>
		<br/>
		<br/>
		<i>CraftedWeb</i>");
    }

    function RandomString()
    {
        $length     = 10;
        $characters = "0123456789abcdefghijklmnopqrstuvwxyz";
        $string     = "";
        for ($p = 0; $p < $length; $p++)
        {
            $string .= $characters[mt_rand(0, strlen($characters))];
        }
        return $string;
    }

    function convTime($time)
    {
        if ($time < 60)
        {
            $string = "Seconds";
        }
        if ($time > 60)
        {
            $time   = $time / 60;
            $string = "Minutes";
        }
        if ($time > 60)
        {
            $string = "Hours";
            $time   = $time / 60;
        }
        if ($time > 24)
        {
            $string = "Days";
            $time   = $time / 24;
        }
        
        return ceil($time) ." ". $string;
    }
    
<div class="menu">                

            <div class="breadLine">            
                <div class="arrow"></div>
                <div class="adminControl active">
                    Hi, <?php echo $fetchrecord['first_name'].' '.$fetchrecord['last_name'];   ?>
                </div>
            </div>

            <div class="admin">
                <div class="image">
                    <img src="../img/users/aqvatarius.jpg" class="img-polaroid"/>                
                </div>
                <ul class="control">                
                    <li><span class="icon-comment"></span> <a href="../branch/querydetail.php">Messages</a></li>
                    <li><span class="icon-cog"></span> <a href="forms.html">Settings</a></li>
                    <li><span class="icon-share-alt"></span> <a href='../index.php?id=logout'>Logout</a></li>
                </ul>
                <div class="info">
                    <span>Welcome back! Your last visit: 24.10.2012 in 19:55</span>
                </div>
            </div>

            <ul class="navigation">            
                <li class="active">
                    <a href="<?php echo constant('BASE_URL') ?>/branch">
                        <span class="isw-grid"></span><span class="text">Dashboard</span>
                    </a>
                </li>
                <li class="openable">
                    <a href="#">
                        <span class="isw-chat"></span><span class="text">Manage Employee</span>
                    </a>
                    <ul>
                                     <li>
                                         <a href="<?php echo constant('BASE_URL'); ?>/branch/manageemployee.php?id=<?php echo $user_id; ?>">
                                <span class="icon-comment"></span><span class="text">Manage Branch</span></a>
</li>              

                    </ul>                


                </li>                        
                <li class="openable">
                    <a href="#">
                        <span class="isw-chat"></span><span class="text">Messages</span>
                    </a>
                    <ul>
                        <li>
                            <a href="<?php echo constant('BASE_URL'); ?>/branch/querydetail.php">
                                <span class="icon-comment"></span><span class="text">Messages widgets</span></a>

                                                                                                                          
                        </li>                                        
                    </ul>                


                </li>                                    
                                                             
            </ul>

            <div class="dr"><span></span></div>

            <div class="widget-fluid">
                <div id="menuDatepicker"></div>
            </div>

            <div class="dr"><span></span></div>

            <div class="widget">

                <div class="input-append">
                    <input id="appendedInputButton" style="width: 118px;" type="text"><button class="btn" type="button">Search</button>
                </div>            

            </div>

            <div class="dr"><span></span></div>

            <div class="widget-fluid">

                <div class="wBlock">
                    <div class="dSpace">
                        <h3>Last visits</h3>
                        <span class="number">6,302</span>                    
                        <span>5,774 <b>unique</b></span>
                        <span>3,512 <b>returning</b></span>
                    </div>
                    <div class="rSpace">
                        <h3>Today</h3>
                        <span class="mChartBar" sparkType="bar" sparkBarColor="white"><!--240,234,150,290,310,240,210,400,320,198,250,222,111,240,221,340,250,190--></span>                                                                                
                        <span>&nbsp;</span>
                        <span>65% <b>New</b></span>
                        <span>35% <b>Returning</b></span>
                    </div>
                </div>

            </div>

        </div>
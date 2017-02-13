
<nav id="topNav" class="navbar navbar-default navbar-fixed-top">
   <div class="container-fluid">
       <div class="navbar-header">
         <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-navbar">
             <span class="sr-only">Toggle navigation</span>
             <span class="icon-bar"></span>
             <span class="icon-bar"></span>
             <span class="icon-bar"></span>
         </button>
           <a id="bHome" class="navbar-brand page-scroll" href="<?php echo base_url(); ?>index.php/home"><p class="cursive">Campfire  <i class="ion-bonfire" id= "custom-icon"></i></p></a>
       </div>
       <div class="navbar-collapse collapse" id="bs-navbar">
         <ul class="nav navbar-nav">
               <li <?php if(isActive("about")) echo "class='active' "; ?>>
                   <a class="page-scroll" id = "about" href="<?php echo base_url('index.php/home'); ?>#about">About</a>
               </li>
               <li>
                   <a class="page-scroll" href="<?php if(!isActive("home")) echo base_url("index.php/home");?>#contact">Contact</a>
               </li>
               <li class="dropdown" id="dropmenu-event">
                      <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                          Event<span class="caret"></span>
                      </a>
                      <ul class="dropdown-menu">
                        <li <?php if(isActive("Event/search")) echo "class='active' "; ?>><a id = "searchEvents" href="<?php echo base_url('index.php/Event/search'); ?>">Search Event</a></li>
                        <li <?php if(isActive("myEvents")) echo "class='active' "; ?>><a id = "bMyEvents" href="<?php echo base_url(); ?>index.php/myEvents">My Events</a></li>
						<li <?php if(isActive("createEvent")) echo "class='active' "; ?>><a id = "createAnEvent" href="<?php echo base_url(); ?>index.php/createEvent">Create Event</a></li>
                      </ul>
              </li>
              <li class="dropdown" id="dropmenu-group">
                     <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                         Group<span class="caret"></span>
                     </a>
                     <ul class="dropdown-menu">
                         <li <?php if(isActive("Group/search")) echo "class='active' "; ?>><a id = "searchGroups" href="<?php echo base_url('index.php/Group/search'); ?>">Search Groups</a></li>
                         <li <?php if(isActive("myGroups")) echo "class='active' "; ?>><a id = "bMyGroups" href="<?php echo base_url(); ?>index.php/myGroups">My Groups</a></li>
						 <li <?php if(isActive("createGroup")) echo "class='active' "; ?>><a id = "createAGroup" href="<?php echo base_url(); ?>index.php/createGroup">Create Group</a></li>
                     </ul>
             </li>
         </ul>
           <ul class="nav navbar-nav navbar-right">
             <li class="dropdown" id="dropmenu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                        <strong><?php echo $this->session->userdata('fname')." ".$this->session->userdata('lname'); ?> </strong><span></span><span class="glyphicon glyphicon-user"></span>
                        <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu">
                      <li <?php if(isActive("editProfile")) echo "class='active' "; ?>><a id = "bEditProfile" href="<?php echo base_url(); ?>index.php/editProfile">Edit Profile</a></li>
                      <li <?php if(isActive("myGroups")) echo "class='active' "; ?>><a id = "bMyGroups" href="<?php echo base_url(); ?>index.php/myGroups">My Groups</a></li>
                      <li <?php if(isActive("myEvents")) echo "class='active' "; ?>><a id = "bMyEvents" href="<?php echo base_url(); ?>index.php/myEvents">My Events</a></li>
                      <li class="divider"></li>
                      <li><a id = "bLogout" href="<?php echo base_url(); ?>index.php/home/logout">Log Out</a></li>
                    </ul>
            </li>
           </ul>
       </div>
   </div>
</nav>


<?php
//Find which page is currently on, then make it active on navbar
function isActive($arg) {
  //$ci is used instead of $this because of "Using $this when not in object context" error
  $ci =& get_instance();
  if($arg === $ci->uri->segment(1))
    return true;


  return false;
}
?>

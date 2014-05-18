<div class="navbar navbar-inverse navbar-fixed-top">
  <div class="navbar-inner">
    <div class="container">
      <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <div id="title"><a class="brand" href="<?php echo $VarsToPass['site_absolute_url']; ?>" title="myRealtorCliq"><h1>MyRealtorCliq.com</h1></a></div>
      <div class="nav-collapse collapse">
        <ul class="nav">
          <li><a href="<?php echo $VarsToPass['site_absolute_url']; ?>how-it-works" title="How It Works">How It Works</a></li>
          <li><a href="<?php echo $VarsToPass['site_absolute_url']; ?>login" title="Login">Login</a></li>
          <li class="blue-button"><a href="<?php echo $VarsToPass['site_absolute_url']; ?>register" title="Create an Account">Create an Account</a></li>
        </ul>
      </div>
    </div>
  </div>
</div>
<div class="search-box-container">
  <div class="search-box-container-inner">
    <form action="/search" id="search-form" name="search-form">
      <input type="text" id="search-term" name="search-name" placeholder="Enter Your Location (Los Angeles, New York)" />
      <input type="submit" id="submit-search" name="submit-search" value="Search" />
    </form>
  </div>
</div>
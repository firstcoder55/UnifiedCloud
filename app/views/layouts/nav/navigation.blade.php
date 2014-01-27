 <nav class="navbar navbar-default main-nav" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
        <a class="navbar-brand brand-small" href="{{ route('landing') }}">Kumo.<!-- logo goes here --></a>
      </div>

      <!-- Collect the nav links, forms, and other content for toggling -->
      <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
           <ul class="nav navbar-nav navbar-right">
            @if(!Auth::check())
              <li><a href="{{ route('sign_in_page') }}">Sign in</a></li>
              <li><a href="{{ route('sign_up_page') }}">Sign up</a></li>
            @else 
              <li>{{ HTML::link('user/logout', 'Logout') }}</li>
            @endif
           </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- container -->
</nav><!-- main navigation-->


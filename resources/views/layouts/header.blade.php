<nav class="main-header navbar navbar-expand navbar-white navbar-light">
  <!-- Left navbar links -->
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
    </li>
  </ul>

  <!-- Right navbar links -->
  <ul class="navbar-nav ml-auto">
    <li class="nav-item">
      <a class="nav-link" data-widget="fullscreen" href="#" role="button">
        <i class="fas fa-expand-arrows-alt"></i>
      </a>
    </li>
    <li class="nav-item dropdown">
      <a class="nav-link" data-toggle="dropdown" href="#" role="button">
        <i class="fas fa-user-circle" style="font-size: 1.5rem;"></i>
      </a>
      <div class="dropdown-menu dropdown-menu-right">
        <a href="{{ url('/profil')}}" class="dropdown-item">
          <i class="fas fa-edit mr-2"></i> Edit Profil
        </a>        
      </div>
    </li>
    <li class="nav-item">
      <form action="{{ url('/logout')}}">
        <button type="submit" class="btn btn-block bg-gradient-danger" fdprocessedid="idnj1o">Logout</button>
      </form>
    </li>
  </ul>
</nav>



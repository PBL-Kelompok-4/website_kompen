<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item">
        <a href="">
          <a href="#" class="btn btn-sm btn-primary">{{ auth()->user()->level->nama_level }}</a>
        </a>
        <button class="btn btn-sm btn-primary">
          {{ auth()->user()->nama }}
        </button>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <li class="nav-item">
        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
          <i class="fas fa-expand-arrows-alt"></i>
        </a>
      </li>
      <li class="nav-item">
        <form action="{{ url('/logout')}}">
          <button type="submit" class="btn btn-block bg-gradient-danger" fdprocessedid="idnj1o">Logout</button>
        </form>
      </li>
    </ul>
  </nav>
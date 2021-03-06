<li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i class="nav-icon la la-dashboard"></i> <span>{{ trans('backpack::base.dashboard') }}</span></a></li>
<li class="nav-title">Academico</li>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('estudiantes') }}'><i class='nav-icon la la-users'></i> Estudiantes</a></li>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('inscripciones') }}'><i class='nav-icon la la-paste'></i> Inscripciones</a></li>
<li class="nav-item nav-dropdown">
  <a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon la la-outdent"></i>Eventos</a>
  <ul class="nav-dropdown-items">
    <li class='nav-item'><a class='nav-link' href='{{ backpack_url('eventos') }}'><i class='nav-icon la la-paperclip'></i> Eventos</a></li>
    <li class='nav-item'><a class='nav-link' href='{{ backpack_url('titulos') }}'><i class='nav-icon la la-paint-brush'></i> Titulos</a></li>    
  </ul>
</li>
<li class="nav-item nav-dropdown">
  <a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon la la-cog"></i>Configuracion</a>
  <ul class="nav-dropdown-items">
    <li class='nav-item'><a class='nav-link' href='{{ backpack_url('departamentos') }}'><i class='nav-icon la la-map-o'></i> Departamentos</a></li>
    <li class='nav-item'><a class='nav-link' href='{{ backpack_url('fuentes') }}'><i class='nav-icon la la-thumb-tack'></i> Fuentes</a></li>
    <li class='nav-item'><a class='nav-link' href='{{ backpack_url('tipo') }}'><i class='nav-icon la la-tag'></i> Tipos</a></li>
  </ul>
</li>

<li class="nav-title">Administracion</li>
<li class="nav-item nav-dropdown">
    <a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon la la-newspaper-o"></i> News</a>
    <ul class="nav-dropdown-items">
      <li class="nav-item"><a class="nav-link" href="{{ backpack_url('article') }}"><i class="nav-icon la la-newspaper-o"></i> <span>Articles</span></a></li>
      <li class="nav-item"><a class="nav-link" href="{{ backpack_url('category') }}"><i class="nav-icon la la-list"></i> <span>Categories</span></a></li>
      <li class="nav-item"><a class="nav-link" href="{{ backpack_url('tag') }}"><i class="nav-icon la la-tag"></i> <span>Tags</span></a></li>
    </ul>
</li>

<li class="nav-item"><a class="nav-link" href="{{ backpack_url('page') }}"><i class="nav-icon la la-file-o"></i> <span>Pages</span></a></li>
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('menu-item') }}"><i class="nav-icon la la-list"></i> <span>Menu</span></a></li>
<li class="nav-title">General</li>
<!-- Users, Roles Permissions -->
<li class="nav-item nav-dropdown">
  <a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon la la-group"></i> Authentication</a>
  <ul class="nav-dropdown-items">
    <li class="nav-item"><a class="nav-link" href="{{ backpack_url('user') }}"><i class="nav-icon la la-user"></i> <span>Users</span></a></li>
    <li class="nav-item"><a class="nav-link" href="{{ backpack_url('role') }}"><i class="nav-icon la la-group"></i> <span>Roles</span></a></li>
    <li class="nav-item"><a class="nav-link" href="{{ backpack_url('permission') }}"><i class="nav-icon la la-key"></i> <span>Permissions</span></a></li>
  </ul>
</li>

<li class="nav-item nav-dropdown">
    <a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon la la-cogs"></i> Advanced</a>
    <ul class="nav-dropdown-items">
      <li class="nav-item"><a class="nav-link" href="{{ backpack_url('elfinder') }}"><i class="nav-icon la la-files-o"></i> <span>File manager</span></a></li>
      <li class="nav-item"><a class="nav-link" href="{{ backpack_url('backup') }}"><i class="nav-icon la la-hdd-o"></i> <span>Backups</span></a></li>
      <li class="nav-item"><a class="nav-link" href="{{ backpack_url('log') }}"><i class="nav-icon la la-terminal"></i> <span>Logs</span></a></li>
      <li class="nav-item"><a class="nav-link" href="{{ backpack_url('setting') }}"><i class="nav-icon la la-cog"></i> <span>Settings</span></a></li>
    </ul>
</li>

<li class="nav-item nav-dropdown">
  <a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon las la-exclamation-triangle"></i>Ejemplos</a>
  <ul class="nav-dropdown-items">
    <li class="nav-item"><a class="nav-link" href="{{ backpack_url('monster') }}"><i class="nav-icon la la-optin-monster"></i> <span>Monsters</span></a></li>
    <li class="nav-item"><a class="nav-link" href="{{ backpack_url('icon') }}"><i class="nav-icon la la-info-circle"></i> <span>Icons</span></a></li>
    <li class="nav-item"><a class="nav-link" href="{{ backpack_url('fluent-monster') }}"><i class="nav-icon la la-pastafarianism"></i> <span>Fluent Monsters</span></a></li>
    <li class="nav-item"><a class="nav-link" href="{{ backpack_url('dummy') }}"><i class="nav-icon la la-poo"></i> <span>Dummies</span></a></li>
  </ul>
</li>





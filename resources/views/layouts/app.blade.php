
  @include('sections.header')

  <main id="main" class="main">
    <div class="container">
    @yield('content')
    </div>
  </main>

  @hasSection('sidebar')
    <aside class="sidebar">
      @yield('sidebar')
    </aside>
  @endif

@include('sections.footer')

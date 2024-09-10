<nav class="topnav navbar navbar-light">
  <button type="button" class="navbar-toggler text-muted mt-2 p-0 mr-3 collapseSidebar">
    <i class="fe fe-menu navbar-toggler-icon"></i>
  </button>
  <form class="form-inline mr-auto searchform text-muted" method="get" action="{{ route('usersearch') }}" style="width: 40%;">
    <input class="form-control mr-sm-2 bg-transparent border-0 pl-4 text-muted" type="search" name="query" placeholder="Type something..." aria-label="Search" id="search-input" value="{{ request()->input('query') }}">
    @csrf
    <!-- <button class="btn btn-link text-muted" type="submit">Search</button> -->
    <div id="autocomplete-results" style="display: none;"></div>
  </form>
  <ul class="nav">
    <li class="nav-item nav-notif">
      <a class="nav-link text-muted my-2" href="{{route('userscart') }}">
        <i class="fas fa-shopping-cart"></i>
      </a>
    </li>
    <?php
      $id = session('id');
      $user = DB::table('users')->where('id', $id)->first();
      if (!empty($user)) {
        $profile_picture = $user->profile_picture;
      }
      else{
        $profile_picture = 'default.png';
      }
   ?>
    <li class="nav-item dropdown">
      <a class="nav-link dropdown-toggle text-muted pr-0" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown">
        <span class="avatar avatar-sm mt-2">
          <img src="{{ asset('profile_picture/'. $profile_picture) }}" alt="..." class="avatar-img rounded-circle">
        </span>
      </a>
      <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
        <a href="{{ route('userprofile') }}" class="dropdown-item">Profile</a>
        <a class="dropdown-item" href="#">Settings</a>
        <a href="{{ route('logout') }}" class="dropdown-item">Logout</a>
      </div>
    </li>
  </ul>
</nav>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

<script>
  $(document).ready(function() {
    $('#search-input').on('keyup', function() {
      var query = $(this).val();
      $.ajax({
        type: 'GET',
        url: '{{ route('autocomplete') }}',
        data: { query: query },
        success: function(data) {
          if (data.length > 0) {
            $('#autocomplete-results').show();
            $('#autocomplete-results').html('');
            $.each(data, function(index, item) {
              $('#autocomplete-results').append('<li><a>' + item.name + '</a></li>');
            });
          } else {
            $('#autocomplete-results').hide();
          }
        }
      });
    });

    $('#autocomplete-results').on('click', 'li a', function() {
      var text = $(this).text();
      $('#search-input').val(text);
      $('form').submit();
    });

    $(document).on('click', function(event) {
      if ($(event.target).closest('#search-input, #autocomplete-results').length === 0) {
        $('#autocomplete-results').hide();
      }
    });

    $('#search-input').on('focus', function() {
      $(this).css('width', '150%');
    });

    $('#search-input').on('blur', function() {
      $(this).css('width', '40%');
    });

  });
</script>
<style>
  #autocomplete-results {
    position: absolute;
    top: 120%;
    left: 0;
    background-color: #fff;
    border: 1px solid #ddd;
    padding: 10px;
    width: 120%;
    z-index: 1000;
  }

  #autocomplete-results ul {
    list-style: none;
    padding: 0;
    margin: 0;
  }

  #autocomplete-results li {
    list-style-type: none;
    padding: 10px;
    border-bottom: 1px solid #ddd;
  }

  #autocomplete-results li:hover {
    background-color: #f0f0f0;
  }

  #autocomplete-results a {
    text-decoration: none;
    color: #337ab7;
  }
</style>
<!--
    - #HEADER
  -->

  <header class="header" data-header>
    <div class="container">
        <h1>
            <a href="#" class="logo"><img src="{{ asset('assets/images/logo.png')}}" width="170px" alt=""></a>
        </h1>
        <nav class="navbar" data-navbar>
            <ul class="navbar-list">
                <li class="nav-item">
                    <a href="{{ route('dashboardCustomer.index')}}" class="navbar-link" data-nav-link>Home</a>
                </li>
                <li class="nav-item">
                    <a href="#food-menu" class="navbar-link" data-nav-link>Katalog</a>
                </li>
                <li class="nav-item">
                    <a href="#about" class="navbar-link" data-nav-link>About Us</a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('show_payment_pesanan_list') }}" class="navbar-link" data-nav-link>Daftar
                        Pesanan</a>
                </li>

                <li class="nav-item">
                    <a href="{{route('customer.cart')}}" class="navbar-link" data-nav-link>Cart</a>
                </li>
            </ul>
        </nav>
        <div class="header-btn-group">
            <button class="search-btn" aria-label="Search" data-search-btn>
                <ion-icon name="search-outline"></ion-icon>
            </button>

            <img src="{{ asset('image/pictureProfile.png') }}" width="50px">

            <div class="dropdown">
                <button class="dropdown-icon" onclick="toggleDropdown()">
                    <ion-icon name="caret-down-outline"></ion-icon>
                </button>
                <div class="dropdown-content" id="dropdownMenu">
                    <a href="{{ route('profil_customer') }}">Profile</a>
                    <a href="{{ route('logout') }}">Logout</a>
                </div>
            </div>

            <button class="nav-toggle-btn" aria-label="Toggle Menu" data-menu-toggle-btn>
                <span class="line top"></span>
                <span class="line middle"></span>
                <span class="line bottom"></span>
            </button>
        </div>
    </div>
</header>


<!--
- #SEARCH BOX
-->

<div class="search-container" data-search-container>
    <div class="search-box">
        <input type="search" name="search" aria-label="Search here" placeholder="Type keywords here..."
            class="search-input">

        <button class="search-submit" aria-label="Submit search" data-search-submit-btn>
            <ion-icon name="search-outline"></ion-icon>
        </button>
    </div>

    <button class="search-close-btn" aria-label="Cancel search" data-search-close-btn></button>

</div>

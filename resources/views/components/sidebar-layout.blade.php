 <nav class="navbar-vertical navbar">
     <div class="nav-scroller">
         <!-- Brand logo -->
         <a class="navbar-brand" href="{{ asset('/') }}">
             {{-- <img src="{{ asset('/') }}assets/images/brand/logo/logo.svg" alt="" /> --}}
             <h3 style="color: white;font-weight: 600">BANSOS</h3>

         </a>
         <!-- Navbar nav -->
         <ul class="navbar-nav flex-column" id="sideNavbar">
             <li class="nav-item">
                 <a class="nav-link has-arrow " href="{{ asset('/dashboard') }}">
                     <i data-feather="home" class="nav-icon icon-xs me-2"></i> Dashboard
                 </a>

             </li>
             <li class="nav-item">
                 <a class="nav-link " href="{{ route('bantuan') }}">
                     <i class="fa-brands fa-squarespace me-2"></i>
                     Bantuan
                 </a>
             </li>
             @if (auth()->user()->level == 1)
                 <li class="nav-item">
                     <div class="navbar-heading">Master</div>
                 </li>
                 <li class="nav-item">
                     <a class="nav-link " href="{{ route('program') }}">
                         <i class="fa-solid fa-layer-group me-2"></i>
                         Program
                     </a>
                 </li>


                 <li class="nav-item">
                     <div class="navbar-heading">User</div>
                 </li>


                 <li class="nav-item">
                     <a class="nav-link " href="{{ route('user') }}">
                         <i class="fa-solid fa-user-tie me-2"></i>
                         Users
                     </a>
                 </li>
             @endif


         </ul>

     </div>
 </nav>

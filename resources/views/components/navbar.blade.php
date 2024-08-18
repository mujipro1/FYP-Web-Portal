
<div class="container pb-4">
    <div class="row">
        <nav class="navbar navbar-expand-lg">
            <div class="container">
                <a class="navbar-brand" href="/home">Cultivating Profits</a>
                
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                aria-expanded="false" aria-label="Toggle navigation">
                    <input type="checkbox" name="toggle-menu" id="toggle-menu">
                    <label for="toggle-menu" type="button" class="toggle-btn">
                        
                        <div class="bar"></div>
                        <div class="bar"></div>
                        <div class="bar"></div>
                    </label>
                    
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0 mx-4">
                        <li class="nav-item">
                            <a id='home' class="nav-link" href="/home">{{__('messages.home')}}</a>
                        </li>
                        <li class="nav-item">
                            <a id='services' class="nav-link" href="/services">{{__('messages.services')}}</a>
                        </li>
                        <li class="nav-item">
                            <a id='contact' class="nav-link" href="/contact" style="margin-right:35px;">{{__('messages.contact')}}</a>
                        </li>
                        <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center"
                            href="#"
                            id="navbarDropdownMenuLink"
                            role="button"
                            data-toggle="dropdown"
                            aria-expanded="false"
                        >
                            <img
                            src="{{asset('images/profile.jpg')}}"
                            class="rounded-circle"
                            height="30"
                            alt=""
                            loading="lazy"
                            />
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                            <li><a class="dropdown-item" href="#">My profile</a></li>
                            <li><a class="dropdown-item" href="#">Settings</a></li>
                            <li><a class="dropdown-item" onclick='logout()'>Logout</a></li>
                            <li class="nav-item"><a class="dropdown-item" href="{{url('lang/en')}}">EN</a></li>
                            <li class="nav-item"><a class="dropdown-item" href="{{url('lang/ur')}}">UR</a></li>
                        </ul>
                    </li>
                        
                    </ul>
                </div>
            </div>
        </nav>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    function logout(){
        window.location.href = "{{ route('logout') }}";
    }
</script>
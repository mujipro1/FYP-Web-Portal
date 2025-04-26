
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
                            <a id='home' class="nav-link" href="/manager/farms">{{__('messages.dashboard')}}</a>
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
                            <li><div onclick="translateToUrdu()" class="dropdown-item">Urdu</div></li>
                            <li><div onclick="resetTranslation()" class="dropdown-item">English</div></li>
                        <li><div onclick="translateToPashto()" class="dropdown-item">Pashto</div></li>
                        <li><div onclick="translateToSindhi()" class="dropdown-item">Sindhi</div></li>


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
<script>
    function storechat(){
        const chatHistory = document.getElementById("chatbot-messages");
        if (!chatHistory) return;
        const chatHistoryContent = chatHistory.innerHTML;
        localStorage.setItem("chatBackup", chatHistoryContent);
    }
     function translateToUrdu() {
        storechat();
        // Set cookie so Google knows the translation preference
        document.cookie = "googtrans=/en/ur; path=/";
        location.reload();
    }
    function translateToPashto() {
        storechat();
        // Set cookie so Google knows the translation preference
        document.cookie = "googtrans=/en/ps; path=/";
        location.reload();
    }
    function translateToSindhi() {
        storechat();
        // Set cookie so Google knows the translation preference
        document.cookie = "googtrans=/en/sd; path=/";
        location.reload();
    }
  
    function resetTranslation() {
        storechat();
        const iframe = document.querySelector('iframe.goog-te-banner-frame');
        if (iframe) iframe.remove(); // Just in case it’s still in DOM

        document.cookie = "googtrans=/en/en; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
        location.reload();
    }
</script>
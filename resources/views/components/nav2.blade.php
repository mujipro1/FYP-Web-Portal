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
                        <li class="nav-item">
                            <a id='services' class="nav-link" href="/services">{{__('messages.services')}}</a>
                        </li>
                        <li class="nav-item">
                            <a id='contact' class="nav-link" href="/contact" style="margin-right:35px;">{{__('messages.contact')}}</a>
                        </li>
                        <li><div onclick="translateToUrdu()" class="dropdown-item">Urdu</div></li>
                        <li><div onclick="resetTranslation()" class="dropdown-item">English</div></li>
                        <li><div onclick="translateToPashto()" class="dropdown-item">Pashto</div></li>
                        <li><div onclick="translateToSindhi()" class="dropdown-item">Sindhi</div></li>

                    </ul>
                </div>
            </div>
        </nav>
    </div>
</div>

<script>
     function translateToUrdu() {
        // Set cookie so Google knows the translation preference
        document.cookie = "googtrans=/en/ur; path=/";
        location.reload();
    }
    function translateToPashto() {
        // Set cookie so Google knows the translation preference
        document.cookie = "googtrans=/en/ps; path=/";
        location.reload();
    }
    function translateToSindhi() {
        // Set cookie so Google knows the translation preference
        document.cookie = "googtrans=/en/sd; path=/";
        location.reload();
    }
 
    function resetTranslation() {
        const iframe = document.querySelector('iframe.goog-te-banner-frame');
        if (iframe) iframe.remove(); // Just in case it’s still in DOM

        document.cookie = "googtrans=/en/en; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
        location.reload();
    }
</script>
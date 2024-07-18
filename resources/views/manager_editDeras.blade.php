<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <link rel="stylesheet" href="{{ asset('bootstrap/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('bootstrap/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/navBar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/superadmin.css') }}">
</head>
<script>
const derasData = @json($deras);
const farm_id = @json($farm_id);
</script>

<body>
    <div class="c1">

        <div class="container mb-4">
            <div id="navbar">
                @include('components.navbar')
            </div>
            @yield('content')
        </div>

        
        @if(Session::get('success'))
        <div class="alert alert-success">
            {{Session::get('success')}}
        </div>
        {{Session::forget('success')}}
        @endif

        <div class="container-fluid">
        <div class="row">
                <div class="mt-3 sidebarcol">
                    <div class="ManagerSidebar sidebar"></div>
                </div>
                <div class="overlay" id="overlay"></div>
                    <div class="col-md-10 offset-md-1 ">



                <div class="d-flex justify-content-between align-items-center my-3">
                <a href="{{ route('manager.configuration', ['farm_id' => $farm_id]) }}"
                class="back-button">
                        <svg xmlns="http://www.w3.org/2000/svg"  class='svg' viewBox="0 0 24 24" width="512" height="512"><path d="M19,10.5H10.207l2.439-2.439a1.5,1.5,0,0,0-2.121-2.122L6.939,9.525a3.505,3.505,0,0,0,0,4.95l3.586,3.586a1.5,1.5,0,0,0,2.121-2.122L10.207,13.5H19a1.5,1.5,0,0,0,0-3Z"/></svg>
                    </a>
                    <h3 class="flex-grow-1 text-center mb-0">Edit Deras</h3>
                    <div style='visibility:hidden;' class="invisible"></div>
                </div>

                    <div class="row my-3 p-4">
                        <img src="{{ asset('images/satellite.jpg') }}" alt="" class="img-fluid satellite-image">
                    </div>

                    <div class="row px-4">                    

                        <div class="col-md-6">
                            <div class="container my-2 p-2 specHighlight">
                                <div class="row align-items-center">

                                    <div class="col divx" style='cursor:pointer;'>
                                        <span>
                                            <svg xmlns="http://www.w3.org/2000/svg"  class='mx-2 svg' id="addNewSvg3" data-name="Layer 1"
                                                viewBox="0 0 24 24" width="512" height="512">
                                                <path
                                                    d="M17,12c0,.553-.448,1-1,1h-3v3c0,.553-.448,1-1,1s-1-.447-1-1v-3h-3c-.552,0-1-.447-1-1s.448-1,1-1h3v-3c0-.553,.448-1,1-1s1,.447,1,1v3h3c.552,0,1,.447,1,1Zm7-7v14c0,2.757-2.243,5-5,5H5c-2.757,0-5-2.243-5-5V5C0,2.243,2.243,0,5,0h14c2.757,0,5,2.243,5,5Zm-2,0c0-1.654-1.346-3-3-3H5c-1.654,0-3,1.346-3,3v14c0,1.654,1.346,3,3,3h14c1.654,0,3-1.346,3-3V5Z" />
                                            </svg>
                                            <strong class='py-2'>Add Deras</strong></span>
                                    </div>
                                    <div class="col-auto">
                                        <button id='drop1' class="btn">
                                            <svg xmlns="http://www.w3.org/2000/svg" id="Bold" class="up1  svg"
                                                viewBox="0 0 24 24" width="512" height="512">
                                                <path
                                                    d="M1.51,6.079a1.492,1.492,0,0,1,1.06.44l7.673,7.672a2.5,2.5,0,0,0,3.536,0L21.44,6.529A1.5,1.5,0,1,1,23.561,8.65L15.9,16.312a5.505,5.505,0,0,1-7.778,0L.449,8.64A1.5,1.5,0,0,1,1.51,6.079Z" />
                                            </svg>
                                            <svg style="display:none;" xmlns="http://www.w3.org/2000/svg" id="Bold"
                                                class="down1 svg" viewBox="0 0 24 24" width="512" height="512">
                                                <path
                                                    d="M22.5,18a1.5,1.5,0,0,1-1.061-.44L13.768,9.889a2.5,2.5,0,0,0-3.536,0L2.57,17.551A1.5,1.5,0,0,1,.449,15.43L8.111,7.768a5.505,5.505,0,0,1,7.778,0l7.672,7.672A1.5,1.5,0,0,1,22.5,18Z" />
                                            </svg>

                                        </button>
                                    </div>
                                </div>
                                <form method="POST" action="{{ route('manager.addDerasPost') }}">
                                    @csrf
                                    <input hidden type="text" id="farm_id" name="farm_id" value="{{$farm_id}}">

                                    <div class='div1 hidden'>
                                        <div class="row p-4">

                                            <!-- section seperator -->
                                            <div class="row">
                                                <div class="col">
                                                    <hr class="hr1">
                                                </div>
                                            </div>

                                            <div class="labelcontainer">
                                                <label class='form-label w-50' for="deraName">Dera Name</label>
                                                <input class='form-control' type="text" id="deraName" name="deraName" required>
                                            </div>

                                            <div class="labelcontainer">
                                                <label class='form-label w-50' for="acres">Acres</label>
                                                <input class='form-control' type="number" id="acres" name="acres" required>
                                            </div>

                                            <div class="text-center">
                                                <button type="submit" class="btn btn-brown my-4">Add</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        @if(count($deras) > 0)
                        <div class="col-md-6">
                            <div class="container my-2 p-2 specHighlight">
                                <div class="row align-items-center">

                                    <div class="col divx2" style='cursor:pointer;'>
                                        <span>
                                            <svg class='mx-2 svg' xmlns="http://www.w3.org/2000/svg" id="Bold" fill='brown'
                                                    data-name="Layer 1" viewBox="0 0 24 24" width="512" height="512">
                                                    <path
                                                        d="M22.73,19.05l-.98-.55c.15-.48,.26-.98,.26-1.5s-.1-1.03-.26-1.5l.98-.55c.48-.27,.65-.88,.39-1.36-.27-.48-.88-.66-1.36-.39l-.98,.55c-.71-.82-1.67-1.42-2.77-1.65v-1.1c0-.55-.45-1-1-1s-1,.45-1,1v1.1c-1.1,.22-2.06,.83-2.77,1.65l-.98-.55c-.48-.27-1.09-.1-1.36,.39-.27,.48-.1,1.09,.39,1.36l.98,.55c-.15,.48-.26,.98-.26,1.5s.1,1.03,.26,1.5l-.98,.55c-.48,.27-.65,.88-.39,1.36,.18,.33,.52,.51,.87,.51,.17,0,.33-.04,.49-.13l.98-.55c.71,.82,1.67,1.42,2.77,1.65v1.1c0,.55,.45,1,1,1s1-.45,1-1v-1.1c1.1-.22,2.06-.83,2.77-1.65l.98,.55c.15,.09,.32,.13,.49,.13,.35,0,.69-.18,.87-.51,.27-.48,.1-1.09-.39-1.36Zm-5.73,.95c-1.65,0-3-1.35-3-3s1.35-3,3-3,3,1.35,3,3-1.35,3-3,3Zm-6.23-9.75l.98,.55c.15,.09,.32,.13,.49,.13,.35,0,.69-.18,.87-.51,.27-.48,.1-1.09-.39-1.36l-.98-.55c.15-.48,.26-.98,.26-1.5s-.1-1.03-.26-1.5l.98-.55c.48-.27,.65-.88,.39-1.36-.27-.48-.88-.66-1.36-.39l-.98,.55c-.71-.82-1.67-1.42-2.77-1.65V1c0-.55-.45-1-1-1s-1,.45-1,1v1.1c-1.1,.22-2.06,.83-2.77,1.65l-.98-.55c-.48-.27-1.09-.1-1.36,.39-.27,.48-.1,1.09,.39,1.36l.98,.55c-.15,.48-.26,.98-.26,1.5s.1,1.03,.26,1.5l-.98,.55c-.48,.27-.65,.88-.39,1.36,.18,.33,.52,.51,.87,.51,.17,0,.33-.04,.49-.13l.98-.55c.71,.82,1.67,1.42,2.77,1.65v1.1c0,.55,.45,1,1,1s1-.45,1-1v-1.1c1.1-.22,2.06-.83,2.77-1.65Zm-3.77-.25c-1.65,0-3-1.35-3-3s1.35-3,3-3,3,1.35,3,3-1.35,3-3,3Z" />
                                                </svg>
                                            <strong class='py-2'>Edit Deras</strong></span>
                                    </div>
                                    <div class="col-auto">
                                        <button id='drop2' class="btn">
                                            <svg xmlns="http://www.w3.org/2000/svg" id="Bold" class="up2 svg"
                                                viewBox="0 0 24 24" width="512" height="512">
                                                <path
                                                    d="M1.51,6.079a1.492,1.492,0,0,1,1.06.44l7.673,7.672a2.5,2.5,0,0,0,3.536,0L21.44,6.529A1.5,1.5,0,1,1,23.561,8.65L15.9,16.312a5.505,5.505,0,0,1-7.778,0L.449,8.64A1.5,1.5,0,0,1,1.51,6.079Z" />
                                            </svg>
                                            <svg style="display:none;" xmlns="http://www.w3.org/2000/svg" id="Bold"
                                                class="down2 svg" viewBox="0 0 24 24" width="512" height="512">
                                                <path
                                                    d="M22.5,18a1.5,1.5,0,0,1-1.061-.44L13.768,9.889a2.5,2.5,0,0,0-3.536,0L2.57,17.551A1.5,1.5,0,0,1,.449,15.43L8.111,7.768a5.505,5.505,0,0,1,7.778,0l7.672,7.672A1.5,1.5,0,0,1,22.5,18Z" />
                                            </svg>

                                        </button>
                                    </div>
                                </div>
                                <form  method="POST" action="{{ route('manager.editDerasPost') }}">
                                    @csrf
                                    <input hidden type="text" id="farm_id" name="farm_id" value="{{$farm_id}}">
                                    <div class='div2 hidden'>
                                        <div class="row  p-4">

                                            <!-- section seperator -->
                                            <div class="row">
                                                <div class="col">
                                                    <hr class="hr1">
                                                </div>
                                            </div>

                                            <div class="labelcontainer">
                                                <label class='form-label w-50' for="deraName">Select Dera</label>
                                                <select class="form-select" aria-label="Default select example" id="deraDropDown" name="deraDropDown">
                                                    <option selected>Select Dera</option>
                                                    @foreach($deras as $dera)
                                                    <option value='{{$dera->id}}'>{{$dera->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="labelcontainer">
                                                <label class='form-label w-50' for="deraNameEdit">Dera Name</label>
                                                <input class='form-control' type="text" id="deraNameEdit" name="deraNameEdit" required>
                                            </div>

                                            <div class="labelcontainer">
                                                <label class='form-label w-50' for="acres">Acres</label>
                                                <input class='form-control' type="number" id="addacres" name="acres" value='0'
                                                >
                                            </div>
                                            <div class="text-center">
                                                <button type="submit" class="btn btn-brown my-4">Save</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        @endif
                    </div>



                </div>
            </div>
        </div>


        <div id="footer">
            @include('components.footer')
        </div>


    </div>

</body>
<script src="{{ asset('js/alert.js') }}"></script>
<script src="{{ asset('js/ManagerSidebar.js') }}"></script>
<script src="{{ asset('bootstrap/bootstrap.bundle.js') }}"></script>
<script src="{{ asset('bootstrap/bootstrap.bundle.min.js') }}"></script>
<script>
divx1 = document.querySelector(".divx");

up1 = document.querySelector(".up1");
down1 = document.querySelector(".down1");
div1 = document.querySelector(".div1");
drop1 = document.querySelector("#drop1");

divx1.addEventListener('click', dropDown1);
drop1.addEventListener('click', dropDown1);

function dropDown1() {
    if (div1.classList.contains('hidden')) {
        div1.classList.remove('hidden');
        div1.classList.add('shown');
        up1.style.display = 'none';
        down1.style.display = 'block';
    } else {
        div1.classList.remove('shown');
        div1.classList.add('hidden');
        up1.style.display = 'block';
        down1.style.display = 'none';
    }
}
@if (count($deras) > 0)
up2 = document.querySelector(".up2");
drop2 = document.querySelector("#drop2");
down2 = document.querySelector(".down2");
div2 = document.querySelector(".div2");
divx2 = document.querySelector(".divx2");

divx2.addEventListener('click', dropDown2);
drop2.addEventListener('click', dropDown2);


function dropDown2() {
    if (div2.classList.contains('hidden')) {
        div2.classList.remove('hidden');
        div2.classList.add('shown');
        up2.style.display = 'none';
        down2.style.display = 'block';
    } else {
        div2.classList.remove('shown');
        div2.classList.add('hidden');
        up2.style.display = 'block';
        down2.style.display = 'none';
    }
}

// add event listener to dera drop down , change acres

deraDropDown = document.querySelector("#deraDropDown");
deraDropDown.addEventListener('change', changeAcres);

function changeAcres() {
    acres = document.querySelector("#addacres");
    deraNameEdit = document.querySelector("#deraNameEdit");
    val =  derasData[deraDropDown.selectedIndex-1]['number_of_acres']
    name = derasData[deraDropDown.selectedIndex-1]['name']
    deraNameEdit.value = name;
    acres.value = val;
}
@endif

</script>

</html>
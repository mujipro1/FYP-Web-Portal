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
    <script>
        farm_id = @json($farm['id']);
    </script>
</head>

<body>
    <div class="c1">

    <div class="container mb-4">
        <div id="navbar">
        @include('components.navbar')
        </div>
        @yield('content')
    </div>


        <div class="container-fluid">
        <div class="row">
                <div class="mt-3 sidebarcol">
                    <div class="ManagerSidebar sidebar"></div>
                </div>
                <div class="overlay" id="overlay"></div>
                    <div class="col-md-10 offset-md-1 ">


                    <div class="container">
                        <div class="row">


                            <div class="d-flex justify-content-between align-items-center my-3">
                                <a href="{{ route('manager.farms') }}" class="back-button">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="512" height="512"><path d="M19,10.5H10.207l2.439-2.439a1.5,1.5,0,0,0-2.121-2.122L6.939,9.525a3.505,3.505,0,0,0,0,4.95l3.586,3.586a1.5,1.5,0,0,0,2.121-2.122L10.207,13.5H19a1.5,1.5,0,0,0,0-3Z"/></svg>
                                </a>
                                <h3 class="flex-grow-1 text-center mb-0">{{$farm['name']}}</h3>
                                <div style='visibility:hidden;' class="invisible"></div>
                            </div>



                            <div class="col-md-7 p-3">
                                <div class="box-conts px-4">
                                    <h4 class='text-start mb-2 light'>Farm Details</h4>
                                    <div class="row px-2">
                                        <div class="col-md-6 text-start">
                                            <label class='light'>Name</label>
                                        </div>
                                        <div class="col-md-6 text-start">
                                            <label>{{$farm['name']}}</label>
                                        </div>
                                    </div>

                                    <div class="row px-2">
                                        <div class="col-md-6 text-start">
                                            <label class='light'>City</label>
                                        </div>
                                        <div class="col-md-6 text-start">
                                            <label>{{$farm['city']}}</label>
                                        </div>
                                    </div>

                                    <div class="row px-2">
                                        <div class="col-md-6 text-start">
                                            <label class='light'>Address</label>
                                        </div>
                                        <div class="col-md-6 text-start">
                                            <label>{{$farm['address']}}</label>
                                        </div>
                                    </div>


                                    <div class="row px-2">
                                        <div class="col-md-6 text-start">
                                            <label class='light'>Acres</label>
                                        </div>
                                        <div class="col-md-6 text-start">
                                            <label>{{$farm['number_of_acres']}}</label>
                                        </div>
                                    </div>

                                    <div class="row px-2">
                                        <div class="col-md-6 text-start">
                                            <label class='light'>Crops</label>
                                        </div>
                                        <div class="col-md-6 text-start">
                                            <label>{{$farm->crops->count() }} </label>  
                                        </div>
                                    </div>

                                    <div class="row px-2">
                                        <div class="col-md-6 text-start">
                                            <label class='light'>Has Deras</label>
                                        </div>
                                        <div class="col-md-6 text-start">
                                            <label>{{ $farm['has_deras'] ? 'Yes' : 'No' }}</label>
                                        </div>
                                    </div>

                                    <!-- deras -->
                                    @if($farm['has_deras'])
                                    <div class="row px-2">
                                        <div class="col-md-6 text-start">
                                            <label class='light'>Deras</label>
                                        </div>
                                        <div class="col-md-6 text-start">
                                            <label>{{$farm->deras->count()}}</label>
                                        </div>
                                    </div>
                                    @endif


                                    <div class="row px-2">
                                        <div class="col-md-6 text-start">
                                            <label class='light'>Workers</label>
                                        </div>
                                        <div class="col-md-6 text-start">
                                            <label>2</label>
                                        </div>
                                    </div>

                                    <div class="row px-2 my-3">
                                        <div class="col-md-6 my-2 text-start">
                                            <label class='light'>Farm Configuration</label>
                                        </div>
                                        <div class="col-md-6 text-start">
                                            <input hidden name="farm_id" value="{{$farm['id']}}">
                                            <button class="btn btn-primary" style='width: 70px;' onclick='handleConfiguration()'>
                                                <svg xmlns="http://www.w3.org/2000/svg" id="configuration" fill='white'
                                                    data-name="Layer 1" viewBox="0 0 24 24" width="512" height="512">
                                                    <path
                                                        d="M22.73,19.05l-.98-.55c.15-.48,.26-.98,.26-1.5s-.1-1.03-.26-1.5l.98-.55c.48-.27,.65-.88,.39-1.36-.27-.48-.88-.66-1.36-.39l-.98,.55c-.71-.82-1.67-1.42-2.77-1.65v-1.1c0-.55-.45-1-1-1s-1,.45-1,1v1.1c-1.1,.22-2.06,.83-2.77,1.65l-.98-.55c-.48-.27-1.09-.1-1.36,.39-.27,.48-.1,1.09,.39,1.36l.98,.55c-.15,.48-.26,.98-.26,1.5s.1,1.03,.26,1.5l-.98,.55c-.48,.27-.65,.88-.39,1.36,.18,.33,.52,.51,.87,.51,.17,0,.33-.04,.49-.13l.98-.55c.71,.82,1.67,1.42,2.77,1.65v1.1c0,.55,.45,1,1,1s1-.45,1-1v-1.1c1.1-.22,2.06-.83,2.77-1.65l.98,.55c.15,.09,.32,.13,.49,.13,.35,0,.69-.18,.87-.51,.27-.48,.1-1.09-.39-1.36Zm-5.73,.95c-1.65,0-3-1.35-3-3s1.35-3,3-3,3,1.35,3,3-1.35,3-3,3Zm-6.23-9.75l.98,.55c.15,.09,.32,.13,.49,.13,.35,0,.69-.18,.87-.51,.27-.48,.1-1.09-.39-1.36l-.98-.55c.15-.48,.26-.98,.26-1.5s-.1-1.03-.26-1.5l.98-.55c.48-.27,.65-.88,.39-1.36-.27-.48-.88-.66-1.36-.39l-.98,.55c-.71-.82-1.67-1.42-2.77-1.65V1c0-.55-.45-1-1-1s-1,.45-1,1v1.1c-1.1,.22-2.06,.83-2.77,1.65l-.98-.55c-.48-.27-1.09-.1-1.36,.39-.27,.48-.1,1.09,.39,1.36l.98,.55c-.15,.48-.26,.98-.26,1.5s.1,1.03,.26,1.5l-.98,.55c-.48,.27-.65,.88-.39,1.36,.18,.33,.52,.51,.87,.51,.17,0,.33-.04,.49-.13l.98-.55c.71,.82,1.67,1.42,2.77,1.65v1.1c0,.55,.45,1,1,1s1-.45,1-1v-1.1c1.1-.22,2.06-.83,2.77-1.65Zm-3.77-.25c-1.65,0-3-1.35-3-3s1.35-3,3-3,3,1.35,3,3-1.35,3-3,3Z" />
                                                </svg>
                                            </button>
                                    </div>
                                    </div>
                                </div>
                                <div class="box-cont mt-5">
                                    <div class="row">
                                        <div class="text-start">
                                            <h4 class='light'>Crops</h4>
                                        </div>

                                        @foreach($farm->crops as $crop)
                                        @if ($crop['active'] == '1')
                                        <div class="col-md-6 my-2">
                                            <div class="selected-crop" style="backgroundColor:#f1f1f1" onclick="handleCropClick('{{$crop['id']}}')">
                                                <img src="{{asset('images/crops/'. str_replace(' ', '', $crop['name']) .'.jpg')}}" class="selected-crop-image" />
                                                <div class="d-flex justify-content-between">
                                                    <h5 class="m-2 my-3">{{$crop['identifier']}}</h5>
                                                    <div class="greenCircle mx-2"></div>
                                                </div>
                                                <div class="light mx-2 fsmall">{{$crop['status']}}</div>
                                            </div>
                                        </div>
                                        @endif
                                        @endforeach
                                        @foreach($farm->crops as $crop)
                                        @if ($crop['active'] == '0')
                                        <div class="col-md-6 my-2">
                                            <div class="selected-crop" style="backgroundColor:#f1f1f1" onclick="handleCropClick('{{$crop['id']}}')">
                                                <img src="{{asset('images/crops/'. str_replace(' ', '', $crop['name']) .'.jpg')}}" class="selected-crop-image" />
                                                <div class="d-flex justify-content-between">
                                                    <h5 class="m-2 light my-3">{{$crop['identifier']}}</h5>
                                                    <div class="redCircle mx-2"></div>
                                                </div>
                                                <div class="light mx-2 fsmall">{{$crop['status']}}</div>
                                            </div>
                                        </div>
                                        @endif
                                        @endforeach

                                        @if (count($farm->crops) == 0)
                                        <div class="mx-3 text-center">
                                            <p class=''>No crops configured yet</p>
                                        </div>
                                        @endif

                                    </div>
                                </div>
                            </div>


                            <div class="col-md-5 p-3">
                                <div class="piccont">
                                    <img src="{{asset('images/satellite.png')}}"  />
                                </div>

                                <div class="box-cont p-3 mt-5">
                                    <div class="">
                                        <div class="card-body row p-3">
                                            <div class="col-md-8">
                                                <h5 class="card-title">Expense</h5>
                                                <p class="mt-2 card-text">Add or View your farm's and crop's expenses</p>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="text-end d-flex justify-content-center align-items-end h-100 flex-column">
                                                        <button class="btn btn-brown" style="width:70px;" onclick='handleExpenseClick()'>
                                                        <svg xmlns="http://www.w3.org/2000/svg" id="configuration" viewBox="0 0 24 24" width="512" height="512"><path d="M18,12h0a2,2,0,0,0-.59-1.4l-4.29-4.3a1,1,0,0,0-1.41,0,1,1,0,0,0,0,1.42L15,11H5a1,1,0,0,0,0,2H15l-3.29,3.29a1,1,0,0,0,1.41,1.42l4.29-4.3A2,2,0,0,0,18,12Z"/></svg>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- seperatpr -->
                                    <div class="my-3">
                                        <hr />
                                    </div>

                                    <div class="mt-3">
                                        <div class="card-body row p-3">
                                            <div class="col-md-8">
                                                <h5 class="card-title">Sales</h5>
                                                <p class="mt-2 card-text">Add or View your farm's and crop's sales</p>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="text-end  d-flex justify-content-center align-items-end h-100 flex-column">
                                                    <a href="#" class="btn btn-brown" style="width:70px;"> 
                                                        <svg xmlns="http://www.w3.org/2000/svg" id="configuration" viewBox="0 0 24 24" width="512" height="512"><path d="M18,12h0a2,2,0,0,0-.59-1.4l-4.29-4.3a1,1,0,0,0-1.41,0,1,1,0,0,0,0,1.42L15,11H5a1,1,0,0,0,0,2H15l-3.29,3.29a1,1,0,0,0,1.41,1.42l4.29-4.3A2,2,0,0,0,18,12Z"/></svg>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="box-cont mt-4">
                                    <div class="text-start">
                                        <h4 class='light px-2'>Workers</h4>
                                        @foreach($workers as $worker)

                                        <div class="my-2 popular-crop w-100 d-flex justify-content-start align-items-center px-2 py-2">
                                            <img src="{{asset('images/profile.jpg')}}" id='profile-image'
                                                class='mx-2' />
                                            <div class='mx-3'>
                                                <div class="">{{$worker->name}}</div>
                                                <div class=" light fsmall">{{$worker->role == 'expense_farmer' ? 'Expense Worker' : 'Sales Worker'}} </div>
                                            </div>
                                        </div>
                                        @endforeach

                                        <div class="card-body row p-3">
                                            <div class="col-md-8">
                                                <p class="mt-2 card-text">Manage your workers</p>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="text-end d-flex justify-content-center align-items-end h-100 flex-column">
                                                        <button class="btn btn-brown" style="width:70px;" onclick='handleWorkerClick()'>
                                                        <svg xmlns="http://www.w3.org/2000/svg" id="configuration" viewBox="0 0 24 24" width="512" height="512"><path d="M18,12h0a2,2,0,0,0-.59-1.4l-4.29-4.3a1,1,0,0,0-1.41,0,1,1,0,0,0,0,1.42L15,11H5a1,1,0,0,0,0,2H15l-3.29,3.29a1,1,0,0,0,1.41,1.42l4.29-4.3A2,2,0,0,0,18,12Z"/></svg>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
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
    function handleConfiguration() {
        window.location.href = "{{ route('manager.configuration' , ['farm_id' => $farm['id']] )}}"
    }
    function handleExpenseClick() {
        window.location.href = "{{ route('manager.render_cropexpense' , ['farm_id' => $farm['id'] ,'worker'=> 0 ])}}"
    }
    function handleWorkerClick() {
        window.location.href = "{{ route('manager.render_workers' , ['farm_id' => $farm['id']] )}}"
    }
    function handleCropClick(crop_id) {
        window.location.href = "{{ route('manager.cropdetails' , ['farm_id' => $farm['id'], 'crop_id' => ':crop_id'] )}}".replace(':crop_id', crop_id);
    }
</script>
</html>
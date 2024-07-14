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
        farm_id = @json($farm_id);
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

        @if(Session::get('success'))
        <div class="alert alert-success">
            {{Session::get('success')}}
        </div>
        {{Session::forget('success')}}
        @endif


        <!-- Modal -->
    <div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="cropModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmationModalLabel">Enter Crop Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to proceed?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="confirmationForm" method="POST" action="">
                @csrf
                <input type="hidden" name='farm_id' value='{{$farm_id}}'>
                <button type="submit" class="btn btn-primary">Confirm</button>
                </form>
            </div>
        </div>
    </div>
</div>





        <div class="container-fluid">
        <div class="row">
                <div class="mt-3 sidebarcol">
                    <div class="ManagerSidebar sidebar"></div>
                </div>
                <div class="overlay" id="overlay"></div>
                    <div class="col-md-10 section offset-md-1 ">


                    <div class="container">

                        <div class="d-flex justify-content-between align-items-center my-3">
                            <a href="{{ route('manager.farmdetails', ['farm_id' => $farm_id]) }}" class="back-button">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="512" height="512">
                                    <path
                                        d="M19,10.5H10.207l2.439-2.439a1.5,1.5,0,0,0-2.121-2.122L6.939,9.525a3.505,3.505,0,0,0,0,4.95l3.586,3.586a1.5,1.5,0,0,0,2.121-2.122L10.207,13.5H19a1.5,1.5,0,0,0,0-3Z" />
                                </svg>
                            </a>
                            <h3 class="flex-grow-1 text-center mb-0">Workers</h3>
                            <div style='visibility:hidden;' class="invisible"></div>
                        </div>


                        <div class="row my-5">
                            <div class="col-md-4 offset-md-1">
                                @foreach($workers as $worker)
                                <div class="worker-cont mb-3 p-2">
                                    <div class="my-2 w-100 d-flex justify-content-start align-items-center px-2 py-2">
                                        <img src="{{asset('images/profile.jpg')}}" id='profile-image'
                                            class='mx-2' />
                                        <div class='mx-3'>
                                            <div class=""> {{$worker->name}} </div>
                                            <div class=" light fsmall"> {{$worker->role == 'expense_farmer' ? 'Expense Worker' : 'Sales Worker'}} </div>
                                        </div>
                                    </div>
                                    
                                    <div class=" pt-2 p-1 px-4">
                                        <svg xmlns="http://www.w3.org/2000/svg" style='fill:#404040;transform:rotate(90deg);' class='mb-2' viewBox="0 0 24 24" width="512" height="512"><path d="M24,6.24c0,7.64-10.13,17.76-17.76,17.76-1.67,0-3.23-.63-4.38-1.78l-1-1.15c-1.16-1.16-1.16-3.12,.05-4.33,.03-.03,2.44-1.88,2.44-1.88,1.2-1.14,3.09-1.14,4.28,0l1.46,1.17c3.2-1.36,5.47-3.64,6.93-6.95l-1.16-1.46c-1.15-1.19-1.15-3.09,0-4.28,0,0,1.85-2.41,1.88-2.44,1.21-1.21,3.17-1.21,4.38,0l1.05,.91c1.2,1.19,1.83,2.75,1.83,4.42Z"/></svg>
                                        <label class='px-3 fsmall'>{{$worker->phone}}</label>
                                    </div>

                                    <div class="pb-2 px-4">
                                        <svg xmlns="http://www.w3.org/2000/svg" style='fill:#404040;' class='mb-2' viewBox="0 0 24 24" width="512" height="512"><path d="M23.954,5.542,15.536,13.96a5.007,5.007,0,0,1-7.072,0L.046,5.542C.032,5.7,0,5.843,0,6V18a5.006,5.006,0,0,0,5,5H19a5.006,5.006,0,0,0,5-5V6C24,5.843,23.968,5.7,23.954,5.542Z"/><path d="M14.122,12.546l9.134-9.135A4.986,4.986,0,0,0,19,1H5A4.986,4.986,0,0,0,.744,3.411l9.134,9.135A3.007,3.007,0,0,0,14.122,12.546Z"/></svg>
                                        <label class='px-3 fsmall'>{{$worker->email}}</label>
                                    </div>

                                    <div class=" text-center fw-bold py-2">
                                        @if ($worker->access == 1)
                                        <label class='px-3 text-success'>Access Granted</label>
                                        @else
                                        <label class='px-3 text-danger'>Access Revoked</label>
                                        @endif
                                    </div>

                                    

                                    <hr class='mx-3' />

                                    <div class="text-center my-3">
                                        <button class="btn btn-primary" onclick="confirmAction('revoke', {{$worker->id}})">
                                        @if ($worker->access == 1)  
                                        Revoke Access
                                        @else
                                        Grant Access
                                        @endif
                                        </button>
                                        <button class="btn btn-danger" onclick="confirmAction('delete', {{$worker->id}})">Delete</button>
                                    </div>

                                </div>
                                @endforeach

                            </div>
                            <div class="col-md-6 offset-md-1">
                                <div class="box-cont p-4">
                                    <div class="text-center">
                                        <h4 class="mb-5">Add Worker</h4>
                                    </div>

                                    <form action="{{route('manager.addworker')}}" method="POST">
                                        @csrf
                                        <input hidden name="farm_id" value="{{$farm_id}}">
                                        <div class="labelcontainer mt-3 px-3">
                                            <label class='w-50' for="name">Name</label>
                                            <input type="text" class="form-control" id="name" name="name" required>
                                        </div>
                                        <div class="labelcontainer px-3">
                                            <label class='w-50' for="name">Role</label>
                                            <select class="form-control" id="role" name="role" required>
                                                <option>Select Role</option>
                                                <option value="1">Expense Worker</option>
                                                <option value="2">Sales Worker</option>
                                            </select>
                                        </div>
                                        <div class="labelcontainer px-3">
                                            <label class='w-50' for="phone">Phone </label>
                                            <input type="number" class="form-control" id="phone" name="phone" required>
                                        </div>
                                        <div class="labelcontainer px-3">
                                            <label class='w-50' for="email">Email</label>
                                            <input type="email" class="form-control" id="email" name="email" required autoComplete="new-email">
                                        </div>
                                        <div class="labelcontainer px-3">
                                            <label class='w-50' for="password">Password</label>
                                            <input type="password" class="form-control" id="password" name="password" required autoComplete="new-password">
                                        </div>

                                        <div class="text-center mt-4 mb-3">
                                            <button type="submit" class="btn w-25 btn-primary">Add</button>
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
    function confirmAction(action, workerId) {
        const form = document.getElementById('confirmationForm');
        // create form field
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'worker_id';
        input.value = workerId;
        form.appendChild(input);

        // Set the form action based on the button clicked
        if (action === 'delete') {
            form.action = `/manager/delete`; // Adjust the route as needed
        } else if (action === 'revoke') {
            form.action = `/manager/revoke`; // Adjust the route as needed
        }
        
        // Show the confirmation modal
        const modal = document.getElementById('confirmationModal');
        const bootstrapModal = new bootstrap.Modal(modal);
        bootstrapModal.show();
    }

</script>
</html>
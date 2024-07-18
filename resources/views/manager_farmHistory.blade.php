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

        <div class="container-fluid">
        <div class="row">
                <div class="mt-3 sidebarcol">
                    <div class="ManagerSidebar sidebar"></div>
                </div>
                <div class="overlay" id="overlay"></div>
                    <div class="col-md-10 offset-md-1 ">


                    <div class="d-flex justify-content-between align-items-center my-3">
                        <a href="{{ route('manager.farmdetails', ['farm_id' => $farm['id']]) }}" class="back-button">
                            <svg xmlns="http://www.w3.org/2000/svg"  class='svg' viewBox="0 0 24 24" width="512" height="512">
                                <path
                                    d="M19,10.5H10.207l2.439-2.439a1.5,1.5,0,0,0-2.121-2.122L6.939,9.525a3.505,3.505,0,0,0,0,4.95l3.586,3.586a1.5,1.5,0,0,0,2.121-2.122L10.207,13.5H19a1.5,1.5,0,0,0,0-3Z" />
                            </svg>
                        </a>
                        <h3 class="flex-grow-1 text-center mb-0">Farm History</h3>
                        <div style='visibility:hidden;' class="invisible"></div>
                    </div>

                    <div class="box-cont">

                    <div class="container table-responsive">
                        <table class="table-striped table table-scroll">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Crop Name</th>
                                    <th>Stage</th>
                                    <th>Remarks</th>
                                    <th>Date</th>
                                    <th>Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cropStatusUpdates as $update)
                                    <tr>
                                        <td>{{ $loop->iteration + $cropStatusUpdates->firstItem() - 1 }}</td>
                                        <td>{{ $update->crop->name }}</td>
                                        <td>{{ $update->status }}</td>
                                        <td>{{ $update->remarks }}</td>
                                        <td>{{ \Carbon\Carbon::parse($update->updated_at)->format('d M Y') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($update->updated_at)->format('H:i') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                        <div class="pagination my-5">
                            {{ $cropStatusUpdates->links() }} <!-- Pagination links -->
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

<style>
    .update-row {
    padding: 10px;
    border-bottom: 1px solid #ddd;
}

#loading {
    text-align: center;
    padding: 20px;
    font-size: 16px;
}

.pagination {
    display: flex;
    justify-content: center;
    margin-top: 20px;
}

.pagination a{
    color: #333;
    padding: 8px 12px;
    margin: 0 4px;
    border: 1px solid #ccc;
    border-radius: 4px;
    text-decoration: none;
    transition: background-color 0.3s ease;
}

.pagination a:hover {
    background-color: #f0f0f0;
}

.pagination .page-item.active a {
    background-color: #007bff;
    color: #fff;
    border-color: #007bff;
}

.pagination a:disabled{
    pointer-events: none;
    background-color: #eee;
    color: white;
    border-color: #ccc;
}


</style>
</html>
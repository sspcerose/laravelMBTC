<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vehicle</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="//cdn.datatables.net/2.1.7/css/dataTables.dataTables.min.css">
    <style>
        body {
            padding: 30px;
        }

        .container {
            margin-top: 10px;
        }

        .table-container {
            margin-top: 30px;
        }

        .header-cell {
            padding-left: 10px;
            padding-right: 10px;
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="mb-4">
        <a href="{{ route('admin.dashboard') }}" class="bg-red-500 text-white py-2 px-4 rounded">Back</a>
    </div>
    
    <h2 class="text-center text-2xl font-bold">Vehicles</h2>

    <div class="mb-4">
        <a href="{{ url('admin/vehicle/addvehicle') }}" class="bg-green-500 text-white py-2 px-4 rounded hover:bg-green-600">Add Vehicle</a>
        <a href="{{ url('admin/vehicle/archivevehicle') }}" class="bg-red-500 text-white py-2 px-4 rounded hover:bg-red-600">Archive</a>
    </div>

    <!-- Search -->
    <div class="mb-4">
        <form action="{{ url('admin/vehicle/vehicle') }}" method="GET" class="flex">
            <input type="search" name="vehicleSearch" placeholder="Search">
            <input type="submit" value="Search">
        </form>
    </div>

    <div class="table-container mt-6">
        <table class="min-w-full bg-white border border-gray-300" id="myTable">
            <thead>
                <tr>
                    <th class="py-2 border-2 border-black header-cell">Owner</th>
                    <th class="py-2 border-2 border-black header-cell">Type</th>
                    <th class="py-2 border-2 border-black header-cell">Plate Number</th>
                    <th class="py-2 border-2 border-black header-cell">Capacity</th>
                    <th class="py-2 border-2 border-black header-cell">Action</th>
                </tr>
            </thead>
            <tbody>
                @if($viewVehicles->isEmpty())
                    <tr>
                        <td colspan="5" class="text-center py-4">No vehicles found</td>
                    </tr>
                @else
                    @foreach($viewVehicles as $viewVehicle)
                        <tr>
                            <td class="py-2 border-2 border-black header-cell">
                                {{ $viewVehicle->member->name }} {{ $viewVehicle->member->last_name }}
                            </td>
                            <td class="py-2 border-2 border-black header-cell">{{ $viewVehicle->type }}</td>
                            <td class="py-2 border-2 border-black header-cell">{{ $viewVehicle->plate_num }}</td>
                            <td class="py-2 border-2 border-black header-cell">{{ $viewVehicle->capacity }}</td>
                            <td class="py-2 border-2 border-black header-cell">
                                <a href="{{ url('admin/vehicle/updatevehicle/' . $viewVehicle->id) }}" class="bg-yellow-500 text-white py-2 px-4 rounded hover:bg-yellow-600">Update</a>
                                <form action="{{ url('admin/vehicle/' . $viewVehicle->id . '/archive') }}" method="POST" class="inline-block">
                                    @csrf
                                    <input type="hidden" name="action" value="archive">
                                    <input type="hidden" name="archive" value="1">
                                    <button type="submit" class="bg-red-500 text-white py-2 px-4 rounded hover:bg-red-600">Archive</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="//cdn.datatables.net/2.1.7/js/dataTables.min.js"></script>

<script>
    let table = new DataTable('#myTable');
</script>
</body>
</html>

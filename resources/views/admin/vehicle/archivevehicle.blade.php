<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Archived Vehicles</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .container {
            margin-top: 50px;
        }

        .table-container {
            margin-top: 30px;
        }

        .header-cell {
            background-color: #f8f9fa;
            padding: 10px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2 class="text-center text-2xl font-bold">Archived Vehicles</h2>

        <a href="{{ url('admin/vehicle/vehicle') }}" class="btn btn-danger mb-4">Back</a>

        <div>
            <form action="{{ url('admin/vehicle/archivevehicle') }}" method="GET" class="mb-4">
                <input type="search" name="vehicleSearch" placeholder="Search">
                <input type="submit" value="Search" class="btn btn-primary">
            </form>
        </div>

        <div class="table-container">
            <table class="min-w-full bg-white border border-gray-200">
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
                                <td class="py-2 border-2 border-black">{{ $viewVehicle->member->name }} {{ $viewVehicle->member->last_name }}</td>
                                <td class="py-2 border-2 border-black">{{ $viewVehicle->type }}</td>
                                <td class="py-2 border-2 border-black">{{ $viewVehicle->plate_num }}</td>
                                <td class="py-2 border-2 border-black">{{ $viewVehicle->capacity }}</td>
                                <td class="py-2 border-2 border-black">
                                    <form action="{{ url('admin/vehicle/archivevehicle/' . $viewVehicle->id) }}" method="POST" class="inline-block">
                                        @csrf
                                        <input type="hidden" name="action" value="archive">
                                        <input type="hidden" name="archive" value="1">
                                        <button type="submit" class="bg-red-500 text-white py-2 px-4 rounded">Unarchive</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>

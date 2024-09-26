<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tariffs</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
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
        <!-- Back Button -->
        <div class="mb-4">
            <a href="{{ route('admin.dashboard') }}" class="bg-red-500 text-white py-2 px-4 rounded">Back</a>
        </div>

        <!-- Page Header -->
        <h2 class="text-center text-2xl font-bold">Tariffs</h2>
        <h4 class="text-center text-xl font-semibold">San Jose City To:</h4>

        <!-- Add Tariff and Archive Buttons -->
        <div class="mb-4">
            <a href="{{ url('admin/tariff/addtariff') }}" class="bg-green-500 text-white py-2 px-4 rounded hover:bg-green-600">Add Tariff</a>
            <a href="{{ url('admin/tariff/archivetariff') }}" class="bg-red-500 text-white py-2 px-4 rounded hover:bg-red-600">Archive</a>
        </div>

        <!-- Search Form -->
        <div>
            <form action="{{ url('admin/tariff/tariff') }}" method="GET">
                <input type="search" name="tariffSearch" placeholder="Search">
                <input type="submit" value="Search">
            </form>
        </div>

        <!-- Tariff Table -->
        <div class="table-container">
            <table class="min-w-full bg-white" id="myTable">
                <thead>
                    <tr>
                        <th class="py-2 border-2 border-black header-cell">Destination</th>
                        <th class="py-2 border-2 border-black header-cell">Rate</th>
                        <th class="py-2 border-2 border-black header-cell">Succeeding</th>
                        <th class="py-2 border-2 border-black header-cell">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if($viewtariffs->isEmpty())
                    <tr>
                        <td colspan="4" class="text-center py-4">No tariffs found</td>
                    </tr>
                    @else
                    @foreach($viewtariffs as $viewtariff)
                    <tr>
                        <td class="py-2 border-2 border-black header-cell">{{ $viewtariff->destination }}</td>
                        <td class="py-2 border-2 border-black header-cell">{{ $viewtariff->rate }}</td>
                        <td class="py-2 border-2 border-black header-cell">{{ $viewtariff->succeeding }}</td>
                        <td class="py-2 border-2 border-black header-cell">
                            <!-- Update Button -->
                            <a href="{{ url('admin/tariff/updatetariff/' . $viewtariff->id) }}" class="bg-yellow-500 text-white py-2 px-4 rounded hover:bg-yellow-600">Update</a>

                            <!-- Archive Button -->
                            <form action="{{ url('admin/tariff/' . $viewtariff->id . '/archive') }}" method="POST" class="inline-block">
                                @csrf
                                <input type="hidden" name="action" value="archive">
                                <input type="hidden" name="archive" value="1">
                                <button type="submit" class="bg-red-500 text-white py-2 px-4 rounded">Archive</button>
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
    <script src="//cdn.datatables.net/2.1.7/js/dataTables.min.js"></script>
     <script>
        let table = new DataTable('#myTable');
     </script>   
</body>

</html>

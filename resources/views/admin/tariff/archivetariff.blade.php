<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tariffs</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
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
        <h2 class="text-center text-2xl font-bold">Archive Tariffs</h2>
        <div class="mb-4">
            <a href="{{ url('admin/tariff/tariff') }}" class="btn btn-danger">Back</a>
        </div>

        <div>
            <br>
            <form action="{{ url('admin/tariff/archivetariff') }}" method="GET">
                <input type="search" name="tariffSearch" placeholder="Search">
                <input type="submit" value="Search">
            </form>
        </div>

        <div class="table-container">
            <table class="min-w-full bg-white">
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
                            <td colspan="4">No tariffs found</td>
                        </tr>
                    @else
                        @foreach($viewtariffs as $viewtariff)
                            <tr>
                                <td class="py-2 border-2 border-black header-cell">{{ $viewtariff->destination }}</td>
                                <td class="py-2 border-2 border-black header-cell">{{ $viewtariff->rate }}</td>
                                <td class="py-2 border-2 border-black header-cell">{{ $viewtariff->succeeding }}</td>
                                <td class="py-2 border-2 border-black header-cell">
                                    <form action="{{ url('admin/tariff/archivetariff/' . $viewtariff->id) }}" method="POST" style="display:inline-block;">
                                        @csrf
                                        <button type="submit" class="btn btn-danger">Unarchive</button>
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

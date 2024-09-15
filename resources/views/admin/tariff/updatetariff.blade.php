<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Tariff</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .container {
            margin-top: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        h2 {
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Back Button -->
        <div class="mb-4">
            <a href="{{ route('admin.tariff.tariff') }}" class="bg-red-500 text-white py-2 px-4 rounded">Back</a>
        </div>

        <!-- Update Tariff Form -->
        <h2 class="text-center">Update Tariff</h2>
        <form method="POST" action="{{ url('admin/tariff/updatetariff/' . $viewtariffs->id) }}">
            @csrf
            <div class="form-group">
                <label for="destination">Destination:</label>
                <input type="text" id="destination" name="destination" class="form-control" required value="{{ $viewtariffs->destination }}">
            </div>
            <div class="form-group">
                <label for="rate">Rate:</label>
                <input type="number" id="rate" name="rate" class="form-control" required value="{{ $viewtariffs->rate }}">
            </div>
            <div class="form-group">
                <label for="succeeding">Succeeding:</label>
                <input type="number" id="succeeding" name="succeeding" class="form-control" required value="{{ $viewtariffs->succeeding }}">
            </div>

            <button type="submit" class="bg-yellow-500 text-white py-2 px-4 rounded">Update Tariff</button>
        </form>
    </div>
</body>

</html>

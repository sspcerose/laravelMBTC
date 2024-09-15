<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tariffs</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f8f9fa;
            padding: 20px;
        }

        .container {
            margin-top: 10px;
        }

        .table-container {
            margin-top: 30px;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="mb-4">
            <a href="{{ route('admin.tariff.tariff') }}" class="bg-red-500 text-white py-2 px-4 rounded">Back</a>
        </div>

        <h2 class="text-center text-2xl font-bold">Add Tariff</h2>
        <form action="{{ url('admin/tariff/addtariff') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="destination">Destination:</label>
                <input type="text" id="destination" name="destination" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="rate">Rate:</label>
                <input type="number" id="rate" name="rate" class="form-control" required>
            </div>

            <div class="mb-4">
                <label for="succeeding">Succeeding:</label>
                <input type="number" id="succeeding" name="succeeding" class="form-control" required>
            </div>

            <input type="hidden" id="status" value="active" name="status">
            <button type="submit" class="btn btn-success">Add Tariff</button>
        </form>
    </div>

</body>

</html>

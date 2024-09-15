<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Vehicle</title>
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
            <a href="{{ route('admin.vehicle.vehicle') }}" class="bg-red-500 text-white py-2 px-4 rounded">Back</a>
        </div>

        <!-- Add Vehicle Form -->
        <h2 class="text-center text-2xl font-bold">Add Vehicle</h2>
        <form action="{{ url('admin/vehicle/addvehicle') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="member_id">Owner:</label>
                <select name="id" id="id" class="form-control" required>
                    @foreach($activeMembers as $activeMember)
                        <option value="{{ $activeMember->id }}">{{ $activeMember->name }} {{ $activeMember->last_name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="type">Type:</label>
                <input type="text" class="form-control" id="type" name="type" required>
            </div>
            <div class="form-group">
                <label for="plate_num">Plate Number:</label>
                <input type="text" class="form-control" id="plate_num" name="plate_num" required>
            </div>
            <div class="form-group">
                <label for="capacity">Capacity:</label>
                <input type="number" class="form-control" id="capacity" name="capacity" required>
            </div>
            
            <input type="hidden" id="status" value="active" name="status">

            <button type="submit" class="btn btn-primary">Add Vehicle</button>
        </form>
    </div>

</body>

</html>

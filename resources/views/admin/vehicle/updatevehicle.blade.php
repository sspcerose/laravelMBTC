<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Vehicle</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .container {
            margin-top: 50px;
        }

        .form-group {
            margin-bottom: 1rem;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="mb-4">
            <a href="{{ route('admin.vehicle.vehicle') }}" class="bg-red-500 text-white py-2 px-4 rounded hover:bg-red-600">Back</a>
        </div>

        <h2 class="text-center text-2xl font-bold">Update Vehicle</h2>
        <form method="POST" action="{{ url('admin/vehicle/updatevehicle/' . $viewVehicles->id) }}">
            @csrf

            <div class="form-group">
                <label for="member_id" class="form-label">Owner:</label>
                <select name="member_id" id="member_id" class="form-control" required>
                    @foreach($activeMembers as $activeMember)
                        <option value="{{ $activeMember->id }}" 
                            @if($viewVehicles->member_id == $activeMember->id) 
                                selected 
                            @endif>
                            {{ $activeMember->name }} {{ $activeMember->last_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="type" class="form-label">Type:</label>
                <input type="text" id="type" name="type" class="form-control"
                required value="{{ $viewVehicles->type }}">
            </div>

            <div class="form-group">
                <label for="plate_num" class="form-label">Plate Number:</label>
                <input type="text" id="plate_num" name="plate_num" class="form-control"
                required value="{{ $viewVehicles->plate_num }}">
            </div>

            <div class="form-group">
                <label for="capacity" class="form-label">Capacity:</label>
                <input type="number" id="capacity" name="capacity" class="form-control"
                required value="{{ $viewVehicles->capacity }}">
            </div>

            <input type="hidden" id="status" value="active" name="status">

            <button type="submit" class="bg-yellow-500 text-white py-2 px-4 rounded hover:bg-yellow-600">Update Vehicle</button>
        </form>
    </div>
</body>

</html>

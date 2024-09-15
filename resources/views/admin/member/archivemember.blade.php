<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Members</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            padding: 30px;
        }

        .container {
            margin-top: 50px;
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
    <!-- Navigation and Action Links -->
    <div class="container">
        <div class="mb-4">
            <a href="{{ route('admin.member.member') }}" class="bg-red-500 text-white py-2 px-4 rounded">Back</a>
        </div>

        <div class="container mx-auto">
            <h2 class="text-center text-2xl font-bold">Archived Members</h2>
        </div>

        <!-- Search Form -->
        <div class="mb-6">
            <form action="{{ url('admin/member/archivemember') }}" method="GET">
                <input type="search" name="membersearch" placeholder="Search">
                <input type="submit" value="Search">
            </form>
        </div>

        <!-- Members Table -->
        <div class="table-container">
            <table class="min-w-full bg-white">
                <thead>
                    <tr>
                        <th class="py-2 border-2 border-black header-cell">First Name</th>
                        <th class="py-2 border-2 border-black header-cell">Last Name</th>
                        <th class="py-2 border-2 border-black header-cell">TIN</th>
                        <th class="py-2 border-2 border-black header-cell">Mobile Number</th>
                        <th class="py-2 border-2 border-black header-cell">Email</th>
                        <th class="py-2 border-2 border-black header-cell">Date Joined</th>
                        <th class="py-2 border-2 border-black header-cell">Type</th>
                        <th class="py-2 border-2 border-black header-cell">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if($viewmembers->isEmpty())
                    <tr>
                        <td colspan="8" class="text-center py-4">No members found</td>
                    </tr>
                    @else
                    @foreach($viewmembers as $viewmember)
                    <tr>
                        <td class="py-2 border-2 border-black header-cell">{{ $viewmember->name }}</td>
                        <td class="py-2 border-2 border-black header-cell">{{ $viewmember->last_name }}</td>
                        <td class="py-2 border-2 border-black header-cell">{{ $viewmember->tin }}</td>
                        <td class="py-2 border-2 border-black header-cell">{{ $viewmember->mobile_num }}</td>
                        <td class="py-2 border-2 border-black header-cell">{{ $viewmember->email }}</td>
                        <td class="py-2 border-2 border-black header-cell">{{ $viewmember->date_joined }}</td>
                        <td class="py-2 border-2 border-black header-cell">{{ $viewmember->type }}</td>
                        <td class="py-2 border-2 border-black header-cell">
                            <form action="{{url('admin/member/archivemember/' . $viewmember->id)}}" method="POST" style="display:inline-block;">
                                @csrf
                                <button type="submit" class="bg-red-500 text-white py-2 px-4 rounded hover:bg-red-600">Unarchive</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>

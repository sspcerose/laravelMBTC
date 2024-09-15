<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("You're logged in!") }}
                </div>
                <div class="py-12">
                    @if($activetariffs->isEmpty())
                        <p class="form-control">I am sorry, no available destination right now</p>
                    @else
                        <h1 class="text-center">Book Now</h1>
                        <form action="{{ url('bookingform') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input hidden type="text" class="form-control" id="customer_id" name="customer_id" value="{{ Auth::user()->id }}" required>

                            <div class="form-group">
                                <label for="location">Location:</label>
                                <input type="text" class="form-control" id="location" name="location" required>
                            </div>

                            <div class="form-group">
                                <label for="tariff">Destination</label>
                                <select name="id" id="id" class="form-control">
                                    @foreach($activetariffs as $activetariff)
                                        <option value="{{ $activetariff->id }}">{{ $activetariff->destination }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="start_date">Depart:</label>
                                <input type="date" class="form-control" id="start_date" name="start_date" min="{{ date('Y-m-d') }}" required>
                            </div>

                            <div class="form-group">
                                <label for="end_date">Return:</label>
                                <input type="date" class="form-control" id="end_date" name="end_date" min="{{ date('Y-m-d') }}" required>
                            </div>

                            <div class="form-group">
                                <label for="passenger">Passengers:</label>
                                <input type="number" class="form-control" id="passenger" name="passenger" value="1" min="1" max="20" required>
                            </div>

                            <div class="form-group">
                                <label for="price">Price:</label>
                                <input type="text" class="form-control" id="price" name="price" readonly>
                            </div>

                            <div>
                                <h6>Please complete payment or down payment to confirm booking reservation at least ₱1,000</h6>

                                <!-- QR Code Button -->
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#imageModal" data-receipt="{{ asset('img/' . $qrcode->qrcode) }}">
                                    View QR Code
                                </button>

                                <!-- QR Code Modal -->
                                <div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="imageModalLabel">QR Code</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body text-center">
                                                <img id="modalImage" src="" alt="QR Code" class="img-fluid" />
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="receipt">Upload your receipt here:</label>
                                <input type="file" class="form-control-file" id="receipt" name="receipt">
                            </div>

                            <div class="form-group">
                                <input hidden type="text" class="form-control" id="status" name="status" value="active" required>
                            </div>

                            <button type="submit" class="btn btn-primary" name="book">Book</button>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#imageModal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                var receipt = button.data('receipt');
                var modalImage = document.getElementById("modalImage");
                modalImage.src = receipt;
            });
        });
    </script>

</x-app-layout>

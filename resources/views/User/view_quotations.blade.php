<x-app-layout>
    @if(session('success'))
    <div class="text-green-600">{{ session('success') }}</div>
    @endif

    <h2 class="text-xl font-bold">View Quotations</h2>

    <div class="container-fluid vh-100 d-flex justify-content-center overflow-auto">
        <div class="row row-cols-2 g-4 justify-content-center w-100">
            @foreach ($quotations as $quotation_id => $drugs)
            <div class="col-md-5">
                <div class="box p-4 border rounded bg-white shadow-sm h-auto overflow-auto">
                    <table class="table table-bordered text-center">
                        <thead class="table-light">
                            <tr>
                                <th class="fw-bold">Drug</th>
                                <th class="fw-bold">Quantity</th>
                                <th class="fw-bold">Price</th>
                                <th class="fw-bold">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($drugs as $drug)
                            <tr>
                                <td>{{ $drug->drug_name }}</td>
                                <td>{{ $drug->quantity }}</td>
                                <td>{{ $drug->price }}</td>
                                <td>{{ $drug->quantity * $drug->price }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="2"></td>
                                <td class="fw-bold">Total</td>
                                <td class="fw-bold">{{ $drugs->first()->total_amount }}</td>
                            </tr>
                        </tfoot>
                    </table>
                    <hr class="my-3">
                    <div class="container mt-2">
                        <div class="row mb-2 d-flex justify-content-between">
                            <div class="col-md-6 d-flex">
                                <span class="fw-bold">Prescription ID:</span>
                                <span class="ms-2">{{ $drugs->first()->prescription_id }}</span>
                            </div>
                            <div class="col-md-6 d-flex justify-content-end">
                                <span class="fw-bold">Time Slot:</span>
                                <span class="ms-2">{{ $drugs->first()->time_slot }}</span>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

</x-app-layout>
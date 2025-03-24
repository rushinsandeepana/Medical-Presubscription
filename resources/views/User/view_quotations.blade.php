<x-app-layout>
    @if(session('success'))
    <div class="text-green-600">{{ session('success') }}</div>
    @endif

    <h2 class="text-xl font-bold">View Quotations</h2>

    <div class="container-fluid vh-100 d-flex justify-content-center overflow-auto">
        <div class="row row-cols-2 g-4 justify-content-center w-100">
            @foreach ($quotations as $quotation_id => $drugs)
            @php
            $status = $drugs->first()->status;
            @endphp
            <div class="col-md-5">
                <div class="box p-4 border rounded bg-white shadow-sm h-auto overflow-auto">
                    <div class="d-flex justify-content-between pb-3">
                        <div>
                            <span class="fw-bold">Quotation ID:</span>
                            <span class="ms-2">{{ $drugs->first()->quotation_id }}</span>
                        </div>
                        <div>
                            <span class="fw-bold">Quotation Status:</span>
                            <span
                                class="ms-2 fw-bold {{ $drugs->first()->status == 'confirmed' ? 'text-success' : ($drugs->first()->status == 'rejected' ? 'text-danger' : '') }}">
                                {{ $drugs->first()->status }}
                            </span>
                        </div>
                    </div>

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
                    <div class="row mb-4 d-flex justify-content-between">

                        <div class="col-md-6 d-flex justify-content-start">
                            <!-- Confirm Button -->
                            <a onclick=" return confirm('Are you sure to confirm this quotation?')"
                                href="{{ route('quotation.confirm', $drugs->first()->quotation_id)}}"
                                class="btn btn-success" style="padding: 10px 20px; border-radius: 5px;">Confirm</a>
                        </div>
                        <div class="col-md-6 d-flex justify-content-end">
                            <!-- Reject Button -->
                            <a onclick=" return confirm('Are you sure to reject this quotation?')" href="
                                {{ route('quotation.reject', $drugs->first()->quotation_id)}}" class="btn btn-danger"
                                style="padding: 10px 20px; border-radius: 5px;">Reject</a>
                        </div>

                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

</x-app-layout>

<script>
function updatePrescriptionStatus(quotationId, status) {
    console.log("Quotation ID:", quotationId);

    let confirmBtn = document.getElementById("confirmBtn_" + quotationId);
    let rejectBtn = document.getElementById("rejectBtn_" + quotationId);

    console.log("Confirm Button:", confirmBtn);
    console.log("Reject Button:", rejectBtn);

    if (!confirmBtn || !rejectBtn) {
        console.error("Buttons not found for quotation ID:", quotationId);
        return;
    }

    if (status === 'confirmed') {
        confirmBtn.disabled = true;
        confirmBtn.style.backgroundColor = "#c8e6c9";
        confirmBtn.innerText = "Confirmed";
        rejectBtn.disabled = true;
    } else if (status === 'rejected') {
        rejectBtn.disabled = true;
        rejectBtn.style.backgroundColor = "#ffcdd2";
        rejectBtn.innerText = "Rejected";
        confirmBtn.disabled = true;
    }

    fetch("{{ url('/pharmacy/quotations/update-status') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({
                quotation_id: quotationId,
                status: status
            })
        })
        .then(response => response.json())
        .then(data => {
            if (!data.success) {
                console.error("Failed to update the status.");
                confirmBtn.disabled = false;
                rejectBtn.disabled = false;
            }
        })
        .catch(error => {
            console.error("Error:", error);
            confirmBtn.disabled = false;
            rejectBtn.disabled = false;
        });
}
</script>
<x-app-layout>
    @if(session('success'))
    <div class="text-green-600">{{ session('success') }}</div>
    @endif

    <h2 class="text-xl font-bold">Uploaded Prescription</h2>

    <div class="container mt-4">
        <div class="row">
            <div class="col-md-4">
                <div class="box p-4 text-black" style="border: 1px solid #ccc; height: 500px;">
                    <div class="mb-4">
                        @if(!empty($images) && count($images) > 0)
                        <div class="box p-4 text-black" style="border: 1px solid #ccc; height: 350px;">
                            <img id="largeImage" src="{{ asset('storage/' . ltrim($images[0], '/')) }}"
                                alt="Large Image" class="img-fluid border border-dark"
                                style="height: 100%; width: auto;">
                        </div>
                        @else
                        <p>No images available</p>
                        @endif
                    </div>

                    <div class="row">
                        @if(!empty($images) && count($images) > 1)
                        <div class="col-md-12">
                            <div class="row">
                                @foreach(array_slice($images, 1) as $index => $image)
                                <div class="col-md-3 mb-2">
                                    <div class="box">
                                        <img src="{{ asset('storage/' . ltrim($image, '/')) }}" alt="Small Image"
                                            class="img-fluid w-100 border border-dark small-image"
                                            style="height: 70px; width: auto; cursor: pointer;"
                                            onclick="swapImage(this)">
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-md-7">
                <div class="box p-4" style="border: 1px solid #ccc; height: 500px;">
                    <table class="table border-none text-center" id="drugTable">
                        <thead>
                            <tr>
                                <th class="fw-bold" style="width: 50%;">Drug</th>
                                <th class="fw-bold" style="width: 20%;">Quantity</th>
                                <th class="fw-bold" style="width: 30%;">Amount</th>
                            </tr>
                        </thead>
                        <tbody id="drugTableBody"></tbody>
                        <tfoot>
                            <tr>
                                <td class="fw-bold" colspan="1"></td>
                                <td class="fw-bold" colspan="1">Total</td>
                                <td class="fw-bold" id="totalAmount">0.00</td>
                            </tr>
                        </tfoot>
                    </table>

                    <div class="container mt-4">
                        <form id="drugForm">
                            <div class="row mb-3">
                                <div class="col-md-6 offset-md-6">
                                    <div class="mb-3 d-flex align-items-center">
                                        <label for="drug_id" class="form-label mr-3" style="width: 100px;">Drug:</label>
                                        <select id="drug_id" name="drug_id" class="form-control" required>
                                            <option value="" disabled selected>Select a drug</option>
                                            @foreach($drugs_details as $drug)
                                            <option value="{{ $drug->id }}" data-price="{{ $drug->price }}">
                                                {{ $drug->drug_name }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-3 d-flex align-items-center">
                                        <label for="quantity" class="form-label mr-3"
                                            style="width: 100px;">Quantity:</label>
                                        <input type="number" id="quantity" name="quantity" class="form-control"
                                            placeholder="Enter drug quantity" required>
                                    </div>
                                    <div class="d-flex justify-content-end">
                                        <button type="button" class="btn btn-primary" id="addDrug">Add</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <hr class="my-4" style="width: 100%; border: 1px solid black;">
                    <form id="sendQuotationForm">
                        @csrf
                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn btn-primary" id="sendQuotation">Send Quotation</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
function swapImage(smallImg) {
    let largeImage = document.getElementById("largeImage");
    [largeImage.src, smallImg.src] = [smallImg.src, largeImage.src];
}

document.addEventListener("DOMContentLoaded", function() {
    const drugTableBody = document.getElementById("drugTableBody");
    const totalAmountEl = document.getElementById("totalAmount");

    // Extract prescription_id from URL
    const subscriptionId = "{{ $subscriptionId }}";
    console.log('Prescription ID:', subscriptionId);
    // Clear sessionStorage on page load
    sessionStorage.removeItem("drugs");

    function updateTable() {
        drugTableBody.innerHTML = "";
        let totalAmount = 0;
        let drugs = JSON.parse(sessionStorage.getItem("drugs")) || [];

        drugs.forEach((drug, index) => {
            let row = `<tr>
                    <td>${drug.name}</td>
                    <td>${drug.quantity}</td>
                    <td>${drug.amount.toFixed(2)}</td>
                </tr>`;
            drugTableBody.innerHTML += row;
            totalAmount += drug.amount;
        });

        totalAmountEl.innerText = totalAmount.toFixed(2);
    }

    document.getElementById("addDrug").addEventListener("click", function() {
        let drugSelect = document.getElementById("drug_id");
        let quantityInput = document.getElementById("quantity");

        let selectedOption = drugSelect.options[drugSelect.selectedIndex];
        let drugName = selectedOption.text;
        let drugPrice = parseFloat(selectedOption.getAttribute("data-price"));
        let quantity = parseInt(quantityInput.value);

        if (!quantity || isNaN(drugPrice)) {
            alert("Please select a drug and enter a valid quantity.");
            return;
        }

        let drug = {
            name: drugName,
            quantity: quantity,
            amount: drugPrice * quantity,
        };

        // Store drugs in sessionStorage
        let drugs = JSON.parse(sessionStorage.getItem("drugs")) || [];
        drugs.push(drug);
        sessionStorage.setItem("drugs", JSON.stringify(drugs));

        updateTable();
        drugSelect.value = ""; // Reset selection
        quantityInput.value = ""; // Reset quantity
    });

    // Send quotation
    document.getElementById("sendQuotation").addEventListener("click", function() {
        let drugs = JSON.parse(sessionStorage.getItem("drugs")) || [];

        if (drugs.length === 0) {
            alert("Please add some drugs before sending the quotation.");
            return;
        }

        fetch(`/pharmacy/send_quotation/${subscriptionId}`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute(
                        "content"),
                },
                body: JSON.stringify({
                    drugs: drugs
                }),
            })
            .then(response => response.json())
            .then(data => {
                alert(data.success || data.error);
                sessionStorage.removeItem("drugs");
                updateTable();
            })
            .catch(error => console.error("Error:", error));
    });
});
</script>
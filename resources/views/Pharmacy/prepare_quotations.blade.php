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
                    <table class="table border-none text-center">
                        <thead>
                            <tr>
                                <th class="fw-bold" style="width: 50%;">Drug</th>
                                <th class="fw-bold" style="width: 20%;">Quantity</th>
                                <th class="fw-bold" style="width: 30%;">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $totalAmount = 0; @endphp
                            @foreach ($drugs as $drug)
                            @php
                            $amount = $drug->quantity * $drug->price;
                            $totalAmount += $amount; // Add to total
                            @endphp
                            <tr>
                                <td>{{$drug->drug_name}}</td>
                                <td>{{$drug->quantity}}</td>
                                <td>{{$drug->amount}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td class="fw-bold" colspan="1"></td>
                                <td class="fw-bold" colspan="1">Total</td>
                                <td class="fw-bold">{{ number_format($totalAmount, 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>

                    <!-- Form inside the same box -->
                    <div class="container mt-4">
                        <form action="{{ route('quotation.addDrugs', ['prescription_id' => $subscription->id]) }}"
                            method="POST">
                            @csrf
                            <div class="row mb-3">
                                <div class="col-md-6 offset-md-6">
                                    <div class="mb-3 d-flex align-items-center">
                                        <label for="drug_name" class="form-label mr-3"
                                            style="width: 100px;">Drug:</label>
                                        <select id="drug_id" name="drug_name" class="form-control" required>
                                            <option value="" disabled selected>Select a drug</option>
                                            @foreach($drugs_details as $drug)
                                            <option value="{{ $drug->id }}">{{ $drug->drug_name }}</option>
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
                                        <button type="submit" class="btn btn-primary">Add</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <hr class="my-4" style="width: 100%; border: 1px solid black;">
                    <form action="{{ route('send.quotation')}}" method="POST">
                        <!-- method('PUT') -->
                        @csrf
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">Send Quotation</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>

<script>
function swapImage(smallImg, index) {
    let largeImage = document.getElementById("largeImage");

    // Swap the image sources
    let tempSrc = largeImage.src;
    largeImage.src = smallImg.src;
    smallImg.src = tempSrc;
}
</script>
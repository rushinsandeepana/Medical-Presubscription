<x-app-layout>
    @if(session('success'))
    <div class="text-green-600">{{ session('success') }}</div>
    @endif

    <h2 class="text-xl font-bold">View Quotation Status</h2>

    <div class="container d-flex justify-content-center mt-5">
        <div class="w-75">
            <div class="d-flex justify-content-between mb-3">
                <!-- Search Form -->
                <form method="GET" class="d-flex">
                    <input type="text" name="search" class="form-control me-2" placeholder="Search..."
                        value="{{ request('search') }}">
                    <button type="submit" class="btn btn-primary">Search</button>
                </form>

                <!-- Pagination Rows Dropdown -->
                <form method="GET" id="paginationForm">
                    <label class="me-2 fw-bold">Rows per page:</label>
                    <select name="per_page" id="perPageSelect" class="form-select"
                        onchange="document.getElementById('paginationForm').submit();">
                        <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                        <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                        <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                        <option value="1000" {{ request('per_page') == 1000 ? 'selected' : '' }}>1000</option>
                    </select>
                </form>
            </div>

            <table class="table table-bordered text-center">
                <thead class="table-light">
                    <tr>
                        <th class="fw-bold">User Name</th>
                        <th class="fw-bold">Prescription ID</th>
                        <th class="fw-bold">Quotation ID</th>
                        <th class="fw-bold">Total Amount</th>
                        <th class="fw-bold">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($quotationStatus as $data)
                    <tr>
                        <td>{{ $data->username }}</td>
                        <td>{{ $data->prescription_id }}</td>
                        <td>{{ $data->quotation_id }}</td>
                        <td>{{ $data->total_amount }}</td>
                        <td
                            class="{{ $data->status == 'confirmed' ? 'text-success' : ($data->status == 'rejected' ? 'text-danger' : '') }}">
                            {{ $data->status }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5">No results found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="d-flex justify-content-center mt-3">
                {{ $quotationStatus->appends(['search' => request('search'), 'per_page' => request('per_page')])->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
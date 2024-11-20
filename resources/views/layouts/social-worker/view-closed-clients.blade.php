@extends('layouts.app')

@section('title', 'Applicant Status')

@section('content')

<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Applicant Status</h1>
        </div>

        <div class="section-body">
            <div class="input-group" style="max-width: 500px; margin-bottom: 20px;">
                <input type="text" id="searchInput" class="form-control" placeholder="Search Client">
                <button class="btn btn-primary" style="margin-left:5px;" type="submit">Search</button>
            </div>
            <div class="card">
                <div class="card-header">
                    <h4>Applicants with Closed Tracking</h4>
                </div>
                <div class="card-body">
                    <!-- Wrap table inside a responsive container -->
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Control No.</th>
                                    <th>Name</th>
                                    <th>Suffix</th>
                                    <th>Age</th>
                                    <th>Sex</th>
                                    <th>Date of Birth</th>
                                    <th>Nationality</th>
                                    <th>Contact Number</th>
                                    <th>Case Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="searchResults">
                                @foreach ($clients as $client)
                                <tr>
                                    <td class="controlnumber">{{ $client->control_number }}</td>
                                    <td class="fullname">{{ $client->first_name }} {{ $client->last_name }}</td>
                                    <td class="suffix">{{ $client->suffix }}</td>
                                    <td class="age">{{ $client->age }}</td>
                                    <td class="sex">{{ $client->sex }}</td>
                                    <td class="birthday">{{ $client->date_of_birth }}</td>
                                    <td class="nationality">{{ $client->nationality }}</td>
                                    <td class="contactnumber">{{ $client->contact_number }}</td>
                                    <td class="case-status" style="padding: 5px; text-align: center;">
                                        <span style="
                                            background-color: {{ $client->tracking == 'Approve' ? 'green' : 'transparent' }};
                                            color: white;
                                            padding: 2px 4px;
                                            border-radius: 4px;">
                                            {{ $client->tracking == 'Approve' ? 'Closed' : 'Not Tracking' }}
                                        </span>
                                    </td>
                                    <td>
                                        <!-- View Button -->
                                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#viewClientModal{{ $client->id }}">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <!-- Duplicate Record Button -->
                                        <button type="button" class="btn btn-primary" onclick="duplicateRecord({{ $client->id }})">
                                            <i class="fas fa-copy"></i> Duplicate Record
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div> <!-- End table-responsive -->
                </div>
            </div>
        </div>
    </section>
    @include('layouts.social-worker.view-modal', ['client' => $client]) <!-- Pass the client variable -->

    <!-- Success Message -->
    <div id="successMessage" class="alert alert-success" style="display: none;">
        Record successfully duplicated!
    </div>

    <script>
        // Function to handle record duplication
        function duplicateRecord(clientId) {
            if (confirm("Are you sure you want to duplicate this record?")) {
                fetch(`/duplicate-client/${clientId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('successMessage').style.display = 'block';
                        setTimeout(() => {
                            document.getElementById('successMessage').style.display = 'none';
                        }, 3000);
                    } else {
                        alert("An error occurred while duplicating the record.");
                    }
                })
                .catch(error => {
                    console.error("Error duplicating record:", error);
                    alert("An error occurred while duplicating the record.");
                });
            }
        }

        // Search Functionality
        document.getElementById('searchInput').addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const tableRows = document.querySelectorAll('#searchResults tr');

            tableRows.forEach(row => {
                const controlNum = row.querySelector('.controlnumber').textContent.toLowerCase();
                const fullname = row.querySelector('.fullname').textContent.toLowerCase();
                const suffix = row.querySelector('.suffix').textContent.toLowerCase();
                const age = row.querySelector('.age').textContent.toLowerCase();
                const sex = row.querySelector('.sex').textContent.toLowerCase();
                const birthday = row.querySelector('.birthday').textContent.toLowerCase();
                const nationality = row.querySelector('.nationality').textContent.toLowerCase();
                const contactnumber = row.querySelector('.contactnumber').textContent.toLowerCase();
                const casestatus = row.querySelector('.case-status').textContent.toLowerCase();

                if (controlNum.includes(searchTerm) || fullname.includes(searchTerm) || suffix.includes(searchTerm) || age.includes(searchTerm) ||
                    sex.includes(searchTerm) || birthday.includes(searchTerm) || nationality.includes(searchTerm) || contactnumber.includes(searchTerm) ||
                    casestatus.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    </script>
</div>
@endsection

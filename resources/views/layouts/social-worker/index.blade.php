	@extends('layouts.app')
	@section('title', 'Access Data')
	@section('content')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap4.min.css">

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap4.min.js"></script>

    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Access Data</h1>
            </div>
            <div class="section-body">
                <div class="table-responsive">
                    <!-- Table -->
                    <table id="clientTable" class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Control No.</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Middle Name</th>
                                <th>Suffix</th>
                                <th>Age</th>
                                <th>Sex</th>
                                <th>Date of Birth</th>
                                <th>Civil Status</th>
                                <th>Nationality</th>
                                <th>Contact Number</th>
                                <th>Case Status</th>
                                <th>Assigned Officer</th>
                                <th>View</th>
                                <th>Family Member</th>
                                <th>Edit</th>
                            </tr>
                        </thead>
                        <tbody id="searchResults">
                            @foreach ($clients as $client)
                            <tr>
                                <td class="control-num">{{ $client->control_number }}</td>
                                <td class="first-name">{{ $client->first_name }}</td>
                                <td class="last-name">{{ $client->last_name }}</td>
                                <td class="middle-name">{{ $client->middle }}</td>
                                <td class="suffix">{{ $client->suffix }}</td>
                                <td class="age">{{ $client->age }}</td>
                                <td class="sex">{{ $client->sex }}</td>
                                <td class="birthday">{{ $client->date_of_birth }}</td>
                                <td class="civil-status">{{ $client->civil_status }}</td>
                                <td class="nationality">{{ $client->nationality }}</td>
                                <td class="contact-number">{{ $client->contact_number }}</td>
                                <td class="case-status">
                                    <span style="background-color: {{ $client->tracking == 'Re-access' ? 'orange' : ($client->tracking == 'Approve' ? 'green' : 'transparent') }};
                                          color: white; padding: 2px 4px; border-radius: 4px;">
                                        {{ $client->tracking == 'Re-access' ? 'Ongoing' : ($client->tracking == 'Approve' ? 'Closed' : $client->tracking) }}
                                    </span>
                                </td>
                                <td>{{ $client->reviewing }}</td>
                                <td>
                                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#viewClientModal{{ $client->id }}">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-info" data-toggle="modal" data-target="#familyMembersModal{{ $client->id }}">
                                        <i class="fas fa-user-edit"></i>
                                    </button>
                                </td>
                                <td>
                                    <button class="btn btn-primary" data-toggle="modal" data-target="#openEditModal{{ $client->id }}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>

	<script>
		document.getElementById('searchInput').addEventListener('input', function() {
			const searchTerm = this.value.toLowerCase();
			const tableRows = document.querySelectorAll('#searchResults tr');

			tableRows.forEach(row => {
				const controlNum = row.querySelector('.control-num').textContent.toLowerCase();
				const fName = row.querySelector('.first-name').textContent.toLowerCase();
				const lName = row.querySelector('.last-name').textContent.toLowerCase();
				const mName = row.querySelector('.middle-name').textContent.toLowerCase();
				const suffix = row.querySelector('.suffix').textContent.toLowerCase();
				const age = row.querySelector('.age').textContent.toLowerCase();
				const sex = row.querySelector('.sex').textContent.toLowerCase();
				const birthday = row.querySelector('.birthday').textContent.toLowerCase();
				const civilStatus = row.querySelector('.civil-status').textContent.toLowerCase();
				const nationality = row.querySelector('.nationality').textContent.toLowerCase();
				const contactNum = row.querySelector('.contact-number').textContent.toLowerCase();
				const caseStatus = row.querySelector('.case-status').textContent.toLowerCase();

				if (controlNum.includes(searchTerm) || fName.includes(searchTerm) || lName.includes(searchTerm) || mName.includes(searchTerm) ||
					suffix.includes(searchTerm) || age.includes(searchTerm) || sex.includes(searchTerm) || birthday.includes(searchTerm) ||
					civilStatus.includes(searchTerm) || nationality.includes(searchTerm) ||
					contactNum.includes(searchTerm) ||
					caseStatus.includes(searchTerm)) {
					row.style.display = '';
				} else {
					row.style.display = 'none';
				}
			});
		});
	</script>
	<!-- General CSS Files -->
    <script>
        $(document).ready(function() {
            $('#clientTable').DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
                "pageLength": 10 // Optional: Set initial page length
            });
        });
    </script>
	<!-- Template CSS -->
	<link rel="stylesheet" href="/assets/css/style.css">
	<link rel="stylesheet" href="/assets/css/components.css">

	<style>
		.order-tracker {
			display: flex;
			justify-content: space-between;
			align-items: center;
			margin-bottom: 30px;
		}

		.order-step {
			text-align: center;
			flex: 1;
		}

		.order-step i {
			font-size: 24px;
			margin-bottom: 10px;
		}

		.progress-bar {
			height: 5px;
			background-color: #6777ef;
		}
	</style>
	@php
	$problemIdentificationStatus = 'Incomplete';
	@endphp
	@foreach($clients as $client)

	@php
	// Count the number of tasks marked as 'Done'
	$totalTasks = 4;
	$completedTasks = 0;

	if ($client->problem_identification == 'Done') $completedTasks++;
	if ($client->data_gather == 'Done') $completedTasks++;
	if ($client->assessment == 'Done') $completedTasks++;
	if ($client->eval == 'Done') $completedTasks++;

	// Calculate the progress percentage
	$progressPercentage = ($completedTasks / $totalTasks) * 100;
	$progressColor = $completedTasks == $totalTasks ? 'bg-success' : 'bg-dark';
	@endphp

	<div class="modal fade" id="viewClientModal{{ $client->id }}" tabindex="-1" role="dialog" aria-labelledby="viewClientModalLabel{{ $client->id }}" aria-hidden="true">
		<div class="modal-dialog modal-xl" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="viewClientModalLabel{{ $client->id }}">Applicant Details</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<h5>View Applicant History</h5>
					<section class="section">
						<div class="section-body">
							<div class="card">
								<div class="card-body">
									<div class="progress mb-3">
										<div class="progress-bar {{ $progressColor }}" role="progressbar" style="width: {{ $progressPercentage }}%" aria-valuenow="{{ $progressPercentage }}" aria-valuemin="0" aria-valuemax="100"></div>
									</div>
									<div class="order-tracker">
										<div class="order-step">
											<i class="fas fa-search
                            @if($client->problem_identification == 'Done') text-success
                            @elseif($client->problem_identification == 'Processing') text-warning
                            @elseif($client->problem_identification == 'Incomplete') text-danger
                            @endif"></i>
											<p>Problem Identification</p>
										</div>

										<div class="order-step">
											<i class="fas fa-database
                            @if($client->data_gather == 'Done') text-success
                            @elseif($client->data_gather == 'Processing') text-warning
                            @elseif($client->data_gather == 'Incomplete') text-danger
                            @endif"></i>
											<p>Data Gathering</p>
										</div>

										<div class="order-step">
											<i class="fas fa-chart-line
                            @if($client->assessment == 'Done') text-success
                            @elseif($client->assessment == 'Processing') text-warning
                            @elseif($client->assessment == 'Incomplete') text-danger
                            @endif"></i>
											<p>Assessment</p>
										</div>

										<div class="order-step">
											<i class="fas fa-check-circle
                            @if($client->eval == 'Done') text-success
                            @elseif($client->eval == 'Processing') text-warning
                            @elseif($client->eval == 'Incomplete') text-danger
                            @endif"></i>
											<p>Evaluation And Resolution</p>
										</div>
									</div>

								</div>
							</div>
						</div>
					</section>

					<div class="client-info">
						<h6 class="text-muted mb-3">Applicant Details</h6>

						<div class="row mb-2">
							<div class="col-md-6"><strong>Control No:</strong> {{ $client->control_number }}</div>
							<div class="col-md-6"><strong>First Name:</strong> {{ $client->first_name }}</div>
						</div>

						<div class="row mb-2">
							<div class="col-md-6"><strong>Last Name:</strong> {{ $client->last_name }}</div>
							<div class="col-md-6"><strong>Middle Name:</strong> {{ $client->middle }}</div>
						</div>

						<div class="row mb-2">
							<div class="col-md-6"><strong>Suffix:</strong> {{ $client->suffix }}</div>
							<div class="col-md-6"><strong>Age:</strong> {{ $client->age }}</div>
						</div>

						<div class="row mb-2">
							<div class="col-md-6"><strong>Sex:</strong> {{ $client->sex }}</div>
							<div class="col-md-6"><strong>Date of Birth:</strong> {{ $client->date_of_birth }}</div>
						</div>

						<div class="row mb-2">
							<div class="col-md-6"><strong>Place of Birth:</strong> {{ $client->pob }}</div>
							<div class="col-md-6"><strong>Educational Attainment:</strong> {{ $client->educational_attainment }}</div>
						</div>

						<div class="row mb-2">
							<div class="col-md-6"><strong>Civil Status:</strong> {{ $client->civil_status }}</div>
							<div class="col-md-6"><strong>Religion:</strong> {{ $client->religion }}</div>
						</div>

						<div class="row mb-2">
							<div class="col-md-6"><strong>Nationality:</strong> {{ $client->nationality }}</div>
							<div class="col-md-6"><strong>Occupation:</strong> {{ $client->occupation }}</div>
						</div>

						<div class="row mb-2">
							<div class="col-md-6"><strong>Monthly Income:</strong> {{ $client->monthly_income }}</div>
							<div class="col-md-6"><strong>Contact Number:</strong> {{ $client->contact_number }}</div>
						</div>

						<div class="row mb-2">
							<div class="col-md-12">
								<strong>Building Number:</strong> {{ $client->building_number ?? 'N/A' }}
							</div>
						</div>

						<div class="row mb-2">
							<div class="col-md-12">
								<strong>Street Name:</strong> {{ $client->street_name ?? 'N/A' }}
							</div>
						</div>

						<div class="row mb-2">
							<div class="col-md-12">
								<strong>Barangay:</strong> {{ $client->barangay ?? 'N/A' }}
							</div>
						</div>
						<hr>
						<h6 class="text-muted mb-3">Household Information</h6>

						<div class="row mb-2">
							<div class="col-md-6"><strong>House Structure:</strong> {{ $client->house_structure }}</div>
							<div class="col-md-6"><strong>Number of Rooms:</strong> {{ $client->number_of_rooms }}</div>
						</div>

						<div class="row mb-2">
							<div class="col-md-6"><strong>Appliances:</strong> {{ $client->appliances }}</div>
							<div class="col-md-6"><strong>Monthly Expenses:</strong> @php
								$expenses = json_decode($client->monthly_expenses, true); // Decode JSON data into an associative array
								@endphp

								@if(is_array($expenses))
								@foreach($expenses as $key => $value)
								@if($value) <!-- Check if value is not empty -->
								<div>{{ $key }} - {{ $value }}</div>
								@endif
								@endforeach
								@else
								<div>No expenses data available.</div>
								@endif
							</div>
						</div>


						<hr>

						<h4 class="mb-3">Services</h4>

						<div class="form-group">
							<?php

							$clientServices = is_array($client->services) ? $client->services : json_decode($client->services, true);
							$clientServices = is_array($clientServices) ? $clientServices : [];


							$serviceCategories = [
								'Burial Assistance' => [
									'Burial',
									'Financial',
									'Funeral',
									'Crisis Intervention Unit = Valid ID',
									'Barangay Clearance.',
									'Medical Certificate.',
									'Incident Report.',
									'Funeral Contract.',
									'Death Certificate.'
								],
								'Solo Parent Services' => [
									'Solo Parent = Agency Referral',
									'Residency Cert.',
									'Medical Cert.',
									'Billing Proof',
									'Birth Cert.',
									'ID Copy',
									'Senior Citizen ID (60+)'
								],
								'Pre-marriage Counseling' => [
									'Pre-marriage Counseling = Valid ID',
									'Birth Certificate',
									'CENOMAR',
									'Barangay Clearance',
									'Passport-sized Photos'
								],
								'After-Care Services' => [
									'After-Care Services = Valid ID',
									'Birth Certificate.',
									'Residence Certificate.',
									'SCSR',
									'Medical Records',
								],
								'Poverty Alleviation Program' => [
									'Poverty Alleviation Program = Valid ID',
									'Residence Certificate',
									'Income Certificate',
									'Application Form'
								],
								'Crisis Intervention Unit' => [
									'Valid ID',
									'Residence Certificate Or Barangay Clearance',
									'Clinical Abstract Medical Certificate',
									'Police Report Or Incident Report',
									'Funeral Contract And Registered Death Certificate. (if Applicable)'
								]
							];


							foreach ($serviceCategories as $category => $services) {
								$filteredServices = array_intersect($services, $clientServices);


								if (!empty($filteredServices)) {
									echo "<h5><label>{$category}</label></h5>";
									echo "<div class='checkbox-group'>";


									echo "<p><strong>Requirements:</strong></p>";


									foreach ($services as $service) {

										$isChecked = in_array($service, $clientServices) ? 'checked' : '';
										echo "<div class='form-check'>";
										echo "<input type='checkbox' class='form-check-input' name='services[]' value='{$service}' {$isChecked} onclick='return false;'>"; // prevent checkbox change
										echo "<label class='form-check-label'>{$service}</label>";
										echo "</div>";
									}

									echo "</div><br>";
								}
							}
							?>
						</div>



						<hr>
						<h6 class="text-muted mb-3">Additional Information</h6>

						<div class="row mb-2">
							<div class="col-md-12"><strong>Services and Requirements:</strong> {{ $client->services_and_requirements }}</div>
						</div>

						<div class="row mb-2">
							<div class="col-md-12"><strong>Circumstances of Referral:</strong> {{ $client->circumstances_of_referral }}</div>
						</div>

						<div class="row mb-2">
							<div class="col-md-12"><strong>Family Background:</strong> {{ $client->family_background }}</div>
						</div>

						<div class="row mb-2">
							<div class="col-md-12"><strong>Health History:</strong> {{ $client->health_history }}</div>
						</div>

						<div class="row mb-2">
							<div class="col-md-12"><strong>Economic Situation:</strong> {{ $client->economic_situation }}</div>
						</div>

						<div class="row mb-2">
							<div class="col-md-12"><strong>Recommendation:</strong> {{ $client->recommendation }}</div>
						</div>
						<hr>
						<h6 class="text-muted mb-3">Interview and Approval Details</h6>

						<div class="row mb-2">
							<div class="col-md-12"><strong>Interviewee:</strong> {{ $client->interviewee }}</div>
						</div>

						<div class="row mb-2">
							<div class="col-md-12"><strong>Interview By:</strong> {{ $client->interviewed_by }}</div>
						</div>

						<div class="row mb-2">
							<div class="col-md-12"><strong>Reviewing:</strong> {{ $client->reviewing }}</div>
						</div>

						<div class="row mb-2">
							<div class="col-md-12"><strong>Approved by:</strong> {{ $client->approving }}</div>
						</div>


					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-danger" onclick="generatePdf({{ $client->id }})">
						<i class="fas fa-file-pdf"></i> Generate PDF
					</button>

					<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
	</div>
	@endforeach

	<script>
		function generatePdf(clientId) {
			window.location.href = '/generate-pdf/' + clientId;
		}
	</script>
	@foreach($clients as $client)
	<div class="modal fade" id="openEditModal{{ $client->id }}" tabindex="-1" role="dialog" aria-labelledby="openEditModal{{ $client->id }}Label" aria-hidden="true">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="openEditModal{{ $client->id }}Label">Edit Applicant</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<form id="editClientForm{{ $client->id }}" data-client-id="{{ $client->id }}">
						@csrf
						@method('PUT')
						<div class="row">

							<div class="col-md-4 form-group">
								<label for="first_name">First Name</label>
								<input type="text" class="form-control" id="first_name" name="first_name" style="border: none; border-bottom: 1px solid black; outline: none; width: 200px;" value="{{ $client->first_name }}" required>
							</div>
							<div class="col-md-4 form-group">
								<label for="last_name">Last Name</label>
								<input type="text" class="form-control" id="last_name" name="last_name" style="border: none; border-bottom: 1px solid black; outline: none; width: 200px;" value="{{ $client->last_name }}" required>
							</div>
							<div class="col-md-4 form-group">
								<label for="middle">Middle Name</label>
								<input type="text" class="form-control" id="middle" name="middle" style="border: none; border-bottom: 1px solid black; outline: none; width: 200px;" value="{{ $client->middle }}" required>
							</div>
							<div class="col-md-4 form-group">
								<label for="suffix">Suffix</label>
								<select name="suffix" class="form-control" style="border: none; border-bottom: 1px solid black; outline: none; width: 200px;" id="suffix" required>
									<option value="" selected disable>Select Suffix</option>
									<option value="Jr." {{ $client->suffix == 'Jr.' ? 'selected' : '' }}>Jr. (Junior)</option>
									<option value="Sr." {{ $client->suffix == 'Sr.' ? 'selected' : '' }}>Sr. (Senior)</option>
									<option value="II" {{ $client->suffix == 'II' ? 'selected' : '' }}>II (Second)</option>
									<option value="III" {{ $client->suffix == 'III' ? 'selected' : '' }}>III (Third)</option>
									<option value="IV" {{ $client->suffix == 'IV' ? 'selected' : '' }}>IV (Fourth)</option>
									<option value="None" {{ $client->suffix == 'None' ? 'selected' : '' }}>None</option>
								</select>
							</div>
							<div class="col-md-4 form-group">
								<label for="building_number">Building / House / Block No.</label>
								<input type="text" class="form-control" name="building_number"
									style="border: none; border-bottom: 1px solid black; outline: none; width: 200px;"
									value="{{$client->building_number}} " required>
							</div>

							<div class="col-md-4 form-group">
								<label for="street_name">Street No. / Name</label>
								<input type="text" class="form-control" name="street_name"
									style="border: none; border-bottom: 1px solid black; outline: none; width: 200px;"
									value="{{$client->street_name}}" required>
							</div>

							<div class="col-md-4 form-group">
								<label for="barangay">Barangay</label>
								<select class="form-control shadow-sm" id="barangay" name="barangay" style="border: none; border-bottom: 1px solid black; outline: none; width: 200px;"
									value="{{$client->barangay}}" required>
									<option value="">Select a Barangay</option>
									<option value="Tanyag">Tanyag</option>
									<option value="Bagumbayan" {{ $client->barangay == 'Bagumbayan' ? 'selected' : '' }}>Bagumbayan</option>
									<option value="Bambang" {{ $client->barangay == 'Bambang' ? 'selected' : '' }}>Bambang</option>
									<option value="Calzada" {{ $client->barangay == 'Calzada' ? 'selected' : '' }}>Calzada</option>
									<option value="Hagonoy" {{ $client->barangay == 'Hagonoy' ? 'selected' : '' }}>Hagonoy</option>
									<option value="Ibayo-Tipas" {{ $client->barangay == 'Ibayo-Tipas' ? 'selected' : '' }}>Ibayo-Tipas</option>
									<option value="Ligid-Tipas" {{ $client->barangay == 'Ligid-Tipas' ? 'selected' : '' }}>Ligid-Tipas</option>
									<option value="Lower Bicutan" {{ $client->barangay == 'Lower Bicutan' ? 'selected' : '' }}>Lower Bicutan</option>
									<option value="Maharlika Village" {{ $client->barangay == 'Maharlika Village' ? 'selected' : '' }}>Maharlika Village</option>
									<option value="Napindan" {{ $client->barangay == 'Napindan' ? 'selected' : '' }}>Napindan</option>
									<option value="Palingon" {{ $client->barangay == 'Palingon' ? 'selected' : '' }}>Palingon</option>
									<option value="Santa Ana" {{ $client->barangay == 'Santa Ana' ? 'selected' : '' }}>Santa Ana</option>
									<option value="Central Signal Village" {{ $client->barangay == 'Central Signal Village' ? 'selected' : '' }}>Central Signal Village</option>
									<option value="Tuktukan" {{ $client->barangay == 'Tuktukan' ? 'selected' : '' }}>Tuktukan</option>
									<option value="Upper Bicutan" {{ $client->barangay == 'Upper Bicutan' ? 'selected' : '' }}>Upper Bicutan</option>
									<option value="Ususan" {{ $client->barangay == 'Ususan' ? 'selected' : '' }}>Ususan</option>
									<option value="Wawa" {{ $client->barangay == 'Wawa' ? 'selected' : '' }}>Wawa</option>
									<option value="Western Bicutan" {{ $client->barangay == 'Western Bicutan' ? 'selected' : '' }}>Western Bicutan</option>
									<option value="Central Bicutan" {{ $client->barangay == 'Central Bicutan' ? 'selected' : '' }}>Central Bicutan</option>
									<option value="Fort Bonifacio" {{ $client->barangay == 'Fort Bonifacio' ? 'selected' : '' }}>Fort Bonifacio</option>
									<option value="Katuparan" {{ $client->barangay == 'Katuparan' ? 'selected' : '' }}>Katuparan</option>
									<option value="New Lower Bicutan" {{ $client->barangay == 'New Lower Bicutan' ? 'selected' : '' }}>New Lower Bicutan</option>
									<option value="North Daang Hari" {{ $client->barangay == 'North Daang Hari' ? 'selected' : '' }}>North Daang Hari</option>
									<option value="North Signal Village" {{ $client->barangay == 'North Signal Village' ? 'selected' : '' }}>North Signal Village</option>
									<option value="Pinagsama" {{ $client->barangay == 'Pinagsama' ? 'selected' : '' }}>Pinagsama</option>
									<option value="San Miguel" {{ $client->barangay == 'San Miguel' ? 'selected' : '' }}>San Miguel</option>
									<option value="South Daang Hari" {{ $client->barangay == 'South Daang Hari' ? 'selected' : '' }}>South Daang Hari</option>
									<option value="South Signal Village" {{ $client->barangay == 'South Signal Village' ? 'selected' : '' }}>South Signal Village</option>
									<option value="Cembo" {{ $client->barangay == 'Cembo' ? 'selected' : '' }}>Cembo</option>
									<option value="Comembo" {{ $client->barangay == 'Comembo' ? 'selected' : '' }}>Comembo</option>
									<option value="East Rembo" {{ $client->barangay == 'East Rembo' ? 'selected' : '' }}>East Rembo</option>
									<option value="Pembo" {{ $client->barangay == 'Pembo' ? 'selected' : '' }}>Pembo</option>
									<option value="Pitogo" {{ $client->barangay == 'Pitogo' ? 'selected' : '' }}>Pitogo</option>
									<option value="Post Proper Northside" {{ $client->barangay == 'Calzada' ? 'selected' : '' }}>Post Proper Northside</option>
									<option value="Post Proper Southside" {{ $client->barangay == 'Post Proper Southside' ? 'selected' : '' }}>Post Proper Southside</option>
									<option value="Rizal" {{ $client->barangay == 'Rizal' ? 'selected' : '' }}>Rizal</option>
									<option value="South Cembo" {{ $client->barangay == 'South Cembo' ? 'selected' : '' }}>South Cembo</option>
									<option value="West Rembo" {{ $client->barangay == 'West Rembo' ? 'selected' : '' }}>West Rembo</option>

								</select>
							</div>
							<div class="col-md-4 mb-3">
								<label for="date_of_birth">Date of Birth</label>
								<input type="date" name="date_of_birth" class="form-control" id="date_of_birth{{ $client->id }}"
									value="{{$client->date_of_birth}}" placeholder="Enter Date of Birth"
									style="border: none; border-bottom: 1px solid black; outline: none; width: 200px;" required>
							</div>
							<div class="col-md-4 mb-3">
								<label for="age">Age</label>
								<input type="text" name="age" class="form-control" id="age{{ $client->id }}" placeholder="Age"
									value="{{$client->age}}" style="border: none; border-bottom: 1px solid black; outline: none; width: 200px;">
							</div>
							<script>
								document.getElementById('date_of_birth{{ $client->id }}').addEventListener('change', function() {
									try {
										var dob = new Date(this.value);
										var today = new Date();
										var age = today.getFullYear() - dob.getFullYear();
										var monthDiff = today.getMonth() - dob.getMonth();

										// Adjust age if the birthday hasn't occurred yet this year
										if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < dob.getDate())) {
											age--;
										}

										// Update the age input field
										document.getElementById('age{{ $client->id }}').value = age;
									} catch (error) {
										console.error("An error occurred while calculating age:", error);
										alert("Failed to calculate age. Please check the date of birth format.");
									}
								});
							</script>
							<div class="col-md-4 form-group">
								<label for="pob">Place of Birth</label>
								<input type="text" class="form-control" id="pob" name="pob" style="border: none; border-bottom: 1px solid black; outline: none; width: 200px;" value="{{ $client->pob }}" required>
							</div>
							<div class="col-md-4 form-group">
								<label for="sex">Sex</label>
								<select class="form-control" id="sex" name="sex" style="border: none; border-bottom: 1px solid black; outline: none; width: 200px;" required>
									<option value="Male" {{ $client->sex == 'Male' ? 'selected' : '' }}>Male</option>
									<option value="Female" {{ $client->sex == 'Female' ? 'selected' : '' }}>Female</option>
								</select>
							</div>
							<div class="col-md-4 form-group">
								<label for="educational_attainment">Educational Attainment</label>
								<select name="educational_attainment" class="form-control" style="border: none; border-bottom: 1px solid black; outline: none; width: 200px;" id="educational_attainment" required>
									<option value="" disabled selected>Select Educational Attainment</option>
                                        <option value="No Formal Education" {{ $client->educational_attainment == 'No Formal Education' ? 'selected' : '' }}>No Formal Education</option>
                                        <option value="Elementary - Some" {{ $client->educational_attainment == 'Elementary - Some' ? 'selected' : '' }}>Elementary - Some</option>
                                        <option value="Elementary Graduate" {{ $client->educational_attainment == 'Elementary Graduate' ? 'selected' : '' }}>Elementary Graduate</option>
                                        <option value="High School - Some" {{ $client->educational_attainment == 'High School - Some' ? 'selected' : '' }}>High School - Some</option>
                                        <option value="High School Graduate" {{ $client->educational_attainment == 'High School Graduate' ? 'selected' : '' }}>High School Graduate</option>
                                        <option value="Vocational/Technical" {{ $client->educational_attainment == 'Vocational/Technical' ? 'selected' : '' }}>Vocational/Technical</option>
                                        <option value="College - Some" {{ $client->educational_attainment == 'College - Some' ? 'selected' : '' }}>College - Some</option>
                                        <option value="Associate Degree" {{ $client->educational_attainment == 'Associate Degree' ? 'selected' : '' }}>Associate Degree</option>
                                        <option value="Bachelor's Degree" {{ $client->educational_attainment == "Bachelor's Degree" ? 'selected' : '' }}>Bachelor's Degree</option>
                                        <option value="Master's Degree" {{ $client->educational_attainment == "Master's Degree" ? 'selected' : '' }}>Master's Degree</option>
                                        <option value="Doctorate Degree" {{ $client->educational_attainment == "Doctorate Degree" ? 'selected' : '' }}>Doctorate Degree</option>
                                        <option value="Other" {{ $client->educational_attainment == 'Other' ? 'selected' : '' }}>Other</option>
								</select>
							</div>
							<div class="col-md-4 form-group">
								<label for="civil_status">Civil Status</label>
								<select class="form-control" id="civil_status" name="civil_status" style="border: none; border-bottom: 1px solid black; outline: none; width: 200px;" required>
									<option value="Single" {{ $client->civil_status == 'Single' ? 'selected' : '' }}>Single</option>
									<option value="Married" {{ $client->civil_status == 'Married' ? 'selected' : '' }}>Married</option>
									<option value="Divorced" {{ $client->civil_status == 'Divorced' ? 'selected' : '' }}>Divorced</option>
									<option value="Widowed" {{ $client->civil_status == 'Widowed' ? 'selected' : '' }}>Widowed</option>
								</select>
							</div>
							<div class="col-md-4 form-group">
								<label for="religion">Religion</label>
								<select name="religion" class="form-control" style="border: none; border-bottom: 1px solid black; outline: none; width: 200px;" id="religion" required>
									<option value="" selected disabled>Select Religion</option>
									<option value="Christianity" {{ $client->religion == 'Christianity' ? 'selected' : '' }}>Christianity</option>
									<option value="Catholic" {{ $client->religion == 'Catholic' ? 'selected' : '' }}>Catholic</option>
									<option value="Islam" {{ $client->religion == 'Islam' ? 'selected' : '' }}>Islam</option>
									<option value="Hinduism" {{ $client->religion == 'Hinduism' ? 'selected' : '' }}>Hinduism</option>
									<option value="Buddhism" {{ $client->religion == 'Buddhism' ? 'selected' : '' }}>Buddhism</option>
									<option value="Judaism" {{ $client->religion == 'Judaism' ? 'selected' : '' }}>Judaism</option>
									<option value="Iglesia ni Cristo" {{ $client->religion == 'Iglesia ni Cristo' ? 'selected' : '' }}>Iglesia ni Cristo</option>
									<option value="Muslim" {{ $client->religion == 'Muslim' ? 'selected' : '' }}>Muslim</option>
									<option value="Other" {{ ($client->religion == 'Other' || $client->religion) ? 'selected' : '' }}>Other</option>
								</select>
							</div>
							<div class="col-md-4 form-group">
								<label for="other_religion">Other Religion</label>
								<input type="text" class="form-control" id="other_religion" name="other_religion" style="border: none; border-bottom: 1px solid black; outline: none; width: 200px;" value="{{$client->religion}}">
							</div>
							<div class="col-md-4 form-group">
								<label for="nationality">Nationality</label>
								<select name="nationality" class="form-control" style="border: none; border-bottom: 1px solid black; outline: none; width: 200px;" id="nationality" required>
									<option value="" selected disabled>Select Nationality</option>
									<option value="Filipino" {{ $client->nationality == 'Filipino' ? 'selected' : '' }}>Filipino</option>
									<option value="American" {{ $client->nationality == 'American' ? 'selected' : '' }}>American</option>
									<option value="British" {{ $client->nationality == 'British' ? 'selected' : '' }}>British</option>
									<option value="Canadian" {{ $client->nationality == 'Canadian' ? 'selected' : '' }}>Canadian</option>
									<option value="Australian" {{ $client->nationality == 'Australian' ? 'selected' : '' }}>Australian</option>
									<option value="Indian" {{ $client->nationality == 'Indian' ? 'selected' : '' }}>Indian</option>
									<option value="Other" {{ ($client->nationality == 'Other' || $client->nationality) ? 'selected' : '' }}>Other</option>
								</select>
							</div>
							<div class="col-md-4 form-group">
								<label for="other_nationality">Other Nationality</label>
								<input type="text" class="form-control" id="other_nationality" style="border: none; border-bottom: 1px solid black; outline: none; width: 200px;" name="other_nationality" value="{{$client->nationality}}">
							</div>
							<div class="col-md-4 form-group">
								<label for="occupation">Occupation</label>
								<input type="text" class="form-control" id="occupation" style="border: none; border-bottom: 1px solid black; outline: none; width: 200px;" name="occupation" value="{{ $client->occupation }}">
							</div>
							<div class="col-md-4 form-group">
								<label for="monthly_income">Monthly Income</label>
								<select class="form-control" id="monthly_income" style="border: none; border-bottom: 1px solid black; outline: none; width: 200px;" name="monthly_income" required>
									<option value="" disabled selected>Select Monthly Income</option>
									<option value="No Income" {{ $client->monthly_income == 'No Income' ? 'selected' : '' }}>No Income</option>
									<option value="100 PHP - 500 PHP" {{ $client->monthly_income == '100 PHP - 500 PHP' ? 'selected' : '' }}>100 PHP - 500 PHP </option>
									<option value="500 PHP - 1000 PHP" {{ $client->monthly_income == ' 500 PHP - 1000 PHP' ? 'selected' : '' }}>500 PHP - 1000 PHP</option>
									<option value="1000 PHP - 2000 PHP" {{ $client->monthly_income == '1000 PHP - 2000 PHP' ? 'selected' : '' }}>1000 PHP - 2000 PHP</option>
									<option value="2000 PHP - 5000 PHP" {{ $client->monthly_income == '2000 PHP - 5000 PHP' ? 'selected' : '' }}>2000 PHP - 5000 PHP</option>
									<option value="5000 PHP - 6000 PHP" {{ $client->monthly_income == '5000 PHP - 6000 PHP' ? 'selected' : '' }}>5000 PHP - 6000 PHP</option>
									<option value="6000 PHP - 7000 PHP" {{ $client->monthly_income == '6000 PHP - 7000 PHP' ? 'selected' : '' }}>6000 PHP - 7000 PHP</option>
									<option value="7000 PHP - 8000 PHP" {{ $client->monthly_income == '7000 PHP - 8000 PHP' ? 'selected' : '' }}>7000 PHP - 8000 PHP</option>
									<option value="8000 PHP - 9000 PHP" {{ $client->monthly_income == '8000 PHP - 9000 PHP' ? 'selected' : '' }}>8000 PHP - 9000 PHP</option>
									<option value="9000 PHP - 10,000 PHP" {{ $client->monthly_income == '9000 PHP - 10,000 PHP' ? 'selected' : '' }}>9000 PHP - 10,000 PHP</option>
									<option value="Above 20,000 PHP" {{ $client->monthly_income == 'Above 20,000 PHP' ? 'selected' : '' }}>Above 20,000 PHP</option>
								</select>
							</div>
							<div class="col-md-4 form-group">
								<label for="contact_number">Contact Number</label>
								<input type="tel" name="contact_number" class="form-control" id="contact_number" style="border: none; border-bottom: 1px solid black; outline: none; width: 200px;" placeholder="Enter Contact Number" value="{{ $client->contact_number }}" oninput="this.value=this.value.replace(/[^0-9+#*]/g,'');" required>
								<div class="invalid-feedback">Invalid contact number. Please enter only numbers, +, *, and #.</div>
							</div>
							<div class="col-md-4 form-group">
								<label for="source_of_referral">Source of Referral</label>
								<input type="text" class="form-control" id="source_of_referral" style="border: none; border-bottom: 1px solid black; outline: none; width: 200px;" name="source_of_referral" value="{{ $client->source_of_referral }}" required>
							</div>
							<div class="col-md-4 form-group">
								<label for="house_structure">House Structure</label>
								<select name="house_structure" class="form-control" style="border: none; border-bottom: 1px solid black; outline: none; width: 200px;" id="house_structure" required>
									<option value="Wood" {{ $client->house_structure == 'Wood' ? 'selected' : '' }}>Wood</option>
									<option value="Semi-concrete" {{ $client->house_structure == 'Semi-concrete' ? 'selected' : '' }}>Semi-concrete</option>
									<option value="Concrete" {{ $client->house_structure == 'Concrete' ? 'selected' : '' }}>Concrete</option>
									<option value="Others" {{ $client->house_structure == 'Others' ? 'selected' : '' }}>Others</option>
								</select>
							</div>
							<div class="col-md-4 form-group">
								<label for="floor">Floor/Lot Area (sqm)</label>
								<select name="floor" class="form-control" id="floor" style="border: none; border-bottom: 1px solid black; outline: none; width: 200px;" required>
									<option value="" disabled selected>Select Floor/Lot Area</option>
									<option value="0-50" {{ $client->floor == '0-50' ? 'selected' : '' }}>0-50 sqm</option>
									<option value="51-100" {{ $client->floor == '51-100' ? 'selected' : '' }}>51-100 sqm</option>
									<option value="101-150" {{ $client->floor == '101-150' ? 'selected' : '' }}>101-150 sqm</option>
									<option value="151-200" {{ $client->floor == '151-200' ? 'selected' : '' }}>151-200 sqm</option>
									<option value="201-300" {{ $client->floor == '201-300' ? 'selected' : '' }}>201-300 sqm</option>
									<option value="301-400" {{ $client->floor == '301-400' ? 'selected' : '' }}>301-400 sqm</option>
									<option value="401-500" {{ $client->floor == '401-500' ? 'selected' : '' }}>401-500 sqm</option>
									<option value="501+" {{ $client->floor == '501+' ? 'selected' : '' }}>501+ sqm</option>
								</select>
							</div>

							<div class="col-md-4 form-group">
								<label for="type">Type</label>
								<select name="type" class="form-control" style="border: none; border-bottom: 1px solid black; outline: none; width: 200px;" id="type" required>
									<option value="" disabled selected>Select Type</option>
									<option value="Apartment" {{ $client->type == 'Apartment' ? 'selected' : '' }}>Apartment</option>
									<option value="Townhouse" {{ $client->type == 'Townhouse' ? 'selected' : '' }}>Townhouse</option>
									<option value="Single-Family Home" {{ $client->type == 'Single-Family Home' ? 'selected' : '' }}>Single-Family Home</option>
									<option value="Other" {{ $client->type == 'Other' ? 'selected' : '' }}>Other</option>
								</select>
							</div>
							<div class="col-md-4 form-group">
								<label for="number_of_rooms">Number Of Rooms</label>
								<select name="number_of_rooms" class="form-control" style="border: none; border-bottom: 1px solid black; outline: none; width: 200px;" id="number_of_rooms" required>
									<option value="" disabled selected>Select Number Of Rooms</option>
									<option value="1" {{ $client->number_of_rooms == '1' ? 'selected' : '' }}>1</option>
									<option value="2" {{ $client->number_of_rooms == '2' ? 'selected' : '' }}>2</option>
									<option value="3" {{ $client->number_of_rooms == '3' ? 'selected' : '' }}>3</option>
									<option value="4" {{ $client->number_of_rooms == '4' ? 'selected' : '' }}>4</option>
									<option value="5" {{ $client->number_of_rooms == '5' ? 'selected' : '' }}>5</option>
									<option value="6" {{ $client->number_of_rooms == '6' ? 'selected' : '' }}>6</option>
									<option value="7" {{ $client->number_of_rooms == '7' ? 'selected' : '' }}>7</option>
									<option value="8" {{ $client->number_of_rooms == '8' ? 'selected' : '' }}>8</option>
									<option value="9" {{ $client->number_of_rooms == '9' ? 'selected' : '' }}>9</option>
									<option value="10" {{ $client->number_of_rooms == '10' ? 'selected' : '' }}>10</option>
								</select>
							</div>
							@php
							// Decode the monthly_expense JSON object
							$monthlyExpense = json_decode($client->monthly_expenses, true);

							// Fetch the 'Electricity' key if it exists, otherwise default to an empty string
							$electricityExpense = $monthlyExpense['Electricity'] ?? '';
							@endphp

							<div class="col-md-4 mb-3">
								<label for="electricity">Electricity</label>
								<select name="electricity" class="form-control" style="border: none; border-bottom: 1px solid black; outline: none; width: 200px;" id="electricity">
									<option value="" disabled selected>Select Amount</option>
									<option value="100-500" {{ $electricityExpense == '100-500' ? 'selected' : '' }}>100-500</option>
									<option value="500-1000" {{ $electricityExpense == '500-1000' ? 'selected' : '' }}>500-1000</option>
									<option value="1000-2000" {{ $electricityExpense == '1000-2000' ? 'selected' : '' }}>1000-2000</option>
									<option value="2000-5000" {{ $electricityExpense == '2000-5000' ? 'selected' : '' }}>2000-5000</option>
									<option value="5000-10000" {{ $electricityExpense == '5000-10000' ? 'selected' : '' }}>5000-10000</option>
								</select>
							</div>


							@php
							// Decode the monthly_expense JSON object
							$monthlyExpense = json_decode($client->monthly_expenses, true);

							// Fetch the values for each expense category, defaulting to an empty string if not set
							$electricityExpense = $monthlyExpense['Electricity'] ?? '';
							$waterExpense = $monthlyExpense['Water'] ?? '';
							$rentExpense = $monthlyExpense['Rent'] ?? '';
							$otherExpense = $monthlyExpense['Other'] ?? '';
							@endphp


							<div class="col-md-4 mb-3">
								<label for="electricity">Electricity</label>
								<select name="electricity" class="form-control" style="border: none; border-bottom: 1px solid black; outline: none; width: 200px;" id="electricity">
									<option value="" disabled selected>Select Amount</option>
									<option value="100-500" {{ $electricityExpense == '100-500' ? 'selected' : '' }}>100-500</option>
									<option value="500-1000" {{ $electricityExpense == '500-1000' ? 'selected' : '' }}>500-1000</option>
									<option value="1000-2000" {{ $electricityExpense == '1000-2000' ? 'selected' : '' }}>1000-2000</option>
									<option value="2000-5000" {{ $electricityExpense == '2000-5000' ? 'selected' : '' }}>2000-5000</option>
									<option value="5000-10000" {{ $electricityExpense == '5000-10000' ? 'selected' : '' }}>5000-10000</option>
								</select>
							</div>


							<div class="col-md-4 mb-3">
								<label for="water">Water</label>
								<select name="water" class="form-control" style="border: none; border-bottom: 1px solid black; outline: none; width: 200px;" id="water">
									<option value="" disabled selected>Select Amount</option>
									<option value="100-500" {{ $waterExpense == '100-500' ? 'selected' : '' }}>100-500</option>
									<option value="500-1000" {{ $waterExpense == '500-1000' ? 'selected' : '' }}>500-1000</option>
									<option value="1000-2000" {{ $waterExpense == '1000-2000' ? 'selected' : '' }}>1000-2000</option>
									<option value="2000-5000" {{ $waterExpense == '2000-5000' ? 'selected' : '' }}>2000-5000</option>
									<option value="5000-10000" {{ $waterExpense == '5000-10000' ? 'selected' : '' }}>5000-10000</option>
								</select>
							</div>


							<div class="col-md-4 mb-3">
								<label for="rent">Rent</label>
								<select name="rent" class="form-control" style="border: none; border-bottom: 1px solid black; outline: none; width: 200px;" id="rent">
									<option value="" disabled selected>Select Amount</option>
									<option value="100-500" {{ $rentExpense == '100-500' ? 'selected' : '' }}>100-500</option>
									<option value="500-1000" {{ $rentExpense == '500-1000' ? 'selected' : '' }}>500-1000</option>
									<option value="1000-2000" {{ $rentExpense == '1000-2000' ? 'selected' : '' }}>1000-2000</option>
									<option value="2000-5000" {{ $rentExpense == '2000-5000' ? 'selected' : '' }}>2000-5000</option>
									<option value="5000-10000" {{ $rentExpense == '5000-10000' ? 'selected' : '' }}>5000-10000</option>
								</select>
							</div>

							<div class="col-md-4 mb-3">
								<label for="other">Other</label>
								<select name="other" class="form-control" style="border: none; border-bottom: 1px solid black; outline: none; width: 200px;" id="other">
									<option value="" disabled selected>Select Amount</option>
									<option value="100-500" {{ $otherExpense == '100-500' ? 'selected' : '' }}>100-500</option>
									<option value="500-1000" {{ $otherExpense == '500-1000' ? 'selected' : '' }}>500-1000</option>
									<option value="1000-2000" {{ $otherExpense == '1000-2000' ? 'selected' : '' }}>1000-2000</option>
									<option value="2000-5000" {{ $otherExpense == '2000-5000' ? 'selected' : '' }}>2000-5000</option>
									<option value="5000-10000" {{ $otherExpense == '5000-10000' ? 'selected' : '' }}>5000-10000</option>
								</select>
							</div>


							<div class="col-md-4 form-group">
								<label for="indicate">Indicate If The Applicant Is</label>
								<select name="indicate" class="form-control" style="border: none; border-bottom: 1px solid black; outline: none; width: 200px;" id="indicate" required>
									<option value="" selected disabled>Indicate If The Client Is</option>
									<option value="House Owner" {{ $client->indicate == 'House Owner' ? 'selected' : '' }}>House Owner</option>
									<option value="House Renter" {{ $client->indicate == 'House Renter' ? 'selected' : '' }}>House Renter</option>
									<option value="Sharer" {{ $client->indicate == 'Sharer' ? 'selected' : '' }}>Sharer</option>
									<option value="Settler" {{ $client->indicate == 'Settler' ? 'selected' : '' }}>Settler</option>
								</select>
							</div>
							<div class="col-md-4 form-group">
								<label for="tracking">Status</label>
								<select name="tracking" class="form-control" style="border: none; border-bottom: 1px solid black; outline: none; width: 200px;" id="tracking">
									<option value="" selected disable>Select Status</option>
									<option value="Approved" {{ $client->tracking == 'Approved' ? 'selected' : '' }}>Approved</option>
									<option value="Pending" {{ $client->tracking == 'Pending' ? 'selected' : '' }}>Pending</option>
								</select>
							</div>
							<div class="col-md-4 form-group">
								<label for="other_appliances">Other Appliances</label>
								<input type="text" class="form-control" id="other_appliances" style="border: none; border-bottom: 1px solid black; outline: none; width: 200px;" name="other_appliances" value="{{ $client->other_appliances }}">
							</div>
							<div class="col-md-4 form-group">
								<label>Appliances</label><br>
								<div class="form-check-row">
									<?php
									$clientAppliances = is_array($client->appliances) ? $client->appliances : json_decode($client->appliances, true);

									$clientAppliances = is_array($clientAppliances) ? $clientAppliances : [];

									$appliances = ['Refrigerator', 'Washing Machine', 'Television', 'Microwave', 'Air Conditioner', 'Electric Fan'];
									?>
									@foreach($appliances as $appliance)
									<div class="form-check">
										<div class="form-check">
											<input type="checkbox" class="form-check-input" name="appliances[]" value="{{ $appliance }}" id="{{ strtolower(str_replace(' ', '-', $appliance)) }}" {{ in_array($appliance, $clientAppliances) ? 'checked' : '' }}>
											<label class="form-check-label" for="{{ strtolower(str_replace(' ', '-', $appliance)) }}">{{ $appliance }}</label>
										</div>

									</div>
									@endforeach
								</div>
							</div>
							<hr>
							<div class="form-group">
								<label for="circumstances_of_referral">Circumstances of Referral</label>
								<textarea name="circumstances_of_referral" class="form-control" id="circumstances_of_referral" placeholder="Referred by barangay due to inability to afford expenses." rows="3" style="max-width: 500px; width: 100%;" {{ $client->problem_identification == 'Done' ? 'readonly' : '' }}>{{ $client->circumstances_of_referral }}</textarea>

							</div>
							<div class="form-group">
								<label for="family_background">Family Background</label>
								<textarea name="family_background" class="form-control" id="family_background" placeholder="Lives with spouse and three children, aged 10, 8, and 5." rows="3" style="max-width: 500px; width: 100%;" {{ $client->problem_identification == 'Done' ? 'readonly' : '' }}>{{ $client->family_background }}</textarea>
							</div>
							<div class="form-group">
								<label for="health_history">Health History of the Applicant</label>
								<textarea name="health_history" class="form-control" id="health_history" placeholder="Diagnosed with hypertension and diabetes." rows="3" style="max-width: 500px; width: 100%;" {{ $client->problem_identification == 'Done' ? 'readonly' : '' }}>{{ $client->health_history }}</textarea>
							</div>
							<div class="form-group">
								<label for="economic_situation">Economic Situation</label>
								<textarea name="economic_situation" class="form-control" id="economic_situation" placeholder="Primary earner of the family with no other sources of income." rows="3" style="max-width: 500px; width: 100%;" {{ $client->problem_identification == 'Done' ? 'readonly' : '' }}>{{ $client->economic_situation }}</textarea>
							</div>

							<br>
							<div class="form-group">
								<label for="problem_presented">Problem Presented</label>
								<textarea name="problem_presented" class="form-control" id="problem_presented" placeholder="The request for burial assistance was submitted due to the financial difficulties faced by the family in managing funeral expenses." rows="5" style="width: 500px; min-height: 150px;" {{ $client->problem_identification == 'Done' ? 'readonly' : '' }}>{{ $client->problem_presented }}</textarea>
							</div>
							<div class="form-group">
								<label for="problem_identification">Problem Identification</label>
								<select name="problem_identification" class="form-control" id="problem_identification" style="max-width: 500px; width: 100%;" {{ $client->problem_identification == 'Done' ? 'disabled' : '' }}>
									<option value="" disabled selected>Select Problem Identification</option>
									<option value="Done" {{ $client->problem_identification == 'Done' ? 'selected' : '' }}>✔️ Done</option>
									<option value="Incomplete" {{ $client->problem_identification == 'Incomplete' ? 'selected' : '' }}>❌ Incomplete</option>
									<!-- 								<option value="Processing" {{ $client->problem_identification == 'Processing' ? 'selected' : '' }}>🔄 Processing</option> -->
								</select>
							</div>
							<hr>
							<style>
								.hidden {
									display: none;
								}
							</style>
							<div class="form-group">
								<h2>Services</h2>
								<?php
								// Assume $client->services is already available and stores the client's services as an array or JSON.
								$clientServices = is_array($client->services) ? $client->services : json_decode($client->services, true);
								$clientServices = is_array($clientServices) ? $clientServices : [];
								?>

								<select id="service-selector-{{ $client->id }}" style="border: none; border-bottom: 1px solid black; outline: none; width: 200px;" class="form-control" onchange="handleServiceChange(this)">


									<!-- Burial Assistance -->
									<option value="burial-assistance" <?php echo in_array('Burial Assistance', $clientServices) ? 'selected' : ''; ?>>Burial Assistance</option>

									<!-- Crisis Intervention Unit -->
									<option value="crisis-intervention" <?php echo in_array('Valid ID', $clientServices) ? 'selected' : ''; ?>>Crisis Intervention Unit</option>

									<!-- Solo Parent Services -->
									<option value="solo-parent" <?php echo in_array('Solo Parent = Agency Referral', $clientServices) ? 'selected' : ''; ?>>Solo Parent Services</option>

									<!-- Pre-marriage Counseling -->
									<option value="premarriage-counseling" <?php echo in_array('Pre-marriage Counseling = Valid ID', $clientServices) ? 'selected' : ''; ?>>Pre-marriage Counseling</option>

									<!-- After-Care Services -->
									<option value="after-care" <?php echo in_array('After-Care Services = Valid ID', $clientServices) ? 'selected' : ''; ?>>After-Care Services</option>

									<!-- Poverty Alleviation Program -->
									<option value="poverty-program" <?php echo in_array('Poverty Alleviation Program = Valid ID', $clientServices) ? 'selected' : ''; ?>>Poverty Alleviation Program</option>
								</select>

								<!-- Modal for Burial Assistance -->
								<div class="modal fade" id="burial-assistance-modal-{{ $client->id }}" tabindex="-1" role="dialog" aria-labelledby="burialAssistanceModalLabel-{{ $client->id }}" aria-hidden="true">
									<div class="modal-dialog" role="document">
										<div class="modal-content">
											<div class="modal-header">
												<h5 class="modal-title" id="burialAssistanceModalLabel-{{ $client->id }}">Burial Assistance Details</h5>
												<button type="button" class="close" data-dismiss="modal" aria-label="Close">
													<span aria-hidden="true">&times;</span>
												</button>
											</div>
											<div class="modal-body">
												<!-- Content from your PHP code -->
												<?php
												$clientServices = is_array($client->services) ? $client->services : json_decode($client->services, true);
												$clientServices = is_array($clientServices) ? $clientServices : [];
												$services = ['Burial', 'Financial', 'Funeral'];
												?>
												@foreach($services as $service)
												<div class="form-check">
													<input type="checkbox" class="form-check-input service-checkbox" name="services[]" value="{{ $service }}" id="{{ strtolower($service) }}-{{ $client->id }}" {{ in_array($service, $clientServices) ? 'checked' : '' }}>
													<label class="form-check-label" for="{{ strtolower($service) }}-{{ $client->id }}">{{ $service }}</label>
												</div>
												@endforeach

												<!-- Additional Requirements -->
												<div class="additional-requirements">
													<h3>Requirements</h3>
													<?php
													$additionalServices = ['Crisis Intervention Unit = Valid ID', 'Barangay Clearance.', 'Medical Certificate.', 'Incident Report.', 'Funeral Contract.', 'Death Certificate.'];
													?>
													@foreach($additionalServices as $service)
													<div class="form-check">
														<input type="checkbox" class="form-check-input" name="services[]" value="{{ $service }}" id="{{ strtolower(str_replace(' ', '-', $service)) }}-{{ $client->id }}" {{ in_array($service, $clientServices) ? 'checked' : '' }}>
														<label class="form-check-label" for="{{ strtolower(str_replace(' ', '-', $service)) }}-{{ $client->id }}">

															{{ $service }}

														</label>
													</div>
													@endforeach
												</div>
											</div>
											<div class="modal-footer">
												<button type="button" class="btn btn-primary" data-dismiss="modal">Save</button>
											</div>
										</div>
									</div>
								</div>
								<script>
									document.addEventListener('DOMContentLoaded', function() {
										const serviceSelector = document.getElementById('service-selector-{{ $client->id }}');

										function handleServiceChange(selectElement) {
											const selectedService = selectElement.value;

											const serviceSections = document.querySelectorAll('.form-check-row');
											serviceSections.forEach(section => section.classList.add('hidden'));


											if (selectedService) {
												const serviceSection = document.getElementById(selectedService + '-{{ $client->id }}');
												if (serviceSection) {
													serviceSection.classList.remove('hidden');
												}

												if (selectedService === 'burial-assistance') {
													$('#burial-assistance-modal-{{ $client->id }}').modal('show');
												} else if (selectedService === 'crisis-intervention') {
													$('#crisis-intervention-modal-{{ $client->id }}').modal('show');
												} else if (selectedService === 'solo-parent') {
													$('#solo-parent-modal-{{ $client->id }}').modal('show');
												} else if (selectedService === 'premarriage-counseling') {
													$('#premarriage-counseling-modal-{{ $client->id }}').modal('show');
												} else if (selectedService === 'after-care') {
													$('#after-care-modal-{{ $client->id }}').modal('show');
												} else if (selectedService === 'poverty-program') {
													$('#poverty-program-modal-{{ $client->id }}').modal('show');
												}
											}
										}

										serviceSelector.addEventListener('change', function() {
											handleServiceChange(this);
										});
									});

									document.addEventListener('DOMContentLoaded', function() {
										const burialCheckbox = document.getElementById('burial-{{ $client->id }}');
										const financialCheckbox = document.getElementById('financial-{{ $client->id }}');
										const funeralCheckbox = document.getElementById('funeral-{{ $client->id }}');

										function updateCheckboxes() {
											const burialChecked = burialCheckbox.checked;
											const financialChecked = financialCheckbox.checked;
											const funeralChecked = funeralCheckbox.checked;

											// Disable 'Funeral' if both 'Financial' and 'Burial' are selected
											if (financialChecked && burialChecked) {
												funeralCheckbox.disabled = true;
											} else {
												funeralCheckbox.disabled = false;
											}

											// Disable 'Burial' if both 'Financial' and 'Funeral' are selected
											if (financialChecked && funeralChecked) {
												burialCheckbox.disabled = true;
											} else {
												burialCheckbox.disabled = false;
											}
										}

										// Attach event listeners
										burialCheckbox.addEventListener('change', updateCheckboxes);
										financialCheckbox.addEventListener('change', updateCheckboxes);
										funeralCheckbox.addEventListener('change', updateCheckboxes);

										// Initialize on page load
										updateCheckboxes();
									});
								</script>


								<!-- Crisis Intervention Modal -->
								<div class="modal fade" id="crisis-intervention-modal-{{ $client->id }}" tabindex="-1" role="dialog" aria-labelledby="crisisInterventionModalLabel-{{ $client->id }}" aria-hidden="true">
									<div class="modal-dialog" role="document">
										<div class="modal-content">
											<div class="modal-header">
												<h5 class="modal-title" id="crisisInterventionModalLabel-{{ $client->id }}">Crisis Intervention Details</h5>
												<button type="button" class="close" data-dismiss="modal" aria-label="Close">
													<span aria-hidden="true">&times;</span>
												</button>
											</div>
											<div class="modal-body">
												<!-- Content from your PHP code -->
												<?php
												$clientServices = is_array($client->services) ? $client->services : json_decode($client->services, true);
												$clientServices = is_array($clientServices) ? $clientServices : [];
												$services = [
													'Valid ID',
													'Residence Certificate or Barangay Clearance',
													'Clinical abstract medical certificate',
													'Police Report or Incident Report',
													'Funeral contract and registered death certificate. (if applicable)',
												];
												$normalizedClientServices = array_map('strtolower', $clientServices);
												?>
												@foreach($services as $service)
												<div class="form-check">
													<input type="checkbox" class="form-check-input" name="services[]" value="{{ $service }}" id="{{ strtolower(str_replace(' ', '-', $service)) }}-{{ $client->id }}" {{ in_array(strtolower($service), $normalizedClientServices) ? 'checked' : '' }}>
													<label class="form-check-label" for="{{ strtolower(str_replace(' ', '-', $service)) }}-{{ $client->id }}">{{ $service }}</label>
												</div>
												@endforeach

											</div>
											<div class="modal-footer">
												<button type="button" class="btn btn-primary" data-dismiss="modal">Save</button>
											</div>
										</div>
									</div>
								</div>

								<div class="modal fade" id="solo-parent-modal-{{ $client->id }}" tabindex="-1" role="dialog" aria-labelledby="soloParentModalLabel-{{ $client->id }}" aria-hidden="true">
									<div class="modal-dialog" role="document">
										<div class="modal-content">
											<div class="modal-header">
												<h5 class="modal-title" id="soloParentModalLabel-{{ $client->id }}">Solo Parent Details</h5>
												<button type="button" class="close" data-dismiss="modal" aria-label="Close">
													<span aria-hidden="true">&times;</span>
												</button>
											</div>
											<div class="modal-body">
												<?php
												$clientServices = is_array($client->services) ? $client->services : json_decode($client->services, true);
												$clientServices = is_array($clientServices) ? $clientServices : [];
												$services = [
													'Solo Parent = Agency Referral',
													'Residency Cert.',
													'Medical Cert.',
													'Billing Proof',
													'Birth Cert.',
													'ID Copy',
													'Senior Citizen ID (60+)'
												];
												?>
												@foreach($services as $service)
												<div class="form-check">
													<input type="checkbox" class="form-check-input" name="services[]" value="{{ $service }}" id="{{ strtolower(str_replace(' ', '-', $service)) }}-{{ $client->id }}" {{ in_array($service, $clientServices) ? 'checked' : '' }}>
													<label class="form-check-label" for="{{ strtolower(str_replace(' ', '-', $service)) }}-{{ $client->id }}">
														@if ($service === 'Solo Parent = Agency Referral')
														Agency Referral
														@else
														{{ $service }}
														@endif
													</label>
												</div>
												@endforeach

											</div>
											<div class="modal-footer">
												<button type="button" class="btn btn-primary" data-dismiss="modal">Save</button>
											</div>
										</div>
									</div>
								</div>

								<!-- Pre-marriage Counseling Section -->


								<div class="modal fade" id="premarriage-counseling-modal-{{ $client->id }}" tabindex="-1" role="dialog" aria-labelledby="premarriageCounselingModalLabel-{{ $client->id }}" aria-hidden="true">
									<div class="modal-dialog" role="document">
										<div class="modal-content">
											<div class="modal-header">
												<h5 class="modal-title" id="premarriageCounselingModalLabel-{{ $client->id }}">Pre Marriaging Details</h5>
												<button type="button" class="close" data-dismiss="modal" aria-label="Close">
													<span aria-hidden="true">&times;</span>
												</button>
											</div>
											<div class="modal-body">
												<?php
												$clientServices = is_array($client->services) ? $client->services : json_decode($client->services, true);
												$clientServices = is_array($clientServices) ? $clientServices : [];
												$services = [
													'Pre-marriage Counseling = Valid ID',
													'Birth Certificate',
													'CENOMAR',
													'Barangay Clearance',
													'Passport-sized Photos',
												];
												?>
												@foreach($services as $service)
												<div class="form-check">
													<input type="checkbox" class="form-check-input" name="services[]" value="{{ $service }}" id="{{ strtolower(str_replace(' ', '-', $service)) }}-{{ $client->id }}" {{ in_array($service, $clientServices) ? 'checked' : '' }}>
													<label class="form-check-label" for="{{ strtolower(str_replace(' ', '-', $service)) }}-{{ $client->id }}">
														@if ($service === 'Pre-marriage Counseling = Valid ID')
														Valid ID
														@else
														{{ $service }}
														@endif
													</label>
												</div>
												@endforeach

											</div>
											<div class="modal-footer">
												<button type="button" class="btn btn-primary" data-dismiss="modal">Save</button>
											</div>
										</div>
									</div>
								</div>



								<div class="modal fade" id="after-care-modal-{{ $client->id }}" tabindex="-1" role="dialog" aria-labelledby="afterCareModalLabel-{{ $client->id }}" aria-hidden="true">
									<div class="modal-dialog" role="document">
										<div class="modal-content">
											<div class="modal-header">
												<h5 class="modal-title" id="afterCareModalLabel-{{ $client->id }}">After Care Details</h5>
												<button type="button" class="close" data-dismiss="modal" aria-label="Close">
													<span aria-hidden="true">&times;</span>
												</button>
											</div>
											<div class="modal-body">
												<?php
												$clientServices = is_array($client->services) ? $client->services : json_decode($client->services, true);
												$clientServices = is_array($clientServices) ? $clientServices : [];
												$services = [
													'After-Care Services = Valid ID',
													'Birth Certificate.',
													'Residence Certificate.',
													'SCSR',
													'Medical Records',
												];
												?>
												@foreach($services as $service)
												<div class="form-check">
													<input type="checkbox" class="form-check-input" name="services[]" value="{{ $service }}" id="{{ strtolower(str_replace(' ', '-', $service)) }}-{{ $client->id }}" {{ in_array($service, $clientServices) ? 'checked' : '' }}>
													<label class="form-check-label" for="{{ strtolower(str_replace(' ', '-', $service)) }}-{{ $client->id }}">
														@if ($service === 'After-Care Services = Valid ID')
														Valid ID
														@else
														{{ $service }}
														@endif
													</label>
												</div>
												@endforeach

											</div>
											<div class="modal-footer">
												<button type="button" class="btn btn-primary" data-dismiss="modal">Save</button>
											</div>
										</div>
									</div>
								</div>

								<!-- Poverty Alleviation Program Section -->
								<div class="form-check-row hidden" id="poverty-program-{{ $client->id }}">

								</div>
							</div>

							<div class="modal fade" id="poverty-program-modal-{{ $client->id }}" tabindex="-1" role="dialog" aria-labelledby="povertyProgramModalLabel-{{ $client->id }}" aria-hidden="true">
								<div class="modal-dialog" role="document">
									<div class="modal-content">
										<div class="modal-header">
											<h5 class="modal-title" id="povertyProgramModalLabel-{{ $client->id }}">Poverty Program Details</h5>
											<button type="button" class="close" data-dismiss="modal" aria-label="Close">
												<span aria-hidden="true">&times;</span>
											</button>
										</div>
										<div class="modal-body">
											<?php
											$clientServices = is_array($client->services) ? $client->services : json_decode($client->services, true);
											$clientServices = is_array($clientServices) ? $clientServices : [];
											$services = [
												'Poverty Alleviation Program = Valid ID',
												'Residence Certificate',
												'Income Certificate',
												'Application Form',
											];
											?>
											@foreach($services as $service)
											<div class="form-check">
												<input type="checkbox" class="form-check-input" name="services[]" value="{{ $service }}" id="{{ strtolower(str_replace(' ', '-', $service)) }}-{{ $client->id }}" {{ in_array($service, $clientServices) ? 'checked' : '' }}>
												<label class="form-check-label" for="{{ strtolower(str_replace(' ', '-', $service)) }}-{{ $client->id }}">
													@if ($service === 'Poverty Alleviation Program = Valid ID')
													Valid ID
													@else
													{{ $service }}
													@endif
												</label>
											</div>
											@endforeach
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-primary" data-dismiss="modal">Save</button>
										</div>
									</div>
								</div>
							</div>


							<script>
								document.addEventListener('DOMContentLoaded', function() {
									const serviceSelector = document.getElementById('service-selector-{{ $client->id }}');

									function handleServiceChange(selectElement) {
										const selectedService = selectElement.value;
										const serviceSections = document.querySelectorAll('.form-check-row');
										serviceSections.forEach(section => section.classList.add('hidden'));

										if (selectedService) {
											document.getElementById(selectedService + '-{{ $client->id }}').classList.remove('hidden');
										}
									}

									serviceSelector.addEventListener('change', function() {
										handleServiceChange(this);
									});
								});
							</script>

							<hr>
							<div class="col-md-12 form-group">
								<label for="home_visit">Home Visit Date</label>
								<input type="date" name="home_visit" class="form-control" id="home_visit" value="{{ $client->home_visit }}" style="border: none; border-bottom: 1px solid black; outline: none; width: 200px;" {{ $client->data_gather == 'Done' ? 'readonly' : '' }}>
							</div>
							<div class="col-md-12 form-group">
								<label for="interviewee">Interviewee</label>
								<input type="text" class="form-control" id="interviewee" name="interviewee" value="{{ $client->interviewee }}" placeholder="Enter Interviewee" style="border: none; border-bottom: 1px solid black; outline: none; width: 200px;" {{ $client->data_gather == 'Done' ? 'readonly' : '' }}>
							</div>
							<div class="col-md-12 form-group">
								<label for="interviewed_by">Interviewed By</label>
								<input type="text" class="form-control" id="interviewed_by" name="interviewed_by" value="{{ $client->interviewed_by }}" placeholder="Enter Interviewed By" style="border: none; border-bottom: 1px solid black; outline: none; width: 200px;" {{ $client->data_gather == 'Done' ? 'readonly' : '' }}>
							</div>
							<div class="col-md-12 form-group">
								<label for="layunin">Layunin Ng Pagbisita</label>
								<textarea name="layunin" class="form-control" id="layunin" placeholder="the social worker confirmed that the Applicant's household has stable electricity, clean running water with adequate pressure, and operational sanitation facilities. These findings will guide future support and interventions as necessary." style="width: 500px; height: 150px;" {{ $client->data_gather == 'Done' ? 'readonly' : '' }}>{{ $client->layunin }}</textarea>
							</div>
							<div class="col-md-12 form-group">
								<label for="resulta">Resulta Ng Pagbisita</label>
								<textarea name="resulta" class="form-control" id="resulta" placeholder="Applicant resides in a one-bedroom apartment with adequate space and basic furnishings. The environment appears clean and well-maintained" style="width: 500px; height: 150px;">{{ $client->resulta }}</textarea>
							</div>
							<div class="col-md-12 form-group">
								<label for="initial_agreement">Initial Agreement</label>
								<textarea name="initial_agreement" class="form-control" id="initial_agreement" placeholder="The initial agreement outlines that the organization will provide financial assistance for the funeral expenses. The family agrees to submit all required documentation and cooperate with the assessment process to ensure timely support." style="width: 500px; height: 150px;" {{ $client->data_gather == 'Done' ? 'readonly' : '' }}>{{ $client->initial_agreement }}</textarea>
							</div>
							<div class="col-md-3 form-group">
								<label for="data_gather">Data Gathering</label>
								<select name="data_gather" class="form-control" id="data_gather" style="max-width: 500px; width: 100%;" {{ $client->data_gather == 'Done' ? 'disabled' : '' }}>
									<option value="" disabled selected>Select Data Gathering</option>
									<option value="Done" {{ $client->data_gather == 'Done' ? 'selected' : '' }}>✔️ Done</option>
									<option value="Incomplete" {{ $client->data_gather == 'Incomplete' ? 'selected' : '' }}>❌ Incomplete</option>
									<!-- <option value="Processing" {{ $client->data_gather == 'Processing' ? 'selected' : '' }}>🔄 Processing</option>
							 -->
								</select>
							</div>
							<div class="col-md-18 form-group">
								<label for="assessment1">Assessment (may include psycho-social functioning, family functioning, environmental factors)</label>
								<textarea name="assessment1" class="form-control custom-textarea" id="assessment1" style="width: 500px; height: 150px;" placeholder="The family is experiencing severe emotional distress and financial hardship due to a recent loss. Communication is strained, leading to conflicts, but there is a strong desire to support each other. Their living conditions are modest with limited access to resources and community services, further complicating their situation." {{ $client->assessment == 'Done' ? 'readonly' : '' }}>{{ $client->assessment1 }}</textarea>
							</div>
							<div class="col-md-12 form-group">
								<label for="assessment">Assessment</label>
								<select name="assessment" class="form-control" id="assessment" style="max-width: 500px; width: 100%;" {{ $client->assessment == 'Done' ? 'disabled' : '' }}>
									<option value="" disabled selected>Select Assessment</option>
									<option value="Done" {{ $client->assessment == 'Done' ? 'selected' : '' }}>✔️ Done</option>
									<option value="Incomplete" {{ $client->assessment == 'Incomplete' ? 'selected' : '' }}>❌ Incomplete</option>
									<!-- <option value="Processing" {{ $client->assessment == 'Processing' ? 'selected' : '' }}>🔄 Processing</option>
						 -->
								</select>
							</div>
							<div class="col-md-12 form-group">
								<label for="case_management_evaluation">Case Management Evaluation</label>
								<textarea name="case_management_evaluation" class="form-control" style="width: 500px; height: 150px;" id="case_management_evaluation" placeholder="Immediate financial assistance was provided for funeral expenses. Psycho-social support services were initiated, showing improved family communication and emotional stability. Continued financial support is recommended due to ongoing instability." style="width: 50%;" {{ $client->eval == 'Done' ? 'readonly' : '' }}>{{ $client->case_management_evaluation }}</textarea>
							</div>
							<div class="col-md-12 form-group">
								<label for="case_resolution">Case Resolution</label>
								<textarea name="case_resolution" class="form-control" id="case_resolution" style="width: 500px; height: 150px;" placeholder="Financial aid for funeral costs was provided, and the family received counseling and community resource referrals. The family is now on a path to recovery and improved stability." style="width: 50%;" {{ $client->eval == 'Done' ? 'readonly' : '' }}>{{ $client->case_resolution }}</textarea>
							</div>
							<div class="col-md-10 form-group">
								<label for="tracking">Case Status</label>
								<select name="tracking" class="form-control" id="tracking" style="max-width: 500px; width: 100%;">
									<option value="" selected disabled>Select Case Status</option>
									<option value="Approve" {{ $client->tracking == 'Approve' ? 'selected' : '' }}>Approve (and close this case)</option>
									<option value="Re-access" {{ $client->tracking == 'Re-access' ? 'selected' : '' }}>Re-access (On-Going case)</option>
								</select>
							</div>
							<div class="col-md-10 form-group">
								<label for="reviewing">Reviewing Officer (Social Welfare Offices)</label>
								<input type="text" class="form-control" id="reviewing" name="reviewing"
									value="{{ old('reviewing', $user->firstname . ' ' . $user->lastname) }}"
									placeholder="Enter Reviewing Officer"
									style="max-width: 500px; width: 100%;"
									{{ $client->eval == 'Done' ? 'readonly' : '' }}>
							</div>

							<div class="col-md-10 form-group">
								<label for="approving">Approving Officer</label>
								<input type="text" class="form-control" id="approving" name="approving" value="{{ $client->approving }}" placeholder="Enter Approving Officer" style="max-width: 500px; width: 100%;" {{ $client->eval == 'Done' ? 'readonly' : '' }}>
							</div>

							<div class="col-md-10 form-group">
								<label for="eval">Evaluation</label>
								<select name="eval" class="form-control" id="eval" style="max-width: 500px; width: 100%;" {{ $client->eval == 'Done' ? 'disabled' : '' }}>
									<option value="" disabled selected>Select Evaluation</option>
									<option value="Done" {{ $client->eval == 'Done' ? 'selected' : '' }}>✔️ Done</option>
									<option value="Incomplete" {{ $client->eval == 'Incomplete' ? 'selected' : '' }}>❌ Incomplete</option>
									<!-- 	<option value="Processing" {{ $client->eval == 'Processing' ? 'selected' : '' }}>🔄 Processing</option>
							 -->
								</select>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
								<button type="button" class="btn btn-primary" onclick="submitEditForm({{ $client->id }})">
									<i class="fas fa-save"></i> Update
								</button>
								<button type="button" class="btn btn-danger" onclick="generatePdf({{ $client->id }})">
									<i class="fas fa-file-pdf"></i> Generate PDF
								</button>

							</div>
						</div>
					</form>
				</div>
			</div>
		</div>

	</div>
	</div>
	@endforeach

	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<script>
		function submitEditForm(clientId) {
			var form = $('#editClientForm' + clientId);
			var formData = form.serializeArray(); // Get form data

			form.find('select[name="tracking"]').each(function(index) {
				var trackingValue = $(this).val(); // Get the value of the current select element

				/* 	console.log('Tracking field ' + index + ':', trackingValue); // Log the value for reference
				 */
				// Compare the value with "Approve"
				if (trackingValue === 'Approve') {
					Swal.fire({
						title: 'Are you sure?',
						text: "You are about to approve and close this case!",
						icon: 'warning',
						showCancelButton: true,
						confirmButtonText: 'Yes, approve it!',
						cancelButtonText: 'Cancel'
					}).then((result) => {
						if (result.isConfirmed) {
							// Submit form if confirmed
							submitFormData(clientId, formData);
						} else {
							// Reset selection if canceled
							this.value = "";
						}
					});

				} else if (trackingValue === 'Re-access') {
					submitFormData(clientId, formData);
				}
			});
		}


		function confirmDelete(clientId) {
			Swal.fire({
				title: 'Are you sure?',
				text: "You won't be able to revert this!",
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Yes, delete it!'
			}).then((result) => {
				if (result.isConfirmed) {
					// Submit the form via AJAX to avoid reloading the page immediately
					fetch(document.getElementById(`delete-form-${clientId}`).action, {
						method: 'POST',
						body: new FormData(document.getElementById(`delete-form-${clientId}`)),
					}).then(() => {
						Swal.fire({
							title: 'Deleted!',
							text: 'The client has been deleted.',
							icon: 'success',
							confirmButtonColor: '#3085d6',
						}).then(() => {
							// Reload the page or redirect after the user clicks "OK"
							window.location.reload();
						});
					});
				}
			});
		}


		function submitFormData(clientId, formData) {
			var url = '/social-worker/update/' + clientId;

			$.ajax({
				url: url,
				type: 'PUT',
				data: $.param(formData, true),

				success: function(response) {
					// Handle successful response
					var updatedClient = response.client;
					console.log(formData);


					// Update table rows with the new data
					$('tr[data-client-id="' + clientId + '"] td[data-field="first_name"]').text(updatedClient.first_name);
					$('tr[data-client-id="' + clientId + '"] td[data-field="last_name"]').text(updatedClient.last_name);
					$('tr[data-client-id="' + clientId + '"] td[data-field="middle"]').text(updatedClient.middle);
					$('tr[data-client-id="' + clientId + '"] td[data-field="suffix"]').text(updatedClient.suffix);
					$('tr[data-client-id="' + clientId + '"] td[data-field="address"]').text(updatedClient.address);
					$('tr[data-client-id="' + clientId + '"] td[data-field="date_of_birth"]').text(updatedClient.date_of_birth);
					$('tr[data-client-id="' + clientId + '"] td[data-field="pob"]').text(updatedClient.pob);
					$('tr[data-client-id="' + clientId + '"] td[data-field="sex"]').text(updatedClient.sex);
					$('tr[data-client-id="' + clientId + '"] td[data-field="educational_attainment"]').text(updatedClient.educational_attainment);
					$('tr[data-client-id="' + clientId + '"] td[data-field="civil_status"]').text(updatedClient.civil_status);
					$('tr[data-client-id="' + clientId + '"] td[data-field="religion"]').text(updatedClient.religion);
					$('tr[data-client-id="' + clientId + '"] td[data-field="nationality"]').text(updatedClient.nationality);
					$('tr[data-client-id="' + clientId + '"] td[data-field="occupation"]').text(updatedClient.occupation);
					$('tr[data-client-id="' + clientId + '"] td[data-field="monthly_income"]').text(updatedClient.monthly_income);
					$('tr[data-client-id="' + clientId + '"] td[data-field="contact_number"]').text(updatedClient.contact_number);
					$('tr[data-client-id="' + clientId + '"] td[data-field="source_of_referral"]').text(updatedClient.source_of_referral);
					$('tr[data-client-id="' + clientId + '"] td[data-field="house_structure"]').text(updatedClient.house_structure);
					$('tr[data-client-id="' + clientId + '"] td[data-field="floor"]').text(updatedClient.floor);
					$('tr[data-client-id="' + clientId + '"] td[data-field="type"]').text(updatedClient.type);
					$('tr[data-client-id="' + clientId + '"] td[data-field="number_of_rooms"]').text(updatedClient.number_of_rooms);
					$('tr[data-client-id="' + clientId + '"] td[data-field="monthly_expenses"]').text(updatedClient.monthly_expenses);
					$('tr[data-client-id="' + clientId + '"] td[data-field="indicate"]').text(updatedClient.indicate);
					$('tr[data-client-id="' + clientId + '"] td[data-field="tracking"]').text(updatedClient.tracking);
					$('tr[data-client-id="' + clientId + '"] td[data-field="other_appliances"]').text(updatedClient.other_appliances);
					$('tr[data-client-id="' + clientId + '"] td[data-field="appliances"]').text(updatedClient.appliances);
					$('tr[data-client-id="' + clientId + '"] td[data-field="circumstances_of_referral"]').text(updatedClient.circumstances_of_referral);
					$('tr[data-client-id="' + clientId + '"] td[data-field="family_background"]').text(updatedClient.family_background);
					$('tr[data-client-id="' + clientId + '"] td[data-field="health_history"]').text(updatedClient.health_history);
					$('tr[data-client-id="' + clientId + '"] td[data-field="economic_situation"]').text(updatedClient.economic_situation);
					$('tr[data-client-id="' + clientId + '"] td[data-field="problem_presented"]').text(updatedClient.problem_presented);
					$('tr[data-client-id="' + clientId + '"] td[data-field="problem_identification"]').text(updatedClient.problem_identification);
					$('tr[data-client-id="' + clientId + '"] td[data-field="services"]').text(updatedClient.services);
					$('tr[data-client-id="' + clientId + '"] td[data-field="home_visit"]').text(updatedClient.home_visit);
					$('tr[data-client-id="' + clientId + '"] td[data-field="interviewee"]').text(updatedClient.interviewee);
					$('tr[data-client-id="' + clientId + '"] td[data-field="interviewed_by"]').text(updatedClient.interviewed_by);
					$('tr[data-client-id="' + clientId + '"] td[data-field="layunin"]').text(updatedClient.layunin);
					$('tr[data-client-id="' + clientId + '"] td[data-field="resulta"]').text(updatedClient.resulta);
					$('tr[data-client-id="' + clientId + '"] td[data-field="initial_agreement"]').text(updatedClient.initial_agreement);
					$('tr[data-client-id="' + clientId + '"] td[data-field="data_gather"]').text(updatedClient.data_gather);
					$('tr[data-client-id="' + clientId + '"] td[data-field="assessment1"]').text(updatedClient.assessment1);
					$('tr[data-client-id="' + clientId + '"] td[data-field="assessment"]').text(updatedClient.assessment);
					$('tr[data-client-id="' + clientId + '"] td[data-field="case_management_evaluation"]').text(updatedClient.case_management_evaluation);
					$('tr[data-client-id="' + clientId + '"] td[data-field="case_resolution"]').text(updatedClient.case_resolution);
					$('tr[data-client-id="' + clientId + '"] td[data-field="tracking"]').text(updatedClient.tracking);
					$('tr[data-client-id="' + clientId + '"] td[data-field="reviewing"]').text(updatedClient.reviewing);
					$('tr[data-client-id="' + clientId + '"] td[data-field="approving"]').text(updatedClient.approving);
					$('tr[data-client-id="' + clientId + '"] td[data-field="eval"]').text(updatedClient.eval);

					$('#openEditModal' + clientId).modal('hide');

					// Show SweetAlert success message and refresh the page on confirmation
					Swal.fire({
						icon: 'success',
						title: 'Client Updated',
						text: 'Client has been updated successfully!',
						confirmButtonText: 'OK'
					}).then((result) => {
						if (result.isConfirmed) {
							// Refresh the page
							location.reload();
						}
					});
				},
				error: function(xhr, status, error) {
					console.error("Error details:", {
						status: status,
						error: error,
						response: xhr.responseText
					});

					// Show SweetAlert error message
					Swal.fire({
						icon: 'error',
						title: 'Update Failed',
						text: 'An error occurred while updating the client.',
						confirmButtonText: 'OK'
					});
				}
			});
		}
	</script>

	@foreach ($clients as $client)
    <div class="modal fade" id="familyMembersModal{{ $client->id }}" tabindex="-1" role="dialog" aria-labelledby="familyMembersModalLabel{{ $client->id }}" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="familyMembersModalLabel{{ $client->id }}">Family Members of {{ $client->first_name }} {{ $client->last_name }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Last Name</th>
                                    <th>First Name</th>
                                    <th>Middle Name</th>
                                    <th>Relationship to the Client</th>
                                    <th>Birthday</th>
                                    <th>Age</th>
                                    <th>Sex</th>
                                    <th>Civil Status</th>
                                    <th>Educational Attainment</th>
                                    <th>Occupation</th>
                                    <th>Monthly Income</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($client->familyMembers as $familyMember)
                                <tr>
                                    <td>{{ $familyMember->fam_lastname }}</td>
                                    <td>{{ $familyMember->fam_firstname }}</td>
                                    <td>{{ $familyMember->fam_middlename }}</td>
                                    <td>{{ $familyMember->fam_relationship }}</td>
                                    <td>{{ $familyMember->fam_birthday }}</td>
                                    <td>{{ $familyMember->fam_age }}</td>
                                    <td>{{ $familyMember->fam_gender }}</td>
                                    <td>{{ $familyMember->fam_status }}</td>
                                    <td>{{ $familyMember->fam_education }}</td>
                                    <td>{{ $familyMember->fam_occupation }}</td>
                                    <td>{{ $familyMember->fam_income }}</td>
                                    <td>
                                        <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editFamilyMemberModal{{ $familyMember->id }}">Edit</button>
                                        <form action="{{ route('social-worker.family.destroy', $familyMember->id) }}" method="POST" class="d-inline" id="delete-family-form-{{ $familyMember->id }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-danger btn-sm" onclick="confirmDeleteFam({{ $familyMember->id }})">Delete</button>
                                        </form>
                                    </td>
                                </tr>

                                <!-- Edit Family Member Modal -->
                                <div class="modal fade" id="editFamilyMemberModal{{ $familyMember->id }}" tabindex="-1" role="dialog" aria-labelledby="editFamilyMemberModalLabel{{ $familyMember->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-lg" role="document">
                                        <div class="modal-content">
                                            <form method="POST" action="{{ route('social-worker.family.update', $familyMember->id) }}">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="client_id" value="{{ $client->id }}">

                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="editFamilyMemberModalLabel{{ $familyMember->id }}">Edit Family Member</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>

                                                <div class="modal-body">
                                                    <!-- First Row: Name fields -->
                                                    <div class="row">
                                                        <div class="form-group col-md-4">
                                                            <label for="fam_lastname">Last Name</label>
                                                            <input type="text" name="fam_lastname" class="form-control" value="{{ $familyMember->fam_lastname }}" required>
                                                        </div>
                                                        <div class="form-group col-md-4">
                                                            <label for="fam_firstname">First Name</label>
                                                            <input type="text" name="fam_firstname" class="form-control" value="{{ $familyMember->fam_firstname }}" required>
                                                        </div>
                                                        <div class="form-group col-md-4">
                                                            <label for="fam_middlename">Middle Name</label>
                                                            <input type="text" name="fam_middlename" class="form-control" value="{{ $familyMember->fam_middlename }}">
                                                        </div>
                                                    </div>

                                                    <!-- Second Row: Relationship, Gender, and Birthday -->
                                                    <div class="row">
                                                        <div class="form-group col-md-4">
                                                            <label for="fam_relationship">Relationship</label>
                                                            <select name="fam_relationship" class="form-control">
                                                                <option value="Parent" {{ $familyMember->fam_relationship == 'Parent' ? 'selected' : '' }}>Parent</option>
                                                                <option value="Sibling" {{ $familyMember->fam_relationship == 'Sibling' ? 'selected' : '' }}>Sibling</option>
                                                                <option value="Child" {{ $familyMember->fam_relationship == 'Child' ? 'selected' : '' }}>Child</option>
                                                                <option value="Spouse" {{ $familyMember->fam_relationship == 'Spouse' ? 'selected' : '' }}>Spouse</option>
                                                                <option value="Relative" {{ $familyMember->fam_relationship == 'Relative' ? 'selected' : '' }}>Relative</option>
                                                                <option value="Other" {{ $familyMember->fam_relationship == 'Other' ? 'selected' : '' }}>Other</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group col-md-4">
                                                            <label for="fam_gender">Gender</label>
                                                            <select name="fam_gender" class="form-control">
                                                                <option value="Male" {{ $familyMember->fam_gender == 'Male' ? 'selected' : '' }}>Male</option>
                                                                <option value="Female" {{ $familyMember->fam_gender == 'Female' ? 'selected' : '' }}>Female</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group col-md-4">
                                                            <label for="fam_birthday">Birthday</label>
                                                            <input type="date" name="fam_birthday" id="edit_fam_birthday_{{ $familyMember->id }}" class="form-control" value="{{ $familyMember->fam_birthday }}">
                                                        </div>
                                                    </div>

                                                    <!-- Third Row: Age, Status, and Education -->
                                                    <div class="row">
                                                        <div class="form-group col-md-4">
                                                            <label for="fam_age">Age</label>
                                                            <input type="text" name="fam_age" id="edit_fam_age_{{ $familyMember->id }}" class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-4">
                                                            <label for="fam_status">Status</label>
                                                            <select name="fam_status" class="form-control">
                                                                <option value="Single" {{ $familyMember->fam_status == 'Single' ? 'selected' : '' }}>Single</option>
                                                                <option value="Married" {{ $familyMember->fam_status == 'Married' ? 'selected' : '' }}>Married</option>
                                                                <option value="Widowed" {{ $familyMember->fam_status == 'Widowed' ? 'selected' : '' }}>Widowed</option>
                                                                <option value="Separated" {{ $familyMember->fam_status == 'Separated' ? 'selected' : '' }}>Separated</option>
                                                                <option value="Divorced" {{ $familyMember->fam_status == 'Divorced' ? 'selected' : '' }}>Divorced</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group col-md-4">
                                                            <label for="fam_education">Education</label>
                                                            <select name="fam_education" class="form-control">
                                                                <option value="" disabled>Select Educational Attainment</option>
                                                                <option value="No Formal Education" {{ $familyMember->fam_education == 'No Formal Education' ? 'selected' : '' }}>No Formal Education</option>
                                                                <option value="Elementary - Some" {{ $familyMember->fam_education == 'Elementary - Some' ? 'selected' : '' }}>Elementary - Some</option>
                                                                <option value="Elementary Graduate" {{ $familyMember->fam_education == 'Elementary Graduate' ? 'selected' : '' }}>Elementary Graduate</option>
                                                                <option value="High School - Some" {{ $familyMember->fam_education == 'High School - Some' ? 'selected' : '' }}>High School - Some</option>
                                                                <option value="High School Graduate" {{ $familyMember->fam_education == 'High School Graduate' ? 'selected' : '' }}>High School Graduate</option>
                                                                <option value="Vocational/Technical" {{ $familyMember->fam_education == 'Vocational/Technical' ? 'selected' : '' }}>Vocational/Technical</option>
                                                                <option value="College - Some" {{ $familyMember->fam_education == 'College - Some' ? 'selected' : '' }}>College - Some</option>
                                                                <option value="Associate Degree" {{ $familyMember->fam_education == 'Associate Degree' ? 'selected' : '' }}>Associate Degree</option>
                                                                <option value="Bachelor's Degree" {{ $familyMember->fam_education == "Bachelor's Degree" ? 'selected' : '' }}>Bachelor's Degree</option>
                                                                <option value="Master's Degree" {{ $familyMember->fam_education == "Master's Degree" ? 'selected' : '' }}>Master's Degree</option>
                                                                <option value="Doctorate Degree" {{ $familyMember->fam_education == "Doctorate Degree" ? 'selected' : '' }}>Doctorate Degree</option>
                                                                <option value="Other" {{ $familyMember->fam_education == 'Other' ? 'selected' : '' }}>Other</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <!-- Fourth Row: Occupation and Monthly Income -->
                                                    <div class="row">
                                                        <div class="form-group col-md-6">
                                                            <label for="fam_occupation">Occupation</label>
                                                            <input type="text" name="fam_occupation" class="form-control" value="{{ $familyMember->fam_occupation }}">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label for="fam_income">Monthly Income</label>
                                                            <select name="fam_income" class="form-control">
                                                                <option value="0" {{ $familyMember->fam_income == '0' ? 'selected' : '' }}>Below 5,000</option>
                                                                <option value="5000" {{ $familyMember->fam_income == '5000' ? 'selected' : '' }}>5,000 - 10,000</option>
                                                                <option value="10000" {{ $familyMember->fam_income == '10000' ? 'selected' : '' }}>10,000 - 20,000</option>
                                                                <option value="20000" {{ $familyMember->fam_income == '20000' ? 'selected' : '' }}>20,000 - 30,000</option>
                                                                <option value="30000" {{ $familyMember->fam_income == '30000' ? 'selected' : '' }}>30,000 - 40,000</option>
                                                                <option value="40000" {{ $familyMember->fam_income == '40000' ? 'selected' : '' }}>40,000 - 50,000</option>
                                                                <option value="50000" {{ $familyMember->fam_income == '50000' ? 'selected' : '' }}>50,000 and above</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary">Update Family Member</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <script>
                                    document.getElementById('edit_fam_birthday_{{ $familyMember->id }}').addEventListener('change', function() {
                                        const birthday = new Date(this.value);
                                        const today = new Date();
                                        let age = today.getFullYear() - birthday.getFullYear();
                                        const monthDiff = today.getMonth() - birthday.getMonth();

                                        if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthday.getDate())) {
                                            age--;
                                        }

                                        document.getElementById('edit_fam_age_{{ $familyMember->id }}').value = age;
                                    });
                                </script>

                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <button class="btn btn-success" data-toggle="modal" data-target="#addFamilyMemberModal{{ $client->id }}">Add Family Member</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addFamilyMemberModal{{ $client->id }}" tabindex="-1" role="dialog" aria-labelledby="addFamilyMemberModalLabel{{ $client->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form method="POST" action="{{ route('social-worker.family.store') }}">
                    @csrf
                    <input type="hidden" name="client_id" value="{{ $client->id }}">

                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="addFamilyMemberModalLabel{{ $client->id }}">Add Family Member for {{ $client->first_name }} {{ $client->last_name }}</h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <!-- First Row: Names -->
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label for="fam_lastname">Last Name</label>
                                <input type="text" name="fam_lastname" class="form-control" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="fam_firstname">First Name</label>
                                <input type="text" name="fam_firstname" class="form-control" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="fam_middlename">Middle Name</label>
                                <input type="text" name="fam_middlename" class="form-control">
                            </div>
                        </div>

                        <!-- Second Row: Relationship, Gender -->
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="fam_relationship">Relationship</label>
                                <select name="fam_relationship" id="fam_relationship" class="form-control">
                                    <option value="">Select Relationship</option>
                                    <option value="Parent">Parent</option>
                                    <option value="Sibling">Sibling</option>
                                    <option value="Child">Child</option>
                                    <option value="Spouse">Spouse</option>
                                    <option value="Relative">Relative</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="fam_gender">Gender</label>
                                <select name="fam_gender" class="form-control">
                                    <option value="">Select Gender</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                            </div>
                        </div>

                        <!-- Third Row: Birthday, Age, Status -->
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label for="fam_birthday">Birthday</label>
                                <input type="date" name="fam_birthday" id="fam_birthday" class="form-control">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="fam_age">Age</label>
                                <input type="text" name="fam_age" id="fam_age" class="form-control">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="fam_status">Status</label>
                                <select name="fam_status" id="fam_status" class="form-control">
                                    <option value="">Select Status</option>
                                    <option value="Single">Single</option>
                                    <option value="Married">Married</option>
                                    <option value="Widowed">Widowed</option>
                                    <option value="Separated">Separated</option>
                                    <option value="Divorced">Divorced</option>
                                </select>
                            </div>
                        </div>

                        <!-- Fourth Row: Education, Occupation -->
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="fam_education">Education</label>
                                <select name="fam_education" class="form-control" id="fam_education">
                                    <option value="" disabled selected>Select Educational Attainment</option>
                                    <option value="No Formal Education">No Formal Education</option>
                                    <option value="Elementary - Some">Elementary - Some</option>
                                    <option value="Elementary Graduate">Elementary Graduate</option>
                                    <option value="High School - Some">High School - Some</option>
                                    <option value="High School Graduate">High School Graduate</option>
                                    <option value="Vocational/Technical">Vocational/Technical</option>
                                    <option value="College - Some">College - Some</option>
                                    <option value="Associate Degree">Associate Degree</option>
                                    <option value="Bachelor's Degree">Bachelor's Degree</option>
                                    <option value="Master's Degree">Master's Degree</option>
                                    <option value="Doctorate Degree">Doctorate Degree</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="fam_occupation">Occupation</label>
                                <input type="text" name="fam_occupation" class="form-control">
                            </div>
                        </div>

                        <!-- Fifth Row: Monthly Income -->
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="fam_income">Monthly Income</label>
                                <select name="fam_income" id="fam_income" class="form-control">
                                    <option value="">Select Income Range</option>
                                    <option value="0">Below 5,000</option>
                                    <option value="5000">5,000 - 10,000</option>
                                    <option value="10000">10,000 - 20,000</option>
                                    <option value="20000">20,000 - 30,000</option>
                                    <option value="30000">30,000 - 40,000</option>
                                    <option value="40000">40,000 - 50,000</option>
                                    <option value="50000">50,000 and above</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Add Family Member</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('fam_birthday').addEventListener('change', function() {
            const birthday = new Date(this.value);
            const today = new Date();
            let age = today.getFullYear() - birthday.getFullYear();
            const monthDiff = today.getMonth() - birthday.getMonth();

            // Adjust age if the birthday hasn't occurred yet this year
            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthday.getDate())) {
                age--;
            }

            // Set the calculated age in the age input field
            document.getElementById('fam_age').value = age;
        });
    </script>



	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<script>
		function confirmDeleteFam(familyMemberId) {
			Swal.fire({
				title: 'Are you sure?',
				text: "You won't be able to revert this!",
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Yes, delete it!'
			}).then((result) => {
				if (result.isConfirmed) {
					document.getElementById(`delete-family-form-${familyMemberId}`).submit();
				}
			});
		}
	</script>
	@if(session('add_success'))
	<script>
		Swal.fire({
			title: 'Success!',
			text: 'Family Members Added Successfully.',
			icon: 'success',
			confirmButtonText: 'OK',
			confirmButtonColor: '#3085d6'
		});
	</script>
	@endif
	@if(session('delete_success'))
	<script>
		Swal.fire({
			title: 'Success!',
			text: 'Family Member Deleted Successfully.',
			icon: 'success',
			confirmButtonText: 'OK',
			confirmButtonColor: '#3085d6'
		});
	</script>
	@endif
	@if(session('update_success'))
	<script>
		Swal.fire({
			title: 'Success!',
			text: 'Family Member Updated Successfully.',
			icon: 'success',
			confirmButtonText: 'OK',
			confirmButtonColor: '#3085d6'
		});
	</script>
	@endif

	@endforeach

	@section('scripts')
	<!-- Bootstrap JS and dependencies -->
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

	@endsection
	@endsection

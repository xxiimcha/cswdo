<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>CSWDO - RMS</title>
    <!-- Favicon-->
    <link rel="icon" type="image/x-icon" href="/img/favicon-landing.ico" />
    <!-- Font Awesome icons (free version)-->
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <!-- Simple line icons-->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/simple-line-icons/2.5.5/css/simple-line-icons.min.css" rel="stylesheet" />
    <!-- Google fonts-->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,700,300italic,400italic,700italic" rel="stylesheet" type="text/css" />
    <!-- Core theme CSS (includes Bootstrap)-->
    <link href="css/styles-landing.css" rel="stylesheet" />
    <link href="css/style.css" rel="stylesheet" />
    <meta name="csrf-token" content="{{ csrf_token() }}">


    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.7.1/sweetalert2.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.7.1/sweetalert2.all.min.js"></script>


    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">


    <style>
        /* Improved padding, margin, and box-shadow for form fields */
        .form-control {
            margin-bottom: 15px;
            padding: 10px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        /* Card enhancements */
        .card {
            margin-top: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
            border-radius: 12px;
        }

        /* Section header styling */
        .section-header h1 {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
            color: #4b78cc;
        }

        /* Button styling */
        .btn-primary {
            background-color: #4b78cc;
            border-color: #4b78cc;
        }

        .btn-primary:hover {
            background-color: #3a5f9e;
            border-color: #3a5f9e;
        }

        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
        }

        /* Footer styling */
        .footer {
            background-color: #4b78cc;
            padding: 10px;
            text-align: center;
            color: white;
        }

        /* Responsive layout for smaller screens */
        @media (max-width: 768px) {
            .section-header h1 {
                font-size: 20px;
            }

            .btn-primary, .btn-secondary {
                width: 100%;
                margin-bottom: 10px;
            }
        }
    </style>
</head>

<body id="page-top">

    <script src="{{ asset('js/preregistration.js') }}"></script>

    <div id="successModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <p id="modalMessage"></p>
        </div>
    </div>
    <!-- Navigation-->
    <a class="menu-toggle rounded" href="#"><i class="fas fa-bars"></i></a>
    <nav id="sidebar-wrapper">
        <ul class="sidebar-nav">
            <li class="sidebar-brand"><a href="#page-top">Navigation</a></li>
            <li class="sidebar-nav-item"><a href="/">Home</a></li>
            <li class="sidebar-nav-item"><a href="#services">Services</a></li>
            <li class="sidebar-nav-item"><a href="/preregistration">Application Form</a></a></li>
            <li class="sidebar-nav-item"><a href="#contact">Contact</a></li>
        </ul>
    </nav>

    <div class="main">
        <section class="section">
            <div class="section-header">
                <h1>Pre-Registration Form</h1>
            </div>

            <div class="section-body">
                <div class="card">
                    <div class="card-header">
                        <h4>Please fill in the details</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('preregisters.store') }}" id="preRegistrationForm">
                            @csrf
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="first_name">First Name</label>
                                    <input type="text" name="first_name" class="form-control shadow-sm" id="first_name" placeholder="Enter First Name" Required>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label for="last_name">Last Name</label>
                                    <input type="text" name="last_name" class="form-control shadow-sm" id="last_name" placeholder="Enter Last Name" Required>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label for="middle">Middle Name</label>
                                    <input type="text" name="middle" class="form-control shadow-sm" id="middle" placeholder="Enter Middle Name" Required>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label for="suffix">Suffix</label>
                                    <select name="suffix" class="form-control shadow-sm" id="suffix" required>
                                        <option value="" selected disabled>Select Suffix</option>
                                        <option value="Jr.">Jr. (Junior)</option>
                                        <option value="Sr.">Sr. (Senior)</option>
                                        <option value="II">II (Second)</option>
                                        <option value="III">III (Third)</option>
                                        <option value="IV">IV (Fourth)</option>
                                        <option value="None">None</option>
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="date_of_birth">Date of Birth</label>
                                    <input type="date" name="date_of_birth" class="form-control shadow-sm" id="date_of_birth" placeholder="Enter Date of Birth" required>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label for="age">Age</label>
                                    <input type="text" name="age" class="form-control shadow-sm" id="age" placeholder="Age" readonly>
                                </div>

                                <script>
                                    document.getElementById('date_of_birth').addEventListener('change', function() {
                                        var dob = new Date(this.value);
                                        var today = new Date();
                                        var age = today.getFullYear() - dob.getFullYear();
                                        var monthDifference = today.getMonth() - dob.getMonth();

                                        if (monthDifference < 0 || (monthDifference === 0 && today.getDate() < dob.getDate())) {
                                            age--;
                                        }

                                        document.getElementById('age').value = age;
                                    });
                                </script>


                                <div class="col-md-4 mb-3">
                                    <label for="sex">Sex</label>
                                    <select name="sex" class="form-control shadow-sm" id="sex" Required>
                                        <option value="" selected disabled>Select Sex</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                    </select>
                                </div>


                                <div class="col-md-4 mb-3">
                                    <label for="pob">Place of Birth</label>
                                    <input type="text" name="pob" class="form-control shadow-sm" id="pob" placeholder="Enter Place of Birth" Required>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label for="educational_attainment">Educational Attainment</label>
                                    <select name="educational_attainment" class="form-control shadow-sm" id="educational_attainment" required>
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


                                <div class="col-md-4 mb-3">
                                    <label for="civil_status">Civil Status</label>
                                    <select name="civil_status" class="form-control shadow-sm" id="civil_status" placeholder="Select Civil Status" Required>
                                        <option value="" selected disabled>Select Civil Status</option>
                                        <option value="Single">Single</option>
                                        <option value="Married">Married</option>
                                        <option value="Divorced">Divorced</option>
                                        <option value="Widowed">Widowed</option>
                                        <option value="Separated">Separated</option>
                                    </select>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label for="religion">Religion</label>
                                    <select name="religion" class="form-control shadow-sm" id="religion" onchange="toggleOtherReligion()" Required>
                                        <option value="" selected disabled>Select Religion</option>
                                        <option value="Catholic">Catholic</option>
                                        <option value="Christianity">Christianity</option>
                                        <option value="Islam">Islam</option>
                                        <option value="Hinduism">Hinduism</option>
                                        <option value="Buddhism">Buddhism</option>
                                        <option value="Judaism">Judaism</option>
                                        <option value="Iglesia ni Cristo">Iglesia ni Cristo</option>
                                        <option value="Muslim">Muslim</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>

                                <div class="col-md-4 mb-3" id="otherReligion" style="display: none;">
                                    <label for="other_religion">Other Religion</label>
                                    <input type="text" name="other_religion" class="form-control shadow-sm" id="other_religion">
                                </div>


                                <div class="col-md-4 mb-3">
                                    <label for="nationality">Nationality</label>
                                    <select name="nationality" class="form-control shadow-sm" id="nationality" onchange="toggleOtherNationality()" Required>
                                        <option value="" selected disabled>Select Nationality</option>
                                        <option value="Filipino">Filipino</option>
                                        <option value="American">American</option>
                                        <option value="British">British</option>
                                        <option value="Canadian">Canadian</option>
                                        <option value="Australian">Australian</option>
                                        <option value="Indian">Indian</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>

                                <div class="col-md-4 mb-3" id="otherNationality" style="display: none;">
                                    <label for="other_nationality">Other Nationality</label>
                                    <input type="text" name="other_nationality" class="form-control shadow-sm" id="other_nationality">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="monthly_income">Monthly Income</label>
                                    <select class="form-control shadow-sm" id="monthly_income" name="monthly_income" Required>
                                        <option value="" disabled selected>Select Monthly Income</option>
                                        <option value="No Income">No Income</option>
                                        <option value="100 PHP - 500 PHP">100 PHP - 500 PHP</option>
                                        <option value="500 PHP - 1000 PHP">500 PHP - 1000 PHP</option>
                                        <option value="1000 PHP - 2000 PHP">1000 PHP - 2000 PHP</option>
                                        <option value="2000 PHP - 5000 PHP">2000 PHP - 5000 PHP</option>
                                        <option value="5000 PHP - 6000 PHP">5000 PHP - 6000 PHP</option>
                                        <option value="6000 PHP - 7000 PHP">6000 PHP - 7000 PHP</option>
                                        <option value="7000 PHP - 8000 PHP">7000 PHP - 8000 PHP</option>
                                        <option value="8000 PHP - 9000 PHP">8000 PHP - 9000 PHP</option>
                                        <option value="9000 PHP - 10,000 PHP">9000 PHP - 10,000 PHP</option>
                                        <option value="10,000 PHP - 20,000 PHP">10,000 PHP - 20,000 PHP</option>
                                        <option value="Above 20,000 PHP">Above 20,000 PHP</option>
                                    </select>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label for="building_number">Building / House / Block No.</label>
                                    <input type="text" class="form-control shadow-sm" id="building_number" name="building_number" placeholder="E.g. Blk 123 or No. 4" required>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label for="street_name">Street No. / Name</label>
                                    <input type="text" class="form-control shadow-sm" id="street_name" name="street_name" placeholder="1st Avenue" required>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label for="barangay">Barangay</label>
                                    <select class="form-control shadow-sm" id="barangay" name="barangay" required>
                                        <option value="">Select a Barangay</option>
                                        <option value="Bagumbayan">Bagumbayan</option>
                                        <option value="Bambang">Bambang</option>
                                        <option value="Calzada">Calzada</option>
                                        <option value="Cembo">Cembo</option>
                                        <option value="Central Bicutan">Central Bicutan</option>
                                        <option value="Central Signal Village">Central Signal Village</option>
                                        <option value="Comembo">Comembo</option>
                                        <option value="East Rembo">East Rembo</option>
                                        <option value="Fort Bonifacio">Fort Bonifacio</option>
                                        <option value="Hagonoy">Hagonoy</option>
                                        <option value="Ibayo-Tipas">Ibayo-Tipas</option>
                                        <option value="Katuparan">Katuparan</option>
                                        <option value="Ligid-Tipas">Ligid-Tipas</option>
                                        <option value="Lower Bicutan">Lower Bicutan</option>
                                        <option value="Maharlika Village">Maharlika Village</option>
                                        <option value="Napindan">Napindan</option>
                                        <option value="New Lower Bicutan">New Lower Bicutan</option>
                                        <option value="North Daang Hari">North Daang Hari</option>
                                        <option value="North Signal Village">North Signal Village</option>
                                        <option value="Palingon">Palingon</option>
                                        <option value="Pembo">Pembo</option>
                                        <option value="Pinagsama">Pinagsama</option>
                                        <option value="Pitogo">Pitogo</option>
                                        <option value="Post Proper Northside">Post Proper Northside</option>
                                        <option value="Post Proper Southside">Post Proper Southside</option>
                                        <option value="Rizal">Rizal</option>
                                        <option value="San Miguel">San Miguel</option>
                                        <option value="Santa Ana">Santa Ana</option>
                                        <option value="South Cembo">South Cembo</option>
                                        <option value="South Daang Hari">South Daang Hari</option>
                                        <option value="South Signal Village">South Signal Village</option>
                                        <option value="Tanyag">Tanyag</option>
                                        <option value="Tuktukan">Tuktukan</option>
                                        <option value="Upper Bicutan">Upper Bicutan</option>
                                        <option value="Ususan">Ususan</option>
                                        <option value="Wawa">Wawa</option>
                                        <option value="West Rembo">West Rembo</option>
                                        <option value="Western Bicutan">Western Bicutan</option>
                                    </select>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label for="occupation">Occupation</label>
                                    <input type="text" name="occupation" class="form-control shadow-sm" id="occupation" placeholder="Enter Occupation" Required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="house_structure">House Structure</label>
                                    <select name="house_structure" class="form-control shadow-sm" id="house_structure" placeholder="Select House Structure" Required>
                                        <option value="" selected disabled>Select House Structure</option>
                                        <option value="Wood">Wood</option>
                                        <option value="Semi-concrete">Semi-concrete</option>
                                        <option value="Concrete">Concrete</option>
                                        <option value="Others">Others</option>
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="floor">Floor/Lot Area (sqm)</label>
                                    <select name="floor" class="form-control shadow-sm" id="floor" Required>
                                        <option value="" disabled selected>Select Floor/Lot Area</option>
                                        <option value="0-50">0-50 sqm</option>
                                        <option value="51-100">51-100 sqm</option>
                                        <option value="101-150">101-150 sqm</option>
                                        <option value="151-200">151-200 sqm</option>
                                        <option value="201-300">201-300 sqm</option>
                                        <option value="301-400">301-400 sqm</option>
                                        <option value="401-500">401-500 sqm</option>
                                        <option value="501+">501+ sqm</option>
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="source_of_referral">Source of Referral</label>
                                    <input type="text" name="source_of_referral" class="form-control shadow-sm" id="source_of_referral" placeholder="Enter Source of Referral" Required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="contact_number">Contact Number</label>
                                    <input type="tel" name="contact_number" class="form-control shadow-sm" id="contact_number" placeholder="Enter Contact Number" oninput="this.value=this.value.replace(/[^0-9+#*]/g,'');" required>
                                    <div class="invalid-feedback">Invalid contact number. Please enter only numbers, +, *, and #.</div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="indicate">Indicate If The Applicant Is</label>
                                    <select name="indicate" class="form-control shadow-sm" id="indicate" placeholder="Indicate If The Client Is" Required>
                                        <option value="" selected disabled>Indicate If The Client Is</option>
                                        <option value="House Owner">House Owner</option>
                                        <option value="House Renter">House Renter</option>
                                        <option value="Sharer">Sharer</option>
                                        <option value="Settler">Settler</option>
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="number_of_rooms">Number Of Rooms</label>
                                    <select name="number_of_rooms" class="form-control shadow-sm" id="number_of_rooms" Required>
                                        <option value="" disabled selected>Select Number Of Rooms</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                        <option value="6">6</option>
                                        <option value="7">7</option>
                                        <option value="8">8</option>
                                        <option value="9">9</option>
                                        <option value="10">10</option>
                                    </select>
                                </div>


                                <div class="row">
                                    <h3>Monthly Expense:</h3>
                                    <!-- Electricity -->
                                    <div class="col-md-3 mb-3">
                                        <label for="electricity">Electricity</label>
                                        <select name="electricity" class="form-control shadow-sm" id="electricity">
                                            <option value="" disabled selected>Select Amount</option>
                                            <option value="100-500">100-500</option>
                                            <option value="500-1000">500-1000</option>
                                            <option value="1000-2000">1000-2000</option>
                                            <option value="2000-5000">2000-5000</option>
                                            <option value="5000-10000">5000-10000</option>
                                        </select>
                                    </div>
                                    <!-- Water -->
                                    <div class="col-md-3 mb-3">
                                        <label for="water">Water</label>
                                        <select name="water" class="form-control shadow-sm" id="water">
                                            <option value="" disabled selected>Select Amount</option>
                                            <option value="100-500">100-500</option>
                                            <option value="500-1000">500-1000</option>
                                            <option value="1000-2000">1000-2000</option>
                                            <option value="2000-5000">2000-5000</option>
                                            <option value="5000-10000">5000-10000</option>
                                        </select>
                                    </div>
                                    <!-- Rent -->
                                    <div class="col-md-3 mb-3">
                                        <label for="rent">Rent</label>
                                        <select name="rent" class="form-control shadow-sm" id="rent">
                                            <option value="" disabled selected>Select Amount</option>
                                            <option value="100-500">100-500</option>
                                            <option value="500-1000">500-1000</option>
                                            <option value="1000-2000">1000-2000</option>
                                            <option value="2000-5000">2000-5000</option>
                                            <option value="5000-10000">5000-10000</option>
                                        </select>
                                    </div>
                                    <!-- Other -->
                                    <div class="col-md-3 mb-3">
                                        <label for="other">Other</label>
                                        <select name="other" class="form-control shadow-sm" id="other">
                                            <option value="" disabled selected>Select Amount</option>
                                            <option value="100-500">100-500</option>
                                            <option value="500-1000">500-1000</option>
                                            <option value="1000-2000">1000-2000</option>
                                            <option value="2000-5000">2000-5000</option>
                                            <option value="5000-10000">5000-10000</option>
                                        </select>
                                    </div>
                                </div>



                                <div class="col-md-3 mb-3">
                                    <label for="type">Type Of House</label>
                                    <select name="type" class="form-control shadow-sm" id="type" placeholder="Select Type" onchange="toggleOtherType()" Required>
                                        <option value="" selected disabled>Select Type</option>
                                        <option value="Apartment">Apartment</option>
                                        <option value="Townhouse">Townhouse</option>
                                        <option value="Single-Family Home">Single-Family Home</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3" id="otherType" style="display: none;">
                                    <label for="other_type">Other Type Of House</label>
                                    <input type="text" name="other_type" class="form-control shadow-sm" id="other_type">

                                </div>
                                    <div class="col">
                                        <label for="appliances">Appliances</label>
                                        <div class="form-check-row" Required>
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input " name="appliances[]" value="Refrigerator" id="refrigerator">
                                                <label class="form-check-label" for="refrigerator">Refrigerator</label>
                                            </div>
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" name="appliances[]" value="Washing Machine" id="washing-machine">
                                                <label class="form-check-label" for="washing-machine">Washing Machine</label>
                                            </div>
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" name="appliances[]" value="Television" id="television">
                                                <label class="form-check-label" for="television">Television</label>
                                            </div>
                                        </div>
                                        <div class="form-check-row">
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" name="appliances[]" value="Microwave" id="microwave">
                                                <label class="form-check-label" for="microwave">Microwave</label>
                                            </div>
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" name="appliances[]" value="Air Conditioner" id="air-conditioner">
                                                <label class="form-check-label" for="air-conditioner">Air Conditioner</label>
                                            </div>
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" name="appliances[]" value="Electric Fan" id="electric-fan">
                                                <label class="form-check-label" for="electric-fan">Electric Fan</label>
                                            </div>

                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="other_appliances">Other Appliances</label>
                                            <input type="text" name="other_appliances" class="form-control" id="other_appliances" placeholder="Other Appliances">
                                        </div>

                                    </div>

                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <button type="submit" class="btn btn-primary">Submit</button>
                                <button type="reset" class="btn btn-secondary">Reset</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>

    @include('components.footer')
    <div class="text-center" style="padding: 10px; background-color: #4b78cc; ">
        <p style="margin: 0; color: white;">&copy; 2024 CSWDO - RMS. All rights reserved.</p>
    </div>

    <a class="scroll-to-top rounded" href="#page-top"><i class="fas fa-angle-up"></i></a>
    <!-- Bootstrap core JS-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Core theme JS-->
    <script src="js/scripts-landing.js"></script>
</body>

</html>

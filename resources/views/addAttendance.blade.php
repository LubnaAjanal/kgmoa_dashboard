@extends('layouts.main')

@section('content')
    <div class="col-lg-12 grid-margin stretch-card">
        <!-- Hidden Video Element to show the camera feed -->
        <div class="row">
            <div class="col-md-5">
                <div id="qr-reader" style="width: 100%;"></div>
            </div>
            <div class="col-md-7">
                <form action="#" method="post" class="form-horizontal">
                    <label>SCAN QR CODE</label><br>
                    <button id="addAttendanceBtn" class="btn btn-primary" type="button">Add Attendance</button>
                    <input type="hidden" name="text" id="text" readonly placeholder="scan qr code"
                        class="form-control">
                </form>
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title text-center">Registered Users</h4>
                        <div id="attendanceTableContainer">
                            <table class="table table-hover text-center">
                                <thead>
                                    <tr>
                                        <th>Sl</th>
                                        <th>KMC ID</th>
                                        <th>Name</th>
                                        <th>Attendance</th>
                                    </tr>
                                </thead>
                                <tbody id="attendanceTable">
                                    <!-- Data will be populated here by JavaScript -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Ensure jQuery, Axios, and Html5Qrcode are included before this -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <script>
        $(document).ready(function() {
            // Make the Axios request to the API endpoint to populate the table
            axios.get('/api/attendance')
                .then(response => {
                    const attendances = response.data.data;
                    const tableBody = document.getElementById('attendanceTable');
                    tableBody.innerHTML = ''; // Clear any previous content

                    // Populate the table with the attendance data
                    if (attendances.length > 0) {
                        attendances.forEach((attendance, index) => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                        <td>${index + 1}</td>
                        <td>${attendance.gov_id}</td>
                        <td>${attendance.fullname}</td> 
                        <td></td>
                    `;
                            tableBody.appendChild(row);
                        });
                    } else {
                        const row = document.createElement('tr');
                        row.innerHTML = `<td colspan="4" class="text-center">No Attendees</td>`;
                        tableBody.appendChild(row);
                    }
                })
                .catch(error => {
                    console.error('Error fetching registered users:', error);
                });

            // Initialize QR code scanning when the "Add Attendance" button is clicked
            $('#addAttendanceBtn').on('click', function() {
                const html5QrCode = new Html5Qrcode("qr-reader");

                // Start QR code scanning
                html5QrCode.start({
                        facingMode: "environment"
                    }, // Use the rear camera
                    {
                        fps: 10,
                        qrbox: {
                            width: 250,
                            height: 250
                        }
                    },
                    qrCodeMessage => {
                        // Stop scanning once a QR code is detected
                        html5QrCode.stop().then(() => {
                            console.log("QR Code scanning stopped.");

                            // Send the scanned QR code data to the server
                            axios.post('/api/attendance', {
                                    qr_code: qrCodeMessage
                                })
                                .then(response => {
                                    console.log("Attendance saved:", response.data.message);
                                    alert("Attendance successfully recorded!");

                                    // Optionally, refresh the attendance table
                                    // You could also call a function here to update the table directly without a page refresh
                                })
                                .catch(error => {
                                    console.error("Error saving attendance:", error);
                                    alert("Failed to record attendance.");
                                });
                        }).catch(err => {
                            console.error("Error stopping QR Code scanner:", err);
                        });
                    },
                    errorMessage => {
                        console.warn("QR Code scanning error:", errorMessage);
                    }
                ).catch(err => {
                    console.error("Error starting QR Code scanner:", err);
                });
            });
        });
    </script>
@endsection

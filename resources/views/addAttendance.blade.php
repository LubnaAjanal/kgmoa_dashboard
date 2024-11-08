@extends('layouts.main')

@section('content')
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="row">
            <div class="col-md-5">
                <div id="qr-reader" style="width: 100%;"></div>
                <button id="closeCameraBtn" class="btn btn-primary mt-2" type="button" style="display: none;">Close
                    Camera</button>
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

    <script>
        $(document).ready(function() {
            let html5QrCode;

            // Populate the attendance table
            axios.get('/api/attendance')
                .then(response => {
                    const attendances = response.data.data;
                    const tableBody = document.getElementById('attendanceTable');
                    tableBody.innerHTML = ''; // Clear any previous content

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
                html5QrCode = new Html5Qrcode("qr-reader");

                // Start QR code scanning
                html5QrCode.start({
                        facingMode: "environment"
                    }, {
                        fps: 10,
                        qrbox: {
                            width: 250,
                            height: 250
                        }
                    },
                    qrCodeMessage => {
                        // console.log("QR Code Scanned: ", qrCodeMessage);

                        // Send the scanned QR code to the server
                        axios.post('/api/attendance', {
                                user_unique_id: qrCodeMessage
                            })
                            .then(response => {
                                if (response.data.status) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Success!',
                                        text: response.data.message,
                                        confirmButtonText: 'OK'
                                    }).then(() => {
                                        // window.location.reload();
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error!',
                                        text: response.data.message,
                                        confirmButtonText: 'OK'
                                    });

                                }

                            })
                            .catch(error => {
                                console.error("Error:", error);
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Submission Failed!',
                                    text: 'There was an issue submitting the attendance.',
                                    confirmButtonText: 'OK'
                                });

                            });
                    },
                    errorMessage => {
                        console.warn("Error scanning QR code:", errorMessage);
                    }
                ).catch(err => {
                    console.error("Error starting QR Code scanner:", err);
                });

                // Hide the "Add Attendance" button and show the "Close Camera" button
                $('#addAttendanceBtn').hide();
                $('#closeCameraBtn').show();
            });

            // Stop the QR code scanning when "Close Camera" button is clicked
            $('#closeCameraBtn').on('click', function() {
                if (html5QrCode) {
                    html5QrCode.stop().then(() => {
                        console.log("QR Code scanning stopped.");

                        // Show the "Add Attendance" button and hide the "Close Camera" button
                        $('#addAttendanceBtn').show();
                        $('#closeCameraBtn').hide();
                    }).catch(err => {
                        console.error("Error stopping QR Code scanner:", err);
                    });
                }
            });
        });
    </script>
@endsection

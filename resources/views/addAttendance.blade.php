@extends('layouts.main')

@section('content')
    <div class="row">
        <div class="col-12 col-xl-8 mb-4 mb-xl-0">
            <div id="qr-reader" style="width: 900px;"></div>
            <button id="closeCameraBtn" class="btn btn-primary mt-2" type="button" style="display: none;">Close
                Camera</button>
        </div>
        <div class="col-12 col-xl-4">
            <div class="justify-content-end d-flex">
                <form action="#" method="post" class="form-horizontal">
                    <button id="addAttendanceBtn" class="btn btn-primary" type="button">SCAN QR CODE</button>
                    <input type="hidden" name="text" id="text" readonly placeholder="scan qr code"
                        class="form-control">
                </form>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            let html5QrCode;
            let canScan = true; // Flag to control scanning
            const scanCooldown = 2000; // Cooldown period in milliseconds (2 seconds in this example)
    
            // Initialize QR code scanning when the "Add Attendance" button is clicked
            $('#addAttendanceBtn').on('click', function() {
                html5QrCode = new Html5Qrcode("qr-reader");
    
                // Start QR code scanning
                html5QrCode.start(
                    { facingMode: "environment" },
                    { fps: 10, qrbox: { width: 250, height: 250 } },
                    qrCodeMessage => {
                        if (canScan) {
                            canScan = false; // Prevent further scans until cooldown
    
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
                                            // Optionally reload or perform other actions
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
    
                            // Reset canScan flag after cooldown period
                            setTimeout(() => {
                                canScan = true;
                            }, scanCooldown);
                        }
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

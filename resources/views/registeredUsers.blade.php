@extends('layouts.main')
<style>
    .attendance-item {
        margin-bottom: 10px; /* Reduced spacing between items */
    }

    .attendance-stage {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 8px; /* Reduced padding inside the box */
        border: 1px solid #ddd;
        border-radius: 8px;
    }

    .attendance-circle {
        width: 20px; /* Slightly smaller */
        height: 20px; /* Slightly smaller */
        border-radius: 50%;
        background-color: #ccc;
        margin-right: 8px; /* Reduced space between circle and message */
    }

    .attendance-circle.filled {
        background-color: #4CAF50; /* Green color for filled circles */
    }

    .attendance-message {
        margin: 0;
        padding: 0;
        flex: 1;
        text-align: center;
        font-size: 13px; /* Smaller text */
        font-weight: bold;
        text-transform: capitalize;
    }

    .attendance-time {
        margin: 0;
        padding: 0;
        font-size: 11px; /* Smaller text for timestamp */
        color: gray;
        text-align: center;
    }

    .attendance-icon {
        text-align: center;
    }

    .fa-check {
        color: green;
    }

    .fa-times.wrong {
        color: red;
    }
</style>

@section('content')
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title text-center">Registered Users</h4>
                <div class="table-responsive">
                    <table class="table table-hover text-center">
                        <thead>
                            <tr>
                                <th>Sl</th>
                                <th>KMC ID</th>
                                <th>Name</th>
                                <th>Mobile</th>
                                <th>Workplace</th>
                                <th>Registration Date</th>
                                <th>Registration Fee</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="registeredUsersTable">
                            <!-- Data will be populated here by JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Attendance Modal -->
    <div class="modal fade" id="attendanceModal" tabindex="-1" aria-labelledby="attendanceModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="attendanceModalLabel" style="font-weight: bolder;">Scan Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="ti ti-close"></i>
                    </button>
                </div>
                <div class="modal-body" id="attendanceModalBody">
                    <!-- Attendance data will be inserted here -->
                </div>
            </div>
        </div>
    </div>

    <!-- Ensure jQuery and Axios are included before this -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <script>
        $(document).ready(function() {
            // Make the Axios request to the API endpoint
            axios.get('/api/registers')
                .then(response => {
                    const registers = response.data.data;
                    const tableBody = document.getElementById('registeredUsersTable');
                    tableBody.innerHTML = ''; // Clear any previous content

                    // Loop through the response data and populate the table
                    registers.forEach((register, index) => {
                        const createdAtDate = new Date(register.created_at);
                        const formattedDate =
                            `${createdAtDate.getDate().toString().padStart(2, '0')}-${(createdAtDate.getMonth() + 1).toString().padStart(2, '0')}-${createdAtDate.getFullYear()}`;

                        const row = document.createElement('tr');
                        row.setAttribute('data-id', register.id);

                        row.innerHTML = `
                    <td>${index + 1}</td>
                    <td>${register.gov_id}</td>
                    <td>${register.fullname}</td>
                    <td>${register.mobile}</td>
                    <td>${register.working_place}</td>
                    <td>${formattedDate}</td> 
                    <td>400</td>
                    <td>
                        <a href="{{ url('/api/register') }}/${register.id}/edit" style="margin-right: 10px;">
                            <i class="ti-credit-card" style="font-size:20px;"></i>                                  
                        </a>
                        <a href="javascript:void(0);" class="show-attendance" data-id="${register.id}">
                            <i class="ti-info-alt" style="font-size:20px;"></i>
                        </a>
                    </td>
                `;
                        tableBody.appendChild(row);
                    });

                    // Attach event listener to all "info" icons
                    $('.show-attendance').on('click', function() {
                        const registerId = $(this).data('id');
                        const register = registers.find(r => r.id == registerId);

                        // Populate the modal with attendance data
                        const attendanceModalBody = $('#attendanceModalBody');
                        attendanceModalBody.empty();

                        // Attendance stage messages corresponding to count_attendance values
                        const messages = [
                            'Attendance Marked, Kit Given',
                            'Breakfast 1 Taken',
                            'Lunch 1 Taken',
                            'Dinner 1 Taken',
                            'Breakfast 2 Taken',
                            'Lunch 2 Taken'
                        ];

                        if (register.attendances.length > 0) {
                            console.log(register.attendances);

                            // Loop through each stage from 1 to 6
                            for (let i = 1; i <= 6; i++) {
                                // Find attendance record for the current stage (count_attendance value)
                                const attendance = register.attendances.find(a => a.count_attendance ===
                                    i);

                                // Set message and status based on the found attendance record
                                const message = messages[i - 1];
                                const scannedAtMessage = attendance ?
                                    `Scanned At: ${attendance.scanned_at}` : 'Not yet scanned';
                                const isFilled = attendance ? 'filled' :
                                ''; // Filled if attendance exists
                                const iconClass = attendance ? 'fa-check' :
                                'fa-times wrong'; // Check or X icon

                                // Append the attendance stage to the modal
                                attendanceModalBody.append(`
                            <div class="attendance-item">
                                <div class="attendance-stage">
                                    <div class="attendance-circle ${isFilled}"></div>
                                    <div class="attendance-message-container">
                                        <p class="attendance-message">${message}</p>
                                        <p class="attendance-time">${scannedAtMessage}</p>
                                    </div>
                                    <div class="attendance-icon">
                                        <i class="fa ${iconClass}"></i>
                                    </div>
                                </div>
                            </div>
                        `);
                            }
                        } else {
                            attendanceModalBody.append(
                                `<p>No attendance data available for this user.</p>`);
                        }

                        // Show the modal
                        $('#attendanceModal').modal('show');
                    });
                })
                .catch(error => {
                    console.error('Error fetching registered users:', error);
                });
        });
    </script>
@endsection

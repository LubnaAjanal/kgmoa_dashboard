@extends('layouts.main')

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
                        <tbody id="attendanceTable">
                            <!-- Data will be populated here by JavaScript -->
                        </tbody>
                    </table>
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
            axios.get('/api/attendance')
                .then(response => {
                    const attendances = response.data.data;
                    const tableBody = document.getElementById('attendanceTable');
                    tableBody.innerHTML = ''; // Clear any previous content

                    // Loop through the response data and populate the table
                    // Assuming 'attendances' is the response array you have
                    if (attendances.length > 0) {
                        attendances.forEach((attendance, index) => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${index + 1}</td>
                                <td>${attendance.user_unique_id}</td>
                                <!-- <td>${register.fullname}</td> -->
                                <!-- <td>${register.mobile}</td> -->
                                <!-- <td>${register.working_place}</td> -->
                                <!-- <td>${formattedDate}</td> --> 
                                <td>400</td>
                            `;
                            tableBody.appendChild(row);
                        });
                    } else {
                        // If no attendance data, create a row with a message
                        const row = document.createElement('tr');
                        row.innerHTML = `
                                    <td colspan="5" class="text-center">No Attendees</td>
                                    `;
                        tableBody.appendChild(row);
                    }

                })
                .catch(error => {
                    console.error('Error fetching registered users:', error);
                });
        });
    </script>
@endsection

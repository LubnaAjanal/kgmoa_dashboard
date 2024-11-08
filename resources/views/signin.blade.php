<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Karnataka Government Medical Officers’ Association</title>
    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/x-icon">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"
        integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">

    <link rel="stylesheet" href="{{ asset('css/vertical-layout-light/login.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

</head>

<body>
    <section class="login">
        <div class="login_box">
            <div class="left">
                <div class="contact">
                    <form id="login">
                        @csrf
                        <div class="brand-logo text-center">
                            <img src="../images/logo.png" style="border-radius: 50%;" alt="logo">
                        </div>
                        <input type="text" id="email" name="email" placeholder="EMAIL" required>
                        <input type="password" id="password" name="password" placeholder="PASSWORD" required>
                        <button type="submit" class="submit">Login</button>
                    </form>
                </div>
            </div>
            <div class="right">
                <div class="right-text">
                    <h2>LONYX</h2>
                    <h5>A UX BASED CREATIVE AGENCY</h5>
                </div>
                <div class="right-inductor"><img
                        src="https://lh3.googleusercontent.com/fife/ABSRlIoGiXn2r0SBm7bjFHea6iCUOyY0N2SrvhNUT-orJfyGNRSMO2vfqar3R-xs5Z4xbeqYwrEMq2FXKGXm-l_H6QAlwCBk9uceKBfG-FjacfftM0WM_aoUC_oxRSXXYspQE3tCMHGvMBlb2K1NAdU6qWv3VAQAPdCo8VwTgdnyWv08CmeZ8hX_6Ty8FzetXYKnfXb0CTEFQOVF4p3R58LksVUd73FU6564OsrJt918LPEwqIPAPQ4dMgiH73sgLXnDndUDCdLSDHMSirr4uUaqbiWQq-X1SNdkh-3jzjhW4keeNt1TgQHSrzW3maYO3ryueQzYoMEhts8MP8HH5gs2NkCar9cr_guunglU7Zqaede4cLFhsCZWBLVHY4cKHgk8SzfH_0Rn3St2AQen9MaiT38L5QXsaq6zFMuGiT8M2Md50eS0JdRTdlWLJApbgAUqI3zltUXce-MaCrDtp_UiI6x3IR4fEZiCo0XDyoAesFjXZg9cIuSsLTiKkSAGzzledJU3crgSHjAIycQN2PH2_dBIa3ibAJLphqq6zLh0qiQn_dHh83ru2y7MgxRU85ithgjdIk3PgplREbW9_PLv5j9juYc1WXFNW9ML80UlTaC9D2rP3i80zESJJY56faKsA5GVCIFiUtc3EewSM_C0bkJSMiobIWiXFz7pMcadgZlweUdjBcjvaepHBe8wou0ZtDM9TKom0hs_nx_AKy0dnXGNWI1qftTjAg=w1920-h979-ft"
                        alt=""></div>
            </div>
        </div>
    </section>
</body>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<script>
    async function sendAxiosRequest(method, apiEndpoint, data) {
        let config = {
            method: method,
            url: `${apiEndpoint}`,
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            data: data
        };

        try {
            const response = await axios.request(config);
            return response;
        } catch (error) {
            throw error;
        }
    }

    $(document).ready(function() {
        $('#login').on('submit', function(e) { // Corrected form ID
            e.preventDefault();

            let email = $('#email').val();
            let password = $('#password').val();

            let formData = {
                email,
                password
            };
            // console.log(formData);
            if (!email || !password) {
                alert('Please enter both email and password.');
                return;
            }

            // Get CSRF token from meta tag
            // let csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            sendAxiosRequest('post', '/api/login', formData)
                .then(response => {
                    // alert('Login successful!');
                    window.location.href = "{{ url('/api/dashboard') }}";
                })
                .catch(error => {
                    console.log(error);
                    let errorMsgs = error.response?.data?.message;
                    alert(errorMsgs || 'Login failed. Please check your credentials.');
                });
        });
    });
</script>

</html>

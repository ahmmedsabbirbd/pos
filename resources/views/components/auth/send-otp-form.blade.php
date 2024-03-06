<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6 center-screen">
            <div class="card animated fadeIn w-90  p-4">
                <div class="card-body">
                    <h4>EMAIL ADDRESS</h4>
                    <br/>
                    <label>Your email address</label>
                    <input id="email" placeholder="User Email" class="form-control" type="email"/>
                    <br/>
                    <button onclick="VerifyEmail()"  class="btn w-100 float-end btn-primary">Next</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const VerifyEmail =async ()=> {
        let email=document.getElementById('email').value;

        
        let emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if(email === "" || !emailRegex.test(email) || email.length > 50) {
            errorToast("Please enter a valid email address (maximum 50 characters).");
        } else {
            showLoader()
            let res = await axios.post('/user-send-otp-to-email', {
                'email': email,
            });
            hideLoader()
            if(!res.data.status) {
                Object.keys(res.data.errors).forEach(function(field) {
                    let errorMessages = res.data.errors[field];
                    errorMessages.forEach(function(errorMessage) {
                        errorToast(`${errorMessage} `);
                    });
                });
            }

            if(res.data.status == 'failed') {
                errorToast(res.data.message); 
            }
            
            if(res.data.status == 'success') { 
                successToast(res.data.message)
                sessionStorage.setItem('email', email)
                window.location.href = "/verifyOtp";
            }
        }
    } 
</script>

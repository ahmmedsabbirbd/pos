<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6 center-screen">
            <div class="card animated fadeIn w-90  p-4">
                <div class="card-body">
                    <h4>ENTER OTP CODE</h4>
                    <br/>
                    <label>4 Digit Code Here</label>
                    <input id="otp" placeholder="Code" class="form-control" type="text"/>
                    <br/>
                    <button onclick="VerifyOtp()"  class="btn w-100 float-end btn-primary">Next</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const VerifyOtp =async ()=> {
        let email=sessionStorage.getItem('email');
        let otp=document.getElementById('otp').value;

        
        let emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (email === "" || !emailRegex.test(email) || email.length > 50) {
            errorToast("Please enter a valid email address (maximum 50 characters).");
        } else if (otp === "" || otp.length > 5) {
            errorToast("Please enter a valid OTP (maximum 5 characters).");
        } else {
            showLoader()
            let res = await axios.post('/otp-verify', {
                'email': email,
                'otp': otp,
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
                window.location.href = "/resetPassword";
            }
        }
    } 
</script>

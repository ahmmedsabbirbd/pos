<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-7 animated fadeIn col-lg-6 center-screen">
            <div class="card w-90  p-4">
                <div class="card-body">
                    <h4>SIGN IN</h4>
                    <br/>
                    <input id="email" placeholder="User Email" class="form-control" type="email"/>
                    <br/>
                    <input id="password" placeholder="User Password" class="form-control" type="password"/>
                    <br/>
                    <button onclick="SubmitLogin()" class="btn w-100 btn-primary">Next</button>
                    <hr/>
                    <div class="float-end mt-3">
                        <span>
                            <a class="text-center ms-3 h6" href="{{route('google.login')}}">Google+</a>
                            <span class="ms-1">|</span>
                            <a class="text-center ms-3 h6" href="{{route('facebook.login')}}">Facebook+</a>
                            <span class="ms-1">|</span>
                            <a class="text-center ms-3 h6" href="{{url('/userRegistration')}}">Sign Up </a>
                            <span class="ms-1">|</span>
                            <a class="text-center ms-3 h6" href="{{url('/sendOtp')}}">Forget Password</a>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    const SubmitLogin =async ()=> {
        let email=document.getElementById('email').value;
        let pass=document.getElementById('password').value;

        let emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (email === "" || !emailRegex.test(email)) {
            errorToast("Please enter a valid email address."); 
        }else if (pass === "" || pass.length > 50) {
            errorToast("Please enter a password (maximum 50 characters).");
        } else {
            showLoader()
            let res = await axios.post('/user-login', {
                'email': email,
                'password': pass,
            });
            hideLoader()

            if(!res.data.status) {
                Object.keys(res.data.errors).forEach(function(field) {
                    let errorMessages = res.data.errors[field];
                    errorMessages.forEach(function(errorMessage) {
                        errorToast(`${field}: ${errorMessage} `);
                    });
                });
            }

            if(res.data.message == 'unauthorzies') {
                errorToast(`Password Did not matched`); 
            }
            
            if(res.data.status == 'success') { 
                successToast(res.data.message)
                window.location.href = "/dashboard";
            }
        }
    } 

</script>

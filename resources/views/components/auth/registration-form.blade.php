<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-10 center-screen">
            <div class="card animated fadeIn w-100 p-3">
                <div class="card-body">
                    <h4>Sign Up</h4>
                    <hr/>
                    <div class="container-fluid m-0 p-0">
                        <div class="row m-0 p-0">
                            <div class="col-md-4 p-2">
                                <label>Email Address</label>
                                <input id="email" placeholder="User Email" class="form-control" type="email"/>
                            </div>
                            <div class="col-md-4 p-2">
                                <label>First Name</label>
                                <input id="fristName" placeholder="First Name" class="form-control" type="text"/>
                            </div>
                            <div class="col-md-4 p-2">
                                <label>Last Name</label>
                                <input id="lastName" placeholder="Last Name" class="form-control" type="text"/>
                            </div>
                            <div class="col-md-4 p-2">
                                <label>Mobile Number</label>
                                <input id="mobile" placeholder="Mobile" class="form-control" type="mobile"/>
                            </div>
                            <div class="col-md-4 p-2">
                                <label>Password</label>
                                <input id="password" placeholder="User Password" class="form-control" type="password"/>
                            </div>
                        </div>
                        <div class="row m-0 p-0">
                            <div class="col-md-4 p-2">
                                <button onclick="onRegistration()" class="btn mt-3 w-100  btn-primary">Complete</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script> 
    const onRegistration =async ()=> {
        let email=document.getElementById('email').value;
        let password=document.getElementById('password').value;
        let fristName=document.getElementById('fristName').value;
        let lastName=document.getElementById('lastName').value;
        let mobile=document.getElementById('mobile').value;

        
        let emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (fristName === "" || fristName.length > 20) {
            errorToast("Please enter a valid first name (maximum 20 characters).");
        }else if (lastName === "" || lastName.length > 20) {
            errorToast("Please enter a valid last name (maximum 20 characters).");
        }else if(email === "" || !emailRegex.test(email) || email.length > 50) {
            errorToast("Please enter a valid email address (maximum 50 characters).");
        }else if(mobile === "" || mobile.length > 20) {
            errorToast("Please enter a valid mobile number (maximum 20 characters).");
        }else if(password === "" || password.length > 50) {
            errorToast("Please enter a password (maximum 50 characters).");
        } else {
            showLoader()
            let res = await axios.post('/user-registration', {
                'email': email,
                'password': password,
                'fristName': fristName,
                'lastName': lastName,
                'mobile': mobile,
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
                window.location.href = "/dashboard";
            }
        }
    } 
</script>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6 center-screen">
            <div class="card animated fadeIn w-90 p-4">
                <div class="card-body">
                    <h4>SET NEW PASSWORD</h4>
                    <br/>
                    <label>New Password</label>
                    <input placeholder="New Password" class="form-control" type="password"/>
                    <br/>
                    <label>Confirm Password</label>
                    <input id="password" placeholder="Confirm Password" class="form-control" type="password"/>
                    <br/>
                    <button onclick="ResetPass()" class="btn w-100  btn-primary">Next</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const ResetPass =async ()=> {
        let password=document.getElementById('password').value;
        
        if (password === "" || password.length > 50) {
            errorToast("Please enter a password (maximum 50 characters).");
        } else {
            showLoader()
            let res = await axios.post('/set-password', {
                'password': password,
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
                window.location.href = "/userLogin";
            }
        }
    } 
</script>

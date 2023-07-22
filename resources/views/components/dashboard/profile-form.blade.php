<div class="container" style="max-width: 100% !important;">
    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-10 center-screen">
            <div class="card animated fadeIn w-100 p-3">
                <div class="card-body">
                    <h4>Profile Update</h4>
                    <hr/>
                    <div class="container-fluid m-0 p-0">
                        <div class="row m-0 p-0">
                            <div class="col-md-4 p-2">
                                <label>Email Address</label>
                                <input id="email" placeholder="User Email" class="form-control" type="email" readonly/>
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
                            <!-- Avatar Upload Field -->
                            <div class="col-md-4 p-2">
                                <label>Avatar</label>
                                <input id="avatar" class="form-control" type="file" accept="image/*"/>
                                <!-- Avatar Preview -->
                                <img id="avatar-preview" src="#" alt="Avatar Preview" class="img-fluid" style="max-height: 150px;">
                            </div>
                        </div>
                        <div class="row m-0 p-0">
                            <div class="col-md-4 p-2" style="margin-left: auto;">
                                <button onclick="ProfileUpdate()" class="btn mt-3 w-100  btn-primary">Update</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const ProfileDetails =async ()=> {
        try {
            showLoader()
            let res = await axios.get('/profile-details')
            hideLoader()

            document.getElementById('email').value =res.data.data['email'];
            document.getElementById('password').value =res.data.data['password'];
            document.getElementById('fristName').value =res.data.data['fristName'];
            document.getElementById('lastName').value =res.data.data['lastName'];
            document.getElementById('mobile').value =res.data.data['mobile'];
            document.getElementById('avatar-preview').src =`avatars/${res.data.data['avatar']}`;
        } catch (e) {
            hideLoader()
            errorToast('Somethink Went Worng')
        }
    }
    ProfileDetails()

    const ProfileUpdate =async ()=> {
        let password=document.getElementById('password').value;
        let fristName=document.getElementById('fristName').value;
        let lastName=document.getElementById('lastName').value;
        let mobile=document.getElementById('mobile').value;
        let avatarInput=document.getElementById('avatar');
        let haveAvatar=document.getElementById('avatar-preview').src;
        let haveAvatarUrl = haveAvatar.substring(haveAvatar.lastIndexOf('/') + 1);

        const formData = new FormData();
        formData.append('fristName', fristName);
        formData.append('lastName', lastName);
        formData.append('mobile', mobile);
        formData.append('password', password);
        formData.append('avatar', avatarInput.files[0]);
        formData.append('haveAvatar', haveAvatarUrl);


        if (fristName === "" || fristName.length > 20) {
            errorToast("Please enter a valid first name (maximum 20 characters).");
        }else if (lastName === "" || lastName.length > 20) {
            errorToast("Please enter a valid last name (maximum 20 characters).");
        }else if(mobile === "" || mobile.length > 20) {
            errorToast("Please enter a valid mobile number (maximum 20 characters).");
        }else if(password === "" || password.length > 50) {
            errorToast("Please enter a password (maximum 50 characters).");
        } else {
            showLoader()
            let res = await axios.post('/profile-update', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data',
                },
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
                ProfileDetails()
                DashBoardImage()
            }
        }
    }
</script>

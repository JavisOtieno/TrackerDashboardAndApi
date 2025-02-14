@include('components/header')
@include('components/header-extension-loginsignup')
            <div class="">
                <!-- Theme-Layout -->

                <!-- CONTAINER OPEN -->
                <div class="col col-login mx-auto mt-7">
                    <div class="text-center">
                        <a href="index.html"><img src="../assets/images/brand/logo-white.png" class="header-brand-img m-0" alt=""></a>
                    </div>
                </div>
                <div class="container-login100">
                    <div class="wrap-login100 p-6" >
                        <form class="login100-form validate-form" action="/attemptsignup" method="POST">
                            @csrf
                            <span class="login100-form-title">
									Registration
								</span>
                                @if ($errors->any())
    <div class="alert alert-danger alert-dismissible">
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        <ul>
            @foreach ($errors->all() as $error)
                <li>
                    @if ($error == 'Any special error')
                    Any special error
                    @else
                    {{ $error }}
                    @endif
                    </li>
            @endforeach
        </ul>
    </div>
    @endif
                    
                            <div class="wrap-input100 validate-input input-group" data-bs-validate="Valid email is required: ex@abc.xyz">
                                <a href="javascript:void(0)" class="input-group-text bg-white text-muted">
                                    <i class="mdi mdi-account" aria-hidden="true"></i>
                                </a>
                                <input class="input100 border-start-0 ms-0 form-control" value="{{old('name')}}" name="name" type="text" placeholder="Admin Name" autocomplete="name">
                            </div>

                            <div class="wrap-input100 validate-input input-group" data-bs-validate="Valid phone number is required">
                                <a href="javascript:void(0)" class="input-group-text bg-white text-muted">
                                    <i class="zmdi zmdi-phone" aria-hidden="true"></i>
                                </a>
                                <input class="input100 border-start-0 ms-0 form-control" value="{{old('phone')}}" name="phone" type="text" placeholder="Admin Phone" autocomplete="phone">
                            </div>
                             
                            <div class="wrap-input100 validate-input input-group" data-bs-validate="Valid email is required: ex@abc.xyz">
                                <a href="javascript:void(0)" class="input-group-text bg-white text-muted">
                                    <i class="zmdi zmdi-email" aria-hidden="true"></i>
                                </a>
                                <input class="input100 border-start-0 ms-0 form-control" value="{{old('email')}}" name="email" type="email" placeholder="Admin Email" autocomplete="email">
                            </div>
                            <div class="wrap-input100 validate-input input-group" id="Password-toggle">
                                <a href="javascript:void(0)" class="input-group-text bg-white text-muted">
                                    <i class="zmdi zmdi-eye" aria-hidden="true"></i>
                                </a>
                                <input class="input100 border-start-0 ms-0 form-control" name="password" type="password" placeholder="Password">
                            </div>
                            <div class="wrap-input100 validate-input input-group" id="Password-toggle">
                                <a href="javascript:void(0)" class="input-group-text bg-white text-muted">
                                    <i class="zmdi zmdi-eye" aria-hidden="true"></i>
                                </a>
                                <input class="input100 border-start-0 ms-0 form-control" name="password_confirmation" type="password" placeholder="Confirm Password">
                            </div>
                            <label class="custom-control custom-checkbox mt-4">
									<!--<input type="checkbox" class="custom-control-input">
									<span class="custom-control-label">Agree the <a href="terms.html">terms and policy</a></span>-->
								</label>
                            <div class="container-login100-form-btn">
                                <button class="login100-form-btn btn-primary">
										Register
                                </button>
                            </div>
                            <div class="text-center pt-3">
                                <p class="text-dark mb-0 d-inline-flex">Already have account ?<a href="/login" class="text-primary ms-1">Sign In</a></p>
                            </div>
                            <!--<label class="login-social-icon"><span>Register with Social</span></label>
                            <div class="d-flex justify-content-center">
                                <a href="javascript:void(0)">
                                    <div class="social-login me-4 text-center">
                                        <i class="fa fa-google"></i>
                                    </div>
                                </a>
                                <a href="javascript:void(0)">
                                    <div class="social-login me-4 text-center">
                                        <i class="fa fa-facebook"></i>
                                    </div>
                                </a>
                                <a href="javascript:void(0)">
                                    <div class="social-login text-center">
                                        <i class="fa fa-twitter"></i>
                                    </div>
                                </a>
                            </div>-->
                        </form>
                    </div>
                </div>
                <!-- CONTAINER CLOSED -->
            </div>
            @include('components/footer-loginsignup');
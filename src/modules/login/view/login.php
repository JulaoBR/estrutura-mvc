<div class="container">
    <div class="overflow-hidden mt-5 z-1 card">
        <div class="p-0 card-body">
            <div class="h-100 g-0 row">
                <div class="text-white text-center bg-primary col-md-5">
                    <div class="position-relative p-4 pt-md-5 pb-md-7">
                        <div class="z-1 position-relative" data-bs-theme="light">
                            <p class="opacity-75 text-white">Estrutura em MVC</p>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-center align-items-center col-md-7">
                    <div class="p-4 p-md-5 flex-grow-1">
                        <h3>Login</h3>
                        <form class="" method="POST" action="<?= $settings['url_logar']; ?>">
                            <div class="mb-3">
                                <label class="form-label">Login</label>
                                <input placeholder="" name="login" type="text" class="form-control" value="">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input placeholder="" name="senha" type="password" class="form-control" value="">
                            </div>

                            <div class="justify-content-between align-items-center row">
                                <div class="col-auto">
                                    <a class="fs-10 mb-0" href="/authentication/card/forgot-password">Forgot Password?</a>
                                </div>
                            </div>
                            <div>
                                <button type="submit" color="primary" class="mt-3 w-100 btn btn-primary">
                                    Log in
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
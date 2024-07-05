<header>
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">App</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <div class="mx-auto">
                    </div>
                </div>

                <div class="mx-auto">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-target="active" id="toggle-dark"><i class="fa-solid fa-toggle-on fa-lg" style="color: #74C0FC;" aria-hidden="true"></i></a>
                            <a class="nav-link active" style="display:none" data-bs-target="none" id="toggle-default"><i class="fa-solid fa-toggle-off fa-lg" style="color: #74C0FC;" aria-hidden="true"></i></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link">{@ $user['email']}</a>
                        </li>
                        <li class="nav-item">
                            <a href="./logout" class="nav-link active"><i class="fa-solid fa-power-off fa-lg" style="color: #74C0FC;" aria-hidden="true"></i></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
</header>
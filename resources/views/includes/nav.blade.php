<nav class="navbar navbar-expand-sm navbar-light bg-light pb-0">
    <div class="row w-100 mx-animated-margin">
        <div class="col-12 col-md-6 py-3">
            <a class="navbar-brand" href="/">LOGO PLACEHOLDER</a>
        </div>
        <div class="col-12 col-md-6 py-3">
            <div class="row">
                <div class="col align-middle">
                    <a class="text-decoration-none fw-bold" href="#">Kontakty a čisla na oddelenia</a>
                    <a id="lang_select" class="text-secondary text-decoration-none ms-2" href="#">EN</a>
                </div>
                <form id="navbar_search_form" class="d-inline-block col">
                    @csrf
                    <div class="input-group">
                        <label for="navbar_search" class="d-none">Vyhľadať</label>
                        <input type="text" id="navbar_search" name="navbar_search" class="form-control rounded-start border-end-0" aria-label="Dollar amount (with dot and two decimal places)">
                        <button type="submit" class="input-group-text bg-light border-start-0 text-secondary">&#x1F50E;&#xFE0E;</button>
                    </div>
                </form>
                <div class="col-12 col-sm-3 d-none d-sm-block">
                    <a href="#" class="btn btn-custom-success">Prihlásenie</a>
                </div>
            </div>
        </div>
        <div class="col-12 container-fluid">
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbar" aria-controls="navbar" aria-expanded="false">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbar">
                <ul class="navbar-nav fw-bold">
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="#">O nás</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Zoznam miest</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Inšpekcia</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Kontakt</a>
                    </li>
                    <li class="nav-item d-block d-sm-none">
                        <a class="nav-link" href="#">Prihlásenie</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>

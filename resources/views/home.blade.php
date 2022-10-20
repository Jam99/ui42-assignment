@include("includes.header")
@include("includes.nav")
<div class="bg-custom-gradient">
    <div class="mx-animated-margin py-150 text-light text-center">
        <h1 class="display-3 fw-light">Vyhľadať v databáze obcí</h1>
        <form id="search_form" class="mt-5 mw-600 mx-auto" autocomplete="off">
            @csrf
            <label class="d-none" for="search">Zadajte názov</label>
            <div id="autocomplete_container">
                <input type="text" id="search" name="search" class="form-control form-control-lg dynamic-border-radius-helper rounded-0" aria-label="Zadajte názov" aria-describedby="inputGroup-sizing-lg" placeholder="Zadajte názov">
            </div>
        </form>
    </div>
</div>
@include("includes.footer")

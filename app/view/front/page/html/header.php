<nav class="navbar navbar-expand-sm navbar-dark bg-dark" style="z-index:10;">
    <div class="container-fluid">
        <button class="btn text-white" onclick="$('#sidebar').toggleClass('d-none')"><i class="fa-solid fa-bars"></i> </button>
        <div class="dropdown">
            <button class="btn dropdown-toggle text-white" type="button" id="triggerId" data-bs-toggle="dropdown">
                <i class="fa-regular fa-user"></i>
            </button>
            <div class="dropdown-menu" aria-labelledby="triggerId" style="left: -100px">
                <a class="dropdown-item" href="<?= base_url('profile/') ?>">Profile</a>
                <a class="dropdown-item" href="<?= base_url('logout/') ?>">Logout</a>
            </div>
        </div>

    </div>
</nav>
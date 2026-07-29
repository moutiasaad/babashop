<div class="fixed-top sub_header px_16 py_8 border-bottom bg_light">
    <div class="row h-100 align-items-center gx-2">
        <div class="col-2 col-sm-2 col-md-3 col-xl-4">
            <div class="d-flex">
                <div class="pe-3 d-xl-none">
                    <button class="btn header_tglBtn" type="button">
                        <i class="fi fi-rr-menu-burger"></i>
                    </button>
                </div>
                <div class="d-none d-xl-block">
<div class="fs_20 c_dark3">مرحبا بك {{ auth()->guard('admin')->user()->name." ".auth()->guard('admin')->user()->title}} </div>
                    <div class="fs_14 c_primaryLt fw-normal">
                        <span id="current-time"></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-3 col-sm-3 col-md-3 col-xl-4">
        </div>
        <div class="col-7 col-sm-7 col-md-6 col-xl-4 ">
            <div class="text-end">

                <!-- notifications -->

                <!-- user options -->
                <div class="dropdown userOpts_dropdown d-inline-block mr_8">
                    <button class="btn btn_sm" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <img class="userOpts_pflImg" src="{{ auth()->guard('admin')->user()->image ? asset(auth()->guard('admin')->user()->image) : '/admin/img/user.svg' }}" alt="profile"><span class=" d-lg-inline ps-1">{{auth()->guard('admin')->user()->name}} ({{auth()->guard('admin')->user()->title}}) <i class="fi fi-rr-angle-small-down lh-1"></i></span> 
                    </button>
                    <div class="dropdown-menu dropdown-menu-end rounded_16 border p_6 fs_16">
                        <!--<li><a class="dropdown-item rounded_12" href="/dashboard/profileSet"><i class="fi fi-rr-user me-2"></i> إعدادات البروفيل</a></li>-->

                        <li><a class="dropdown-item rounded_12" href="/dashboard/profileSet"><i class="fi fi-rr-user me-2"></i> إعدادات الحساب</a></li>
                        <li><a class="dropdown-item rounded_12" href="/logout"><i class="fi fi-rr-arrow-circle-right me-2"></i> تسجيل الخروج</a></li>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="d-flex justify-content-between d-none">
        <div>
            <a class="header-brand py-0 me-3" href="index.html">
                <img class="header_logo" src="/admin/img/payline.png" alt="logo">
            </a>
        </div>
    </div>
</div>
<script>
    function updateTime() {
        const now = new Date();

        // Format the date and time
        const year = now.getFullYear();
        const month = String(now.getMonth() + 1).padStart(2, '0'); // Months are 0-based
        const day = String(now.getDate()).padStart(2, '0');
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const seconds = String(now.getSeconds()).padStart(2, '0');

        // Construct the formatted date string
        const formattedDate = `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;

        // Update the HTML
        document.getElementById('current-time').textContent = formattedDate;
    }

    // Initial time set
    updateTime();

    // Update every second (1000 ms)
    setInterval(updateTime, 1000);
</script>

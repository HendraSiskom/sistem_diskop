<div class="left-side-menu">

    <div class="h-100" data-simplebar>
        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <ul id="side-menu">
                <li class="menu-title">Menu</li>
                <li>
                    <a href="{{ route('home') }}">
                        <i class="mdi mdi-view-dashboard-outline"></i>
                        <span> Beranda </span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('pengguna.index') }}">
                        <i class="mdi mdi-view-dashboard-outline"></i>
                        <span> pengguna </span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('peran.index') }}">
                        <i class="mdi mdi-view-dashboard-outline"></i>
                        <span> peran </span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('kd_wilayah.index') }}">
                        <i class="mdi mdi-view-dashboard-outline"></i>
                        <span> Kode wilayah </span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('wilayah.index') }}">
                        <i class="mdi mdi-view-dashboard-outline"></i>
                        <span> wilayah </span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('reporting_output.index') }}">
                        <i class="mdi mdi-view-dashboard-outline"></i>
                        <span> Reporting Output </span>
                    </a>
                </li>

                <li class="menu-title">Menu Master </li>
                <li>
                    <a href="{{ route('listbarang.index') }}">
                        <i class="mdi mdi-view-dashboard-outline"></i>
                        <span> List Barang </span>
                    </a>
                </li>
                <li class="menu-title">Transaksi </li>
                <li>
                    <a href="{{ route('transaksi.index') }}">
                        <i class="mdi mdi-view-dashboard-outline"></i>
                        <span> Transaksi Barang </span>
                    </a>
                </li>
            </ul>
        </div>
        <!-- End Sidebar -->
        <div class="clearfix"></div>
    </div>
    <!-- Sidebar -left -->
</div>

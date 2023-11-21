<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="{{ route('home') }}" target="_blank" class="app-brand-link">
            <span class="app-brand-logo demo">
                <img style="width:35px !important" src="{{ asset('theme_2/logo.png') }}" />
            </span>
        </a>
        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        <!-- Dashboard -->
        {{-- <li class="menu-item {{ IsActiveOnlyIf(['admin.dashboard']) }}">
            <a href="{{ route('admin.dashboard') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-circle"></i>
                <div data-i18n="Analytics">الرئيسية</div>
            </a>
        </li> --}}

       <!-- employees -->
        <li class="menu-item {{ IsActiveOnlyIf(['admin.employees.index','admin.employees.create','admin.products.edit']) }}">
            <a href="{{ route('admin.employees.index') }}" class="menu-link menu-toggle">
                <i class='menu-icon tf-icons bx bxs-package'></i>
                <div data-i18n="Layouts">الموظفيين</div>
            </a>

            <ul class="menu-sub">
                <li class="menu-item {{ IsActiveOnlyIf(['admin.employees.index','admin.employees.edit','admin.employees.create']) }}">
                    <a href="{{ route('admin.employees.index') }}" class="menu-link">
                        <div data-i18n="Without navbar"> عرض</div>
                    </a>
                </li>
            </ul>
        </li>

        <!-- expenses  -->
        <li class="menu-item {{ IsActiveOnlyIf(['admin.departments-expenses.index']) }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class='menu-icon tf-icons bx bxs-package'></i>
                <div data-i18n="Layouts">أقسام المصروفات</div>
            </a>

            <ul class="menu-sub">
                <li class="menu-item {{ IsActiveOnlyIf(['admin.departments-expenses.index']) }}">
                    <a href="{{ route('admin.departments-expenses.index') }}" class="menu-link">
                        <div data-i18n="Without navbar">عرض</div>
                    </a>
                </li>
            </ul>
        </li>

        <!-- expenses  -->
        <li class="menu-item {{ IsActiveOnlyIf(['admin.expenses.index','admin.expenses.create','admin.expenses.edit','admin.expenses.show','admin.expenses.payments']) }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class='menu-icon tf-icons bx bxs-package'></i>
                <div data-i18n="Layouts">المصروفات</div>
            </a>

            <ul class="menu-sub">
                <li class="menu-item {{ IsActiveOnlyIf(['admin.expenses.index','admin.expenses.create','admin.expenses.edit','admin.expenses.show','admin.expenses.payments']) }}">
                    <a href="{{ route('admin.expenses.index') }}" class="menu-link">
                        <div data-i18n="Without navbar">عرض</div>
                    </a>
                </li>
            </ul>
        </li>

        <!-- expenses  -->
        <li class="menu-item {{ IsActiveOnlyIf(['admin.money-resources.index']) }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class='menu-icon tf-icons bx bxs-package'></i>
                <div data-i18n="Layouts">الموارد</div>
            </a>

            <ul class="menu-sub">
                <li class="menu-item {{ IsActiveOnlyIf(['admin.money-resources.index']) }}">
                    <a href="{{ route('admin.money-resources.index') }}" class="menu-link">
                        <div data-i18n="Without navbar">عرض</div>
                    </a>
                </li>
            </ul>
        </li>

             <!-- sales  -->
        <li class="menu-item {{ IsActiveOnlyIf(['admin.sales.index']) }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class='menu-icon tf-icons bx bxs-package'></i>
                <div data-i18n="Layouts">المبيعات</div>
            </a>

            <ul class="menu-sub">
                <li class="menu-item {{ IsActiveOnlyIf(['admin.sales.index']) }}">
                    <a href="{{ route('admin.sales.index') }}" class="menu-link">
                        <div data-i18n="Without navbar">عرض</div>
                    </a>
                </li>
            </ul>
        </li>

        <!-- employeeSales  -->
        <li class="menu-item {{ IsActiveOnlyIf(['admin.employeeSales.index']) }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class='menu-icon tf-icons bx bxs-package'></i>
                <div data-i18n="Layouts">مبيعات الموظفين</div>
            </a>

            <ul class="menu-sub">
                <li class="menu-item {{ IsActiveOnlyIf(['admin.employeeSales.index']) }}">
                    <a href="{{ route('admin.employeeSales.index') }}" class="menu-link">
                        <div data-i18n="Without navbar">عرض</div>
                    </a>
                </li>
            </ul>
        </li>

         <!-- clients  -->
        <li class="menu-item {{ IsActiveOnlyIf(['admin.clients.index']) }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class='menu-icon tf-icons bx bxs-package'></i>
                <div data-i18n="Layouts">العملاء</div>
            </a>

            <ul class="menu-sub">
                <li class="menu-item {{ IsActiveOnlyIf(['admin.clients.index']) }}">
                    <a href="{{ route('admin.clients.index') }}" class="menu-link">
                        <div data-i18n="Without navbar">عرض</div>
                    </a>
                </li>
            </ul>
        </li>

              <!-- clientSales  -->
        <li class="menu-item {{ IsActiveOnlyIf(['admin.clientSales.index','admin.clientSales.show','admin.client-payments.get']) }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class='menu-icon tf-icons bx bxs-package'></i>
                <div data-i18n="Layouts">مبيعات العملاء</div>
            </a>

            <ul class="menu-sub">
                <li class="menu-item {{ IsActiveOnlyIf(['admin.clientSales.index','admin.clientSales.show','admin.client-payments.get']) }}">
                    <a href="{{ route('admin.clientSales.index') }}" class="menu-link">
                        <div data-i18n="Without navbar">عرض</div>
                    </a>
                </li>
            </ul>
        </li>



        <!-- employeeAdvances  -->
        <li class="menu-item {{ IsActiveOnlyIf(['admin.employeeAdvances.index']) }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class='menu-icon tf-icons bx bxs-package'></i>
                <div data-i18n="Layouts">سلف الموظفين</div>
            </a>

            <ul class="menu-sub">
                <li class="menu-item {{ IsActiveOnlyIf(['admin.employeeAdvances.index']) }}">
                    <a href="{{ route('admin.employeeAdvances.index') }}" class="menu-link">
                        <div data-i18n="Without navbar">عرض</div>
                    </a>
                </li>
            </ul>
        </li>

       <!-- shifts  -->
        <li class="menu-item {{ IsActiveOnlyIf(['admin.shifts.index']) }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class='menu-icon tf-icons bx bxs-package'></i>
                <div data-i18n="Layouts">الشفتات</div>
            </a>

            <ul class="menu-sub">
                <li class="menu-item {{ IsActiveOnlyIf(['admin.shifts.index']) }}">
                    <a href="{{ route('admin.shifts.index') }}" class="menu-link">
                        <div data-i18n="Without navbar">عرض</div>
                    </a>
                </li>
            </ul>
        </li>



        <!-- employeeAttendances  -->
        <li class="menu-item {{ IsActiveOnlyIf(['admin.employeeAttendances.index']) }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class='menu-icon tf-icons bx bxs-package'></i>
                <div data-i18n="Layouts"> حضور وانصراف</div>
            </a>

            <ul class="menu-sub">
                <li class="menu-item {{ IsActiveOnlyIf(['admin.employeeAttendances.index']) }}">
                    <a href="{{ route('admin.employeeAttendances.index') }}" class="menu-link">
                        <div data-i18n="Without navbar">عرض</div>
                    </a>
                </li>
            </ul>
        </li>



              <!-- employeeSalaries  -->
        <li class="menu-item {{ IsActiveOnlyIf(['admin.employeeSalaries.index','admin.employeeSalaries.show']) }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class='menu-icon tf-icons bx bxs-package'></i>
                <div data-i18n="Layouts">مرتبات الموظفين</div>
            </a>

            <ul class="menu-sub">
                <li class="menu-item {{ IsActiveOnlyIf(['admin.employeeSalaries.index','admin.employeeSalaries.show']) }}">
                    <a href="{{ route('admin.employeeSalaries.index') }}" class="menu-link">
                        <div data-i18n="Without navbar">عرض</div>
                    </a>
                </li>
            </ul>
        </li>






    </ul>
</aside>

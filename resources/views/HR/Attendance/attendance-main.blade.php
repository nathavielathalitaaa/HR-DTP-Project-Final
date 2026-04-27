@extends('layouts.master')
@section('content')
    <!-- Page-content -->
    <div class="group-data-[sidebar-size=lg]:ltr:md:ml-vertical-menu group-data-[sidebar-size=lg]:rtl:md:mr-vertical-menu group-data-[sidebar-size=md]:ltr:ml-vertical-menu-md group-data-[sidebar-size=md]:rtl:mr-vertical-menu-md group-data-[sidebar-size=sm]:ltr:ml-vertical-menu-sm group-data-[sidebar-size=sm]:rtl:mr-vertical-menu-sm pt-[calc(theme('spacing.header')_*_1)] pb-[calc(theme('spacing.header')_*_0.8)] px-4 group-data-[navbar=bordered]:pt-[calc(theme('spacing.header')_*_1.3)] group-data-[navbar=hidden]:pt-0 group-data-[layout=horizontal]:mx-auto group-data-[layout=horizontal]:max-w-screen-2xl group-data-[layout=horizontal]:px-0 group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:ltr:md:ml-auto group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:rtl:md:mr-auto group-data-[layout=horizontal]:md:pt-[calc(theme('spacing.header')_*_1.6)] group-data-[layout=horizontal]:px-3 group-data-[layout=horizontal]:group-data-[navbar=hidden]:pt-[calc(theme('spacing.header')_*_0.9)]">
        <div class="container-fluid group-data-[content=boxed]:max-w-boxed mx-auto">

            <div class="flex flex-col gap-2 py-4 md:flex-row md:items-center print:hidden">
                <div class="grow">
                    <h5 class="text-16">Main Attendance</h5>
                </div>
                <ul class="flex items-center gap-2 text-sm font-normal shrink-0">
                    <li class="relative before:content-['\ea54'] before:font-remix ltr:before:-right-1 rtl:before:-left-1  before:absolute before:text-[18px] before:-top-[3px] ltr:pr-4 rtl:pl-4 before:text-slate-400 dark:text-zink-200">
                        <a href="#!" class="text-slate-400 dark:text-zink-200">HR Management</a>
                    </li>
                    <li class="text-slate-700 dark:text-zink-100">
                        Main Attendance
                    </li>
                </ul>
            </div>
            <div class="grid grid-cols-1 gap-x-5 md:grid-cols-2 xl:grid-cols-12">
                <div class="xl:col-span-3">
                    <div class="card">
                        <div class="flex items-center gap-4 card-body">
                            <div class="flex items-center justify-center rounded-md size-12 text-sky-500 bg-sky-100 text-15 dark:bg-sky-500/20 shrink-0"><i data-lucide="users-2"></i></div>
                            <div class="overflow-hidden grow">
                                <h5 class="mb-1 text-16"><span class="counter-value" data-target="{{ $totalEmployee }}">0</span></h5>
                                <p class="truncate text-slate-500 dark:text-zink-200">Total Employee</p>
                            </div>
                        </div>
                    </div>
                </div><!--end col-->
                <div class="xl:col-span-3">
                    <div class="card">
                        <div class="flex items-center gap-4 card-body">
                            <div class="flex items-center justify-center text-red-500 bg-red-100 rounded-md size-12 text-15 dark:bg-red-500/20 shrink-0"><i data-lucide="user-x-2"></i></div>
                            <div class="overflow-hidden grow">
                                <h5 class="mb-1 text-16"><span class="counter-value" data-target="{{ $absenHariIni }}">0</span></h5>
                                <p class="truncate text-slate-500 dark:text-zink-200">Absent Employee (Today)</p>
                            </div>
                        </div>
                    </div>
                </div><!--end col-->
                <div class="xl:col-span-3">
                    <div class="card">
                        <div class="flex items-center gap-4 card-body">
                            <div class="flex items-center justify-center text-green-500 bg-green-100 rounded-md size-12 text-15 dark:bg-green-500/20 shrink-0"><i data-lucide="user-check-2"></i></div>
                            <div class="overflow-hidden grow">
                                <h5 class="mb-1 text-16"><span class="counter-value" data-target="{{ $hadirHariIni }}">0</span></h5>
                                <p class="truncate text-slate-500 dark:text-zink-200">Present Employee (Today)</p>
                            </div>
                        </div>
                    </div>
                </div><!--end col-->
                <div class="xl:col-span-3">
                    <div class="card">
                        <div class="flex items-center gap-4 card-body">
                            <div class="flex items-center justify-center rounded-md size-12 text-custom-500 bg-custom-100 text-15 dark:bg-custom-500/20 shrink-0"><i data-lucide="briefcase"></i></div>
                            <div class="overflow-hidden grow">
                                <h5 class="mb-1 text-16"><span class="counter-value" data-target="{{ $hariKerja }}">0</span></h5>
                                <p class="truncate text-slate-500 dark:text-zink-200">Working Days (Current Month)</p>
                            </div>
                        </div>
                    </div>
                </div><!--end col-->
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="grid grid-cols-1 gap-5 mb-5 xl:grid-cols-12">
                        <div class="xl:col-span-3">
                            <div class="relative">
                                <input type="text" class="ltr:pl-8 rtl:pr-8 search form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200" placeholder="Search for ..." autocomplete="off">
                                <i data-lucide="search" class="inline-block size-4 absolute ltr:left-2.5 rtl:right-2.5 top-2.5 text-slate-500 dark:text-zink-200 fill-slate-100 dark:fill-zink-600"></i>
                            </div>
                        </div><!--end col-->
                        <div class="xl:col-span-2 xl:col-start-11">
                            <div class="relative">
                                <i data-lucide="calendar-range" class="absolute size-4 ltr:left-3 rtl:right-3 top-3 text-slate-500 dark:text-zink-200"></i>
                                <input type="text" class="ltr:pl-10 rtl:pr-10 form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200" data-provider="flatpickr" data-date-format="d M, Y" data-range-date="true" readonly="readonly" placeholder="Select Date">
                            </div>
                        </div>
                    </div><!--end grid-->
                    <div class="overflow-x-auto">
                        <table class="w-full whitespace-nowrap">
                            <thead class="ltr:text-left rtl:text-right bg-slate-100 text-slate-500 dark:text-zink-200 dark:bg-zink-600">
                                <tr class="*:px-3.5 *:py-2.5 *:font-semibold *:border-b *:border-slate-200 *:dark:border-zink-500">
                                    <th>Employee Name</th>
                                    <th>01</th>
                                    <th>02</th>
                                    <th>03</th>
                                    <th>04</th>
                                    <th>05</th>
                                    <th class="active">06</th>
                                    <th>07</th>
                                    <th>08</th>
                                    <th>09</th>
                                    <th>10</th>
                                    <th>11</th>
                                    <th>12</th>
                                    <th>13</th>
                                    <th>14</th>
                                    <th>15</th>
                                    <th>16</th>
                                    <th>17</th>
                                    <th>18</th>
                                    <th>19</th>
                                    <th>20</th>
                                    <th>21</th>
                                    <th>22</th>
                                    <th>23</th>
                                    <th>24</th>
                                    <th>25</th>
                                    <th>26</th>
                                    <th>27</th>
                                    <th>28</th>
                                    <th>29</th>
                                    <th>30</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="*:px-3.5 *:py-2.5 *:border-y *:border-slate-200 *:dark:border-zink-500">
                                    <td><a href="#!" class="transition-all duration-200 ease-linear">Patricia Garcia</a></td>
                                    <td><i data-lucide="x" class="text-red-500 size-4"></i></td>
                                    <td><i data-lucide="x" class="text-red-500 size-4"></i></td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td><i data-lucide="x" class="text-red-500 size-4"></i></td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td><i data-lucide="x" class="text-red-500 size-4"></i></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td><i data-lucide="x" class="text-red-500 size-4"></i></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                </tr>
                                <tr class="*:px-3.5 *:py-2.5 *:border-y *:border-slate-200 *:dark:border-zink-500">
                                    <td><a href="#!" class="transition-all duration-200 ease-linear">Tonya Johnson</a></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td><i data-lucide="x" class="text-red-500 size-4"></i></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td><i data-lucide="x" class="text-red-500 size-4"></i></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td><i data-lucide="x" class="text-red-500 size-4"></i></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td><i data-lucide="x" class="text-red-500 size-4"></i></td>
                                </tr>
                                <tr class="*:px-3.5 *:py-2.5 *:border-y *:border-slate-200 *:dark:border-zink-500">
                                    <td><a href="#!" class="transition-all duration-200 ease-linear">Willie Torres</a></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td><i data-lucide="x" class="text-red-500 size-4"></i></td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td><i data-lucide="x" class="text-red-500 size-4"></i></td>
                                    <td><i data-lucide="x" class="text-red-500 size-4"></i></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td><i data-lucide="x" class="text-red-500 size-4"></i></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                </tr>
                                <tr class="*:px-3.5 *:py-2.5 *:border-y *:border-slate-200 *:dark:border-zink-500">
                                    <td><a href="#!" class="transition-all duration-200 ease-linear">Jose White</a></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td><i data-lucide="x" class="text-red-500 size-4"></i></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td><i data-lucide="x" class="text-red-500 size-4"></i></td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                </tr>
                                <tr class="*:px-3.5 *:py-2.5 *:border-y *:border-slate-200 *:dark:border-zink-500">
                                    <td><a href="#!" class="transition-all duration-200 ease-linear">Juliette Fecteau</a></td>
                                    <td><i data-lucide="x" class="text-red-500 size-4"></i></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td><i data-lucide="x" class="text-red-500 size-4"></i></td>
                                    <td><i data-lucide="x" class="text-red-500 size-4"></i></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                </tr>
                                <tr class="*:px-3.5 *:py-2.5 *:border-y *:border-slate-200 *:dark:border-zink-500">
                                    <td><a href="#!" class="transition-all duration-200 ease-linear">Jonas Frederiksen</a></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td><i data-lucide="x" class="text-red-500 size-4"></i></td>
                                    <td><i data-lucide="x" class="text-red-500 size-4"></i></td>
                                    <td><i data-lucide="x" class="text-red-500 size-4"></i></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td><i data-lucide="x" class="text-red-500 size-4"></i></td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td><i data-lucide="x" class="text-red-500 size-4"></i></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                </tr>
                                <tr class="*:px-3.5 *:py-2.5 *:border-y *:border-slate-200 *:dark:border-zink-500">
                                    <td><a href="#!" class="transition-all duration-200 ease-linear">Kim Broberg</a></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td><i data-lucide="x" class="text-red-500 size-4"></i></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td><i data-lucide="x" class="text-red-500 size-4"></i></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td><i data-lucide="x" class="text-red-500 size-4"></i></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                </tr>
                                <tr class="*:px-3.5 *:py-2.5 *:border-y *:border-slate-200 *:dark:border-zink-500">
                                    <td><a href="#!" class="transition-all duration-200 ease-linear">Nancy Reynolds</a></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td><i data-lucide="x" class="text-red-500 size-4"></i></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td><i data-lucide="x" class="text-red-500 size-4"></i></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td><i data-lucide="x" class="text-red-500 size-4"></i></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                </tr>
                                <tr class="*:px-3.5 *:py-2.5 *:border-y *:border-slate-200 *:dark:border-zink-500">
                                    <td><a href="#!" class="transition-all duration-200 ease-linear">Thomas Hatfield</a></td>
                                    <td><i data-lucide="x" class="text-red-500 size-4"></i></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td><i data-lucide="x" class="text-red-500 size-4"></i></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td><i data-lucide="x" class="text-red-500 size-4"></i></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td><i data-lucide="x" class="text-red-500 size-4"></i></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td><i data-lucide="x" class="text-red-500 size-4"></i></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                </tr>
                                <tr class="*:px-3.5 *:py-2.5 *:border-y *:border-slate-200 *:dark:border-zink-500">
                                    <td><a href="#!" class="transition-all duration-200 ease-linear">Holly Kavanaugh</a></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td><i data-lucide="x" class="text-red-500 size-4"></i></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td><i data-lucide="x" class="text-red-500 size-4"></i></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td><i data-lucide="x" class="text-red-500 size-4"></i></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                    <td><i data-lucide="x" class="text-red-500 size-4"></i></td>
                                    <td><a href="#!" data-modal-target="addOrderModal" class="text-green-500 transition-all duration-200 ease-linear hover:text-green-600"><i data-lucide="check" class="size-4"></i></a></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div><!--end card-->

        </div>
        <!-- container-fluid -->
    </div>
    <!-- End Page-content -->

@section('script')

@endsection
@endsection

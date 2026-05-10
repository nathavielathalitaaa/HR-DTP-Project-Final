@extends('layouts.master')
@section('content')
    
    {{-- header --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-playfair font-bold text-[#1A2B24]">Holidays List</h1>
            <p class="text-sm text-gray-500 mt-1">Manage national holidays and compensatory leaves</p>
        </div>
        <button data-modal-target="addHolidayModal" type="button"
            class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#4F6560] hover:bg-[#3d504c] text-white rounded-xl text-sm font-bold shadow transition">
            <i data-lucide="plus" class="w-4 h-4"></i> Add Holiday
        </button>
    </div>

    {{-- table card --}}
    <div class="bg-white/80 backdrop-blur-md rounded-[24px] shadow-sm border border-white/40 p-6">
        <div class="overflow-x-auto">
            <table id="alternativePagination" class="display w-full text-sm">
            <thead>
                <tr class="text-left text-[#4F6560] border-b border-gray-100">
                    <th hidden>No</th>
                    <th class="px-4 py-3 font-semibold">No</th>
                    <th class="px-4 py-3 font-semibold">Day</th>
                    <th class="px-4 py-3 font-semibold">Date</th>
                    <th hidden>Date</th>
                    <th class="px-4 py-3 font-semibold">Holiday Name</th>
                    <th class="px-4 py-3 font-semibold">Type</th>
                    <th class="px-4 py-3 font-semibold text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($holidayList as $key => $value)
                    <tr class="border-b border-gray-50 hover:bg-[#80BB9B]/5 transition">
                        <td id="idUpdate" hidden>{{ $value->id }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ ++ $key }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ Carbon\Carbon::parse($value->holiday_date)->format('l') }}</td>
                        <td id="holiday_date" hidden class="px-4 py-3 text-gray-600">{{ $value->holiday_date }}</td>
                        <td class="px-4 py-3 font-medium text-[#1A2B24]">{{ Carbon\Carbon::parse($value->holiday_date)->format('d M Y') }}</td>
                        <td id="holiday_name" class="px-4 py-3 text-gray-800 font-medium">{{ $value->holiday_name }}</td>
                        <td id="holiday_type" class="px-4 py-3">
                            <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider
                                {{ $value->holiday_type === 'Gazetted Holiday' ? 'bg-red-100 text-red-600' : 'bg-blue-100 text-blue-600' }}">
                                {{ $value->holiday_type }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex gap-2 justify-center">
                                <a href="#!" data-modal-target="editHolidayModal" id="updateHoliday"
                                   class="p-2 rounded-lg bg-[#80BB9B]/20 text-[#4F6560] hover:bg-[#80BB9B]/40 transition">
                                    <i data-lucide="pencil" class="w-4 h-4"></i>
                                </a>
                                <a href="#!" data-modal-target="deleteModal" id="deleteHoliday"
                                   class="p-2 rounded-lg bg-red-50 text-red-400 hover:bg-red-100 hover:text-red-600 transition">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        </div>
    </div>


    <div id="addHolidayModal" modal-center="" class="fixed flex flex-col hidden transition-all duration-300 ease-in-out left-2/4 z-drawer -translate-x-2/4 -translate-y-2/4 show ">
        <div class="w-screen md:w-[30rem] bg-white shadow rounded-md dark:bg-zink-600">
            <div class="flex items-center justify-between p-4 border-b dark:border-zink-500">
                <h5 class="text-16">Add Holiday</h5>
                <button data-modal-close="addHolidayModal" class="transition-all duration-200 ease-linear text-slate-400 hover:text-red-500"><i data-lucide="x" class="w-5 h-5"></i></button>
            </div>
            <div class="max-h-[calc(theme('height.screen')_-_180px)] p-4 overflow-y-auto">
                <form action="{{ route('hr/holidays/save') }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 gap-4 xl:grid-cols-12">
                        <div class="xl:col-span-12">
                            <label for="typeSelect" class="inline-block mb-2 text-base font-medium">Type</label>
                            <select name="holiday_type" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200" data-choices="" data-choices-search-false="" id="typeSelect">
                                <option value="">-- Select Holiday--</option>
                                <option value="Restricted Holiday" {{ old('holiday_type') == 'Restricted Holiday' ? 'selected' : '' }}>Restricted Holiday</option>
                                <option value="Gazetted Holiday" {{ old('holiday_type') == 'Gazetted Holiday' ? 'selected' : '' }}>Gazetted Holiday</option>
                                <option value="Observance" {{ old('holiday_type') == 'Observance' ? 'selected' : '' }}>Observance</option>
                                <option value="Season" {{ old('holiday_type') == 'Season' ? 'selected' : '' }}>Season</option>
                            </select>
                            @error('holiday_type')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="xl:col-span-12">
                            <label for="holidayInput" class="inline-block mb-2 text-base font-medium">Holiday Name</label>
                            <input type="text" name="holiday_name" id="holidayInput" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200 @error('holiday_name') is-invalid @enderror " placeholder="Holiday name" value="{{ old('holiday_name') }}">
                            @error('holiday_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="xl:col-span-12">
                            <label for="holidayDateInput" class="inline-block mb-2 text-base font-medium">Date</label>
                            <input type="text" name="holiday_date" id="holidayDateInput" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200 @error('holiday_date') is-invalid @enderror " placeholder="Select date" data-provider="flatpickr" data-date-format="d M, Y" value="{{ old('holiday_date') }}">
                            @error('holiday_date')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="flex justify-end gap-2 mt-4">
                        <button type="reset" data-modal-close="addHolidayModal" class="text-red-500 bg-white btn hover:text-red-500 hover:bg-red-100 focus:text-red-500 focus:bg-red-100 active:text-red-500 active:bg-red-100 dark:bg-zink-600 dark:hover:bg-red-500/10 dark:focus:bg-red-500/10 dark:active:bg-red-500/10">Cancel</button>
                        <button type="submit" class="text-white btn bg-custom-500 border-custom-500 hover:text-white hover:bg-custom-600 hover:border-custom-600 focus:text-white focus:bg-custom-600 focus:border-custom-600 focus:ring focus:ring-custom-100 active:text-white active:bg-custom-600 active:border-custom-600 active:ring active:ring-custom-100 dark:ring-custom-400/20">Add Holiday</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="editHolidayModal" modal-center="" class="fixed flex flex-col hidden transition-all duration-300 ease-in-out left-2/4 z-drawer -translate-x-2/4 -translate-y-2/4 show ">
        <div class="w-screen md:w-[30rem] bg-white shadow rounded-md dark:bg-zink-600">
            <div class="flex items-center justify-between p-4 border-b dark:border-zink-500">
                <h5 class="text-16">Edit Holiday</h5>
                <button data-modal-close="editHolidayModal" class="transition-all duration-200 ease-linear text-slate-400 hover:text-red-500"><i data-lucide="x" class="w-5 h-5"></i></button>
            </div>
            <div class="max-h-[calc(theme('height.screen')_-_180px)] p-4 overflow-y-auto">
                <form action="{{ route('hr/holidays/save') }}" method="POST">
                    @csrf
                    <input type="hidden" name="idUpdate" id="e_idUpdate" value="">
                    <div class="grid grid-cols-1 gap-4 xl:grid-cols-12">
                        <div class="xl:col-span-12">
                            <label for="typeSelect" class="inline-block mb-2 text-base font-medium">Type</label>
                            <select name="holiday_type" id="e_holiday_type" class="form-select border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200">
                                <option value="Restricted Holiday">Restricted Holiday</option>
                                <option value="Gazetted Holiday">Gazetted Holiday</option>
                                <option value="Observance">Observance</option>
                                <option value="Season">Season</option>
                            </select>
                            @error('holiday_type')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="xl:col-span-12">
                            <label for="holidayInput" class="inline-block mb-2 text-base font-medium">Holiday Name</label>
                            <input type="text" name="holiday_name" id="e_holiday_name" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200 @error('holiday_name') is-invalid @enderror " placeholder="Holiday name" value="{{ old('holiday_name') }}">
                            @error('holiday_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="xl:col-span-12">
                            <label for="holiday_date" class="inline-block mb-2 text-base font-medium">Date</label>
                            <input type="text" name="holiday_date" id="e_holiday_date" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200 @error('holiday_date') is-invalid @enderror " placeholder="Select date" data-provider="flatpickr" data-date-format="d M, Y" value="{{ old('holiday_date') }}">
                            @error('holiday_date')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="flex justify-end gap-2 mt-4">
                        <button type="reset" data-modal-close="editHolidayModal" class="text-red-500 bg-white btn hover:text-red-500 hover:bg-red-100 focus:text-red-500 focus:bg-red-100 active:text-red-500 active:bg-red-100 dark:bg-zink-600 dark:hover:bg-red-500/10 dark:focus:bg-red-500/10 dark:active:bg-red-500/10">Cancel</button>
                        <button type="submit" class="text-white btn bg-custom-500 border-custom-500 hover:text-white hover:bg-custom-600 hover:border-custom-600 focus:text-white focus:bg-custom-600 focus:border-custom-600 focus:ring focus:ring-custom-100 active:text-white active:bg-custom-600 active:border-custom-600 active:ring active:ring-custom-100 dark:ring-custom-400/20">Update Holiday</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="deleteModal" modal-center="" class="fixed flex flex-col hidden transition-all duration-300 ease-in-out left-2/4 z-drawer -translate-x-2/4 -translate-y-2/4 show">
        <div class="w-screen md:w-[25rem] bg-white shadow rounded-md dark:bg-zink-600">
            <div class="max-h-[calc(theme('height.screen')_-_180px)] overflow-y-auto px-6 py-8">
                <div class="float-right">
                    <button data-modal-close="deleteModal" class="transition-all duration-200 ease-linear text-slate-500 hover:text-red-500">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>
                <img src="data:image/png;base64,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" alt="" class="block h-12 mx-auto">
                <form action="{{ route('hr/holidays/delete') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id_delete" id="e_idDelete" value="">
                    <div class="mt-5 text-center">
                        <h5 class="mb-1">Are you sure?</h5>
                        <p class="text-slate-500 dark:text-zink-200">Are you certain you want to delete this record?</p>
                        <div class="flex justify-center gap-2 mt-6">
                            <button type="reset" data-modal-close="deleteModal" class="bg-white text-slate-500 btn hover:text-slate-500 hover:bg-slate-100 focus:text-slate-500 focus:bg-slate-100 active:text-slate-500 active:bg-slate-100 dark:bg-zink-600 dark:hover:bg-slate-500/10 dark:focus:bg-slate-500/10 dark:active:bg-slate-500/10">Cancel</button>
                            <button type="submit" class="text-white bg-red-500 border-red-500 btn hover:text-white hover:bg-red-600 hover:border-red-600 focus:text-white focus:bg-red-600 focus:border-red-600 focus:ring focus:ring-red-100 active:text-white active:bg-red-600 active:border-red-600 active:ring active:ring-red-100 dark:ring-custom-400/20">Yes, Delete It!</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@section('script')
    {{-- update js --}}
    <script>
        $(document).on('click','#updateHoliday',function()
        {
            var _this = $(this).parents('tr');
            $('#e_idUpdate').val(_this.find('#idUpdate').text());
            $('#e_holiday_type').val(_this.find('#holiday_type').text()).change();
            $('#e_holiday_name').val(_this.find('#holiday_name').text());
            $('#e_holiday_date').val(_this.find('#holiday_date').text());
        });
    </script>

    {{-- delete js --}}
    <script>
        $(document).on('click','#deleteHoliday',function()
        {
            var _this = $(this).parents('tr');
            $('#e_idDelete').val(_this.find('#idUpdate').text());
        });
    </script>
@endsection
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    if (document.getElementById('alternativePagination')) {
        new DataTable('#alternativePagination', {
            pagingType: 'full_numbers',
            columnDefs: [
                { orderable: false, targets: [0, 7] }
            ],
            language: {
                search: 'Search:',
                lengthMenu: 'Show _MENU_ entries',
                info: 'Showing _START_ to _END_ of _TOTAL_ entries',
                paginate: { first: 'First', last: 'Last', next: 'Next', previous: 'Previous' },
                emptyTable: 'No data available'
            }
        });
    }
});
</script>
@endpush
@endsection

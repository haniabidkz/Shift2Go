@php
// $logo = asset(Storage::url('uploads/logo/'));
$logo=\App\Models\Utility::get_file('uploads/logo/');

$company_logo = Utility::get_company_logo();

$setting = App\Models\Utility::settings();

    // dd($setting);
@endphp
<div class="modal-body">
    <div class="text-md-end mb-2">
        <a href="#" class="btn btn-sm btn-primary" data-bs-toggle="tooltip"
            data-bs-placement="bottom" title="{{ __('Download') }}" onclick="saveAsPDF()"><span
                class="fa fa-download"></span></a>

        <a title="Mail Send" href="{{ route('payslip.send', [$employee->id, $payslip->salary_month]) }}"
            class="btn btn-sm btn-warning"><span class="fa fa-paper-plane"></span></a>
    </div>
    <div class="invoice" id="printableArea">
    <div class="row">
        <div class="col-form-label">
            <div class="invoice-number">
                <img src="{{ $logo . '/' . (isset($company_logo) && !empty($company_logo) ? $company_logo : 'logo-dark.png') }}"
                    width="170px;">
            </div>


                <div class="invoice-print">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="invoice-title">
                                {{-- <h6 class="mb-3">{{ __('Payslip') }}</h6> --}}

                            </div>
                            <hr>
                            <div class="row text-sm">
                                <div class="col-md-6">
                                    <address>
                                        <strong>{{ __('Name') }} :</strong> {{ $employee->first_name }} {{ $employee->last_name }}<br>
                                        <strong>{{ __('Position') }} :</strong> {{ __('Employee') }}<br>
                                        <strong>{{ __('Salary Date') }} :</strong>
                                        {{ \Auth::user()->dateFormat($payslip->created_at) }}<br>
                                    </address>
                                </div>
                                <div class="col-md-6 text-end">
                                    <address>
                                        <strong>{{ $setting['company_email'] }} </strong><br>
                                        {{ \Utility::getValByName('company_email_from_name') }}<br>
                                        {{-- {{ \Utility::getValByName('company_city') }},<br>
                                        {{ \Utility::getValByName('company_state') }}-{{ \Utility::getValByName('company_zipcode') }}<br> --}}
                                        <strong>{{ __('Salary Slip') }} :</strong> {{ $payslip->salary_month }}<br>
                                    </address>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table  table-md">
                                    <tbody>
                                        <tr class="font-weight-bold">
                                            <th>{{ __('Role Name') }}</th>
                                            <th>{{ __('Salary by hour') }}</th>
                                            <th>{{ __('Salary by shift') }}</th>
                                            <th>{{ __('Total working hours') }}</th>
                                            <th class="text-right">{{ __('Total Amount') }}</th>
                                        </tr>
                                        {{-- @dd($user_salaries) --}}
                                        {{-- @dd($user_salaries); --}}

                                        @php
                                            $net_total=0;
                                        @endphp

                                        @foreach ($user_salaries as $k => $data)
                                        {{-- @dd($data) --}}
                                        <tr>
                                            <td>{{ !empty($data['name']) ? $data['name'] : 'Default' }}</td>
                                            <td>{{ \Auth::user()->priceFormat($data['salary']). ' '. __('per hour') }} </td>
                                            <td>{{ \Auth::user()->priceFormat($data['shift_salary']) }}</td>
                                            <td>{{ round($data['time'],2)}}</td>
                                            <td class="text-right">{{ \Auth::user()->priceFormat($data['salary'] * $data['time'] + $data['shift_salary']) }}</td>
                                        </tr>
                                            @php
                                                $net_total += $data['salary'] * $data['time'] + $data['shift_salary']
                                            @endphp
                                        @endforeach

                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-striped table-hover table-md">
                                    <tbody>
                                        <tr class="font-weight-bold">
                                            <td >{{ __('') }}</td>
                                            <td>{{ __('') }}</td>
                                            <td class="text-right">{{ __('Net Salary') }}</td>
                                            <td>{{ \Auth::user()->priceFormat($net_total)}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="text-md-right pb-2 text-sm">
                    <div class="float-lg-left mb-lg-0 mb-3 ">
                        <p class="mt-2">{{ __('Employee Signature') }}</p>
                    </div>
                    <p class="mt-2 "> {{ __('Paid By') }}</p>
                </div>
            </div>
        </div>

    </div>
</div>

<script type="text/javascript" src="{{ asset('js/html2pdf.bundle.min.js') }}"></script>
<script>
    function saveAsPDF() {
        var element = document.getElementById('printableArea');
        var opt = {
            margin: 0.3,
            filename: '{{ $employee->name }}',
            image: {
                type: 'jpeg',
                quality: 1
            },
            html2canvas: {
                scale: 4,
                dpi: 72,
                letterRendering: true
            },
            jsPDF: {
                unit: 'in',
                format: 'A4'
            }
        };
        html2pdf().set(opt).from(element).save();
    }
</script>

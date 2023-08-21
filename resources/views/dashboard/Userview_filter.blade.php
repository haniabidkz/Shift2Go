<table class="table align-items-center pc-dt-simple">
    <thead>
        <tr>
            <th>{{ __('Name') }}</th>
            <th>{{ __('Date') }}</th>
            <th>{{ __('Time') }}</th>
            <th>{{ __('Break') }}</th>
            <th>{{ __('Location') }}</th>
            <th>{{ __('Revenue') }}</th>
        </tr>
    </thead>
    <tbody>
        @if (!empty($rotas) && count($rotas) != 0)
            @foreach ($rotas as $rota)
                <tr>
                    <th>
                        <div class="media align-items-center">
                            <div>
                                <div class="avatar-parent-child">
                                    <img src="{{ asset(Storage::url($rota->userprofile($rota->user_id))) }}"
                                        class="avatar  rounded-circle" style="width: 50px;">
                                </div>
                            </div>
                            <div class="media-body ml-4">
                                <a href="#"
                                    class="d-block name h6 mb-0 text-sm">{{ !empty($rota->getrotauser->first_name) ? $rota->getrotauser->first_name : '' }}</a>
                                <div class="d-inline-block day_view_dot"
                                    style="background-color: {{ $rota->getrotarole->color }}"></div>
                                <small
                                    class="d-inline-block font-weight-bold">{{ $rota->getrotarole->name }}</small>
                            </div>
                        </div>
                    </th>
                    <td> {{ $rota->rotas_date }} </td>
                    <td> {{ \App\Models\User::CompanyTimeFormat($rota->start_time) }} -
                        {{ \App\Models\User::CompanyTimeFormat($rota->end_time) }} </td>
                    <td> {{ $rota->break_time . __('Min') }} </td>
                    <td> {{ $rota->getrotalocation->name }} </td>
                    <td> {{ $rota->rota_cost($rota) }} </td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="5">
                    <div class="text-center">
                        <i class="fas fa-calendar-times text-primary fs-40"></i>
                        <h2>{{ __('Opps...') }}</h2>
                        <h6> {!! __('No rotas found.') !!} </h6>
                    </div>
                </td>
            </tr>
        @endif
    </tbody>
</table>

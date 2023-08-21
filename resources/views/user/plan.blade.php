@foreach($plans as $plan)
    <div class="list-group-item">
        <div class="row align-items-center">
            {{-- <div class="col-auto">
                <a href="#" class="avatar rounded-circle">
                    <img alt="Image placeholder" src="{{asset(Storage::url('uploads/plan')).'/'.$plan->image}}" class="" style="width : 50px">
                </a>
            </div> --}}
            <div class="col ml-n2">
                <a href="#!" class="d-block h6 mb-0">{{$plan->name}}</a>
                <div>
                    <span class="text-sm">{{env('CURRENCY_SYMBOL').$plan->price}} {{' / '. $plan->duration}}</span>
                </div>
            </div>
            <div class="col ml-n2">
                <a href="#!" class="d-block h6 mb-0">{{__('Employees')}}</a>
                <div>
                    <span class="text-sm">{{$plan->max_employee}}</span>
                </div>
            </div>
            <div class="col-auto">
                @if($user->plan==$plan->id)
                    <span class="btn btn-sm btn-primary my-auto">{{__('Active')}}</span>
                @else
                    <a href="{{route('plan.active',[$user->id,$plan->id])}}" class="btn btn-sm btn-warning btn-icon" data-toggle="tooltip" data-original-title="{{__('Click to Upgrade Plan')}}">
                        <i class="ti ti-shopping-cart" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Upgrade') }}"></i>
                    </a>
                @endif
            </div>
        </div>
    </div>
@endforeach

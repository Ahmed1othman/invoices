@if(Session::has('error'))


    <div class="card bd-0 mg-b-20">
        <div class="card-body bd bd-danger text-danger text-center">
            <div class="danger-widget">
                <p class="mt-3 mb-0">{{Session::get('error')}}</p>
            </div>
        </div>
    </div>
@endif

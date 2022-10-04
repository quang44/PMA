@extends('frontend.layouts.app')

@section('content')
    <section class="gry-bg py-4">
        <div class="profile">
            <div class="container">
                <div class="row">
                    <div class="col-xxl-6 col-xl-8 col-lg-8 col-md-8 mx-auto">
                        <div class="card">
                            <div class="text-center pt-4">
                                <h1 class="h4 fw-600">
                                    ĐĂNG KÝ THÀNH CÔNG
                                </h1>
                                <div>
                                    Tải ngay App GOMDON - Gom Đơn Express
                                    Hưởng trọn bộ tính năng và những ưu đãi tốt nhất chỉ với một chạm
                                </div>
                            </div>
                            <div class="px-4 py-3 py-lg-4" style="padding-top: 0 !important;">
                                <div class="row mb-3">
                                    <a href="javascript:void(0)" style="padding: 3px;width: 100%;text-align: center">
                                        <img style="width: 50%" src="{{ asset('public/assets/img/qrcode.png') }}" alt="">
                                    </a>
                                </div>
                                <div class="row">
                                    <a href="https://play.google.com/store/apps/details?id=gomdon.com.vn" style="padding: 3px;width: 50%;text-align: center">
                                        <img style="width: 100%" src="{{ asset('public/assets/img/play.png') }}" alt="">
                                    </a>
                                    <a href="https://apps.apple.com/us/app/gomdon/id1634363930" style="padding: 3px;width: 50%;text-align: center">
                                        <img style="width: 100%" src="{{ asset('public/assets/img/app.png') }}" alt="">
                                    </a>
<!--                                    <ul class="list-inline social colored text-center mb-5">
                                        <li class="list-inline-item">
                                            <a href="{{ route('social.login', ['provider' => 'facebook']) }}" class="facebook">

                                            </a>
                                        </li>
                                        <li class="list-inline-item">
                                            <a href="{{ route('social.login', ['provider' => 'google']) }}" class="google">
                                                <i class="lab la-google"></i>
                                            </a>
                                        </li>
                                    </ul>-->
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
@endsection


@section('script')
    @if(get_setting('google_recaptcha') == 1)
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    @endif

    <script type="text/javascript">

        @if(get_setting('google_recaptcha') == 1)
        // making the CAPTCHA  a required field for form submission
        $(document).ready(function(){
            // alert('helloman');
            $("#reg-form").on("submit", function(evt)
            {
                var response = grecaptcha.getResponse();
                if(response.length == 0)
                {
                //reCaptcha not verified
                    alert("please verify you are humann!");
                    evt.preventDefault();
                    return false;
                }
                //captcha verified
                //do the rest of your validations here
                $("#reg-form").submit();
            });
        });
        @endif

        var isPhoneShown = true,
            countryData = window.intlTelInputGlobals.getCountryData(),
            input = document.querySelector("#phone-code");

        for (var i = 0; i < countryData.length; i++) {
            var country = countryData[i];
            if(country.iso2 == 'bd'){
                country.dialCode = '88';
            }
        }

        var iti = intlTelInput(input, {
            separateDialCode: true,
            utilsScript: "{{ static_asset('assets/js/intlTelutils.js') }}?1590403638580",
            onlyCountries: @php echo json_encode(\App\Models\Country::where('status', 1)->pluck('code')->toArray()) @endphp,
            customPlaceholder: function(selectedCountryPlaceholder, selectedCountryData) {
                if(selectedCountryData.iso2 == 'bd'){
                    return "01xxxxxxxxx";
                }
                return selectedCountryPlaceholder;
            }
        });

        var country = iti.getSelectedCountryData();
        $('input[name=country_code]').val(country.dialCode);

        input.addEventListener("countrychange", function(e) {
            // var currentMask = e.currentTarget.placeholder;

            var country = iti.getSelectedCountryData();
            $('input[name=country_code]').val(country.dialCode);

        });

        function toggleEmailPhone(el){
            if(isPhoneShown){
                $('.phone-form-group').addClass('d-none');
                $('.email-form-group').removeClass('d-none');
                isPhoneShown = false;
                $(el).html('{{ translate('Use Phone Instead') }}');
            }
            else{
                $('.phone-form-group').removeClass('d-none');
                $('.email-form-group').addClass('d-none');
                isPhoneShown = true;
                $(el).html('{{ translate('Use Email Instead') }}');
            }
        }
    </script>

    @if($url)
        <script type="text/javascript">
            $(document).ready(function (){
                setTimeout(function() {
                    window.location.href = `{{ $url }}`;
                }, 5000);
            })
        </script>
    @endif
@endsection

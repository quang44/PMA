@extends('backend.layouts.app')

@section('content')

    {{--@php--}}
    {{--    CoreComponentRepository::instantiateShopRepository();--}}
    {{--    CoreComponentRepository::initializeCache();--}}
    {{--@endphp--}}

    <div class="aiz-titlebar text-left mt-2 mb-3">
        <h5 class="mb-0 h6">{{translate('Edit Product')}}</h5>
    </div>
    <div class="">
        <form class="form form-horizontal mar-top" action="{{route('products.update',$product->id)}}" method="POST" enctype="multipart/form-data" id="choice_form">
            <div class="row gutters-5">
                <div class="col-lg-12">
                    @csrf
                    <input type="hidden" name="added_by" value="admin">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0 h6">{{translate('Product Information')}}</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{translate('Product Name')}} <span class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" name="name" value="{{$product->name}}" placeholder="{{ translate('Product Name') }}" onchange="update_sku()" required>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{translate('Warranty duration')}} <span class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    <input type="text" name="warranty_duration" value="{{$product->warranty_duration}} " lang="vn" class="form-control"  required>
                                </div>
                            </div>


                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0 h6">{{translate('Product Images')}}</h5>
                        </div>
                        <div class="card-body">
                            {{--                        <div class="form-group row">--}}
                            {{--                            <label class="col-md-3 col-form-label" for="signinSrEmail">{{translate('Gallery Images')}} <small>(600x600)</small></label>--}}
                            {{--                            <div class="col-md-8">--}}
                            {{--                                <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="true">--}}
                            {{--                                    <div class="input-group-prepend">--}}
                            {{--                                        <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>--}}
                            {{--                                    </div>--}}
                            {{--                                    <div class="form-control file-amount">{{ translate('Choose File') }}</div>--}}
                            {{--                                    <input type="hidden" name="photos" class="selected-files">--}}
                            {{--                                </div>--}}
                            {{--                                <div class="file-preview box sm">--}}
                            {{--                                </div>--}}
                            {{--                                <small class="text-muted">{{translate('These images are visible in product details page gallery. Use 600x600 sizes images.')}}</small>--}}
                            {{--                            </div>--}}
                            {{--                        </div>--}}
                            <div class="form-group row">
                                <label class="col-md-3 col-form-label" for="signinSrEmail">{{translate('Thumbnail Image')}} <small>(300x300)</small></label>
                                <div class="col-md-8">
                                    <div class="input-group" data-toggle="aizuploader" data-type="image">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
                                        </div>
                                        <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                        <input type="hidden" name="thumbnail_img" class="selected-files" value="{{$product->thumbnail_img}}">
                                    </div>
                                    <div class="file-preview box sm">
                                    </div>
                                    <small class="text-muted">{{translate('This image is visible in all product box. Use 300x300 sizes image. Keep some blank space around main object of your image as we had to crop some edge in different devices to make it responsive.')}}</small>
                                </div>
                            </div>
                        </div>
                    </div>




                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0 h6">{{translate('Product Description')}}</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{translate('Description')}}</label>
                                <div class="col-md-8">
                                    <textarea class="aiz-text-editor"  name="description">
                                       {{$product->description}}
                                    </textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{--            <div class="card">--}}
                    {{--                    <div class="card-header">--}}
                    {{--                        <h5 class="mb-0 h6">{{translate('Product Shipping Cost')}}</h5>--}}
                    {{--                    </div>--}}
                    {{--                    <div class="card-body">--}}

                    {{--                    </div>--}}
                    {{--                </div>--}}

                    {{--                <div class="card">--}}
                    {{--                    <div class="card-header">--}}
                    {{--                        <h5 class="mb-0 h6">{{translate('PDF Specification')}}</h5>--}}
                    {{--                    </div>--}}
                    {{--                    <div class="card-body">--}}
                    {{--                        <div class="form-group row">--}}
                    {{--                            <label class="col-md-3 col-form-label" for="signinSrEmail">{{translate('PDF Specification')}}</label>--}}
                    {{--                            <div class="col-md-8">--}}
                    {{--                                <div class="input-group" data-toggle="aizuploader" data-type="document">--}}
                    {{--                                    <div class="input-group-prepend">--}}
                    {{--                                        <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>--}}
                    {{--                                    </div>--}}
                    {{--                                    <div class="form-control file-amount">{{ translate('Choose File') }}</div>--}}
                    {{--                                    <input type="hidden" name="pdf" class="selected-files">--}}
                    {{--                                </div>--}}
                    {{--                                <div class="file-preview box sm">--}}
                    {{--                                </div>--}}
                    {{--                            </div>--}}
                    {{--                        </div>--}}
                    {{--                    </div>--}}
                    {{--                </div>--}}


                    {{--                <div class="card">--}}
                    {{--                    <div class="card-header">--}}
                    {{--                        <h5 class="mb-0 h6">{{translate('SEO Meta Tags')}}</h5>--}}
                    {{--                    </div>--}}
                    {{--                    <div class="card-body">--}}
                    {{--                        <div class="form-group row">--}}
                    {{--                            <label class="col-md-3 col-from-label">{{translate('Meta Title')}}</label>--}}
                    {{--                            <div class="col-md-8">--}}
                    {{--                                <input type="text" class="form-control" name="meta_title" placeholder="{{ translate('Meta Title') }}">--}}
                    {{--                            </div>--}}
                    {{--                        </div>--}}
                    {{--                        <div class="form-group row">--}}
                    {{--                            <label class="col-md-3 col-from-label">{{translate('Description')}}</label>--}}
                    {{--                            <div class="col-md-8">--}}
                    {{--                                <textarea name="meta_description" rows="8" class="form-control"></textarea>--}}
                    {{--                            </div>--}}
                    {{--                        </div>--}}
                    {{--                        <div class="form-group row">--}}
                    {{--                            <label class="col-md-3 col-form-label" for="signinSrEmail">{{ translate('Meta Image') }}</label>--}}
                    {{--                            <div class="col-md-8">--}}
                    {{--                                <div class="input-group" data-toggle="aizuploader" data-type="image">--}}
                    {{--                                    <div class="input-group-prepend">--}}
                    {{--                                        <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>--}}
                    {{--                                    </div>--}}
                    {{--                                    <div class="form-control file-amount">{{ translate('Choose File') }}</div>--}}
                    {{--                                    <input type="hidden" name="meta_img" class="selected-files">--}}
                    {{--                                </div>--}}
                    {{--                                <div class="file-preview box sm">--}}
                    {{--                                </div>--}}
                    {{--                            </div>--}}
                    {{--                        </div>--}}
                    {{--                    </div>--}}
                    {{--                </div>--}}

                </div>

                {{--            <div class="col-lg-4">--}}

                {{--                <div class="card">--}}
                {{--                    <div class="card-header">--}}
                {{--                        <h5 class="mb-0 h6">--}}
                {{--                            {{translate('Shipping Configuration')}}--}}
                {{--                        </h5>--}}
                {{--                    </div>--}}

                {{--                    <div class="card-body">--}}
                {{--                        @if (get_setting('shipping_type') == 'product_wise_shipping')--}}
                {{--                        <div class="form-group row">--}}
                {{--                            <label class="col-md-6 col-from-label">{{translate('Free Shipping')}}</label>--}}
                {{--                            <div class="col-md-6">--}}
                {{--                                <label class="aiz-switch aiz-switch-success mb-0">--}}
                {{--                                    <input type="radio" name="shipping_type" value="free" checked>--}}
                {{--                                    <span></span>--}}
                {{--                                </label>--}}
                {{--                            </div>--}}
                {{--                        </div>--}}

                {{--                        <div class="form-group row">--}}
                {{--                            <label class="col-md-6 col-from-label">{{translate('Flat Rate')}}</label>--}}
                {{--                            <div class="col-md-6">--}}
                {{--                                <label class="aiz-switch aiz-switch-success mb-0">--}}
                {{--                                    <input type="radio" name="shipping_type" value="flat_rate">--}}
                {{--                                    <span></span>--}}
                {{--                                </label>--}}
                {{--                            </div>--}}
                {{--                        </div>--}}

                {{--                        <div class="flat_rate_shipping_div" style="display: none">--}}
                {{--                            <div class="form-group row">--}}
                {{--                                <label class="col-md-6 col-from-label">{{translate('Shipping cost')}}</label>--}}
                {{--                                <div class="col-md-6">--}}
                {{--                                    <input type="number" lang="en" min="0" value="0" step="0.01" placeholder="{{ translate('Shipping cost') }}" name="flat_shipping_cost" class="form-control" required>--}}
                {{--                                </div>--}}
                {{--                            </div>--}}
                {{--                        </div>--}}

                {{--                        <div class="form-group row">--}}
                {{--                            <label class="col-md-6 col-from-label">{{translate('Is Product Quantity Mulitiply')}}</label>--}}
                {{--                            <div class="col-md-6">--}}
                {{--                                <label class="aiz-switch aiz-switch-success mb-0">--}}
                {{--                                    <input type="checkbox" name="is_quantity_multiplied" value="1">--}}
                {{--                                    <span></span>--}}
                {{--                                </label>--}}
                {{--                            </div>--}}
                {{--                        </div>--}}
                {{--                        @else--}}
                {{--                        <p>--}}
                {{--                            {{ translate('Product wise shipping cost is disable. Shipping cost is configured from here') }}--}}
                {{--                            <a href="{{route('shipping_configuration.index')}}" class="aiz-side-nav-link {{ areActiveRoutes(['shipping_configuration.index','shipping_configuration.edit','shipping_configuration.update'])}}">--}}
                {{--                                <span class="aiz-side-nav-text">{{translate('Shipping Configuration')}}</span>--}}
                {{--                            </a>--}}
                {{--                        </p>--}}
                {{--                        @endif--}}
                {{--                    </div>--}}
                {{--                </div>--}}

                {{--                <div class="card">--}}
                {{--                    <div class="card-header">--}}
                {{--                        <h5 class="mb-0 h6">{{translate('Low Stock Quantity Warning')}}</h5>--}}
                {{--                    </div>--}}
                {{--                    <div class="card-body">--}}
                {{--                        <div class="form-group mb-3">--}}
                {{--                            <label for="name">--}}
                {{--                                {{translate('Quantity')}}--}}
                {{--                            </label>--}}
                {{--                            <input type="number" name="low_stock_quantity" value="1" min="0" step="1" class="form-control">--}}
                {{--                        </div>--}}
                {{--                    </div>--}}
                {{--                </div>--}}

                {{--                <div class="card">--}}
                {{--                    <div class="card-header">--}}
                {{--                        <h5 class="mb-0 h6">--}}
                {{--                            {{translate('Stock Visibility State')}}--}}
                {{--                        </h5>--}}
                {{--                    </div>--}}

                {{--                    <div class="card-body">--}}

                {{--                        <div class="form-group row">--}}
                {{--                            <label class="col-md-6 col-from-label">{{translate('Show Stock Quantity')}}</label>--}}
                {{--                            <div class="col-md-6">--}}
                {{--                                <label class="aiz-switch aiz-switch-success mb-0">--}}
                {{--                                    <input type="radio" name="stock_visibility_state" value="quantity" checked>--}}
                {{--                                    <span></span>--}}
                {{--                                </label>--}}
                {{--                            </div>--}}
                {{--                        </div>--}}

                {{--                        <div class="form-group row">--}}
                {{--                            <label class="col-md-6 col-from-label">{{translate('Show Stock With Text Only')}}</label>--}}
                {{--                            <div class="col-md-6">--}}
                {{--                                <label class="aiz-switch aiz-switch-success mb-0">--}}
                {{--                                    <input type="radio" name="stock_visibility_state" value="text">--}}
                {{--                                    <span></span>--}}
                {{--                                </label>--}}
                {{--                            </div>--}}
                {{--                        </div>--}}

                {{--                        <div class="form-group row">--}}
                {{--                            <label class="col-md-6 col-from-label">{{translate('Hide Stock')}}</label>--}}
                {{--                            <div class="col-md-6">--}}
                {{--                                <label class="aiz-switch aiz-switch-success mb-0">--}}
                {{--                                    <input type="radio" name="stock_visibility_state" value="hide">--}}
                {{--                                    <span></span>--}}
                {{--                                </label>--}}
                {{--                            </div>--}}
                {{--                        </div>--}}

                {{--                    </div>--}}
                {{--                </div>--}}

                {{--                <div class="card">--}}
                {{--                    <div class="card-header">--}}
                {{--                        <h5 class="mb-0 h6">{{translate('Cash On Delivery')}}</h5>--}}
                {{--                    </div>--}}
                {{--                    <div class="card-body">--}}
                {{--                        @if (get_setting('cash_payment') == '1')--}}
                {{--                            <div class="form-group row">--}}
                {{--                                <label class="col-md-6 col-from-label">{{translate('Status')}}</label>--}}
                {{--                                <div class="col-md-6">--}}
                {{--                                    <label class="aiz-switch aiz-switch-success mb-0">--}}
                {{--                                        <input type="checkbox" name="cash_on_delivery" value="1" checked="">--}}
                {{--                                        <span></span>--}}
                {{--                                    </label>--}}
                {{--                                </div>--}}
                {{--                            </div>--}}
                {{--                        @else--}}
                {{--                            <p>--}}
                {{--                                {{ translate('Cash On Delivery option is disabled. Activate this feature from here') }}--}}
                {{--                                <a href="{{route('activation.index')}}" class="aiz-side-nav-link {{ areActiveRoutes(['shipping_configuration.index','shipping_configuration.edit','shipping_configuration.update'])}}">--}}
                {{--                                    <span class="aiz-side-nav-text">{{translate('Cash Payment Activation')}}</span>--}}
                {{--                                </a>--}}
                {{--                            </p>--}}
                {{--                        @endif--}}
                {{--                    </div>--}}
                {{--                </div>--}}

                {{--                <div class="card">--}}
                {{--                    <div class="card-header">--}}
                {{--                        <h5 class="mb-0 h6">{{translate('Featured')}}</h5>--}}
                {{--                    </div>--}}
                {{--                    <div class="card-body">--}}
                {{--                        <div class="form-group row">--}}
                {{--                            <label class="col-md-6 col-from-label">{{translate('Status')}}</label>--}}
                {{--                            <div class="col-md-6">--}}
                {{--                                <label class="aiz-switch aiz-switch-success mb-0">--}}
                {{--                                    <input type="checkbox" name="featured" value="1">--}}
                {{--                                    <span></span>--}}
                {{--                                </label>--}}
                {{--                            </div>--}}
                {{--                        </div>--}}
                {{--                    </div>--}}
                {{--                </div>--}}

                {{--                <div class="card">--}}
                {{--                    <div class="card-header">--}}
                {{--                        <h5 class="mb-0 h6">{{translate('Todays Deal')}}</h5>--}}
                {{--                    </div>--}}
                {{--                    <div class="card-body">--}}
                {{--                        <div class="form-group row">--}}
                {{--                            <label class="col-md-6 col-from-label">{{translate('Status')}}</label>--}}
                {{--                            <div class="col-md-6">--}}
                {{--                                <label class="aiz-switch aiz-switch-success mb-0">--}}
                {{--                                    <input type="checkbox" name="todays_deal" value="1">--}}
                {{--                                    <span></span>--}}
                {{--                                </label>--}}
                {{--                            </div>--}}
                {{--                        </div>--}}
                {{--                    </div>--}}
                {{--                </div>--}}

                {{--                <div class="card">--}}
                {{--                    <div class="card-header">--}}
                {{--                        <h5 class="mb-0 h6">{{translate('Flash Deal')}}</h5>--}}
                {{--                    </div>--}}
                {{--                    <div class="card-body">--}}
                {{--                        <div class="form-group mb-3">--}}
                {{--                            <label for="name">--}}
                {{--                                {{translate('Add To Flash')}}--}}
                {{--                            </label>--}}
                {{--                            <select class="form-control aiz-selectpicker" name="flash_deal_id" id="flash_deal">--}}
                {{--                                <option value="">Choose Flash Title</option>--}}
                {{--                                @foreach(\App\Models\FlashDeal::where("status", 1)->get() as $flash_deal)--}}
                {{--                                    <option value="{{ $flash_deal->id}}">--}}
                {{--                                        {{ $flash_deal->title }}--}}
                {{--                                    </option>--}}
                {{--                                @endforeach--}}
                {{--                            </select>--}}
                {{--                        </div>--}}

                {{--                        <div class="form-group mb-3">--}}
                {{--                            <label for="name">--}}
                {{--                                {{translate('Discount')}}--}}
                {{--                            </label>--}}
                {{--                            <input type="number" name="flash_discount" value="0" min="0" step="1" class="form-control">--}}
                {{--                        </div>--}}
                {{--                        <div class="form-group mb-3">--}}
                {{--                            <label for="name">--}}
                {{--                                {{translate('Discount Type')}}--}}
                {{--                            </label>--}}
                {{--                            <select class="form-control aiz-selectpicker" name="flash_discount_type" id="flash_discount_type">--}}
                {{--                                <option value="">Choose Discount Type</option>--}}
                {{--                                <option value="amount">{{translate('Flat')}}</option>--}}
                {{--                                <option value="percent">{{translate('Percent')}}</option>--}}
                {{--                            </select>--}}
                {{--                        </div>--}}
                {{--                    </div>--}}
                {{--                </div>--}}

                {{--                <div class="card">--}}
                {{--                    <div class="card-header">--}}
                {{--                        <h5 class="mb-0 h6">{{translate('Estimate Shipping Time')}}</h5>--}}
                {{--                    </div>--}}
                {{--                    <div class="card-body">--}}
                {{--                        <div class="form-group mb-3">--}}
                {{--                            <label for="name">--}}
                {{--                                {{translate('Shipping Days')}}--}}
                {{--                            </label>--}}
                {{--                            <div class="input-group">--}}
                {{--                                <input type="number" class="form-control" name="est_shipping_days" min="1" step="1" placeholder="{{translate('Shipping Days')}}">--}}
                {{--                                <div class="input-group-prepend">--}}
                {{--                                    <span class="input-group-text" id="inputGroupPrepend">{{translate('Days')}}</span>--}}
                {{--                                </div>--}}
                {{--                            </div>--}}
                {{--                        </div>--}}
                {{--                    </div>--}}
                {{--                </div>--}}

                {{--                <div class="card">--}}
                {{--                    <div class="card-header">--}}
                {{--                        <h5 class="mb-0 h6">{{translate('VAT & Tax')}}</h5>--}}
                {{--                    </div>--}}
                {{--                    <div class="card-body">--}}
                {{--                        @foreach(\App\Models\Tax::where('tax_status', 1)->get() as $tax)--}}
                {{--                        <label for="name">--}}
                {{--                            {{$tax->name}}--}}
                {{--                            <input type="hidden" value="{{$tax->id}}" name="tax_id[]">--}}
                {{--                        </label>--}}

                {{--                        <div class="form-row">--}}
                {{--                            <div class="form-group col-md-6">--}}
                {{--                                <input type="number" lang="en" min="0" value="0" step="0.01" placeholder="{{ translate('Tax') }}" name="tax[]" class="form-control" required>--}}
                {{--                            </div>--}}
                {{--                            <div class="form-group col-md-6">--}}
                {{--                                <select class="form-control aiz-selectpicker" name="tax_type[]">--}}
                {{--                                    <option value="amount">{{translate('Flat')}}</option>--}}
                {{--                                    <option value="percent">{{translate('Percent')}}</option>--}}
                {{--                                </select>--}}
                {{--                            </div>--}}
                {{--                        </div>--}}
                {{--                        @endforeach--}}
                {{--                    </div>--}}
                {{--                </div>--}}

                {{--            </div>--}}
                <div class="col-12">
                    <div class="btn-toolbar float-right mb-3" role="toolbar" aria-label="Toolbar with button groups">
                        <div class="btn-group mr-2" role="group" aria-label="Third group">
                            <button type="submit" name="button" value="unpublish" class="btn btn-primary action-btn">{{ translate('Save & Unpublish') }}</button>
                        </div>
                        <div class="btn-group" role="group" aria-label="Second group">
                            <button type="submit" name="button" value="publish" class="btn btn-success action-btn">{{ translate('Save & Publish') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

@endsection

@section('script')

    <script type="text/javascript">
        $('form').bind('submit', function (e) {
            if ( $(".action-btn").attr('attempted') == 'true' ) {
                //stop submitting the form because we have already clicked submit.
                e.preventDefault();
            }
            else {
                $(".action-btn").attr("attempted", 'true');
            }
            // Disable the submit button while evaluating if the form should be submitted
            // $("button[type='submit']").prop('disabled', true);

            // var valid = true;

            // if (!valid) {
            // e.preventDefault();

            ////Reactivate the button if the form was not submitted
            // $("button[type='submit']").button.prop('disabled', false);
            // }
        });

        $("[name=shipping_type]").on("change", function (){
            $(".flat_rate_shipping_div").hide();

            if($(this).val() == 'flat_rate'){
                $(".flat_rate_shipping_div").show();
            }

        });

        function add_more_customer_choice_option(i, name){
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type:"POST",
                url:'{{ route('products.add-more-choice-option') }}',
                data:{
                    attribute_id: i
                },
                success: function(data) {
                    var obj = JSON.parse(data);
                    $('#customer_choice_options').append('\
                <div class="form-group row">\
                    <div class="col-md-3">\
                        <input type="hidden" name="choice_no[]" value="'+i+'">\
                        <input type="text" class="form-control" name="choice[]" value="'+name+'" placeholder="{{ translate('Choice Title') }}" readonly>\
                    </div>\
                    <div class="col-md-8">\
                        <select class="form-control aiz-selectpicker attribute_choice" data-live-search="true" name="choice_options_'+ i +'[]" multiple>\
                            '+obj+'\
                        </select>\
                    </div>\
                </div>');
                    AIZ.plugins.bootstrapSelect('refresh');
                }
            });


        }

        $('input[name="colors_active"]').on('change', function() {
            if(!$('input[name="colors_active"]').is(':checked')) {
                $('#colors').prop('disabled', true);
                AIZ.plugins.bootstrapSelect('refresh');
            }
            else {
                $('#colors').prop('disabled', false);
                AIZ.plugins.bootstrapSelect('refresh');
            }
            update_sku();
        });

        $(document).on("change", ".attribute_choice",function() {
            update_sku();
        });

        $('#colors').on('change', function() {
            update_sku();
        });

        $('input[name="unit_price"]').on('keyup', function() {
            update_sku();
        });

        $('input[name="name"]').on('keyup', function() {
            update_sku();
        });

        function delete_row(em){
            $(em).closest('.form-group row').remove();
            update_sku();
        }

        function delete_variant(em){
            $(em).closest('.variant').remove();
        }

        function update_sku(){
            $.ajax({
                type:"POST",
                url:'{{ route('products.sku_combination') }}',
                data:$('#choice_form').serialize(),
                success: function(data) {
                    $('#sku_combination').html(data);
                    AIZ.uploader.previewGenerate();
                    AIZ.plugins.fooTable();
                    if (data.length > 1) {
                        $('#show-hide-div').hide();
                    }
                    else {
                        $('#show-hide-div').show();
                    }
                }
            });
        }

        $('#choice_attributes').on('change', function() {
            $('#customer_choice_options').html(null);
            $.each($("#choice_attributes option:selected"), function(){
                add_more_customer_choice_option($(this).val(), $(this).text());
            });

            update_sku();
        });

    </script>

@endsection

@php $i=round(rand()*9999)  @endphp
    <div class="row content{{$i}}">
        <div class="col-3 form-group">
            <label class="col-from-label text-center" for="email">{{translate('Product')}}</label>
            <select name="product[]" id="product{{$i}}" data-product="{{$i}}"
                    class="form-control aiz-selectpicker product" data-selected-text-format="count"
                    data-live-search="true">
                <option >Không có gì được chọn</option>
                @foreach($products as $product)
                    <option value="{{$product->id}}">{{$product->name}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-3 ">
            <label class=" col-from-label" for="district">{{translate('Color')}}</label>
            <select name="color[]" data-color="{{$i}}" id="color{{$i}}"
                    class="color form-control aiz-selectpicker "
                    data-selected-text-format="count"
                    data-live-search="true"
                    disabled>
            </select>
        </div>
        <div class="col-2 form-group">
            <label class="" for="quantity">{{translate('Maximum quantity')}}</label>
            <input type="number" min="0"  name="qty[]" id="quantity{{$i}}" data-qty="{{$i}}" class="quantity form-control" disabled>
        </div>
        <div class="form-group col-3">
            <label class="col-from-label d-sm-flex justify-content-center" for="image">{{translate('Image')}}</label>
            <input type="hidden" id="image{{$i}}"  name="image[]">
            <div class="box sm d-sm-flex justify-content-center" id="gallery{{$i}}">
                <a class="a-key image{{$i}}"  data-key="{{$i}}" href="">
                    <img class="image{{$i}} w-50px input-group-lg" src="{{uploaded_asset(275)}}" alt="image">
                </a>
            </div>
        </div>
        <div class="col-1  align-items-center" style="display: flex">
            <a href="javascript:;"
               class="badge badge-danger btn btn-danger " onclick="onDelete({{$i}})" >X</a>
        </div>
    </div>


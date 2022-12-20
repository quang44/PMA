@php $i=round(rand()*99)  @endphp

<div class="row content{{$i}}">
    <div class="col-3 d-flex align-items-center">
        <label class=" col-from-label "></label>
    </div>

    <div class="col-9 row">
        <div class="col-4 form-group">
            <div class="form-group">
                <label class="col-from-label">{{translate('Province')}} / {{translate('City')}} </label>
            <select name="city[]" id="city{{$i}}" data-city="{{$i}}"  class="form-control aiz-selectpicker city"
                    data-selected-text-format="count"
                    data-live-search="true">
                <option >Không có gì được chọn</option>
                @foreach($provinces as $city)
                    <option value="{{$city->id}}">{{$city->name}}</option>
                @endforeach
            </select>
            </div>
        </div>

        <div class="col-4 form-group">
            <div class="form-group ">
                <label class="col-from-label">{{translate('District')}}</label>
            <select name="district[]" id="district{{$i}}" data-district="{{$i}}"
                    class="district form-control aiz-selectpicker" data-selected-text-format="count"
                    data-live-search="true" disabled></select>
            </div>
        </div>

        <div class="col-3 form-group">
            <div class="form-group ">
                <label class="col-from-label">{{translate('Ward')}}</label>
            <select name="ward[]" id="ward{{$i}}"  class="ward form-control aiz-selectpicker"
                    data-selected-text-format="count" data-live-search="true" disabled>
            </select>
        </div>
    </div>
    <div class="col-1  align-items-center" style="display: flex">
        <a href="javascript:;"
           class="badge badge-danger btn btn-danger" onclick="onDelete({{$i}})">X</a>
    </div>

    </div>

</div>

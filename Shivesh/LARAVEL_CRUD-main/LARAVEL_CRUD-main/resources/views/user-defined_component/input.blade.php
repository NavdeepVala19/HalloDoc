

<div class="mb-3">
    <label for="" class="form-label">{{$label}}</label>
    <input type="{{$type}}" name="{{$name}}" id="" class="form-control"/>
    
    <span class="text-danger">
        @error('name')
        {{$message}}
        @enderror
    </span>
</div>
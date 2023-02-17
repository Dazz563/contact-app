<select class="custom-select" name="company_id" id="search-select" onchange="this.form.submit()">
    <option value="" selected>All Companies</option>
    @foreach($companies as $id => $name)
        <option value="{{ $id }}"@if($id == request()->query("company_id")) selected @endIf>{{ $name }}</option>
    @endforeach
</select>

{{ csrf_field() }}
<div class="row">
<div class="medium-8 columns">
    <label>title<input type="text" name="title" /></label>
</div>
<div class="medium-4 columns">
    <label>cover<input type="file" name="cover" /></label>
    <label>cover_thumbnail<input type="file" name="cover_thumbnail" /></label>
</div>
</div>
    <textarea name="description"></textarea>
<input type="submit">
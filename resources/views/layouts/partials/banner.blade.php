<section role="banner" @isset($banner_class) class="{{ $banner_class }}" @endisset>
    <h1 class="text-center">{{ $title }}</h1>
    @isset($image)
        <img class="{{ $image_class }}" src="{{ $image }}" />
    @endisset
</section>